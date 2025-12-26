<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Official Documents Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for certified translation documents, seals, and certificates
    |
    */

    'certification' => [
        // Where to place the certification stamp
        // Options: 'last' (recommended), 'all', 'none'
        'stamp_pages' => env('CERT_STAMP_PAGES', 'last'),
        
        // Whether to append a dedicated certificate page
        'add_certificate_page' => env('CERT_ADD_CERTIFICATE_PAGE', true),
    ],

    'seal' => [
        // Path to seal template PNG (optional)
        'template_path' => storage_path('app/seals/seal_template.png'),
        
        // Path to main seal PNG
        'path' => public_path('images/certified-translation/official_stamp_transparent.png'),
        
        // Path to seal SVG (preferred if available)
        'svg_path' => public_path('images/certified-translation/official_stamp.svg'),
    ],

    'legal_statement' => [
        // Full legal statement template
        'full' => 'This is to certify that the attached document has been translated from [source language] to [target language] by a certified translator. The translation is accurate and complete to the best of the translator\'s knowledge and ability.

Certificate ID: [cert_id]
Issue Date: [issue_date]
Verification URL: [verification_url]

This certificate can be verified at any time by visiting the verification URL or scanning the QR code.',
    ],

    'payment' => [
        'stripe' => [
            'public_key' => env('STRIPE_KEY'),
            'secret_key' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
    ],

    'certificate' => [
        'default_expiry_days' => null, // null = no expiry
    ],

    'translation' => [
        'ai_engine' => env('TRANSLATION_AI_ENGINE', 'gpt-5'),
    ],
];
