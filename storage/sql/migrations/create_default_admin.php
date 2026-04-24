<?php
/**
 * Migration: Create Default Admin User
 * 
 * This migration ensures a default admin user exists in the database.
 * It runs automatically as part of the setup script.
 * 
 * Email: admin@example.com
 * Password: admin123 (should be changed after first login)
 */

// Get PDO from global scope (setup.php provides this)
global $pdo;

echo "  Checking for default admin user...\n";

// Check if admin user exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'admin@example.com'");
$stmt->execute();
$adminExists = $stmt->fetch();

if (!$adminExists) {
    echo "  Creating default admin user...\n";
    
    // Generate password hash for 'admin123'
    $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Insert admin user
    $stmt = $pdo->prepare("
        INSERT INTO users (role_id, email, slug, password_hash, status, created_at, updated_at)
        VALUES (3, 'admin@example.com', 'admin', ?, 'active', NOW(), NOW())
    ");
    $stmt->execute([$passwordHash]);
    
    $adminId = $pdo->lastInsertId();
    
    // Insert admin profile
    $stmt = $pdo->prepare("
        INSERT INTO user_profiles (user_id, display_name, rating_avg, rating_count, created_at, updated_at)
        VALUES (?, 'Admin User', 0.00, 0, NOW(), NOW())
    ");
    $stmt->execute([$adminId]);
    
    echo "  ✓ Default admin user created (admin@example.com / admin123)\n";
    echo "  ⚠ IMPORTANT: Change the default admin password after first login!\n";
} else {
    echo "  ✓ Admin user already exists\n";
}
