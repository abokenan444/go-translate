<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authority Access Middleware
 * 
 * Enforces:
 * 1. Subdomain: authority.culturaltranslate.com
 * 2. Role: gov_authority_officer OR gov_authority_supervisor
 * 3. Verified status
 * 4. Optional: IP whitelist
 */
class EnsureAuthorityAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check subdomain (skip in local)
        $host = $request->getHost();
        $isAuthoritySubdomain = str_starts_with($host, 'authority.');

        if (!$isAuthoritySubdomain && !app()->environment('local')) {
            return response()->json([
                'error' => 'Access denied',
                'message' => 'Authority console must be accessed through authority.culturaltranslate.com'
            ], 403);
        }

        // Check authentication
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login with authority credentials');
        }

        $user = auth()->user();

        // Check role
        if (!in_array($user->account_type, ['gov_authority_officer', 'gov_authority_supervisor'])) {
            abort(403, 'Authority role required. Your account type: ' . $user->account_type);
        }

        // Check verification status
        if (!$user->is_government_verified) {
            abort(403, 'Authority account must be verified');
        }

        // Optional: Check authority profile exists
        if (!$user->authorityProfile) {
            abort(403, 'Authority profile not found');
        }

        // Optional: IP whitelist check
        if (config('government.authority_ip_whitelist_enabled', false)) {
            $allowedIps = config('government.authority_allowed_ips', []);
            if (!empty($allowedIps) && !in_array($request->ip(), $allowedIps)) {
                \Log::warning('Authority access attempt from unauthorized IP', [
                    'user_id' => $user->id,
                    'ip' => $request->ip()
                ]);
                abort(403, 'IP not whitelisted for authority access');
            }
        }

        return $next($request);
    }
}
