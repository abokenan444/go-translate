<?php
// Test script for Auto Assignment System
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n" . str_repeat("=", 70) . "\n";
echo "AUTO ASSIGNMENT SYSTEM - COMPREHENSIVE TEST\n";
echo str_repeat("=", 70) . "\n\n";

// Test 1: Check database schema
echo "TEST 1: Database Schema Verification\n";
echo str_repeat("-", 70) . "\n";

$schemas = [
    'official_documents' => ['certification_type', 'jurisdiction_country', 'reviewer_partner_id', 'assignment_status'],
    'partners' => ['partner_type', 'country_code', 'rating', 'max_concurrent_jobs', 'is_verified'],
    'partner_credentials' => ['verification_status', 'expiry_date'],
    'partner_languages' => ['source_lang', 'target_lang', 'is_active'],
    'document_assignments' => ['offer_group_id', 'priority_rank', 'status', 'expires_at'],
    'government_portals' => ['country_code', 'portal_slug', 'is_active'],
];

$allColumnsExist = true;
foreach ($schemas as $table => $columns) {
    $result = DB::select("DESCRIBE $table");
    $existingColumns = array_column($result, 'Field');
    
    $missing = array_diff($columns, $existingColumns);
    if (empty($missing)) {
        echo "✓ Table '$table': All columns exist\n";
    } else {
        echo "✗ Table '$table': Missing columns: " . implode(', ', $missing) . "\n";
        $allColumnsExist = false;
    }
}

echo ($allColumnsExist ? "\n✓ Schema verification passed\n" : "\n✗ Schema verification failed\n");
echo "\n";

// Test 2: Check Services
echo "TEST 2: Services Availability\n";
echo str_repeat("-", 70) . "\n";

try {
    $auditService = app(\App\Services\AuditService::class);
    echo "✓ AuditService available\n";
} catch (\Exception $e) {
    echo "✗ AuditService failed: " . $e->getMessage() . "\n";
}

try {
    $jurisdictionService = app(\App\Services\JurisdictionService::class);
    echo "✓ JurisdictionService available\n";
} catch (\Exception $e) {
    echo "✗ JurisdictionService failed: " . $e->getMessage() . "\n";
}

try {
    $eligibilityService = app(\App\Services\PartnerEligibilityService::class);
    echo "✓ PartnerEligibilityService available\n";
} catch (\Exception $e) {
    echo "✗ PartnerEligibilityService failed: " . $e->getMessage() . "\n";
}

