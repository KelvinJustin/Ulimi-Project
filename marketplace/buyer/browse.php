<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - Browse Products</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; width: 100%; max-width: 100%; }
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; width: 100%; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .product-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; overflow: hidden; }
        .product-card h3 { margin: 0 0 10px 0; color: #333; word-wrap: break-word; }
        .product-card p { margin: 5px 0; color: #666; }
     div class="container">
        <   .product-card .price { font-size: 18px; font-weight: bold; color: #007cba; margin: 10px 0; }
            .product-card img { max-width: 100%; height: auto; }
            .no-products { text-align: center; padding: 40px; color: #666; }
            @media (max-width: 768px) {</div>
        
            body { padding: 15px; }
            .container { padding: 0 15px; }
            .products-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 15px; }
        }
        @media (max-width: 480px) {
            body { padding: 10px; }
            .container { padding: 0 10px; }
            .products-grid { grid-template-columns: 1fr; gap: 15px; }
        }
    </style>
</head>
<body>
    <h1>Marketplace Products</h1>
    
    <div id="products-container">
        <div class="loading">Loading products...</div>
    </div>
    
    <script>
        // Fetch products from API
        fetch('api/fetch_products.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('products-container');
                
                if (data.success && data.products.length > 0) {
                    let html = '';
                    data.products.forEach(product => {
                        html += `
                            <div class="product-card">
                                <h3>${product.title}</h3>
                                <p>${product.description || 'No description available'}</p>
                                <div class="price">MWK ${product.price}</div>
                                <p>Quantity: ${product.quantity}</p>
                                <p>Category: ${product.category}</p>
                                ${product.image_path ? `<img src="../${product.image_path}" width="100%" height="200" style="object-fit: cover;">` : ''}
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="no-products">No products available</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                document.getElementById('products-container').innerHTML = '<div class="no-products">Error loading products</div>';
            });
    </script>
</body>
</html>
