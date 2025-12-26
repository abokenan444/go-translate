<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Government Subdomain Middleware
 * 
 * يتحقق من:
 * 1. الدخول من gov.culturaltranslate.com
 * 2. المستخدم له صلاحيات حكومية
 * 3. IP في Whitelist (اختياري)
 */
class GovernmentSubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request is from government subdomain
        $host = $request->getHost();
        $isGovernmentSubdomain = str_starts_with($host, 'gov.') || 
                                 str_starts_with($host, 'government.');

        if (!$isGovernmentSubdomain && !app()->environment('local')) {
            return response()->json([
                'error' => 'Access denied. Government subdomain required.',
                'message' => 'Please access this resource through gov.culturaltranslate.com'
            ], 403);
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('government.login')
                ->with('error', 'Please login with government credentials');
        }

        // Check if user has government account
        $user = auth()->user();
        if ($user->account_type !== 'government') {
            return response()->json([
                'error' => 'Access denied. Government account required.',
                'message' => 'You must have a verified government account to access this resource.'
            ], 403);
        }

        // Check if government profile is verified
        if (!$user->governmentProfile || !$user->governmentProfile->is_verified) {
            return redirect()->route('government.verification-pending')
                ->with('error', 'Your government account is pending verification');
        }

        // Optional: Check IP whitelist
        if (config('government.ip_whitelist_enabled')) {
            $allowedIps = config('government.allowed_ips', []);
            $clientIp = $request->ip();

            if (!empty($allowedIps) && !in_array($clientIp, $allowedIps)) {
                \Log::warning('Government access denied - IP not whitelisted', [
                    'ip' => $clientIp,
                    'user_id' => $user->id,
                    'route' => $request->path()
                ]);

                return response()->json([
                    'error' => 'Access denied. IP address not authorized.',
                    'message' => 'Your IP address is not in the government whitelist.'
                ], 403);
            }
        }

        // Log government access
        \Log::info('Government subdomain access', [
            'user_id' => $user->id,
            'government_entity' => $user->governmentProfile->entity_name,
            'route' => $request->path(),
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        // Add government context to request
        $request->merge([
            'is_government' => true,
            'government_entity' => $user->governmentProfile->entity_name,
            'government_country' => $user->governmentProfile->country
        ]);

        return $next($request);
    }
}
