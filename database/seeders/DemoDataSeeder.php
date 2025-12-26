<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $users = [];
        for ($i = 1; $i <= 150; $i++) {
            $users[] = [
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now()->subDays(rand(1, 180)),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);

        // Create demo companies
        $companies = [];
        for ($i = 1; $i <= 25; $i++) {
            $companies[] = [
                'name' => "Company {$i}",
                'slug' => "company-{$i}",
                'email' => "company{$i}@example.com",
                'created_at' => now()->subDays(rand(1, 180)),
                'updated_at' => now(),
            ];
        }
        DB::table('companies')->insert($companies);

        // Create active subscriptions
        $userIds = DB::table('users')->pluck('id')->toArray();
        $subscriptions = [];
        for ($i = 0; $i < 85; $i++) {
            $subscriptions[] = [
                'user_id' => $userIds[array_rand($userIds)],
                'plan_id' => rand(1, 3),
                'status' => 'active',
                'starts_at' => now()->subDays(rand(1, 30)),
                'ends_at' => now()->addDays(rand(30, 365)),
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now(),
            ];
        }
        DB::table('user_subscriptions')->insert($subscriptions);

        // Create published pages
        $pages = [];
        for ($i = 1; $i <= 45; $i++) {
            $pages[] = [
                'title' => "Page {$i}",
                'slug' => "page-{$i}",
                'content' => "Content for page {$i}",
                'status' => 'published',
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now(),
            ];
        }
        
        // Check if pages table exists
        if (DB::getSchemaBuilder()->hasTable('pages')) {
            DB::table('pages')->insert($pages);
        }

        // Create demo translations
        $translations = [];
        $sampleTexts = [
            'Welcome to our platform',
            'Thank you for your business',
            'Your order has been processed',
            'Please review the document',
            'We appreciate your feedback',
        ];
        
        if (DB::getSchemaBuilder()->hasTable('translations')) {
            for ($i = 0; $i < 300; $i++) {
                $translations[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'source_text' => $sampleTexts[array_rand($sampleTexts)],
                    'translated_text' => 'مرحباً بكم في منصتنا',
                    'source_language' => 'en',
                    'target_language' => ['ar', 'fr', 'es', 'de'][array_rand(['ar', 'fr', 'es', 'de'])],
                    'status' => ['pending', 'completed', 'completed'][array_rand(['pending', 'completed', 'completed'])],
                    'quality_score' => rand(85, 99),
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now(),
                ];
            }
            DB::table('translations')->insert($translations);
        }

        // Create demo official documents
        $documents = [];
        if (DB::getSchemaBuilder()->hasTable('official_documents')) {
            for ($i = 0; $i < 50; $i++) {
                $documents[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'document_type' => ['passport', 'certificate', 'contract', 'report'][array_rand(['passport', 'certificate', 'contract', 'report'])],
                    'source_language' => 'en',
                    'target_language' => 'ar',
                    'status' => ['pending', 'processing', 'completed'][array_rand(['pending', 'processing', 'completed'])],
                    'pages_count' => rand(1, 10),
                    'price' => rand(50, 500),
                    'created_at' => now()->subDays(rand(1, 45)),
                    'updated_at' => now(),
                ];
            }
            DB::table('official_documents')->insert($documents);
        }

        echo "Demo data seeded successfully!\n";
        echo "Users: " . count($users) . "\n";
        echo "Companies: " . count($companies) . "\n";
        echo "Active Subscriptions: " . count($subscriptions) . "\n";
        echo "Published Pages: " . count($pages) . "\n";
        echo "Translations: " . count($translations) . "\n";
        echo "Official Documents: " . count($documents) . "\n";
    }
}
