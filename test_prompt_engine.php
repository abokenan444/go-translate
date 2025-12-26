<?php
// Test PromptEngine directly
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing PromptEngine ===\n\n";

try {
    // Test 1: Create PromptEngine instance
    echo "1. Creating PromptEngine instance...\n";
    $promptEngine = app(\App\Services\PromptEngine\PromptEngine::class);
    echo "   ✓ PromptEngine created successfully\n\n";
    
    // Test 2: Get prompt for academic document
    echo "2. Getting prompt for academic document...\n";
    $prompt = $promptEngine->buildPrompt([
        'document_type' => 'academic_transcript',
        'source_lang' => 'en',
        'target_lang' => 'ar',
        'certified' => true
    ]);
    echo "   ✓ Prompt retrieved successfully\n";
    echo "   Prompt length: " . strlen($prompt) . " characters\n";
    echo "   First 200 chars: " . substr($prompt, 0, 200) . "...\n\n";
    
    // Test 3: Get prompt for utility bill
    echo "3. Getting prompt for utility bill...\n";
    $prompt2 = $promptEngine->buildPrompt([
        'filename' => 'utility_bill.pdf',
        'source_lang' => 'en',
        'target_lang' => 'ar'
    ]);
    echo "   ✓ Prompt retrieved successfully\n";
    echo "   Prompt length: " . strlen($prompt2) . " characters\n";
    echo "   First 200 chars: " . substr($prompt2, 0, 200) . "...\n\n";
    
    echo "=== All tests passed! ===\n";
    
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
}
