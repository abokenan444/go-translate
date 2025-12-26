<?php
/**
 * Migration Script: SQLite to MySQL
 * 
 * This script safely migrates data from SQLite to MySQL database
 * without affecting the current running system.
 * 
 * Usage: php migrate_sqlite_to_mysql.php
 */

// Configuration
$sqliteDb = '/var/www/cultural-translate-platform/database/database.sqlite';
$mysqlHost = 'localhost';
$mysqlDb = 'cultural_translate';
$mysqlUser = 'root';
$mysqlPass = 'Shatha-Yasser-1992';

// Step 1: Connect to SQLite
try {
    $sqlite = new PDO("sqlite:$sqliteDb");
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to SQLite database\n";
} catch (PDOException $e) {
    die("✗ SQLite connection failed: " . $e->getMessage() . "\n");
}

// Step 2: MySQL password is already set above
// $envFile = '/var/www/cultural-translate-platform/.env';
// Password is hardcoded for migration

// Step 3: Connect to MySQL
try {
    $mysql = new PDO("mysql:host=$mysqlHost", $mysqlUser, $mysqlPass);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to MySQL server\n";
} catch (PDOException $e) {
    die("✗ MySQL connection failed: " . $e->getMessage() . "\n");
}

// Step 4: Create database if not exists
try {
    $mysql->exec("CREATE DATABASE IF NOT EXISTS `$mysqlDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $mysql->exec("USE `$mysqlDb`");
    echo "✓ Database '$mysqlDb' ready\n";
} catch (PDOException $e) {
    die("✗ Database creation failed: " . $e->getMessage() . "\n");
}

// Step 5: Get all tables from SQLite
$tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
echo "✓ Found " . count($tables) . " tables to migrate\n\n";

// Step 6: Migrate each table
$migratedTables = 0;
$migratedRows = 0;

foreach ($tables as $table) {
    echo "Migrating table: $table ... ";
    
    try {
        // Get table structure
        $createTableStmt = $sqlite->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='$table'")->fetchColumn();
        
        // Convert SQLite syntax to MySQL syntax
        $mysqlCreateStmt = convertSqliteToMysql($createTableStmt, $table);
        
        // Drop table if exists and create new one
        $mysql->exec("DROP TABLE IF EXISTS `$table`");
        $mysql->exec($mysqlCreateStmt);
        
        // Get data from SQLite
        $data = $sqlite->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($data) > 0) {
            // Get column names
            $columns = array_keys($data[0]);
            $columnList = '`' . implode('`, `', $columns) . '`';
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            
            // Prepare insert statement
            $insertStmt = $mysql->prepare("INSERT INTO `$table` ($columnList) VALUES ($placeholders)");
            
            // Insert data in batches
            $batchSize = 1000;
            $batches = array_chunk($data, $batchSize);
            
            foreach ($batches as $batch) {
                $mysql->beginTransaction();
                foreach ($batch as $row) {
                    $insertStmt->execute(array_values($row));
                    $migratedRows++;
                }
                $mysql->commit();
            }
        }
        
        $migratedTables++;
        echo "✓ (" . count($data) . " rows)\n";
        
    } catch (PDOException $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        continue;
    }
}

echo "\n";
echo "========================================\n";
echo "Migration Summary:\n";
echo "========================================\n";
echo "Total tables migrated: $migratedTables / " . count($tables) . "\n";
echo "Total rows migrated: $migratedRows\n";
echo "========================================\n";

/**
 * Convert SQLite CREATE TABLE syntax to MySQL syntax
 */
function convertSqliteToMysql($sqliteStmt, $tableName) {
    // Replace SQLite data types with MySQL equivalents
    $mysqlStmt = str_replace('integer primary key autoincrement', 'INT AUTO_INCREMENT PRIMARY KEY', $sqliteStmt);
    $mysqlStmt = str_replace('integer', 'INT', $mysqlStmt);
    $mysqlStmt = str_replace('datetime', 'DATETIME', $mysqlStmt);
    $mysqlStmt = str_replace('tinyint(1)', 'TINYINT(1)', $mysqlStmt);
    $mysqlStmt = str_replace('varchar', 'VARCHAR(255)', $mysqlStmt);
    $mysqlStmt = str_replace('text', 'TEXT', $mysqlStmt);
    $mysqlStmt = str_replace('float', 'FLOAT', $mysqlStmt);
    
    // Add ENGINE and CHARSET
    $mysqlStmt = rtrim($mysqlStmt, ';') . ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    
    return $mysqlStmt;
}

echo "\n✓ Migration completed successfully!\n";
echo "\nNext steps:\n";
echo "1. Update .env file to use MySQL:\n";
echo "   DB_CONNECTION=mysql\n";
echo "   DB_HOST=127.0.0.1\n";
echo "   DB_PORT=3306\n";
echo "   DB_DATABASE=cultural_translate\n";
echo "2. Test the application with MySQL\n";
echo "3. If everything works, backup and remove SQLite database\n";
?>
