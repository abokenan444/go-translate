<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GovernmentVerification;
use App\Models\OfficialDocument;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Government Admin Controller
 * 
 * لوحة التحكم الإدارية للجهات الحكومية
 */
class GovernmentAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'government']);
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Check if user has admin privileges
        if (!$this->hasAdminPrivileges($user)) {
            abort(403, 'Unauthorized access');
        }

        $stats = [
            'total_users' => $this->getTotalGovernmentUsers(),
            'pending_verifications' => GovernmentVerification::where('status', 'pending_verification')->count(),
            'active_documents' => OfficialDocument::whereIn('status', ['pending', 'processing'])->count(),
            'completed_this_month' => OfficialDocument::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'total_spending' => OfficialDocument::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_cost'),
        ];

        $recentVerifications = GovernmentVerification::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentDocuments = OfficialDocument::with(['user', 'translation'])
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return view('government.admin.dashboard', compact(
            'stats',
            'recentVerifications',
            'recentDocuments'
        ));
    }

    /**
     * Manage users
     */
    public function users(Request $request)
    {
        $query = User::where('role', 'government')
            ->with('governmentProfile');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(20);

        return view('government.admin.users', compact('users'));
    }

    /**
     * Manage verifications
     */
    public function verifications(Request $request)
    {
        $query = GovernmentVerification::with('user');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $verifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('government.admin.verifications', compact('verifications'));
    }

    /**
     * Approve verification
     */
    public function approveVerification(Request $request, $id)
    {
        $verification = GovernmentVerification::findOrFail($id);
        
        DB::transaction(function () use ($verification, $request) {
            $verification->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'admin_notes' => $request->notes,
            ]);

            // Update user role
            $verification->user->update([
                'role' => 'government',
                'is_verified' => true,
            ]);

            // Log activity
            $verification->addAuditEntry(
                'approved',
                'pending_verification',
                'verified',
                'Verification approved by admin',
                auth()->id()
            );
        });

        return redirect()->back()->with('success', 'Verification approved successfully');
    }

    /**
     * Reject verification
     */
    public function rejectVerification(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $verification = GovernmentVerification::findOrFail($id);
        
        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
        ]);

        // Log activity
        $verification->addAuditEntry(
            'rejected',
            'pending_verification',
            'rejected',
            'Verification rejected: ' . $request->reason,
            auth()->id()
        );

        return redirect()->back()->with('success', 'Verification rejected');
    }

    /**
     * Document management
     */
    public function documents(Request $request)
    {
        $query = OfficialDocument::with(['user', 'translation']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('document_type', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $documents = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('government.admin.documents', compact('documents'));
    }

    /**
     * Update document status
     */
    public function updateDocumentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,translated,certified,delivered',
            'notes' => 'nullable|string|max:500',
        ]);

        $document = OfficialDocument::findOrFail($id);
        
        $document->update([
            'status' => $request->status,
            'admin_notes' => $request->notes,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Document status updated');
    }

    /**
     * Reports
     */
    public function reports()
    {
        $monthlyStats = $this->getMonthlyStatistics();
        $documentTypeStats = $this->getDocumentTypeStatistics();
        $languageStats = $this->getLanguageStatistics();

        return view('government.admin.reports', compact(
            'monthlyStats',
            'documentTypeStats',
            'languageStats'
        ));
    }

    /**
     * Settings
     */
    public function settings()
    {
        $settings = [
            'verification_required' => setting('government.verification_required', true),
            'auto_approve_domains' => setting('government.auto_approve_domains', []),
            'max_upload_size' => setting('government.max_upload_size', 10),
            'priority_processing' => setting('government.priority_processing', true),
            'discount_rate' => setting('government.discount_rate', 0),
        ];

        return view('government.admin.settings', compact('settings'));
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'verification_required' => 'boolean',
            'max_upload_size' => 'integer|min:1|max:100',
            'discount_rate' => 'numeric|min:0|max:100',
        ]);

        foreach ($request->except(['_token', '_method']) as $key => $value) {
            setting()->set('government.' . $key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Helper: Check admin privileges
     */
    private function hasAdminPrivileges($user): bool
    {
        return $user->role === 'government' && 
               ($user->is_admin || $user->governmentProfile?->is_admin);
    }

    /**
     * Helper: Get total government users
     */
    private function getTotalGovernmentUsers(): int
    {
        return User::where('role', 'government')
            ->where('is_active', true)
            ->count();
    }

    /**
     * Helper: Get monthly statistics
     */
    private function getMonthlyStatistics(): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'documents' => OfficialDocument::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'revenue' => OfficialDocument::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'completed')
                    ->sum('total_cost'),
            ];
        }
        return $months;
    }

    /**
     * Helper: Get document type statistics
     */
    private function getDocumentTypeStatistics(): array
    {
        return OfficialDocument::select('document_type', DB::raw('count(*) as count'))
            ->groupBy('document_type')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Helper: Get language statistics
     */
    private function getLanguageStatistics(): array
    {
        return OfficialDocument::select('target_language', DB::raw('count(*) as count'))
            ->groupBy('target_language')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