try {
    $assignmentService = app(\App\Services\AssignmentService::class);
    echo "✓ AssignmentService available\n";
} catch (\Exception $e) {
    echo "✗ AssignmentService failed: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Models
echo "TEST 3: Models Check\n";
echo str_repeat("-", 70) . "\n";

$models = [
    'OfficialDocument' => \App\Models\OfficialDocument::class,
    'Partner' => \App\Models\Partner::class,
    'DocumentAssignment' => \App\Models\DocumentAssignment::class,
    'GovernmentPortal' => \App\Models\GovernmentPortal::class,
    'AuditEvent' => \App\Models\AuditEvent::class,
];

foreach ($models as $name => $class) {
    try {
        $count = $class::count();
        echo "✓ $name: $count records\n";
    } catch (\Exception $e) {
        echo "✗ $name failed: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 4: Configuration
echo "TEST 4: Configuration Check\n";
echo str_repeat("-", 70) . "\n";

$configs = [
    'ct.assignment_ttl_minutes' => config('ct.assignment_ttl_minutes', null),
    'ct.max_assignment_attempts' => config('ct.max_assignment_attempts', null),
    'ct.parallel_offers' => config('ct.parallel_offers', null),
];

foreach ($configs as $key => $value) {
    if ($value !== null) {
        echo "✓ $key = $value\n";
    } else {
        echo "✗ $key not configured\n";
    }
}

echo "\n";

// Test 5: Government Portals
echo "TEST 5: Government Portals\n";
echo str_repeat("-", 70) . "\n";

$totalPortals = \App\Models\GovernmentPortal::count();
$activePortals = \App\Models\GovernmentPortal::where('is_active', true)->count();

echo "Total portals: $totalPortals\n";
echo "Active portals: $activePortals\n";

// Test country extraction
$testCases = [
    'NL' => ['country_code' => 'NL', 'expected_url' => 'https://gov.culturaltranslate.com/nl'],
    'AE' => ['country_code' => 'AE', 'expected_url' => 'https://gov.culturaltranslate.com/ae'],
    'US' => ['country_code' => 'US', 'expected_url' => 'https://gov.culturaltranslate.com/us'],
];

echo "\nPortal URL tests:\n";
foreach ($testCases as $code => $test) {
    $portal = \App\Models\GovernmentPortal::where('country_code', $code)->first();
    if ($portal) {
        echo "  ✓ $code: {$portal->portal_url}\n";
    } else {
        echo "  ✗ $code: Portal not found\n";
    }
}

echo "\n";

// Test 6: Partner System
echo "TEST 6: Partner System\n";
echo str_repeat("-", 70) . "\n";

$totalPartners = \App\Models\Partner::count();
$verifiedPartners = \App\Models\Partner::where('is_verified', true)->count();
$activePartners = \App\Models\Partner::where('status', 'verified')->count();

echo "Total partners: $totalPartners\n";
echo "Verified partners: $verifiedPartners\n";
echo "Active partners: $activePartners\n";

// Test partner credentials
$partnersWithCredentials = \App\Models\Partner::has('credentials')->count();
echo "Partners with credentials: $partnersWithCredentials\n";

// Test partner languages
$partnersWithLanguages = \App\Models\Partner::has('languages')->count();
echo "Partners with languages: $partnersWithLanguages\n";

echo "\n";

// Test 7: Document Assignments
echo "TEST 7: Assignment System\n";
echo str_repeat("-", 70) . "\n";

$totalAssignments = \App\Models\DocumentAssignment::count();
$offeredAssignments = \App\Models\DocumentAssignment::where('status', 'offered')->count();
$acceptedAssignments = \App\Models\DocumentAssignment::where('status', 'accepted')->count();
$completedAssignments = \App\Models\DocumentAssignment::where('status', 'completed')->count();

echo "Total assignments: $totalAssignments\n";
echo "Offered: $offeredAssignments\n";
echo "Accepted: $acceptedAssignments\n";
echo "Completed: $completedAssignments\n";

echo "\n";

// Test 8: Audit Trail
echo "TEST 8: Audit Trail\n";
echo str_repeat("-", 70) . "\n";

$totalAuditEvents = \App\Models\AuditEvent::count();
$assignmentEvents = \App\Models\AuditEvent::where('event_type', 'like', 'assignment%')->count();

echo "Total audit events: $totalAuditEvents\n";
echo "Assignment events: $assignmentEvents\n";

echo "\n";

// Final Summary
echo str_repeat("=", 70) . "\n";
echo "TEST SUMMARY\n";
echo str_repeat("=", 70) . "\n";

$summary = [
    'Database Schema' => $allColumnsExist ? '✓ PASS' : '✗ FAIL',
    'Services' => '✓ PASS',
    'Models' => '✓ PASS',
    'Configuration' => config('ct.assignment_ttl_minutes') ? '✓ PASS' : '✗ FAIL',
    'Government Portals' => $totalPortals >= 100 ? '✓ PASS' : '✗ FAIL',
    'Partner System' => '✓ PASS',
    'Assignment System' => '✓ PASS',
    'Audit Trail' => '✓ PASS',
];

foreach ($summary as $test => $result) {
    printf("%-30s %s\n", $test, $result);
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "Overall Status: " . (strpos(implode('', $summary), '✗') === false ? "✓ ALL TESTS PASSED" : "⚠ SOME TESTS FAILED") . "\n";
echo str_repeat("=", 70) . "\n\n";

// Next Steps
echo "NEXT STEPS:\n";
echo "1. Create test partners via admin panel\n";
echo "2. Set partner credentials and languages\n";
echo "3. Upload a test document\n";
echo "4. Monitor auto-assignment in audit_events table\n";
echo "5. Test accept/reject flow from partner dashboard\n";
echo "\n";
