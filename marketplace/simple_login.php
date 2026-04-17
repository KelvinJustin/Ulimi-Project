<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Load users from file storage
    $usersFile = __DIR__ . '/../storage/users.php';
    if (file_exists($usersFile)) {
        $users = include $usersFile;
    } else {
        $users = [];
    }
    
    // Find user by email
    $user = null;
    foreach ($users as $u) {
        if ($u['email'] === $email && $u['role'] === 'seller') {
            $user = $u;
            break;
        }
    }
    
    // Validate password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['seller_id'] = $user['id'];
        $_SESSION['seller_name'] = $user['display_name'];
        $_SESSION['seller_email'] = $user['email'];
        header('Location: create_listing.php');
        exit;
    }
    
    $error = 'Invalid credentials';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seller Login - Marketplace</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ccc; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Seller Login</h1>
    
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
