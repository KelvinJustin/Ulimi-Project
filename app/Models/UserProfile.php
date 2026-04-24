<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class UserProfile
{
    public $db;
    
    public function __construct()
    {
        $this->db = \App\Core\Database::pdo();
    }
    
    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM user_profiles WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createDefault(int $userId, string $displayName): void
    {
        $stmt = $this->db->prepare('INSERT INTO user_profiles (user_id, display_name) VALUES (:uid, :display_name)');
        $stmt->execute(['uid' => $userId, 'display_name' => $displayName]);
    }
}
