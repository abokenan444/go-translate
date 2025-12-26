<?php

namespace App\Listeners;

use App\Events\CommissionCreated;
use App\Events\CommissionPaid;
use App\Events\PayoutInitiated;
use App\Events\PayoutPaid;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendAffiliateWebhook
{
    public function handle($event): void
    {
        $webhookUrl = config('services.affiliate_webhook_url');
        if (!$webhookUrl) {
            return;
        }

        $payload = $this->buildPayload($event);
        if (!$payload) {
            return;
        }

        try {
            Http::timeout(10)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('Affiliate webhook failed', [
                'event' => get_class($event),
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function buildPayload($event): ?array
    {
        return match (true) {
            $event instanceof CommissionCreated => [
                'event' => 'commission.created',
                'data' => [
                    'commission_id' => $event->commission->id,
                    'affiliate_id' => $event->commission->affiliate_id,
                    'amount' => (float) $event->commission->amount,
                    'currency' => $event->commission->currency,
                    'status' => $event->commission->status,
                    'eligible_at' => $event->commission->eligible_at?->toIso8601String(),
                ],
            ],
            $event instanceof CommissionPaid => [
                'event' => 'commission.paid',
                'data' => [
                    'commission_id' => $event->commission->id,
                    'affiliate_id' => $event->commission->affiliate_id,
                    'amount' => (float) $event->commission->amount,
                    'currency' => $event->commission->currency,
                    'paid_at' => $event->commission->paid_at?->toIso8601String(),
                ],
            ],
            $event instanceof PayoutInitiated => [
                'event' => 'payout.initiated',
                'data' => [
                    'payout_id' => $event->payout->id,
                    'affiliate_id' => $event->payout->affiliate_id,
                    'amount' => (float) $event->payout->amount,
                    'currency' => $event->payout->currency,
                    'period' => $event->payout->period,
                    'status' => $event->payout->status,
                ],
            ],
            $event instanceof PayoutPaid => [
                'event' => 'payout.paid',
                'data' => [
                    'payout_id' => $event->payout->id,
                    'affiliate_id' => $event->payout->affiliate_id,
                    'amount' => (float) $event->payout->amount,
                    'currency' => $event->payout->currency,
                    'period' => $event->payout->period,
                    'paid_at' => $event->payout->paid_at?->toIso8601String(),
                ],
            ],
            default => null,
        };
    }
}
