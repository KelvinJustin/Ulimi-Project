<?php
// Standalone migration script - doesn't require app framework
$host = '127.0.0.1';
$dbname = 'ulimi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Adding image_path column to messages table...\n";
    
    // Add image_path column if it doesn't exist
    $pdo->exec("ALTER TABLE messages ADD COLUMN image_path VARCHAR(255) NULL AFTER message_text");
    echo "✓ image_path column added\n";
    
    echo "\nMigration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "The image_path column may already exist. Skipping...\n";
    }
}
