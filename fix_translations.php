<?php
/**
 * Fix Translation Keys - Convert nested to flat format
 * This script creates flat key translations that match the blade files
 */

// Master English translations with FLAT keys (matching blade files)
$englishTranslations = [
    // Site Meta
    'site_title' => 'CulturalTranslate - AI-Powered Cultural Translation Platform',
    'site_description' => 'Professional AI-powered translation platform that preserves cultural context, tone, and meaning.',
    
    // Hero Section
    'hero_title' => 'Translate with Cultural Intelligence',
    'hero_subtitle' => 'AI-powered translation that preserves meaning, context, and cultural nuance across 15+ languages.',
    'hero_cta_start' => 'Start Free Trial',
    'hero_cta_discover' => 'Discover Features',
    'hero_no_credit_card' => 'No credit card required',
    'hero_free_trial' => '7-day free trial',
    
    // Stats
    'stats_registered_users' => 'Registered Users',
    'stats_active_subscriptions' => 'Active Subscriptions',
    'stats_published_pages' => 'Published Pages',
    'stats_active_companies' => 'Active Companies',
    
    // Demo Section
    'demo_title' => 'Try It Now',
    'demo_subtitle' => 'Experience the power of cultural translation',
    'demo_from' => 'From',
    'demo_to' => 'To',
    'demo_source_text' => 'Source Text',
    'demo_placeholder' => 'Enter text to translate...',
    'demo_translation' => 'Translation',
    'demo_result_placeholder' => 'Translation will appear here...',
    'demo_translate_btn' => 'Translate',
    'demo_free_trial_note' => 'Free demo - no account required',
    'demo_try_examples' => 'Try these examples:',
    'demo_example_welcome' => 'Welcome Message',
    'demo_example_marketing' => 'Marketing Copy',
    'demo_example_support' => 'Customer Support',
    'demo_feature_instant' => 'Instant Translation',
    'demo_feature_instant_desc' => 'Get results in seconds with our AI engine',
    'demo_feature_cultural' => 'Cultural Context',
    'demo_feature_cultural_desc' => 'Preserves idioms and local expressions',
    'demo_feature_secure' => 'Secure & Private',
    'demo_feature_secure_desc' => 'Your data is encrypted and protected',
    
    // Features Section
    'features_title' => 'Powerful Features',
    'features_subtitle' => 'Everything you need for professional translation',
    'feature_cultural_title' => 'Cultural Intelligence',
    'feature_cultural_desc' => 'Our AI understands cultural nuances and adapts translations accordingly',
    'feature_fast_title' => 'Lightning Fast',
    'feature_fast_desc' => 'Get instant translations powered by advanced AI technology',
    'feature_security_title' => 'Enterprise Security',
    'feature_security_desc' => 'Bank-level encryption protects your sensitive content',
    'feature_memory_title' => 'Translation Memory',
    'feature_memory_desc' => 'Learn from your previous translations for consistency',
    'feature_glossary_title' => 'Custom Glossary',
    'feature_glossary_desc' => 'Define your own terminology for brand consistency',
    'feature_api_title' => 'API Access',
    'feature_api_desc' => 'Integrate translation into your workflow with our REST API',
    
    // CTA Section
    'cta_title' => 'Ready to Get Started?',
    'cta_subtitle' => 'Join thousands of businesses using CulturalTranslate',
    'cta_button' => 'Start Your Free Trial',
    
    // Navigation
    'nav_home' => 'Home',
    'nav_features' => 'Features',
    'nav_pricing' => 'Pricing',
    'nav_about' => 'About',
    'nav_contact' => 'Contact',
    'nav_login' => 'Log in',
    'nav_register' => 'Register',
    'nav_dashboard' => 'Dashboard',
    'nav_logout' => 'Logout',
    
    // Footer
    'footer_description' => 'AI-powered translation platform that preserves cultural context and meaning.',
    'footer_company' => 'Company',
    'footer_product' => 'Product',
    'footer_legal' => 'Legal',
    'footer_copyright' => '© 2025 CulturalTranslate. All rights reserved.',
    'footer_privacy' => 'Privacy Policy',
    'footer_terms' => 'Terms of Service',
    
    // Common
    'loading' => 'Loading...',
    'error' => 'Error',
    'success' => 'Success',
    'cancel' => 'Cancel',
    'save' => 'Save',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'view' => 'View',
    'close' => 'Close',
    'submit' => 'Submit',
    'search' => 'Search',
    'filter' => 'Filter',
    'sort' => 'Sort',
    'actions' => 'Actions',
    'yes' => 'Yes',
    'no' => 'No',
    'confirm' => 'Confirm',
    'back' => 'Back',
    'next' => 'Next',
    'previous' => 'Previous',
];

