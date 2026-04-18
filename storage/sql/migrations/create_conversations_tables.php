<?php
// Create conversations and messages tables
$host = '127.0.0.1';
$dbname = 'ulimi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating conversations table...\n";
    
    // Create conversations table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS conversations (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          buyer_id BIGINT UNSIGNED NOT NULL,
          seller_id BIGINT UNSIGNED NOT NULL,
          listing_id BIGINT UNSIGNED NULL,
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (buyer_id) REFERENCES users(id),
          FOREIGN KEY (seller_id) REFERENCES users(id),
          FOREIGN KEY (listing_id) REFERENCES commodity_listings(id),
          UNIQUE KEY uniq_convo (buyer_id, seller_id, listing_id)
        ) ENGINE=InnoDB
    ");
    echo "✓ conversations table created\n";
    
    echo "Creating messages table...\n";
    
    // Create messages table with image_path column
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS messages (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          conversation_id BIGINT UNSIGNED NOT NULL,
          sender_id BIGINT UNSIGNED NOT NULL,
          message_text TEXT NOT NULL,
          image_path VARCHAR(255) NULL,
          offer_price_per_unit DECIMAL(12,2) NULL,
          currency CHAR(3) NOT NULL DEFAULT 'MWK',
          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
          FOREIGN KEY (sender_id) REFERENCES users(id),
          INDEX idx_messages_convo (conversation_id)
        ) ENGINE=InnoDB
    ");
    echo "✓ messages table created with image_path column\n";
    
    echo "\nMigration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
