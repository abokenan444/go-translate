<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

try {
    // Create Basic product and price ($29/month)
    $basicProduct = $stripe->products->create([
        'name' => 'Basic Plan', 
        'description' => 'Basic translation plan - 50,000 words/month'
    ]);
    $basicPrice = $stripe->prices->create([
        'product' => $basicProduct->id, 
        'unit_amount' => 2900, 
        'currency' => 'usd', 
        'recurring' => ['interval' => 'month']
    ]);
    echo 'STRIPE_PRICE_BASIC=' . $basicPrice->id . PHP_EOL;

    // Create Professional product and price ($79/month)
    $proProduct = $stripe->products->create([
        'name' => 'Professional Plan', 
        'description' => 'Professional translation plan - 200,000 words/month'
    ]);
    $proPrice = $stripe->prices->create([
        'product' => $proProduct->id, 
        'unit_amount' => 7900, 
        'currency' => 'usd', 
        'recurring' => ['interval' => 'month']
    ]);
    echo 'STRIPE_PRICE_PROFESSIONAL=' . $proPrice->id . PHP_EOL;

    // Create Enterprise product and price ($199/month)
    $entProduct = $stripe->products->create([
        'name' => 'Enterprise Plan', 
        'description' => 'Enterprise translation plan - Unlimited words'
    ]);
    $entPrice = $stripe->prices->create([
        'product' => $entProduct->id, 
        'unit_amount' => 19900, 
        'currency' => 'usd', 
        'recurring' => ['interval' => 'month']
    ]);
    echo 'STRIPE_PRICE_ENTERPRISE=' . $entPrice->id . PHP_EOL;
    
    echo PHP_EOL . 'SUCCESS: All prices created in Stripe!' . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
