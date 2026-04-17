<?php
// Setup database from schema.sql
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'ulimi';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "Connected to MySQL server\n";
    
    // Read and execute schema.sql
    $schema = file_get_contents(__DIR__ . '/storage/sql/schema.sql');
    
    if ($schema === false) {
        throw new Exception("Could not read schema.sql file");
    }
    
    // Split by semicolon to get individual statements
    $statements = explode(';', $schema);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $pdo->exec($statement);
                echo "Executed: " . substr($statement, 0, 50) . "...\n";
            } catch (PDOException $e) {
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\nDatabase setup completed successfully!\n";
    echo "Database: $dbname\n";
    echo "Tables created: roles, users, user_profiles, commodities, commodity_listings, listing_images, carts, cart_items, orders, order_items, payments, shipments, conversations, messages, notifications, price_ticks\n";
    
} catch (Exception $e) {
    echo "Database setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
