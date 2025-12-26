<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->affiliateProfile ?? null;
        
        // Get affiliate statistics
        $stats = [
            'total_earnings' => DB::table('commissions')
                ->where('affiliate_id', $profile->id ?? 0)
                ->where('status', 'paid')
                ->sum('amount') ?? 0,
            'pending_earnings' => DB::table('commissions')
                ->where('affiliate_id', $profile->id ?? 0)
                ->where('status', 'pending')
                ->sum('amount') ?? 0,
            'total_referrals' => DB::table('referral_links')
                ->where('user_id', $user->id)
                ->sum('clicks') ?? 0,
            'conversions' => DB::table('conversions')
                ->where('affiliate_id', $profile->id ?? 0)
                ->count(),
            'commission_rate' => $profile->commission_rate ?? 10,
            'this_month_earnings' => DB::table('commissions')
                ->where('affiliate_id', $profile->id ?? 0)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0,
        ];
        
        // Get recent referrals
        $recentReferrals = DB::table('referral_links')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get API key if exists
        $apiKey = DB::table('api_keys')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();
        
        return view('dashboard.affiliate.index', compact('user', 'profile', 'stats', 'recentReferrals', 'apiKey'));
    }
}
