<?php
// API endpoint for seller's products
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Check if user is logged in and is a seller
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Load database configuration
require_once __DIR__ . '/../marketplace/config/db.php';

// Get current user from database
$stmt = $pdo->prepare("SELECT u.*, up.display_name FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id WHERE u.id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch();

// Role ID mapping: 1 = seller, 2 = buyer, 3 = admin
if (!$currentUser || $currentUser['role_id'] !== 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied - Seller role required']);
    exit;
}

try {
    // Load products
    $productsFile = __DIR__ . '/../storage/products.php';
    if (file_exists($productsFile)) {
        $products = include $productsFile;
    } else {
        $products = [];
    }

    // Filter products for current seller
    $sellerProducts = [];
    foreach ($products as $product) {
        if ($product['seller_id'] == $currentUser['id']) {
            $sellerProducts[] = [
                'id' => $product['id'],
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => (float)$product['price'],
                'price_unit' => $product['price_unit'] ?? 'kg',
                'quantity' => (int)$product['quantity'],
                'category' => $product['category'],
                'location' => $product['location'],
                'image' => $product['image'],
                'status' => $product['status'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at']
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'products' => $sellerProducts,
        'count' => count($sellerProducts),
        'seller' => [
            'id' => $currentUser['id'],
            'name' => ucfirst($currentUser['display_name'] ?? $currentUser['email'])
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
