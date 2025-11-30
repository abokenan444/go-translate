<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'flag_emoji' => '🇸🇦', 'direction' => 'rtl', 'is_default' => false, 'order' => 1],
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'flag_emoji' => '🇬🇧', 'direction' => 'ltr', 'is_default' => true, 'order' => 2],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'flag_emoji' => '🇫🇷', 'direction' => 'ltr', 'is_default' => false, 'order' => 3],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'flag_emoji' => '🇪🇸', 'direction' => 'ltr', 'is_default' => false, 'order' => 4],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'flag_emoji' => '🇩🇪', 'direction' => 'ltr', 'is_default' => false, 'order' => 5],
            ['code' => 'it', 'name' => 'Italian', 'native_name' => 'Italiano', 'flag_emoji' => '🇮🇹', 'direction' => 'ltr', 'is_default' => false, 'order' => 6],
            ['code' => 'pt', 'name' => 'Portuguese', 'native_name' => 'Português', 'flag_emoji' => '🇵🇹', 'direction' => 'ltr', 'is_default' => false, 'order' => 7],
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский', 'flag_emoji' => '🇷🇺', 'direction' => 'ltr', 'is_default' => false, 'order' => 8],
            ['code' => 'zh', 'name' => 'Chinese', 'native_name' => '中文', 'flag_emoji' => '🇨🇳', 'direction' => 'ltr', 'is_default' => false, 'order' => 9],
            ['code' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語', 'flag_emoji' => '🇯🇵', 'direction' => 'ltr', 'is_default' => false, 'order' => 10],
            ['code' => 'ko', 'name' => 'Korean', 'native_name' => '한국어', 'flag_emoji' => '🇰🇷', 'direction' => 'ltr', 'is_default' => false, 'order' => 11],
            ['code' => 'tr', 'name' => 'Turkish', 'native_name' => 'Türkçe', 'flag_emoji' => '🇹🇷', 'direction' => 'ltr', 'is_default' => false, 'order' => 12],
            ['code' => 'nl', 'name' => 'Dutch', 'native_name' => 'Nederlands', 'flag_emoji' => '🇳🇱', 'direction' => 'ltr', 'is_default' => false, 'order' => 13],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
