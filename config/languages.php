<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Languages Configuration
    |--------------------------------------------------------------------------
    | Complete list of supported languages with RTL support, native names,
    | and cultural context information
    */

    'supported' => [
        // Major Languages
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'rtl' => false,
            'flag' => '🇬🇧',
            'regions' => ['US', 'GB', 'CA', 'AU'],
            'formality' => ['formal', 'informal'],
        ],
        'ar' => [
            'name' => 'Arabic',
            'native' => 'العربية',
            'rtl' => true,
            'flag' => '🇸🇦',
            'regions' => ['SA', 'EG', 'AE', 'MA'],
            'formality' => ['formal', 'standard', 'dialectal'],
            'dialects' => ['msa', 'egyptian', 'levantine', 'gulf', 'maghrebi'],
        ],
        'es' => [
            'name' => 'Spanish',
            'native' => 'Español',
            'rtl' => false,
            'flag' => '🇪🇸',
            'regions' => ['ES', 'MX', 'AR', 'CO'],
            'formality' => ['formal', 'informal'],
        ],
        'fr' => [
            'name' => 'French',
            'native' => 'Français',
            'rtl' => false,
            'flag' => '🇫🇷',
            'regions' => ['FR', 'CA', 'BE', 'CH'],
            'formality' => ['formal', 'informal'],
        ],
        'de' => [
            'name' => 'German',
            'native' => 'Deutsch',
            'rtl' => false,
            'flag' => '🇩🇪',
            'regions' => ['DE', 'AT', 'CH'],
            'formality' => ['formal', 'informal'],
        ],
        'zh' => [
            'name' => 'Chinese',
            'native' => '中文',
            'rtl' => false,
            'flag' => '🇨🇳',
            'regions' => ['CN', 'TW', 'HK'],
            'variants' => ['simplified', 'traditional'],
            'formality' => ['formal', 'informal'],
        ],
        'ja' => [
            'name' => 'Japanese',
            'native' => '日本語',
            'rtl' => false,
            'flag' => '🇯🇵',
            'formality' => ['formal', 'polite', 'casual'],
        ],
        'ko' => [
            'name' => 'Korean',
            'native' => '한국어',
            'rtl' => false,
            'flag' => '🇰🇷',
            'formality' => ['formal', 'informal', 'honorific'],
        ],
        'ru' => [
            'name' => 'Russian',
            'native' => 'Русский',
            'rtl' => false,
            'flag' => '🇷🇺',
            'formality' => ['formal', 'informal'],
        ],
        'pt' => [
            'name' => 'Portuguese',
            'native' => 'Português',
            'rtl' => false,
            'flag' => '🇵🇹',
            'regions' => ['PT', 'BR'],
            'formality' => ['formal', 'informal'],
        ],
        'it' => [
            'name' => 'Italian',
            'native' => 'Italiano',
            'rtl' => false,
            'flag' => '🇮🇹',
            'formality' => ['formal', 'informal'],
        ],
        'nl' => [
            'name' => 'Dutch',
            'native' => 'Nederlands',
            'rtl' => false,
            'flag' => '🇳🇱',
            'formality' => ['formal', 'informal'],
        ],
        'pl' => [
            'name' => 'Polish',
            'native' => 'Polski',
            'rtl' => false,
            'flag' => '🇵🇱',
            'formality' => ['formal', 'informal'],
        ],
        'tr' => [
            'name' => 'Turkish',
            'native' => 'Türkçe',
            'rtl' => false,
            'flag' => '🇹🇷',
            'formality' => ['formal', 'informal'],
        ],
        'hi' => [
            'name' => 'Hindi',
            'native' => 'हिन्दी',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
        'ur' => [
            'name' => 'Urdu',
            'native' => 'اردو',
            'rtl' => true,
            'flag' => '🇵🇰',
            'formality' => ['formal', 'informal'],
        ],
        'fa' => [
            'name' => 'Persian',
            'native' => 'فارسی',
            'rtl' => true,
            'flag' => '🇮🇷',
            'formality' => ['formal', 'informal'],
        ],
        'he' => [
            'name' => 'Hebrew',
            'native' => 'עברית',
            'rtl' => true,
            'flag' => '🇮🇱',
            'formality' => ['formal', 'informal'],
        ],
        'id' => [
            'name' => 'Indonesian',
            'native' => 'Bahasa Indonesia',
            'rtl' => false,
            'flag' => '🇮🇩',
            'formality' => ['formal', 'informal'],
        ],
        'ms' => [
            'name' => 'Malay',
            'native' => 'Bahasa Melayu',
            'rtl' => false,
            'flag' => '🇲🇾',
            'formality' => ['formal', 'informal'],
        ],
        'th' => [
            'name' => 'Thai',
            'native' => 'ไทย',
            'rtl' => false,
            'flag' => '🇹🇭',
            'formality' => ['formal', 'polite', 'informal'],
        ],
        'vi' => [
            'name' => 'Vietnamese',
            'native' => 'Tiếng Việt',
            'rtl' => false,
            'flag' => '🇻🇳',
            'formality' => ['formal', 'informal'],
        ],
        'uk' => [
            'name' => 'Ukrainian',
            'native' => 'Українська',
            'rtl' => false,
            'flag' => '🇺🇦',
            'formality' => ['formal', 'informal'],
        ],
        'cs' => [
            'name' => 'Czech',
            'native' => 'Čeština',
            'rtl' => false,
            'flag' => '🇨🇿',
            'formality' => ['formal', 'informal'],
        ],
        'ro' => [
            'name' => 'Romanian',
            'native' => 'Română',
            'rtl' => false,
            'flag' => '🇷🇴',
            'formality' => ['formal', 'informal'],
        ],
        'sv' => [
            'name' => 'Swedish',
            'native' => 'Svenska',
            'rtl' => false,
            'flag' => '🇸🇪',
            'formality' => ['formal', 'informal'],
        ],
        'no' => [
            'name' => 'Norwegian',
            'native' => 'Norsk',
            'rtl' => false,
            'flag' => '🇳🇴',
            'formality' => ['formal', 'informal'],
        ],
        'da' => [
            'name' => 'Danish',
            'native' => 'Dansk',
            'rtl' => false,
            'flag' => '🇩🇰',
            'formality' => ['formal', 'informal'],
        ],
        'fi' => [
            'name' => 'Finnish',
            'native' => 'Suomi',
            'rtl' => false,
            'flag' => '🇫🇮',
            'formality' => ['formal', 'informal'],
        ],
        'el' => [
            'name' => 'Greek',
            'native' => 'Ελληνικά',
            'rtl' => false,
            'flag' => '🇬🇷',
            'formality' => ['formal', 'informal'],
        ],
        'hu' => [
            'name' => 'Hungarian',
            'native' => 'Magyar',
            'rtl' => false,
            'flag' => '🇭🇺',
            'formality' => ['formal', 'informal'],
        ],
        'bg' => [
            'name' => 'Bulgarian',
            'native' => 'Български',
            'rtl' => false,
            'flag' => '🇧🇬',
            'formality' => ['formal', 'informal'],
        ],
        'sk' => [
            'name' => 'Slovak',
            'native' => 'Slovenčina',
            'rtl' => false,
            'flag' => '🇸🇰',
            'formality' => ['formal', 'informal'],
        ],
        'hr' => [
            'name' => 'Croatian',
            'native' => 'Hrvatski',
            'rtl' => false,
            'flag' => '🇭🇷',
            'formality' => ['formal', 'informal'],
        ],
        'sr' => [
            'name' => 'Serbian',
            'native' => 'Српски',
            'rtl' => false,
            'flag' => '🇷🇸',
            'formality' => ['formal', 'informal'],
        ],
        'sl' => [
            'name' => 'Slovenian',
            'native' => 'Slovenščina',
            'rtl' => false,
            'flag' => '🇸🇮',
            'formality' => ['formal', 'informal'],
        ],
        'et' => [
            'name' => 'Estonian',
            'native' => 'Eesti',
            'rtl' => false,
            'flag' => '🇪🇪',
            'formality' => ['formal', 'informal'],
        ],
        'lv' => [
            'name' => 'Latvian',
            'native' => 'Latviešu',
            'rtl' => false,
            'flag' => '🇱🇻',
            'formality' => ['formal', 'informal'],
        ],
        'lt' => [
            'name' => 'Lithuanian',
            'native' => 'Lietuvių',
            'rtl' => false,
            'flag' => '🇱🇹',
            'formality' => ['formal', 'informal'],
        ],
        'sw' => [
            'name' => 'Swahili',
            'native' => 'Kiswahili',
            'rtl' => false,
            'flag' => '🇰🇪',
            'formality' => ['formal', 'informal'],
        ],
        'am' => [
            'name' => 'Amharic',
            'native' => 'አማርኛ',
            'rtl' => false,
            'flag' => '🇪🇹',
            'formality' => ['formal', 'informal'],
        ],
        'bn' => [
            'name' => 'Bengali',
            'native' => 'বাংলা',
            'rtl' => false,
            'flag' => '🇧🇩',
            'formality' => ['formal', 'informal'],
        ],
        'ta' => [
            'name' => 'Tamil',
            'native' => 'தமிழ்',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
        'te' => [
            'name' => 'Telugu',
            'native' => 'తెలుగు',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
        'mr' => [
            'name' => 'Marathi',
            'native' => 'मराठी',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
        'gu' => [
            'name' => 'Gujarati',
            'native' => 'ગુજરાતી',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
        'kn' => [
            'name' => 'Kannada',
            'native' => 'ಕನ್ನಡ',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
        'ml' => [
            'name' => 'Malayalam',
            'native' => 'മലയാളം',
            'rtl' => false,
            'flag' => '🇮🇳',
            'formality' => ['formal', 'informal'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    */
    'default' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Fallback Language
    |--------------------------------------------------------------------------
    */
    'fallback' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Available Website Languages
    |--------------------------------------------------------------------------
    | Languages available for the website interface
    */
    'website' => ['en', 'ar', 'es', 'fr', 'de', 'zh', 'ja', 'ru', 'pt', 'it', 'nl', 'tr'],

    /*
    |--------------------------------------------------------------------------
    | Translation Quality Layers
    |--------------------------------------------------------------------------
    */
    'quality_layers' => [
        'grammar_check' => true,
        'spell_check' => true,
        'context_preservation' => true,
        'cultural_adaptation' => true,
        'formality_adjustment' => true,
        'idiom_localization' => true,
        'technical_term_accuracy' => true,
    ],
];
