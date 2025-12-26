<?php

namespace App\Jobs;

use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\Payout;
use App\Events\PayoutInitiated;
use App\Events\CommissionPaid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonthlyPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $period)
    {
    }

    public function handle(): void
    {
        // Set eligible status for frozen commissions past their window
        app(\App\Services\CommissionService::class)->releaseEligible();

        $affiliates = Affiliate::where('status', 'active')->get();
        foreach ($affiliates as $affiliate) {
            $total = Commission::eligible()
                ->where('affiliate_id', $affiliate->id)
                ->sum('amount');

            if ($total <= 0) {
                continue;
            }

            $payout = Payout::create([
                'affiliate_id' => $affiliate->id,
                'amount' => $total,
                'currency' => 'USD',
                'period' => $this->period,
                'status' => 'initiated',
                'details' => [
                    'commissions' => Commission::eligible()->where('affiliate_id', $affiliate->id)->pluck('id'),
                ],
            ]);

            // Dispatch payout initiated event
            event(new PayoutInitiated($payout));

            // Mark commissions as paid
            Commission::eligible()
                ->where('affiliate_id', $affiliate->id)
                ->update(['status' => 'paid', 'paid_at' => now()]);

            // Dispatch commission paid events
            foreach (Commission::where('affiliate_id', $affiliate->id)->where('status', 'paid')->where('paid_at', '>=', now()->subSeconds(5))->get() as $commission) {
                event(new CommissionPaid($commission));
            }
        }
    }
}
