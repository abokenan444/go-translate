<?php

// Test CTS Verification System
require __DIR__ . '/vendor/autoload.php';

echo "Testing CTS Verification System\n";
echo "================================\n\n";

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test 1: Check if Controller exists
echo "1. Checking CtsVerificationController...\n";
try {
    $controller = new App\Http\Controllers\CtsVerificationController(
        new App\Services\CtsCertificateService()
    );
    echo "   ✅ Controller exists and instantiates\n";
} catch (Exception $e) {
    echo "   ❌ Controller error: " . $e->getMessage() . "\n";
}

// Test 2: Check if view exists
echo "\n2. Checking view file...\n";
$viewPath1 = __DIR__ . '/resources/views/cts-verification/index.blade.php';
$viewPath2 = __DIR__ . '/resources/views/cts/verification.blade.php';

if (file_exists($viewPath1)) {
    echo "   ✅ View exists: cts-verification.index\n";
} else {
    echo "   ❌ View missing: cts-verification.index\n";
}

if (file_exists($viewPath2)) {
    echo "   ✅ View exists: cts.verification\n";
} else {
    echo "   ❌ View missing: cts.verification\n";
}

// Test 3: Check route registration
echo "\n3. Checking route registration...\n";
$routes = app('router')->getRoutes();
$ctsRoute = null;

foreach ($routes as $route) {
    if ($route->uri() === 'cts/verification') {
        $ctsRoute = $route;
        break;
    }
}

if ($ctsRoute) {
    echo "   ✅ Route registered: /cts/verification\n";
    echo "   Controller: " . $ctsRoute->getActionName() . "\n";
    echo "   Methods: " . implode(', ', $ctsRoute->methods()) . "\n";
} else {
    echo "   ❌ Route not found\n";
}

// Test 4: Check CtsCertificateService
echo "\n4. Checking CtsCertificateService...\n";
try {
    $service = new App\Services\CtsCertificateService();
    echo "   ✅ Service exists and instantiates\n";
} catch (Exception $e) {
    echo "   ❌ Service error: " . $e->getMessage() . "\n";
}

// Test 5: Try to call index method
echo "\n5. Testing Controller index() method...\n";
try {
    $controller = new App\Http\Controllers\CtsVerificationController(
        new App\Services\CtsCertificateService()
    );
    
    // Capture the response
    ob_start();
    $result = $controller->index();
    ob_end_clean();
    
    echo "   ✅ index() method executed successfully\n";
    echo "   Response type: " . get_class($result) . "\n";
} catch (Exception $e) {
    echo "   ❌ index() method error: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n";
    echo "   " . $e->getTraceAsString() . "\n";
}

echo "\n================================\n";
echo "Test Complete\n";
