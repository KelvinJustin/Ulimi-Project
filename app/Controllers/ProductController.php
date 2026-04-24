<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Validator;
use App\Core\View;
use App\Models\Listing;
use App\Models\Commodity;

final class ProductController
{
    private Listing $listingModel;
    private Commodity $commodityModel;

    public function __construct(Listing $listingModel = null, Commodity $commodityModel = null)
    {
        $this->listingModel = $listingModel ?? new Listing();
        $this->commodityModel = $commodityModel ?? new Commodity();
    }

    public function showCreateListing(): void
    {
        View::render('listings.create', [
            'title' => 'Create Listing - Ulimi Agricultural Marketplace',
            'csrf' => Csrf::token(),
            'errors' => [],
            'old' => [],
        ]);
    }

    private function isListingComplete(array $data): bool
    {
        // Check if all required fields are present and non-empty
        $requiredFields = ['title', 'description', 'category', 'location', 'quantity', 'price', 'price_unit'];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        
        // Additional validation: quantity and price must be positive
        if ($data['quantity'] <= 0 || $data['price'] <= 0) {
            return false;
        }
        
        // Image is required for complete listing
        if (empty($data['image'])) {
            return false;
        }
        
        return true;
    }

    public function createListing(Request $request): void
    {
        // Debug logging
        error_log('=== CREATE LISTING START ===');
        
        $user = Auth::user();
        error_log('User authenticated: ' . json_encode($user));

        // CSRF validation is now handled by CsrfMiddleware
        
        error_log('CSRF validation passed');

        // Collect form data
        $title = trim((string)$request->input('title', ''));
        $description = trim((string)$request->input('description', ''));
        $category = (string)$request->input('category', '');
        $location = (string)$request->input('location', '');
        $quantity = (float)$request->input('quantity', 0);
        $price = (float)$request->input('price', 0);
        $priceUnit = (string)$request->input('price_unit', 'kg');
        $qualityGrade = (string)$request->input('quality_grade', '');
        $minOrderQuantity = (float)$request->input('min_order_quantity', 1);
        $saveAsDraft = (bool)$request->input('save_as_draft', false);

        $errors = [];

        // Validate required fields
        if (!Validator::str($title, 3, 255)) {
            $errors['title'] = 'Product title must be between 3 and 255 characters.';
        }

        if (!Validator::str($description, 10, 2000)) {
            $errors['description'] = 'Description must be between 10 and 2000 characters.';
        }

        if (!Validator::in($category, ['grains', 'legumes', 'vegetables', 'fruits', 'cash-crops', 'livestock', 'inputs'])) {
            $errors['category'] = 'Please select a valid category.';
        }

        if (!Validator::numeric($quantity, 1, 999999)) {
            $errors['quantity'] = 'Quantity must be a positive number.';
        }

        if (!Validator::numeric($price, 0.01, 999999)) {
            $errors['price'] = 'Price must be a positive number.';
        }

        if (!Validator::in($priceUnit, ['kg', 'bag', 'ton', 'piece', 'liter'])) {
            $errors['price_unit'] = 'Please select a valid price unit.';
        }

        if (!empty($qualityGrade) && !Validator::in($qualityGrade, ['premium', 'standard', 'basic'])) {
            $errors['quality_grade'] = 'Please select a valid quality grade.';
        }

        if (!Validator::numeric($minOrderQuantity, 0.01, 999999)) {
            $errors['min_order_quantity'] = 'Minimum order quantity must be a positive number.';
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['product_image']);
            if ($imagePath === null) {
                $errors['product_image'] = 'Invalid image file. Please upload a JPG or PNG file under 5MB.';
            }
        }

        // Determine if we should save as draft or require complete validation
        $isDraft = $saveAsDraft || !$this->isListingComplete([
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'location' => $location,
            'quantity' => $quantity,
            'price' => $price,
            'price_unit' => $priceUnit,
            'image' => $imagePath
        ]);

