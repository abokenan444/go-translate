<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get real statistics
        $stats = [
            'total_translations' => DB::table('translations')->where('user_id', $user->id)->count(),
            'active_orders' => DB::table('translations')->where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed' => DB::table('translations')->where('user_id', $user->id)->where('status', 'completed')->count(),
            'characters_used' => DB::table('translations')->where('user_id', $user->id)->sum('characters_count') ?? 0,
            'this_month' => DB::table('translations')
                ->where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        // Get recent translations
        $recentTranslations = DB::table('translations')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get subscription info
        $subscription = DB::table('user_subscriptions')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
        
        return view('dashboard.customer.index', compact('user', 'stats', 'recentTranslations', 'subscription'));
    }
}
