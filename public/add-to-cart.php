<?php
header('Content-Type: application/json');

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/debug.log');
error_reporting(E_ALL);

ob_start();

try {
    // Load bootstrap to get Config and Database
    require_once __DIR__ . '/../app/bootstrap.php';

    $input = json_decode(file_get_contents('php://input'), true);
    $listingId = isset($input['listing_id']) ? (int)$input['listing_id'] : 0;
    $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

    error_log('add-to-cart.php called with listing_id: ' . $listingId . ', quantity: ' . $quantity);

    if ($listingId <= 0) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
        exit;
    }

    // Check if user is logged in
    if (!\App\Core\Auth::check()) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Please log in to add items to cart']);
        exit;
    }

    $user = \App\Core\Auth::user();
    $pdo = \App\Core\Database::pdo();

    // Create cart tables if they don't exist
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS carts (
              id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              buyer_id BIGINT UNSIGNED NOT NULL,
              status ENUM('active','checked_out') NOT NULL DEFAULT 'active',
              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
              FOREIGN KEY (buyer_id) REFERENCES users(id),
              INDEX idx_cart_buyer_status (buyer_id, status)
            ) ENGINE=InnoDB
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS cart_items (
              id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              cart_id BIGINT UNSIGNED NOT NULL,
              listing_id BIGINT UNSIGNED NOT NULL,
              quantity DECIMAL(12,2) NOT NULL,
              price_per_unit_at_add DECIMAL(12,2) NOT NULL,
              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
              FOREIGN KEY (listing_id) REFERENCES commodity_listings(id),
              UNIQUE KEY uniq_cart_listing (cart_id, listing_id)
            ) ENGINE=InnoDB
        ");
    } catch (\PDOException $e) {
        error_log('Error creating cart tables: ' . $e->getMessage());
    }

    // Get listing details
    $stmt = $pdo->prepare("SELECT id, title, price_per_unit, quantity_available FROM commodity_listings WHERE id = ? AND status = 'active'");
    $stmt->execute([$listingId]);
    $listing = $stmt->fetch();

    if (!$listing) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Listing not found or inactive']);
        exit;
    }

    if ($listing['quantity_available'] < $quantity) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Insufficient quantity available']);
        exit;
    }

    // Get or create user's active cart
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE buyer_id = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$user['id']]);
    $cart = $stmt->fetch();

    if (!$cart) {
        $stmt = $pdo->prepare("INSERT INTO carts (buyer_id, status) VALUES (?, 'active')");
        $stmt->execute([$user['id']]);
        $cartId = $pdo->lastInsertId();
    } else {
        $cartId = $cart['id'];
    }

    // Check if item already exists in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND listing_id = ?");
    $stmt->execute([$cartId, $listingId]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        $newQuantity = $existingItem['quantity'] + $quantity;
        if ($listing['quantity_available'] < $newQuantity) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Insufficient quantity available']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, listing_id, quantity, price_per_unit_at_add) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cartId, $listingId, $quantity, $listing['price_per_unit']]);
    }

    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'Item added to cart successfully']);

} catch (Exception $e) {
    error_log('Error in add-to-cart.php: ' . $e->getMessage());
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
}
