<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    |
    | Configure system monitoring thresholds and alerting rules
    |
    */

    'enabled' => env('MONITORING_ENABLED', true),

    'thresholds' => [
        'response_time' => [
            'max' => 1000, // milliseconds
        ],
        'error_rate' => [
            'max' => 5, // percentage
        ],
        'cpu_usage' => [
            'max' => 80, // percentage
        ],
        'memory_usage' => [
            'max' => 85, // percentage
        ],
        'disk_usage' => [
            'max' => 90, // percentage
        ],
        'queue_size' => [
            'max' => 1000, // jobs
        ],
        'failed_jobs' => [
            'max' => 100,
        ],
    ],

    'alerts' => [
        'enabled' => env('ALERTS_ENABLED', true),
        'channels' => ['slack', 'email'],
        'slack_webhook' => env('SLACK_WEBHOOK_URL'),
        'email_recipients' => env('ALERT_EMAIL_RECIPIENTS', 'admin@example.com'),
    ],

    'metrics_retention' => [
        'real_time' => 3600, // 1 hour in seconds
        'historical' => 86400, // 24 hours in seconds
    ],

    'health_check' => [
        'interval' => 60, // seconds
        'timeout' => 5, // seconds
        'endpoints' => [
            'database' => true,
            'redis' => true,
            'queue' => true,
            'storage' => true,
            'external_apis' => true,
        ],
    ],

    'performance' => [
        'track_queries' => env('TRACK_QUERIES', true),
        'slow_query_threshold' => 1000, // milliseconds
        'track_requests' => env('TRACK_REQUESTS', true),
    ],
];
