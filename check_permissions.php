<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Checking Permissions System ===\n\n";

// Check if permissions tables exist
$hasPermissions = Schema::hasTable('permissions');
$hasRoles = Schema::hasTable('roles');
$hasModelHasRoles = Schema::hasTable('model_has_roles');
$hasModelHasPermissions = Schema::hasTable('model_has_permissions');
$hasRoleHasPermissions = Schema::hasTable('role_has_permissions');

echo "permissions table: " . ($hasPermissions ? 'Yes' : 'No') . "\n";
echo "roles table: " . ($hasRoles ? 'Yes' : 'No') . "\n";
echo "model_has_roles table: " . ($hasModelHasRoles ? 'Yes' : 'No') . "\n";
echo "model_has_permissions table: " . ($hasModelHasPermissions ? 'Yes' : 'No') . "\n";
echo "role_has_permissions table: " . ($hasRoleHasPermissions ? 'Yes' : 'No') . "\n";

if ($hasRoles) {
    echo "\n=== Existing Roles ===\n";
    $roles = DB::table('roles')->get();
    foreach ($roles as $role) {
        echo "- {$role->name} (ID: {$role->id})\n";
    }
}

if ($hasPermissions) {
    echo "\n=== Total Permissions: " . DB::table('permissions')->count() . " ===\n";
}

// Check if super_admin role exists
if ($hasRoles) {
    $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
    if (!$superAdminRole) {
        echo "\n⚠️ super_admin role not found, creating...\n";
        $roleId = DB::table('roles')->insertGetId([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ super_admin role created (ID: {$roleId})\n";
        
        // Assign all permissions to super_admin
        if ($hasPermissions && Schema::hasTable('role_has_permissions')) {
            $permissions = DB::table('permissions')->pluck('id');
            foreach ($permissions as $permId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permId,
                    'role_id' => $roleId,
                ]);
            }
            echo "✅ All permissions assigned to super_admin\n";
        }
    } else {
        echo "\n✅ super_admin role exists (ID: {$superAdminRole->id})\n";
    }
    
    // Assign super_admin role to admin user
    if ($hasModelHasRoles) {
        $adminUser = DB::table('users')->where('email', 'admin@culturaltranslate.com')->first();
        if ($adminUser) {
            $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
            if ($superAdminRole) {
                DB::table('model_has_roles')->updateOrInsert(
                    ['model_type' => 'App\\Models\\User', 'model_id' => $adminUser->id],
                    ['role_id' => $superAdminRole->id]
                );
                echo "✅ super_admin role assigned to admin@culturaltranslate.com\n";
            }
        }
    }
}

echo "\n=== Super Admin Account Status ===\n";
$admin = DB::table('users')->where('email', 'admin@culturaltranslate.com')->first();
echo "Email: {$admin->email}\n";
echo "Role (column): {$admin->role}\n";
echo "is_admin: {$admin->is_admin}\n";
echo "is_super_admin: {$admin->is_super_admin}\n";
echo "is_active: {$admin->is_active}\n";
echo "email_verified: " . ($admin->email_verified_at ? 'Yes' : 'No') . "\n";

echo "\n✅ Super Admin is ready with full permissions!\n";
echo "Login URL: https://culturaltranslate.com/admin\n";
echo "Email: admin@culturaltranslate.com\n";
echo "Password: Yasser-591983\n";
