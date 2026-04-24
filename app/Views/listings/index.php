<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Current Listings - Ulimi Agricultural Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">
  <link rel="icon" type="image/png" href="<?= $base ?>/logo.png">
  
  <style>
    .listings-page {
      min-height: 100vh;
      background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e8 100%);
      padding: 2rem 0;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
    }
    
    .page-header {
      text-align: center;
      margin-bottom: 3rem;
    }
    
    .page-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      color: #3d6b3f;
      margin-bottom: 1rem;
    }
    
    .page-subtitle {
      font-size: 1.2rem;
      color: #666;
    }
    
    .listings-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }
    
    .listing-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .listing-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .listing-image {
      width: 100%;
      height: 200px;
      background: linear-gradient(45deg, #3d6b3f, #c8a84b);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 3rem;
      position: relative;
    }
    
    .listing-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: #28a745;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 600;
    }
    
    .listing-content {
      padding: 1.5rem;
    }
    
    .listing-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 0.5rem;
    }
    
    .listing-description {
      color: #666;
      margin-bottom: 1rem;
      line-height: 1.5;
    }
    
    .listing-details {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
      border-bottom: 1px solid #eee;
    }
    
    .detail-row:last-child {
      border-bottom: none;
    }
    
    .detail-label {
      font-weight: 500;
      color: #555;
    }
    
    .detail-value {
      font-weight: 600;
      color: #3d6b3f;
    }
    
    .listing-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 1rem;
      border-top: 1px solid #eee;
    }
    
    .listing-price {
      font-size: 1.25rem;
      font-weight: 700;
      color: #3d6b3f;
    }
    
    .listing-actions {
      display: flex;
      gap: 0.5rem;
    }
    
    .btn-small {
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-primary {
      background: #3d6b3f;
      color: white;
    }
    
    .btn-primary:hover {
      background: #2d5a2f;
    }
    
    .btn-secondary {
      background: #6c757d;
      color: white;
    }
    
    .btn-secondary:hover {
      background: #5a6268;
    }
    
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .empty-icon {
      font-size: 4rem;
      color: #ccc;
      margin-bottom: 1rem;
    }
    
    .empty-title {
      font-size: 1.5rem;
      color: #666;
      margin-bottom: 0.5rem;
    }
    
    .empty-message {
      color: #999;
      margin-bottom: 2rem;
    }
    
    .stats-bar {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .stat-item {
      text-align: center;
    }
    
    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #3d6b3f;
    }
    
    .stat-label {
      color: #666;
      font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
      .listings-grid {
        grid-template-columns: 1fr;
      }
      
      .stats-bar {
        flex-direction: column;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .page-header {
        padding: 1.25rem 0;
      }

      .page-title {
        font-size: 1.875rem;
      }

      .page-subtitle {
        font-size: 0.95rem;
      }

      .listings-grid {
        gap: 1.125rem;
      }

      .listing-card {
        border-radius: 11px;
      }

      .listing-image {
        height: 170px;
      }

      .listing-title {
        font-size: 1.05rem;
      }

      .listing-price {
        font-size: 1.15rem;
      }

      .btn-small {
        padding: 5px 11px;
        font-size: 0.825rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .page-header {
        padding: 1.5rem 0;
      }

      .page-title {
        font-size: 2rem;
      }

      .page-subtitle {
        font-size: 1rem;
      }

      .listings-grid {
        gap: 1.25rem;
      }

      .listing-card {
        border-radius: 12px;
      }

      .listing-image {
        height: 180px;
      }

      .listing-title {
        font-size: 1.1rem;
      }

      .listing-price {
        font-size: 1.2rem;
      }

      .btn-small {
        padding: 6px 12px;
        font-size: 0.85rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .page-header {
        padding: 1rem 0;
      }

      .page-title {
        font-size: 1.75rem;
      }

      .page-subtitle {
        font-size: 0.95rem;
      }

      .listings-grid {
        gap: 1rem;
      }

      .listing-image {
        height: 160px;
      }

      .listing-title {
        font-size: 1rem;
      }

      .listing-price {
        font-size: 1.1rem;
      }

      .listing-actions {
        flex-direction: column;
        gap: 0.5rem;
      }

      .btn-small {
        width: 100%;
        text-align: center;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .page-title {
        font-size: 1.5rem;
      }

      .listing-image {
        height: 140px;
      }

      .listing-title {
        font-size: 0.95rem;
      }

      .listing-price {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="listings-page">
    <div class="container">
      <div class="page-header">
        <h1 class="page-title">Current Listings</h1>
        <p class="page-subtitle">Browse all available agricultural products from our marketplace</p>
      </div>
      
      <?php if (!empty($listings)): ?>
        <div class="stats-bar">
          <div class="stat-item">
            <div class="stat-value"><?= count($listings) ?></div>
            <div class="stat-label">Total Listings</div>
          </div>
          <div class="stat-item">
            <div class="stat-value"><?= count(array_unique(array_column($listings, 'seller_id'))) ?></div>
            <div class="stat-label">Active Sellers</div>
          </div>
          <div class="stat-item">
            <div class="stat-value"><?= count(array_unique(array_column($listings, 'commodity_id'))) ?></div>
            <div class="stat-label">Product Types</div>
          </div>
        </div>
        
        <div class="listings-grid">
          <?php foreach ($listings as $listing): ?>
            <div class="listing-card">
              <div class="listing-image">
                <i class="fa fa-leaf"></i>
                <span class="listing-badge">Active</span>
              </div>
              
              <div class="listing-content">
                <h3 class="listing-title"><?= htmlspecialchars($listing['title'] ?? 'Untitled') ?></h3>
                <p class="listing-description"><?= htmlspecialchars($listing['description'] ?? 'No description available') ?></p>
                
                <div class="listing-details">
                  <div class="detail-row">
                    <span class="detail-label">Category:</span>
                    <span class="detail-value"><?= htmlspecialchars($listing['commodity_name'] ?? 'Unknown') ?></span>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Quantity:</span>
                    <span class="detail-value"><?= number_format($listing['quantity_available'] ?? 0) ?> kg</span>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Min Order:</span>
                    <span class="detail-value"><?= number_format($listing['min_order_quantity'] ?? 0) ?> kg</span>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value"><?= htmlspecialchars($listing['location_text'] ?? 'Not specified') ?></span>
                  </div>
                  <?php if ($listing['quality_grade']): ?>
                  <div class="detail-row">
                    <span class="detail-label">Grade:</span>
                    <span class="detail-value"><?= htmlspecialchars($listing['quality_grade']) ?></span>
                  </div>
                  <?php endif; ?>
                </div>
                
                <div class="listing-footer">
                  <div class="listing-price">
                    MWK <?= number_format($listing['price_per_unit'] ?? 0, 2) ?>/kg
                  </div>
                  <div class="listing-actions">
                    <a href="#" class="btn-small btn-primary">
                      <i class="fa fa-shopping-cart"></i> Order
                    </a>
                    <a href="#" class="btn-small btn-secondary">
                      <i class="fa fa-eye"></i> View
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fa fa-inbox"></i>
          </div>
          <h3 class="empty-title">No Listings Yet</h3>
          <p class="empty-message">Be the first to list your agricultural products on our marketplace!</p>
          <a href="<?= $base ?>/create-listing" class="btn btn-primary">
            <i class="fa fa-plus"></i> Create Your First Listing
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
  
  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
