<?php
// Check if is_read column exists in messages table
$host = getenv('DB_HOST') ?: '127.0.0.1';
$dbname = getenv('DB_NAME') ?: 'ulimi';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Checking messages table structure...\n";

    // Get column information
    $stmt = $pdo->query("DESCRIBE messages");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Columns in messages table:\n";
    foreach ($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }

    // Check if is_read exists
    $hasIsRead = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'is_read') {
            $hasIsRead = true;
            break;
        }
    }

    if ($hasIsRead) {
        echo "\n✓ is_read column exists\n";
    } else {
        echo "\n✗ is_read column does NOT exist\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
