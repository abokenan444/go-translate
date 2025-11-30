<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RealisticDataSeederFixed extends Seeder
{
    public function run(): void
    {
        echo "Starting realistic data seeding...\n";

        // 1. Create Users (30 users)
        $users = [];
        $userNames = [
            ['Ahmed Al-Mansour', 'ahmed.almansour@gmail.com'],
            ['Sarah Johnson', 'sarah.johnson@outlook.com'],
            ['Mohammed Al-Saud', 'mohammed.alsaud@hotmail.com'],
            ['Emily Chen', 'emily.chen@yahoo.com'],
            ['Fatima Hassan', 'fatima.hassan@gmail.com'],
            ['David Martinez', 'david.martinez@outlook.com'],
            ['Layla Al-Rashid', 'layla.alrashid@gmail.com'],
            ['James Wilson', 'james.wilson@hotmail.com'],
            ['Aisha Abdullah', 'aisha.abdullah@gmail.com'],
            ['Michael Brown', 'michael.brown@yahoo.com'],
            ['Nora Al-Fahad', 'nora.alfahad@gmail.com'],
            ['Sophia Garcia', 'sophia.garcia@outlook.com'],
            ['Omar Al-Qahtani', 'omar.alqahtani@gmail.com'],
            ['Emma Rodriguez', 'emma.rodriguez@hotmail.com'],
            ['Khalid Al-Mutairi', 'khalid.almutairi@gmail.com'],
            ['Olivia Lee', 'olivia.lee@yahoo.com'],
            ['Abdullah Al-Harbi', 'abdullah.alharbi@gmail.com'],
            ['Ava Kim', 'ava.kim@outlook.com'],
            ['Yasser Al-Dosari', 'yasser.aldosari@gmail.com'],
            ['Mia Nguyen', 'mia.nguyen@hotmail.com'],
            ['Hassan Al-Zahrani', 'hassan.alzahrani@gmail.com'],
            ['Isabella Martinez', 'isabella.martinez@outlook.com'],
            ['Faisal Al-Ghamdi', 'faisal.alghamdi@gmail.com'],
            ['Charlotte Anderson', 'charlotte.anderson@yahoo.com'],
            ['Tariq Al-Otaibi', 'tariq.alotaibi@gmail.com'],
            ['Amelia Taylor', 'amelia.taylor@outlook.com'],
            ['Saad Al-Shammari', 'saad.alshammari@gmail.com'],
            ['Harper Thomas', 'harper.thomas@hotmail.com'],
            ['Nawaf Al-Enezi', 'nawaf.alenezi@gmail.com'],
            ['Evelyn White', 'evelyn.white@yahoo.com'],
        ];

        foreach ($userNames as $userData) {
            try {
                $userId = DB::table('users')->insertGetId([
                    'name' => $userData[0],
                    'email' => $userData[1],
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'email_verified_at' => Carbon::now()->subDays(rand(1, 365)),
                    'created_at' => Carbon::now()->subDays(rand(1, 365)),
                    'updated_at' => Carbon::now(),
                ]);
                $users[] = $userId;
            } catch (\Exception $e) {
                // Skip if email exists
            }
        }
        echo "✅ Created " . count($users) . " users\n";

        // 2. Create Companies (10 companies)
        $companies = [];
        $companyData = [
            ['TechVision Solutions', 'techvision.com'],
            ['Global Translate Inc', 'globaltranslate.com'],
            ['Digital Marketing Pro', 'digitalmarketingpro.com'],
            ['E-Commerce Masters', 'ecommercemaster.com'],
            ['Content Creators Hub', 'contentcreatorshub.com'],
            ['Legal Docs International', 'legaldocs.com'],
            ['Medical Translation Services', 'medicaltrans.com'],
            ['Tourism & Travel Experts', 'tourismexperts.com'],
            ['Financial Services Group', 'financialservices.com'],
            ['Education Platform', 'eduplatform.com'],
        ];

        foreach ($companyData as $company) {
            try {
                $companyId = DB::table('companies')->insertGetId([
                    'name' => $company[0],
                    'domain' => $company[1],
                    'status' => 'active',
                    'member_count' => rand(5, 50),
                    'translation_memory_size' => rand(1000, 50000),
                    'sso_enabled' => rand(0, 1),
                    'custom_domain_enabled' => rand(0, 1),
                    'created_at' => Carbon::now()->subDays(rand(30, 365)),
                    'updated_at' => Carbon::now(),
                ]);
                $companies[] = $companyId;
            } catch (\Exception $e) {
                // Skip if exists
            }
        }
        echo "✅ Created " . count($companies) . " companies\n";

        // 3. Create Translations (150 translations)
        $translationTypes = ['general', 'marketing', 'legal', 'technical', 'medical'];
        $languages = ['ar', 'en', 'fr', 'es', 'de', 'it', 'pt', 'ru', 'zh', 'ja'];
        $translationCount = 0;
        
        for ($i = 0; $i < 150; $i++) {
            if (empty($users)) break;
            
            $sourceLang = $languages[array_rand($languages)];
            $targetLang = $languages[array_rand($languages)];
            while ($targetLang === $sourceLang) {
                $targetLang = $languages[array_rand($languages)];
            }

            try {
                DB::table('translations')->insert([
                    'user_id' => $users[array_rand($users)],
                    'type' => $translationTypes[array_rand($translationTypes)],
                    'source_language' => $sourceLang,
                    'target_language' => $targetLang,
                    'source_text' => 'Sample text for translation ' . ($i + 1),
                    'translated_text' => 'Translated sample text ' . ($i + 1),
                    'word_count' => rand(50, 500),
                    'total_tokens' => rand(100, 1000),
                    'cost' => rand(10, 100) / 10,
                    'status' => ['completed', 'completed', 'completed', 'pending'][rand(0, 3)],
                    'created_at' => Carbon::now()->subDays(rand(1, 90)),
                    'updated_at' => Carbon::now(),
                ]);
                $translationCount++;
            } catch (\Exception $e) {
                // Skip
            }
        }
        echo "✅ Created $translationCount translations\n";

        // 4. Create Subscriptions
        $subscriptionCount = 0;
        foreach (array_slice($users, 0, 15) as $userId) {
            $planType = ['basic', 'pro', 'enterprise'][rand(0, 2)];
            $startDate = Carbon::now()->subDays(rand(30, 180));
            
            try {
                DB::table('subscriptions')->insert([
                    'user_id' => $userId,
                    'name' => $planType . ' Plan',
                    'stripe_id' => 'sub_' . strtoupper(bin2hex(random_bytes(8))),
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_' . strtoupper(bin2hex(random_bytes(8))),
                    'quantity' => 1,
                    'trial_ends_at' => null,
                    'ends_at' => null,
                    'created_at' => $startDate,
                    'updated_at' => Carbon::now(),
                ]);
                $subscriptionCount++;
            } catch (\Exception $e) {
                // Skip
            }
        }
        echo "✅ Created $subscriptionCount active subscriptions\n";

        // 5. Create Brand Profiles
        $brandCount = 0;
        foreach (array_slice($users, 0, 10) as $index => $userId) {
            try {
                DB::table('brand_profiles')->insert([
                    'user_id' => $userId,
                    'brand_name' => $companyData[$index % count($companyData)][0],
                    'industry' => ['technology', 'marketing', 'legal', 'ecommerce', 'content'][rand(0, 4)],
                    'target_audience' => ['B2B', 'B2C', 'Enterprise', 'SMB'][rand(0, 3)],
                    'tone_preference' => ['professional', 'friendly', 'formal', 'casual'][rand(0, 3)],
                    'brand_voice' => 'Professional and engaging brand voice',
                    'keywords' => json_encode(['innovation', 'quality', 'excellence']),
                    'avoid_words' => json_encode(['cheap', 'discount', 'sale']),
                    'is_active' => true,
                    'created_at' => Carbon::now()->subDays(rand(30, 180)),
                    'updated_at' => Carbon::now(),
                ]);
                $brandCount++;
            } catch (\Exception $e) {
                // Skip
            }
        }
        echo "✅ Created $brandCount brand profiles\n";

        // 6. Create Integrations
        $platforms = ['wordpress', 'woocommerce', 'shopify', 'github'];
        $integrationCount = 0;
        foreach (array_slice($users, 0, 12) as $userId) {
            try {
                DB::table('integrations')->insert([
                    'user_id' => $userId,
                    'platform' => $platforms[rand(0, count($platforms) - 1)],
                    'name' => ucfirst($platforms[rand(0, count($platforms) - 1)]) . ' Integration',
                    'description' => 'Active integration for automated translation',
                    'api_key' => 'key_' . bin2hex(random_bytes(16)),
                    'api_secret' => 'secret_' . bin2hex(random_bytes(16)),
                    'webhook_url' => 'https://example.com/webhook',
                    'settings' => json_encode(['auto_translate' => true]),
                    'is_active' => true,
                    'last_sync_at' => Carbon::now()->subHours(rand(1, 24)),
                    'created_at' => Carbon::now()->subDays(rand(10, 60)),
                    'updated_at' => Carbon::now(),
                ]);
                $integrationCount++;
            } catch (\Exception $e) {
                // Skip
            }
        }
        echo "✅ Created $integrationCount integrations\n";

        // 7. Create Newsletter Subscribers
        $subscriberCount = 0;
        $emails = [
            'subscriber1@example.com', 'subscriber2@example.com', 'subscriber3@example.com',
            'subscriber4@example.com', 'subscriber5@example.com', 'subscriber6@example.com',
            'subscriber7@example.com', 'subscriber8@example.com', 'subscriber9@example.com',
            'subscriber10@example.com', 'subscriber11@example.com', 'subscriber12@example.com',
        ];
        
        foreach ($emails as $email) {
            try {
                DB::table('newsletter_subscribers')->insert([
                    'email' => $email,
                    'status' => 'active',
                    'subscribed_at' => Carbon::now()->subDays(rand(1, 180)),
                    'created_at' => Carbon::now()->subDays(rand(1, 180)),
                    'updated_at' => Carbon::now(),
                ]);
                $subscriberCount++;
            } catch (\Exception $e) {
                // Skip
            }
        }
        echo "✅ Created $subscriberCount newsletter subscribers\n";

        echo "\n========================================\n";
        echo "✅ REALISTIC DATA SEEDED SUCCESSFULLY!\n";
        echo "========================================\n";
        echo "Summary:\n";
        echo "   - " . count($users) . " Users\n";
        echo "   - " . count($companies) . " Companies\n";
        echo "   - $translationCount Translations\n";
        echo "   - $subscriptionCount Active Subscriptions\n";
        echo "   - $brandCount Brand Profiles\n";
        echo "   - $integrationCount Integrations\n";
        echo "   - $subscriberCount Newsletter Subscribers\n";
        echo "========================================\n";
    }
}
