<?php

namespace App\Http\Controllers;

use App\Models\ReferralLink;
use App\Models\Click;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function redirect(Request $request, string $slug)
    {
        $link = ReferralLink::where('slug', $slug)->firstOrFail();

        Click::create([
            'referral_link_id' => $link->id,
            'ip' => $request->ip(),
            'user_agent' => (string) $request->header('User-Agent'),
            'country' => $request->header('CF-IPCountry') ?? null,
            'referer' => $request->header('Referer') ?? null,
            'session_id' => $request->session()->getId(),
            'clicked_at' => now(),
        ]);

        // Persist attribution cookie for 30 days
        cookie()->queue(cookie('affiliate_slug', $slug, 60 * 24 * 30));

        return redirect()->away($link->destination_url);
    }
}
