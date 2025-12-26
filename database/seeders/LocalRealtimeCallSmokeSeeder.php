<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\MinutesWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LocalRealtimeCallSmokeSeeder extends Seeder
{
    public function run(): void
    {
        $plan = SubscriptionPlan::where('slug', 'live-voice-calls-custom')->first()
            ?? SubscriptionPlan::where('slug', 'live-voice-calls')->first()
            ?? SubscriptionPlan::where('slug', 'custom')->first();

        if (!$plan) {
            $this->command?->error('No suitable subscription plan found. Run LiveVoiceCallsSubscriptionPlanSeeder or SubscriptionPlansSeeder first.');
            return;
        }

        $userA = User::updateOrCreate(
            ['email' => 'call_a@local.test'],
            [
                'name' => 'Call User A',
                'password' => Hash::make('password'),
            ]
        );

        $userB = User::updateOrCreate(
            ['email' => 'call_b@local.test'],
            [
                'name' => 'Call User B',
                'password' => Hash::make('password'),
            ]
        );

        foreach ([$userA, $userB] as $user) {
            MinutesWallet::updateOrCreate(
                ['user_id' => $user->id],
                ['balance_seconds' => 30 * 60]
            );

            UserSubscription::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                ],
                [
                    'status' => 'active',
                    'tokens_used' => 0,
                    'tokens_remaining' => (int)($plan->tokens_limit ?? 60000),
                    'starts_at' => now(),
                    'expires_at' => now()->addMonth(),
                    'last_token_reset_at' => now(),
                    'auto_renew' => true,
                    'is_complimentary' => true,
                    'complimentary_reason' => 'Local smoke test for realtime call translation',
                    'granted_by_admin_id' => null,
                    'granted_at' => now(),
                    'metadata' => [
                        'local_smoke_test' => true,
                    ],
                ]
            );
        }

        $this->command?->info('Local realtime call smoke users created:');
        $this->command?->line(' - call_a@local.test / password');
        $this->command?->line(' - call_b@local.test / password');
        $this->command?->line('Plan: ' . $plan->slug);
    }
}
