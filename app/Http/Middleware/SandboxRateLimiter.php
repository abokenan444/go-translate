<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SandboxRateLimiter
{
    /**
     * Rate limits for different sandbox profiles
     */
    private const RATE_LIMITS = [
        'sandbox_basic' => [
            'per_minute' => 60,
            'per_hour' => 1000,
            'per_day' => 10000,
        ],
        'sandbox_premium' => [
            'per_minute' => 300,
            'per_hour' => 10000,
            'per_day' => 100000,
        ],
    ];

    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        $instance = $request->attributes->get('sandboxInstance');
        $apiKey = $request->attributes->get('sandboxApiKey');

        if (!$instance || !$apiKey) {
            return $next($request);
        }

        $profile = $instance->rate_limit_profile ?? 'sandbox_basic';
        $limits = self::RATE_LIMITS[$profile] ?? self::RATE_LIMITS['sandbox_basic'];

        // Check rate limits
        $keyPrefix = "sandbox:ratelimit:{$instance->id}:{$apiKey->id}";

        // Per-minute limit
        $minuteKey = "{$keyPrefix}:minute:" . now()->format('Y-m-d-H-i');
        $minuteHits = Cache::get($minuteKey, 0);

        if ($minuteHits >= $limits['per_minute']) {
            return $this->rateLimitExceeded('minute', $limits['per_minute'], 60);
        }

        // Per-hour limit
        $hourKey = "{$keyPrefix}:hour:" . now()->format('Y-m-d-H');
        $hourHits = Cache::get($hourKey, 0);

        if ($hourHits >= $limits['per_hour']) {
            return $this->rateLimitExceeded('hour', $limits['per_hour'], 3600);
        }

        // Per-day limit
        $dayKey = "{$keyPrefix}:day:" . now()->format('Y-m-d');
        $dayHits = Cache::get($dayKey, 0);

        if ($dayHits >= $limits['per_day']) {
            return $this->rateLimitExceeded('day', $limits['per_day'], 86400);
        }

        // Increment counters
        Cache::put($minuteKey, $minuteHits + 1, 60);
        Cache::put($hourKey, $hourHits + 1, 3600);
        Cache::put($dayKey, $dayHits + 1, 86400);

        // Add rate limit headers to response
        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit-Minute', $limits['per_minute']);
        $response->headers->set('X-RateLimit-Remaining-Minute', max(0, $limits['per_minute'] - $minuteHits - 1));
        $response->headers->set('X-RateLimit-Limit-Hour', $limits['per_hour']);
        $response->headers->set('X-RateLimit-Remaining-Hour', max(0, $limits['per_hour'] - $hourHits - 1));
        $response->headers->set('X-RateLimit-Limit-Day', $limits['per_day']);
        $response->headers->set('X-RateLimit-Remaining-Day', max(0, $limits['per_day'] - $dayHits - 1));

        return $response;
    }

    /**
     * Return rate limit exceeded response
     */
    private function rateLimitExceeded(string $period, int $limit, int $retryAfter): Response
    {
        return response()->json([
            'success' => false,
            'error' => 'Rate limit exceeded',
            'message' => "You have exceeded the {$period}ly rate limit of {$limit} requests",
            'retry_after_seconds' => $retryAfter,
        ], 429)
        ->header('Retry-After', $retryAfter)
        ->header('X-RateLimit-Reset', now()->addSeconds($retryAfter)->timestamp);
    }
}
