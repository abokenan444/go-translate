<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountType
{
    public function handle(Request $request, Closure $next, string $accountType): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userAccountType = auth()->user()->account_type;

        // 'individual' is an alias for 'customer' (backward compatibility)
        $allowedTypes = [$accountType];
        if ($accountType === 'customer') {
            $allowedTypes[] = 'individual';
        }

        if (!in_array($userAccountType, $allowedTypes)) {
            abort(403, 'Unauthorized access. You do not have permission to access this area.');
        }

        return $next($request);
    }
}
