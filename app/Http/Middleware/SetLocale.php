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
        // Get locale from various sources (priority order)
        $locale = $this->getLocale($request);
        
        // Set application locale
        App::setLocale($locale);
        
        // Store in session for persistence
        Session::put('locale', $locale);
        
        return $next($request);
    }
    
    /**
     * Get locale from request
     */
    protected function getLocale(Request $request): string
    {
        // 1. Check URL parameter (?lang=ar)
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
        }
        
        // 2. Check session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
        }
        
        // 3. Check user preference (if authenticated)
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
        }
        
        // 4. Default to English (don't auto-detect browser language)
        return config('app.locale', 'en');
    }
    
    /**
     * Check if locale is valid
     */
    protected function isValidLocale(string $locale): bool
    {
        return in_array($locale, $this->getSupportedLocales());
    }
    
    /**
     * Get supported locales
     */
    protected function getSupportedLocales(): array
    {
        return [
            'ar', // Arabic
            'en', // English
            'nl', // Dutch
            'de', // German
            'fr', // French
            'es', // Spanish
            'pl', // Polish
            'tr', // Turkish
            'ja', // Japanese
            'zh', // Chinese
            'hi', // Hindi
            'ru', // Russian
            'ko', // Korean
            'pt', // Portuguese
            'it', // Italian
            'id', // Indonesian
        ];
    }
}
