<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== Final Super Admin Setup ===\n\n";

// Update admin with all needed fields
$updates = [
    'name' => 'Super Admin',
    'role' => 'super_admin',
    'account_status' => 'active',
    'is_admin' => 1,
    'is_super_admin' => 1,
    'is_active' => 1,
    'email_verified_at' => now(),
    'password' => Hash::make('Yasser-591983'),
    'language' => 'en',
    'updated_at' => now(),
];

$affected = DB::table('users')
    ->where('email', 'admin@culturaltranslate.com')
    ->update($updates);

echo "Updated {$affected} user(s)\n\n";

// Verify
$admin = DB::table('users')->where('email', 'admin@culturaltranslate.com')->first();

echo "=== Super Admin Account Details ===\n";
echo "ID: {$admin->id}\n";
echo "Name: {$admin->name}\n";
echo "Email: {$admin->email}\n";
echo "Role: {$admin->role}\n";
echo "Account Status: {$admin->account_status}\n";
echo "is_admin: {$admin->is_admin}\n";
echo "is_super_admin: {$admin->is_super_admin}\n";
echo "is_active: {$admin->is_active}\n";
echo "Email Verified: " . ($admin->email_verified_at ? 'Yes (' . $admin->email_verified_at . ')' : 'No') . "\n";

echo "\n=== Access Check ===\n";
$adminRoles = ['super_admin', 'support_admin', 'financial_admin', 'technical_admin'];
$canAccess = in_array($admin->role, $adminRoles, true) && $admin->account_status === 'active';
echo "Can Access Admin Panel: " . ($canAccess ? '✅ YES' : '❌ NO') . "\n";

echo "\n========================================\n";
echo "✅ SUPER ADMIN READY!\n";
echo "========================================\n";
echo "Login URL: https://culturaltranslate.com/admin\n";
echo "Email: admin@culturaltranslate.com\n";
echo "Password: Yasser-591983\n";
echo "========================================\n";
