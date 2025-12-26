<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Roles Table Structure ===\n";
$cols = DB::select('DESCRIBE roles');
foreach ($cols as $col) {
    echo $col->Field . ' - ' . $col->Type . "\n";
}

echo "\n=== Existing Roles ===\n";
$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    print_r($role);
}

echo "\n=== Permissions Table Structure ===\n";
if (Schema::hasTable('permissions')) {
    $cols = DB::select('DESCRIBE permissions');
    foreach ($cols as $col) {
        echo $col->Field . ' - ' . $col->Type . "\n";
    }
}

echo "\n=== All Tables Related to Roles/Permissions ===\n";
$tables = DB::select("SHOW TABLES LIKE '%role%'");
foreach ($tables as $t) {
    $vals = get_object_vars($t);
    echo array_values($vals)[0] . "\n";
}

$tables = DB::select("SHOW TABLES LIKE '%permission%'");
foreach ($tables as $t) {
    $vals = get_object_vars($t);
    echo array_values($vals)[0] . "\n";
}
