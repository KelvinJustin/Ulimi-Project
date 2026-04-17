
<?php
require_once __DIR__ . '/../config/db.php';

session_start();

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];

// Validate inputs
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 0);
$category = $_POST['category'] ?? '';

if (empty($title)) {
    $errors['title'] = 'Title is required';
}
if ($price <= 0) {
    $errors['price'] = 'Price must be greater than 0';
}
if ($quantity <= 0) {
    $errors['quantity'] = 'Quantity must be greater than 0';
}

// Handle image upload
$imagePath = null;
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/products';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES['product_image'];
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_', true) . '.' . $extension;
    $targetPath = $uploadDir . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $imagePath = 'uploads/products/' . $filename;
    }
}

// If no errors, save to database
if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO products (
                seller_id, title, description, price, quantity, category, image_path, status, created_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, 'active', NOW()
            )
        ");
        
        $stmt->execute([
            $_SESSION['seller_id'],
            $title,
            $description,
            $price,
            $quantity,
            $category,
            $imagePath
        ]);
        
        header('Location: dashboard.php?success=1');
        exit;
        
    } catch (Exception $e) {
        $errors['general'] = 'Failed to save listing. Please try again.';
    }
}

// If errors, redirect back with error messages
if (!empty($errors)) {
    $query = http_build_query([
        'errors' => $errors,
        'old' => [
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'category' => $category
        ]
    ]);
    header('Location: create_listing.php?' . $query);
    exit;
}
?>
