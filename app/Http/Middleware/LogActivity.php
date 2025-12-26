<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated users
        if (auth()->check()) {
            $action = $this->getActionFromRequest($request);
            
            if ($action) {
                ActivityLog::log(
                    $action,
                    $this->getDescription($request),
                    [
                        'method' => $request->method(),
                        'url' => $request->fullUrl(),
                        'status' => $response->getStatusCode(),
                    ]
                );
            }
        }

        return $response;
    }

    private function getActionFromRequest(Request $request): ?string
    {
        $method = $request->method();
        $path = $request->path();

        // Login/Logout
        if (str_contains($path, 'login') && $method === 'POST') return 'login';
        if (str_contains($path, 'logout')) return 'logout';
        
        // Translation
        if (str_contains($path, 'translate') && $method === 'POST') return 'translation';
        
        // Subscription
        if (str_contains($path, 'subscribe') || str_contains($path, 'checkout')) return 'subscription';
        
        // Settings
        if (str_contains($path, 'settings') && in_array($method, ['POST', 'PUT', 'PATCH'])) return 'settings_update';
        
        return null;
    }

    private function getDescription(Request $request): string
    {
        $action = $this->getActionFromRequest($request);
        
        return match($action) {
            'login' => 'User logged in',
            'logout' => 'User logged out',
            'translation' => 'Translation request',
            'subscription' => 'Subscription action',
            'settings_update' => 'Settings updated',
            default => 'Activity: ' . $request->method() . ' ' . $request->path(),
        };
    }
}
