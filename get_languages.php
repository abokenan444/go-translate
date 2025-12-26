<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all languages from database
$languages = DB::table('languages')->where('is_active', 1)->get();
echo "Total Languages: " . $languages->count() . "\n\n";

foreach ($languages as $lang) {
    echo "{$lang->code}: {$lang->name} ({$lang->native_name})\n";
}
