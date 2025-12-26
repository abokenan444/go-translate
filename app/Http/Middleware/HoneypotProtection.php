<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HoneypotProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $minSeconds = 2): Response
    {
        // Skip for non-POST requests
        if (!$request->isMethod('post')) {
            return $next($request);
        }
        
        // Check honeypot field (should be empty)
        $honeypotField = $request->input('website_url'); // Hidden field
        if (!empty($honeypotField)) {
            $this->logBotDetected($request, 'honeypot_filled');
            
            return response()->json([
                'success' => false,
                'message' => __('Invalid submission detected.'),
            ], 422);
        }
        
        // Check time-to-submit (should be > minSeconds)
        $formStartTime = $request->input('form_start_time');
        if ($formStartTime) {
            $timeElapsed = time() - (int) $formStartTime;
            
            if ($timeElapsed < $minSeconds) {
                $this->logBotDetected($request, 'too_fast', $timeElapsed);
                
                return response()->json([
                    'success' => false,
                    'message' => __('Please take your time to fill the form.'),
                ], 422);
            }
            
            // Also check if too old (> 1 hour = likely bot or abandoned)
            if ($timeElapsed > 3600) {
                $this->logBotDetected($request, 'too_old', $timeElapsed);
                
                return response()->json([
                    'success' => false,
                    'message' => __('Form session expired. Please refresh and try again.'),
                ], 422);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Log bot detection
     */
    private function logBotDetected(Request $request, string $reason, ?int $timeElapsed = null): void
    {
        Log::warning('Bot detected via honeypot/timing', [
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route' => $request->path(),
            'time_elapsed' => $timeElapsed,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
