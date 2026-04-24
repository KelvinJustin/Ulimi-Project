<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Products - Ulimi Marketplace</title>
  <link rel="icon" type="image/png" href="/logo.png">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .atmospheric-glow {
      box-shadow: 0px 12px 32px rgba(26, 61, 34, 0.06);
    }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
  
  <!-- Meta tags for SEO -->
  <meta name="description" content="Browse agricultural products in Malawi - maize, ground nuts, soya, pigeon peas and more. Connect with local farmers and traders.">
  <meta name="keywords" content="agriculture, malawi, maize, ground nuts, soya, farming, marketplace">
</head>
<body class="bg-surface text-on-surface">
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  $isLoggedIn = \App\Core\Auth::check();
  $user = \App\Core\Auth::user();
  $userId = $isLoggedIn && $user ? $user['id'] : 'guest';

  // Helper functions
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

  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <main class="pt-4 pb-16 px-6 max-w-7xl mx-auto">
    <!-- Search & Filter Section -->
    <header class="mb-12">
      <div class="bg-surface-container-low p-5 rounded-xl atmospheric-glow">
        <div class="flex flex-col lg:flex-row gap-3 items-center">
          <!-- Search Bar -->
          <div class="relative w-full lg:flex-1 group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input class="w-full bg-surface-container-lowest border-none py-3 pl-12 pr-4 rounded-xl focus:ring-2 focus:ring-primary/20 placeholder:text-on-surface-variant/60" placeholder="Search harvests, seeds, or organic tools..." type="text" id="searchInput" value="<?= htmlspecialchars($filters['q'] ?? '') ?>"/>
          </div>
          <!-- Filters Grid -->
          <div class="flex flex-wrap gap-2 w-full lg:w-auto">
            <div class="relative group flex-1 lg:flex-none">
              <select id="categoryFilter" class="bg-surface-container-highest px-4 py-3 rounded-xl text-xs font-semibold hover:bg-surface-container-high transition-colors appearance-none cursor-pointer w-full min-w-[140px]">
                <option value="all">Category</option>
                <option value="grains" <?= ($filters['category'] ?? '') === 'grains' ? 'selected' : '' ?>>Grains</option>
                <option value="legumes" <?= ($filters['category'] ?? '') === 'legumes' ? 'selected' : '' ?>>Legumes</option>
                <option value="vegetables" <?= ($filters['category'] ?? '') === 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                <option value="fruits" <?= ($filters['category'] ?? '') === 'fruits' ? 'selected' : '' ?>>Fruits</option>
                <option value="cash-crops" <?= ($filters['category'] ?? '') === 'cash-crops' ? 'selected' : '' ?>>Cash Crops</option>
                <option value="livestock" <?= ($filters['category'] ?? '') === 'livestock' ? 'selected' : '' ?>>Livestock</option>
                <option value="inputs" <?= ($filters['category'] ?? '') === 'inputs' ? 'selected' : '' ?>>Inputs</option>
              </select>
            </div>
            <div class="relative group flex-1 lg:flex-none">
              <select id="locationFilter" class="bg-surface-container-highest px-4 py-3 rounded-xl text-xs font-semibold hover:bg-surface-container-high transition-colors appearance-none cursor-pointer w-full min-w-[140px]">
                <option value="all">Location</option>
                <option value="lilongwe" <?= ($filters['location'] ?? '') === 'lilongwe' ? 'selected' : '' ?>>Lilongwe</option>
                <option value="blantyre" <?= ($filters['location'] ?? '') === 'blantyre' ? 'selected' : '' ?>>Blantyre</option>
                <option value="mzuzu" <?= ($filters['location'] ?? '') === 'mzuzu' ? 'selected' : '' ?>>Mzuzu</option>
                <option value="zomba" <?= ($filters['location'] ?? '') === 'zomba' ? 'selected' : '' ?>>Zomba</option>
                <option value="kasungu" <?= ($filters['location'] ?? '') === 'kasungu' ? 'selected' : '' ?>>Kasungu</option>
                <option value="mangochi" <?= ($filters['location'] ?? '') === 'mangochi' ? 'selected' : '' ?>>Mangochi</option>
                <option value="karonga" <?= ($filters['location'] ?? '') === 'karonga' ? 'selected' : '' ?>>Karonga</option>
                <option value="dedza" <?= ($filters['location'] ?? '') === 'dedza' ? 'selected' : '' ?>>Dedza</option>
                <option value="salima" <?= ($filters['location'] ?? '') === 'salima' ? 'selected' : '' ?>>Salima</option>
              </select>
            </div>
            <div class="relative group flex-1 lg:flex-none">
              <select id="sortBy" class="bg-surface-container-highest px-4 py-3 rounded-xl text-xs font-semibold hover:bg-surface-container-high transition-colors appearance-none cursor-pointer w-full min-w-[140px]">
                <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest</option>
                <option value="price-low" <?= ($filters['sort'] ?? '') === 'price-low' ? 'selected' : '' ?>>Price: Low</option>
                <option value="price-high" <?= ($filters['sort'] ?? '') === 'price-high' ? 'selected' : '' ?>>Price: High</option>
                <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>Popular</option>
              </select>
            </div>
          </div>
        </div>
        <!-- Chips/Tags -->
        <div class="flex gap-2 mt-6 overflow-x-auto hide-scrollbar">
          <span class="bg-primary text-white px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap cursor-pointer" onclick="resetFilters()">All Harvests</span>
          <span class="bg-surface-container-high text-on-surface-variant px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap hover:bg-primary/10 transition-colors cursor-pointer" onclick="setCategory('vegetables')">Organic Vegetables</span>
          <span class="bg-surface-container-high text-on-surface-variant px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap hover:bg-primary/10 transition-colors cursor-pointer" onclick="setCategory('grains')">Artisan Grains</span>
          <span class="bg-surface-container-high text-on-surface-variant px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap hover:bg-primary/10 transition-colors cursor-pointer" onclick="setCategory('cash-crops')">Cash Crops</span>
          <span class="bg-surface-container-high text-on-surface-variant px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap hover:bg-primary/10 transition-colors cursor-pointer" onclick="setCategory('fruits')">Seasonal Fruits</span>
        </div>
      </div>
    </header>

    <!-- High-Density Listings Grid -->
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
      <?php if (!empty($listings)): ?>
        <?php foreach ($listings as $listing): ?>
          <div class="group cursor-pointer">
            <a href="<?= $base ?>/browse/<?= $listing['id'] ?>" class="block">
              <div class="relative aspect-square mb-4 rounded-xl overflow-hidden bg-surface-container">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     src="<?= $listing['image_path'] ? '/' . htmlspecialchars($listing['image_path']) : '/assets/images/placeholder-product.jpg' ?>"
                     alt="<?= htmlspecialchars($listing['title']) ?>"
                     onerror="this.src='/assets/images/placeholder-product.jpg'">
                <div class="absolute top-3 right-3">
                  <button class="bg-white/80 backdrop-blur-md p-1.5 rounded-lg shadow-atmospheric hover:bg-white transition-colors flex items-center justify-center" onclick="event.preventDefault(); toggleFavorite(<?= $listing['id'] ?>, this)">
                    <span class="material-symbols-outlined text-xl text-primary favorite-icon" style="font-variation-settings: 'FILL' <?= in_array($listing['id'], $userFavorites ?? []) ? '1' : '0' ?>;">
                      <?= in_array($listing['id'], $userFavorites ?? []) ? 'favorite' : 'favorite_border' ?>
                    </span>
                  </button>
                </div>
              </div>
              <div class="space-y-1 px-1">
                <div class="flex items-center gap-1.5 mb-2">
                  <div class="w-5 h-5 rounded-full bg-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[10px] text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">verified</span>
                  </div>
                  <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant"><?= htmlspecialchars(ucfirst($listing['display_name'] ?? 'Seller')) ?></span>
                </div>
                <h3 class="text-sm font-semibold text-on-surface leading-snug line-clamp-2"><?= htmlspecialchars(ucfirst($listing['title'])) ?></h3>
                <div class="flex items-baseline gap-1 mt-1">
                  <span class="text-lg font-extrabold text-primary">MWK <?= number_format($listing['price_per_unit'], 2) ?></span>
                  <span class="text-[10px] text-on-surface-variant font-medium">/ <?= htmlspecialchars($listing['commodity_unit'] ?? 'unit') ?></span>
                </div>
                <div class="flex items-center gap-1 text-[11px] text-on-surface-variant mt-2">
                  <span class="material-symbols-outlined text-xs">location_on</span>
                  <span><?= formatLocation($listing) ?></span>
                </div>
                <button onclick="addToCart(<?= $listing['id'] ?>, event)" class="mt-3 w-full py-2.5 bg-gradient-to-br from-primary to-primary-container text-white rounded-full text-sm font-semibold hover:opacity-90 transition-all flex items-center justify-center gap-2">
                  <span class="material-symbols-outlined text-sm">shopping_cart</span>
                  Add to Cart
                </button>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-full text-center py-12">
          <span class="material-symbols-outlined text-6xl text-on-surface-variant">search_off</span>
          <h3 class="text-xl font-bold text-on-surface mt-4">No products found</h3>
          <p class="text-on-surface-variant mt-2">Try adjusting your filters or search terms</p>
          <button onclick="resetFilters()" class="mt-4 px-6 py-2 bg-primary text-white rounded-full font-semibold hover:bg-primary-container transition-colors">Reset Filters</button>
        </div>
      <?php endif; ?>
    </section>

    <!-- Pagination -->
    <?php if (!empty($listings) && ($pagination['total_pages'] ?? 1) > 1): ?>
      <div class="mt-16 flex justify-center">
        <nav class="flex items-center gap-2 bg-surface-container-low p-2 rounded-full">
          <?php if (($pagination['current_page'] ?? 1) > 1): ?>
            <a href="?<?= http_build_query(array_merge($filters ?? [], ['page' => ($pagination['current_page'] ?? 1) - 1])) ?>" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant">
              <span class="material-symbols-outlined">chevron_left</span>
            </a>
          <?php endif; ?>
          
          <?php for ($i = 1; $i <= ($pagination['total_pages'] ?? 1); $i++): ?>
            <?php if ($i === ($pagination['current_page'] ?? 1)): ?>
              <button class="w-10 h-10 rounded-full flex items-center justify-center bg-primary text-white font-bold"><?= $i ?></button>
            <?php elseif ($i <= 3 || $i > ($pagination['total_pages'] ?? 1) - 2 || abs($i - ($pagination['current_page'] ?? 1)) <= 1): ?>
              <a href="?<?= http_build_query(array_merge($filters ?? [], ['page' => $i])) ?>" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant font-bold"><?= $i ?></a>
            <?php elseif ($i === 4 || $i === ($pagination['total_pages'] ?? 1) - 3): ?>
              <span class="px-2 text-on-surface-variant">...</span>
            <?php endif; ?>
          <?php endfor; ?>
          
          <?php if (($pagination['current_page'] ?? 1) < ($pagination['total_pages'] ?? 1)): ?>
            <a href="?<?= http_build_query(array_merge($filters ?? [], ['page' => ($pagination['current_page'] ?? 1) + 1])) ?>" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant">
              <span class="material-symbols-outlined">chevron_right</span>
            </a>
          <?php endif; ?>
        </nav>
      </div>
    <?php endif; ?>
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
    .floating-actions {
      position: fixed;
      bottom: 24px;
      right: 24px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      z-index: 1000;
    }

    .fab-btn {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      border: none;
      background: white;
      color: #4a7c4e;
      font-size: 20px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .fab-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
      background: #f8f9fa;
    }

    .cart-count, .favorites-count {
      position: absolute;
      top: -4px;
      right: -4px;
      background: #e74c3c;
      color: white;
      font-size: 11px;
      font-weight: 700;
      min-width: 20px;
      height: 20px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 4px;
      border: 2px solid white;
    }

    .cart-count-updated, .favorites-count-updated {
      animation: pulse 0.3s ease-out;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.3); }
    }

    /* Cart Sidebar Styles */
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

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background: #4a7c4e;
      color: white;
    }

    .btn-primary:hover {
      background: #3a5f3e;
    }

    .btn-full-width {
      width: 100%;
    }
  </style>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      initializeBrowsePage();
      initializeFloatingButtons();
    });

    function initializeBrowsePage() {
      // Add event listeners for filters
      document.getElementById('categoryFilter')?.addEventListener('change', applyFilters);
      document.getElementById('locationFilter')?.addEventListener('change', applyFilters);
      document.getElementById('sortBy')?.addEventListener('change', applyFilters);
      
      // Search input with debounce
      let searchTimeout;
      document.getElementById('searchInput')?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => applyFilters(), 500);
      });
    }

    function initializeFloatingButtons() {
      const cartToggle = document.getElementById('cartToggle');
      const closeCart = document.getElementById('closeCart');
      const cartSidebar = document.getElementById('cartSidebar');
      const cartOverlay = document.getElementById('cartOverlay');
      const favoritesToggle = document.getElementById('favoritesToggle');
      const scrollToTop = document.getElementById('scrollToTop');

      // Load cart and favorites counts
      loadCartCount();
      loadFavoritesCount();

      // Cart sidebar toggle
      if (cartToggle && cartSidebar) {
        cartToggle.addEventListener('click', function() {
          cartSidebar.classList.add('active');
          if (cartOverlay) {
            cartOverlay.classList.add('active');
          }
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

      // Favorites button - navigate to favorites page
      if (favoritesToggle) {
        favoritesToggle.addEventListener('click', function() {
          window.location.href = '<?= $base ?>/favorites';
        });
      }

      // Scroll to top button
      if (scrollToTop) {
        scrollToTop.addEventListener('click', function() {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
      }
    }

    function loadCartCount() {
      const userId = window.currentUserId || 'guest';
      const cartCookieName = 'ulimi_cart_user_' + userId;
      const cartItems = getCookie(cartCookieName);

      const cartCount = document.getElementById('cartCount');
      if (cartCount && cartItems) {
        cartCount.textContent = Array.isArray(cartItems) ? cartItems.length : 0;
      }
    }

    function loadFavoritesCount() {
      // Load favorites count from PHP variable or fetch from server
      const favoritesCount = document.getElementById('favoritesCount');
      if (favoritesCount) {
        // Use the userFavorites array from PHP if available
        const favorites = <?= isset($userFavorites) ? json_encode($userFavorites) : '[]' ?>;
        favoritesCount.textContent = Array.isArray(favorites) ? favorites.length : 0;
      }
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

    function setCookie(name, value, days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      const expires = '; expires=' + date.toUTCString();
      document.cookie = name + '=' + JSON.stringify(value) + expires + '; path=/';
    }

    function deleteCookie(name) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    // CSRF token for API requests
    const csrfToken = '<?= htmlspecialchars(\App\Core\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>';

    // Store cart items locally
    let cartItems = [];

    function addToCart(productId, event) {
      event.preventDefault();
      event.stopPropagation();

      // Get listing data from the page
      const listingElement = event.target.closest('.group');
      const listingTitle = listingElement.querySelector('h3')?.textContent || '';
      const listingPrice = listingElement.querySelector('.text-lg')?.textContent || '';
      const listingImage = listingElement.querySelector('img')?.src || '';

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
      const userId = window.currentUserId || 'guest';
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

      // Open cart sidebar
      document.getElementById('cartSidebar')?.classList.add('active');
      document.getElementById('cartOverlay')?.classList.add('active');
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
        </div>
      `).join('');

      // Calculate total
      let total = 0;
      cartItems.forEach(item => {
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

    function updateCartQuantity(productId, newQuantity, event) {
      event.stopPropagation();

      // Validate quantity
      if (newQuantity < 1) {
        // Remove item if quantity goes to 0
        removeFromCart(productId, event);
        return;
      }

      // Update local cart
      const item = cartItems.find(item => item.id === productId);
      if (item) {
        item.quantity = newQuantity;

        // Save to cookie
        const userId = window.currentUserId || 'guest';
        const cartCookieName = 'ulimi_cart_user_' + userId;
        setCookie(cartCookieName, cartItems, 7);

        // Re-render cart
        renderCartItems();
      }
    }

    function removeFromCart(productId, event) {
      event.stopPropagation();

      // Remove from local cart
      cartItems = cartItems.filter(item => item.id !== productId);

      // Update cookie
      const userId = window.currentUserId || 'guest';
      const cartCookieName = 'ulimi_cart_user_' + userId;
      setCookie(cartCookieName, cartItems, 7);

      // Update cart count
      const cartCount = document.getElementById('cartCount');
      if (cartCount) {
        cartCount.textContent = cartItems.length;
      }

      // Re-render cart
      renderCartItems();
    }

    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Load cart from cookie on page load
    function loadCartFromCookie() {
      const userId = window.currentUserId || 'guest';
      const cartCookieName = 'ulimi_cart_user_' + userId;
      const savedCart = getCookie(cartCookieName);
      if (savedCart && Array.isArray(savedCart) && savedCart.length > 0) {
        cartItems = savedCart;
        renderCartItems();
      }
    }

    // Call load cart from cookie on initialization
    loadCartFromCookie();

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

    function resetFilters() {
      window.location.href = '/browse';
    }

    function setCategory(category) {
      document.getElementById('categoryFilter').value = category;
      applyFilters();
    }

    function toggleFavorite(listingId, button) {
      const icon = button.querySelector('.favorite-icon');
      const isFavorited = icon.textContent === 'favorite';

      // Toggle icon
      icon.textContent = isFavorited ? 'favorite_border' : 'favorite';
      icon.style.fontVariationSettings = isFavorited ? "'FILL' 0" : "'FILL' 1";

      // Make API call with CSRF token in body
      fetch('/api/favorites/' + (isFavorited ? 'remove' : 'add'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ listing_id: listingId, _csrf: csrfToken })
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          // Revert on error
          icon.textContent = isFavorited ? 'favorite' : 'favorite_border';
          icon.style.fontVariationSettings = isFavorited ? "'FILL' 1" : "'FILL' 0";
          console.error('Failed to toggle favorite:', data.message);
        } else {
          // Update favorites count
          const favoritesCount = document.getElementById('favoritesCount');
          if (favoritesCount) {
            const currentCount = parseInt(favoritesCount.textContent) || 0;
            favoritesCount.textContent = isFavorited ? currentCount - 1 : currentCount + 1;
          }
        }
      })
      .catch(error => {
        // Revert on error
        icon.textContent = isFavorited ? 'favorite' : 'favorite_border';
        icon.style.fontVariationSettings = isFavorited ? "'FILL' 1" : "'FILL' 0";
        console.error('Error toggling favorite:', error);
      });
    }
  </script>
</body>
</html>
