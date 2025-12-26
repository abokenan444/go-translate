<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'settings' => [
                'app_language' => $user->app_language ?? 'en',
                'default_send_language' => $user->default_send_language ?? 'en',
                'default_receive_language' => $user->default_receive_language ?? 'ar',
                'auto_topup_enabled' => (bool) ($user->auto_topup_enabled ?? false),
                'auto_topup_threshold' => (float) ($user->auto_topup_threshold ?? 5),
                'auto_topup_amount' => (float) ($user->auto_topup_amount ?? 30),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_language' => 'sometimes|string|max:10',
            'default_send_language' => 'sometimes|string|max:10',
            'default_receive_language' => 'sometimes|string|max:10',
            'auto_topup_enabled' => 'sometimes|boolean',
            'auto_topup_threshold' => 'sometimes|numeric|min:1|max:100',
            'auto_topup_amount' => 'sometimes|numeric|min:5|max:500',
        ]);

        $user = Auth::user();
        
        // Update each field explicitly
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'settings' => [
                'app_language' => $user->app_language ?? 'en',
                'default_send_language' => $user->default_send_language ?? 'en',
                'default_receive_language' => $user->default_receive_language ?? 'ar',
                'auto_topup_enabled' => (bool) ($user->auto_topup_enabled ?? false),
                'auto_topup_threshold' => (float) ($user->auto_topup_threshold ?? 5),
                'auto_topup_amount' => (float) ($user->auto_topup_amount ?? 30),
            ],
        ]);
    }

    /**
     * Get supported languages - alias for route
     */
    public function languages()
    {
        return $this->getLanguages();
    }

    public function getLanguages()
    {
        // Return supported languages for the voice translation
        $languages = [
            ['code' => 'ar', 'name' => 'العربية', 'name_en' => 'Arabic'],
            ['code' => 'en', 'name' => 'English', 'name_en' => 'English'],
            ['code' => 'es', 'name' => 'Español', 'name_en' => 'Spanish'],
            ['code' => 'fr', 'name' => 'Français', 'name_en' => 'French'],
            ['code' => 'de', 'name' => 'Deutsch', 'name_en' => 'German'],
            ['code' => 'it', 'name' => 'Italiano', 'name_en' => 'Italian'],
            ['code' => 'pt', 'name' => 'Português', 'name_en' => 'Portuguese'],
            ['code' => 'ru', 'name' => 'Русский', 'name_en' => 'Russian'],
            ['code' => 'zh', 'name' => '中文', 'name_en' => 'Chinese'],
            ['code' => 'ja', 'name' => '日本語', 'name_en' => 'Japanese'],
            ['code' => 'ko', 'name' => '한국어', 'name_en' => 'Korean'],
            ['code' => 'hi', 'name' => 'हिन्दी', 'name_en' => 'Hindi'],
            ['code' => 'tr', 'name' => 'Türkçe', 'name_en' => 'Turkish'],
            ['code' => 'nl', 'name' => 'Nederlands', 'name_en' => 'Dutch'],
            ['code' => 'pl', 'name' => 'Polski', 'name_en' => 'Polish'],
            ['code' => 'sv', 'name' => 'Svenska', 'name_en' => 'Swedish'],
            ['code' => 'da', 'name' => 'Dansk', 'name_en' => 'Danish'],
            ['code' => 'no', 'name' => 'Norsk', 'name_en' => 'Norwegian'],
            ['code' => 'fi', 'name' => 'Suomi', 'name_en' => 'Finnish'],
            ['code' => 'el', 'name' => 'Ελληνικά', 'name_en' => 'Greek'],
            ['code' => 'he', 'name' => 'עברית', 'name_en' => 'Hebrew'],
            ['code' => 'th', 'name' => 'ไทย', 'name_en' => 'Thai'],
            ['code' => 'vi', 'name' => 'Tiếng Việt', 'name_en' => 'Vietnamese'],
            ['code' => 'id', 'name' => 'Bahasa Indonesia', 'name_en' => 'Indonesian'],
            ['code' => 'ms', 'name' => 'Bahasa Melayu', 'name_en' => 'Malay'],
            ['code' => 'uk', 'name' => 'Українська', 'name_en' => 'Ukrainian'],
            ['code' => 'cs', 'name' => 'Čeština', 'name_en' => 'Czech'],
            ['code' => 'ro', 'name' => 'Română', 'name_en' => 'Romanian'],
            ['code' => 'hu', 'name' => 'Magyar', 'name_en' => 'Hungarian'],
            ['code' => 'bn', 'name' => 'বাংলা', 'name_en' => 'Bengali'],
            ['code' => 'ta', 'name' => 'தமிழ்', 'name_en' => 'Tamil'],
            ['code' => 'ur', 'name' => 'اردو', 'name_en' => 'Urdu'],
            ['code' => 'fa', 'name' => 'فارسی', 'name_en' => 'Persian'],
        ];

        return response()->json([
            'success' => true,
            'languages' => $languages,
        ]);
    }
}
