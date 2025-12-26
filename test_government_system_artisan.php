#!/usr/bin/env php
<?php

/**
 * Test Government & Authority System
 * Run: php test_government_system_artisan.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Government & Authority System ===\n\n";

// 1. Check if authority users exist
echo "1. Checking Authority Users...\n";
$authorityOfficer = App\Models\User::where('account_type', 'gov_authority_officer')->first();
$authoritySupervisor = App\Models\User::where('account_type', 'gov_authority_supervisor')->first();

if (!$authorityOfficer) {
    echo "   Creating Authority Officer...\n";
    $authorityOfficer = App\Models\User::create([
        'name' => 'Test Authority Officer',
        'email' => 'authority.officer@test.gov',
        'password' => bcrypt('password123'),
        'account_type' => 'gov_authority_officer',
        'is_government_verified' => true,
    ]);
    echo "   ✅ Created: {$authorityOfficer->email} (ID: {$authorityOfficer->id})\n";
} else {
    echo "   ✅ Found Officer: {$authorityOfficer->email} (ID: {$authorityOfficer->id})\n";
}

if (!$authoritySupervisor) {
    echo "   Creating Authority Supervisor...\n";
    $authoritySupervisor = App\Models\User::create([
        'name' => 'Test Authority Supervisor',
        'email' => 'authority.supervisor@test.gov',
        'password' => bcrypt('password123'),
        'account_type' => 'gov_authority_supervisor',
        'is_government_verified' => true,
    ]);
    echo "   ✅ Created: {$authoritySupervisor->email} (ID: {$authoritySupervisor->id})\n";
} else {
    echo "   ✅ Found Supervisor: {$authoritySupervisor->email} (ID: {$authoritySupervisor->id})\n";
}

// 2. Check government client users
echo "\n2. Checking Government Client Users...\n";
$govClient = App\Models\User::where('account_type', 'government')->first();
if (!$govClient) {
    echo "   Creating Government Client...\n";
    $govClient = App\Models\User::create([
        'name' => 'Test Government Client',
        'email' => 'gov.client@test.gov',
        'password' => bcrypt('password123'),
        'account_type' => 'government',
        'is_government_verified' => true,
    ]);
    echo "   ✅ Created: {$govClient->email} (ID: {$govClient->id})\n";
} else {
    echo "   ✅ Found Client: {$govClient->email} (ID: {$govClient->id})\n";
}

// 3. Check certificates
echo "\n3. Checking Certificates...\n";
$certificate = DB::table('document_certificates')->first();
if ($certificate) {
    echo "   ✅ Found Certificate ID: {$certificate->id}\n";
    $certNumber = $certificate->certificate_number ?? $certificate->cert_number ?? 'N/A';
    echo "   Certificate Number: {$certNumber}\n";
    echo "   Status: {$certificate->status}\n";
} else {
    echo "   ⚠️ No certificates found\n";
}

// 4. Check decision ledger
echo "\n4. Checking Decision Ledger...\n";
try {
    $ledgerCount = DB::table('decision_ledger_events')->count();
    echo "   Total Events: {$ledgerCount}\n";
    if ($ledgerCount > 0) {
        $lastEvent = DB::table('decision_ledger_events')->orderBy('id', 'desc')->first();
        echo "   Latest Event: {$lastEvent->event_type}\n";
        echo "   Hash: " . substr($lastEvent->hash ?? 'N/A', 0, 16) . "...\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️ Error: " . $e->getMessage() . "\n";
}

// 5. Check certificate_revocation_requests table
echo "\n5. Checking Revocation Requests Table...\n";
try {
    $requestsCount = DB::table('certificate_revocation_requests')->count();
    echo "   ✅ Table exists - Total Requests: {$requestsCount}\n";
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 6. Check certificate_revocations table
echo "\n6. Checking Certificate Revocations Table...\n";
try {
    $revocationsCount = DB::table('certificate_revocations')->count();
    echo "   ✅ Table exists - Total Revocations: {$revocationsCount}\n";
    
    // Check new jurisdiction columns
    $columns = DB::select("SHOW COLUMNS FROM certificate_revocations LIKE 'jurisdiction%'");
    if (count($columns) > 0) {
        echo "   ✅ Jurisdiction columns added:\n";
        foreach ($columns as $col) {
            echo "      - {$col->Field}\n";
        }
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 7. Check government entities
echo "\n7. Checking Government Entities...\n";
try {
    $entityCount = DB::table('gov_entities')->count();
    echo "   Total Entities: {$entityCount}\n";
    if ($entityCount > 0) {
        $entity = DB::table('gov_entities')->first();
        echo "   First Entity ID: {$entity->id}\n";
        echo "   Name: {$entity->name}\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️ Table may not exist: " . $e->getMessage() . "\n";
}

// 8. Test Middleware existence
echo "\n8. Checking Middleware...\n";
if (class_exists('App\Http\Middleware\EnsureAuthorityAccess')) {
    echo "   ✅ EnsureAuthorityAccess middleware exists\n";
} else {
    echo "   ❌ EnsureAuthorityAccess middleware NOT found\n";
}

// 9. Test Services existence
echo "\n9. Checking Services...\n";
$services = [
    'App\Services\EvidencePackageService',
    'App\Services\RevocationReceiptService',
    'App\Services\DecisionLedgerService',
    'App\Services\GovernmentAccessService',
];
foreach ($services as $service) {
    if (class_exists($service)) {
        echo "   ✅ " . basename(str_replace('\\', '/', $service)) . " exists\n";
    } else {
        echo "   ❌ " . basename(str_replace('\\', '/', $service)) . " NOT found\n";
    }
}

// 10. Test Controllers existence
echo "\n10. Checking Controllers...\n";
$controllers = [
    'App\Http\Controllers\Authority\AuthorityDashboardController',
    'App\Http\Controllers\Government\ComplianceReportController',
];
foreach ($controllers as $controller) {
    if (class_exists($controller)) {
        echo "   ✅ " . basename(str_replace('\\', '/', $controller)) . " exists\n";
    } else {
        echo "   ❌ " . basename(str_replace('\\', '/', $controller)) . " NOT found\n";
    }
}

// 11. Test URLs to verify
echo "\n11. Testing URLs...\n";
echo "   Authority Dashboard: https://authority.culturaltranslate.com/authority/dashboard\n";
echo "   Government Dashboard: https://government.culturaltranslate.com/dashboard\n";
echo "   Compliance Report: https://culturaltranslate.com/government/compliance-report\n";
if ($certificate) {
    echo "   Verify Certificate: https://culturaltranslate.com/verify/{$certificate->id}\n";
}

echo "\n=== Test Complete ===\n";
echo "\n📝 Test Credentials:\n";
echo "   Authority Officer: authority.officer@test.gov / password123\n";
echo "   Authority Supervisor: authority.supervisor@test.gov / password123\n";
echo "   Government Client: gov.client@test.gov / password123\n";

echo "\n🔍 Next Steps:\n";
echo "1. Login as Authority Officer at: https://culturaltranslate.com/login\n";
echo "2. Visit Authority Dashboard: https://authority.culturaltranslate.com/authority/dashboard\n";
echo "3. Test certificate verification with ID: " . ($certificate->id ?? 'N/A') . "\n";
