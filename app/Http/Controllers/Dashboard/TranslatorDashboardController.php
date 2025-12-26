<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TranslatorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->translatorProfile ?? null;
        
        // Get translator statistics
        $stats = [
            'total_earnings' => DB::table('payouts')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount') ?? 0,
            'pending_earnings' => DB::table('payouts')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount') ?? 0,
            'completed_jobs' => DB::table('translations')
                ->where('translator_id', $profile->id ?? 0)
                ->where('status', 'completed')
                ->count(),
            'active_jobs' => DB::table('translations')
                ->where('translator_id', $profile->id ?? 0)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
            'rating' => $profile->average_rating ?? 0,
            'reviews_count' => $profile->reviews_count ?? 0,
            'this_month_earnings' => DB::table('payouts')
                ->where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0,
        ];
        
        // Get available jobs
        $availableJobs = DB::table('job_postings')
            ->where('status', 'open')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get recent completed jobs
        $recentJobs = DB::table('translations')
            ->where('translator_id', $profile->id ?? 0)
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.translator.index', compact('user', 'profile', 'stats', 'availableJobs', 'recentJobs'));
    }
}
