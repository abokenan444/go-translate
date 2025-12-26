<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PricingPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Hash;

class ActiveSubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        // Get pricing plans
        $plans = \DB::table('pricing_plans')->get()->keyBy('name');
        
        if ($plans->isEmpty()) {
            $this->command->error('No pricing plans found.');
            return;
        }
        
        // Create 30 active subscriptions with realistic data
        $subscriptionData = [
            // Professional Plans (15)
            ['plan' => 'Professional', 'count' => 15, 'balance' => [500, 2000]],
            // Enterprise Plans (10)
            ['plan' => 'Enterprise', 'count' => 10, 'balance' => [2000, 5000]],
            // Starter Plans (5)
            ['plan' => 'Starter', 'count' => 5, 'balance' => [100, 500]],
        ];
        
        $timestamp = time();
        $userIndex = 1;
        
        foreach ($subscriptionData as $data) {
            $plan = $plans->get($data['plan']);
            
            if (!$plan) {
                $this->command->warn("Plan {$data['plan']} not found, skipping.");
                continue;
            }
            
            for ($i = 0; $i < $data['count']; $i++) {
                // Create a demo user with unique timestamp
                $user = User::create([
                    'name' => "Demo User {$timestamp}_{$userIndex}",
                    'email' => "demo.user.{$timestamp}.{$userIndex}@example.com",
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]);
                
                // Random balance within range
                $currentBalance = rand($data['balance'][0], $data['balance'][1]);
                $creditBalance = rand(0, 200);
                $usageCount = rand(100, 5000);
                
                // Create active subscription
                \DB::table('user_subscriptions')->insert([
                    'user_id' => $user->id,
                    'pricing_plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now()->subDays(rand(1, 25)),
                    'expires_at' => now()->addDays(rand(5, 30)),
                    'cancelled_at' => null,
                    'monthly_usage_count' => rand(50, 500),
                    'total_usage_count' => $usageCount,
                    'current_period_start' => now()->subDays(rand(1, 25))->format('Y-m-d'),
                    'current_period_end' => now()->addDays(rand(5, 30))->format('Y-m-d'),
                    'current_balance' => $currentBalance,
                    'credit_balance' => $creditBalance,
                    'auto_recharge' => rand(0, 1),
                    'auto_recharge_amount' => rand(0, 1) ? rand(100, 500) : null,
                    'auto_recharge_threshold' => rand(0, 1) ? rand(50, 100) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $userIndex++;
            }
        }
        
        $this->command->info("Created 30 active subscriptions successfully!");
    }
}
