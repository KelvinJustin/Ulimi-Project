<?php
/**
 * Migration: Migrate File Users to Database
 * 
 * This migration migrates users from storage/users.php to the database.
 * Only runs if storage/users.php exists.
 * 
 * After successful migration, users.php is backed up to users.php.bak
 */

global $pdo;

$usersFile = __DIR__ . '/../../users.php';

if (!file_exists($usersFile)) {
    echo "  No file users found, skipping migration\n";
    return;
}

echo "  Migrating users from storage/users.php...\n";

$fileUsers = include $usersFile;

if (!is_array($fileUsers) || empty($fileUsers)) {
    echo "  No users to migrate\n";
    return;
}

$roleMap = ['seller' => 1, 'buyer' => 2, 'admin' => 3];
$migratedCount = 0;

foreach ($fileUsers as $fileUser) {
    $email = $fileUser['email'];
    $passwordHash = $fileUser['password'];
    $displayName = $fileUser['display_name'];
    $role = $fileUser['role'];
    $roleId = $roleMap[$role] ?? 2;
    $createdAt = $fileUser['created_at'] ?? date('Y-m-d H:i:s');

    // Check if user exists in DB
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if (!$existing) {
        // Generate unique slug
        $emailUsername = explode('@', $email)[0];
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $emailUsername));
        $counter = 1;
        $originalSlug = $slug;

        // Ensure slug is unique
        while (true) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE slug = ?");
            $stmt->execute([$slug]);
            if (!$stmt->fetch()) {
                break;
            }
            $slug = $originalSlug . '-' . $counter++;
        }

        // Insert user
        $stmt = $pdo->prepare("
            INSERT INTO users (role_id, email, slug, password_hash, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'active', ?, NOW())
        ");
        $stmt->execute([$roleId, $email, $slug, $passwordHash, $createdAt]);

        $userId = $pdo->lastInsertId();

        // Insert profile
        $stmt = $pdo->prepare("
            INSERT INTO user_profiles (user_id, display_name, rating_avg, rating_count, created_at, updated_at)
            VALUES (?, ?, 0.00, 0, NOW(), NOW())
        ");
        $stmt->execute([$userId, $displayName]);

        $migratedCount++;
    }
}

if ($migratedCount > 0) {
    echo "  ✓ Migrated $migratedCount users from file to database\n";
    
    // Backup the file
    $backupFile = $usersFile . '.bak';
    if (!file_exists($backupFile)) {
        rename($usersFile, $backupFile);
        echo "  ✓ Backed up users.php to users.php.bak\n";
    }
} else {
    echo "  All users already in database\n";
}
