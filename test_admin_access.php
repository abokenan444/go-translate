<?php

// Test admin panel access

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Admin Panel Access\n";
echo "===========================\n\n";

// Get all super_admin users
$admins = \App\Models\User::whereIn('role', ['super_admin', 'support_admin', 'financial_admin', 'technical_admin'])
    ->where('account_status', 'active')
    ->get();

echo "Found " . $admins->count() . " admin users:\n\n";

foreach ($admins as $admin) {
    echo "ID: {$admin->id}\n";
    echo "Name: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Role: {$admin->role}\n";
    echo "Status: {$admin->account_status}\n";
    
    // Test canAccessPanel
    $panel = new \Filament\Panel('admin');
    $canAccess = $admin->canAccessPanel($panel);
    
    echo "Can Access Panel: " . ($canAccess ? '✅ YES' : '❌ NO') . "\n";
    echo "---\n\n";
}

// Check if there's a logged in user
if (auth()->check()) {
    echo "Current logged in user:\n";
    $user = auth()->user();
    echo "ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    
    $panel = new \Filament\Panel('admin');
    $canAccess = $user->canAccessPanel($panel);
    echo "Can Access Panel: " . ($canAccess ? '✅ YES' : '❌ NO') . "\n";
} else {
    echo "No user currently logged in.\n";
}
