<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Partner;
use Illuminate\Http\Request;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->bearerToken();

        if (!$apiKey) {
            return response()->json(['error' => 'API key required'], 401);
        }

        $partner = Partner::where('api_key', $apiKey)
            ->where('status', 'approved')
            ->first();

        if (!$partner) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $request->merge(['partner' => $partner]);

        return $next($request);
    }
}
