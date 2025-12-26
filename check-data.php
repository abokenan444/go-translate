<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking Users ===\n";
$users = DB::table('users')->select('id', 'email', 'role')->get();
foreach ($users as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Role: " . ($user->role ?? 'null') . "\n";
}

echo "\n=== Checking User Subscriptions ===\n";
$subs = DB::table('user_subscriptions')
    ->join('users', 'users.id', '=', 'user_subscriptions.user_id')
    ->join('subscription_plans', 'subscription_plans.id', '=', 'user_subscriptions.subscription_plan_id')
    ->select('user_subscriptions.id', 'users.email', 'subscription_plans.name as plan', 'user_subscriptions.status')
    ->get();
    
foreach ($subs as $sub) {
    echo "ID: {$sub->id} | User: {$sub->email} | Plan: {$sub->plan} | Status: {$sub->status}\n";
}

if ($subs->isEmpty()) {
    echo "No subscriptions found!\n";
}
