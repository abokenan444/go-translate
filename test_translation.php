<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing API Translation ===\n\n";

// Method 1: Direct service call
echo "Testing OpenAI Service directly:\n";
try {
    $openaiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
    $client = OpenAI::client($openaiKey);
    $response = $client->chat()->create([
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a professional translator. Translate the following text to Arabic. Return only the translation, nothing else.'],
            ['role' => 'user', 'content' => 'Good morning, how are you today?']
        ],
        'max_tokens' => 500
    ]);
    $translation = $response->choices[0]->message->content;
    echo "Original: Good morning, how are you today?\n";
    echo "Translation: " . $translation . "\n";
    echo "✅ Direct OpenAI translation successful!\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Method 2: Via TranslationService if available
echo "Testing TranslationService:\n";
try {
    $translationService = app(\App\Services\TranslationService::class);
    echo "✅ TranslationService loaded successfully!\n";
    
    if (method_exists($translationService, 'translate')) {
        $result = $translationService->translate('Hello world', 'en', 'ar');
        echo "Translated: " . (is_array($result) ? json_encode($result) : $result) . "\n";
    } else {
        echo "⚠️ translate method not found, checking available methods...\n";
        $methods = get_class_methods($translationService);
        echo "Available methods: " . implode(', ', $methods) . "\n";
    }
} catch (Exception $e) {
    echo "❌ TranslationService Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
