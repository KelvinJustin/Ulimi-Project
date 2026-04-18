<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Csrf;
use App\Core\Auth;
use App\Models\Listing;

final class BrowseController
{
    public function index(): void
    {
        // Get filter parameters from query string
        $filters = [
            'category' => $_GET['category'] ?? 'all',
            'location' => $_GET['location'] ?? 'all',
            'q' => $_GET['q'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        // Build search filters for the model
        $searchFilters = ['status' => 'active']; // Only show active listings to public

        if ($filters['category'] !== 'all') {
            $searchFilters['category'] = $filters['category'];
        }

        if ($filters['location'] !== 'all') {
            $searchFilters['location'] = $filters['location'];
        }

        if (!empty($filters['q'])) {
            $searchFilters['search'] = $filters['q'];
        }

        // Fetch listings from database
        $listingModel = new Listing();
        $listings = $listingModel->search($searchFilters);
        $count = count($listings);

        // Get authentication state
        $isLoggedIn = Auth::check();
        $user = $isLoggedIn ? Auth::user() : null;

        // Fetch user favorites if logged in
        $userFavorites = [];
        if ($isLoggedIn && $user) {
            try {
                $pdo = \App\Core\Database::pdo();
                $stmt = $pdo->prepare("SELECT listing_id FROM favorites WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $favorites = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                $userFavorites = $favorites ?: [];
            } catch (\PDOException $e) {
                // Table doesn't exist - try to create it automatically
                if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), "Table")) {
                    try {
                        $pdo->exec("CREATE TABLE IF NOT EXISTS favorites (
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
                        
                        // Try fetching favorites again after creating table
                        $stmt = $pdo->prepare("SELECT listing_id FROM favorites WHERE user_id = ?");
                        $stmt->execute([$user['id']]);
                        $favorites = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                        $userFavorites = $favorites ?: [];
                    } catch (\PDOException $createError) {
                        error_log('Failed to create favorites table: ' . $createError->getMessage());
                        $userFavorites = [];
                    }
                } else {
                    error_log('Failed to fetch favorites: ' . $e->getMessage());
                    $userFavorites = [];
                }
            } catch (Exception $e) {
                error_log('Failed to fetch favorites: ' . $e->getMessage());
                $userFavorites = [];
            }
        }

        View::render('browse.index', [
            'title' => 'Browse Agricultural Products - Ulimi Marketplace',
            'csrf' => Csrf::token(),
            'listings' => $listings,
            'count' => $count,
            'filters' => $filters,
            'isLoggedIn' => $isLoggedIn,
            'userId' => $user ? $user['id'] : null,
            'userFavorites' => $userFavorites
        ]);
    }

    public function favorites(): void
    {
        $isLoggedIn = Auth::check();
        
        if (!$isLoggedIn) {
            // Redirect to login if not logged in
            header('Location: /login');
            exit;
        }

        $user = Auth::user();
        
        // Direct file logging instead of error_log
        file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Favorites page - User ID: ' . $user['id'] . PHP_EOL, FILE_APPEND);
        error_log('Favorites page - User ID: ' . $user['id']);
        
        try {
            $pdo = \App\Core\Database::pdo();
            
            // First, check if user has any favorites at all
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $count = $stmt->fetch();
            file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Favorites count for user: ' . $count['count'] . PHP_EOL, FILE_APPEND);
            
            // Fetch user's favorite listing IDs with listing details
            $stmt = $pdo->prepare("
                SELECT
                    cl.id,
                    cl.title,
                    cl.description,
                    cl.price_per_unit as price,
                    cl.quantity_available as quantity,
                    cl.currency,
                    cl.location_text as location,
                    cl.quality_grade,
                    cl.status,
                    cl.created_at,
                    c.name as commodity_name,
                    c.category as commodity_category,
                    up.display_name as seller_name,
                    u.id as seller_id,
                    u.slug as seller_slug,
                    u.email as seller_email,
                    li.path as image_path
                FROM favorites f
                INNER JOIN commodity_listings cl ON f.listing_id = cl.id
                INNER JOIN commodities c ON cl.commodity_id = c.id
                INNER JOIN users u ON cl.seller_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN listing_images li ON cl.id = li.listing_id
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC
            ");
            $stmt->execute([$user['id']]);
            $favorites = $stmt->fetchAll();
            file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Fetched ' . count($favorites) . ' favorites with details' . PHP_EOL, FILE_APPEND);
            
            // Group images by listing ID
            $listingsWithImages = [];
            foreach ($favorites as $fav) {
                $listingId = $fav['id'];
                if (!isset($listingsWithImages[$listingId])) {
                    $listingsWithImages[$listingId] = $fav;
                    $listingsWithImages[$listingId]['images'] = [];
                }
                if ($fav['image_path']) {
                    $listingsWithImages[$listingId]['images'][] = $fav['image_path'];
                }
            }
            
            $favorites = array_values($listingsWithImages);
            file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Final favorites count after grouping: ' . count($favorites) . PHP_EOL, FILE_APPEND);
            
        } catch (\PDOException $e) {
            file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'PDOException in favorites: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            // Table doesn't exist - try to create it automatically
            if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), "Table")) {
                try {
                    $pdo->exec("CREATE TABLE IF NOT EXISTS favorites (
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
                    file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Created favorites table' . PHP_EOL, FILE_APPEND);
                    
                    // Retry fetching favorites after creating table
                    $stmt = $pdo->prepare("
                        SELECT
                            cl.id,
                            cl.title,
                            cl.description,
                            cl.price_per_unit as price,
                            cl.quantity_available as quantity,
                            cl.currency,
                            cl.location_text as location,
                            cl.quality_grade,
                            cl.status,
                            cl.created_at,
                            c.name as commodity_name,
                            c.category as commodity_category,
                            up.display_name as seller_name,
                            u.id as seller_id,
                            u.slug as seller_slug,
                            u.email as seller_email,
                            li.path as image_path
                        FROM favorites f
                        INNER JOIN commodity_listings cl ON f.listing_id = cl.id
                        INNER JOIN commodities c ON cl.commodity_id = c.id
                        INNER JOIN users u ON cl.seller_id = u.id
                        LEFT JOIN user_profiles up ON u.id = up.user_id
                        LEFT JOIN listing_images li ON cl.id = li.listing_id
                        WHERE f.user_id = ?
                        ORDER BY f.created_at DESC
                    ");
                    $stmt->execute([$user['id']]);
                    $favorites = $stmt->fetchAll();
                    
                    // Group images by listing ID
                    $listingsWithImages = [];
                    foreach ($favorites as $fav) {
                        $listingId = $fav['id'];
                        if (!isset($listingsWithImages[$listingId])) {
                            $listingsWithImages[$listingId] = $fav;
                            $listingsWithImages[$listingId]['images'] = [];
                        }
                        if ($fav['image_path']) {
                            $listingsWithImages[$listingId]['images'][] = $fav['image_path'];
                        }
                    }
                    
                    $favorites = array_values($listingsWithImages);
                } catch (\PDOException $createError) {
                    file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Failed to create favorites table: ' . $createError->getMessage() . PHP_EOL, FILE_APPEND);
                    $favorites = [];
                }
            } else {
                file_put_contents(__DIR__ . '/../../storage/debug.log', date('[Y-m-d H:i:s] ') . 'Failed to fetch favorites: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
                $favorites = [];
            }
        }

        View::render('browse.favorites', [
            'title' => 'My Favorites - Ulimi Marketplace',
            'favorites' => $favorites,
            'count' => count($favorites),
            'user' => $user
        ]);
    }

    public function viewListing(\App\Core\Request $request, array $params): void
    {
        $listingId = (int)($params['id'] ?? 0);

        if ($listingId <= 0) {
            http_response_code(404);
            echo 'Listing not found';
            return;
        }

        try {
            $pdo = \App\Core\Database::pdo();

            // Fetch listing details with seller info and first image
            $stmt = $pdo->prepare("
                SELECT
                    cl.id,
                    cl.title,
                    cl.description,
                    cl.price_per_unit as price,
                    cl.quantity_available as quantity,
                    cl.currency as unit,
                    cl.location_text as location,
                    cl.quality_grade,
                    cl.status,
                    cl.created_at,
                    cl.image_path as listing_image_path,
                    c.name as commodity_name,
                    c.category as commodity_category,
                    up.display_name as seller_name,
                    u.id as seller_id,
                    u.slug as seller_slug,
                    u.email as seller_email,
                    li.path as gallery_image_path
                FROM commodity_listings cl
                INNER JOIN commodities c ON cl.commodity_id = c.id
                INNER JOIN users u ON cl.seller_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN listing_images li ON cl.id = li.listing_id
                WHERE cl.id = ?
                LIMIT 1
            ");
            $stmt->execute([$listingId]);
            $listing = $stmt->fetch();

            if (!$listing) {
                http_response_code(404);
                echo 'Listing not found';
                return;
            }

            // Fetch all listing images
            $stmt = $pdo->prepare("SELECT path FROM listing_images WHERE listing_id = ? ORDER BY id ASC");
            $stmt->execute([$listingId]);
            $images = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            $images = $images ?: [];

            // Use listing_image_path from commodity_listings as primary, fallback to gallery image
            $listing['image_path'] = $listing['listing_image_path'] ?? $listing['gallery_image_path'] ?? null;

            // Debug: Log image data
            error_log('Listing ID: ' . $listingId . ', listing_image_path: ' . ($listing['listing_image_path'] ?? 'NULL'));
            error_log('Gallery image path: ' . ($listing['gallery_image_path'] ?? 'NULL'));
            error_log('Images array: ' . json_encode($images));

            // Get user's favorite status if logged in
            $isFavorite = false;
            $isLoggedIn = Auth::check();
            if ($isLoggedIn) {
                $user = Auth::user();
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND listing_id = ?");
                $stmt->execute([$user['id'], $listingId]);
                $isFavorite = $stmt->fetchColumn() > 0;
            }

            View::render('browse.listing', [
                'title' => $listing['title'] . ' - Ulimi Marketplace',
                'listing' => $listing,
                'images' => $images,
                'isFavorite' => $isFavorite,
                'isLoggedIn' => $isLoggedIn
            ]);

        } catch (\PDOException $e) {
            error_log('Error fetching listing: ' . $e->getMessage());
            http_response_code(500);
            echo 'Error loading listing';
        }
    }

    public function viewSellerProfile(\App\Core\Request $request, array $params): void
    {
        $sellerSlug = $params['id'] ?? '';

        if (empty($sellerSlug)) {
            http_response_code(404);
            echo 'Seller not found';
            return;
        }

        try {
            $pdo = \App\Core\Database::pdo();

            // Fetch seller profile information by slug
            $stmt = $pdo->prepare("
                SELECT
                    u.id as seller_id,
                    u.slug as seller_slug,
                    u.email as seller_email,
                    u.created_at as user_created_at,
                    up.display_name,
                    up.business_name,
                    up.bio,
                    up.country,
                    up.region,
                    up.district,
                    up.city,
                    up.rating_avg,
                    up.rating_count,
                    up.avatar_path,
                    up.created_at as profile_created_at
                FROM users u
                LEFT JOIN user_profiles up ON u.id = up.user_id
                WHERE u.slug = ?
                LIMIT 1
            ");
            $stmt->execute([$sellerSlug]);
            $seller = $stmt->fetch();

            if (!$seller) {
                http_response_code(404);
                echo 'Seller not found';
                return;
            }

            $sellerId = $seller['seller_id'];

            // Fetch seller's active listings
            $stmt = $pdo->prepare("
                SELECT
                    cl.id,
                    cl.title,
                    cl.description,
                    cl.price_per_unit as price,
                    cl.quantity_available as quantity,
                    cl.currency,
                    cl.location_text as location,
                    cl.quality_grade,
                    cl.created_at,
                    cl.image_path as listing_image_path,
                    c.name as commodity_name,
                    c.category as commodity_category,
                    c.unit as commodity_unit,
                    li.path as gallery_image_path
                FROM commodity_listings cl
                INNER JOIN commodities c ON cl.commodity_id = c.id
                LEFT JOIN listing_images li ON cl.id = li.listing_id AND li.sort_order = 0
                WHERE cl.seller_id = ? AND cl.status = 'active'
                ORDER BY cl.created_at DESC
                LIMIT 12
            ");
            $stmt->execute([$sellerId]);
            $listings = $stmt->fetchAll();

            // Use listing_image_path from commodity_listings as primary, fallback to gallery image
            foreach ($listings as &$listing) {
                $listing['image_path'] = $listing['listing_image_path'] ?? $listing['gallery_image_path'] ?? null;
            }

            // Calculate completed orders count (handle if orders table doesn't exist)
            $completedOrders = 0;
            $totalOrders = 0;
            try {
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as completed_orders
                    FROM orders
                    WHERE seller_id = ? AND status = 'delivered'
                ");
                $stmt->execute([$sellerId]);
                $completedOrders = $stmt->fetch()['completed_orders'] ?? 0;

                // Calculate total orders count
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as total_orders
                    FROM orders
                    WHERE seller_id = ?
                ");
                $stmt->execute([$sellerId]);
                $totalOrders = $stmt->fetch()['total_orders'] ?? 0;
            } catch (\PDOException $e) {
                // Orders table doesn't exist yet, use defaults
                error_log('Orders table not found: ' . $e->getMessage());
            }

            // Calculate cancellation rate
            $cancellationRate = $totalOrders > 0 ? round(($totalOrders - $completedOrders) / $totalOrders * 100, 1) : 0;

            View::render('browse.seller-profile', [
                'title' => $seller['display_name'] . ' - Seller Profile - Ulimi Marketplace',
                'seller' => $seller,
                'listings' => $listings,
                'completedOrders' => $completedOrders,
                'totalOrders' => $totalOrders,
                'cancellationRate' => $cancellationRate,
                'isLoggedIn' => Auth::check()
            ]);

        } catch (\PDOException $e) {
            error_log('Error fetching seller profile: ' . $e->getMessage());
            http_response_code(500);
            echo 'Error loading seller profile: ' . $e->getMessage();
        }
    }
}
