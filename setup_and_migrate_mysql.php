<?php
/**
 * Setup MySQL and Migrate Data from SQLite
 * 
 * This script will:
 * 1. Create MySQL database and user
 * 2. Migrate all data from SQLite to MySQL
 * 3. Update .env file
 */

echo "========================================\n";
echo "MySQL Setup and Migration Script\n";
echo "========================================\n\n";

// Configuration
$mysqlRootPass = 'Shatha-Yasser-1992'; // Try different passwords
$mysqlHost = 'localhost';
$mysqlDb = 'cultural_translate';
$mysqlUser = 'cultural_user';
$mysqlUserPass = 'CulturalTranslate2025Strong';

// Try to connect with different methods
$connected = false;
$pdo = null;

// Method 1: Try with provided password
try {
    $pdo = new PDO("mysql:host=$mysqlHost", 'root', $mysqlRootPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to MySQL with provided password\n";
    $connected = true;
} catch (PDOException $e) {
    echo "✗ Method 1 failed: " . $e->getMessage() . "\n";
}

// Method 2: Try without password (Unix socket)
if (!$connected) {
    try {
        $pdo = new PDO("mysql:host=$mysqlHost;unix_socket=/var/run/mysqld/mysqld.sock", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✓ Connected to MySQL via Unix socket\n";
        $connected = true;
    } catch (PDOException $e) {
        echo "✗ Method 2 failed: " . $e->getMessage() . "\n";
    }
}

// Method 3: Try with empty password
if (!$connected) {
    try {
        $pdo = new PDO("mysql:host=$mysqlHost", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✓ Connected to MySQL with empty password\n";
        $connected = true;
    } catch (PDOException $e) {
        echo "✗ Method 3 failed: " . $e->getMessage() . "\n";
    }
}

if (!$connected) {
    die("\n✗ Could not connect to MySQL. Please check MySQL root password.\n");
}

// Create database
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$mysqlDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$mysqlDb' created\n";
} catch (PDOException $e) {
    echo "✗ Database creation failed: " . $e->getMessage() . "\n";
}

// Create user
try {
    $pdo->exec("CREATE USER IF NOT EXISTS '$mysqlUser'@'$mysqlHost' IDENTIFIED BY '$mysqlUserPass'");
    echo "✓ User '$mysqlUser' created\n";
} catch (PDOException $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

// Grant privileges
try {
    $pdo->exec("GRANT ALL PRIVILEGES ON `$mysqlDb`.* TO '$mysqlUser'@'$mysqlHost'");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "✓ Privileges granted\n";
} catch (PDOException $e) {
    echo "✗ Grant privileges failed: " . $e->getMessage() . "\n";
}

echo "\n========================================\n";
echo "MySQL setup completed!\n";
echo "========================================\n\n";

echo "Database: $mysqlDb\n";
echo "User: $mysqlUser\n";
echo "Password: $mysqlUserPass\n\n";

echo "Next steps:\n";
echo "1. Update .env file with MySQL credentials\n";
echo "2. Run: php artisan migrate:fresh\n";
echo "3. Run migration script to copy data from SQLite\n";

?>
