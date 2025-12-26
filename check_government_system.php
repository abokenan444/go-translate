<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Government Protection System Check ===\n\n";

// Check tables
$tables = [
    'government_verifications',
    'government_documents', 
    'government_audit_logs',
    'government_email_domains',
    'government_communications'
];

echo "📊 Database Tables:\n";
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $count = DB::table($table)->count();
        echo "  ✅ $table (Records: $count)\n";
    } else {
        echo "  ❌ $table - MISSING\n";
    }
}

// Check User columns
echo "\n👤 User Table Government Columns:\n";
$userColumns = ['account_type', 'is_government_verified', 'government_verified_at', 'government_badge', 'government_access_level', 'government_verification_id'];
foreach ($userColumns as $col) {
    if (Schema::hasColumn('users', $col)) {
        echo "  ✅ users.$col\n";
    } else {
        echo "  ❌ users.$col - MISSING\n";
    }
}

// Check email domains
echo "\n📧 Government Email Domains:\n";
if (Schema::hasTable('government_email_domains')) {
    $domains = DB::table('government_email_domains')->where('is_active', true)->count();
    echo "  Total active domains: $domains\n";
} else {
    echo "  ❌ Table not created yet\n";
}

// Check pending verifications
echo "\n📋 Verification Statistics:\n";
if (Schema::hasTable('government_verifications')) {
    $stats = DB::table('government_verifications')
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->get();
    foreach ($stats as $stat) {
        echo "  - {$stat->status}: {$stat->count}\n";
    }
    if ($stats->isEmpty()) {
        echo "  No verification requests yet\n";
    }
} else {
    echo "  ❌ Table not created yet\n";
}

echo "\n✅ Check complete!\n";
