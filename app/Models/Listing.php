<?php
declare(strict_types=1);

namespace App\Models;

final class Listing
{
    public $db;
    
    public function __construct()
    {
        $this->db = \App\Core\Database::pdo();
    }
    
    public function search(array $filters = []): array
    {
        $sql = "SELECT
                    cl.*,
                    c.name as commodity_name,
                    c.category as commodity_category,
                    c.unit as commodity_unit,
                    up.display_name,
                    up.district,
                    up.region,
                    up.rating_avg,
                    up.rating_count
                FROM commodity_listings cl
                LEFT JOIN commodities c ON cl.commodity_id = c.id
                LEFT JOIN users u ON cl.seller_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                WHERE 1=1";
        $params = [];
        
        // Filter by status if specified (default to 'active' for public browse)
        if (isset($filters['status'])) {
            if (is_array($filters['status'])) {
                $placeholders = implode(',', array_fill(0, count($filters['status']), '?'));
                $sql .= " AND cl.status IN ($placeholders)";
                $params = array_merge($params, $filters['status']);
            } else {
                $sql .= " AND cl.status = ?";
                $params[] = $filters['status'];
            }
        }
        
        if (!empty($filters['seller_id'])) {
            $sql .= " AND cl.seller_id = ?";
            $params[] = $filters['seller_id'];
        }
        
        if (!empty($filters['category'])) {
            $sql .= " AND c.category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['location'])) {
            $sql .= " AND cl.location_text = ?";
            $params[] = $filters['location'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (cl.title LIKE ? OR cl.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY cl.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function findById(int $id): ?array
    {
        $sql = "SELECT 
                    cl.*,
                    c.name as commodity_name,
                    c.category as commodity_category,
                    c.unit as commodity_unit,
                    up.display_name,
                    up.district,
                    up.region,
                    up.rating_avg,
                    up.rating_count
                FROM commodity_listings cl
                LEFT JOIN commodities c ON cl.commodity_id = c.id
                LEFT JOIN users u ON cl.seller_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                WHERE cl.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    
    public function getImages(int $listingId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM listing_images WHERE listing_id = ?");
        $stmt->execute([$listingId]);
        return $stmt->fetchAll();
    }
}
