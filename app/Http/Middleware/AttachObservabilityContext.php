<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttachObservabilityContext
{
    public function handle(Request $request, Closure $next)
    {
        $cid = $request->headers->get('X-Correlation-Id') ?: (string) Str::uuid();
        $request->headers->set('X-Correlation-Id', $cid);

        // Store in container for later use
        app()->instance('correlation_id', $cid);

        // Attach to Sentry if available
        if (class_exists(\Sentry\State\Hub::class)) {
            \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($request, $cid) {
                $scope->setTag('correlation_id', $cid);
                $scope->setTag('host', $request->getHost());
                $scope->setTag('path', $request->path());

                if ($request->user()) {
                    $scope->setUser([
                        'id' => $request->user()->id,
                        'email' => $request->user()->email,
                    ]);
                }
            });
        }

        $response = $next($request);
        $response->headers->set('X-Correlation-Id', $cid);
        
        return $response;
    }
}
