<?php

namespace App\Http\Middleware;

use App\Models\GovernmentPortal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GovernmentPortalMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * This middleware:
     * 1. Detects if the request is for a government portal
     * 2. Extracts the country code from subdomain or path
     * 3. Loads the portal configuration
     * 4. Sets country context for document submissions
     */
    public function handle(Request $request, Closure $next): Response
    {
        $countryCode = $this->extractCountryCode($request);
        
        if ($countryCode) {
            $portal = GovernmentPortal::where('country_code', strtoupper($countryCode))
                ->orWhere('portal_slug', strtolower($countryCode))
                ->where('is_active', true)
                ->first();
            
            if ($portal) {
                // Share portal data with all views
                view()->share('governmentPortal', $portal);
                
                // Store in request for controllers
                $request->attributes->set('government_portal', $portal);
                $request->attributes->set('country_from_portal', $portal->country_code);
                
                // Set locale if available
                if ($portal->default_language) {
                    app()->setLocale($portal->default_language);
                }
            } else {
                // Country not found but was requested - could redirect or show generic
                $request->attributes->set('country_from_portal', strtoupper($countryCode));
            }
        }
        
        return $next($request);
    }

    /**
     * Extract country code from subdomain or path
     */
    private function extractCountryCode(Request $request): ?string
    {
        $host = $request->getHost();
        $path = $request->path();
        
        // Pattern 1: prefix subdomain (nl-gov.culturaltranslate.com)
        if (preg_match('/^([a-z]{2,3})-gov\./', $host, $matches)) {
            return $matches[1];
        }
        
        // Pattern 2: gov prefix subdomain (gov-nl.culturaltranslate.com)
        if (preg_match('/^gov-([a-z]{2,3})\./', $host, $matches)) {
            return $matches[1];
        }
        
        // Pattern 3: Path-based (gov.culturaltranslate.com/nl or culturaltranslate.com/gov/nl)
        if (str_contains($host, 'gov.') || str_starts_with($path, 'gov/')) {
            $segments = explode('/', trim($path, '/'));
            
            // Remove 'gov' prefix if present
            if (!empty($segments[0]) && $segments[0] === 'gov') {
                array_shift($segments);
            }
            
            // Check if first segment is a country code
            if (!empty($segments[0]) && preg_match('/^[a-z]{2,3}$/i', $segments[0])) {
                return $segments[0];
            }
        }
        
        return null;
    }
}
