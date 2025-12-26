<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\GovernmentVerification;
use App\Models\Document;
use App\Models\EvidenceChain;
use App\Models\DocumentClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceController extends Controller
{
    public function index()
    {
        $governmentUser = Auth::user();

        // Get verification statistics
        $stats = [
            'pending_verifications' => GovernmentVerification::where('status', 'pending')->count(),
            'approved_today' => GovernmentVerification::where('status', 'approved')
                ->whereDate('verified_at', today())
                ->count(),
            'total_documents' => Document::whereHas('governmentVerifications')
                ->count(),
            'classified_documents' => DocumentClassification::count(),
        ];

        // Recent verifications
        $recentVerifications = GovernmentVerification::with(['document', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Pending verifications
        $pendingVerifications = GovernmentVerification::with(['document', 'requestedBy'])
            ->where('status', 'pending')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('government.compliance.index', compact(
            'stats',
            'recentVerifications',
            'pendingVerifications'
        ));
    }

    public function show($id)
    {
        $verification = GovernmentVerification::with(['document', 'requestedBy', 'verifier'])
            ->findOrFail($id);

        // Get document evidence chain
        $evidenceChain = EvidenceChain::where('document_id', $verification->document_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Get document classification
        $classification = DocumentClassification::where('document_id', $verification->document_id)
            ->first();

        return view('government.compliance.show', compact(
            'verification',
            'evidenceChain',
            'classification'
        ));
    }

    public function auditTrail(Request $request)
    {
        $query = EvidenceChain::with(['document', 'performer'])
            ->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        // Filter by document
        if ($request->filled('document_id')) {
            $query->where('document_id', $request->document_id);
        }

        $auditRecords = $query->paginate(50);

        return view('government.compliance.audit-trail', compact('auditRecords'));
    }

    public function classifications()
    {
        $classifications = DocumentClassification::with(['document', 'classifier'])
            ->orderBy('security_level', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'top_secret' => DocumentClassification::where('security_level', 'top_secret')->count(),
            'secret' => DocumentClassification::where('security_level', 'secret')->count(),
            'confidential' => DocumentClassification::where('security_level', 'confidential')->count(),
            'internal' => DocumentClassification::where('security_level', 'internal')->count(),
            'public' => DocumentClassification::where('security_level', 'public')->count(),
        ];

        return view('government.compliance.classifications', compact('classifications', 'stats'));
    }

    public function reports()
    {
        return view('government.compliance.reports');
    }
}
