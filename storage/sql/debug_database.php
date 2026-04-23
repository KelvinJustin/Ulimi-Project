<?php
/**
 * Database Debug Script
 * 
 * This script tests and verifies the database consolidation is working correctly.
 * It checks that all expected tables exist and have the correct structure.
 * 
 * Usage: php storage/sql/debug_database.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'ulimi';

// Expected tables with their key columns
$expectedTables = [
    'roles' => ['id', 'name'],
    'users' => ['id', 'role_id', 'email', 'password_hash', 'slug'],
    'user_profiles' => ['user_id', 'display_name'],
    'commodities' => ['id', 'name', 'category', 'unit'],
    'commodity_listings' => ['id', 'seller_id', 'commodity_id', 'title', 'price_per_unit', 'status', 'image_path', 'price_unit'],
    'listing_images' => ['id', 'listing_id', 'path'],
    'carts' => ['id', 'buyer_id', 'status'],
    'cart_items' => ['id', 'cart_id', 'listing_id'],
    'orders' => ['id', 'order_number', 'buyer_id', 'seller_id', 'status'],
    'order_items' => ['id', 'order_id', 'listing_id'],
    'payments' => ['id', 'order_id', 'status'],
    'shipments' => ['id', 'order_id', 'status'],
    'conversations' => ['id', 'buyer_id', 'seller_id', 'listing_id'],
    'messages' => ['id', 'conversation_id', 'sender_id', 'message_text', 'image_path', 'is_read'],
    'notifications' => ['id', 'user_id', 'type', 'is_read'],
    'price_ticks' => ['id', 'commodity_id', 'price_per_unit'],
    'favorites' => ['id', 'user_id', 'listing_id'],
    'migrations' => ['id', 'migration_name', 'executed_at']
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "=== Database Debug Script ===\n";
    echo "Database: $dbname\n\n";
    
    // Get all actual tables
    $stmt = $pdo->query("SHOW TABLES");
    $actualTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Found " . count($actualTables) . " tables in database\n\n";
    
    $missingTables = [];
    $extraTables = [];
    $issues = [];
    
    // Check for missing expected tables
    foreach ($expectedTables as $table => $columns) {
        if (!in_array($table, $actualTables)) {
            $missingTables[] = $table;
        }
    }
    
    // Check for extra tables
    foreach ($actualTables as $table) {
        if (!array_key_exists($table, $expectedTables)) {
            $extraTables[] = $table;
        }
    }
    
    // Check column structure for existing tables
    foreach ($expectedTables as $table => $expectedColumns) {
        if (in_array($table, $actualTables)) {
            $stmt = $pdo->query("DESCRIBE `$table`");
            $actualColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($expectedColumns as $column) {
                if (!in_array($column, $actualColumns)) {
                    $issues[] = "Table '$table' is missing column '$column'";
                }
            }
        }
    }
    
    // Report results
    if (empty($missingTables) && empty($extraTables) && empty($issues)) {
        echo "✓ ALL CHECKS PASSED\n\n";
        echo "All expected tables exist with correct structure.\n";
        echo "No extra tables found.\n";
        echo "Database consolidation is working correctly!\n";
    } else {
        echo "✗ ISSUES FOUND\n\n";
        
        if (!empty($missingTables)) {
            echo "Missing tables:\n";
            foreach ($missingTables as $table) {
                echo "  - $table\n";
            }
            echo "\n";
        }
        
        if (!empty($extraTables)) {
            echo "Extra tables (not in expected schema):\n";
            foreach ($extraTables as $table) {
                echo "  - $table\n";
            }
            echo "\n";
        }
        
        if (!empty($issues)) {
            echo "Column issues:\n";
            foreach ($issues as $issue) {
                echo "  - $issue\n";
            }
            echo "\n";
        }
        
        echo "Run: php storage/sql/setup.php\n";
    }
    
    // Show table details
    echo "\n=== Table Details ===\n";
    foreach ($expectedTables as $table => $columns) {
        if (in_array($table, $actualTables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch()['count'];
            echo "✓ $table ($count records)\n";
        } else {
            echo "✗ $table (MISSING)\n";
        }
    }
    
    // Check migrations table
    if (in_array('migrations', $actualTables)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM migrations");
        $migrationCount = $stmt->fetch()['count'];
        echo "\nMigrations tracking: $migrationCount migrations recorded\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "\nMake sure:\n";
    echo "1. MySQL is running\n";
    echo "2. Database '$dbname' exists (run setup first)\n";
    echo "3. Credentials are correct\n";
    exit(1);
}
