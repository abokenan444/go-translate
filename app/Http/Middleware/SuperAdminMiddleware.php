<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('filament.super-admin.auth.login');
        }

        // Check if user has super_admin role
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Access denied. Super Admin role required.');
        }

        return $next($request);
    }
}
