<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDF Translation Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the external PDF translation service endpoint. This service
    | should accept a PDF file and return the translated version with
    | layout preservation, OCR, and original stamp/signature retention.
    |
    */

    'service_url' => env('PDF_TRANSLATION_SERVICE_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Service Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time (in seconds) to wait for the translation service to respond.
    | PDF translation can be time-consuming, especially for large documents.
    |
    */

    'timeout' => env('PDF_TRANSLATION_TIMEOUT', 300),

    /*
    |--------------------------------------------------------------------------
    | Fallback Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, if the translation service is not available or fails,
    | the system will copy the original file as a fallback. Disable this
    | in production to ensure all translations go through the service.
    |
    */

    'fallback_enabled' => env('PDF_TRANSLATION_FALLBACK', true),

    /*
    |--------------------------------------------------------------------------
    | Quality Settings
    |--------------------------------------------------------------------------
    |
    | Default quality score for translated documents (0-100).
    | This is used when the translation service doesn't return a quality metric.
    |
    */

    'default_quality_score' => 95.00,

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    |
    | List of language codes supported for PDF translation.
    |
    */

    'supported_languages' => [
        'ar' => 'Arabic',
        'en' => 'English',
        'nl' => 'Dutch',
        'fr' => 'French',
        'de' => 'German',
        'es' => 'Spanish',
        'it' => 'Italian',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'zh' => 'Chinese',
        'ja' => 'Japanese',
        'ko' => 'Korean',
    ],

    /*
    |--------------------------------------------------------------------------
    | Document Types
    |--------------------------------------------------------------------------
    |
    | Supported official document types for certified translation.
    |
    */

    'document_types' => [
        'birth_certificate' => 'Birth Certificate',
        'marriage_certificate' => 'Marriage Certificate',
        'divorce_certificate' => 'Divorce Certificate',
        'death_certificate' => 'Death Certificate',
        'passport' => 'Passport Page',
        'national_id' => 'National ID Card',
        'drivers_license' => 'Driver\'s License',
        'diploma' => 'Diploma / Degree',
        'transcript' => 'Academic Transcript',
        'employment_contract' => 'Employment Contract',
        'commercial_license' => 'Commercial License',
        'court_document' => 'Court Order / Legal Document',
        'power_of_attorney' => 'Power of Attorney',
        'police_clearance' => 'Police Clearance Certificate',
        'bank_statement' => 'Bank Statement',
        'other' => 'Other Official Document',
    ],

];
