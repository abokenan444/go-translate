<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Checking government_email_domains table...\n";
$columns = Schema::getColumnListing('government_email_domains');
print_r($columns);

// Check if country column exists
if (!in_array('country', $columns)) {
    echo "\nAdding 'country' column...\n";
    DB::statement("ALTER TABLE government_email_domains ADD COLUMN country VARCHAR(100) DEFAULT NULL AFTER domain");
    echo "Done!\n";
}

// Check if is_verified column exists  
if (!in_array('is_verified', $columns)) {
    echo "\nAdding 'is_verified' column...\n";
    DB::statement("ALTER TABLE government_email_domains ADD COLUMN is_verified BOOLEAN DEFAULT TRUE");
    echo "Done!\n";
}

echo "\nFinal columns:\n";
print_r(Schema::getColumnListing('government_email_domains'));
