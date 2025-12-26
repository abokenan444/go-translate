<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing SMTP Mail ===\n\n";

try {
    // Send a test email
    \Illuminate\Support\Facades\Mail::raw('This is a test email from CulturalTranslate platform. All systems are operational!', function ($message) {
        $message->to('info@culturaltranslate.com')
                ->subject('CulturalTranslate - System Test');
    });
    
    echo "✅ Test email sent successfully!\n";
    echo "Check inbox at info@culturaltranslate.com\n";
} catch (Exception $e) {
    echo "❌ Mail Error: " . $e->getMessage() . "\n";
}
