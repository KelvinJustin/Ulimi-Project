<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Database;
use App\Core\View;

final class CartController
{
    public function addItem(Request $request): void
    {
        // Start output buffering to catch any HTML output
        ob_start();
        
        // Set error handler to prevent HTML error output
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            error_log("CartController Error: [$errno] $errstr in $errfile on line $errline");
            return true;
        });

        header('Content-Type: application/json');

        try {
            // Debug logging
            error_log('CartController::addItem called');
            error_log('Request input: ' . json_encode($request->input()));

            // Authentication check now handled by 'auth' middleware

            $user = Auth::user();
            
            // Temporarily allow all logged-in users to add to cart for debugging
            // TODO: Re-enable buyer role check after fixing the issue
            // if (!Auth::isBuyer()) {
            //     http_response_code(403);
            //     echo json_encode(['success' => false, 'message' => 'Only buyers can add items to cart']);
            //     return;
            // }

            $listingId = (int)$request->input('listing_id', 0);
            $quantity = (float)$request->input('quantity', 1);

            error_log('Listing ID received: ' . $listingId);
            error_log('Quantity received: ' . $quantity);

            if ($listingId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
                ob_end_clean();
                return;
            }

            if ($quantity <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Quantity must be greater than 0']);
                ob_end_clean();
                return;
            }

            $pdo = Database::pdo();

            // Get listing details
            $stmt = $pdo->prepare("
                SELECT id, title, price_per_unit, quantity_available, status, seller_id
                FROM commodity_listings
                WHERE id = ?
            ");
            $stmt->execute([$listingId]);
            $listing = $stmt->fetch();

            if (!$listing) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Listing not found']);
                ob_end_clean();
                return;
            }

            if ($listing['status'] !== 'active') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'This listing is not available']);
                ob_end_clean();
                return;
            }

            // Check if buyer is trying to add their own listing
            if ($listing['seller_id'] == $user['id']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'You cannot add your own listing to cart']);
                ob_end_clean();
                return;
            }

            // Stock validation
            if ($quantity > $listing['quantity_available']) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock',
                    'available_quantity' => $listing['quantity_available']
                ]);
                ob_end_clean();
                return;
            }

            // Get or create active cart for the buyer
            $stmt = $pdo->prepare("
                SELECT id FROM carts
                WHERE buyer_id = ? AND status = 'active'
                LIMIT 1
            ");
            $stmt->execute([$user['id']]);
            $cart = $stmt->fetch();

            if (!$cart) {
                // Create new cart
                $stmt = $pdo->prepare("
                    INSERT INTO carts (buyer_id, status, created_at, updated_at)
                    VALUES (?, 'active', NOW(), NOW())
                ");
                $stmt->execute([$user['id']]);
                $cartId = $pdo->lastInsertId();
            } else {
                $cartId = $cart['id'];
            }

            // Check if item already exists in cart
            $stmt = $pdo->prepare("
                SELECT id, quantity FROM cart_items
                WHERE cart_id = ? AND listing_id = ?
            ");
            $stmt->execute([$cartId, $listingId]);
            $existingItem = $stmt->fetch();

            $pdo->beginTransaction();

            try {
                if ($existingItem) {
                    // Update existing item quantity
                    $newQuantity = $existingItem['quantity'] + $quantity;

                    // Validate new quantity against stock
                    if ($newQuantity > $listing['quantity_available']) {
                        $pdo->rollBack();
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'message' => 'Total quantity would exceed available stock',
                            'available_quantity' => $listing['quantity_available'],
                            'current_cart_quantity' => $existingItem['quantity']
                        ]);
                        ob_end_clean();
                        return;
                    }

                    $stmt = $pdo->prepare("
                        UPDATE cart_items
                        SET quantity = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$newQuantity, $existingItem['id']]);
                } else {
                    // Add new item to cart
                    $stmt = $pdo->prepare("
                        INSERT INTO cart_items (cart_id, listing_id, quantity, price_per_unit_at_add, created_at)
                        VALUES (?, ?, ?, ?, NOW())
                    ");
                    $stmt->execute([$cartId, $listingId, $quantity, $listing['price_per_unit']]);
                }

                $pdo->commit();

                // Get updated cart count
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count, SUM(ci.quantity * ci.price_per_unit_at_add) as total
                    FROM cart_items ci
                    JOIN carts c ON ci.cart_id = c.id
                    WHERE c.buyer_id = ? AND c.status = 'active'
                ");
                $stmt->execute([$user['id']]);
                $cartSummary = $stmt->fetch();

                echo json_encode([
                    'success' => true,
                    'message' => 'Item added to cart successfully',
                    'cart_count' => $cartSummary['count'],
                    'cart_total' => $cartSummary['total']
                ]);
                ob_end_clean();

            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }

        } catch (Exception $e) {
            error_log('Error adding item to cart: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
            ob_end_clean();
        }
    }

    public function getCart(): void
    {
        // Start output buffering to catch any HTML output
        ob_start();
        
        // Set error handler to prevent HTML error output
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            error_log("CartController Error: [$errno] $errstr in $errfile on line $errline");
            return true;
        });

        header('Content-Type: application/json');

        try {
            // Authentication check now handled by 'auth' middleware

            $user = Auth::user();
            $pdo = Database::pdo();

            // Get cart items
            $stmt = $pdo->prepare("
                SELECT 
                    ci.id as cart_item_id,
                    ci.quantity,
                    ci.price_per_unit_at_add,
                    cl.id as listing_id,
                    cl.title,
                    cl.price_per_unit,
                    cl.quantity_available,
                    cl.commodity_id,
                    c.name as commodity_name,
                    c.unit as commodity_unit,
                    up.display_name as seller_name
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.id
                JOIN commodity_listings cl ON ci.listing_id = cl.id
                LEFT JOIN commodities com ON cl.commodity_id = com.id
                LEFT JOIN user_profiles up ON cl.seller_id = up.user_id
                WHERE c.buyer_id = ? AND c.status = 'active'
                ORDER BY ci.created_at DESC
            ");
            $stmt->execute([$user['id']]);
            $cartItems = $stmt->fetchAll();

            // Calculate totals
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['quantity'] * $item['price_per_unit_at_add'];
            }

            echo json_encode([
                'success' => true,
                'cart_items' => $cartItems,
                'cart_count' => count($cartItems),
                'cart_total' => $total
            ]);
            ob_end_clean();

        } catch (Exception $e) {
            error_log('Error fetching cart: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch cart']);
            ob_end_clean();
        }
    }

    public function removeItem(Request $request): void
    {
        // Start output buffering to catch any HTML output
        ob_start();
        
        // Set error handler to prevent HTML error output
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            error_log("CartController Error: [$errno] $errstr in $errfile on line $errline");
            return true;
        });

        header('Content-Type: application/json');

        try {
            // Authentication check now handled by 'auth' middleware

            $user = Auth::user();
            $cartItemId = (int)$request->input('cart_item_id', 0);

            if ($cartItemId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid cart item ID']);
                ob_end_clean();
                return;
            }

            $pdo = Database::pdo();

            // Verify the cart item belongs to the user
            $stmt = $pdo->prepare("
                SELECT ci.id
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.id
                WHERE ci.id = ? AND c.buyer_id = ? AND c.status = 'active'
            ");
            $stmt->execute([$cartItemId, $user['id']]);
            $cartItem = $stmt->fetch();

            if (!$cartItem) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Cart item not found']);
                ob_end_clean();
                return;
            }

            // Delete the cart item
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ?");
            $stmt->execute([$cartItemId]);

            // Get updated cart count
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count, SUM(ci.quantity * ci.price_per_unit_at_add) as total
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.id
                WHERE c.buyer_id = ? AND c.status = 'active'
            ");
            $stmt->execute([$user['id']]);
            $cartSummary = $stmt->fetch();

            echo json_encode([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cartSummary['count'],
                'cart_total' => $cartSummary['total']
            ]);
            ob_end_clean();

        } catch (Exception $e) {
            error_log('Error removing item from cart: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to remove item from cart']);
            ob_end_clean();
        }
    }

    public function updateQuantity(Request $request): void
    {
        // Start output buffering to catch any HTML output
        ob_start();
        
        // Set error handler to prevent HTML error output
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            error_log("CartController Error: [$errno] $errstr in $errfile on line $errline");
            return true;
        });

        header('Content-Type: application/json');

        try {
            // Authentication check now handled by 'auth' middleware

            $user = Auth::user();
            $cartItemId = (int)$request->input('cart_item_id', 0);
            $quantity = (float)$request->input('quantity', 1);

            if ($cartItemId <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid cart item ID']);
                ob_end_clean();
                return;
            }

            if ($quantity <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Quantity must be greater than 0']);
                ob_end_clean();
                return;
            }

            $pdo = Database::pdo();

            // Get cart item and listing details
            $stmt = $pdo->prepare("
                SELECT ci.id, ci.quantity, ci.listing_id, cl.quantity_available
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.id
                JOIN commodity_listings cl ON ci.listing_id = cl.id
                WHERE ci.id = ? AND c.buyer_id = ? AND c.status = 'active'
            ");
            $stmt->execute([$cartItemId, $user['id']]);
            $cartItem = $stmt->fetch();

            if (!$cartItem) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Cart item not found']);
                ob_end_clean();
                return;
            }

            // Stock validation
            if ($quantity > $cartItem['quantity_available']) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock',
                    'available_quantity' => $cartItem['quantity_available']
                ]);
                ob_end_clean();
                return;
            }

            // Update quantity
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $stmt->execute([$quantity, $cartItemId]);

            // Get updated cart count
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count, SUM(ci.quantity * ci.price_per_unit_at_add) as total
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.id
                WHERE c.buyer_id = ? AND c.status = 'active'
            ");
            $stmt->execute([$user['id']]);
            $cartSummary = $stmt->fetch();

            echo json_encode([
                'success' => true,
                'message' => 'Quantity updated',
                'cart_count' => $cartSummary['count'],
                'cart_total' => $cartSummary['total']
            ]);
            ob_end_clean();

        } catch (Exception $e) {
            error_log('Error updating cart quantity: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
            ob_end_clean();
        }
    }

    public function checkout(): void
    {
        // Authentication check now handled by 'auth' middleware

        $user = Auth::user();
        $pdo = Database::pdo();

        // Get cart from cookie (user-specific)
        $userId = $user ? $user['id'] : 'guest';
        $cartCookieName = 'ulimi_cart_user_' . $userId;
        $cartCookie = $_COOKIE[$cartCookieName] ?? null;
        $cartItems = $cartCookie ? json_decode($cartCookie, true) : [];

        if (empty($cartItems)) {
            header('Location: /browse');
            exit;
        }

        // Get listing details from database
        $listingIds = array_column($cartItems, 'id');
        $placeholders = implode(',', array_fill(0, count($listingIds), '?'));
        $stmt = $pdo->prepare("
            SELECT
                cl.id as listing_id,
                cl.title,
                cl.price_per_unit,
                cl.quantity_available,
                cl.image_path,
                com.name as commodity_name,
                com.unit as commodity_unit,
                up.display_name as seller_name
            FROM commodity_listings cl
            LEFT JOIN commodities com ON cl.commodity_id = com.id
            LEFT JOIN user_profiles up ON cl.seller_id = up.user_id
            WHERE cl.id IN ($placeholders)
        ");
        $stmt->execute($listingIds);
        $listings = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Map listings by ID
        $listingsMap = [];
        foreach ($listings as $listing) {
            $listingsMap[$listing['listing_id']] = $listing;
        }

        // Merge cart items with listing details
        $cartItemsWithDetails = [];
        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $listing = $listingsMap[$cartItem['id']] ?? null;
            if ($listing) {
                // Extract numeric value from price string (e.g., "MWK 123.45" -> 123.45)
                $priceNumeric = is_numeric($cartItem['price']) ? (float)$cartItem['price'] : (float)preg_replace('/[^0-9.]/', '', $cartItem['price']);

                $cartItemsWithDetails[] = [
                    'listing_id' => $listing['listing_id'],
                    'title' => $listing['title'],
                    'price_per_unit' => $listing['price_per_unit'],
                    'quantity' => $cartItem['quantity'],
                    'price_per_unit_at_add' => $priceNumeric,
                    'commodity_name' => $listing['commodity_name'],
                    'commodity_unit' => $listing['commodity_unit'],
                    'seller_name' => $listing['seller_name'],
                    'image_path' => $listing['image_path']
                ];
                $subtotal += $cartItem['quantity'] * $priceNumeric;
            }
        }

        // Calculate fees
        $platformFee = $subtotal * 0.05; // 5% platform fee
        $deliveryFee = 0; // TODO: Calculate based on location/weight
        $tax = 0.175; // 17.5% tax
        $total = $subtotal + $platformFee + $deliveryFee + $tax;

        if (empty($cartItemsWithDetails)) {
            header('Location: /browse');
            exit;
        }

        View::render('cart.checkout', [
            'title' => 'Checkout - Ulimi Marketplace',
            'cartItems' => $cartItemsWithDetails,
            'subtotal' => $subtotal,
            'platformFee' => $platformFee,
            'deliveryFee' => $deliveryFee,
            'tax' => $tax,
            'total' => $total,
            'user' => $user
        ]);
    }
}
