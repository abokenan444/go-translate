<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Partner;

class EnsureCertifiedPartner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check if user has a certified partner account
        $partner = Partner::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('is_verified', true)
            ->first();

        if (!$partner) {
            return redirect()->route('dashboard')
                ->with('error', 'Your partner account is pending verification. Please wait for admin approval or contact support.');
        }

        return $next($request);
    }
}
