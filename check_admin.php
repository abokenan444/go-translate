<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== Checking Super Admin Account ===\n\n";

$user = DB::table('users')->where('email', 'admin@culturaltranslate.com')->first();

if ($user) {
    echo "✅ User Found!\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . ($user->role ?? 'N/A') . "\n";
    echo "Is Admin: " . (isset($user->is_admin) ? ($user->is_admin ? 'Yes' : 'No') : 'N/A') . "\n";
    echo "Is Super Admin: " . (isset($user->is_super_admin) ? ($user->is_super_admin ? 'Yes' : 'No') : 'N/A') . "\n";
    
    // Update to ensure full permissions
    $updates = [
        'role' => 'super_admin',
        'is_admin' => 1,
        'is_super_admin' => 1,
        'email_verified_at' => now(),
        'password' => Hash::make('Yasser-591983'),
        'updated_at' => now(),
    ];
    
    DB::table('users')->where('email', 'admin@culturaltranslate.com')->update($updates);
    echo "\n✅ Updated with full super admin permissions!\n";
    echo "Password reset to: Yasser-591983\n";
    
} else {
    echo "❌ User NOT found - Creating new super admin...\n";
    
    DB::table('users')->insert([
        'name' => 'Super Admin',
        'email' => 'admin@culturaltranslate.com',
        'password' => Hash::make('Yasser-591983'),
        'role' => 'super_admin',
        'is_admin' => 1,
        'is_super_admin' => 1,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✅ Super Admin created successfully!\n";
    echo "Email: admin@culturaltranslate.com\n";
    echo "Password: Yasser-591983\n";
}

// Verify final state
echo "\n=== Final Verification ===\n";
$user = DB::table('users')->where('email', 'admin@culturaltranslate.com')->first();
if ($user) {
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . ($user->role ?? 'N/A') . "\n";
    echo "Is Admin: " . (isset($user->is_admin) ? ($user->is_admin ? 'Yes' : 'No') : 'N/A') . "\n";
    echo "Is Super Admin: " . (isset($user->is_super_admin) ? ($user->is_super_admin ? 'Yes' : 'No') : 'N/A') . "\n";
}
