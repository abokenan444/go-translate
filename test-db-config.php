<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "DB Connection: " . config('database.default') . "\n";
echo "DB Host: " . config('database.connections.mysql.host') . "\n";
echo "DB Name: " . config('database.connections.mysql.database') . "\n";
