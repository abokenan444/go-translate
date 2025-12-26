<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Full Translation Test ===\n\n";

// Test OpenAI Direct
echo "1. OpenAI Direct Call:\n";
try {
    $openaiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
    $client = OpenAI::client($openaiKey);
    
    $texts = [
        'Hello, how are you?' => 'ar',
        'Thank you very much' => 'ar',
        'The meeting is scheduled for tomorrow' => 'ar',
    ];
    
    foreach ($texts as $text => $target) {
        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional translator. Translate to Arabic with cultural sensitivity. Return only the translation.'],
                ['role' => 'user', 'content' => $text]
            ],
            'max_tokens' => 500
        ]);
        echo "   EN: $text\n   AR: " . $response->choices[0]->message->content . "\n\n";
    }
    echo "✅ OpenAI translations successful!\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test Cultural Translation Service
echo "2. Cultural Translation Service:\n";
try {
    $service = app(\App\Services\TranslationService::class);
    $result = $service->translateCultural('Good morning, I hope you are well', 'en', 'ar', [
        'cultural_adaptation' => true,
        'tone' => 'formal'
    ]);
    echo "   Result: " . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "✅ Cultural translation successful!\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test Language Detection
echo "3. Language Detection:\n";
try {
    $service = app(\App\Services\TranslationService::class);
    $testTexts = [
        'Hello world',
        'مرحبا بالعالم',
        'Bonjour le monde',
        'Hola mundo'
    ];
    foreach ($testTexts as $text) {
        $detected = $service->detectLanguage($text);
        echo "   '$text' => " . (is_array($detected) ? json_encode($detected) : $detected) . "\n";
    }
    echo "✅ Language detection working!\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

echo "=== All Tests Complete ===\n";
