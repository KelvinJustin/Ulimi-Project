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
                    up.rating_count,
                    u.id as seller_id,
                    u.slug as seller_slug
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

    public function deleteBySellerId(int $sellerId): int
    {
        // Get all listings for this seller
        $stmt = $this->db->prepare("SELECT id, image_path FROM commodity_listings WHERE seller_id = ?");
        $stmt->execute([$sellerId]);
        $listings = $stmt->fetchAll();

        $deletedCount = 0;

        foreach ($listings as $listing) {
            // Delete images from filesystem
            if (!empty($listing['image_path'])) {
                $imagePath = __DIR__ . '/../../public/' . $listing['image_path'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Fetch and delete images from listing_images table
            $stmt = $this->db->prepare("SELECT path FROM listing_images WHERE listing_id = ?");
            $stmt->execute([$listing['id']]);
            $listingImages = $stmt->fetchAll();

            foreach ($listingImages as $image) {
                if (!empty($image['path'])) {
                    $imagePath = __DIR__ . '/../../public/' . $image['path'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            // Delete from database in a transaction
            $this->db->beginTransaction();
            try {
                // Delete from cart_items (foreign key constraint)
                $this->db->prepare("DELETE FROM cart_items WHERE listing_id = ?")->execute([$listing['id']]);
                // Delete from listing_images
                $this->db->prepare("DELETE FROM listing_images WHERE listing_id = ?")->execute([$listing['id']]);
                // Delete from commodity_listings
                $this->db->prepare("DELETE FROM commodity_listings WHERE id = ?")->execute([$listing['id']]);
                $this->db->commit();
                $deletedCount++;
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log('Failed to delete listing ' . $listing['id'] . ': ' . $e->getMessage());
            }
        }

        return $deletedCount;
    }

    public function cleanupOrphanedListings(): int
    {
        // Get all user IDs from file storage (the actual active users)
        $fileUserIds = array_column(\App\Core\FileUserStorage::loadUsers(), 'id');

        // Find listings where seller_id doesn't exist in file storage
        if (empty($fileUserIds)) {
            // If no users in file storage, delete all listings
            $stmt = $this->db->prepare("SELECT id, seller_id, image_path FROM commodity_listings");
            $stmt->execute();
        } else {
            // Get listings where seller_id is NOT in file storage users
            $placeholders = implode(',', array_fill(0, count($fileUserIds), '?'));
            $stmt = $this->db->prepare("
                SELECT id, seller_id, image_path
                FROM commodity_listings
                WHERE seller_id NOT IN ($placeholders)
            ");
            $stmt->execute($fileUserIds);
        }

        $orphanedListings = $stmt->fetchAll();
        $deletedCount = 0;

        foreach ($orphanedListings as $listing) {
            // Delete images from filesystem
            if (!empty($listing['image_path'])) {
                $imagePath = __DIR__ . '/../../public/' . $listing['image_path'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Fetch and delete images from listing_images table
            $stmt = $this->db->prepare("SELECT path FROM listing_images WHERE listing_id = ?");
            $stmt->execute([$listing['id']]);
            $listingImages = $stmt->fetchAll();

            foreach ($listingImages as $image) {
                if (!empty($image['path'])) {
                    $imagePath = __DIR__ . '/../../public/' . $image['path'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            // Delete from database in a transaction
            $this->db->beginTransaction();
            try {
                // Delete from cart_items (foreign key constraint)
                $this->db->prepare("DELETE FROM cart_items WHERE listing_id = ?")->execute([$listing['id']]);
                // Delete from listing_images
                $this->db->prepare("DELETE FROM listing_images WHERE listing_id = ?")->execute([$listing['id']]);
                // Delete from commodity_listings
                $this->db->prepare("DELETE FROM commodity_listings WHERE id = ?")->execute([$listing['id']]);
                $this->db->commit();
                $deletedCount++;
                error_log('Deleted orphaned listing ID: ' . $listing['id'] . ' for seller_id: ' . $listing['seller_id']);
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log('Failed to delete orphaned listing ' . $listing['id'] . ': ' . $e->getMessage());
            }
        }

        return $deletedCount;
    }
}
