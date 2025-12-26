<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "DB Default: " . config('database.default') . PHP_EOL;
echo "MenuItem Connection: " . (new App\Models\MenuItem)->getConnectionName() . PHP_EOL;

try {
    $count = App\Models\MenuItem::count();
    echo "MenuItem Count: $count" . PHP_EOL;
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
