<!DOCTYPE html>
<html>
<head>
    <title>Create Listing - Marketplace</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; }
        textarea { height: 100px; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Create New Listing</h1>
    
    <?php
    session_start();
    if (!isset($_SESSION['seller_id'])) {
        echo '<p><a href="../login.php">Please login first</a></p>';
        exit;
    }
    
    $errors = $_GET['errors'] ?? [];
    $old = $_GET['old'] ?? [];
    
    if (!empty($errors)) {
        echo '<div class="error">' . htmlspecialchars($errors['general'] ?? '') . '</div>';
    }
    ?>
    
    <form method="POST" action="process_listing.php" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Price (MWK)</label>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?= htmlspecialchars($old['quantity'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="grains" <?= ($old['category'] ?? '') === 'grains' ? 'selected' : '' ?>>Grains</option>
                <option value="vegetables" <?= ($old['category'] ?? '') === 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                <option value="fruits" <?= ($old['category'] ?? '') === 'fruits' ? 'selected' : '' ?>>Fruits</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="product_image" accept="image/*">
        </div>
        
        <button type="submit">Create Listing</button>
    </form>
    
    <p><a href="../dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
