<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Retention Policies
    |--------------------------------------------------------------------------
    |
    | Define how long documents should be retained based on their classification
    | Time is in days
    |
    */
    
    'rules' => [
        'public' => (int) env('RETENTION_PUBLIC', 3650),           // 10 years - public documents
        'internal' => (int) env('RETENTION_INTERNAL', 730),        // 2 years - internal documents
        'confidential' => (int) env('RETENTION_CONFIDENTIAL', 365), // 1 year - confidential
        'restricted' => (int) env('RETENTION_RESTRICTED', 180),    // 6 months - restricted
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Purge Settings
    |--------------------------------------------------------------------------
    */
    
    // Enable automatic purging
    'auto_purge_enabled' => (bool) env('AUTO_PURGE_ENABLED', true),
    
    // Grace period before actual deletion (days)
    'grace_period' => (int) env('PURGE_GRACE_PERIOD', 30),
    
    // Send notification before purge
    'notify_before_purge' => (bool) env('NOTIFY_BEFORE_PURGE', true),
    
    // Notification days before purge
    'notification_days_before' => (int) env('NOTIFICATION_DAYS_BEFORE_PURGE', 7),
];
