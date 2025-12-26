<?php
/**
 * اختبار صفحة ترجمة المستندات
 * Test file translation page with authentication
 */

echo "\n=== Testing File Translation System with CSRF ===\n\n";

// Test 1: Check if page is accessible (requires authentication)
echo "Test 1: Checking page accessibility...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://culturaltranslate.com/dashboard/file-translation');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Page accessible (Status: $httpCode)\n";
    
    // Check for CSRF token in meta tag
    if (strpos($response, 'csrf-token') !== false) {
        echo "✅ CSRF token meta tag found\n";
    } else {
        echo "❌ CSRF token meta tag NOT found\n";
    }
    
    // Check for file upload form elements
    if (strpos($response, 'fileTranslationApp') !== false) {
        echo "✅ Alpine.js app component found\n";
    } else {
        echo "❌ Alpine.js app component NOT found\n";
    }
    
    // Check for upload text
    if (strpos($response, 'ترجمة المستندات') !== false || strpos($response, 'Document Translation') !== false) {
        echo "✅ Page title found\n";
    } else {
        echo "❌ Page title NOT found\n";
    }
    
    // Check for file input or upload zone
    if (strpos($response, 'file') !== false || strpos($response, 'upload') !== false) {
        echo "✅ Upload functionality elements found\n";
    } else {
        echo "❌ Upload functionality elements NOT found\n";
    }
} else {
    echo "⚠️  Page status: $httpCode (may require authentication)\n";
}

echo "\n";

// Test 2: Check view file on server
echo "Test 2: Checking view file on server...\n";
$result = shell_exec('ssh root@145.14.158.101 "test -f /var/www/cultural-translate-platform/resources/views/dashboard/file-translation/index.blade.php && echo \'EXISTS\' || echo \'MISSING\'" 2>&1');
if (strpos($result, 'EXISTS') !== false) {
    echo "✅ View file exists on server\n";
    
    // Check file size
    $size = shell_exec('ssh root@145.14.158.101 "stat -f%z /var/www/cultural-translate-platform/resources/views/dashboard/file-translation/index.blade.php 2>/dev/null || stat -c%s /var/www/cultural-translate-platform/resources/views/dashboard/file-translation/index.blade.php" 2>&1');
    if ($size) {
        echo "   File size: " . trim($size) . " bytes\n";
    }
    
    // Check for CSRF token in view
    $csrfCheck = shell_exec('ssh root@145.14.158.101 "grep -c \'csrf-token\' /var/www/cultural-translate-platform/resources/views/dashboard/file-translation/index.blade.php" 2>&1');
    if (intval(trim($csrfCheck)) > 0) {
        echo "✅ CSRF token usage found in view (" . trim($csrfCheck) . " occurrences)\n";
    } else {
        echo "⚠️  CSRF token NOT used in view\n";
    }
} else {
    echo "❌ View file missing on server\n";
}

echo "\n";

// Test 3: Check layout file for CSRF meta tag
echo "Test 3: Checking layout file for CSRF meta tag...\n";
$layoutCheck = shell_exec('ssh root@145.14.158.101 "grep -c \'csrf-token\' /var/www/cultural-translate-platform/resources/views/layouts/app.blade.php" 2>&1');
if (intval(trim($layoutCheck)) > 0) {
    echo "✅ CSRF meta tag in layout (" . trim($layoutCheck) . " occurrences)\n";
} else {
    echo "⚠️  CSRF meta tag NOT in layout\n";
}

echo "\n";

// Test 4: Check routes
echo "Test 4: Checking routes...\n";
$routesCheck = shell_exec('ssh root@145.14.158.101 "cd /var/www/cultural-translate-platform && php artisan route:list --path=file-translation 2>&1" 2>&1');
if ($routesCheck) {
    echo "Routes found:\n";
    $lines = explode("\n", trim($routesCheck));
    foreach ($lines as $line) {
        if (strpos($line, 'file-translation') !== false) {
            echo "   " . trim($line) . "\n";
        }
    }
}

echo "\n=== CSRF Token Solution ===\n\n";
echo "The page uses Laravel's built-in CSRF protection.\n";
echo "The CSRF token is automatically included in the layout's <head> section.\n";
echo "The JavaScript code fetches it using: document.querySelector('meta[name=\"csrf-token\"]').content\n\n";

echo "To test the upload functionality:\n";
echo "1. Login to: https://culturaltranslate.com/login\n";
echo "2. Navigate to: https://culturaltranslate.com/dashboard/file-translation\n";
echo "3. The CSRF token will be automatically included in all requests\n";
echo "4. Upload a file and test the functionality\n\n";

echo "If you still get CSRF errors, try:\n";
echo "1. Clear browser cache and cookies\n";
echo "2. php artisan config:clear (on server)\n";
echo "3. php artisan view:clear (on server)\n";
echo "4. Check session configuration in .env\n\n";
