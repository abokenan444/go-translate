# CulturalTranslate PHP SDK

Official PHP SDK for [CulturalTranslate](https://culturaltranslate.com) API - Intelligent cultural translation & localization.

## Features

- ✅ **Easy to Use** - Simple, intuitive API
- ✅ **Full API Coverage** - All CulturalTranslate features
- ✅ **Type Safe** - Strong typing for better IDE support
- ✅ **Helper Functions** - Quick access functions
- ✅ **PSR-4 Autoloading** - Modern PHP standards
- ✅ **Comprehensive** - Document upload, brand voice, glossary, analytics

## Installation

### Via Composer (Recommended)

```bash
composer require culturaltranslate/php-sdk
```

### Manual Installation

Download the SDK and include the autoloader:

```php
require_once 'path/to/sdk/src/Client.php';
```

## Quick Start

```php
<?php

use CulturalTranslate\Client;

// Initialize client
$client = new Client('your-api-key');

// Translate text
$result = $client->translate(
    'Hello World',
    'en',  // Source language
    'ar'   // Target language
);

echo $result['translation']; // مرحبا بالعالم
```

## Usage Examples

### Basic Translation

```php
$client = new Client('your-api-key');

$result = $client->translate(
    'Welcome to our website',
    'en',
    'ar',
    [
        'brand_voice_id' => 123,
        'apply_glossary' => true
    ]
);

print_r($result);
// Array
// (
//     [translation] => مرحباً بكم في موقعنا
//     [original_text] => Welcome to our website
//     [source_language] => en
//     [target_language] => ar
//     [context_analysis] => Array(...)
// )
```

### Batch Translation

```php
$translations = [
    [
        'text' => 'Hello',
        'source_language' => 'en',
        'target_language' => 'ar'
    ],
    [
        'text' => 'Goodbye',
        'source_language' => 'en',
        'target_language' => 'ar'
    ]
];

$results = $client->batchTranslate($translations);
```

### Context Analysis (7 Layers)

```php
$analysis = $client->analyzeContext(
    'This legal agreement must be signed',
    'en',
    'ar'
);

print_r($analysis);
// Array
// (
//     [layer_1_domain] => Array
//     (
//         [primary_domain] => legal
//         [confidence] => 0.95
//     )
//     [layer_2_formality] => Array
//     (
//         [formality_level] => very_formal
//         [score] => 9
//     )
//     ...
// )
```

### Document Upload

```php
$result = $client->uploadDocument(
    '/path/to/document.pdf',
    'en',              // Source language
    ['ar', 'fr'],      // Target languages
    'legal_contract',  // Document type
    'urgent'           // Priority
);

$documentId = $result['document']['id'];

// Check status later
$status = $client->getDocumentStatus($documentId);
echo $status['status']; // pending, processing, completed
```

### Certificate Verification

```php
$verification = $client->verifyCertificate('CT-2025-ABC12345');

if ($verification['valid']) {
    echo "Certificate is valid!\n";
    echo "Issued: " . $verification['issue_date'] . "\n";
    echo "Translator: " . $verification['translator']['name'] . "\n";
} else {
    echo "Certificate is invalid or revoked\n";
}
```

### Brand Voice Management

```php
// Get all brand voices
$brandVoices = $client->getBrandVoices();

// Create new brand voice
$brandVoice = $client->createBrandVoice([
    'name' => 'Company Brand Voice',
    'tone' => 'professional',
    'formality_level' => 'formal',
    'preferred_vocabulary' => [
        'use' => ['agreement', 'contract'],
        'avoid' => ['deal', 'stuff']
    ]
]);

// Add glossary term
$term = $client->addGlossaryTerm($brandVoice['id'], [
    'source_term' => 'terms and conditions',
    'target_term' => 'الشروط والأحكام',
    'source_language' => 'en',
    'target_language' => 'ar',
    'context' => 'Legal documents'
]);
```

### Usage Statistics

```php
$stats = $client->getUsageStats('2025-01-01', '2025-12-31');

echo "Total requests: " . $stats['total_requests'] . "\n";
echo "Characters translated: " . $stats['characters_translated'] . "\n";
```

### Webhook Configuration

```php
$webhook = $client->configureWebhook(
    'https://yoursite.com/webhook',
    [
        'document.completed',
        'certificate.issued',
        'translation.completed'
    ],
    'your-webhook-secret'
);
```

## Helper Functions

Quick access functions for common operations:

```php
// Quick translate
$translation = ct_translate('Hello', 'en', 'ar', 'your-api-key');

// Quick verify
$isValid = ct_verify('CT-2025-ABC123', 'your-api-key');

// Quick analyze
$analysis = ct_analyze('Text to analyze', 'en', 'ar', 'your-api-key');
```

### Using Environment Variables

```php
// Set API key in environment
putenv('CT_API_KEY=your-api-key');

// Use helper functions without passing API key
$translation = ct_translate('Hello', 'en', 'ar');
```

## Error Handling

```php
use CulturalTranslate\Client;
use CulturalTranslate\CulturalTranslateException;

$client = new Client('your-api-key');

try {
    $result = $client->translate('Hello', 'en', 'ar');
    echo $result['translation'];
} catch (CulturalTranslateException $e) {
    echo "Error: " . $e->getMessage();
    echo "Status Code: " . $e->getCode();
}
```

## Configuration Options

```php
$client = new Client('your-api-key', [
    'base_url' => 'https://api.culturaltranslate.com', // Custom API URL
    'timeout' => 60 // Request timeout in seconds
]);
```

## Testing Connection

```php
$client = new Client('your-api-key');

if ($client->testConnection()) {
    echo "Connected successfully!";
} else {
    echo "Connection failed!";
}
```

## Supported Languages

100+ languages including:

- English (en)
- Arabic (ar)
- French (fr)
- Spanish (es)
- German (de)
- Chinese (zh)
- Japanese (ja)
- Korean (ko)
- And many more...

## Requirements

- PHP >= 7.4
- cURL extension
- JSON extension

## Support

- **Documentation:** https://docs.culturaltranslate.com
- **Email:** support@culturaltranslate.com
- **Website:** https://culturaltranslate.com

## License

MIT License - see LICENSE file for details

## Links

- [Website](https://culturaltranslate.com)
- [Documentation](https://docs.culturaltranslate.com)
- [API Reference](https://docs.culturaltranslate.com/api)
- [GitHub](https://github.com/culturaltranslate/php-sdk)

---

Made with ❤️ by [CulturalTranslate](https://culturaltranslate.com)
