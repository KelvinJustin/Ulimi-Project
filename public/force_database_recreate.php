<?php
// Force database recreation by discarding corrupted tablespaces
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'ulimi';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "Connected to MySQL server<br>";
    
    // Drop and recreate database completely
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
    echo "Dropped database<br>";
    
    $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Created new database<br>";
    
    $pdo->exec("USE `$dbname`");
    echo "Using database<br>";
    
    // Create commodities table
    $pdo->exec("
        CREATE TABLE commodities (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL UNIQUE,
            category VARCHAR(120) NOT NULL,
            unit VARCHAR(30) NOT NULL DEFAULT 'kg'
        ) ENGINE=InnoDB
    ");
    echo "Created commodities table<br>";
    
    // Create commodity_listings table
    $pdo->exec("
        CREATE TABLE commodity_listings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            seller_id BIGINT UNSIGNED NOT NULL,
            commodity_id INT UNSIGNED NOT NULL,
            title VARCHAR(190) NOT NULL,
            description TEXT NULL,
            quality_grade VARCHAR(50) NULL,
            price_per_unit DECIMAL(12,2) NOT NULL,
            currency CHAR(3) NOT NULL DEFAULT 'MWK',
            quantity_available DECIMAL(12,2) NOT NULL,
            min_order_quantity DECIMAL(12,2) NOT NULL DEFAULT 1,
            location_text VARCHAR(255) NULL,
            latitude DECIMAL(10,7) NULL,
            longitude DECIMAL(10,7) NULL,
            status ENUM('draft','active','paused','sold_out','archived') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "Created commodity_listings table<br>";
    
    // Create listing_images table
    $pdo->exec("
        CREATE TABLE listing_images (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            listing_id BIGINT UNSIGNED NOT NULL,
            path VARCHAR(255) NOT NULL,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "Created listing_images table<br>";
    
    // Insert sample commodities
    $sample_commodities = [
        ['Maize', 'Cereals', 'kg'],
        ['Tomatoes', 'Vegetables', 'kg'],
        ['Beans', 'Legumes', 'kg'],
        ['Potatoes', 'Vegetables', 'kg'],
        ['Onions', 'Vegetables', 'kg'],
        ['Cabbage', 'Vegetables', 'kg'],
        ['Rice', 'Cereals', 'kg'],
        ['Groundnuts', 'Legumes', 'kg'],
        ['Soybeans', 'Legumes', 'kg'],
        ['Carrots', 'Vegetables', 'kg']
    ];
    
    foreach ($sample_commodities as $commodity) {
        $stmt = $pdo->prepare("INSERT INTO commodities (name, category, unit) VALUES (?, ?, ?)");
        $stmt->execute($commodity);
    }
    echo "Inserted sample commodities<br>";
    
    echo "<strong>Database recreation completed successfully!</strong><br>";
    
} catch (Exception $e) {
    echo "Database recreation failed: " . $e->getMessage() . "<br>";
}
