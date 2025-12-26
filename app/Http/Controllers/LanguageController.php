<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Jobs\TranslatePagesJob;

class LanguageController extends Controller
{
    /**
     * Supported languages
     */
    protected $supportedLanguages = [
        'en', // English
        'ar', // Arabic
        'es', // Spanish
        'fr', // French
        'de', // German
        'it', // Italian
        'pt', // Portuguese
        'ru', // Russian
        'zh', // Chinese
        'ja', // Japanese
        'ko', // Korean
        'hi', // Hindi
        'tr', // Turkish
        'nl', // Dutch
        'pl', // Polish
    ];

    /**
     * Switch application language
     */
    public function switch(Request $request, string $locale)
    {
        // Convert to lowercase for consistency
        $locale = strtolower($locale);
        
        // Validate locale
        if (!in_array($locale, $this->supportedLanguages)) {
            abort(400, 'Invalid language. Supported languages: ' . implode(', ', $this->supportedLanguages));
        }
        
        // Store in session
        Session::put('locale', $locale);
        Session::save(); // Force save
        
        // Set application locale immediately
        app()->setLocale($locale);
        
        // Log for debugging
        \Log::info('Language switched', [
            'new_locale' => $locale,
            'session_locale' => Session::get('locale'),
        ]);

        // Optional: kick off background sync of page translations.
        // SECURITY: never allow this to be abused from a public GET endpoint.
        // Enable only via env + strict throttling using Cache.
        if ((bool) env('TRANSLATION_SYNC_ON_LANGUAGE_SWITCH', false)) {
            try {
                $cacheKey = 'translation_sync:last_dispatch:' . $locale;
                // Allow at most once per 30 minutes per locale.
                if (Cache::add($cacheKey, now()->timestamp, now()->addMinutes(30))) {
                    TranslatePagesJob::dispatch($locale);
                }
            } catch (\Throwable $e) {
                // Non-fatal
            }
        }
        
        // Redirect back with cookie (avoid open redirect to external hosts)
        $previous = url()->previous();
        $previousHost = parse_url($previous, PHP_URL_HOST);
        if ($previousHost && $previousHost !== $request->getHost()) {
            $previous = route('home');
        }

        return redirect()->to($previous)
            ->cookie('locale', $locale, 525600) // 1 year
            ->with('success', 'Language changed to ' . strtoupper($locale));
    }
    
    /**
     * Get current locale
     */
    public function current()
    {
        return response()->json([
            'locale' => app()->getLocale(),
            'available' => $this->supportedLanguages
        ]);
    }
    
    /**
     * Get all supported languages
     */
    public function index()
    {
        $languages = [
            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => '🇸🇦'],
            'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸'],
            'fr' => ['name' => 'French', 'native' => 'Français', 'flag' => '🇫🇷'],
            'de' => ['name' => 'German', 'native' => 'Deutsch', 'flag' => '🇩🇪'],
            'it' => ['name' => 'Italian', 'native' => 'Italiano', 'flag' => '🇮🇹'],
            'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'flag' => '🇵🇹'],
            'ru' => ['name' => 'Russian', 'native' => 'Русский', 'flag' => '🇷🇺'],
            'zh' => ['name' => 'Chinese', 'native' => '中文', 'flag' => '🇨🇳'],
            'ja' => ['name' => 'Japanese', 'native' => '日本語', 'flag' => '🇯🇵'],
            'ko' => ['name' => 'Korean', 'native' => '한국어', 'flag' => '🇰🇷'],
            'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'flag' => '🇮🇳'],
            'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'flag' => '🇹🇷'],
            'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'flag' => '🇳🇱'],
        ];
        
        return response()->json([
            'success' => true,
            'languages' => $languages,
            'current' => app()->getLocale()
        ]);
    }
}
