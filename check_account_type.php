#!/usr/bin/env php
<?php

/**
 * Check account_type column and update if needed
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking account_type Column ===\n\n";

// Get column details
$columns = DB::select("SHOW COLUMNS FROM users WHERE Field = 'account_type'");

if (count($columns) > 0) {
    $col = $columns[0];
    echo "Current Definition:\n";
    echo "  Type: {$col->Type}\n";
    echo "  Null: {$col->Null}\n";
    echo "  Default: {$col->Default}\n\n";
    
    // Check if it's an ENUM
    if (strpos($col->Type, 'enum') !== false) {
        echo "⚠️ Column is ENUM, need to alter to VARCHAR or add new values\n\n";
        
        // Parse existing values
        preg_match("/^enum\('(.*)'\)$/", $col->Type, $matches);
        if (isset($matches[1])) {
            $values = explode("','", $matches[1]);
            echo "Current ENUM values:\n";
            foreach ($values as $val) {
                echo "  - $val\n";
            }
            
            // Check if our values exist
            $requiredValues = ['gov_authority_officer', 'gov_authority_supervisor'];
            $missingValues = array_diff($requiredValues, $values);
            
            if (count($missingValues) > 0) {
                echo "\nMissing values:\n";
                foreach ($missingValues as $val) {
                    echo "  - $val\n";
                }
                
                // Add missing values
                $allValues = array_merge($values, $missingValues);
                $enumString = "'" . implode("','", $allValues) . "'";
                
                echo "\nAttempting to alter column...\n";
                try {
                    DB::statement("ALTER TABLE users MODIFY account_type ENUM($enumString)");
                    echo "✅ Column altered successfully!\n";
                } catch (\Exception $e) {
                    echo "❌ Error: " . $e->getMessage() . "\n";
                }
            } else {
                echo "\n✅ All required values exist!\n";
            }
        }
    } else {
        echo "✅ Column is {$col->Type} - should work fine\n";
    }
} else {
    echo "❌ Column 'account_type' not found!\n";
}

echo "\n=== Check Complete ===\n";
