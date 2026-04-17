<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="stylesheet" href="/assets/css/browse.css">
  <link rel="icon" type="image/png" href="/logo.png">
  <style>
    .product-images-link {
      display: block;
      text-decoration: none;
    }
    .product-title-link {
      text-decoration: none;
      color: inherit;
      transition: color 0.2s ease;
    }
    .product-title-link:hover {
      color: #3d6b3f;
    }
  </style>
  <link rel="alternate icon" href="/favicon.ico">
  
  <!-- Meta tags for SEO -->
  <meta name="description" content="Browse agricultural products in Malawi - maize, ground nuts, soya, pigeon peas and more. Connect with local farmers and traders.">
  <meta name="keywords" content="agriculture, malawi, maize, ground nuts, soya, farming, marketplace">
  
  <!-- Open Graph meta tags -->
  <meta property="og:title" content="Ulimi - Agricultural Marketplace Malawi">
  <meta property="og:description" content="Buy and sell agricultural products in Malawi">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '') ?>/assets/images/ulimi-og.jpg">
</head>
<body>
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  $isLoggedIn = \App\Core\Auth::check();
  $user = \App\Core\Auth::user();
  $userId = $isLoggedIn && $user ? $user['id'] : 'guest';

  // Helper functions
  function getProductBadge($listing) {
    $badges = [];
    $createdDate = new \DateTime($listing['created_at']);
    $now = new \DateTime();
    $daysSinceCreated = $createdDate->diff($now)->days;
    
    if ($daysSinceCreated <= 7) {
      $badges[] = '<span class="product-badge new">New</span>';
    }
    return implode('', $badges);
  }

  function generateStars($rating) {
    $fullStars = floor($rating);
    $hasHalfStar = $rating % 1 >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
    $stars = '';
    
    for ($i = 0; $i < $fullStars; $i++) {
      $stars .= '<i class="fas fa-star"></i>';
    }
    
    if ($hasHalfStar) {
      $stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    
    for ($i = 0; $i < $emptyStars; $i++) {
      $stars .= '<i class="far fa-star"></i>';
    }

    return $stars;
  }

  function formatCategory($category) {
    $categories = [
      'Cereals' => 'Grains & Cereals',
      'Legumes' => 'Legumes & Pulses',
      'Vegetables' => 'Vegetables',
      'Fruits' => 'Fruits',
      'Cash Crops' => 'Cash Crops',
      'Livestock' => 'Livestock',
      'Inputs' => 'Farm Inputs'
    ];
    return $categories[$category] ?? $category;
  }

  function formatLocation($listing) {
    $parts = [];
    if (!empty($listing['district'])) {
      $parts[] = htmlspecialchars($listing['district']);
    }
    if (!empty($listing['region'])) {
      $parts[] = htmlspecialchars($listing['region']);
    }
    if (!empty($listing['location_text'])) {
      $parts[] = htmlspecialchars($listing['location_text']);
    }
    return implode(', ', $parts) ?: 'Location not specified';
  }
  ?>
  
  <!-- Main Browse Content -->
  <main class="browse-main">
    <!-- Simple Search Section -->
    <section class="simple-search-section">
      <div class="container">
        <div class="search-header">
          <h1 class="page-title">Browse Products</h1>
          <p class="page-subtitle">Find quality agricultural products from local farmers</p>
        </div>
        
        <!-- Search Bar -->
        <div class="simple-search-container">
          <div class="simple-search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search for maize, ground nuts, soya, pigeon peas..." autocomplete="off">
            <button type="button" id="clearSearch" class="clear-btn" aria-label="Clear search">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <!-- Basic Filters -->
        <div class="basic-filters-container">
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-tag"></i>
              Category
            </label>
            <select id="categoryFilter" class="filter-select">
              <option value="all">All Categories</option>
              <option value="grains">Grains & Cereals</option>
              <option value="legumes">Legumes & Pulses</option>
              <option value="vegetables">Vegetables</option>
              <option value="fruits">Fruits</option>
              <option value="cash-crops">Cash Crops</option>
              <option value="livestock">Livestock</option>
              <option value="inputs">Farm Inputs</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-map-marker-alt"></i>
              Location
            </label>
            <select id="locationFilter" class="filter-select">
              <option value="all">All Malawi</option>
              <option value="lilongwe">Lilongwe (Central)</option>
              <option value="blantyre">Blantyre (Southern)</option>
              <option value="mzuzu">Mzuzu (Northern)</option>
              <option value="zomba">Zomba</option>
              <option value="kasungu">Kasungu</option>
              <option value="mangochi">Mangochi</option>
              <option value="karonga">Karonga</option>
              <option value="dedza">Dedza</option>
              <option value="salima">Salima</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-sort"></i>
              Sort By
            </label>
            <select id="sortBy" class="filter-select">
              <option value="newest">Newest First</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="distance">Distance: Nearest</option>
              <option value="popular">Most Popular</option>
            </select>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Active Filters Display -->
    <section class="active-filters-section" id="activeFiltersSection" style="display: none;">
      <div class="container">
        <div class="active-filters-header">
          <h3>Active Filters:</h3>
          <button type="button" id="clearAllFilters" class="clear-all-btn">Clear All</button>
        </div>
        <div id="activeFiltersList" class="active-filters-list"></div>
      </div>
    </section>

    <!-- Results Header -->
    <section class="results-header-section">
      <div class="container">
        <div class="results-info">
          <p class="results-count">
            Showing <span id="resultsCount"><?= $count ?? 0 ?></span> products
            <span id="locationContext" class="location-context"></span>
          </p>
          <div class="view-toggle">
            <button type="button" id="gridView" class="view-btn active" aria-label="Grid view">
              <i class="fas fa-th"></i>
            </button>
            <button type="button" id="listView" class="view-btn" aria-label="List view">
              <i class="fas fa-list"></i>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
      <div class="container">
        <div id="productsGrid" class="products-grid">
          <?php if (!empty($listings)): ?>
            <?php foreach ($listings as $listing): ?>
              <?php
              // Debug: Check if listing ID exists
              if (empty($listing['id'])) {
                error_log('Listing has no ID: ' . json_encode($listing));
              }
              ?>
              <div class="product-card" data-product-id="<?= $listing['id'] ?>">
                <a href="<?= $base ?>/browse/<?= $listing['id'] ?>" class="product-images-link">
                  <div class="product-images">
                    <img src="<?= $listing['image_path'] ? '/' . htmlspecialchars($listing['image_path']) : '/assets/images/placeholder-product.jpg' ?>"
                         alt="<?= htmlspecialchars($listing['title']) ?>"
                         class="product-image"
                         onerror="this.src='/assets/images/placeholder-product.jpg'">
                    <?= getProductBadge($listing) ?>
                  </div>
                </a>
                <div class="product-info">
                  <div class="product-category"><?= formatCategory($listing['commodity_category']) ?></div>
                  <h3 class="product-title">
                    <a href="<?= $base ?>/browse/<?= $listing['id'] ?>" class="product-title-link"><?= htmlspecialchars(ucfirst($listing['title'])) ?></a>
                  </h3>
                  <div class="product-rating">
                    <div class="stars">
                      <?= generateStars($listing['rating_avg'] ?? 0) ?>
                    </div>
                    <span class="rating-count">(<?= number_format($listing['rating_count'] ?? 0) ?> reviews)</span>
                  </div>
                  <div class="product-price">
                    MWK <?= number_format($listing['price_per_unit'], 2) ?>
                    <span class="price-unit">/<?= htmlspecialchars($listing['price_unit'] ?? $listing['commodity_unit']) ?></span>
                  </div>
                  <div class="product-meta">
                    <div class="seller-info">
                      <i class="fas fa-user-circle"></i>
                      <span class="seller-name"><?= htmlspecialchars(ucfirst($listing['display_name'] ?? 'Unknown Seller')) ?></span>
                      <?php if ($listing['rating_avg'] > 0): ?>
                        <span class="verified-badge">Verified</span>
                      <?php endif; ?>
                    </div>
                    <div class="location-info">
                      <i class="fas fa-map-marker-alt"></i>
                      <span><?= formatLocation($listing) ?></span>
                    </div>
                  </div>
                  <div class="product-details">
                    <span class="condition">Good Condition</span>
                    <span class="stock-level">In Stock: <?= number_format($listing['quantity_available']) ?> <?= htmlspecialchars($listing['commodity_unit']) ?></span>
                    <span class="shipping">Available Now</span>
                  </div>
                  <div class="product-actions">
                    <button class="btn-add-cart" data-listing-id="<?= isset($listing['id']) ? (int)$listing['id'] : 0 ?>" onclick="addToCart(<?= isset($listing['id']) ? (int)$listing['id'] : 0 ?>, event)">
                      <i class="fas fa-shopping-cart"></i>
                      Add to Cart
                    </button>
                    <button class="btn-favorite" onclick="toggleFavorite(<?= isset($listing['id']) ? (int)$listing['id'] : 0 ?>)">
                      <i class="far fa-heart"></i>
                    </button>
                    <button class="btn-quick-view" onclick="quickView(<?= isset($listing['id']) ? (int)$listing['id'] : 0 ?>)">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="loading-state" style="display: none;">
          <div class="loading-spinner"></div>
          <p>Loading products...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state" style="display: <?= empty($listings) ? 'block' : 'none' ?>;">
          <div class="empty-icon">
            <i class="fas fa-search"></i>
          </div>
          <h3>No products found</h3>
          <p>Try adjusting your filters or search terms</p>
          <button type="button" id="resetFilters" class="btn btn-primary">Reset Filters</button>
        </div>

        <!-- Error State -->
        <div id="errorState" class="error-state" style="display: none;">
          <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <h3>Unable to load products</h3>
          <p>Please check your internet connection and try again</p>
          <button type="button" id="retryLoad" class="btn btn-primary">Try Again</button>
        </div>
      </div>
    </section>

    <!-- Pagination -->
    <section class="pagination-section" id="paginationSection" style="display: none;">
      <div class="container">
        <div class="pagination-controls">
          <button type="button" id="prevPage" class="pagination-btn" disabled>
            <i class="fas fa-chevron-left"></i>
            Previous
          </button>
          
          <div id="pageNumbers" class="page-numbers">
            <!-- Page numbers will be dynamically generated -->
          </div>
          
          <button type="button" id="nextPage" class="pagination-btn">
            Next
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        
        <div class="pagination-info">
          <span id="paginationInfo">Page 1 of 1</span>
        </div>
      </div>
    </section>

    <!-- Load More Button (for infinite scroll) -->
    <section class="load-more-section" id="loadMoreSection" style="display: none;">
      <div class="container">
        <button type="button" id="loadMoreBtn" class="load-more-btn">
          <i class="fas fa-plus"></i>
          Load More Products
        </button>
      </div>
    </section>
  </main>

  <!-- Shopping Cart Sidebar -->
  <div id="cartSidebar" class="cart-sidebar">
    <div class="cart-header">
      <h3>Shopping Cart</h3>
      <button type="button" id="closeCart" class="close-cart-btn">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="cart-content">
      <div id="cartItems" class="cart-items">
        <!-- Cart items will be dynamically added -->
      </div>
      
      <div id="cartEmpty" class="cart-empty">
        <i class="fas fa-shopping-cart"></i>
        <p>Your cart is empty</p>
      </div>
      
      <div id="cartSummary" class="cart-summary" style="display: none;">
        <div class="cart-total">
          <span>Total:</span>
          <span id="cartTotal">MWK 0</span>
        </div>
        <form action="<?= $base ?>/checkout" method="get" style="display: inline;">
          <button type="submit" class="btn btn-primary btn-full-width">
            Proceed to Checkout
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Cart Overlay -->
  <div id="cartOverlay" class="cart-overlay"></div>

  <!-- Favorites Modal -->
  <div id="favoritesModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>My Favorites</h3>
        <button type="button" id="closeFavorites" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="modal-body">
        <div id="favoritesList" class="favorites-list">
          <!-- Favorites will be dynamically loaded -->
        </div>
        
        <div id="favoritesEmpty" class="favorites-empty">
          <i class="fas fa-heart"></i>
          <p>No favorites yet</p>
          <small>Click the heart icon on products to save them</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Detail Modal -->
  <div id="productModal" class="modal">
    <div class="modal-content modal-large">
      <div class="modal-header">
        <h3 id="modalProductTitle">Product Details</h3>
        <button type="button" id="closeProductModal" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="modal-body">
        <div id="productDetailContent">
          <!-- Product details will be dynamically loaded -->
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Overlay -->
  <div id="modalOverlay" class="modal-overlay"></div>

  <!-- Floating Action Buttons -->
  <div class="floating-actions">
    <button type="button" id="cartToggle" class="fab-btn cart-btn" aria-label="Toggle cart">
      <i class="fas fa-shopping-cart"></i>
      <span id="cartCount" class="cart-count">0</span>
    </button>
    
    <button type="button" id="favoritesToggle" class="fab-btn favorites-btn" aria-label="Toggle favorites">
      <i class="fas fa-heart"></i>
      <span id="favoritesCount" class="favorites-count">0</span>
    </button>
    
    <button type="button" id="scrollToTop" class="fab-btn scroll-btn" aria-label="Scroll to top">
      <i class="fas fa-arrow-up"></i>
    </button>
  </div>

  <style>
    /* Favorites counter badge */
    .fab-btn {
      position: relative;
    }

    .favorites-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #e74c3c;
      color: white;
      font-size: 11px;
      font-weight: 700;
      min-width: 18px;
      height: 18px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 5px;
      border: 2px solid white;
    }

    .favorites-count-updated {
      animation: pulse 0.3s ease-out;
    }

    /* Amazon-style Cart Sidebar */
    .cart-sidebar {
      position: fixed;
      top: 0;
      right: -400px;
      width: 400px;
      max-width: 90vw;
      height: 100vh;
      background: white;
      box-shadow: -4px 0 20px rgba(0, 0, 0, 0.2);
      z-index: 2000;
      transition: right 0.3s ease-in-out;
      display: flex;
      flex-direction: column;
    }

    .cart-sidebar.active {
      right: 0;
    }

    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #e0e0e0;
      background: #f8f8f8;
    }

    .cart-header h3 {
      margin: 0;
      font-size: 18px;
      font-weight: 600;
      color: #333;
    }

    .close-cart-btn {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #666;
      padding: 8px;
      transition: color 0.2s;
    }

    .close-cart-btn:hover {
      color: #333;
    }

    .cart-content {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
    }

    .cart-items {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .cart-item {
      display: flex;
      gap: 12px;
      padding: 12px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background: white;
    }

    .cart-item-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
      background: #f5f5f5;
    }

    .cart-item-details {
      flex: 1;
    }

    .cart-item-title {
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 4px;
      color: #333;
    }

    .cart-item-price {
      font-size: 16px;
      font-weight: 700;
      color: #b12704;
      margin-bottom: 4px;
    }

    .cart-item-quantity {
      font-size: 13px;
      color: #666;
    }

    .quantity-controls {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .qty-btn {
      width: 28px;
      height: 28px;
      border: 1px solid #e0e0e0;
      background: #f5f5f5;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      font-size: 12px;
    }

    .qty-btn:hover {
      background: #e0e0e0;
      border-color: #d0d0d0;
    }

    .qty-value {
      min-width: 24px;
      text-align: center;
      font-weight: 600;
      font-size: 14px;
    }

    .btn-remove-item {
      margin-top: 8px;
      padding: 6px 12px;
      background: #f5f5f5;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
      color: #666;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .btn-remove-item:hover {
      background: #fee;
      border-color: #fcc;
      color: #c00;
    }

    .btn-remove-item i {
      font-size: 11px;
    }

    .cart-empty {
      text-align: center;
      padding: 40px 20px;
      color: #666;
    }

    .cart-empty i {
      font-size: 48px;
      margin-bottom: 16px;
      color: #ccc;
    }

    .cart-empty p {
      margin: 0;
      font-size: 16px;
    }

    .cart-summary {
      padding: 20px;
      border-top: 1px solid #e0e0e0;
      background: #f8f8f8;
    }

    .cart-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      font-size: 18px;
      font-weight: 600;
    }

    .cart-total span:first-child {
      color: #333;
    }

    .cart-total span:last-child {
      color: #b12704;
    }

    .cart-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1999;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s, visibility 0.3s;
    }

    .cart-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Browse top bar styles */
    .browse-top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
      padding: 0;
      border-bottom: none;
    }
    
    .browse-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: white;
      font-weight: 600;
      font-size: 1.2rem;
      transition: opacity 0.3s;
    }
    
    .browse-logo:hover {
      opacity: 0.8;
    }
    
    .browse-logo-mark {
      width: 28px;
      height: 28px;
      margin-right: 8px;
      background: var(--leaf, #4a7c4e);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .browse-logo-mark svg {
      width: 16px;
      height: 16px;
      fill: white;
    }
    
    .browse-user-menu {
      position: relative;
    }
    
    .browse-user-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 6px;
      color: white;
      cursor: pointer;
      font-size: 13px;
      transition: all 0.3s;
    }
    
    .browse-user-btn:hover {
      background: rgba(255, 255, 255, 0.25);
      border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .browse-avatar-icon {
      width: 24px;
      height: 24px;
      background: var(--leaf, #4a7c4e);
      border: 2px solid rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 11px;
      color: white;
    }
    
    .browse-user-name {
      font-weight: 500;
      color: white;
    }
    
    .browse-user-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      margin-top: 8px;
      background: white;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      min-width: 160px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 1000;
    }
    
    .browse-user-dropdown.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    
    .browse-user-dropdown a {
      display: block;
      padding: 12px 16px;
      color: #333;
      text-decoration: none;
      font-size: 14px;
      transition: background 0.2s;
    }
    
    .browse-user-dropdown a:hover {
      background: #f5f5f5;
    }
    
    .browse-logout-btn {
      width: 100%;
      padding: 12px 16px;
      background: none;
      border: none;
      color: #333;
      text-align: left;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.2s;
    }
    
    .browse-logout-btn:hover {
      background: #f5f5f5;
    }

    /* Product Cards Styles */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .product-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s, box-shadow 0.3s;
      position: relative;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .product-images {
      position: relative;
      height: 150px;
      overflow: hidden;
    }

    .product-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s;
    }

    .product-card:hover .product-image {
      transform: scale(1.05);
    }

    .product-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .product-badge.new {
      background: #28a745;
      color: white;
    }

    .product-badge.sale {
      background: #dc3545;
      color: white;
    }

    .product-badge.popular {
      background: #ffc107;
      color: #212529;
    }

    .product-info {
      padding: 1rem;
    }

    .product-category {
      font-size: 12px;
      color: var(--leaf, #4a7c4e);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }

    .product-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--earth, #2B2A25);
      margin-bottom: 0.5rem;
      line-height: 1.3;
      min-height: 2.6rem;
    }

    .product-rating {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .stars {
      display: flex;
      gap: 2px;
    }

    .stars i {
      color: #ffc107;
      font-size: 14px;
    }

    .stars .far.fa-star {
      color: #ddd;
    }

    .rating-count {
      font-size: 12px;
      color: #666;
    }

    .product-price {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--leaf, #4a7c4e);
      margin-bottom: 0.5rem;
      display: flex;
      align-items: baseline;
      gap: 0.5rem;
    }

    .price-unit {
      font-size: 12px;
      font-size: 0.9rem;
      font-weight: 400;
      color: #666;
    }

    .original-price {
      font-size: 1rem;
      color: #999;
      text-decoration: line-through;
      font-weight: 400;
    }

    .product-meta {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
      gap: 1rem;
    }

    .seller-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 14px;
      color: #333;
    }

    .seller-name {
      font-weight: 500;
    }

    .verified-badge, .top-seller-badge {
      font-size: 11px;
      padding: 2px 6px;
      border-radius: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .verified-badge {
      background: #e3f2fd;
      color: #1976d2;
    }

    .top-seller-badge {
      background: #fff3e0;
      color: #f57c00;
    }

    .location-info {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      font-size: 12px;
      color: #666;
    }

    .location-info i {
      font-size: 10px;
    }

    .product-details {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }

    .product-details span {
      font-size: 11px;
      padding: 4px 8px;
      border-radius: 12px;
      background: #f8f9fa;
      color: #495057;
      font-weight: 500;
    }

    .condition {
      background: #e8f5e8;
      color: #2e7d32;
    }

    .stock-level {
      background: #e3f2fd;
      color: #1565c0;
    }

    .stock-level.low-stock {
      background: #fff3e0;
      color: #e65100;
    }

    .shipping {
      background: #f3e5f5;
      color: #7b1fa2;
    }

    .product-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      justify-content: flex-end;
    }

    .btn-add-cart {
      flex: 1;
      padding: 0.75rem 1rem;
      background: var(--leaf, #4a7c4e);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-add-cart:hover {
      background: #3a5f3e;
    }

    .btn-favorite, .btn-quick-view {
      width: 40px;
      height: 40px;
      border: 2px solid #e0e0e0;
      background: white;
      border-radius: 8px;
      color: #666;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-favorite:hover, .btn-quick-view:hover {
      border-color: var(--leaf, #4a7c4e);
      color: var(--leaf, #4a7c4e);
    }

    .btn-favorite.active {
      background: var(--leaf, #4a7c4e);
      border-color: var(--leaf, #4a7c4e);
      color: white;
    }

    .btn-favorite.favorited {
      background: #e74c3c;
      border-color: #e74c3c;
      color: white;
    }

    .btn-favorite.favorited:hover {
      background: #c0392b;
      border-color: #c0392b;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
      }

      .product-card {
        border-radius: 12px;
      }

      .product-info {
        padding: 1rem;
      }

      .product-title {
        font-size: 0.9rem;
        min-height: 2.2rem;
      }

      .product-price {
        font-size: 1.1rem;
      }

      .product-meta {
        flex-direction: column;
        gap: 0.75rem;
      }

      .product-actions {
        gap: 0.5rem;
      }

      .btn-add-cart {
        padding: 0.6rem 0.8rem;
        font-size: 13px;
      }

      .btn-favorite, .btn-quick-view {
        width: 36px;
        height: 36px;
      }
    }

    @media (max-width: 480px) {
      .products-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .product-details {
        gap: 0.5rem;
      }

      .product-details span {
        font-size: 10px;
        padding: 3px 6px;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .page-header h1 {
        font-size: 1.875rem;
      }

      .page-header p {
        font-size: 0.95rem;
      }

      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.125rem;
      }

      .product-card {
        border-radius: 11px;
      }

      .product-image {
        height: 120px;
      }

      .product-title {
        font-size: 0.85rem;
      }

      .product-price {
        font-size: 1rem;
      }
    }

    /* Responsive container padding for browse page */
    @media (max-width: 768px) {
      .browse-main .container {
        padding: 0 20px;
      }
    }

    @media (max-width: 480px) {
      .browse-main .container {
        padding: 0 16px;
      }
    }

    @media (max-width: 360px) {
      .browse-main .container {
        padding: 0 12px;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .page-header p {
        font-size: 1rem;
      }

      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.25rem;
      }

      .product-card {
        border-radius: 12px;
      }

      .product-image {
        height: 130px;
      }

      .product-title {
        font-size: 0.9rem;
      }

      .product-price {
        font-size: 1.1rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .page-header h1 {
        font-size: 1.75rem;
      }

      .page-header p {
        font-size: 0.95rem;
      }

      .products-grid {
        gap: 1rem;
      }

      .product-image {
        height: 160px;
      }

      .product-title {
        font-size: 1rem;
      }

      .product-price {
        font-size: 1.1rem;
      }

      .btn-view {
        padding: 8px 16px;
        font-size: 0.85rem;
      }
    }

    /* Filter Labels Styling */
    .basic-filters-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .filter-label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.9);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .filter-label i {
      font-size: 0.85rem;
      color: var(--leaf, #4a7c4e);
    }

    .filter-select {
      padding: 12px 16px;
      border: none;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.95);
      color: #333;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .filter-select:hover {
      background: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    .filter-select:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(74, 124, 78, 0.3);
    }

    .filter-select option {
      padding: 10px;
      color: #333;
    }
  </style>
  
  <script>
    function toggleBrowseUserMenu(event) {
      event.preventDefault();
      event.stopPropagation();
      const dropdown = document.getElementById('browseUserDropdown');
      dropdown.classList.toggle('show');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const dropdown = document.getElementById('browseUserDropdown');
      const userMenu = document.querySelector('.browse-user-menu');
      
      if (userMenu && !userMenu.contains(event.target)) {
        dropdown.classList.remove('show');
      }
    });
  </script>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      initializeBrowsePage();
    });

    function initializeBrowsePage() {
      // Set current filter values
      const filters = <?= json_encode($filters ?? []) ?>;
      if (filters.category && filters.category !== 'all') {
        document.getElementById('categoryFilter').value = filters.category;
      }
      if (filters.location && filters.location !== 'all') {
        document.getElementById('locationFilter').value = filters.location;
      }
      if (filters.sort && filters.sort !== 'newest') {
        document.getElementById('sortBy').value = filters.sort;
      }
      if (filters.q) {
        document.getElementById('searchInput').value = filters.q;
      }

      // Add event listeners for filters
      document.getElementById('categoryFilter')?.addEventListener('change', applyFilters);
      document.getElementById('locationFilter')?.addEventListener('change', applyFilters);
      document.getElementById('sortBy')?.addEventListener('change', applyFilters);
      document.getElementById('clearSearch')?.addEventListener('click', clearSearch);
      document.getElementById('resetFilters')?.addEventListener('click', resetFilters);
      
      // Search input with debounce
      let searchTimeout;
      document.getElementById('searchInput')?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => applyFilters(), 500);
      });

      // Show empty state if no products
      const productsGrid = document.getElementById('productsGrid');
      const emptyState = document.getElementById('emptyState');
      if (productsGrid && productsGrid.children.length === 0 && emptyState) {
        emptyState.style.display = 'block';
      }
    }

    function applyFilters() {
      const category = document.getElementById('categoryFilter')?.value || 'all';
      const location = document.getElementById('locationFilter')?.value || 'all';
      const sort = document.getElementById('sortBy')?.value || 'newest';
      const search = document.getElementById('searchInput')?.value || '';

      const params = new URLSearchParams({
        category,
        location,
        sort,
        q: search
      });

      // Remove empty parameters
      for (const [key, value] of [...params.entries()]) {
        if (!value || value === 'all') {
          params.delete(key);
        }
      }

      // Reload page with new filters
      window.location.href = '/browse?' + params.toString();
    }

    function clearSearch() {
      document.getElementById('searchInput').value = '';
      applyFilters();
    }

    function resetFilters() {
      document.getElementById('categoryFilter').value = 'all';
      document.getElementById('locationFilter').value = 'all';
      document.getElementById('sortBy').value = 'newest';
      document.getElementById('searchInput').value = '';
      window.location.href = '/browse';
    }

    // Store cart items locally
    let cartItems = [];

    // Cookie functions
    function setCookie(name, value, days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      const expires = '; expires=' + date.toUTCString();
      document.cookie = name + '=' + JSON.stringify(value) + expires + '; path=/';
    }

    function getCookie(name) {
      const nameEQ = name + '=';
      const ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) {
          try {
            return JSON.parse(c.substring(nameEQ.length, c.length));
          } catch (e) {
            return null;
          }
        }
      }
      return null;
    }

    function deleteCookie(name) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    function addToCart(productId, event) {
      // Prevent multiple clicks
      const button = event.target.closest('.btn-add-cart');
      if (button.disabled) return;
      
      // Get listing data from the page
      const listingElement = button.closest('.product-card');
      const listingTitle = listingElement.querySelector('.product-title')?.textContent || '';
      const listingPrice = listingElement.querySelector('.product-price')?.textContent || '';
      const listingImage = listingElement.querySelector('.product-image')?.src || '';

      button.disabled = true;
      button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

      fetch('/add-to-cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          listing_id: parseInt(productId),
          quantity: 1
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Add to local cart
          const existingItem = cartItems.find(item => item.id === productId);
          if (existingItem) {
            existingItem.quantity += 1;
          } else {
            cartItems.push({
              id: productId,
              title: listingTitle,
              price: listingPrice,
              image: listingImage,
              quantity: 1
            });
          }

          // Save to cookie
          const userId = window.ulimiUserId || 'guest';
          const cartCookieName = 'ulimi_cart_user_' + userId;
          setCookie(cartCookieName, cartItems, 7);

          // Update cart count
          const cartCount = document.getElementById('cartCount');
          if (cartCount) {
            cartCount.textContent = cartItems.length;
            cartCount.classList.add('cart-count-updated');
            setTimeout(() => cartCount.classList.remove('cart-count-updated'), 300);
          }

          // Render cart items
          renderCartItems();

          // Show success message
          showNotification('Item added to cart successfully!', 'success');
          
          // Reset button
          button.innerHTML = '<i class="fas fa-shopping-cart"></i> Added';
          setTimeout(() => {
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
            button.disabled = false;
          }, 2000);
        } else {
          showNotification(data.message || 'Failed to add item to cart', 'error');
          button.disabled = false;
          button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
        }
      })
      .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification('An error occurred. Please try again.', 'error');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
      });
    }

    function renderCartItems() {
      const cartItemsContainer = document.getElementById('cartItems');
      const cartEmpty = document.getElementById('cartEmpty');
      const cartSummary = document.getElementById('cartSummary');
      const cartTotal = document.getElementById('cartTotal');

      if (!cartItemsContainer) return;

      if (cartItems.length === 0) {
        cartItemsContainer.innerHTML = '';
        cartEmpty.style.display = 'block';
        cartSummary.style.display = 'none';
        return;
      }

      cartEmpty.style.display = 'none';
      cartSummary.style.display = 'block';

      cartItemsContainer.innerHTML = cartItems.map(item => `
        <div class="cart-item">
          <div class="cart-item-image">
            <img src="${item.image || 'https://via.placeholder.com/80x80?text=Product'}" alt="${escapeHtml(item.title)}" class="cart-item-image">
          </div>
          <div class="cart-item-details">
            <div class="cart-item-title">${escapeHtml(item.title)}</div>
            <div class="cart-item-price">${item.price}</div>
            <div class="cart-item-quantity">
              <div class="quantity-controls">
                <button type="button" class="qty-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1}, event)">
                  <i class="fas fa-minus"></i>
                </button>
                <span class="qty-value">${item.quantity}</span>
                <button type="button" class="qty-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1}, event)">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <button type="button" class="btn-remove-item" onclick="removeFromCart(${item.id}, event)">
              <i class="fas fa-trash"></i> Remove
            </button>
          </div>
        </div>
      `).join('');

      // Calculate and update cart total
      let total = 0;
      cartItems.forEach(item => {
        // Extract numeric price from string like "MWK 50,000"
        const priceMatch = item.price.match(/[\d,]+/g);
        if (priceMatch) {
          const priceValue = parseFloat(priceMatch[0].replace(/,/g, ''));
          if (!isNaN(priceValue)) {
            total += priceValue * item.quantity;
          }
        }
      });

      if (cartTotal) {
        cartTotal.textContent = 'MWK ' + total.toLocaleString('en-MW');
      }
    }

    function removeFromCart(productId, event) {
      event.stopPropagation();

      // Remove from local cart
      cartItems = cartItems.filter(item => item.id !== productId);

      // Update cookie
      const userId = window.ulimiUserId || 'guest';
      const cartCookieName = 'ulimi_cart_user_' + userId;
      setCookie(cartCookieName, cartItems, 7);
      
      // Update cart count
      const cartCount = document.getElementById('cartCount');
      if (cartCount) {
        cartCount.textContent = cartItems.length;
      }
      
      // Re-render cart
      renderCartItems();
      
      // Call API to remove from database
      fetch('/remove-from-cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          listing_id: productId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          console.error('Failed to remove from database:', data.message);
        }
      })
      .catch(error => {
        console.error('Error removing from database:', error);
      });
    }

    function updateCartQuantity(productId, newQuantity, event) {
      event.stopPropagation();

      // Validate quantity
      if (newQuantity < 1) {
        return; // Don't allow quantity below 1
      }

      // Update local cart
      const item = cartItems.find(item => item.id === productId);
      if (item) {
        item.quantity = newQuantity;

        // Update cookie
        const userId = window.ulimiUserId || 'guest';
        const cartCookieName = 'ulimi_cart_user_' + userId;
        setCookie(cartCookieName, cartItems, 7);

        // Re-render cart
        renderCartItems();

        // Call API to update database
        fetch('/api/cart/update-quantity', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            cart_item_id: productId,
            quantity: newQuantity
          })
        })
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            console.error('Failed to update quantity in database:', data.message);
            showNotification('Failed to update quantity', 'error');
          }
        })
        .catch(error => {
          console.error('Error updating quantity in database:', error);
        });
      }
    }

    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function number_format(number, decimals = 0) {
      return number.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function showNotification(message, type) {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = `notification notification-${type}`;
      notification.textContent = message;
      
      // Add styles
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        background: ${type === 'success' ? '#4a7c4e' : '#dc3545'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        max-width: 300px;
      `;

      // Add animation keyframes
      if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
          @keyframes slideIn {
            from {
              transform: translateX(100%);
              opacity: 0;
            }
            to {
              transform: translateX(0);
              opacity: 1;
            }
          }
          @keyframes slideOut {
            from {
              transform: translateX(0);
              opacity: 1;
            }
            to {
              transform: translateX(100%);
              opacity: 0;
            }
          }
          .cart-count-updated {
            animation: pulse 0.3s ease-out;
          }
          @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
          }
        `;
        document.head.appendChild(style);
      }

      document.body.appendChild(notification);

      // Remove after 3 seconds
      setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
      }, 3000);
    }

    // Favorite functionality
    let userFavorites = new Set(<?= json_encode($userFavorites ?? []) ?>);
    const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;

    function toggleFavorite(productId) {
      if (!isLoggedIn) {
        showNotification('Please log in to add items to your favorites', 'error');
        return;
      }

      const isFavorited = userFavorites.has(productId);
      const endpoint = isFavorited ? '/api/favorites/remove' : '/api/favorites/add';
      const button = document.querySelector(`[data-product-id="${productId}"] .btn-favorite`);

      // Show loading state
      if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
      }

      fetch(endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ listing_id: productId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if (isFavorited) {
            userFavorites.delete(productId);
            if (button) {
              button.innerHTML = '<i class="far fa-heart"></i>';
              button.classList.remove('favorited');
            }
            showNotification('Removed from favorites', 'success');
          } else {
            userFavorites.add(productId);
            if (button) {
              button.innerHTML = '<i class="fas fa-heart"></i>';
              button.classList.add('favorited');
            }
            showNotification('Added to favorites', 'success');
          }
          // Update favorites counter
          updateFavoritesCounter();
        } else {
          showNotification(data.message || 'Failed to update favorites', 'error');
        }
      })
      .catch(error => {
        console.error('Error toggling favorite:', error);
        showNotification('Failed to update favorites. Please try again.', 'error');
      })
      .finally(() => {
        if (button) {
          button.disabled = false;
        }
      });
    }

    function updateFavoritesCounter() {
      const favoritesCount = document.getElementById('favoritesCount');
      if (favoritesCount) {
        favoritesCount.textContent = userFavorites.size;
        favoritesCount.classList.add('favorites-count-updated');
        setTimeout(() => favoritesCount.classList.remove('favorites-count-updated'), 300);
      }
    }

    // Initialize favorite buttons on page load
    function initializeFavoriteButtons() {
      if (isLoggedIn) {
        userFavorites.forEach(listingId => {
          const button = document.querySelector(`[data-product-id="${listingId}"] .btn-favorite`);
          if (button) {
            button.innerHTML = '<i class="fas fa-heart"></i>';
            button.classList.add('favorited');
          }
        });
        // Initialize favorites counter
        updateFavoritesCounter();
      }
    }

    // Cart sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
      const cartToggle = document.getElementById('cartToggle');
      const closeCart = document.getElementById('closeCart');
      const cartSidebar = document.getElementById('cartSidebar');
      const cartOverlay = document.getElementById('cartOverlay');
      const favoritesToggle = document.getElementById('favoritesToggle');

      // Load cart from cookie on page load
      loadCartFromCookie();

      // Initialize favorite buttons
      initializeFavoriteButtons();

      // Favorites toggle - navigate to favorites page
      if (favoritesToggle) {
        favoritesToggle.addEventListener('click', function() {
          window.location.href = '/favorites';
        });
      }

      if (cartToggle && cartSidebar) {
        cartToggle.addEventListener('click', function() {
          cartSidebar.classList.add('active');
          if (cartOverlay) {
            cartOverlay.classList.add('active');
          }
          // Render cart items from local storage
          renderCartItems();
        });
      }

      if (closeCart && cartSidebar) {
        closeCart.addEventListener('click', function() {
          cartSidebar.classList.remove('active');
          if (cartOverlay) {
            cartOverlay.classList.remove('active');
          }
        });
      }

      if (cartOverlay && cartSidebar) {
        cartOverlay.addEventListener('click', function() {
          cartSidebar.classList.remove('active');
          cartOverlay.classList.remove('active');
        });
      }
    });

    function loadCartFromCookie() {
      const userId = window.ulimiUserId || 'guest';
      const cartCookieName = 'ulimi_cart_user_' + userId;
      const savedCart = getCookie(cartCookieName);
      if (savedCart && Array.isArray(savedCart) && savedCart.length > 0) {
        // Verify listings against database
        verifyListings(savedCart).then(validItems => {
          cartItems = validItems;
          setCookie(cartCookieName, cartItems, 7);

          // Update cart count
          const cartCount = document.getElementById('cartCount');
          if (cartCount) {
            cartCount.textContent = cartItems.length;
          }
        });
      }
    }

    async function verifyListings(cartItems) {
      try {
        // Get all listing IDs from cookie
        const listingIds = cartItems.map(item => item.id);
        
        // Verify against database
        const response = await fetch('/verify-listings.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ listing_ids: listingIds })
        });
        
        const data = await response.json();
        
        if (data.success && data.valid_ids) {
          // Filter cart items to only include valid listings
          return cartItems.filter(item => data.valid_ids.includes(item.id));
        }
        
        // If verification fails, return all items (fallback)
        return cartItems;
      } catch (error) {
        console.error('Error verifying listings:', error);
        // On error, return all items (fallback)
        return cartItems;
      }
    }

    function quickView(productId) {
      console.log('Quick view:', productId);
      // TODO: Implement quick view modal
    }
  </script>
  <script>
    // Pass user ID to JavaScript for user-specific cart cookie
    window.ulimiUserId = '<?= $userId ?>';
  </script>
</body>
</html>
