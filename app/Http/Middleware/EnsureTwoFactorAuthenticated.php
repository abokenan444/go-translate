<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user has 2FA enabled but hasn't verified this session
        if ($user && $user->two_factor_secret && !session('two_factor_verified')) {
            // Store user in session for verification
            session(['two_factor_login_user' => $user]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '2FA verification required',
                    'requires_2fa' => true,
                ], 403);
            }

            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
