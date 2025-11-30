<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RealisticDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users (50 users)
        $users = [];
        $userNames = [
            ['Ahmed', 'Al-Mansour', 'ahmed.almansour@gmail.com'],
            ['Sarah', 'Johnson', 'sarah.johnson@outlook.com'],
            ['Mohammed', 'Al-Saud', 'mohammed.alsaud@hotmail.com'],
            ['Emily', 'Chen', 'emily.chen@yahoo.com'],
            ['Fatima', 'Hassan', 'fatima.hassan@gmail.com'],
            ['David', 'Martinez', 'david.martinez@outlook.com'],
            ['Layla', 'Al-Rashid', 'layla.alrashid@gmail.com'],
            ['James', 'Wilson', 'james.wilson@hotmail.com'],
            ['Aisha', 'Abdullah', 'aisha.abdullah@gmail.com'],
            ['Michael', 'Brown', 'michael.brown@yahoo.com'],
            ['Nora', 'Al-Fahad', 'nora.alfahad@gmail.com'],
            ['Sophia', 'Garcia', 'sophia.garcia@outlook.com'],
            ['Omar', 'Al-Qahtani', 'omar.alqahtani@gmail.com'],
            ['Emma', 'Rodriguez', 'emma.rodriguez@hotmail.com'],
            ['Khalid', 'Al-Mutairi', 'khalid.almutairi@gmail.com'],
            ['Olivia', 'Lee', 'olivia.lee@yahoo.com'],
            ['Abdullah', 'Al-Harbi', 'abdullah.alharbi@gmail.com'],
            ['Ava', 'Kim', 'ava.kim@outlook.com'],
            ['Yasser', 'Al-Dosari', 'yasser.aldosari@gmail.com'],
            ['Mia', 'Nguyen', 'mia.nguyen@hotmail.com'],
        ];

        foreach ($userNames as $index => $userData) {
            $userId = DB::table('users')->insertGetId([
                'name' => $userData[0] . ' ' . $userData[1],
                'email' => $userData[2],
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => Carbon::now()->subDays(rand(1, 365)),
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now(),
            ]);
            $users[] = $userId;
        }

        // 2. Create Companies (15 companies)
        $companies = [];
        $companyData = [
            ['TechVision Solutions', 'techvision@company.com', '+966501234567', 'Riyadh, Saudi Arabia', 'https://techvision.com', 'Leading software development company'],
            ['Global Translate Inc', 'info@globaltranslate.com', '+1-555-0123', 'New York, USA', 'https://globaltranslate.com', 'Professional translation services'],
            ['Digital Marketing Pro', 'contact@digitalmarketingpro.com', '+44-20-1234-5678', 'London, UK', 'https://digitalmarketingpro.com', 'Digital marketing agency'],
            ['E-Commerce Masters', 'hello@ecommercemaster.com', '+971-4-123-4567', 'Dubai, UAE', 'https://ecommercemaster.com', 'E-commerce solutions provider'],
            ['Content Creators Hub', 'team@contentcreatorshub.com', '+33-1-23-45-67-89', 'Paris, France', 'https://contentcreatorshub.com', 'Content creation and management'],
            ['Legal Docs International', 'info@legaldocs.com', '+49-30-1234-5678', 'Berlin, Germany', 'https://legaldocs.com', 'Legal document translation'],
            ['Medical Translation Services', 'contact@medicaltrans.com', '+81-3-1234-5678', 'Tokyo, Japan', 'https://medicaltrans.com', 'Medical and pharmaceutical translation'],
            ['Tourism & Travel Experts', 'hello@tourismexperts.com', '+34-91-123-4567', 'Madrid, Spain', 'https://tourismexperts.com', 'Tourism and travel content'],
            ['Financial Services Group', 'info@financialservices.com', '+86-10-1234-5678', 'Beijing, China', 'https://financialservices.com', 'Financial and banking services'],
            ['Education Platform', 'team@eduplatform.com', '+7-495-123-4567', 'Moscow, Russia', 'https://eduplatform.com', 'Educational content and e-learning'],
        ];

        foreach ($companyData as $company) {
            $companyId = DB::table('companies')->insertGetId([
                'name' => $company[0],
                'email' => $company[1],
                'phone' => $company[2],
                'address' => $company[3],
                'website' => $company[4],
                'description' => $company[5],
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(rand(30, 365)),
                'updated_at' => Carbon::now(),
            ]);
            $companies[] = $companyId;
        }

        // 3. Create Translations (200 translations)
        $translationTypes = ['general', 'marketing', 'legal', 'technical', 'medical'];
        $languages = ['ar', 'en', 'fr', 'es', 'de', 'it', 'pt', 'ru', 'zh', 'ja'];
        
        for ($i = 0; $i < 200; $i++) {
            $sourceLang = $languages[array_rand($languages)];
            $targetLang = $languages[array_rand($languages)];
            while ($targetLang === $sourceLang) {
                $targetLang = $languages[array_rand($languages)];
            }

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
                'status' => ['completed', 'completed', 'completed', 'pending'][array_rand(['completed', 'completed', 'completed', 'pending'])],
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'updated_at' => Carbon::now(),
            ]);
        }

        // 4. Create Subscriptions (for companies)
        foreach ($companies as $companyId) {
            $planType = ['basic', 'pro', 'enterprise'][array_rand(['basic', 'pro', 'enterprise'])];
            $startDate = Carbon::now()->subDays(rand(30, 180));
            
            DB::table('subscriptions')->insert([
                'user_id' => $users[array_rand($users)],
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
        }

        // 5. Create Brand Profiles
        foreach (array_slice($companies, 0, 8) as $index => $companyId) {
            DB::table('brand_profiles')->insert([
                'user_id' => $users[$index],
                'brand_name' => $companyData[$index][0],
                'industry' => ['technology', 'marketing', 'legal', 'ecommerce', 'content'][array_rand(['technology', 'marketing', 'legal', 'ecommerce', 'content'])],
                'target_audience' => ['B2B', 'B2C', 'Enterprise', 'SMB'][array_rand(['B2B', 'B2C', 'Enterprise', 'SMB'])],
                'tone_preference' => ['professional', 'friendly', 'formal', 'casual'][array_rand(['professional', 'friendly', 'formal', 'casual'])],
                'brand_voice' => 'Professional and engaging brand voice',
                'keywords' => json_encode(['innovation', 'quality', 'excellence']),
                'avoid_words' => json_encode(['cheap', 'discount', 'sale']),
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(rand(30, 180)),
                'updated_at' => Carbon::now(),
            ]);
        }

        // 6. Create Integrations
        $platforms = ['wordpress', 'woocommerce', 'shopify', 'github'];
        foreach (array_slice($users, 0, 10) as $userId) {
            DB::table('integrations')->insert([
                'user_id' => $userId,
                'platform' => $platforms[array_rand($platforms)],
                'name' => ucfirst($platforms[array_rand($platforms)]) . ' Integration',
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
        }

        // 7. Create Translation Feedbacks
        $translations = DB::table('translations')->where('status', 'completed')->limit(50)->get();
        foreach ($translations as $translation) {
            if (rand(0, 1)) { // 50% chance
                DB::table('translation_feedback')->insert([
                    'translation_id' => $translation->id,
                    'user_id' => $translation->user_id,
                    'rating' => rand(3, 5),
                    'feedback_type' => ['quality', 'accuracy', 'speed'][array_rand(['quality', 'accuracy', 'speed'])],
                    'comment' => 'Great translation quality!',
                    'created_at' => Carbon::parse($translation->created_at)->addHours(rand(1, 48)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        echo "✅ Realistic data seeded successfully!\n";
        echo "   - 20 Users\n";
        echo "   - 10 Companies\n";
        echo "   - 200 Translations\n";
        echo "   - 10 Active Subscriptions\n";
        echo "   - 8 Brand Profiles\n";
        echo "   - 10 Integrations\n";
        echo "   - 50+ Translation Feedbacks\n";
    }
}
