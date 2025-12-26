<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GovernmentApiRateLimiter
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $governmentId = $request->header('X-Government-ID');
        
        if (!$governmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Government ID header is required'
            ], 401);
        }

        $key = 'government_api:' . $governmentId;
        
        // 1000 requests per hour for government entities
        $maxAttempts = 1000;
        $decayMinutes = 60;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => 'Too many requests',
                'retry_after' => $seconds
            ], 429);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', $maxAttempts - $this->limiter->attempts($key));

        return $response;
    }
}
