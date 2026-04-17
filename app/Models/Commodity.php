<?php
declare(strict_types=1);

namespace App\Models;

final class Commodity
{
    public $db;
    
    public function __construct()
    {
        $this->db = \App\Core\Database::pdo();
    }
    
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM commodities ORDER BY name ASC');
        return $stmt->fetchAll();
    }
}
