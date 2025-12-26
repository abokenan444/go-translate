<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureInternalAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // Check if user is admin
        $isAdmin =
            ($user->is_admin ?? false) ||
            (($user->role ?? null) === 'platform_admin') ||
            (method_exists($user, 'can') && $user->can('access_admin'));

        if (!$isAdmin) {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
