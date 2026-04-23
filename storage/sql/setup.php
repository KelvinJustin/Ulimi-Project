<?php
/**
 * Unified Database Setup Script
 * 
 * This is the single entry point for database initialization.
 * Run this after cloning the project to set up the database.
 * 
 * Usage: php storage/sql/setup.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'ulimi';

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "=== Ulimi Database Setup ===\n";
    echo "Connected to MySQL server\n\n";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$dbname' exists or was created\n";
    
    // Select the database
    $pdo->exec("USE `$dbname`");
    echo "✓ Using database '$dbname'\n\n";
    
    // Read and execute schema.sql
    $schemaPath = __DIR__ . '/schema.sql';
    if (!file_exists($schemaPath)) {
        throw new Exception("schema.sql not found at $schemaPath");
    }
    
    $schema = file_get_contents($schemaPath);
    if ($schema === false) {
        throw new Exception("Could not read schema.sql file");
    }
    
    echo "Executing schema.sql...\n";
    
    // Split by semicolon to get individual statements
    $statements = explode(';', $schema);
    $executedCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $pdo->exec($statement);
                $executedCount++;
            } catch (PDOException $e) {
                // Ignore errors for IF NOT EXISTS and similar
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "  Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "✓ Executed $executedCount statements from schema.sql\n\n";
    
    // Create migrations tracking table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration_name VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    echo "✓ Migrations tracking table ready\n\n";
    
    // Run any pending migrations
    $migrationsDir = __DIR__ . '/migrations';
    if (is_dir($migrationsDir)) {
        echo "Checking for pending migrations...\n";
        
        // Get all PHP migration files (excluding utility scripts)
        $migrationFiles = glob($migrationsDir . '/*.php');
        $migrationFiles = array_filter($migrationFiles, function($file) {
            $basename = basename($file);
            // Exclude utility scripts
            return !in_array($basename, [
                'check_tables.php',
                'check_is_read.php',
                'clear_messages.php'
            ]);
        });
        
        sort($migrationFiles);
        
        $pendingCount = 0;
        foreach ($migrationFiles as $file) {
            $migrationName = basename($file);
            
            // Check if this migration has already run
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration_name = ?");
            $stmt->execute([$migrationName]);
            $hasRun = $stmt->fetchColumn() > 0;
            
            if (!$hasRun) {
                echo "  Running migration: $migrationName\n";
                
                try {
                    // Require the migration file
                    require_once $file;
                    
                    // Record that this migration has run
                    $stmt = $pdo->prepare("INSERT INTO migrations (migration_name) VALUES (?)");
                    $stmt->execute([$migrationName]);
                    
                    $pendingCount++;
                } catch (Exception $e) {
                    echo "  Error running $migrationName: " . $e->getMessage() . "\n";
                }
            }
        }
        
        if ($pendingCount == 0) {
            echo "  No pending migrations\n";
        } else {
            echo "✓ Ran $pendingCount migration(s)\n";
        }
    }
    
    echo "\n=== Setup Complete ===\n";
    echo "Database: $dbname\n";
    echo "All tables and migrations are up to date.\n";
    echo "\nYou can now use the application.\n";
    
} catch (Exception $e) {
    echo "\n=== Setup Failed ===\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
