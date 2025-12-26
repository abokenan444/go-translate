<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Plan',
                'slug' => 'free',
                'description' => 'Perfect for individuals and personal use',
                'price' => 0,
                'currency' => 'EUR',
                'billing_period' => 'monthly',
                'tokens_limit' => 1000,
                'features' => [
                    'Short text translation',
                    'Support for 13 languages',
                    'Easy-to-use interface',
                ],
                'max_projects' => 1,
                'max_team_members' => 1,
                'api_access' => false,
                'priority_support' => false,
                'custom_integrations' => false,
                'is_popular' => false,
                'is_custom' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional Plan',
                'slug' => 'professional',
                'description' => 'For professionals and small businesses',
                'price' => 29.99,
                'currency' => 'EUR',
                'billing_period' => 'monthly',
                'tokens_limit' => 50000,
                'features' => [
                    'Unlimited translations',
                    'Support for 13 languages',
                    'API access',
                    'Detailed reports',
                    'Fast technical support',
                ],
                'max_projects' => 5,
                'max_team_members' => 3,
                'api_access' => true,
                'priority_support' => false,
                'custom_integrations' => false,
                'is_popular' => true,
                'is_custom' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Business Plan',
                'slug' => 'business',
                'description' => 'For medium and large enterprises',
                'price' => 99.99,
                'currency' => 'EUR',
                'billing_period' => 'monthly',
                'tokens_limit' => 200000,
                'features' => [
                    'Unlimited translations',
                    'Support for all languages',
                    'Advanced API',
                    'Custom integrations',
                    '24/7 premium support',
                    'Dedicated account manager',
                    'Team training',
                ],
                'max_projects' => 20,
                'max_team_members' => 10,
                'api_access' => true,
                'priority_support' => true,
                'custom_integrations' => true,
                'is_popular' => false,
                'is_custom' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Custom Plan',
                'slug' => 'custom',
                'description' => 'Tailored solutions for large organizations',
                'price' => 0,
                'currency' => 'EUR',
                'billing_period' => 'monthly',
                'tokens_limit' => 999999999,
                'features' => [
                    'Custom solutions to fit your needs',
                    'Unlimited tokens',
                    'Dedicated technical support',
                    'Advanced integrations',
                    'Guaranteed SLA',
                    'Comprehensive training',
                ],
                'max_projects' => 999,
                'max_team_members' => 999,
                'api_access' => true,
                'priority_support' => true,
                'custom_integrations' => true,
                'is_popular' => false,
                'is_custom' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],

            [
                'name' => 'Live Voice Calls Plan',
                'slug' => 'live-voice-calls',
                'description' => 'Dedicated plan for real-time translated voice calls',
                'price' => 0,
                'currency' => 'EUR',
                'billing_period' => 'monthly',
                'tokens_limit' => 200000,
                'features' => [
                    'Real-time translated voice calls',
                    'Per-participant send/receive language selection',
                    'Speech-to-text + translation + text-to-speech',
                ],
                'max_projects' => 50,
                'max_team_members' => 50,
                'api_access' => false,
                'priority_support' => true,
                'custom_integrations' => false,
                'is_popular' => false,
                'is_custom' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('Subscription plans seeded successfully!');
    }
}
