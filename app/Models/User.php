<?php
declare(strict_types=1);

namespace App\Models;

final class User
{
    public $db;
    
    public function __construct()
    {
        $this->db = \App\Core\Database::pdo();
    }
    
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT u.*, up.display_name, up.rating_avg, up.rating_count 
                                     FROM users u 
                                     LEFT JOIN user_profiles up ON u.id = up.user_id 
                                     WHERE u.email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT u.*, up.display_name, up.rating_avg, up.rating_count 
                                     FROM users u 
                                     LEFT JOIN user_profiles up ON u.id = up.user_id 
                                     WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function verifyCredentials(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }

    public function create(array $userData): int
    {
        $roleMap = ['seller' => 1, 'buyer' => 2, 'admin' => 3];
        $roleId = $roleMap[$userData['role']] ?? 2;

        $emailUsername = explode('@', $userData['email'])[0];
        $slug = $this->generateUniqueSlug($emailUsername);

        $stmt = $this->db->prepare("
            INSERT INTO users (role_id, email, slug, password_hash, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'active', NOW(), NOW())
        ");
        $stmt->execute([
            $roleId,
            $userData['email'],
            $slug,
            password_hash($userData['password'], PASSWORD_DEFAULT)
        ]);

        $userId = (int)$this->db->lastInsertId();

        // Create profile
        $stmt = $this->db->prepare("
            INSERT INTO user_profiles (user_id, display_name, rating_avg, rating_count, created_at, updated_at)
            VALUES (?, ?, 0.00, 0, NOW(), NOW())
        ");
        $stmt->execute([$userId, $userData['display_name'] ?? $userData['email']]);

        return $userId;
    }

    public function update(int $userId, array $data): bool
    {
        $updates = [];
        $params = [];

        if (isset($data['display_name'])) {
            $updates[] = "up.display_name = ?";
            $params[] = $data['display_name'];
        }

        if (isset($data['email'])) {
            $updates[] = "u.email = ?";
            $params[] = $data['email'];
        }

        if (isset($data['password'])) {
            $updates[] = "u.password_hash = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($updates)) {
            return false;
        }

        $params[] = $userId;

        $sql = "UPDATE users u LEFT JOIN user_profiles up ON u.id = up.user_id SET " . implode(', ', $updates) . " WHERE u.id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $userId): bool
    {
        // Check if admin
        $user = $this->findById($userId);
        if ($user && $user['role_id'] === 3) {
            return false; // Cannot delete admin
        }

        $this->db->beginTransaction();
        try {
            // Delete profile
            $this->db->prepare("DELETE FROM user_profiles WHERE user_id = ?")->execute([$userId]);
            // Delete user
            $this->db->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT u.*, up.display_name FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id ORDER BY u.created_at DESC");
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        // Add role string for backward compatibility
        $roleMap = [1 => 'seller', 2 => 'buyer', 3 => 'admin'];
        foreach ($users as &$user) {
            $user['role'] = $roleMap[$user['role_id']] ?? 'buyer';
        }
        
        return $users;
    }

    private function generateUniqueSlug(string $base): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $base));
        $counter = 1;
        $originalSlug = $slug;

        while (true) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE slug = ?");
            $stmt->execute([$slug]);
            if (!$stmt->fetch()) {
                return $slug;
            }
            $slug = $originalSlug . '-' . $counter++;
        }
    }
}
