<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

try {
    $pdo = Database::pdo();
    echo "Connected to database<br><br>";
    
    // Check if commodity_listings table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'commodity_listings'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "commodity_listings table does not exist!<br>";
        echo "Please run setup_database.php to create the tables.<br>";
        exit;
    }
    
    echo "commodity_listings table exists<br><br>";
    
    // Check if there are any listings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM commodity_listings");
    $result = $stmt->fetch();
    echo "Total listings in database: " . $result['count'] . "<br><br>";
    
    if ($result['count'] == 0) {
        echo "No listings found in database!<br>";
        echo "You need to create listings through the create-listing page.<br>";
    } else {
        // Get sample listings
        $stmt = $pdo->query("SELECT id, title, status, seller_id, quantity_available FROM commodity_listings LIMIT 10");
        $listings = $stmt->fetchAll();
        
        echo "Sample listings in database:<br>";
        foreach ($listings as $listing) {
            echo "ID: {$listing['id']}, Title: {$listing['title']}, Status: {$listing['status']}, Seller ID: {$listing['seller_id']}, Quantity: {$listing['quantity_available']}<br>";
        }
    }
    
    // Check commodities table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM commodities");
    $result = $stmt->fetch();
    echo "<br>Total commodities in database: " . $result['count'] . "<br>";
    
    if ($result['count'] > 0) {
        $stmt = $pdo->query("SELECT id, name, category FROM commodities LIMIT 5");
        $commodities = $stmt->fetchAll();
        echo "Sample commodities:<br>";
        foreach ($commodities as $commodity) {
            echo "ID: {$commodity['id']}, Name: {$commodity['name']}, Category: {$commodity['category']}<br>";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}
