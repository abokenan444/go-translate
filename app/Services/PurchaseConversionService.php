<?php

namespace App\Services;

use App\Models\ReferralLink;
use App\Models\Conversion;
use App\Models\Affiliate;

class PurchaseConversionService
{
    public function recordPurchase(array $payload): ?Conversion
    {
        // payload: amount, currency, user_id|null, order_id, request
        $request = $payload['request'];
        $slug = $request->cookie('affiliate_slug');
        if (!$slug) {
            return null;
        }

        $link = ReferralLink::where('slug', $slug)->first();
        if (!$link) {
            return null;
        }

        $affiliate = $link->affiliate;

        $conversion = Conversion::create([
            'affiliate_id' => $affiliate->id,
            'referral_link_id' => $link->id,
            'type' => 'purchase',
            'user_id' => $payload['user_id'] ?? null,
            'order_id' => $payload['order_id'] ?? null,
            'amount' => (float) ($payload['amount'] ?? 0),
            'currency' => $payload['currency'] ?? 'USD',
            'converted_at' => now(),
            'metadata' => [
                'ip' => $request->ip(),
                'ua' => (string) $request->header('User-Agent'),
                'referer' => $request->header('Referer'),
                'session_id' => $request->session()->getId(),
            ],
        ]);

        app(CommissionService::class)->createCommission($affiliate, $conversion);
        return $conversion;
    }
}
