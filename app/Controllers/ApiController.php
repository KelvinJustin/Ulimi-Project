<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\View;
use App\Core\Csrf;
use App\Models\Listing;

final class ApiController
{
    private Listing $listingModel;

    public function __construct(Listing $listingModel = null)
    {
        $this->listingModel = $listingModel ?? new Listing();
    }

    public function sellerProducts(Request $request): void
    {
        // Authentication and role checks now handled by 'seller' middleware

        $user = Auth::user();

        try {
            $listing = $this->listingModel;
            $filters = ['seller_id' => $user['id']];
            $products = $listing->search($filters);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'products' => $products,
                'count' => count($products),
                'seller' => [
                    'id' => $user['id'],
                    'name' => ucfirst($user['display_name'] ?? $user['email'])
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function products(Request $request): void
    {
        try {
            $listing = $this->listingModel;
            $filters = [
                'category' => $request->input('category'),
                'location' => $request->input('location'),
                'search' => $request->input('search')
            ];

            $products = $listing->search($filters);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'products' => $products,
                'count' => count($products)
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function addFavorite(Request $request): void
    {
        // Authentication check now handled by 'auth' middleware

        $user = Auth::user();
        $listingId = $request->input('listing_id');

        if (!$listingId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Listing ID is required']);
            return;
        }

        try {
            $db = \App\Core\Database::pdo();
            $stmt = $db->prepare(
                "INSERT INTO favorites (user_id, listing_id) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE id = id"
            );
            $stmt->execute([$user['id'], $listingId]);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Added to favorites']);

        } catch (\PDOException $e) {
            // Table doesn't exist - try to create it automatically
            if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), "Table")) {
                try {
                    $db->exec("CREATE TABLE IF NOT EXISTS favorites (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        user_id BIGINT UNSIGNED NOT NULL,
                        listing_id BIGINT UNSIGNED NOT NULL,
                        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                        FOREIGN KEY (listing_id) REFERENCES commodity_listings(id) ON DELETE CASCADE,
                        UNIQUE KEY uniq_user_listing (user_id, listing_id),
                        INDEX idx_favorites_user (user_id),
                        INDEX idx_favorites_listing (listing_id)
                    ) ENGINE=InnoDB");
                    
                    // Retry the insert after creating table
                    $stmt = $db->prepare(
                        "INSERT INTO favorites (user_id, listing_id) VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE id = id"
                    );
                    $stmt->execute([$user['id'], $listingId]);
                    
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Added to favorites']);
                } catch (\PDOException $createError) {
                    error_log('Failed to create favorites table: ' . $createError->getMessage());
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Favorites feature not available']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function removeFavorite(Request $request): void
    {
        // Authentication check now handled by 'auth' middleware

        $user = Auth::user();
        $listingId = $request->input('listing_id');

        if (!$listingId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Listing ID is required']);
            return;
        }

        try {
            $db = \App\Core\Database::pdo();
            $stmt = $db->prepare(
                "DELETE FROM favorites WHERE user_id = ? AND listing_id = ?"
            );
            $stmt->execute([$user['id'], $listingId]);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Removed from favorites']);

        } catch (\PDOException $e) {
            // Table doesn't exist - try to create it automatically
            if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), "Table")) {
                try {
                    $db->exec("CREATE TABLE IF NOT EXISTS favorites (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        user_id BIGINT UNSIGNED NOT NULL,
                        listing_id BIGINT UNSIGNED NOT NULL,
                        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                        FOREIGN KEY (listing_id) REFERENCES commodity_listings(id) ON DELETE CASCADE,
                        UNIQUE KEY uniq_user_listing (user_id, listing_id),
                        INDEX idx_favorites_user (user_id),
                        INDEX idx_favorites_listing (listing_id)
                    ) ENGINE=InnoDB");
                    
                    // If table was just created, there's nothing to remove
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Removed from favorites']);
                } catch (\PDOException $createError) {
                    error_log('Failed to create favorites table: ' . $createError->getMessage());
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Favorites feature not available']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function getFavorites(Request $request): void
    {
        // Authentication check now handled by 'auth' middleware

        $user = Auth::user();

        try {
            $db = \App\Core\Database::pdo();
            $stmt = $db->prepare(
                "SELECT listing_id FROM favorites WHERE user_id = ?"
            );
            $stmt->execute([$user['id']]);
            $favorites = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'favorites' => $favorites
            ]);

        } catch (\PDOException $e) {
            // Table doesn't exist yet
            error_log('Favorites table not found: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'favorites' => []
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
