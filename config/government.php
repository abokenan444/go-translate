<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Government Subdomain Configuration
    |--------------------------------------------------------------------------
    |
    | government.culturaltranslate.com إعدادات خاصة بـ
    |
    */

    'subdomain' => env('GOVERNMENT_SUBDOMAIN', 'government'),

    'email' => env('GOVERNMENT_EMAIL', 'government@culturaltranslate.com'),

    'enabled' => env('GOVERNMENT_SUBDOMAIN_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist
    |--------------------------------------------------------------------------
    |
    | تفعيل/تعطيل قائمة IPs المسموح بها للوصول للجهات الحكومية
    |
    */

    'ip_whitelist_enabled' => env('GOVERNMENT_IP_WHITELIST_ENABLED', false),

    'allowed_ips' => env('GOVERNMENT_ALLOWED_IPS') ? 
        explode(',', env('GOVERNMENT_ALLOWED_IPS')) : [],

    /*
    |--------------------------------------------------------------------------
    | Authority Subdomain Configuration
    |--------------------------------------------------------------------------
    |
    | authority.culturaltranslate.com إعدادات خاصة بـ
    |
    */

    'authority_subdomain' => env('AUTHORITY_SUBDOMAIN', 'authority'),
    'authority_ip_whitelist_enabled' => env('AUTHORITY_IP_WHITELIST_ENABLED', true),
    'authority_allowed_ips' => env('AUTHORITY_ALLOWED_IPS') ? 
        explode(',', env('AUTHORITY_ALLOWED_IPS')) : [],

    /*
    |--------------------------------------------------------------------------
    | Invite-Only Registration
    |--------------------------------------------------------------------------
    |
    | Both government client and authority console require invitation tokens
    |
    */

    'invite_only' => env('GOVERNMENT_INVITE_ONLY', true),
    'invite_expiry_days' => env('GOVERNMENT_INVITE_EXPIRY_DAYS', 30),
    'domain_allowlist' => [
        '@gov.*',
        '@embassy.*',
        '@ministry.*',
        '@police.*',
        '@justice.*',
        '@foreign.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pilot System Configuration
    |--------------------------------------------------------------------------
    |
    | Controls for government pilot entities during beta phase
    |
    */

    'pilot_limits' => [
        'max_pilots' => 10, // Maximum concurrent pilot entities
        'max_pilot_duration_months' => 6,
        'max_users_per_pilot' => 20,
        'max_documents_per_pilot' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Sampling Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for authority audit sample creation
    |
    */

    'audit_sampling' => [
        'min_sample_size' => 5,
        'max_sample_size' => 500,
        'default_percentage' => 10, // 10% of documents
        'random_seed' => env('AUDIT_RANDOM_SEED', null), // For reproducibility
    ],

    /*
    |--------------------------------------------------------------------------
    | Decision Ledger Configuration
    |--------------------------------------------------------------------------
    |
    | Tamper-evident decision ledger with hash chaining
    |
    */

    'ledger' => [
        'hash_algorithm' => 'sha256',
        'append_only' => true, // Enforced at model level
        'chain_verification_rate_limit' => 10, // Requests per minute
    ],

    /*
    |--------------------------------------------------------------------------
    | Evidence Package Configuration
    |--------------------------------------------------------------------------
    |
    | Court-ready evidence packages for legal proceedings
    |
    */

    'evidence_packages' => [
        'storage_path' => 'evidence-packages',
        'retention_years' => 7,
        'manifest_signature_algorithm' => 'sha256',
        'include_files' => [
            '01_original_document',
            '02_translated_document',
            '03_certificate.pdf',
            '04_review_timeline.json',
            '05_decision_ledger.json',
            '06_compliance_info.json',
            '00_MANIFEST.json',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Revocation Configuration
    |--------------------------------------------------------------------------
    |
    | Two-man rule for certificate revocations
    |
    */

    'revocations' => [
        'two_man_rule_enabled' => true,
        'require_legal_basis' => true,
        'require_jurisdiction' => true,
        'generate_receipt' => true,
        'receipt_storage_path' => 'revocation-receipts',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | المميزات الخاصة بالجهات الحكومية
    |
    */

    'features' => [
        'bulk_upload' => true,
        'priority_processing' => true,
        'api_access' => true,
        'webhooks' => true,
        'compliance_reports' => true,
        'dedicated_support' => true,
        'white_label' => true,
        'custom_branding' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Limits
    |--------------------------------------------------------------------------
    |
    | حدود الاستخدام للجهات الحكومية
    |
    */

    'limits' => [
        'max_bulk_upload' => 50, // Maximum files per bulk upload
        'max_file_size' => 10240, // 10 MB in KB
        'max_monthly_documents' => 1000,
        'api_rate_limit' => 1000, // Requests per hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Document Types
    |--------------------------------------------------------------------------
    |
    | أنواع المستندات المدعومة للجهات الحكومية
    |
    */

    'document_types' => [
        'passport' => 'Passport',
        'birth_certificate' => 'Birth Certificate',
        'marriage_certificate' => 'Marriage Certificate',
        'death_certificate' => 'Death Certificate',
        'divorce_certificate' => 'Divorce Certificate',
        'academic_certificate' => 'Academic Certificate',
        'driving_license' => 'Driving License',
        'police_clearance' => 'Police Clearance',
        'court_document' => 'Court Document',
        'legal_contract' => 'Legal Contract',
        'power_of_attorney' => 'Power of Attorney',
        'visa_document' => 'Visa Document',
        'immigration_document' => 'Immigration Document',
        'business_license' => 'Business License',
        'tax_document' => 'Tax Document',
        'medical_report' => 'Medical Report',
        'official_letter' => 'Official Letter',
        'government_notice' => 'Government Notice',
        'other' => 'Other Official Document'
    ],

    /*
    |--------------------------------------------------------------------------
    | Priority Levels
    |--------------------------------------------------------------------------
    |
    | مستويات الأولوية للمستندات الحكومية
    |
    */

    'priorities' => [
        'normal' => [
            'name' => 'Normal',
            'turnaround_hours' => 48,
            'price_multiplier' => 1.0
        ],
        'high' => [
            'name' => 'High Priority',
            'turnaround_hours' => 24,
            'price_multiplier' => 1.5
        ],
        'urgent' => [
            'name' => 'Urgent',
            'turnaround_hours' => 12,
            'price_multiplier' => 2.0
        ],
        'emergency' => [
            'name' => 'Emergency',
            'turnaround_hours' => 6,
            'price_multiplier' => 3.0
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Events
    |--------------------------------------------------------------------------
    |
    | الأحداث المتاحة للـ Webhooks
    |
    */

    'webhook_events' => [
        'document.created',
        'document.processing',
        'document.translated',
        'document.certified',
        'document.completed',
        'document.failed',
        'certificate.issued',
        'certificate.verified',
        'payment.completed',
        'payment.failed'
    ],

    /*
    |--------------------------------------------------------------------------
    | Compliance Standards
    |--------------------------------------------------------------------------
    |
    | معايير الامتثال للجهات الحكومية
    |
    */

    'compliance' => [
        'iso_17100' => true, // Translation quality standard
        'iso_27001' => true, // Information security
        'gdpr_compliant' => true,
        'hipaa_compliant' => false, // For medical documents
        'encryption_required' => true,
        'audit_trail' => true,
        'data_retention_days' => 2555, // 7 years
    ],

    /*
    |--------------------------------------------------------------------------
    | SLA (Service Level Agreement)
    |--------------------------------------------------------------------------
    |
    | اتفاقية مستوى الخدمة للجهات الحكومية
    |
    */

    'sla' => [
        'uptime_guarantee' => 99.9, // Percentage
        'max_response_time_hours' => 2,
        'support_availability' => '24/7',
        'dedicated_account_manager' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | إعدادات الإشعارات للجهات الحكومية
    |
    */

    'notifications' => [
        'email' => true,
        'sms' => true,
        'webhook' => true,
        'in_app' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | إعدادات العلامة التجارية المخصصة
    |
    */

    'branding' => [
        'allow_custom_logo' => true,
        'allow_custom_colors' => true,
        'allow_custom_domain' => true,
        'remove_platform_branding' => true,
    ],

];
