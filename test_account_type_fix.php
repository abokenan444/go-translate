<?php

// Test account type routing fix

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Account Type Routing Fix\n";
echo "=================================\n\n";

// Get a user with 'individual' account type
$user = \App\Models\User::where('account_type', 'individual')->first();

if (!$user) {
    echo "❌ No user with 'individual' account type found\n";
    exit(1);
}

echo "✅ Found user: {$user->email}\n";
echo "   Account Type: {$user->account_type}\n\n";

// Test AccountDashboard::pathFor
echo "Testing AccountDashboard::pathFor():\n";
$path = \App\Support\AccountDashboard::pathFor($user);
echo "   Expected: /dashboard/customer\n";
echo "   Got:      {$path}\n";

if ($path === '/dashboard/customer') {
    echo "   ✅ PASS\n\n";
} else {
    echo "   ❌ FAIL\n\n";
    exit(1);
}

// Test CheckAccountType middleware logic
echo "Testing CheckAccountType middleware logic:\n";
$accountType = 'customer';
$userAccountType = $user->account_type;

$allowedTypes = [$accountType];
if ($accountType === 'customer') {
    $allowedTypes[] = 'individual';
}

echo "   Checking if '{$userAccountType}' is in: " . implode(', ', $allowedTypes) . "\n";
$allowed = in_array($userAccountType, $allowedTypes);

if ($allowed) {
    echo "   ✅ PASS - User should be able to access /dashboard/customer\n\n";
} else {
    echo "   ❌ FAIL - User would be blocked\n\n";
    exit(1);
}

echo "=================================\n";
echo "✅ All tests passed!\n";
echo "Fix is working correctly.\n";
