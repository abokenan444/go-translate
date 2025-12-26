<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\ReferralLink;
use App\Models\Commission;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateApiController extends Controller
{
    public function me(Request $request)
    {
        $affiliate = $this->authenticate($request);
        if (!$affiliate) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id' => $affiliate->id,
            'code' => $affiliate->code,
            'name' => $affiliate->name,
            'status' => $affiliate->status,
            'current_rate' => $affiliate->current_rate,
            'country' => $affiliate->country,
            'created_at' => $affiliate->created_at,
        ]);
    }

    public function stats(Request $request)
    {
        $affiliate = $this->authenticate($request);
        if (!$affiliate) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $totalClicks = $affiliate->links()->withCount('clicks')->get()->sum('clicks_count');
        $totalConversions = $affiliate->conversions()->count();
        $totalEarnings = $affiliate->commissions()->where('status', '!=', 'void')->sum('amount');
        $eligibleEarnings = $affiliate->commissions()->where('status', 'eligible')->sum('amount');
        $paidEarnings = $affiliate->commissions()->where('status', 'paid')->sum('amount');

        return response()->json([
            'total_clicks' => $totalClicks,
            'total_conversions' => $totalConversions,
            'total_earnings' => (float) $totalEarnings,
            'eligible_earnings' => (float) $eligibleEarnings,
            'paid_earnings' => (float) $paidEarnings,
            'commission_rate' => (float) $affiliate->current_rate,
        ]);
    }

    public function links(Request $request)
    {
        $affiliate = $this->authenticate($request);
        if (!$affiliate) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $links = $affiliate->links()->withCount(['clicks', 'conversions'])->get();

        return response()->json([
            'links' => $links->map(fn($link) => [
                'id' => $link->id,
                'slug' => $link->slug,
                'url' => url('/r/' . $link->slug),
                'destination_url' => $link->destination_url,
                'clicks' => $link->clicks_count,
                'conversions' => $link->conversions_count,
                'created_at' => $link->created_at,
            ]),
        ]);
    }

    public function createLink(Request $request)
    {
        $affiliate = $this->authenticate($request);
        if (!$affiliate) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'destination_url' => 'required|url',
            'slug' => 'nullable|string|unique:referral_links,slug',
            'utm_source' => 'nullable|string',
            'utm_medium' => 'nullable|string',
            'utm_campaign' => 'nullable|string',
        ]);

        $link = ReferralLink::create([
            'affiliate_id' => $affiliate->id,
            'slug' => $validated['slug'] ?? Str::lower(Str::random(10)),
            'destination_url' => $validated['destination_url'],
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
        ]);

        return response()->json([
            'id' => $link->id,
            'slug' => $link->slug,
            'url' => url('/r/' . $link->slug),
            'destination_url' => $link->destination_url,
        ], 201);
    }

    public function payouts(Request $request)
    {
        $affiliate = $this->authenticate($request);
        if (!$affiliate) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payouts = $affiliate->payouts()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'payouts' => $payouts->map(fn($payout) => [
                'id' => $payout->id,
                'amount' => (float) $payout->amount,
                'currency' => $payout->currency,
                'period' => $payout->period,
                'status' => $payout->status,
                'paid_at' => $payout->paid_at,
                'created_at' => $payout->created_at,
            ]),
        ]);
    }

    private function authenticate(Request $request): ?Affiliate
    {
        $apiKey = $request->bearerToken();
        if (!$apiKey) {
            return null;
        }

        return Affiliate::where('api_key', $apiKey)->where('status', 'active')->first();
    }
}
