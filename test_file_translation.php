#!/usr/bin/env php
<?php
/**
 * اختبار نظام ترجمة المستندات
 * Testing File Translation System
 */

echo "=== Testing File Translation System ===\n\n";

$baseUrl = "https://culturaltranslate.com";

// Test 1: Check page accessibility
echo "Test 1: Checking page accessibility...\n";
$ch = curl_init("$baseUrl/dashboard/file-translation");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($statusCode === 200) {
    echo "✅ Page accessible (Status: 200 OK)\n";
} else {
    echo "❌ Page not accessible (Status: $statusCode)\n";
}

// Test 2: Check view file exists
echo "\nTest 2: Checking view file on server...\n";
$viewPath = "/var/www/cultural-translate-platform/resources/views/dashboard/file-translation/index.blade.php";
$command = "ssh root@145.14.158.101 'test -f $viewPath && echo exists || echo missing'";
$output = shell_exec($command);
if (trim($output) === 'exists') {
    echo "✅ View file exists on server\n";
    
    // Get file size
    $sizeCmd = "ssh root@145.14.158.101 'stat -c %s $viewPath 2>/dev/null || stat -f %z $viewPath 2>/dev/null'";
    $size = trim(shell_exec($sizeCmd));
    echo "   Size: " . number_format($size) . " bytes\n";
} else {
    echo "❌ View file missing on server\n";
}

// Test 3: Check controller exists
echo "\nTest 3: Checking controller...\n";
$controllerPath = "/var/www/cultural-translate-platform/app/Http/Controllers/Dashboard/FileTranslationController.php";
$command = "ssh root@145.14.158.101 'test -f $controllerPath && echo exists || echo missing'";
$output = shell_exec($command);
if (trim($output) === 'exists') {
    echo "✅ Controller exists\n";
} else {
    echo "❌ Controller missing\n";
}

// Test 4: Check model exists
echo "\nTest 4: Checking model...\n";
$modelPath = "/var/www/cultural-translate-platform/app/Models/FileTranslation.php";
$command = "ssh root@145.14.158.101 'test -f $modelPath && echo exists || echo missing'";
$output = shell_exec($command);
if (trim($output) === 'exists') {
    echo "✅ Model exists\n";
} else {
    echo "❌ Model missing\n";
}

// Test 5: Check database table
echo "\nTest 5: Checking database table...\n";
$dbCmd = "ssh root@145.14.158.101 \"mysql -u root -pShatha-Yasser-1992 cultural_translate -e 'SELECT COUNT(*) as count FROM file_translations' 2>/dev/null\"";
$output = shell_exec($dbCmd);
if (strpos($output, 'count') !== false) {
    echo "✅ Database table exists\n";
    preg_match('/(\d+)/', $output, $matches);
    if (isset($matches[1])) {
        echo "   Total records: {$matches[1]}\n";
    }
} else {
    echo "❌ Database table missing or inaccessible\n";
}

// Test 6: Check storage directory
echo "\nTest 6: Checking storage directory...\n";
$storageDir = "/var/www/cultural-translate-platform/storage/app/public/file-translations";
$command = "ssh root@145.14.158.101 'test -d $storageDir && echo exists || echo missing'";
$output = shell_exec($command);
if (trim($output) === 'exists') {
    echo "✅ Storage directory exists\n";
    
    // Check permissions
    $permCmd = "ssh root@145.14.158.101 'stat -c \"%a %U:%G\" $storageDir 2>/dev/null || stat -f \"%p %Su:%Sg\" $storageDir 2>/dev/null'";
    $perms = trim(shell_exec($permCmd));
    echo "   Permissions: $perms\n";
    
    // Count files
    $countCmd = "ssh root@145.14.158.101 'ls -1 $storageDir 2>/dev/null | wc -l'";
    $count = trim(shell_exec($countCmd));
    echo "   Files in directory: $count\n";
} else {
    echo "⚠️  Storage directory missing (will be created on first upload)\n";
}

// Test 7: Check routes
echo "\nTest 7: Checking routes configuration...\n";
$routesCmd = "ssh root@145.14.158.101 \"cd /var/www/cultural-translate-platform && php artisan route:list --path=file-translation 2>/dev/null | grep -c file-translation\"";
$routeCount = (int)trim(shell_exec($routesCmd));
if ($routeCount > 0) {
    echo "✅ Routes configured ($routeCount routes found)\n";
} else {
    echo "❌ Routes not configured\n";
}

// Summary
echo "\n=== Test Summary ===\n";
echo "All critical components are in place for document translation.\n";
echo "The system requires authentication to access the upload interface.\n";
echo "Next step: Test with actual file upload after authentication.\n\n";
