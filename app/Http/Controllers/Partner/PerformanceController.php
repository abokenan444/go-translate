<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\TranslatorPerformance;
use App\Models\PartnerPayout;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get latest performance record
        $latestPerformance = TranslatorPerformance::where('user_id', $userId)
            ->orderBy('period_end', 'desc')
            ->first();

        // Get performance history (last 12 periods)
        $performanceHistory = TranslatorPerformance::where('user_id', $userId)
            ->orderBy('period_end', 'desc')
            ->take(12)
            ->get();

        // Get current stats
        $currentStats = [
            'total_documents' => Document::where('translator_id', $userId)->count(),
            'completed_documents' => Document::where('translator_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'in_progress' => Document::where('translator_id', $userId)
                ->where('status', 'in_progress')
                ->count(),
            'pending' => Document::where('translator_id', $userId)
                ->where('status', 'assigned')
                ->count(),
        ];

        // Get recent payouts
        $recentPayouts = PartnerPayout::whereHas('partner', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('partner.performance.index', compact(
            'latestPerformance',
            'performanceHistory',
            'currentStats',
            'recentPayouts'
        ));
    }

    public function details($periodId)
    {
        $performance = TranslatorPerformance::where('user_id', Auth::id())
            ->findOrFail($periodId);

        // Get documents from this period
        $documents = Document::where('translator_id', Auth::id())
            ->whereBetween('created_at', [$performance->period_start, $performance->period_end])
            ->with(['sourceLanguage', 'targetLanguage'])
            ->get();

        return view('partner.performance.details', compact('performance', 'documents'));
    }
}
