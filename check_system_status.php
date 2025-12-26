<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== SYSTEM STATUS CHECK ===\n\n";

// Check users table
echo "1. Users Table Columns:\n";
$userColumns = Schema::getColumnListing('users');
echo implode(', ', $userColumns) . "\n\n";

// Check if government-related columns exist
$govColumns = ['account_type', 'is_government_verified', 'government_verification_status'];
echo "2. Government Columns Status:\n";
foreach ($govColumns as $col) {
    $exists = in_array($col, $userColumns) ? '✓' : '✗';
    echo "   {$col}: {$exists}\n";
}

// Check if government_verifications table exists
echo "\n3. Government Verifications Table:\n";
if (Schema::hasTable('government_verifications')) {
    echo "   ✓ Exists\n";
    echo "   Columns: " . implode(', ', Schema::getColumnListing('government_verifications')) . "\n";
} else {
    echo "   ✗ Does not exist\n";
}

// Check if government_documents table exists
echo "\n4. Government Documents Table:\n";
if (Schema::hasTable('government_documents')) {
    echo "   ✓ Exists\n";
} else {
    echo "   ✗ Does not exist\n";
}

// Check if government_audit_logs table exists
echo "\n5. Government Audit Logs Table:\n";
if (Schema::hasTable('government_audit_logs')) {
    echo "   ✓ Exists\n";
} else {
    echo "   ✗ Does not exist\n";
}

// Check Filament Resources
echo "\n6. Filament Resources:\n";
$resources = [
    'GovernmentVerificationResource',
    'SubscriptionPlanResource',
];
foreach ($resources as $resource) {
    $path = "app/Filament/Resources/{$resource}.php";
    $exists = file_exists(__DIR__ . '/' . $path) ? '✓' : '✗';
    echo "   {$resource}: {$exists}\n";
}

// Check subscription plans stripe_price_id
echo "\n7. Subscription Plans with Stripe:\n";
$plans = DB::table('subscription_plans')->get(['name', 'slug', 'stripe_price_id']);
foreach ($plans as $plan) {
    $hasStripe = !empty($plan->stripe_price_id) ? '✓' : '✗';
    echo "   {$plan->name}: {$hasStripe} ({$plan->stripe_price_id})\n";
}

echo "\n=== CHECK COMPLETE ===\n";
