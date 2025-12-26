<?php
require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Demo Translation System\n";
echo "================================\n\n";

// Test 1: Check if DemoController exists
echo "1. Checking DemoController...\n";
try {
    $service = new App\Services\SimpleDemoTranslationService();
    echo "   ✅ SimpleDemoTranslationService exists\n";
} catch (Exception $e) {
    echo "   ❌ Service error: " . $e->getMessage() . "\n";
}

// Test 2: Test translation
echo "\n2. Testing translation...\n";
try {
    $service = new App\Services\SimpleDemoTranslationService();
    
    $result = $service->translate([
        'text' => 'Hello, how are you?',
        'source_language' => 'en',
        'target_language' => 'ar',
        'tone' => 'professional'
    ]);
    
    if ($result['success']) {
        echo "   ✅ Translation successful\n";
        echo "   Original: Hello, how are you?\n";
        echo "   Translated: " . $result['translated_text'] . "\n";
        echo "   Quality: " . $result['quality_score'] . "%\n";
        echo "   Tokens: " . $result['tokens_used'] . "\n";
        echo "   Time: " . $result['response_time_ms'] . "ms\n";
    } else {
        echo "   ❌ Translation failed: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Exception: " . $e->getMessage() . "\n";
}

// Test 3: Check DemoController methods
echo "\n3. Checking DemoController methods...\n";
try {
    $controller = new App\Http\Controllers\DemoController(new App\Services\SimpleDemoTranslationService());
    
    // Check if generateCulturalInsights method exists
    $reflection = new ReflectionClass($controller);
    if ($reflection->hasMethod('generateCulturalInsights')) {
        echo "   ✅ generateCulturalInsights method exists\n";
    } else {
        echo "   ❌ generateCulturalInsights method missing\n";
    }
    
    if ($reflection->hasMethod('translate')) {
        echo "   ✅ translate method exists\n";
    } else {
        echo "   ❌ translate method missing\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 4: Check OpenAI configuration
echo "\n4. Checking OpenAI configuration...\n";
$apiKey = env('OPENAI_API_KEY');
if ($apiKey && strlen($apiKey) > 20) {
    echo "   ✅ OPENAI_API_KEY configured (length: " . strlen($apiKey) . ")\n";
} else {
    echo "   ❌ OPENAI_API_KEY missing or invalid\n";
}

// Test 5: Check route
echo "\n5. Checking routes...\n";
$routes = app('router')->getRoutes();
$demoRoute = null;

foreach ($routes as $route) {
    if ($route->uri() === 'api/demo-translate') {
        $demoRoute = $route;
        break;
    }
}

if ($demoRoute) {
    echo "   ✅ Route registered: /api/demo-translate\n";
    echo "   Method: " . implode(', ', $demoRoute->methods()) . "\n";
    echo "   Action: " . $demoRoute->getActionName() . "\n";
} else {
    echo "   ❌ Route not found\n";
}

echo "\n================================\n";
echo "Test Complete\n";
