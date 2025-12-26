<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Get current columns
$columns = Schema::getColumnListing('subscription_plans');
echo "Current columns:\n";
print_r($columns);

// Check which columns need to be added
$neededColumns = [
    'stripe_product_id' => "VARCHAR(255) NULL",
    'billing_cycle' => "VARCHAR(50) DEFAULT 'monthly'",
    'words_limit' => "INT DEFAULT 0",
    'translations_limit' => "INT DEFAULT 0",
    'api_calls_limit' => "INT DEFAULT 0",
    'team_members_limit' => "INT DEFAULT 1",
    'features' => "JSON NULL",
    'is_active' => "BOOLEAN DEFAULT TRUE",
    'is_featured' => "BOOLEAN DEFAULT FALSE",
    'sort_order' => "INT DEFAULT 0",
];

foreach ($neededColumns as $column => $definition) {
    if (!in_array($column, $columns)) {
        try {
            DB::statement("ALTER TABLE subscription_plans ADD COLUMN {$column} {$definition}");
            echo "Added column: {$column}\n";
        } catch (Exception $e) {
            echo "Column {$column} may already exist or error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Column exists: {$column}\n";
    }
}

// Update existing plans with stripe_price_id
$plans = DB::table('subscription_plans')->get();
echo "\nExisting plans:\n";
foreach ($plans as $plan) {
    echo "- {$plan->name} (slug: {$plan->slug}, stripe_price_id: " . ($plan->stripe_price_id ?? 'NOT SET') . ")\n";
}

echo "\nDone!\n";
