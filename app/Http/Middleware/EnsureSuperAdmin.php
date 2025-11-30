<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'super_admin') {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        return $next($request);
    }
}
