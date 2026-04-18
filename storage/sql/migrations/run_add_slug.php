<?php
// Run the slug migration
require __DIR__ . '/../../../app/Core/Database.php';

try {
    $pdo = \App\Core\Database::pdo();
    
    echo "Adding slug column to users table...\n";
    
    // Add slug column
    $pdo->exec("ALTER TABLE users ADD COLUMN slug VARCHAR(190) NULL UNIQUE AFTER email");
    echo "✓ Slug column added\n";
    
    // Create index
    $pdo->exec("CREATE INDEX idx_users_slug ON users(slug)");
    echo "✓ Index created\n";
    
    // Generate slugs for existing users
    $pdo->exec("UPDATE users SET slug = SUBSTRING_INDEX(email, '@', 1) WHERE slug IS NULL");
    echo "✓ Slugs generated for existing users\n";
    
    // Handle duplicates
    $pdo->exec("SET @row_number = 0");
    $pdo->exec("UPDATE users u1 SET slug = CONCAT(SUBSTRING_INDEX(u1.email, '@', 1), '-', (@row_number := @row_number + 1)) WHERE slug IN (SELECT slug FROM (SELECT slug, COUNT(*) as cnt FROM users WHERE slug IS NOT NULL GROUP BY slug HAVING cnt > 1) AS duplicates)");
    echo "✓ Duplicate slugs handled\n";
    
    echo "\nMigration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "The slug column may already exist. Skipping...\n";
    }
}
