<?php

// Fix admin permissions - Only admin@culturaltranslate.com should have admin access

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Fixing Admin Permissions\n";
echo "========================\n\n";

// Set all users back to 'user' role except admin@culturaltranslate.com
$users = \App\Models\User::whereNotIn('email', ['admin@culturaltranslate.com'])->get();

echo "Resetting roles for " . $users->count() . " users...\n\n";

foreach ($users as $user) {
    $oldRole = $user->role;
    $user->role = 'user';
    $user->save();
    
    echo "✅ User ID {$user->id} ({$user->email}): {$oldRole} → user\n";
}

echo "\n";

// Ensure admin@culturaltranslate.com has super_admin role
$admin = \App\Models\User::where('email', 'admin@culturaltranslate.com')->first();

if ($admin) {
    $admin->role = 'super_admin';
    $admin->account_status = 'active';
    $admin->save();
    
    echo "✅ Admin user confirmed:\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
    echo "   Status: {$admin->account_status}\n\n";
} else {
    echo "❌ Admin user not found!\n\n";
}

echo "========================\n";
echo "✅ Permissions fixed!\n";
echo "Only admin@culturaltranslate.com has admin access now.\n";
