<?php
// Create missing tables without dropping database
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
    
    echo "Connected to database: $dbname<br>";
    
    // Create commodities table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS commodities (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL UNIQUE,
            category VARCHAR(120) NOT NULL,
            unit VARCHAR(30) NOT NULL DEFAULT 'kg'
        ) ENGINE=InnoDB
    ");
    echo "Created/verified commodities table<br>";
    
    // Create commodity_listings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS commodity_listings (
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
    echo "Created/verified commodity_listings table<br>";
    
    // Create listing_images table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS listing_images (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            listing_id BIGINT UNSIGNED NOT NULL,
            path VARCHAR(255) NOT NULL,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "Created/verified listing_images table<br>";
    
    // Create users table (for compatibility)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            role_id INT UNSIGNED NOT NULL,
            email VARCHAR(190) NOT NULL UNIQUE,
            phone VARCHAR(30) NULL,
            password_hash VARCHAR(255) NOT NULL,
            status ENUM('active','suspended') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "Created/verified users table<br>";
    
    // Create user_profiles table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_profiles (
            user_id BIGINT UNSIGNED PRIMARY KEY,
            display_name VARCHAR(120) NOT NULL,
            business_name VARCHAR(190) NULL,
            bio TEXT NULL,
            country VARCHAR(100) NULL,
            region VARCHAR(120) NULL,
            district VARCHAR(120) NULL,
            city VARCHAR(120) NULL,
            address_line VARCHAR(255) NULL,
            latitude DECIMAL(10,7) NULL,
            longitude DECIMAL(10,7) NULL,
            rating_avg DECIMAL(3,2) NOT NULL DEFAULT 0.00,
            rating_count INT UNSIGNED NOT NULL DEFAULT 0,
            avatar_path VARCHAR(255) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "Created/verified user_profiles table<br>";
    
    // Create roles table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE
        ) ENGINE=InnoDB
    ");
    echo "Created/verified roles table<br>";
    
    // Insert sample roles if not exists
    $pdo->exec("INSERT IGNORE INTO roles (name) VALUES ('farmer'), ('buyer'), ('admin')");
    echo "Inserted default roles<br>";
    
    // Insert sample commodities if table is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM commodities");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
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
    } else {
        echo "Commodities already exist ($count records)<br>";
    }
    
    echo "<strong>Database tables created successfully!</strong><br>";
    echo "<a href='/browse'>Test the browse page</a>";
    
} catch (Exception $e) {
    echo "Database setup failed: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}
