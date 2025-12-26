<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SandboxApiKeyAuthentication
{
    /**
     * Handle an incoming request for Sandbox API
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->bearerToken();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'API key is required',
                'message' => 'Please provide API key via X-API-Key header or Authorization: Bearer token',
            ], 401);
        }

        // Check API key in database
        $keyRecord = DB::table('sandbox_api_keys')
            ->where('key', $apiKey)
            ->first();

        if (!$keyRecord) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API key',
                'message' => 'The provided API key is not valid',
            ], 401);
        }

        // Get associated sandbox instance
        $instance = DB::table('sandbox_instances')
            ->where('id', $keyRecord->sandbox_instance_id)
            ->first();

        if (!$instance) {
            return response()->json([
                'success' => false,
                'error' => 'Sandbox instance not found',
            ], 404);
        }

        // Check if instance is active
        if ($instance->status !== 'active') {
            return response()->json([
                'success' => false,
                'error' => 'Sandbox instance is not active',
                'message' => "Instance status: {$instance->status}",
            ], 403);
        }

        // Check if expired
        if ($instance->expires_at && strtotime($instance->expires_at) < time()) {
            return response()->json([
                'success' => false,
                'error' => 'Sandbox instance has expired',
                'message' => "Expired at: {$instance->expires_at}",
            ], 403);
        }

        // Attach instance and API key to request for use in controllers
        $request->attributes->set('sandboxInstance', $instance);
        $request->attributes->set('sandboxApiKey', $keyRecord);

        return $next($request);
    }
}
