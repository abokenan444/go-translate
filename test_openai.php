<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing OpenAI API...\n";

try {
    $apiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
    
    if (!$apiKey) {
        echo "ERROR: API Key is missing!\n";
        exit(1);
    }
    
    echo "API Key found: " . substr($apiKey, 0, 15) . "...\n";
    
    $client = OpenAI::client($apiKey);
    
    $response = $client->chat()->create([
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'user', 'content' => 'Translate "Hello" to Arabic. Reply with only the translation.']
        ],
        'max_tokens' => 20,
    ]);

    $result = $response->choices[0]->message->content;
    echo "SUCCESS!\n";
    echo "Translation: " . $result . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Type: " . get_class($e) . "\n";
    exit(1);
}
