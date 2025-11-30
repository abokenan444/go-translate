<?php

return [

    'provider' => env('CULTURAL_AI_PROVIDER', 'openai'),

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model'   => env('CULTURAL_AI_MODEL', 'gpt-4o-mini'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    ],

    'defaults' => [
        'source_language' => 'ar',
        'target_language' => 'en',
        'culture_slug'    => 'gulf_arabic',
        'tone_slug'       => 'professional',
        'industry_slug'   => 'generic',
    ],

    'limits' => [
        'max_chars'   => 8000,
        'max_history' => 10,
    ],
];
