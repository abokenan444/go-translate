<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Certificate;

try {
    echo "Testing Certificate Verification Endpoint\n";
    echo "==========================================\n\n";
    
    // Test 1: Find certificate by cert_id
    echo "1. Finding certificate by cert_id...\n";
    $cert = Certificate::where('cert_id', 'CT-UPDATED-94A959F8')
        ->with(['revocation', 'document'])
        ->first();
    
    if ($cert) {
        echo "✅ Certificate found: ID {$cert->id}\n";
        echo "   Cert ID: {$cert->cert_id}\n";
        echo "   Status: {$cert->status}\n";
        echo "   Has Revocation: " . ($cert->revocation ? 'Yes' : 'No') . "\n";
        
        // Determine legal status
        $legalStatus = 'valid';
        if ($cert->revocation) {
            $legalStatus = $cert->revocation->action === 'revoked' ? 'revoked' : 'frozen';
        }
        echo "   Legal Status: {$legalStatus}\n\n";
    } else {
        echo "❌ Certificate not found\n\n";
    }
    
    // Test 2: Check if CertificateRevocation model exists
    echo "2. Checking CertificateRevocation model...\n";
    if (class_exists('App\Models\CertificateRevocation')) {
        echo "✅ CertificateRevocation model exists\n\n";
        
        // Check if table exists
        $revocations = DB::table('certificate_revocations')->count();
        echo "   Revocations count: {$revocations}\n\n";
    } else {
        echo "❌ CertificateRevocation model not found\n\n";
    }
    
    // Test 3: Check view exists
    echo "3. Checking view file...\n";
    $viewPath = resource_path('views/certificates/result.blade.php');
    if (file_exists($viewPath)) {
        echo "✅ View file exists: {$viewPath}\n";
        echo "   File size: " . filesize($viewPath) . " bytes\n\n";
        
        // Check if view contains legalStatus
        $content = file_get_contents($viewPath);
        if (strpos($content, 'legalStatus') !== false) {
            echo "✅ View contains legalStatus variable\n\n";
        } else {
            echo "⚠️ View does not contain legalStatus variable\n\n";
        }
    } else {
        echo "❌ View file not found\n\n";
    }
    
    // Test 4: Simulate controller logic
    echo "4. Simulating controller logic...\n";
    $certificateId = 'CT-UPDATED-94A959F8';
    $certificate = Certificate::where('cert_id', $certificateId)
        ->orWhere('id', $certificateId)
        ->with(['revocation', 'document'])
        ->first();
    
    if ($certificate) {
        $legalStatus = 'valid';
        if ($certificate->revocation) {
            $legalStatus = $certificate->revocation->action === 'revoked' ? 'revoked' : 'frozen';
        }
        
        echo "✅ Controller logic works:\n";
        echo "   - Certificate found: Yes\n";
        echo "   - Legal Status: {$legalStatus}\n";
        echo "   - Revocation: " . ($certificate->revocation ? 'Yes' : 'No') . "\n";
        echo "   - View data ready: ✅\n\n";
    } else {
        echo "❌ Controller logic failed\n\n";
    }
    
    echo "==========================================\n";
    echo "All tests completed!\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
