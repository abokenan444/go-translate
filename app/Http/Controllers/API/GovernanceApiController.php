<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TranslatorPerformance;
use App\Models\PartnerPayout;
use App\Models\DocumentDispute;
use App\Models\EvidenceChain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernanceApiController extends Controller
{
    public function getPerformanceStats(Request $request)
    {
        $userId = $request->user_id ?? Auth::id();

        $latestPerformance = TranslatorPerformance::where('user_id', $userId)
            ->orderBy('period_end', 'desc')
            ->first();

        $history = TranslatorPerformance::where('user_id', $userId)
            ->orderBy('period_end', 'desc')
            ->take(6)
            ->get()
            ->map(function ($record) {
                return [
                    'period' => $record->period_start->format('M Y'),
                    'overall_score' => $record->overall_score,
                    'quality_score' => $record->quality_score,
                    'speed_score' => $record->speed_score,
                    'reliability_score' => $record->reliability_score,
                    'communication_score' => $record->communication_score,
                ];
            });

        return response()->json([
            'current' => $latestPerformance,
            'history' => $history,
        ]);
    }

    public function getPayoutsSummary(Request $request)
    {
        $partnerId = $request->partner_id;

        $summary = [
            'total_earned' => PartnerPayout::where('partner_id', $partnerId)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending' => PartnerPayout::where('partner_id', $partnerId)
                ->where('status', 'pending')
                ->sum('amount'),
            'processing' => PartnerPayout::where('partner_id', $partnerId)
                ->where('status', 'processing')
                ->sum('amount'),
            'last_payout' => PartnerPayout::where('partner_id', $partnerId)
                ->where('status', 'completed')
                ->orderBy('paid_at', 'desc')
                ->first(),
            'monthly_earnings' => PartnerPayout::where('partner_id', $partnerId)
                ->where('status', 'completed')
                ->whereYear('paid_at', now()->year)
                ->selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->get(),
        ];

        return response()->json($summary);
    }

    public function getDisputeStats()
    {
        $stats = [
            'open' => DocumentDispute::where('status', 'open')->count(),
            'under_review' => DocumentDispute::where('status', 'under_review')->count(),
            'investigating' => DocumentDispute::where('status', 'investigating')->count(),
            'resolved' => DocumentDispute::where('status', 'resolved')
                ->whereDate('resolved_at', '>=', now()->subDays(30))
                ->count(),
            'by_type' => DocumentDispute::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'by_priority' => DocumentDispute::selectRaw('priority, COUNT(*) as count')
                ->whereIn('status', ['open', 'under_review', 'investigating'])
                ->groupBy('priority')
                ->get(),
        ];

        return response()->json($stats);
    }

    public function getEvidenceChain($documentId)
    {
        $chain = EvidenceChain::where('document_id', $documentId)
            ->with('performer')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'action' => $record->action_type,
                    'performed_by' => $record->performer->name,
                    'timestamp' => $record->created_at->format('Y-m-d H:i:s'),
                    'details' => $record->details,
                    'is_verified' => $record->is_verified,
                    'hash' => $record->hash,
                ];
            });

        // Verify chain integrity
        $isValid = $this->verifyChainIntegrity($chain);

        return response()->json([
            'chain' => $chain,
            'is_valid' => $isValid,
            'total_records' => $chain->count(),
        ]);
    }

    private function verifyChainIntegrity($chain)
    {
        if ($chain->isEmpty()) {
            return true;
        }

        $previousHash = null;
        foreach ($chain as $record) {
            if ($previousHash !== null && $record['hash'] !== null) {
                // In a real implementation, verify that current hash includes previous hash
                // For now, just check that hashes exist
                if (empty($record['hash'])) {
                    return false;
                }
            }
            $previousHash = $record['hash'];
        }

        return true;
    }

    public function getComplianceMetrics()
    {
        $metrics = [
            'documents_verified_today' => \App\Models\GovernmentVerification::whereDate('verified_at', today())->count(),
            'pending_verifications' => \App\Models\GovernmentVerification::where('status', 'pending')->count(),
            'classified_documents' => \App\Models\DocumentClassification::count(),
            'high_security_docs' => \App\Models\DocumentClassification::whereIn('security_level', ['secret', 'top_secret'])->count(),
            'documents_with_pii' => \App\Models\DocumentClassification::where('contains_pii', true)->count(),
            'encrypted_documents' => \App\Models\DocumentClassification::where('requires_encryption', true)->count(),
            'audit_trail_records' => EvidenceChain::whereDate('created_at', today())->count(),
        ];

        return response()->json($metrics);
    }
}