// Languages to translate to
$languages = [
    'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
    'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
    'es' => ['name' => 'Spanish', 'native' => 'Español', 'rtl' => false],
    'fr' => ['name' => 'French', 'native' => 'Français', 'rtl' => false],
    'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'rtl' => false],
    'it' => ['name' => 'Italian', 'native' => 'Italiano', 'rtl' => false],
    'ja' => ['name' => 'Japanese', 'native' => '日本語', 'rtl' => false],
    'ko' => ['name' => 'Korean', 'native' => '한국어', 'rtl' => false],
    'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'rtl' => false],
    'pl' => ['name' => 'Polish', 'native' => 'Polski', 'rtl' => false],
    'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'rtl' => false],
    'ru' => ['name' => 'Russian', 'native' => 'Русский', 'rtl' => false],
    'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'rtl' => false],
    'zh' => ['name' => 'Chinese', 'native' => '中文', 'rtl' => false],
];

// Load API key from .env
$envFile = __DIR__ . '/.env';
$apiKey = null;
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'OPENAI_API_KEY=') === 0) {
            $apiKey = trim(substr($line, strlen('OPENAI_API_KEY=')));
            break;
        }
    }
}

if (!$apiKey) {
    die("Error: OPENAI_API_KEY not found in .env file\n");
}

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║     CulturalTranslate - Fix Translation Keys              ║\n";
echo "╠═══════════════════════════════════════════════════════════╣\n";
echo "║  Creating flat-key translations for blade compatibility   ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

// First, save English file
$langDir = __DIR__ . '/lang/en';
if (!is_dir($langDir)) {
    mkdir($langDir, 0755, true);
}

$content = "<?php\n\nreturn " . var_export($englishTranslations, true) . ";\n";
file_put_contents($langDir . '/messages.php', $content);
echo "✓ Saved English translations\n";

// Get target language
$targetLang = $argv[1] ?? 'all';

if ($targetLang === 'all') {
    $targetLanguages = array_keys($languages);
} else {
    $targetLanguages = [$targetLang];
}

foreach ($targetLanguages as $langCode) {
    if (!isset($languages[$langCode])) {
        echo "⚠ Unknown language: $langCode, skipping...\n";
        continue;
    }
    
    $langInfo = $languages[$langCode];
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  Translating to {$langInfo['name']} ($langCode)...\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Prepare batch for API
    $stringsToTranslate = json_encode($englishTranslations, JSON_UNESCAPED_UNICODE);
    
    $prompt = "Translate this JSON object from English to {$langInfo['name']}. 
Keep the keys exactly the same, only translate the values.
Keep brand names like 'CulturalTranslate', 'API', 'AI' unchanged.
Return ONLY valid JSON, no explanations.

$stringsToTranslate";

    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'system', 'content' => "You are a professional translator. Translate to {$langInfo['name']}. Return only valid JSON."],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.3,
        'max_tokens' => 4000
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
        CURLOPT_TIMEOUT => 120
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        echo "  ✗ API Error (HTTP $httpCode)\n";
        continue;
    }
    
    $result = json_decode($response, true);
    $translatedJson = $result['choices'][0]['message']['content'] ?? '';
    
    // Clean up response
    $translatedJson = preg_replace('/```json\s*/', '', $translatedJson);
    $translatedJson = preg_replace('/```\s*/', '', $translatedJson);
    $translatedJson = trim($translatedJson);
    
    $translations = json_decode($translatedJson, true);
    
    if (!$translations) {
        echo "  ✗ Failed to parse translation response\n";
        continue;
    }
    
    // Save to file
    $langDir = __DIR__ . "/lang/$langCode";
    if (!is_dir($langDir)) {
        mkdir($langDir, 0755, true);
    }
    
    $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
    file_put_contents($langDir . '/messages.php', $content);
    
    echo "  ✓ Saved to lang/$langCode/messages.php (" . count($translations) . " strings)\n";
    
    // Small delay between requests
    usleep(500000);
}

echo "\n════════════════════════════════════════════════════════════\n";
echo "  TRANSLATION COMPLETE!\n";
echo "════════════════════════════════════════════════════════════\n";
