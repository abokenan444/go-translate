#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Document Certificates Table Structure ===\n\n";

$columns = DB::select('SHOW COLUMNS FROM document_certificates');

echo "Column Name | Type | Null | Key | Default\n";
echo str_repeat('-', 70) . "\n";

foreach($columns as $col) {
    echo sprintf("%-20s | %-20s | %-5s | %-5s | %s\n", 
        $col->Field, 
        $col->Type, 
        $col->Null, 
        $col->Key ?? '',
        $col->Default ?? 'NULL'
    );
}

echo "\n=== Sample Certificate Data ===\n\n";
$cert = DB::table('document_certificates')->first();
if ($cert) {
    foreach ($cert as $key => $value) {
        echo sprintf("%-25s: %s\n", $key, $value ?? 'NULL');
    }
}
