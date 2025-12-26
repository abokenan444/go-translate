<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class TieredRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            // Fallback to standard auth or allow if public
            return $next($request);
        }

        // Determine tier (Mock logic - normally fetched from DB/Cache)
        $tier = $this->getTier($apiKey);
        $limit = $this->getLimitForTier($tier);
        
        $key = "rate_limit:{$apiKey}";
        
        // Redis atomic increment
        $current = Redis::incr($key);
        
        if ($current === 1) {
            Redis::expire($key, 60); // 1 minute window
        }

        if ($current > $limit) {
            return response()->json([
                'error' => 'Rate limit exceeded',
                'tier' => $tier,
                'limit' => $limit,
                'retry_after' => Redis::ttl($key)
            ], 429);
        }

        $response = $next($request);

        // Add headers
        $response->headers->set('X-RateLimit-Limit', $limit);
        $response->headers->set('X-RateLimit-Remaining', max(0, $limit - $current));

        return $response;
    }

    private function getTier($apiKey)
    {
        // In production, look up API key in DB/Cache
        if (str_starts_with($apiKey, 'ent_')) return 'enterprise';
        if (str_starts_with($apiKey, 'pro_')) return 'pro';
        return 'free';
    }

    private function getLimitForTier($tier)
    {
        return match($tier) {
            'enterprise' => 10000, // 10k req/min
            'pro' => 1000,         // 1k req/min
            default => 60,         // 60 req/min
        };
    }
}
