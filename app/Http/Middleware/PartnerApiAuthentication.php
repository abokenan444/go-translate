<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PartnerApiKey;
use App\Models\PartnerApiLog;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PartnerApiAuthentication
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Get API credentials from headers
        $apiKey = $request->header('Authorization');
        $apiSecret = $request->header('X-API-Secret');
        $partnerId = $request->header('X-Partner-ID');
        
        // Remove "Bearer " prefix if present
        if (str_starts_with($apiKey, 'Bearer ')) {
            $apiKey = substr($apiKey, 7);
        }
        
        // Validate required headers
        if (!$apiKey || !$apiSecret || !$partnerId) {
            return $this->errorResponse(
                'MISSING_CREDENTIALS',
                'API key, secret, and partner ID are required',
                401
            );
        }
        
        // Find API key (with caching)
        $cacheKey = "partner_api_key:{$apiKey}";
        $partnerApiKey = Cache::remember($cacheKey, 300, function () use ($apiKey) {
            return PartnerApiKey::where('api_key', $apiKey)
                ->where('is_active', true)
                ->with('partner')
                ->first();
        });
        
        if (!$partnerApiKey) {
            $this->logApiRequest($request, null, 401, $startTime);
            return $this->errorResponse(
                'INVALID_API_KEY',
                'The provided API key is invalid or inactive',
                401
            );
        }
        
        // Verify partner ID matches
        if ($partnerApiKey->partner_id != $partnerId) {
            $this->logApiRequest($request, $partnerApiKey->id, 403, $startTime);
            return $this->errorResponse(
                'PARTNER_MISMATCH',
                'Partner ID does not match the API key',
                403
            );
        }
        
        // Verify API secret
        if (!hash_equals(decrypt($partnerApiKey->api_secret), $apiSecret)) {
            $this->logApiRequest($request, $partnerApiKey->id, 401, $startTime);
            return $this->errorResponse(
                'INVALID_SECRET',
                'The provided API secret is incorrect',
                401
            );
        }
        
        // Check if expired
        if ($partnerApiKey->expires_at && $partnerApiKey->expires_at < now()) {
            $this->logApiRequest($request, $partnerApiKey->id, 401, $startTime);
            return $this->errorResponse(
                'EXPIRED_API_KEY',
                'The API key has expired',
                401
            );
        }
        
        // Rate limiting
        $rateLimitKey = "api_rate_limit:{$partnerApiKey->id}";
        $requestCount = Cache::get($rateLimitKey, 0);
        
        if ($requestCount >= $partnerApiKey->rate_limit) {
            $this->logApiRequest($request, $partnerApiKey->id, 429, $startTime);
            return $this->errorResponse(
                'RATE_LIMIT_EXCEEDED',
                "Rate limit of {$partnerApiKey->rate_limit} requests per minute exceeded",
                429,
                [
                    'limit' => $partnerApiKey->rate_limit,
                    'reset_at' => now()->addMinute()->toIso8601String()
                ]
            );
        }
        
        // Increment rate limit counter
        Cache::put($rateLimitKey, $requestCount + 1, 60);
        
        // Update last used timestamp
        $partnerApiKey->update(['last_used_at' => now()]);
        
        // Attach to request for use in controllers
        $request->merge([
            'partner' => $partnerApiKey->partner,
            'partner_api_key' => $partnerApiKey,
        ]);
        
        $response = $next($request);
        
        // Log successful request
        $this->logApiRequest($request, $partnerApiKey->id, $response->getStatusCode(), $startTime);
        
        // Add rate limit headers to response
        $response->headers->set('X-RateLimit-Limit', $partnerApiKey->rate_limit);
        $response->headers->set('X-RateLimit-Remaining', max(0, $partnerApiKey->rate_limit - $requestCount - 1));
        $response->headers->set('X-RateLimit-Reset', now()->addMinute()->timestamp);
        
        return $response;
    }
    
    private function logApiRequest(Request $request, ?int $apiKeyId, int $responseCode, float $startTime): void
    {
        $responseTime = (int)((microtime(true) - $startTime) * 1000);
        
        PartnerApiLog::create([
            'partner_id' => $request->header('X-Partner-ID'),
            'partner_api_key_id' => $apiKeyId,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'request_data' => $request->except(['password', 'api_secret']),
            'response_code' => $responseCode,
            'response_time' => $responseTime,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
    
    private function errorResponse(string $code, string $message, int $status, array $details = []): Response
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ], $status);
    }
}
