<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class LiveVoiceCallsSubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'live-voice-calls-custom'],
            [
                'name' => 'Live Voice Calls (Custom)',
                'description' => 'Dedicated plan for real-time translated voice calls (local/dev).',
                'price' => 0,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'tokens_limit' => 60000,
                'features' => ['realtime_call_translation'],
                'max_projects' => 999,
                'max_team_members' => 50,
                'api_access' => false,
                'priority_support' => true,
                'custom_integrations' => true,
                'is_popular' => false,
                'is_custom' => true,
                'is_active' => true,
                'sort_order' => 999,
            ]
        );
    }
}
