<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

echo "=== Users Table Structure ===\n";
$cols = DB::select('DESCRIBE users');
foreach ($cols as $col) {
    echo $col->Field . ' - ' . $col->Type . "\n";
}

// Check if is_admin column exists
$hasIsAdmin = Schema::hasColumn('users', 'is_admin');
$hasIsSuperAdmin = Schema::hasColumn('users', 'is_super_admin');

echo "\n=== Column Check ===\n";
echo "has is_admin: " . ($hasIsAdmin ? 'Yes' : 'No') . "\n";
echo "has is_super_admin: " . ($hasIsSuperAdmin ? 'Yes' : 'No') . "\n";

// Add missing columns if needed
if (!$hasIsAdmin) {
    DB::statement('ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER role');
    echo "✅ Added is_admin column\n";
}

if (!$hasIsSuperAdmin) {
    DB::statement('ALTER TABLE users ADD COLUMN is_super_admin TINYINT(1) DEFAULT 0 AFTER is_admin');
    echo "✅ Added is_super_admin column\n";
}

// Now update the super admin
echo "\n=== Updating Super Admin ===\n";
DB::table('users')->where('email', 'admin@culturaltranslate.com')->update([
    'role' => 'super_admin',
    'is_admin' => 1,
    'is_super_admin' => 1,
    'email_verified_at' => now(),
    'password' => Hash::make('Yasser-591983'),
    'updated_at' => now(),
]);

echo "✅ Super Admin updated with full permissions!\n";
echo "Email: admin@culturaltranslate.com\n";
echo "Password: Yasser-591983\n";

// Final verification
echo "\n=== Final Verification ===\n";
$user = DB::table('users')->where('email', 'admin@culturaltranslate.com')->first();
print_r($user);
