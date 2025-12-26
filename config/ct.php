<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Assignment System Configuration
    |--------------------------------------------------------------------------
    */
    'assignment_ttl_minutes' => (int) env('ASSIGNMENT_TTL_MINUTES', 60),
    'max_assignment_attempts' => (int) env('MAX_ASSIGNMENT_ATTEMPTS', 7),
    'parallel_offers' => (int) env('PARALLEL_OFFERS', 2),
    
    // Minimum rating for partner assignment
    'min_partner_rating' => (float) env('MIN_PARTNER_RATING', 4.0),
    
    // Priority levels
    'priority_levels' => [
        'low' => ['urgency' => 1, 'max_days' => 14],
        'normal' => ['urgency' => 2, 'max_days' => 7],
        'high' => ['urgency' => 3, 'max_days' => 3],
        'urgent' => ['urgency' => 4, 'max_days' => 1],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Government Portal Configuration
    |--------------------------------------------------------------------------
    */
    'enable_gov_subdomains' => (bool) env('ENABLE_GOV_SUBDOMAINS', false),
    
    // Government subdomain patterns
    'government_subdomain_patterns' => [
        'gov-{country}', // e.g., gov-nl.culturaltranslate.com
        '{country}-gov', // e.g., nl-gov.culturaltranslate.com
    ],
    
    // Government portal base URL (for path-based routing)
    'government_portal_base' => env('GOV_PORTAL_BASE', '/gov'),
    
    // Allowed government email domains
    'government_email_domains' => [
        '.gov',
        '.gov.uk',
        '.gov.nl',
        '.gov.ae',
        '.gouv.fr',
        '.gob.es',
        '.gob.mx',
        '.govt.nz',
        '.go.jp',
        '.go.kr',
        '.bund.de',
    ],
    
    // Country validation strictness for government documents
    // 'strict' = must match portal country exactly
    // 'relaxed' = allows user selection with warning
    'country_validation_mode' => env('COUNTRY_VALIDATION_MODE', 'strict'),
    
    /*
    |--------------------------------------------------------------------------
    | Document Processing
    |--------------------------------------------------------------------------
    */
    'max_file_size_mb' => (int) env('MAX_FILE_SIZE_MB', 25),
    'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'tiff'],
    
    // Pricing configuration
    'pricing' => [
        'standard' => [
            'per_word' => 0.12,
            'delivery_days' => '5-7',
        ],
        'express' => [
            'per_word' => 0.18,
            'delivery_days' => '2-3',
        ],
        'rush' => [
            'per_word' => 0.25,
            'delivery_days' => '1-2',
        ],
        'minimum_charge' => 25,
        'notarization_fee' => 25,
        'apostille_fee' => 75,
        'hard_copy_fee' => 15,
        'express_courier_fee' => 45,
    ],
    
    // Flat rate documents
    'flat_rate_documents' => [
        'birth_certificate' => ['standard' => 30, 'express' => 45, 'rush' => 65],
        'marriage_certificate' => ['standard' => 35, 'express' => 50, 'rush' => 75],
        'death_certificate' => ['standard' => 30, 'express' => 45, 'rush' => 65],
        'divorce_decree' => ['standard' => 40, 'express' => 60, 'rush' => 90],
        'drivers_license' => ['standard' => 25, 'express' => 40, 'rush' => 55],
        'diploma_degree' => ['standard' => 35, 'express' => 50, 'rush' => 75],
    ],
];
