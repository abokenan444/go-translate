<?php

namespace App\Services;

use App\Models\Click;
use App\Models\Conversion;
use Illuminate\Http\Request;

class FraudDetectionService
{
    // Check for duplicate clicks from same IP within short window
    public function isDuplicateClick(string $linkId, Request $request): bool
    {
        $ip = $request->ip();
        $window = now()->subMinutes(5);

        $recentClicks = Click::where('referral_link_id', $linkId)
            ->where('ip', $ip)
            ->where('clicked_at', '>=', $window)
            ->count();

        return $recentClicks > 0;
    }

    // Check for suspicious conversion patterns
    public function isSuspiciousConversion(string $affiliateId, Request $request): bool
    {
        $ip = $request->ip();
        $sessionId = $request->session()->getId();

        // Check for multiple conversions from same IP/session in 24h
        $recentConversions = Conversion::where('affiliate_id', $affiliateId)
            ->where('converted_at', '>=', now()->subDay())
            ->whereJsonContains('metadata->ip', $ip)
            ->count();

        if ($recentConversions > 3) {
            return true;
        }

        // Check for rapid conversions (velocity check)
        $lastConversion = Conversion::where('affiliate_id', $affiliateId)
            ->where('converted_at', '>=', now()->subMinutes(2))
            ->exists();

        return $lastConversion;
    }

    // Get fraud score (0-100)
    public function getFraudScore(string $affiliateId, Request $request): int
    {
        $score = 0;
        $ip = $request->ip();

        // Check IP reputation
        $ipConversions = Conversion::whereJsonContains('metadata->ip', $ip)
            ->where('converted_at', '>=', now()->subDays(7))
            ->count();

        if ($ipConversions > 10) {
            $score += 40;
        } elseif ($ipConversions > 5) {
            $score += 20;
        }

        // Check user agent
        $ua = (string) $request->header('User-Agent');
        if (empty($ua) || strlen($ua) < 20) {
            $score += 30;
        }

        // Check referer
        if (!$request->header('Referer')) {
            $score += 10;
        }

        // Check conversion velocity
        $recentCount = Conversion::where('affiliate_id', $affiliateId)
            ->where('converted_at', '>=', now()->subHour())
            ->count();

        if ($recentCount > 5) {
            $score += 20;
        }

        return min($score, 100);
    }
}
