<?php
// Add image_path column to commodity_listings table
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
    ]);
    
    echo "Connected to database: $dbname<br>";
    
    // Check if column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM commodity_listings LIKE 'image_path'");
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "Column image_path already exists in commodity_listings table<br>";
    } else {
        // Add image_path column
        $pdo->exec("ALTER TABLE commodity_listings ADD COLUMN image_path VARCHAR(255) NULL AFTER status");
        echo "Added image_path column to commodity_listings table<br>";
    }
    
    echo "<strong>Database update completed successfully!</strong><br>";
    
} catch (Exception $e) {
    echo "Database update failed: " . $e->getMessage() . "<br>";
}
