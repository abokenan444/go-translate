<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

echo "=== Emergency Password Test ===\n";
echo "Password from config: " . config('ai_developer.emergency.password') . "\n";
echo "Password from env: " . env('AI_EMERGENCY_PASSWORD') . "\n";
echo "Testing Hash with 'Shatha-Yasser-1992': " . (Hash::check('Shatha-Yasser-1992', env('AI_EMERGENCY_PASSWORD')) ? 'MATCH ✓' : 'NO MATCH ✗') . "\n";
