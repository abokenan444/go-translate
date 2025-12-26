<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:partner_users');
    }

    public function index()
    {
        $partner = auth('partner_users')->user()->partner;
        
        $stats = [
            'total_orders' => $partner->orders()->count(),
            'total_revenue' => $partner->orders()->where('status', 'completed')->sum('price'),
            'total_commission' => $partner->commissions()->where('type', 'earned')->sum('amount'),
            'pending_payout' => $partner->balance,
        ];

        $recent_orders = $partner->orders()->latest()->take(10)->get();

        return view('partners.dashboard', compact('partner', 'stats', 'recent_orders'));
    }

    public function apiKeys()
    {
        $partner = auth('partner_users')->user()->partner;
        return view('partners.api-keys', compact('partner'));
    }

    public function regenerateApiKey(Request $request)
    {
        $partner = auth('partner_users')->user()->partner;
        
        $partner->update([
            'api_key' => 'pk_' . \Str::random(32),
            'api_secret' => 'sk_' . \Str::random(64),
        ]);

        return back()->with('success', 'API keys regenerated successfully!');
    }
}
