<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing API Configuration ===\n\n";

// Test OpenAI
$openaiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
echo "OpenAI Key: " . (strlen($openaiKey) > 20 ? substr($openaiKey, 0, 20) . '...' : 'NOT SET') . "\n";

// Test Stripe
$stripeKey = config('services.stripe.key') ?? env('STRIPE_KEY');
$stripeSecret = config('services.stripe.secret') ?? env('STRIPE_SECRET');
echo "Stripe Key: " . (strlen($stripeKey) > 20 ? substr($stripeKey, 0, 20) . '...' : 'NOT SET') . "\n";
echo "Stripe Secret: " . (strlen($stripeSecret) > 20 ? 'SET (hidden)' : 'NOT SET') . "\n";

// Test Mail
echo "\n=== Mail Configuration ===\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "From: " . config('mail.from.address') . "\n";

echo "\n=== Testing OpenAI Connection ===\n";
try {
    $client = OpenAI::client($openaiKey);
    $response = $client->chat()->create([
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'user', 'content' => 'Translate to Arabic: Hello, how are you?']
        ],
        'max_tokens' => 100
    ]);
    echo "OpenAI Response: " . $response->choices[0]->message->content . "\n";
    echo "\n✅ OpenAI API Working!\n";
} catch (Exception $e) {
    echo "❌ OpenAI Error: " . $e->getMessage() . "\n";
}
