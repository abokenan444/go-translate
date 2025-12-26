<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@culturaltranslate.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@culturaltranslate.com',
                'password' => Hash::make('Yasser-591983'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        echo "Admin user created: admin@culturaltranslate.com / Yasser-591983\n";
    }
}
