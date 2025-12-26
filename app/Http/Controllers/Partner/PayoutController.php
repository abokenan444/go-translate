<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerPayout;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index()
    {
        $partner = Auth::user()->partner;

        if (!$partner) {
            abort(403, 'Partner profile not found.');
        }

        $payouts = PartnerPayout::where('partner_id', $partner->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_earned' => PartnerPayout::where('partner_id', $partner->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_amount' => PartnerPayout::where('partner_id', $partner->id)
                ->whereIn('status', ['pending', 'processing'])
                ->sum('amount'),
            'total_payouts' => PartnerPayout::where('partner_id', $partner->id)
                ->where('status', 'completed')
                ->count(),
            'current_balance' => $partner->account_balance ?? 0,
        ];

        return view('partner.payouts.index', compact('payouts', 'stats'));
    }

    public function show($id)
    {
        $partner = Auth::user()->partner;

        $payout = PartnerPayout::where('partner_id', $partner->id)
            ->findOrFail($id);

        return view('partner.payouts.show', compact('payout'));
    }
}
