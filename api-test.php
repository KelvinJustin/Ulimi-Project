<?php
// Simple test to check if API is working
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Load products directly from storage
    $productsFile = __DIR__ . '/../storage/products.php';
    if (file_exists($productsFile)) {
        $products = include $productsFile;
    } else {
        $products = [];
    }
    
    // Get all products
    $allProducts = [];
    foreach ($products as $product) {
        if ($product['status'] === 'active') {
            $allProducts[] = [
                'id' => $product['id'],
                'seller_id' => $product['seller_id'],
                'seller_name' => 'mary', // Your new product
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
        'products' => $allProducts,
        'count' => count($allProducts)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
