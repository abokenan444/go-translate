<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Priority: Session > Cookie > Browser > Default
        $locale = Session::get('locale') 
                  ?? $request->cookie('locale')
                  ?? $this->getBrowserLocale($request)
                  ?? config('app.locale');
        
        // Validate and set locale
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            
            // Set direction for RTL languages
            if ($locale === 'ar') {
                config(['app.direction' => 'rtl']);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Get browser preferred language
     */
    private function getBrowserLocale(Request $request)
    {
        $browserLang = $request->server('HTTP_ACCEPT_LANGUAGE');
        
        if (!$browserLang) {
            return null;
        }
        
        // Parse Accept-Language header
        $languages = explode(',', $browserLang);
        
        foreach ($languages as $lang) {
            $lang = strtolower(substr($lang, 0, 2));
            
            if (in_array($lang, ['en', 'ar'])) {
                return $lang;
            }
        }
        
        return null;
    }
}
