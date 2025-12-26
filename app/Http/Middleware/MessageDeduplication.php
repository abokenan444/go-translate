<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MessageDeduplication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $cooldownMinutes = 30): Response
    {
        // Skip for non-POST requests
        if (!$request->isMethod('post')) {
            return $next($request);
        }
        
        // Generate message hash from key fields
        $messageHash = $this->generateMessageHash($request);
        
        // Check if this exact message was submitted recently
        if (Cache::has($messageHash)) {
            $this->logDuplicateDetected($request, $messageHash);
            
            return response()->json([
                'success' => false,
                'message' => __('This message was already submitted recently. Please wait before submitting again.'),
                'cooldown_minutes' => $cooldownMinutes,
            ], 429);
        }
        
        // Check email/phone cooldown
        $emailOrPhone = $request->input('email') ?? $request->input('phone') ?? $request->input('contact_email');
        if ($emailOrPhone) {
            $contactKey = "contact_cooldown:" . md5(strtolower(trim($emailOrPhone)));
            
            if (Cache::has($contactKey)) {
                $this->logCooldownViolation($request, $emailOrPhone);
                
                return response()->json([
                    'success' => false,
                    'message' => __('Please wait before submitting another request from this contact.'),
                    'cooldown_minutes' => $cooldownMinutes,
                ], 429);
            }
            
            // Set contact cooldown
            Cache::put($contactKey, true, now()->addMinutes($cooldownMinutes));
        }
        
        // Store message hash
        Cache::put($messageHash, true, now()->addMinutes($cooldownMinutes));
        
        return $next($request);
    }
    
    /**
     * Generate unique hash for message content
     */
    private function generateMessageHash(Request $request): string
    {
        $fields = [
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'contact_email' => $request->input('contact_email'),
            'message' => $request->input('message'),
            'description' => $request->input('description'),
            'company_name' => $request->input('company_name'),
            'subject' => $request->input('subject'),
        ];
        
        // Filter out empty values and normalize
        $fields = array_filter($fields, fn($value) => !empty($value));
        $fields = array_map(fn($value) => strtolower(trim($value)), $fields);
        
        // Create hash
        $content = implode('|', $fields);
        return "message_hash:" . md5($content);
    }
    
    /**
     * Log duplicate message detection
     */
    private function logDuplicateDetected(Request $request, string $hash): void
    {
        Log::warning('Duplicate message detected', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route' => $request->path(),
            'hash' => $hash,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
    
    /**
     * Log contact cooldown violation
     */
    private function logCooldownViolation(Request $request, string $contact): void
    {
        Log::warning('Contact cooldown violated', [
            'ip' => $request->ip(),
            'contact' => $contact,
            'route' => $request->path(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
