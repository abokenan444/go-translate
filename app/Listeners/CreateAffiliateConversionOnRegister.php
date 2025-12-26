<?php

namespace App\Listeners;

use App\Models\Affiliate;
use App\Models\ReferralLink;
use App\Models\Conversion;
use App\Services\CommissionService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class CreateAffiliateConversionOnRegister
{
    public function handle(Registered $event): void
    {
        $request = request();
        $slug = $request->cookie('affiliate_slug');
        if (!$slug) {
            return;
        }

        $link = ReferralLink::where('slug', $slug)->first();
        if (!$link) {
            return;
        }

        $affiliate = $link->affiliate;
        $user = $event->user;

        $conversion = Conversion::create([
            'affiliate_id' => $affiliate->id,
            'referral_link_id' => $link->id,
            'type' => 'signup',
            'user_id' => $user->id,
            'order_id' => null,
            'amount' => 0,
            'currency' => 'USD',
            'converted_at' => now(),
            'metadata' => [
                'ip' => $request->ip(),
                'ua' => (string) $request->header('User-Agent'),
                'referer' => $request->header('Referer'),
                'session_id' => $request->session()->getId(),
            ],
        ]);

        // Optional: award a small signup commission if business rules allow
        if ($affiliate->current_rate > 0) {
            app(CommissionService::class)->createCommission($affiliate, $conversion);
        }
    }
}
