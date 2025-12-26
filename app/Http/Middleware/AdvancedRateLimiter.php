<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdvancedRateLimiter
{
    /**
     * Handle an incoming request with advanced rate limiting.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $maxAttempts = '5', string $decayMinutes = '10'): Response
    {
        // Generate composite key: IP + User-Agent + Route
        $key = $this->generateCompositeKey($request);
        
        $attempts = (int) Cache::get($key, 0);
        $maxAttempts = (int) $maxAttempts;
        $decayMinutes = (int) $decayMinutes;
        
        // Check if limit exceeded
        if ($attempts >= $maxAttempts) {
            $this->logRateLimitExceeded($request, $key, $attempts);
            
            return response()->json([
                'success' => false,
                'message' => __('Too many requests. Please try again later.'),
                'retry_after' => $decayMinutes * 60,
            ], 429);
        }
        
        // Increment attempts
        if ($attempts === 0) {
            Cache::put($key, 1, now()->addMinutes($decayMinutes));
        } else {
            Cache::increment($key);
        }
        
        // Add rate limit headers
        $response = $next($request);
        
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxAttempts - $attempts - 1));
        $response->headers->set('X-RateLimit-Reset', now()->addMinutes($decayMinutes)->timestamp);
        
        return $response;
    }
    
    /**
     * Generate composite key for rate limiting
     */
    private function generateCompositeKey(Request $request): string
    {
        $ip = $request->ip();
        $userAgent = substr(md5($request->userAgent() ?? 'unknown'), 0, 8);
        $route = $request->route() ? $request->route()->getName() ?? $request->path() : $request->path();
        
        return "rate_limit:{$ip}:{$userAgent}:{$route}";
    }
    
    /**
     * Log rate limit exceeded event
     */
    private function logRateLimitExceeded(Request $request, string $key, int $attempts): void
    {
        Log::warning('Rate limit exceeded', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route' => $request->path(),
            'method' => $request->method(),
            'key' => $key,
            'attempts' => $attempts,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
