<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Affiliate System Configuration
    |--------------------------------------------------------------------------
    */

    // Default commission rate (%)
    'default_rate' => env('AFFILIATE_DEFAULT_RATE', 10),

    // Commission freeze period (days)
    'freeze_period' => env('AFFILIATE_FREEZE_PERIOD', 30),

    // Webhook URL for outbound events
    'webhook_url' => env('AFFILIATE_WEBHOOK_URL'),

    // Webhook secret for HMAC signature
    'webhook_secret' => env('AFFILIATE_WEBHOOK_SECRET', ''),

    // Fraud detection thresholds
    'fraud' => [
        'max_clicks_per_ip_5min' => 1,
        'max_conversions_per_ip_24h' => 3,
        'max_conversions_per_hour' => 5,
        'fraud_score_threshold' => 60, // Mark for manual review if >= 60
    ],

    // API rate limiting
    'api_rate_limit' => env('AFFILIATE_API_RATE_LIMIT', 60), // per minute

    // Minimum payout amount
    'min_payout' => env('AFFILIATE_MIN_PAYOUT', 50),
];
