<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Partner Governance Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Partner Governance Model including assignment
    | timeouts, attempt limits, and workflow settings.
    |
    */

    'assignment' => [
        // Time limit for partner to accept/reject assignment (in minutes)
        'ttl_minutes' => env('ASSIGNMENT_TTL_MINUTES', 30),
        
        // Maximum number of reassignment attempts before escalation
        'max_attempts' => env('ASSIGNMENT_MAX_ATTEMPTS', 5),
        
        // Enable parallel assignment offers (offer to multiple partners simultaneously)
        'parallel_offers' => env('ASSIGNMENT_PARALLEL_OFFERS', false),
        
        // Number of partners to offer simultaneously if parallel is enabled
        'parallel_count' => env('ASSIGNMENT_PARALLEL_COUNT', 2),
        
        // Auto-accept if partner has auto-accept enabled and criteria match
        'allow_auto_accept' => env('ASSIGNMENT_ALLOW_AUTO_ACCEPT', false),
    ],

    'partner' => [
        // Default maximum concurrent jobs per partner
        'default_max_concurrent_jobs' => env('PARTNER_DEFAULT_MAX_CONCURRENT', 5),
        
        // Minimum rating required for new assignments
        'min_rating_threshold' => env('PARTNER_MIN_RATING', 3.0),
        
        // License expiry warning days
        'license_expiry_warning_days' => env('PARTNER_LICENSE_WARNING_DAYS', 30),
        
        // Verification document retention period (years)
        'document_retention_years' => env('PARTNER_DOCUMENT_RETENTION', 7),
    ],

    'review' => [
        // Default review deadline after acceptance (in hours)
        'default_deadline_hours' => env('REVIEW_DEADLINE_HOURS', 24),
        
        // Government document review deadline (stricter)
        'government_deadline_hours' => env('REVIEW_GOVERNMENT_DEADLINE_HOURS', 48),
        
        // Send reminder before deadline (in hours)
        'reminder_before_hours' => env('REVIEW_REMINDER_HOURS', 6),
        
        // Allow partner to request deadline extension
        'allow_extension' => env('REVIEW_ALLOW_EXTENSION', true),
        
        // Maximum extension time (in hours)
        'max_extension_hours' => env('REVIEW_MAX_EXTENSION', 24),
    ],

    'certification' => [
        // Certificate ID prefix
        'certificate_prefix' => env('CERTIFICATE_PREFIX', 'CT'),
        
        // Certificate verification URL
        'verification_base_url' => env('CERTIFICATE_VERIFICATION_URL', env('APP_URL') . '/verify'),
        
        // QR code size (pixels)
        'qr_code_size' => env('CERTIFICATE_QR_SIZE', 200),
        
        // Enable blockchain anchoring for certificates
        'blockchain_anchoring' => env('CERTIFICATE_BLOCKCHAIN', false),
        
        // Certificate validity period (years) - 0 for permanent
        'validity_years' => env('CERTIFICATE_VALIDITY_YEARS', 0),
    ],

    'notifications' => [
        // Channels for partner notifications
        'channels' => [
            'mail' => true,
            'database' => true,
            'sms' => env('PARTNER_NOTIFY_SMS', false),
        ],
        
        // Send daily digest of pending assignments
        'daily_digest' => env('PARTNER_DAILY_DIGEST', true),
        
        // Digest time (24h format)
        'digest_time' => env('PARTNER_DIGEST_TIME', '08:00'),
    ],

    'scoring' => [
        // Weight factors for partner scoring algorithm
        'weights' => [
            'availability' => 0.3,
            'rating' => 0.3,
            'sla_reliability' => 0.2,
            'specialization' => 0.2,
        ],
        
        // Minimum score required to be eligible
        'min_score' => env('PARTNER_MIN_SCORE', 0.5),
    ],

    'country_determination' => [
        // How to determine document country
        // Options: 'client_choice', 'subdomain', 'both'
        'method' => env('COUNTRY_DETERMINATION_METHOD', 'client_choice'),
        
        // Verify country for government documents
        'verify_government' => env('COUNTRY_VERIFY_GOVERNMENT', true),
    ],

    'legal' => [
        // Require partner legal disclaimer acceptance
        'require_disclaimer' => true,
        
        // Log IP address for legal actions
        'log_ip_addresses' => true,
        
        // Legal disclaimer text key (stored in lang files)
        'disclaimer_text_key' => 'partner.legal.disclaimer',
        
        // Require re-acceptance after updates
        'require_reacceptance_on_update' => true,
    ],

    'audit' => [
        // Enable detailed audit logging
        'enabled' => env('PARTNER_AUDIT_ENABLED', true),
        
        // Audit log retention period (days)
        'retention_days' => env('PARTNER_AUDIT_RETENTION', 2555), // 7 years
        
        // Log all partner actions
        'log_all_actions' => true,
        
        // Include request metadata
        'include_metadata' => true,
    ],

];
