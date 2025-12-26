<?php

namespace App\Listeners;

use App\Events\CommissionCreated;
use App\Events\CommissionPaid;
use App\Events\PayoutInitiated;
use App\Events\PayoutPaid;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;

class SendWebhookNotification
{
    public function handle($event): void
    {
        $eventName = $this->getEventName($event);
        $payload = $this->buildPayload($event);

        $endpoints = WebhookEndpoint::where('active', true)
            ->where(function ($q) use ($eventName) {
                $q->whereJsonContains('events', $eventName)
                  ->orWhereNull('events');
            })
            ->get();

        foreach ($endpoints as $endpoint) {
            $this->sendWebhook($endpoint, $eventName, $payload);
        }
    }

    private function sendWebhook(WebhookEndpoint $endpoint, string $event, array $payload): void
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Webhook-Event' => $event,
                    'X-Webhook-Signature' => $this->sign($payload, $endpoint->secret),
                ])
                ->post($endpoint->url, $payload);

            WebhookLog::create([
                'webhook_endpoint_id' => $endpoint->id,
                'event' => $event,
                'payload' => $payload,
                'response_code' => $response->status(),
                'response_body' => $response->body(),
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            WebhookLog::create([
                'webhook_endpoint_id' => $endpoint->id,
                'event' => $event,
                'payload' => $payload,
                'response_code' => 0,
                'response_body' => $e->getMessage(),
                'sent_at' => now(),
            ]);
        }
    }

    private function getEventName($event): string
    {
        return match (get_class($event)) {
            \App\Events\CommissionCreated::class => 'commission.created',
            \App\Events\CommissionPaid::class => 'commission.paid',
            \App\Events\PayoutInitiated::class => 'payout.initiated',
            \App\Events\PayoutPaid::class => 'payout.paid',
            default => 'unknown',
        };
    }

    private function buildPayload($event): array
    {
        if ($event instanceof CommissionCreated || $event instanceof CommissionPaid) {
            $commission = $event->commission;
            return [
                'id' => $commission->id,
                'affiliate_id' => $commission->affiliate_id,
                'amount' => (float) $commission->amount,
                'currency' => $commission->currency,
                'status' => $commission->status,
                'rate' => (float) $commission->rate,
                'eligible_at' => $commission->eligible_at?->toIso8601String(),
                'paid_at' => $commission->paid_at?->toIso8601String(),
                'created_at' => $commission->created_at->toIso8601String(),
            ];
        }

        if ($event instanceof PayoutInitiated || $event instanceof PayoutPaid) {
            $payout = $event->payout;
            return [
                'id' => $payout->id,
                'affiliate_id' => $payout->affiliate_id,
                'amount' => (float) $payout->amount,
                'currency' => $payout->currency,
                'period' => $payout->period,
                'status' => $payout->status,
                'paid_at' => $payout->paid_at?->toIso8601String(),
                'created_at' => $payout->created_at->toIso8601String(),
            ];
        }

        return [];
    }

    private function sign(array $payload, ?string $secret): string
    {
        if (!$secret) {
            return '';
        }
        return hash_hmac('sha256', json_encode($payload), $secret);
    }
}
