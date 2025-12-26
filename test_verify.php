#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Certificate Verification ===\n\n";

// Test Certificate Model
echo "1. Testing Certificate Model...\n";
try {
    $cert = App\Models\Certificate::first();
    if ($cert) {
        echo "   ✅ Certificate found: ID {$cert->id}\n";
        echo "   Table: " . (new App\Models\Certificate)->getTable() . "\n";
        echo "   Status: {$cert->status}\n";
        echo "   Legal Status: " . ($cert->legal_status ?? 'N/A') . "\n";
    } else {
        echo "   ⚠️ No certificates found\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test verify route
echo "\n2. Testing Verify Route...\n";
try {
    $routes = app('router')->getRoutes();
    $verifyRoute = null;
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'verify/{') !== false) {
            $verifyRoute = $route->uri();
            echo "   ✅ Found verify route: {$verifyRoute}\n";
            break;
        }
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
