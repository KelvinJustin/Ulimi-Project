<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

use App\Core\Database;

try {
    $pdo = Database::pdo();
    
    // Check if table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'favorites'");
    $stmt->execute();
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        // Count records
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM favorites");
        $stmt->execute();
        $result = $stmt->fetch();
        
        echo "Favorites table exists.\n";
        echo "Total favorites: " . $result['count'] . "\n";
        
        // Show some sample data
        if ($result['count'] > 0) {
            $stmt = $pdo->prepare("SELECT * FROM favorites LIMIT 5");
            $stmt->execute();
            $favorites = $stmt->fetchAll();
            
            echo "\nRecent favorites:\n";
            foreach ($favorites as $fav) {
                echo "- User ID: {$fav['user_id']}, Listing ID: {$fav['listing_id']}, Created: {$fav['created_at']}\n";
            }
        }
    } else {
        echo "Favorites table does not exist.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
