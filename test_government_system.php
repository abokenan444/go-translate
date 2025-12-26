<?php

/**
 * Test Government & Authority System
 * Run: php artisan tinker < test_government_system.php
 */

echo "=== Testing Government & Authority System ===\n\n";

// 1. Check if authority users exist
echo "1. Checking Authority Users...\n";
$authorityOfficer = \App\Models\User::where('account_type', 'gov_authority_officer')->first();
$authoritySupervisor = \App\Models\User::where('account_type', 'gov_authority_supervisor')->first();

if (!$authorityOfficer) {
    echo "   Creating Authority Officer...\n";
    $authorityOfficer = \App\Models\User::create([
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
    $authoritySupervisor = \App\Models\User::create([
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
$govClient = \App\Models\User::where('account_type', 'government')->first();
if (!$govClient) {
    echo "   Creating Government Client...\n";
    $govClient = \App\Models\User::create([
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
$certificate = \App\Models\Certificate::first();
if ($certificate) {
    echo "   ✅ Found Certificate ID: {$certificate->id}\n";
    echo "   Certificate Number: {$certificate->certificate_number}\n";
    echo "   Status: {$certificate->status}\n";
    echo "   Legal Status: " . ($certificate->legal_status ?? 'valid') . "\n";
} else {
    echo "   ⚠️ No certificates found\n";
}

// 4. Check decision ledger
echo "\n4. Checking Decision Ledger...\n";
$ledgerCount = \App\Models\DecisionLedgerEvent::count();
echo "   Total Events: {$ledgerCount}\n";
if ($ledgerCount > 0) {
    $lastEvent = \App\Models\DecisionLedgerEvent::latest()->first();
    echo "   Latest Event: {$lastEvent->event_type}\n";
    echo "   Hash: " . substr($lastEvent->hash, 0, 16) . "...\n";
}

// 5. Check certificate_revocation_requests table
echo "\n5. Checking Revocation Requests Table...\n";
try {
    $requestsCount = \App\Models\CertificateRevocationRequest::count();
    echo "   ✅ Table exists - Total Requests: {$requestsCount}\n";
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 6. Check government entities
echo "\n6. Checking Government Entities...\n";
try {
    $entityCount = \DB::table('gov_entities')->count();
    echo "   Total Entities: {$entityCount}\n";
    if ($entityCount > 0) {
        $entity = \DB::table('gov_entities')->first();
        echo "   First Entity ID: {$entity->id}\n";
        echo "   Name: {$entity->name}\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️ Table may not exist: " . $e->getMessage() . "\n";
}

// 7. Test URLs to verify
echo "\n7. Testing URLs...\n";
echo "   Authority Dashboard: https://authority.culturaltranslate.com/authority/dashboard\n";
echo "   Government Dashboard: https://government.culturaltranslate.com/dashboard\n";
echo "   Compliance Report: https://culturaltranslate.com/government/compliance-report\n";
if ($certificate) {
    echo "   Verify Certificate: https://culturaltranslate.com/verify/{$certificate->id}\n";
}

echo "\n=== Test Complete ===\n";
echo "\nTest Credentials:\n";
echo "Authority Officer: authority.officer@test.gov / password123\n";
echo "Authority Supervisor: authority.supervisor@test.gov / password123\n";
echo "Government Client: gov.client@test.gov / password123\n";
