<?php
// Migration script to add 'pending' status to commodity_listings table
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'ulimi';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "Connected to MySQL server and selected database '$dbname'\n";
    
    // Get current enum values
    $stmt = $pdo->query("SHOW COLUMNS FROM commodity_listings LIKE 'status'");
    $column = $stmt->fetch();
    preg_match("/^enum\((.*)\)$/i", $column['Type'], $matches);
    $enumValues = str_getcsv($matches[1], ',', "'");
    
    echo "Current enum values: " . implode(', ', $enumValues) . "\n";
    
    // Add 'pending' if not already present
    if (!in_array('pending', $enumValues)) {
        $enumValues[] = 'pending';
        $newEnum = "enum('" . implode("','", $enumValues) . "')";
        
        $pdo->exec("ALTER TABLE commodity_listings MODIFY COLUMN status $newEnum NOT NULL DEFAULT 'active'");
        echo "Added 'pending' status to enum\n";
    } else {
        echo "'pending' status already exists\n";
    }
    
    echo "Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
