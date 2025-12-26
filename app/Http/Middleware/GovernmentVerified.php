<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GovernmentVerified
{
    /**
     * Handle an incoming request.
     * 
     * Ensures that only verified government users can access protected routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this area.');
        }

        $user = $request->user();

        // Check if account type is government
        if ($user->account_type !== 'government') {
            abort(403, 'This area is restricted to government entities only.');
        }

        // Check if government account is verified
        if (!$user->is_government_verified) {
            return redirect()->route('government.status')
                ->with('warning', 'Your government account is pending verification. You will be notified once approved.');
        }

        // Check if verification is still valid (if expiry system is implemented)
        if ($user->governmentRegistration && $user->governmentRegistration->verification_expiry_date) {
            if ($user->governmentRegistration->verification_expiry_date->isPast()) {
                return redirect()->route('government.status')
                    ->with('error', 'Your government verification has expired. Please contact support for renewal.');
            }
        }

        // All checks passed - allow access
        return $next($request);
    }
}
