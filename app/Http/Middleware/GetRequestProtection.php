<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GetRequestProtection
{
    /**
     * Protected GET routes with their limits
     */
    private array $protectedRoutes = [
        '/' => ['max' => 60, 'decay' => 1], // 60 requests per minute
        '/pricing' => ['max' => 30, 'decay' => 1],
        '/partners' => ['max' => 30, 'decay' => 1],
        '/contact' => ['max' => 30, 'decay' => 1],
        '/enterprise' => ['max' => 20, 'decay' => 1],
        '/api-docs' => ['max' => 20, 'decay' => 1],
        '/certified-translation' => ['max' => 30, 'decay' => 1],
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only for GET requests
        if (!$request->isMethod('get')) {
            return $next($request);
        }
        
        $path = $request->path();
        
        // Check if this route is protected
        $config = $this->getRouteConfig($path);
        if (!$config) {
            return $next($request);
        }
        
        // Generate key
        $key = $this->generateKey($request, $path);
        
        $attempts = (int) Cache::get($key, 0);
        $maxAttempts = $config['max'];
        $decayMinutes = $config['decay'];
        
        // Check if limit exceeded
        if ($attempts >= $maxAttempts) {
            $this->logGetFlood($request, $path, $attempts);
            
            // Return 429 with retry-after header
            return response()->view('errors.429', [
                'message' => __('Too many requests. Please slow down.'),
                'retry_after' => $decayMinutes * 60,
            ], 429)
            ->header('Retry-After', $decayMinutes * 60);
        }
        
        // Increment attempts
        if ($attempts === 0) {
            Cache::put($key, 1, now()->addMinutes($decayMinutes));
        } else {
            Cache::increment($key);
        }
        
        return $next($request);
    }
    
    /**
     * Get route configuration
     */
    private function getRouteConfig(string $path): ?array
    {
        // Exact match
        if (isset($this->protectedRoutes[$path])) {
            return $this->protectedRoutes[$path];
        }
        
        // Prefix match
        foreach ($this->protectedRoutes as $route => $config) {
            if (str_starts_with($path, $route)) {
                return $config;
            }
        }
        
        return null;
    }
    
    /**
     * Generate cache key
     */
    private function generateKey(Request $request, string $path): string
    {
        $ip = $request->ip();
        $pathKey = str_replace('/', '_', $path);
        
        return "get_limit:{$ip}:{$pathKey}";
    }
    
    /**
     * Log GET flood attempt
     */
    private function logGetFlood(Request $request, string $path, int $attempts): void
    {
        Log::warning('GET request flood detected', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'path' => $path,
            'attempts' => $attempts,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
