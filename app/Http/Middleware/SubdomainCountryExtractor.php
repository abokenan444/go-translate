<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JurisdictionService;
use Symfony\Component\HttpFoundation\Response;

class SubdomainCountryExtractor
{
    public function __construct(private JurisdictionService $jurisdictionService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * Extracts country code from subdomain and stores it in session/request
     * Supports patterns:
     * - nl-gov.culturaltranslate.com -> NL
     * - gov-nl.culturaltranslate.com -> NL
     * - government.culturaltranslate.com -> (no country, general portal)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Extract subdomain (everything before culturaltranslate.com)
        // Support both .com and local dev domains
        $pattern = '/^(.+?)\.culturaltranslate\.(com|test|local)/i';
        
        if (preg_match($pattern, $host, $matches)) {
            $subdomain = $matches[1];
            
            // Check if it's a government portal
            if ($this->jurisdictionService->isGovernmentPortal($subdomain)) {
                // Extract country code
                $countryCode = $this->jurisdictionService->extractCountryFromSubdomain($subdomain);
                
                if ($countryCode) {
                    // Store in request for current cycle
                    $request->merge(['country_from_portal' => $countryCode]);
                    
                    // Store in session for persistence
                    session(['country_from_portal' => $countryCode]);
                    session(['is_government_portal' => true]);
                    
                    // Add to view composer data (optional)
                    view()->share('portalCountry', $countryCode);
                    view()->share('isGovernmentPortal', true);
                    
                    \Log::info("Subdomain country extracted", [
                        'subdomain' => $subdomain,
                        'country' => $countryCode,
                        'host' => $host,
                    ]);
                } else {
                    // General government portal without specific country
                    session(['is_government_portal' => true]);
                    view()->share('isGovernmentPortal', true);
                }
            }
        }

        return $next($request);
    }
}
