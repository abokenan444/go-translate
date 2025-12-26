<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackRequestMetrics
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking if monitoring is disabled or Redis not available
        if (!config('monitoring.enabled')) {
            return $next($request);
        }

        // Skip tracking for mobile API to avoid Redis dependency
        if ($request->is('api/mobile/*')) {
            return $next($request);
        }

        $start = microtime(true);

        try {
            $response = $next($request);
            
            $duration = (microtime(true) - $start) * 1000; // Convert to milliseconds
            
            // Track successful request (wrapped in try-catch to prevent Redis errors)
            try {
                app(\App\Services\RealTimeMonitoringService::class)->trackRequest(true);
                app(\App\Services\RealTimeMonitoringService::class)->trackMetric(
                    'response_time',
                    $duration,
                    [
                        'route' => $request->route()?->getName(),
                        'method' => $request->method(),
                    ]
                );
            } catch (\Exception $e) {
                // Silently ignore tracking errors
            }

            return $response;
        } catch (\Exception $e) {
            // Track failed request
            try {
                app(\App\Services\RealTimeMonitoringService::class)->trackRequest(false);
            } catch (\Exception $ex) {
                // Silently ignore tracking errors
            }
            throw $e;
        }
    }
}
