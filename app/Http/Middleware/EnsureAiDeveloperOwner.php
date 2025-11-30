<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAiDeveloperOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('ai_developer.enabled')) {
            abort(403, 'AI Developer is disabled.');
        }

        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $ownerEmail = config('ai_developer.owner_email');

        if ($user->email !== $ownerEmail) {
            abort(403, 'You are not allowed to use AI Developer System.');
        }

        return $next($request);
    }
}
