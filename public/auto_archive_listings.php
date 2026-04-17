<?php
// Auto-archive listings older than 4 months
// This script can be run manually or via cron job
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bootstrap the application
require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

try {
    $pdo = Database::pdo();
    
    // Archive listings that are active and older than 4 months
    $stmt = $pdo->prepare("
        UPDATE commodity_listings 
        SET status = 'archived', updated_at = NOW() 
        WHERE status = 'active' 
        AND created_at < DATE_SUB(NOW(), INTERVAL 4 MONTH)
    ");
    $stmt->execute();
    $affectedRows = $stmt->rowCount();

    echo "Auto-archived {$affectedRows} listings older than 4 months.\n";
    
    if ($affectedRows > 0) {
        // Log the archived listings
        $stmt = $pdo->prepare("
            SELECT id, title, created_at 
            FROM commodity_listings 
            WHERE status = 'archived' 
            AND updated_at >= DATE_SUB(NOW(), INTERVAL 1 MINUTE)
        ");
        $stmt->execute();
        $archivedListings = $stmt->fetchAll();
        
        echo "Archived listings:\n";
        foreach ($archivedListings as $listing) {
            echo "  - ID {$listing['id']}: {$listing['title']} (created: {$listing['created_at']})\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
