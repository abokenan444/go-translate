<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Affiliate;

class EnsureAffiliate
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

        // Check if user has an affiliate account (create one if not exists)
        $affiliate = Affiliate::where('user_id', Auth::id())->first();

        if (!$affiliate) {
            // Auto-create affiliate account for authenticated users
            $affiliate = Affiliate::create([
                'user_id' => Auth::id(),
                'affiliate_code' => 'AFF' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'status' => 'active',
            ]);
        }

        return $next($request);
    }
}
