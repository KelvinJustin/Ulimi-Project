<?php
require_once 'app/bootstrap.php';

try {
    $pdo = \App\Core\Database::pdo();
    
    // Check if commodity_listings table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM commodity_listings");
    $result = $stmt->fetch();
    echo "Total listings in database: " . $result['count'] . "\n\n";
    
    // Get sample listings with their IDs
    $stmt = $pdo->query("SELECT id, title, status, seller_id, quantity_available FROM commodity_listings LIMIT 5");
    $listings = $stmt->fetchAll();
    
    echo "Sample listings:\n";
    foreach ($listings as $listing) {
        echo "ID: {$listing['id']}, Title: {$listing['title']}, Status: {$listing['status']}, Seller ID: {$listing['seller_id']}, Quantity: {$listing['quantity_available']}\n";
    }
    
    // Check if there are any active listings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM commodity_listings WHERE status = 'active'");
    $result = $stmt->fetch();
    echo "\nActive listings: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
