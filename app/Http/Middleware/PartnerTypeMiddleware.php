<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PartnerTypeMiddleware
{
    public function handle(Request $request, Closure $next, string $type)
    {
        $partner = $request->get('partner');
        
        if (!$partner || $partner->partner_type !== $type) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_PARTNER_TYPE',
                    'message' => "This endpoint is only available for {$type} partners",
                ],
            ], 403);
        }
        
        return $next($request);
    }
}
