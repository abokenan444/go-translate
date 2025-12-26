<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\Commission;
use App\Events\CommissionCreated;

class CommissionService
{
    // Calculate commission with freeze window (30 days) and current rate
    public function createCommission(Affiliate $affiliate, Conversion $conversion): Commission
    {
        $rate = (float) $affiliate->current_rate;
        $amount = round(((float) $conversion->amount) * ($rate / 100), 2);

        $commission = Commission::create([
            'affiliate_id' => $affiliate->id,
            'conversion_id' => $conversion->id,
            'rate' => $rate,
            'amount' => $amount,
            'currency' => $conversion->currency,
            'status' => 'frozen',
            'eligible_at' => now()->addDays(30),
            'metadata' => [
                'type' => $conversion->type,
                'order_id' => $conversion->order_id,
            ],
        ]);

        // Dispatch event for webhooks
        event(new CommissionCreated($commission));

        return $commission;
    }

    // Mark commissions eligible after freeze
    public function releaseEligible(): int
    {
        return Commission::where('status', 'frozen')
            ->where('eligible_at', '<=', now())
            ->update(['status' => 'eligible']);
    }
}
