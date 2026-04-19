<?php
// Run migration to add is_read column to messages table
$host = '127.0.0.1';
$dbname = 'ulimi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Adding is_read column to messages table...\n";
    
    // Add is_read column
    $pdo->exec("ALTER TABLE messages ADD COLUMN is_read TINYINT(1) DEFAULT 0 AFTER image_path");
    echo "✓ Added is_read column\n";
    
    // Add index
    $pdo->exec("ALTER TABLE messages ADD INDEX idx_messages_is_read (is_read)");
    echo "✓ Added index on is_read\n";
    
    echo "\nMigration completed successfully.\n";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column is_read already exists. Skipping.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
