<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\TranslationSyncService;
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
    ];

    /**
     * Switch application language
     */
    public function switch($locale, TranslationSyncService $syncService)
    {
        // Convert to lowercase for consistency
        $locale = strtolower($locale);
        
        // Validate locale
        if (!in_array($locale, $this->supportedLanguages)) {
            abort(400, 'Invalid language. Supported languages: ' . implode(', ', $this->supportedLanguages));
        }
        
        // Store in session
        Session::put('locale', $locale);
        
        // Store in cookie for 1 year
        cookie()->queue('locale', $locale, 525600);
        
        // Set application locale
        app()->setLocale($locale);

        // Kick off background sync of page translations for selected locale
        try {
            TranslatePagesJob::dispatch($locale);
        } catch (\Throwable $e) {
            // Non-fatal
        }
        
        // Redirect back
        return redirect()->back()->with('success', 'Language changed. Syncing translations...');
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
