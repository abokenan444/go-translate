<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfficialDocument;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Government Dashboard Controller
 * 
 * لوحة التحكم الخاصة بالجهات الحكومية على gov.culturaltranslate.com
 */
class GovernmentDashboardController extends Controller
{
    /**
     * Display government dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $governmentProfile = $user->governmentProfile;

        // Get statistics
        $stats = $this->getStatistics($user->id);

        // Get recent documents
        $recentDocuments = OfficialDocument::where('user_id', $user->id)
            ->with(['translation', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get pending documents
        $pendingDocuments = OfficialDocument::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        return view('government.dashboard', compact(
            'governmentProfile',
            'stats',
            'recentDocuments',
            'pendingDocuments'
        ));
    }

    /**
     * Get government statistics
     */
    protected function getStatistics(int $userId): array
    {
        return [
            'total_documents' => OfficialDocument::where('user_id', $userId)->count(),
            'completed_documents' => OfficialDocument::where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'pending_documents' => OfficialDocument::where('user_id', $userId)
                ->where('status', 'pending')
                ->count(),
            'certified_documents' => OfficialDocument::where('user_id', $userId)
                ->whereHas('certificate')
                ->count(),
            'total_pages' => OfficialDocument::where('user_id', $userId)
                ->sum('page_count'),
            'total_cost' => OfficialDocument::where('user_id', $userId)
                ->sum('total_cost'),
            'this_month_documents' => OfficialDocument::where('user_id', $userId)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'avg_turnaround_time' => $this->calculateAverageTurnaroundTime($userId)
        ];
    }

    /**
     * Calculate average turnaround time
     */
    protected function calculateAverageTurnaroundTime(int $userId): float
    {
        $documents = OfficialDocument::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->select('created_at', 'completed_at')
            ->get();

        if ($documents->isEmpty()) {
            return 0;
        }

        $totalHours = $documents->sum(function ($doc) {
            return $doc->created_at->diffInHours($doc->completed_at);
        });

        return round($totalHours / $documents->count(), 1);
    }

    /**
     * Bulk document upload
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'documents' => 'required|array|min:1|max:50',
            'documents.*' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'source_language' => 'required|string',
            'target_languages' => 'required|array|min:1',
            'document_type' => 'required|string',
            'priority' => 'required|in:normal,high,urgent'
        ]);

        $results = [];
        $user = auth()->user();

        foreach ($request->file('documents') as $file) {
            try {
                $document = OfficialDocument::create([
                    'user_id' => $user->id,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $file->store('government/documents', 'private'),
                    'source_language' => $request->source_language,
                    'target_languages' => $request->target_languages,
                    'document_type' => $request->document_type,
                    'priority' => $request->priority,
                    'status' => 'pending',
                    'government_entity' => $user->governmentProfile->entity_name
                ]);

                $results[] = [
                    'success' => true,
                    'filename' => $file->getClientOriginalName(),
                    'document_id' => $document->id
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'success' => false,
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk upload completed',
            'results' => $results,
            'total' => count($results),
            'successful' => collect($results)->where('success', true)->count(),
            'failed' => collect($results)->where('success', false)->count()
        ]);
    }

    /**
     * API access for government
     */
    public function apiAccess(Request $request)
    {
        $user = auth()->user();
        
        // Get or create API key
        $apiKey = $user->apiKeys()->where('type', 'government')->first();

        if (!$apiKey) {
            $apiKey = $user->apiKeys()->create([
                'name' => 'Government API Key',
                'type' => 'government',
                'key' => 'gov_' . \Str::random(40),
                'permissions' => [
                    'documents.create',
                    'documents.read',
                    'documents.update',
                    'translations.read',
                    'certificates.read',
                    'bulk_upload'
                ],
                'rate_limit' => 1000, // 1000 requests per hour
                'expires_at' => now()->addYear()
            ]);
        }

        return view('government.api-access', compact('apiKey'));
    }

    /**
     * Webhook configuration
     */
    public function webhookConfig(Request $request)
    {
        $user = auth()->user();
        $webhooks = $user->webhooks()->where('type', 'government')->get();

        return view('government.webhooks', compact('webhooks'));
    }

    /**
     * Create webhook
     */
    public function createWebhook(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'events' => 'required|array',
            'secret' => 'required|string|min:16'
        ]);

        $user = auth()->user();

        $webhook = $user->webhooks()->create([
            'type' => 'government',
            'url' => $request->url,
            'events' => $request->events,
            'secret' => $request->secret,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook created successfully',
            'webhook' => $webhook
        ]);
    }

    /**
     * Priority processing
     */
    public function priorityProcessing(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:official_documents,id',
            'priority' => 'required|in:high,urgent'
        ]);

        $document = OfficialDocument::findOrFail($request->document_id);

        // Check ownership
        if ($document->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update priority
        $document->update([
            'priority' => $request->priority,
            'priority_updated_at' => now(),
            'priority_updated_by' => auth()->id()
        ]);

        // Notify system about priority change
        \Log::info('Government document priority changed', [
            'document_id' => $document->id,
            'new_priority' => $request->priority,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Priority updated successfully',
            'document' => $document
        ]);
    }

    /**
     * Compliance report
     */
    public function complianceReport(Request $request)
    {
        $user = auth()->user();
        
        $startDate = $request->input('start_date', now()->subMonth());
        $endDate = $request->input('end_date', now());

        $documents = OfficialDocument::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['translation', 'certificate'])
            ->get();

        $report = [
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'summary' => [
                'total_documents' => $documents->count(),
                'certified_documents' => $documents->where('certificate')->count(),
                'compliance_rate' => $this->calculateComplianceRate($documents),
                'avg_processing_time' => $this->calculateAvgProcessingTime($documents)
            ],
            'by_type' => $documents->groupBy('document_type')->map->count(),
            'by_language' => $documents->groupBy('source_language')->map->count(),
            'by_status' => $documents->groupBy('status')->map->count()
        ];

        if ($request->wantsJson()) {
            return response()->json($report);
        }

        return view('government.compliance-report', compact('report', 'documents'));
    }

    /**
     * Calculate compliance rate
     */
    protected function calculateComplianceRate($documents): float
    {
        if ($documents->isEmpty()) {
            return 0;
        }

        $compliant = $documents->filter(function ($doc) {
            return $doc->status === 'completed' && 
                   $doc->certificate !== null &&
                   $doc->created_at->diffInHours($doc->completed_at) <= 48;
        })->count();

        return round(($compliant / $documents->count()) * 100, 2);
    }

    /**
     * Calculate average processing time
     */
    protected function calculateAvgProcessingTime($documents): float
    {
        $completed = $documents->where('status', 'completed');
        
        if ($completed->isEmpty()) {
            return 0;
        }

        $totalHours = $completed->sum(function ($doc) {
            return $doc->created_at->diffInHours($doc->completed_at);
        });

        return round($totalHours / $completed->count(), 1);
    }
}