        // If not saving as draft, validate all required fields
        if (!$isDraft && $errors) {
            View::render('listings.create', [
                'title' => 'Create Listing - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => [
                    'title' => $title,
                    'description' => $description,
                    'category' => $category,
                    'location' => $location,
                    'quantity' => $quantity,
                    'price' => $price,
                    'price_unit' => $priceUnit,
                    'quality_grade' => $qualityGrade,
                    'min_order_quantity' => $minOrderQuantity
                ],
            ]);
            return;
        }

        // Determine status: draft if incomplete or explicitly saved as draft, pending if complete
        $status = $isDraft ? 'draft' : 'pending';

        // Prepare product data
        $productData = [
            'seller_id' => $user['id'],
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'location' => $location,
            'quantity' => $quantity,
            'price' => $price,
            'price_unit' => $priceUnit,
            'image' => $imagePath,
            'status' => $status
        ];

        // Debug output for form submission
        error_log('=== CREATE LISTING START ===');
        error_log('User authenticated: ' . json_encode($user));
        error_log('POST data: ' . json_encode($_POST));
        error_log('FILES data: ' . json_encode($_FILES));
        
        // Create product using database with fallback
        try {
            error_log('Starting database operations');
            $listing = $this->listingModel;
            $pdo = $listing->db;
            error_log('Database connection established');
            
            // Find or create commodity
            error_log('Finding or creating commodity');
            $commodity = $this->findOrCreateCommodity($title, $category);
            error_log('Commodity found/created: ' . json_encode($commodity));
            
            // Prepare listing data for database
            $listingData = [
                'seller_id' => $user['id'],
                'commodity_id' => $commodity['id'],
                'title' => $title,
                'description' => $description,
                'quality_grade' => $qualityGrade ?: null,
                'price_per_unit' => $price,
                'currency' => 'MWK',
                'price_unit' => $priceUnit,
                'quantity_available' => $quantity,
                'min_order_quantity' => $minOrderQuantity,
                'location_text' => $this->getLocationName($location),
                'latitude' => null,
                'longitude' => null,
                'status' => $status,
                'image_path' => $imagePath
            ];

            // Insert listing
            $stmt = $pdo->prepare("
                INSERT INTO commodity_listings (
                    seller_id, commodity_id, title, description, quality_grade,
                    price_per_unit, currency, price_unit, quantity_available, min_order_quantity,
                    location_text, latitude, longitude, status, image_path, created_at, updated_at
                ) VALUES (
                    :seller_id, :commodity_id, :title, :description, :quality_grade,
                    :price_per_unit, :currency, :price_unit, :quantity_available, :min_order_quantity,
                    :location_text, :latitude, :longitude, :status, :image_path, NOW(), NOW()
                )
            ");

            $stmt->execute($listingData);
            $listingId = $pdo->lastInsertId();

            // Prepare listing data for success screen
            $listingData = [
                'title' => $title,
                'category' => $category,
                'price' => $price,
                'price_unit' => $priceUnit,
                'quantity' => $quantity,
                'location' => $location,
                'status' => $status
            ];
            
            // Render success screen
            View::render('listings.success', [
                'title' => 'Listing Created Successfully - Ulimi Agricultural Marketplace',
                'listing' => $listingData
            ]);
            return;

        } catch (Exception $e) {
            error_log('Failed to create listing: ' . $e->getMessage());
            error_log('Exception trace: ' . $e->getTraceAsString());
            View::render('listings.create', [
                'title' => 'Create Listing - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => ['general' => 'Failed to create listing: ' . $e->getMessage()],
                'old' => [
                    'title' => $title,
                    'description' => $description,
                    'category' => $category,
                    'location' => $location,
                    'quantity' => $quantity,
                    'price' => $price,
                    'price_unit' => $priceUnit,
                    'quality_grade' => $qualityGrade,
                    'min_order_quantity' => $minOrderQuantity
                ],
            ]);
        }
    }

    private function handleImageUpload(array $file): ?string
    {
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validate MIME type
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            return null;
        }

        // Validate file size
        if ($file['size'] > $maxSize) {
            return null;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = UPLOADS_PATH . '/products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $filename = uniqid('product_', true) . '.' . $extension;
        $targetPath = $uploadDir . '/' . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/products/' . $filename;
        }

        return null;
    }

    private function findOrCreateCommodity(string $title, string $category): array
    {
        // Use database connection from Listing model for consistency
        $pdo = $this->listingModel->db;
        
        // Try to find existing commodity by name (more specific)
        $stmt = $pdo->prepare("SELECT * FROM commodities WHERE name = ? LIMIT 1");
        $stmt->execute([$title]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            return $existing;
        }
        
        // Create new commodity
        $stmt = $pdo->prepare("
            INSERT INTO commodities (name, category, unit) 
            VALUES (?, ?, 'kg')
        ");
        $stmt->execute([$title, $this->mapCategoryToName($category)]);
        
        $id = $pdo->lastInsertId();
        return [
            'id' => $id,
            'name' => $title,
            'category' => $this->mapCategoryToName($category),
            'unit' => 'kg'
        ];
    }

    private function mapCategoryToName(string $category): string
    {
        $mapping = [
            'grains' => 'Cereals',
            'legumes' => 'Legumes',
            'vegetables' => 'Vegetables',
            'fruits' => 'Fruits',
            'cash-crops' => 'Cash Crops',
            'livestock' => 'Livestock',
            'inputs' => 'Inputs'
        ];
        
        return $mapping[$category] ?? 'Cereals';
    }

    private function getLocationName(string $location): string
    {
        $locations = [
            // Northern Region
            'chitipa' => 'Chitipa',
            'karonga' => 'Karonga',
            'nkhata-bay' => 'Nkhata Bay',
            'rumphi' => 'Rumphi',
            'mzimba' => 'Mzimba',
            'likoma' => 'Likoma',
            // Central Region
            'kasungu' => 'Kasungu',
            'nkhotakota' => 'Nkhotakota',
            'ntcheu' => 'Ntcheu',
            'ntchisi' => 'Ntchisi',
            'dedza' => 'Dedza',
            'dowa' => 'Dowa',
            'lilongwe' => 'Lilongwe',
            'mchinji' => 'Mchinji',
            'salima' => 'Salima',
            // Southern Region
            'balaka' => 'Balaka',
            'blantyre' => 'Blantyre',
            'chikwawa' => 'Chikwawa',
            'chiradzulu' => 'Chiradzulu',
            'machinga' => 'Machinga',
            'mangochi' => 'Mangochi',
            'mulanje' => 'Mulanje',
            'mwanza' => 'Mwanza',
            'neno' => 'Neno',
            'nsanje' => 'Nsanje',
            'phalombe' => 'Phalombe',
            'thyolo' => 'Thyolo',
            'zomba' => 'Zomba'
        ];
        
        return $locations[$location] ?? $location;
    }

    public function getProducts(Request $request): void
    {
        header('Content-Type: application/json');
        
        try {
            $listing = $this->listingModel;
            $filters = [
                'category' => $request->input('category'),
                'location' => $request->input('location'),
                'search' => $request->input('search')
            ];
            
            $products = $listing->search($filters);
            
            echo json_encode([
                'success' => true,
                'products' => $products,
                'count' => count($products)
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching products: ' . $e->getMessage()
            ]);
        }
    }

    public function showListings(): void
    {
        try {
            $listing = $this->listingModel;
            $listings = $listing->search([]); // Get all listings
            
            View::render('listings.index', [
                'title' => 'Current Listings - Ulimi Agricultural Marketplace',
                'listings' => $listings
            ]);
            
        } catch (Exception $e) {
            error_log('Error fetching listings: ' . $e->getMessage());
            View::render('listings.index', [
                'title' => 'Current Listings - Ulimi Agricultural Marketplace',
                'listings' => [],
                'error' => 'Unable to load listings at this time. Please try again later.'
            ]);
        }
    }

    public function showSellerListings(): void
    {
        $user = Auth::user();
        
        try {
            $listing = $this->listingModel;
            
            // Get listings for the current seller
            $listings = $listing->search(['seller_id' => $user['id']]);
            
            View::render('listings.seller', [
                'title' => 'My Listings - Ulimi Agricultural Marketplace',
                'listings' => $listings,
                'user' => $user,
                'csrf' => Csrf::token()
            ]);

        } catch (Exception $e) {
            error_log('Error fetching seller listings: ' . $e->getMessage());
            View::render('listings.seller', [
                'title' => 'My Listings - Ulimi Agricultural Marketplace',
                'listings' => [],
                'error' => 'Unable to load your listings at this time. Please try again later.',
                'csrf' => Csrf::token()
            ]);
        }
    }

    public function deleteListing(Request $request): void
    {
        header('Content-Type: application/json');

        try {
            // CSRF validation is now handled by CsrfMiddleware

            // Require seller authentication
            $user = Auth::user();

            $listingId = (int)$request->input('listing_id', 0);
            if ($listingId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
                return;
            }

            // Get the listing to verify ownership
            $listing = $this->listingModel;
            $pdo = $listing->db;
            $stmt = $pdo->prepare("SELECT * FROM commodity_listings WHERE id = ? AND seller_id = ?");
            $stmt->execute([$listingId, $user['id']]);
            $listingData = $stmt->fetch();

            if (!$listingData) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Listing not found or you do not have permission']);
                return;
            }

            // Delete listing images from filesystem
            if (!empty($listingData['image_path'])) {
                $imagePath = PUBLIC_PATH . '/' . $listingData['image_path'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Fetch and delete images from listing_images table
            $stmt = $pdo->prepare("SELECT path FROM listing_images WHERE listing_id = ?");
            $stmt->execute([$listingId]);
            $listingImages = $stmt->fetchAll();

            foreach ($listingImages as $image) {
                if (!empty($image['path'])) {
                    $imagePath = PUBLIC_PATH . '/' . $image['path'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            // Delete listing from database
            $pdo->beginTransaction();
            try {
                // Delete from related tables first (in case CASCADE isn't working)
                $pdo->prepare("DELETE FROM cart_items WHERE listing_id = ?")->execute([$listingId]);
                $pdo->prepare("DELETE FROM favorites WHERE listing_id = ?")->execute([$listingId]);
                $pdo->prepare("DELETE FROM messages WHERE conversation_id IN (SELECT id FROM conversations WHERE listing_id = ?)")->execute([$listingId]);
                $pdo->prepare("DELETE FROM conversations WHERE listing_id = ?")->execute([$listingId]);
                
                // Delete from listing_images if exists
                $pdo->prepare("DELETE FROM listing_images WHERE listing_id = ?")->execute([$listingId]);
                
                // Delete from commodity_listings
                $pdo->prepare("DELETE FROM commodity_listings WHERE id = ?")->execute([$listingId]);
                
                $pdo->commit();
                
                echo json_encode(['success' => true, 'message' => 'Listing deleted successfully']);
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }

        } catch (Exception $e) {
            error_log('Error deleting listing: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete listing']);
        }
    }

    public function showEditListing(Request $request, array $params): void
    {
        $user = Auth::user();
        $listingId = (int)($params['id'] ?? 0);

        // Get the listing to verify ownership
        $pdo = $this->listingModel->db;
        $stmt = $pdo->prepare("SELECT * FROM commodity_listings WHERE id = ? AND seller_id = ?");
        $stmt->execute([$listingId, $user['id']]);
        $listingData = $stmt->fetch();

        if (!$listingData) {
            View::render('listings.seller', [
                'title' => 'My Listings - Ulimi Agricultural Marketplace',
                'listings' => [],
                'error' => 'Listing not found or you do not have permission to edit it.'
            ]);
            return;
        }

        // Get commodity info
        $stmt = $pdo->prepare("SELECT * FROM commodities WHERE id = ?");
        $stmt->execute([$listingData['commodity_id']]);
        $commodity = $stmt->fetch();

        // Map category back from commodity category
        $categoryMap = [
            'Cereals' => 'grains',
            'Legumes' => 'legumes',
            'Vegetables' => 'vegetables',
            'Fruits' => 'fruits',
            'Cash Crops' => 'cash-crops',
            'Livestock' => 'livestock',
            'Inputs' => 'inputs'
        ];
        $category = $categoryMap[$commodity['category']] ?? 'grains';

        // Prepare old data for form
        $old = [
            'title' => $listingData['title'],
            'description' => $listingData['description'],
            'category' => $category,
            'location' => strtolower(str_replace(' ', '-', $listingData['location_text'] ?? '')),
            'quantity' => $listingData['quantity_available'],
            'price' => $listingData['price_per_unit'],
            'price_unit' => 'kg',
            'quality_grade' => $listingData['quality_grade'],
            'min_order_quantity' => $listingData['min_order_quantity']
        ];

        View::render('create-listing', [
            'title' => 'Edit Listing - Ulimi Agricultural Marketplace',
            'csrf' => Csrf::token(),
            'errors' => [],
            'old' => $old,
            'isEdit' => true,
            'listingId' => $listingId,
            'existingImage' => $listingData['image_path']
        ]);
    }

    public function updateListing(Request $request, array $params): void
    {
        $user = Auth::user();
        $listingId = (int)($params['id'] ?? 0);

        // CSRF validation is now handled by CsrfMiddleware

        // Get the listing to verify ownership
        $pdo = $this->listingModel->db;
        $stmt = $pdo->prepare("SELECT * FROM commodity_listings WHERE id = ? AND seller_id = ?");
        $stmt->execute([$listingId, $user['id']]);
        $listingData = $stmt->fetch();

        if (!$listingData) {
            View::render('listings.seller', [
                'title' => 'My Listings - Ulimi Agricultural Marketplace',
                'listings' => [],
                'error' => 'Listing not found or you do not have permission to edit it.'
            ]);
            return;
        }

        // Collect form data
        $title = trim((string)$request->input('title', ''));
        $description = trim((string)$request->input('description', ''));
        $category = (string)$request->input('category', '');
        $location = (string)$request->input('location', '');
        $quantity = (float)$request->input('quantity', 0);
        $price = (float)$request->input('price', 0);
        $priceUnit = (string)$request->input('price_unit', 'kg');
        $qualityGrade = (string)$request->input('quality_grade', '');
        $minOrderQuantity = (float)$request->input('min_order_quantity', 1);

        $errors = [];

        // Validate required fields
        if (!Validator::str($title, 3, 255)) {
            $errors['title'] = 'Product title must be between 3 and 255 characters.';
        }

        if (!Validator::str($description, 10, 2000)) {
            $errors['description'] = 'Description must be between 10 and 2000 characters.';
        }

        if (!Validator::in($category, ['grains', 'legumes', 'vegetables', 'fruits', 'cash-crops', 'livestock', 'inputs'])) {
            $errors['category'] = 'Please select a valid category.';
        }

        if (!Validator::numeric($quantity, 1, 999999)) {
            $errors['quantity'] = 'Quantity must be a positive number.';
        }

        if (!Validator::numeric($price, 0.01, 999999)) {
            $errors['price'] = 'Price must be a positive number.';
        }

        if (!Validator::in($priceUnit, ['kg', 'bag', 'ton', 'piece', 'liter'])) {
            $errors['price_unit'] = 'Please select a valid price unit.';
        }

        if (!empty($qualityGrade) && !Validator::in($qualityGrade, ['premium', 'standard', 'basic'])) {
            $errors['quality_grade'] = 'Please select a valid quality grade.';
        }

        if (!Validator::numeric($minOrderQuantity, 0.01, 999999)) {
            $errors['min_order_quantity'] = 'Minimum order quantity must be a positive number.';
        }

        // Handle image upload
        $imagePath = $listingData['image_path']; // Keep existing image by default
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $newImagePath = $this->handleImageUpload($_FILES['product_image']);
            if ($newImagePath === null) {
                $errors['product_image'] = 'Invalid image file. Please upload a JPG or PNG file under 5MB.';
            } else {
                $imagePath = $newImagePath;
                // Delete old image if it exists
                if (!empty($listingData['image_path'])) {
                    $oldImagePath = PUBLIC_PATH . '/' . $listingData['image_path'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        }

        if ($errors) {
            View::render('listings.create', [
                'title' => 'Edit Listing - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => [
                    'title' => $title,
                    'description' => $description,
                    'category' => $category,
                    'location' => $location,
                    'quantity' => $quantity,
                    'price' => $price,
                    'price_unit' => $priceUnit,
                    'quality_grade' => $qualityGrade,
                    'min_order_quantity' => $minOrderQuantity
                ],
                'isEdit' => true,
                'listingId' => $listingId,
                'existingImage' => $listingData['image_path']
            ]);
            return;
        }

        try {
            // Find or create commodity
            $commodity = $this->findOrCreateCommodity($title, $category);

            // Update listing in database
            $stmt = $pdo->prepare("
                UPDATE commodity_listings
                SET commodity_id = ?, title = ?, description = ?, quality_grade = ?,
                    price_per_unit = ?, currency = ?, price_unit = ?, quantity_available = ?, min_order_quantity = ?,
                    location_text = ?, image_path = ?, updated_at = NOW()
                WHERE id = ? AND seller_id = ?
            ");

            $stmt->execute([
                $commodity['id'],
                $title,
                $description,
                $qualityGrade ?: null,
                $price,
                'MWK',
                $priceUnit,
                $quantity,
                $minOrderQuantity,
                $this->getLocationName($location),
                $imagePath,
                $listingId,
                $user['id']
            ]);

            // Render success screen
            View::render('listings.success', [
                'title' => 'Listing Updated Successfully - Ulimi Agricultural Marketplace',
                'listing' => [
                    'title' => $title,
                    'category' => $category,
                    'price' => $price,
                    'price_unit' => $priceUnit,
                    'quantity' => $quantity,
                    'location' => $location
                ]
            ]);

        } catch (Exception $e) {
            error_log('Failed to update listing: ' . $e->getMessage());
            View::render('listings.create', [
                'title' => 'Edit Listing - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => ['general' => 'Failed to update listing: ' . $e->getMessage()],
                'old' => [
                    'title' => $title,
                    'description' => $description,
                    'category' => $category,
                    'location' => $location,
                    'quantity' => $quantity,
                    'price' => $price,
                    'price_unit' => $priceUnit,
                    'quality_grade' => $qualityGrade,
                    'min_order_quantity' => $minOrderQuantity
                ],
                'isEdit' => true,
                'listingId' => $listingId,
                'existingImage' => $listingData['image_path']
            ]);
        }
    }

    private function saveListingImage(int $listingId, string $imagePath): void
    {
        $pdo = $this->listingModel->db;
        $stmt = $pdo->prepare("
            INSERT INTO listing_images (listing_id, path, sort_order)
            VALUES (?, ?, 0)
        ");
        $stmt->execute([$listingId, $imagePath]);
    }

    public function approveListing(Request $request): void
    {
        header('Content-Type: application/json');

        // CSRF validation is now handled by CsrfMiddleware

        // Require admin role - now handled by 'admin' middleware

        $listingId = (int)$request->input('listing_id', 0);
        if ($listingId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
            return;
        }

        try {
            $listing = $this->listingModel;
            $pdo = $listing->db;

            $stmt = $pdo->prepare("UPDATE commodity_listings SET status = 'active', updated_at = NOW() WHERE id = ?");
            $stmt->execute([$listingId]);

            echo json_encode(['success' => true, 'message' => 'Listing approved successfully']);
        } catch (Exception $e) {
            error_log('Error approving listing: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to approve listing']);
        }
    }

    public function rejectListing(Request $request): void
    {
        header('Content-Type: application/json');

        // CSRF validation is now handled by CsrfMiddleware

        // Require admin role - now handled by 'admin' middleware

        $listingId = (int)$request->input('listing_id', 0);
        if ($listingId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
            return;
        }

        try {
            $listing = $this->listingModel;
            $pdo = $listing->db;

            $stmt = $pdo->prepare("UPDATE commodity_listings SET status = 'draft', updated_at = NOW() WHERE id = ?");
            $stmt->execute([$listingId]);

            echo json_encode(['success' => true, 'message' => 'Listing rejected successfully']);
        } catch (Exception $e) {
            error_log('Error rejecting listing: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to reject listing']);
        }
    }

    public function archiveListing(Request $request): void
    {
        header('Content-Type: application/json');

        // CSRF validation is now handled by CsrfMiddleware

        // Require authentication - now handled by 'admin' middleware

        $listingId = (int)$request->input('listing_id', 0);
        if ($listingId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
            return;
        }

        try {
            $listing = $this->listingModel;
            $pdo = $listing->db;
            $user = Auth::user();

            // Check if user is admin or the listing owner
            $stmt = $pdo->prepare("SELECT seller_id FROM commodity_listings WHERE id = ?");
            $stmt->execute([$listingId]);
            $listingData = $stmt->fetch();

            if (!$listingData) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Listing not found']);
                return;
            }

            // Only admin or listing owner can archive
            if (!Auth::isAdmin() && $listingData['seller_id'] != $user['id']) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            $stmt = $pdo->prepare("UPDATE commodity_listings SET status = 'archived', updated_at = NOW() WHERE id = ?");
            $stmt->execute([$listingId]);

            echo json_encode(['success' => true, 'message' => 'Listing archived successfully']);
        } catch (Exception $e) {
            error_log('Error archiving listing: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to archive listing']);
        }
    }

    public function getPendingListings(): void
    {
        header('Content-Type: application/json');

        // Require admin role - now handled by 'admin' middleware

        try {
            $listing = $this->listingModel;
            $pdo = $listing->db;

            $stmt = $pdo->prepare("
                SELECT cl.*, c.name as commodity_name, up.display_name as seller_name
                FROM commodity_listings cl
                LEFT JOIN commodities c ON cl.commodity_id = c.id
                LEFT JOIN users u ON cl.seller_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                WHERE cl.status = 'pending'
                ORDER BY cl.created_at DESC
            ");
            $stmt->execute();
            $listings = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'listings' => $listings,
                'count' => count($listings)
            ]);
        } catch (Exception $e) {
            error_log('Error fetching pending listings: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch pending listings']);
        }
    }

    public function getAllListings(): void
    {
        header('Content-Type: application/json');

        // Require admin role - now handled by 'admin' middleware

        try {
            $listing = $this->listingModel;
            $pdo = $listing->db;

            // Get filter parameters
            $status = $_GET['status'] ?? '';
            $category = $_GET['category'] ?? '';

            // Build query with filters
            $sql = "
                SELECT cl.*, c.name as commodity_name, up.display_name as seller_name
                FROM commodity_listings cl
                LEFT JOIN commodities c ON cl.commodity_id = c.id
                LEFT JOIN users u ON cl.seller_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                WHERE 1=1
            ";

            $params = [];

            if (!empty($status)) {
                $sql .= " AND cl.status = :status";
                $params[':status'] = $status;
            }

            if (!empty($category)) {
                $sql .= " AND c.name = :category";
                $params[':category'] = $category;
            }

            $sql .= " ORDER BY cl.created_at DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $listings = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'listings' => $listings,
                'count' => count($listings)
            ]);
        } catch (Exception $e) {
            error_log('Error fetching all listings: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch listings']);
        }
    }

    public function autoArchiveOldListings(): void
    {
        // This method can be called via cron job or manually to auto-archive listings older than 4 months
        try {
            $listing = $this->listingModel;
            $pdo = $listing->db;

            // Archive listings that are active and older than 4 months
            $stmt = $pdo->prepare("
                UPDATE commodity_listings 
                SET status = 'archived', updated_at = NOW() 
                WHERE status = 'active' 
                AND created_at < DATE_SUB(NOW(), INTERVAL 4 MONTH)
            ");
            $stmt->execute();
            $affectedRows = $stmt->rowCount();

            error_log("Auto-archived {$affectedRows} listings older than 4 months");
        } catch (Exception $e) {
            error_log('Error auto-archiving listings: ' . $e->getMessage());
        }
    }
}
