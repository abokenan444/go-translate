<?php

namespace App\Services\LiveCall;

use App\Models\LiveCallSession;
use App\Models\LiveCallUsage;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class LiveCallBillingService
{
    /**
     * Bill processing seconds in chunks (e.g., every 60 sec).
     * We bill per-minute snapshots with rounding rules.
     */
    public function billSeconds(LiveCallSession $session, int $secondsToBill, int $userId): void
    {
        if ($secondsToBill <= 0) return;

        DB::transaction(function () use ($session, $secondsToBill, $userId) {
            $wallet = Wallet::firstOrCreate(['user_id' => $userId]);

            // Convert seconds to billable minutes with rounding:
            // - Round up after 30 seconds
            // - Minimum 1 minute if >= 1 second
            $minutes = $this->secondsToBillableMinutes($secondsToBill);

            $cost = $minutes * (float)$session->price_per_minute_snapshot;

            if ($session->billing_mode === 'prepaid') {
                if ($wallet->minutes_balance < $minutes) {
                    // Not enough minutes => caller should end call
                    throw new \RuntimeException('INSUFFICIENT_MINUTES');
                }
                $wallet->decrement('minutes_balance', $minutes);
            } else {
                // pay-as-you-go: enforce cap if set
                if (!$wallet->payg_enabled) {
                    throw new \RuntimeException('PAYG_NOT_ENABLED');
                }
                // Optional: implement monthly cap usage in another table
            }

            LiveCallUsage::create([
                'session_id' => $session->id,
                'user_id' => $userId,
                'seconds_processed' => $secondsToBill,
                'cost_snapshot' => $cost,
            ]);

            $session->increment('billed_seconds', $secondsToBill);
        });
    }

    private function secondsToBillableMinutes(int $seconds): int
    {
        if ($seconds <= 0) return 0;
        if ($seconds <= 30) return 1;
        // ceil(seconds/60) but with 30-sec rounding:
        // 31..90 => 2, 91..150 => 3 ...
        return (int) ceil(($seconds - 30) / 60) + 1;
    }
}
