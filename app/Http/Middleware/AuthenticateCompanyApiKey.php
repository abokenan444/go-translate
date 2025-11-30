<?php
namespace App\Http\Middleware;

use App\Models\CompanyApiKey;
use Closure;
use Illuminate\Http\Request;

class AuthenticateCompanyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Company-Key') ?? $request->bearerToken();
        if (!$key) {
            return response()->json(['success'=>false,'error'=>'Missing company API key'], 401);
        }
        $apiKey = CompanyApiKey::where('key', $key)->where('revoked', false)
            ->where(function($q){ $q->whereNull('expires_at')->orWhere('expires_at','>', now()); })
            ->first();
        if (!$apiKey) {
            return response()->json(['success'=>false,'error'=>'Invalid or expired key'], 401);
        }
        // Attach company to request for downstream use
        $request->attributes->set('company_id', $apiKey->company_id);
        return $next($request);
    }
}
