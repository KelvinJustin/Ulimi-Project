<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; }
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; padding: 0 20px; width: 100%; }
        .nav { background: #f8f9fa; padding: 15px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
        .nav a { margin: 0 15px; text-decoration: none; color: #007cba; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #007cba; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        @media (max-width: 768px) {
            body { padding: 15px; }
            .container { padding: 0 15px; }
            .nav a { margin: 0 10px; font-size: 14px; }
        }
        @media (max-width: 480px) {
            body { padding: 10px; }
            .container { padding: 0 10px; }
            .nav { padding: 10px; }
            .nav a { display: block; margin: 10px 0; }
            .btn { display: block; margin: 10px 0; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="seller/simple_login.php">Seller Login</a>
            <a href="buyer/browse.php">Browse Products</a>
        </div>
        
        <div class="card">
            <h1>Welcome to Marketplace</h1>
            <p>A simple marketplace for buying and selling products.</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="seller/simple_login.php" class="btn btn-primary">Seller Login</a>
                <a href="buyer/browse.php" class="btn btn-secondary">Browse Products</a>
            </div>
        </div>
    </div>
</body>
</html>
