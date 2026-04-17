<?php
// Verify database tables exist
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
    
    echo "Connected to database: $dbname<br><br>";
    
    // List all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in database:<br>";
    foreach ($tables as $table) {
        echo "- $table<br>";
    }
    
    echo "<br>Verifying commodity_listings table structure:<br>";
    $stmt = $pdo->query("DESCRIBE commodity_listings");
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})<br>";
    }
    
    echo "<br>Verifying commodities data:<br>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM commodities");
    $count = $stmt->fetch();
    echo "Commodities count: {$count['count']}<br>";
    
    $stmt = $pdo->query("SELECT * FROM commodities LIMIT 5");
    $commodities = $stmt->fetchAll();
    echo "Sample commodities:<br>";
    foreach ($commodities as $commodity) {
        echo "- {$commodity['name']} ({$commodity['category']})<br>";
    }
    
    echo "<br><strong>Database verification successful!</strong><br>";
    echo "<a href='/browse'>Test the browse page</a>";
    
} catch (Exception $e) {
    echo "Verification failed: " . $e->getMessage() . "<br>";
}
