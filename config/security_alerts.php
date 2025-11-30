<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Alerts Configuration
    |--------------------------------------------------------------------------
    */

    // Enable/Disable security alerts
    'enabled' => env('SECURITY_ALERTS_ENABLED', true),

    // Alert channels (mail, database, slack, etc.)
    'channels' => ['mail', 'database'],

    // Email recipients for security alerts
    'recipients' => [
        // Will be sent to all super admins by default
        // You can add additional emails here:
        // 'security@culturaltranslate.com',
        // 'admin@culturaltranslate.com',
    ],

    // Attack types that trigger alerts
    'alert_on' => [
        'SQL Injection' => true,
        'XSS' => true,
        'CSRF' => true,
        'File Upload' => true,
        'Brute Force' => true,
        'Path Traversal' => true,
        'Command Injection' => true,
    ],

    // Severity levels that trigger alerts
    'alert_severity' => ['medium', 'high', 'critical'],

    // Rate limiting for alerts (to prevent spam)
    'rate_limit' => [
        'enabled' => true,
        'max_alerts_per_hour' => 10, // Max alerts to same admin per hour
    ],

    // Auto-block settings
    'auto_block' => [
        'enabled' => true,
        'threshold' => 5, // Number of attempts before auto-block
        'duration' => 3600, // Block duration in seconds (1 hour)
    ],

    // Log retention
    'retention' => [
        'days' => 90, // Keep logs for 90 days
        'auto_cleanup' => true,
    ],

    // Notification settings
    'notifications' => [
        'include_full_payload' => false, // Include full request data in email
        'summarize_daily' => true, // Send daily summary
        'daily_summary_time' => '09:00', // Time to send daily summary
    ],
];
