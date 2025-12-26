<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Cultural Profiles Table Structure ===\n";
$columns = DB::select('DESCRIBE cultural_profiles');
foreach ($columns as $col) {
    echo "{$col->Field} - {$col->Type}\n";
}

echo "\n=== Sample Data ===\n";
$data = DB::table('cultural_profiles')->limit(2)->get();
print_r($data->toArray());
