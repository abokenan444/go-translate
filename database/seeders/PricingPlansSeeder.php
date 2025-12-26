<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingPlan;

class PricingPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            // Free Plan
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => '2 free translations per day',
                'type' => 'free',
                'price_per_translation' => 0,
                'price_per_1k_chars' => 0,
                'price_per_word' => 0,
                'monthly_price' => 0,
                'daily_translation_limit' => 2,
                'monthly_translation_limit' => 60,
                'max_chars_per_translation' => 5000,
                'has_api_access' => false,
                'has_bulk_translation' => false,
                'has_advanced_features' => false,
                'has_priority_support' => false,
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 1,
            ],

            // Pay-as-you-go Plan
            [
                'name' => 'Pay as You Go',
                'slug' => 'pay-as-you-go',
                'description' => 'Pay only for what you use',
                'type' => 'pay_per_use',
                'price_per_translation' => 0.05, // $0.05 per translation
                'price_per_1k_chars' => 0.02, // $0.02 per 1000 chars
                'price_per_word' => 0.001, // $0.001 per word
                'monthly_translation_limit' => null, // unlimited
                'daily_translation_limit' => null,
                'max_chars_per_translation' => 10000,
                'has_api_access' => true,
                'has_bulk_translation' => false,
                'has_advanced_features' => true,
                'has_priority_support' => false,
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 2,
            ],

            // Starter Subscription
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => '100 translations per month',
                'type' => 'subscription',
                'price_per_translation' => 0,
                'monthly_price' => 9.99,
                'yearly_price' => 99.00,
                'monthly_translation_limit' => 100,
                'max_chars_per_translation' => 10000,
                'has_api_access' => false,
                'has_bulk_translation' => false,
                'has_advanced_features' => true,
                'has_priority_support' => false,
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 3,
            ],

            // Professional Subscription
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => '1000 translations per month',
                'type' => 'subscription',
                'price_per_translation' => 0,
                'monthly_price' => 49.99,
                'yearly_price' => 499.00,
                'monthly_translation_limit' => 1000,
                'max_chars_per_translation' => 50000,
                'has_api_access' => true,
                'has_bulk_translation' => true,
                'has_advanced_features' => true,
                'has_priority_support' => true,
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 4,
            ],

            // Enterprise Custom
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Custom plan for large organizations',
                'type' => 'custom',
                'price_per_translation' => 0.03, // Custom pricing
                'monthly_price' => null,
                'monthly_translation_limit' => null, // unlimited
                'max_chars_per_translation' => 100000,
                'has_api_access' => true,
                'has_bulk_translation' => true,
                'has_advanced_features' => true,
                'has_priority_support' => true,
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('✅ Created ' . count($plans) . ' pricing plans');
    }
}
