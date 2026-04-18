<?php
// Check if conversations and messages tables exist
$host = '127.0.0.1';
$dbname = 'ulimi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking database tables...\n";
    
    // Check if conversations table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'conversations'");
    $exists = $stmt->fetch();
    if ($exists) {
        echo "✓ conversations table exists\n";
        
        // Count records
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM conversations");
        $result = $stmt->fetch();
        echo "  Records: " . $result['count'] . "\n";
    } else {
        echo "✗ conversations table does NOT exist\n";
    }
    
    // Check if messages table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    $exists = $stmt->fetch();
    if ($exists) {
        echo "✓ messages table exists\n";
        
        // Count records
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
        $result = $stmt->fetch();
        echo "  Records: " . $result['count'] . "\n";
    } else {
        echo "✗ messages table does NOT exist\n";
    }
    
    echo "\nDone.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
