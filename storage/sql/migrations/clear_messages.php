<?php
// Clear all messages from the database
$host = '127.0.0.1';
$dbname = 'ulimi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Deleting all messages...\n";

    // Delete all messages
    $stmt = $pdo->exec("DELETE FROM messages");
    echo "✓ Deleted all messages\n";

    // Reset auto-increment
    $pdo->exec("ALTER TABLE messages AUTO_INCREMENT = 1");
    echo "✓ Reset auto-increment\n";

    echo "\nDone. All messages have been cleared.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
