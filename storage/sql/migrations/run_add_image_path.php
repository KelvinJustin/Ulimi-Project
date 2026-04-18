<?php
// Run the migration to add image_path column to messages table
require __DIR__ . '/../../../app/Core/Database.php';

try {
    $pdo = \App\Core\Database::pdo();
    
    echo "Adding image_path column to messages table...\n";
    
    // Add image_path column
    $pdo->exec("ALTER TABLE messages ADD COLUMN image_path VARCHAR(255) NULL AFTER message_text");
    echo "✓ image_path column added\n";
    
    echo "\nMigration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "The image_path column may already exist. Skipping...\n";
    }
}
