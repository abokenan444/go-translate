<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Government Session Security Middleware
 * 
 * Layer 1: Access & Identity
 * Enforces enhanced security for government and partner accounts
 */
class GovernmentSessionSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Check if user is government or partner
        $isGovernment = $user->hasRole('government');
        $isPartner = $user->hasRole('certified_partner');

        if (!$isGovernment && !$isPartner) {
            return $next($request);
        }

        // Layer 1: IP-based access restrictions for government accounts
        if ($isGovernment) {
            $this->enforceGovernmentSecurity($request, $user);
        }

        // Layer 1: Partner session security
        if ($isPartner) {
            $this->enforcePartnerSecurity($request, $user);
        }

        return $next($request);
    }

    /**
     * Enforce government account security rules
     */
    protected function enforceGovernmentSecurity(Request $request, $user): void
    {
        $config = config('session.government_security');
        $sessionKey = "government_session_{$user->id}";
        
        // Strict IP binding check
        if ($config['strict_ip_binding']) {
            $sessionIp = session()->get("{$sessionKey}_ip");
            $currentIp = $request->ip();

            if ($sessionIp && $sessionIp !== $currentIp) {
                Log::warning('Government session IP mismatch detected', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'session_ip' => $sessionIp,
                    'current_ip' => $currentIp,
                    'user_agent' => $request->userAgent(),
                ]);

                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                abort(403, 'Session security violation: IP address mismatch. Please login again.');
            }

            // Store IP on first access
            if (!$sessionIp) {
                session()->put("{$sessionKey}_ip", $currentIp);
            }
        }

        // IP whitelist check
        if (!empty($config['government_ip_whitelist'])) {
            $whitelist = explode(',', $config['government_ip_whitelist']);
            $whitelist = array_map('trim', $whitelist);
            
            $currentIp = $request->ip();
            $allowed = false;

            foreach ($whitelist as $allowedIp) {
                if ($this->ipMatches($currentIp, $allowedIp)) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                Log::warning('Government access from non-whitelisted IP', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'ip' => $currentIp,
                    'whitelist' => $whitelist,
                ]);

                abort(403, 'Access denied: Your IP address is not authorized for government access.');
            }
        }

        // Session activity logging
        if ($config['log_session_activity']) {
            Log::info('Government session activity', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Update last activity timestamp
        session()->put("{$sessionKey}_last_activity", now());
    }

    /**
     * Enforce partner session security rules
     */
    protected function enforcePartnerSecurity(Request $request, $user): void
    {
        $sessionKey = "partner_session_{$user->id}";
        
        // Track partner session activity
        session()->put("{$sessionKey}_last_activity", now());
        
        // Log partner access for audit trail
        if (config('session.government_security.log_session_activity')) {
            Log::info('Partner session activity', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }
    }

    /**
     * Check if IP matches pattern (supports CIDR notation)
     */
    protected function ipMatches(string $ip, string $pattern): bool
    {
        // Exact match
        if ($ip === $pattern) {
            return true;
        }

        // CIDR notation support (e.g., 192.168.1.0/24)
        if (str_contains($pattern, '/')) {
            [$subnet, $mask] = explode('/', $pattern);
            
            $ipLong = ip2long($ip);
            $subnetLong = ip2long($subnet);
            $maskLong = -1 << (32 - (int)$mask);
            
            return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
        }

        // Wildcard support (e.g., 192.168.1.*)
        if (str_contains($pattern, '*')) {
            $pattern = str_replace('.', '\.', $pattern);
            $pattern = str_replace('*', '.*', $pattern);
            return preg_match("/^{$pattern}$/", $ip) === 1;
        }

        return false;
    }
}
