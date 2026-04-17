<?php
declare(strict_types=1);

// Migration script to add favorites table to database
require_once __DIR__ . '/app/bootstrap.php';

use App\Core\Database;

try {
    $pdo = Database::pdo();
    
    // Create favorites table
    $sql = "CREATE TABLE IF NOT EXISTS favorites (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        listing_id BIGINT UNSIGNED NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (listing_id) REFERENCES commodity_listings(id) ON DELETE CASCADE,
        UNIQUE KEY uniq_user_listing (user_id, listing_id),
        INDEX idx_favorites_user (user_id),
        INDEX idx_favorites_listing (listing_id)
    ) ENGINE=InnoDB";
    
    $pdo->exec($sql);
    
    echo "Favorites table created successfully!\n";
    
} catch (PDOException $e) {
    echo "Error creating favorites table: " . $e->getMessage() . "\n";
    exit(1);
}
