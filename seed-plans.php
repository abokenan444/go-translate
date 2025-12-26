<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$plans = [
    [
        'name' => 'Free',
        'slug' => 'free',
        'description' => 'Perfect for trying out our service',
        'price' => 0.00,
        'billing_period' => 'lifetime',
        'tokens_limit' => 10000,
        'max_team_members' => 1,
        'max_projects' => 3,
        'is_active' => 1,
        'is_popular' => 0,
        'is_custom' => 0,
        'api_access' => 0,
        'priority_support' => 0,
        'custom_integrations' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Basic',
        'slug' => 'basic',
        'description' => 'Great for individuals and small projects',
        'price' => 29.00,
        'billing_period' => 'monthly',
        'tokens_limit' => 100000,
        'max_team_members' => 3,
        'max_projects' => 10,
        'is_active' => 1,
        'is_popular' => 0,
        'is_custom' => 0,
        'api_access' => 1,
        'priority_support' => 0,
        'custom_integrations' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Professional',
        'slug' => 'pro',
        'description' => 'Perfect for growing businesses',
        'price' => 99.00,
        'billing_period' => 'monthly',
        'tokens_limit' => 500000,
        'max_team_members' => 10,
        'max_projects' => 50,
        'is_active' => 1,
        'is_popular' => 1,
        'is_custom' => 0,
        'api_access' => 1,
        'priority_support' => 1,
        'custom_integrations' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Enterprise',
        'slug' => 'enterprise',
        'description' => 'For large organizations with custom needs',
        'price' => 299.00,
        'billing_period' => 'monthly',
        'tokens_limit' => 999999999,
        'max_team_members' => 999999999,
        'max_projects' => 999999999,
        'is_active' => 1,
        'is_popular' => 0,
        'is_custom' => 1,
        'api_access' => 1,
        'priority_support' => 1,
        'custom_integrations' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

DB::table('subscription_plans')->truncate();
DB::table('subscription_plans')->insert($plans);

echo "✅ Successfully created " . count($plans) . " subscription plans!\n";
