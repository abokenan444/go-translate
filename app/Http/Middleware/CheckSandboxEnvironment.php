<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSandboxEnvironment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get API key from request
        $apiKey = $request->header('X-API-Key') ?? $request->input('api_key');
        
        if (!$apiKey) {
            return response()->json([
                'error' => 'API key required',
                'message' => 'Please provide a valid API key'
            ], 401);
        }

        // Find the API key
        $partnerApiKey = \App\Models\PartnerApiKey::where('api_key', $apiKey)
            ->where('is_active', true)
            ->first();

        if (!$partnerApiKey) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'The provided API key is invalid or inactive'
            ], 401);
        }

        // Check if environment matches
        $requestedEnvironment = $request->header('X-Environment') ?? 'production';
        
        if ($partnerApiKey->environment !== $requestedEnvironment) {
            return response()->json([
                'error' => 'Environment mismatch',
                'message' => "This API key is for {$partnerApiKey->environment} environment only",
                'requested' => $requestedEnvironment,
                'expected' => $partnerApiKey->environment
            ], 403);
        }

        // Attach environment info to request
        $request->merge([
            'environment' => $partnerApiKey->environment,
            'is_sandbox' => $partnerApiKey->environment === 'sandbox',
            'partner_api_key' => $partnerApiKey
        ]);

        return $next($request);
    }
}
