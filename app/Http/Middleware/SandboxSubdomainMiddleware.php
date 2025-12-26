<?php

namespace App\Http\Middleware;

use App\Models\SandboxInstance;
use Closure;
use Illuminate\Http\Request;

class SandboxSubdomainMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        // Expecting subdomain like {company}.integration.culturaltranslate.com
        $parts = explode('.', $host);
        if (count($parts) >= 4 && $parts[1] === 'integration' && $parts[2] === 'culturaltranslate' && $parts[3] === 'com') {
            $subdomain = $parts[0];
            $instance = SandboxInstance::where('subdomain', $subdomain)->first();
            if (!$instance || !$instance->isActive()) {
                abort(404, 'Sandbox not found or inactive');
            }
            // Share instance with request
            $request->attributes->set('sandboxInstance', $instance);
        }

        return $next($request);
    }
}
