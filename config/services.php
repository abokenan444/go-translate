<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Video Conferencing Integrations
    'zoom' => [
        'client_id' => env('ZOOM_CLIENT_ID'),
        'client_secret' => env('ZOOM_CLIENT_SECRET'),
        'redirect' => env('ZOOM_REDIRECT_URL', env('APP_URL') . '/integrations/callback/zoom'),
        'base_auth' => 'https://zoom.us/oauth/authorize',
        'base_token' => 'https://zoom.us/oauth/token',
        'scopes' => [
            'meeting:read', 'meeting:write', 'user:read', 'user:write'
        ],
    ],

    'teams' => [
        'client_id' => env('TEAMS_CLIENT_ID'),
        'client_secret' => env('TEAMS_CLIENT_SECRET'),
        'redirect' => env('TEAMS_REDIRECT_URL', env('APP_URL') . '/integrations/callback/teams'),
        'auth' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
        'token' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
        'scopes' => [
            'offline_access', 'User.Read', 'Calendars.ReadWrite', 'OnlineMeetings.ReadWrite'
        ],
    ],

    // Stripe Payment Gateway
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'prices' => [
            'basic' => env('STRIPE_PRICE_BASIC'),
            'pro' => env('STRIPE_PRICE_PRO'),
            'enterprise' => env('STRIPE_PRICE_ENTERPRISE'),
        ],
    ],

    // External translation API endpoints
    'translation_api' => [
        'pages_endpoint' => env('TRANSLATION_PAGES_ENDPOINT', env('APP_URL') . '/api/translate-pages'),
    ],

    // ASR (Automatic Speech Recognition)
    'asr' => [
        'provider' => env('ASR_PROVIDER', 'whisper'),
        'deepgram' => [
            'key' => env('DEEPGRAM_API_KEY'),
        ],
        'azure' => [
            'key' => env('AZURE_SPEECH_KEY'),
            'region' => env('AZURE_SPEECH_REGION'),
        ],
    ],

    // TTS (Text-to-Speech)
    'tts' => [
        'provider' => env('TTS_PROVIDER', 'azure'),
        'azure' => [
            'key' => env('AZURE_TTS_KEY'),
            'region' => env('AZURE_TTS_REGION'),
        ],
        'google' => [
            'key' => env('GOOGLE_TTS_KEY'),
        ],
        'elevenlabs' => [
            'key' => env('ELEVENLABS_API_KEY'),
            'default_voice' => env('ELEVENLABS_DEFAULT_VOICE', '21m00Tcm4TlvDq8ikWAM'),
        ],
    ],

];
