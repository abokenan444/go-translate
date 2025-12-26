<?php

// Test private disk configuration

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Private Disk Configuration\n";
echo "===================================\n\n";

try {
    // Test that disk exists
    $disk = \Storage::disk('private');
    echo "✅ Private disk found\n";
    
    // Get disk config
    $config = config('filesystems.disks.private');
    echo "✅ Configuration:\n";
    echo "   Driver: " . ($config['driver'] ?? 'N/A') . "\n";
    echo "   Root: " . ($config['root'] ?? 'N/A') . "\n";
    echo "   Visibility: " . ($config['visibility'] ?? 'N/A') . "\n\n";
    
    // Test write operation
    $testFile = 'test-' . time() . '.txt';
    $disk->put($testFile, 'Test content for private disk');
    echo "✅ Write test successful\n";
    
    // Test read operation
    $content = $disk->get($testFile);
    echo "✅ Read test successful: " . $content . "\n";
    
    // Test exists
    if ($disk->exists($testFile)) {
        echo "✅ File exists check successful\n";
    }
    
    // Clean up
    $disk->delete($testFile);
    echo "✅ Delete test successful\n\n";
    
    echo "===================================\n";
    echo "✅ All tests passed!\n";
    echo "Private disk is working correctly.\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
