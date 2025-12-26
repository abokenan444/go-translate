<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\University;

class EnsureVerifiedUniversity
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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this area.');
        }

        $university = University::where('user_id', Auth::id())
            ->where('is_verified', true)
            ->where('status', 'active')
            ->first();

        if (!$university) {
            return redirect()->route('home')->with('error', 'Your university account is not verified or is inactive.');
        }

        return $next($request);
    }
}
