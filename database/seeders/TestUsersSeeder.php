<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('Test@123456');
        
        // 1. Customer Account
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => $password,
            'account_type' => 'customer',
            'company_name' => 'Customer Company Ltd',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 2. Affiliate Account
        User::create([
            'name' => 'Test Affiliate',
            'email' => 'affiliate@test.com',
            'password' => $password,
            'account_type' => 'affiliate',
            'company_name' => 'Affiliate Marketing Inc',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 3. Government Account
        User::create([
            'name' => 'Test Government',
            'email' => 'government@test.com',
            'password' => $password,
            'account_type' => 'government',
            'company_name' => 'Ministry of Foreign Affairs',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 4. Partner Account
        User::create([
            'name' => 'Test Partner',
            'email' => 'partner@test.com',
            'password' => $password,
            'account_type' => 'partner',
            'company_name' => 'Partner Translation Services',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 5. Translator Account
        User::create([
            'name' => 'Test Translator',
            'email' => 'translator@test.com',
            'password' => $password,
            'account_type' => 'translator',
            'company_name' => 'Freelance Translator',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
