<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Language & Cultural Status ===\n\n";

$langCount = DB::table('languages')->count();
$profileCount = DB::table('cultural_profiles')->count();

echo "Languages in DB: {$langCount}\n";
echo "Cultural Profiles: {$profileCount}\n";

echo "\n=== Sample Languages ===\n";
$langs = DB::table('languages')->select('code', 'name', 'native_name', 'direction', 'region')->limit(20)->get();
foreach ($langs as $l) {
    echo "{$l->code} - {$l->name} ({$l->native_name}) [{$l->direction}] - {$l->region}\n";
}

echo "\n=== RTL Languages ===\n";
$rtl = DB::table('languages')->where('direction', 'rtl')->get();
foreach ($rtl as $l) {
    echo "{$l->code} - {$l->name}\n";
}

echo "\n=== API Test ===\n";
// Simulate what the API would return
$apiLangs = DB::table('languages')
    ->where('is_active', true)
    ->select('code', 'name', 'native_name as native', 'direction', 'region')
    ->orderBy('name')
    ->get();

echo "API would return: " . count($apiLangs) . " languages\n";
