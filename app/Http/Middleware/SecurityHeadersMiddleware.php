<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
        
        // More permissive CSP to allow external CDNs and scripts
        $csp = implode('; ', [
            "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob: ws: wss:",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:",
            "style-src 'self' 'unsafe-inline' https: data:",
            "img-src 'self' data: https: blob:",
            "font-src 'self' data: https:",
            "connect-src 'self' https: ws: wss: data: blob:",
            "media-src 'self' https: data: blob:",
            "object-src 'none'",
            "frame-src 'self' https:",
            "worker-src 'self' blob:",
            "form-action 'self'",
            "base-uri 'self'",
            "manifest-src 'self'"
        ]);
        
        $response->headers->set('Content-Security-Policy', $csp);
        return $response;
    }
}
