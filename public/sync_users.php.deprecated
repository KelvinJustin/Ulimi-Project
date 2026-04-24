<?php
// Sync existing users from file storage to database
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\FileUserStorage;
use App\Core\Database;

try {
    $pdo = Database::pdo();
    echo "Connected to database<br>";
    
    // Load users from file storage
    $users = FileUserStorage::loadUsers();
    echo "Loaded " . count($users) . " users from file storage<br><br>";
    
    // Map role to role_id
    $roleMap = ['seller' => 1, 'buyer' => 2, 'admin' => 3];
    
    $syncedCount = 0;
    $profileCount = 0;
    
    foreach ($users as $user) {
        $roleId = $roleMap[$user['role']] ?? 2;
        
        // Check if user already exists in database
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $existing = $stmt->fetch();
        
        if (!$existing) {
            // Create user in database
            $stmt = $pdo->prepare("
                INSERT INTO users (id, role_id, email, password_hash, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, 'active', NOW(), NOW())
            ");
            $stmt->execute([
                $user['id'],
                $roleId,
                $user['email'],
                $user['password_hash'] ?? password_hash('default', PASSWORD_DEFAULT)
            ]);
            echo "Created database user: {$user['email']} (ID: {$user['id']})<br>";
            $syncedCount++;
        } else {
            echo "User already exists: {$user['email']} (ID: {$user['id']})<br>";
        }
        
        // Check if profile exists
        $stmt = $pdo->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $profileExists = $stmt->fetch();
        
        if (!$profileExists) {
            // Create user profile
            $stmt = $pdo->prepare("
                INSERT INTO user_profiles (user_id, display_name, rating_avg, rating_count, created_at, updated_at)
                VALUES (?, ?, 0.00, 0, NOW(), NOW())
            ");
            $stmt->execute([
                $user['id'],
                $user['display_name'] ?? explode('@', $user['email'])[0]
            ]);
            echo "Created user profile for: {$user['email']}<br>";
            $profileCount++;
        } else {
            echo "Profile already exists for: {$user['email']}<br>";
        }
        
        echo "<br>";
    }
    
    echo "<strong>Sync complete!</strong><br>";
    echo "Synced $syncedCount users to database<br>";
    echo "Created $profileCount user profiles<br>";
    
} catch (Exception $e) {
    echo "Sync failed: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}
