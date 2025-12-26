<?php

// Make a user super admin

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

if ($argc < 2) {
    echo "Usage: php make_super_admin.php <email>\n";
    echo "\nExample: php make_super_admin.php user@example.com\n";
    exit(1);
}

$email = $argv[1];

echo "Making Super Admin\n";
echo "==================\n\n";

$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found with email: {$email}\n";
    exit(1);
}

echo "Found user:\n";
echo "ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Current Role: {$user->role}\n";
echo "Current Status: {$user->account_status}\n\n";

// Update to super_admin
$user->role = 'super_admin';
$user->account_status = 'active';
$user->save();

echo "✅ User updated successfully!\n\n";
echo "New Role: {$user->role}\n";
echo "New Status: {$user->account_status}\n\n";

// Test access
$panel = new \Filament\Panel('admin');
$canAccess = $user->canAccessPanel($panel);

echo "Can Access Admin Panel: " . ($canAccess ? '✅ YES' : '❌ NO') . "\n";
