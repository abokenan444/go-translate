<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PartnerWebhook;
use App\Models\PartnerWebhookLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Trigger webhook for specific event
     */
    public function trigger(Partner $partner, string $event, array $data): void
    {
        $webhooks = PartnerWebhook::where('partner_id', $partner->id)
            ->where('is_active', true)
            ->get();

        foreach ($webhooks as $webhook) {
            if ($webhook->hasEvent($event)) {
                $this->send($webhook, $event, $data);
            }
        }
    }

    /**
     * Send webhook request
     */
    protected function send(PartnerWebhook $webhook, string $event, array $data): void
    {
        $payload = [
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ];

        // Add signature if secret is set
        $headers = ['Content-Type' => 'application/json'];
        if ($webhook->secret) {
            $signature = hash_hmac('sha256', json_encode($payload), $webhook->secret);
            $headers['X-Webhook-Signature'] = $signature;
        }

        $startTime = microtime(true);
        
        try {
            $response = Http::timeout(10)
                ->withHeaders($headers)
                ->post($webhook->url, $payload);

            $responseTime = (int)((microtime(true) - $startTime) * 1000);
            $success = $response->successful();

            // Log webhook call
            PartnerWebhookLog::create([
                'webhook_id' => $webhook->id,
                'event_type' => $event,
                'payload' => $payload,
                'response' => $response->json(),
                'status_code' => $response->status(),
                'response_time' => $responseTime,
                'success' => $success,
            ]);

            // Update webhook stats
            $webhook->update([
                'last_triggered_at' => now(),
                'last_success_at' => $success ? now() : $webhook->last_success_at,
                'failure_count' => $success ? 0 : $webhook->failure_count + 1,
            ]);

            // Disable webhook after 5 consecutive failures
            if ($webhook->failure_count >= 5) {
                $webhook->update(['is_active' => false]);
                Log::warning('Webhook disabled due to failures', ['webhook_id' => $webhook->id]);
            }

        } catch (\Exception $e) {
            $responseTime = (int)((microtime(true) - $startTime) * 1000);

            PartnerWebhookLog::create([
                'webhook_id' => $webhook->id,
                'event_type' => $event,
                'payload' => $payload,
                'response_time' => $responseTime,
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);

            $webhook->update([
                'last_triggered_at' => now(),
                'last_failure_at' => now(),
                'failure_count' => $webhook->failure_count + 1,
            ]);

            Log::error('Webhook failed', [
                'webhook_id' => $webhook->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create new webhook
     */
    public function create(Partner $partner, array $data): PartnerWebhook
    {
        return PartnerWebhook::create([
            'partner_id' => $partner->id,
            'url' => $data['url'],
            'events' => $data['events'],
            'secret' => $data['secret'] ?? null,
        ]);
    }

    /**
     * Delete webhook
     */
    public function delete(PartnerWebhook $webhook): bool
    {
        return $webhook->delete();
    }

    /**
     * Test webhook
     */
    public function test(PartnerWebhook $webhook): array
    {
        $testPayload = [
            'event' => 'test',
            'timestamp' => now()->toIso8601String(),
            'data' => ['message' => 'This is a test webhook'],
        ];

        try {
            $response = Http::timeout(10)->post($webhook->url, $testPayload);
            
            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
