<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class Order
{
    public $db;
    
    public function __construct()
    {
        $this->db = \App\Core\Database::pdo();
    }
    
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
