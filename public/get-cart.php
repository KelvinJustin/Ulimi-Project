<?php
// Simple standalone get cart endpoint
header('Content-Type: application/json');

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/debug.log');
error_reporting(E_ALL);

// Start output buffering
ob_start();

try {
    // Simple session check
    session_start();
    if (!isset($_SESSION['user_id'])) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Please log in to view cart']);
        exit;
    }
    
    $userId = $_SESSION['user_id'];
    
    // Simple database connection
    require_once __DIR__ . '/../app/Core/Database.php';
    $pdo = \App\Core\Database::pdo();
    
    // Get cart items with listing details
    $stmt = $pdo->prepare("
        SELECT 
            ci.id as cart_item_id,
            ci.quantity,
            ci.price_per_unit_at_add,
            cl.id as listing_id,
            cl.title,
            cl.price_per_unit,
            cl.quantity_available,
            com.name as commodity_name,
            com.unit as commodity_unit,
            up.display_name as seller_name
        FROM cart_items ci
        JOIN carts c ON ci.cart_id = c.id
        JOIN commodity_listings cl ON ci.listing_id = cl.id
        LEFT JOIN commodities com ON cl.commodity_id = com.id
        LEFT JOIN user_profiles up ON cl.seller_id = up.user_id
        WHERE c.buyer_id = ? AND c.status = 'active'
        ORDER BY ci.created_at DESC
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();
    
    // Calculate totals
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['quantity'] * $item['price_per_unit_at_add'];
    }
    
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'cart_items' => $cartItems,
        'cart_count' => count($cartItems),
        'cart_total' => $total
    ]);
    
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Failed to fetch cart']);
}
