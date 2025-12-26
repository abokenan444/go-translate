<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Update plans with Stripe price IDs
DB::table('subscription_plans')->where('slug', 'basic')->update([
    'stripe_price_id' => 'price_1SfSHDK4SuZ8hJuZcMXklAcT'
]);
echo "Updated basic plan\n";

DB::table('subscription_plans')->where('slug', 'professional')->update([
    'stripe_price_id' => 'price_1SfSHEK4SuZ8hJuZeADJT7Zh'
]);
echo "Updated professional plan\n";

DB::table('subscription_plans')->where('slug', 'enterprise')->update([
    'stripe_price_id' => 'price_1SfSHFK4SuZ8hJuZtlErxYAz'
]);
echo "Updated enterprise plan\n";

// Verify
$plans = DB::table('subscription_plans')->get(['name', 'slug', 'stripe_price_id']);
echo "\nVerified plans:\n";
foreach ($plans as $plan) {
    echo "- {$plan->name}: {$plan->stripe_price_id}\n";
}
