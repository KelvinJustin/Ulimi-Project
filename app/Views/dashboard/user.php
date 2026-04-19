<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="icon" type="image/png" href="/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <style>
    /* CSS Reset and Variables */
    *, *::before, *::after { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }

    :root {
      --earth:    #2B2A25;
      --leaf:     #3D6B3F;
      --leaf-lt:  #4F8A52;
      --crop:     #C8A84B;
      --crop-lt:  #E8C96A;
      --cream:    #F5F0E8;
      --cream-dk: #EBE4D6;
      --mist:     #F9F6F0;
      --charcoal: #1A1A16;
      --text-muted: #6B6558;
      --border:   rgba(43,42,37,0.12);
      --radius:   10px;
      --font-head: 'DM Serif Display', Georgia, serif;
      --font-body: 'DM Sans', sans-serif;
      --transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    html { 
      scroll-behavior: smooth; 
    }

    /* User Dashboard Styles */
    .user-dashboard-page {
      --dashboard-bg: var(--mist);
      --dashboard-card-bg: rgba(255,255,255,0.92);
      --dashboard-border: rgba(43,42,37,0.12);
      --dashboard-text: var(--earth);
      --dashboard-accent: var(--leaf);
      --dashboard-subtle: var(--text-muted);
      background: var(--dashboard-bg);
      color: var(--dashboard-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
    }

    .user-dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 32px;
      position: relative;
      overflow: visible;
    }

    .user-welcome-section {
      background: var(--dashboard-card-bg);
      border-radius: 20px;
      padding: 3rem;
      margin-bottom: 2rem;
      border: 1px solid var(--dashboard-border);
      text-align: center;
      position: relative;
      overflow: visible;
    }

    .user-welcome-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--dashboard-accent), var(--crop));
    }

    .user-welcome-section h1 {
      font-family: var(--font-head);
      font-size: 2.5rem;
      color: var(--dashboard-text);
      margin-bottom: 1rem;
    }

    .user-welcome-section p {
      font-size: 1.2rem;
      color: var(--dashboard-subtle);
      margin-bottom: 2rem;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .user-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .primary-action-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 14px 24px;
      background: var(--crop, #C8A84B);
      color: var(--charcoal, #1A1A16);
      text-decoration: none;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      white-space: nowrap;
      box-shadow: 0 4px 12px rgba(200, 168, 75, 0.2);
    }

    .primary-action-btn:hover {
      background: var(--crop-lt, #E8C96A);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(200, 168, 75, 0.3);
    }

    .secondary-action-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 14px 24px;
      background: var(--dashboard-accent, #3d6b3f);
      color: white;
      text-decoration: none;
      border-radius: 12px;
      font-weight: 500;
      font-size: 1rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      white-space: nowrap;
    }

    .secondary-action-btn:hover {
      background: #2d5a2f;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(61, 107, 63, 0.25);
    }

    .user-stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
      overflow: visible;
    }

    .user-stat-card {
      background: var(--dashboard-card-bg);
      border: 1px solid var(--dashboard-border);
      border-radius: 16px;
      padding: 2rem;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: visible;
    }

    .user-stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
      border-color: var(--dashboard-accent);
    }

    .user-stat-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, var(--dashboard-accent), var(--crop));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.8rem;
      margin: 0 auto 1.5rem;
      position: relative;
    }

    .user-stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--dashboard-text);
      margin-bottom: 0.5rem;
    }

    .user-stat-label {
      font-size: 1rem;
      color: var(--dashboard-subtle);
      font-weight: 500;
    }

    .user-features-section {
      background: var(--dashboard-card-bg);
      border-radius: 20px;
      padding: 3rem;
      margin-bottom: 2rem;
      position: relative;
      overflow: visible;
      border: 1px solid var(--dashboard-border);
    }

    .user-features-section h2 {
      font-family: var(--font-head);
      font-size: 2rem;
      color: var(--dashboard-text);
      margin-bottom: 2rem;
      text-align: center;
    }

    .user-features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      overflow: visible;
    }

    .user-feature-card {
      background: rgba(61,107,63,0.05);
      border-radius: 12px;
      padding: 2rem;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: visible;
    }

    .user-feature-card:hover {
      background: rgba(61,107,63,0.08);
      transform: translateY(-2px);
    }

    .user-feature-icon {
      width: 50px;
      height: 50px;
      background: var(--dashboard-accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      margin: 0 auto 1rem;
      position: relative;
    }

    .user-feature-card h3 {
      font-size: 1.3rem;
      color: var(--dashboard-text);
      margin-bottom: 1rem;
    }

    .user-feature-card p {
      color: var(--dashboard-subtle);
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }

    .user-feature-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 10px 20px;
      background: var(--dashboard-accent);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .user-feature-btn:hover {
      background: #2d5a2f;
      transform: translateY(-1px);
    }

    /* Responsive container padding */
    @media (max-width: 768px) {
      .container {
        padding: 0 20px;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 16px;
      }
    }

    @media (max-width: 360px) {
      .container {
        padding: 0 12px;
      }
    }

    /* Footer Styles */
    footer {
      background: var(--dashboard-card-bg, rgba(255, 255, 255, 0.05));
      border-top: 1px solid var(--dashboard-border, rgba(255, 255, 255, 0.1));
      color: var(--dashboard-text, #333);
      margin-top: 3rem;
      padding: 2rem 0;
    }
    
    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
    }
    
    .footer-brand {
      grid-column: span 2;
    }
    
    .footer-brand .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dashboard-accent, #3d6b3f) !important;
      text-decoration: none;
      margin-bottom: 1rem;
    }
    
    .footer-brand .logo .logo-mark {
      width: 32px;
      height: 32px;
      background: var(--dashboard-accent, #3d6b3f);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .footer-brand .logo .logo-mark svg {
      width: 20px;
      height: 20px;
      fill: white;
    }
    
    .footer-brand p {
      color: var(--dashboard-subtle, #666);
      line-height: 1.6;
      margin-bottom: 1rem;
    }
    
    .footer-socials {
      display: flex;
      gap: 1rem;
    }
    
    .footer-socials a {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--dashboard-accent, #3d6b3f);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s;
    }
    
    .footer-socials a:hover {
      background: #2d5a2f;
      transform: translateY(-2px);
    }
    
    .footer-col h6 {
      color: var(--dashboard-accent, #3d6b3f);
      font-weight: 600;
      margin-bottom: 1rem;
      text-transform: uppercase;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }
    
    .footer-col ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .footer-col ul li {
      margin-bottom: 0.5rem;
    }
    
    .footer-col ul li a {
      color: var(--dashboard-text, #333);
      text-decoration: none;
      transition: color 0.3s;
    }
    
    .footer-col ul li a:hover {
      color: var(--dashboard-accent, #3d6b3f);
    }
    
    .footer-bottom {
      border-top: 1px solid var(--dashboard-border, rgba(255, 255, 255, 0.1));
      padding-top: 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .footer-bottom span {
      color: var(--dashboard-subtle, #666);
    }
    
    .footer-bottom div {
      display: flex;
      gap: 1rem;
    }
    
    .footer-bottom div a {
      color: var(--dashboard-subtle, #666) !important;
      text-decoration: none !important;
      font-size: 0.85rem;
      transition: color 0.3s;
    }
    
    .footer-bottom div a:hover {
      color: var(--dashboard-accent, #3d6b3f) !important;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .user-dashboard-container {
        padding: 1rem 16px;
      }
      
      .user-welcome-section {
        padding: 2rem 1.5rem;
      }
      
      .user-welcome-section h1 {
        font-size: 2rem;
      }
      
      .user-actions {
        flex-direction: column;
        align-items: center;
      }
      
      .user-actions a {
        width: 100%;
        max-width: 300px;
        justify-content: center;
      }
      
      .user-stats-grid {
        grid-template-columns: 1fr;
      }
      
      .user-features-grid {
        grid-template-columns: 1fr;
      }
      
      .user-features-section {
        padding: 2rem 1.5rem;
      }
      
      .footer-brand {
        grid-column: span 1;
      }
      
      .footer-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
      
      .footer-bottom {
        flex-direction: column;
        text-align: center;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .user-dashboard-container {
        padding: 1rem 10px;
      }
      
      .user-welcome-section {
        padding: 1.25rem 0.75rem;
      }
      
      .user-welcome-section h1 {
        font-size: 1.85rem;
      }
      
      .user-actions {
        gap: 0.625rem;
      }
      
      .user-actions a {
        max-width: 260px;
        padding: 9px 18px;
        font-size: 0.875rem;
      }
      
      .user-stats-grid {
        gap: 0.875rem;
      }
      
      .user-stat-card {
        padding: 1.25rem;
      }
      
      .user-stat-icon {
        width: 44px;
        height: 44px;
        font-size: 1.2rem;
      }
      
      .user-stat-value {
        font-size: 1.875rem;
      }
      
      .user-features-grid {
        gap: 0.875rem;
      }
      
      .user-feature-card {
        padding: 1.25rem;
      }
      
      .user-feature-icon {
        width: 38px;
        height: 38px;
        font-size: 1.1rem;
      }
    }

    @media (max-width: 640px) {
      .user-dashboard-container {
        padding: 1rem 12px;
      }
      
      .user-welcome-section {
        padding: 1.5rem 1rem;
      }
      
      .user-welcome-section h1 {
        font-size: 1.75rem;
      }
      
      .user-welcome-section p {
        font-size: 1rem;
      }
      
      .user-actions {
        gap: 0.75rem;
      }
      
      .user-actions a {
        max-width: 280px;
        padding: 10px 20px;
        font-size: 0.9rem;
      }
      
      .user-stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .user-stat-card {
        padding: 1.5rem;
      }
      
      .user-stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.3rem;
      }
      
      .user-stat-value {
        font-size: 2rem;
      }
      
      .user-features-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .user-features-section {
        padding: 1.5rem 1rem;
      }
      
      .user-features-section h2 {
        font-size: 1.5rem;
      }
      
      .user-feature-card {
        padding: 1.5rem;
      }
      
      .user-feature-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
      }
      
      .user-feature-btn {
        padding: 8px 16px;
        font-size: 0.85rem;
      }
    }

    /* Extra Small Screens */
    @media (max-width: 480px) {
      .user-dashboard-container {
        padding: 1rem 8px;
      }
      
      .user-welcome-section {
        padding: 1rem 0.75rem;
      }
      
      .user-welcome-section h1 {
        font-size: 1.5rem;
      }
      
      .user-actions a {
        max-width: 240px;
        padding: 8px 16px;
        font-size: 0.85rem;
      }
      
      .user-stat-card {
        padding: 1rem;
      }
      
      .user-stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
      }
      
      .user-stat-value {
        font-size: 1.75rem;
      }
      
      .user-features-section {
        padding: 1rem 0.75rem;
      }
      
      .user-feature-card {
        padding: 1rem;
      }
      
      .user-feature-icon {
        width: 36px;
        height: 36px;
        font-size: 1rem;
      }
    }

    /* Ultra Small Screens */
    @media (max-width: 360px) {
      .user-dashboard-container {
        padding: 0.75rem 6px;
      }
      
      .user-welcome-section {
        padding: 0.75rem 0.5rem;
      }
      
      .user-welcome-section h1 {
        font-size: 1.25rem;
      }
      
      .user-actions a {
        max-width: 200px;
        padding: 6px 12px;
        font-size: 0.8rem;
      }
      
      .user-stat-card {
        padding: 0.75rem;
      }
      
      .user-stat-icon {
        width: 32px;
        height: 32px;
        font-size: 1rem;
      }
      
      .user-stat-value {
        font-size: 1.5rem;
      }
      
      .user-features-section {
        padding: 0.75rem 0.5rem;
      }
      
      .user-feature-card {
        padding: 0.75rem;
      }
    }
  </style>
</head>
<body class="user-dashboard-page">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  $user = \App\Core\Auth::user();
  $userRole = $user['role'] ?? 'buyer';
  ?>

  <div class="user-dashboard-container">
    <!-- Welcome Section -->
    <section class="user-welcome-section">
      <h1><?= htmlspecialchars($greeting, ENT_QUOTES, 'UTF-8') ?></h1>
      <p>
        <?php if ($userRole === 'seller'): ?>
          Manage your agricultural listings and connect with buyers across Malawi.
        <?php else: ?>
          Discover fresh agricultural products and support local farmers.
        <?php endif; ?>
      </p>
      
      <div class="user-actions">
        <?php if ($userRole === 'seller'): ?>
          <a href="<?= $base ?>/create-listing" class="primary-action-btn">
            <i class="fa fa-plus"></i>
            Create New Listing
          </a>
          <a href="<?= $base ?>/my-listings" class="secondary-action-btn">
            <i class="fa fa-list"></i>
            My Listings
          </a>
        <?php else: ?>
          <a href="<?= $base ?>/browse" class="primary-action-btn">
            <i class="fa fa-search"></i>
            Browse Products
          </a>
          <button onclick="openChatsModal()" class="secondary-action-btn">
            <i class="fa fa-comments"></i>
            Open Chats
          </button>
        <?php endif; ?>
      </div>
    </section>

    <!-- User Stats -->
    <div class="user-stats-grid">
      <?php if ($userRole === 'seller'): ?>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-list-alt"></i>
          </div>
          <div class="user-stat-value"><?= $listingCount ?? 0 ?></div>
          <div class="user-stat-label">Active Listings</div>
        </div>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="user-stat-value">12</div>
          <div class="user-stat-label">Total Orders</div>
        </div>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-money"></i>
          </div>
          <div class="user-stat-value">MWK 8,450</div>
          <div class="user-stat-label">Total Earnings</div>
        </div>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-star"></i>
          </div>
          <div class="user-stat-value">4.8</div>
          <div class="user-stat-label">Average Rating</div>
        </div>
      <?php else: ?>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="user-stat-value">8</div>
          <div class="user-stat-label">Orders Placed</div>
        </div>
        <a href="<?= $base ?>/favorites" class="user-stat-card" style="text-decoration: none;">
          <div class="user-stat-icon">
            <i class="fa fa-heart"></i>
          </div>
          <div class="user-stat-value"><?= $favoritesCount ?? 0 ?></div>
          <div class="user-stat-label">Saved Items</div>
        </a>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-money"></i>
          </div>
          <div class="user-stat-value">MWK 3,200</div>
          <div class="user-stat-label">Total Spent</div>
        </div>
        <div class="user-stat-card">
          <div class="user-stat-icon">
            <i class="fa fa-truck"></i>
          </div>
          <div class="user-stat-value">6</div>
          <div class="user-stat-label">Deliveries Received</div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Features Section -->
    <section class="user-features-section">
      <h2>Quick Actions</h2>
      <div class="user-features-grid">
        <?php if ($userRole === 'seller'): ?>
          <div class="user-feature-card">
            <div class="user-feature-icon">
              <i class="fa fa-plus-circle"></i>
            </div>
            <h3>Add New Product</h3>
            <p>List your agricultural products and reach buyers across Malawi.</p>
            <a href="<?= $base ?>/create-listing" class="user-feature-btn">
              <i class="fa fa-plus"></i>
              Create Listing
            </a>
          </div>
          <div class="user-feature-card">
            <div class="user-feature-icon">
              <i class="fa fa-eye"></i>
            </div>
            <h3>View Analytics</h3>
            <p>Track your sales performance and customer engagement.</p>
            <a href="<?= $base ?>/dashboard/analytics" class="user-feature-btn">
              <i class="fa fa-chart-line"></i>
              View Stats
            </a>
          </div>
          <div class="user-feature-card">
            <div class="user-feature-icon">
              <i class="fa fa-cog"></i>
            </div>
            <h3>Manage Settings</h3>
            <p>Update your profile and marketplace preferences.</p>
            <a href="<?= $base ?>/profile" class="user-feature-btn">
              <i class="fa fa-user"></i>
              My Profile
            </a>
          </div>
        <?php else: ?>
          <div class="user-feature-card">
            <div class="user-feature-icon">
              <i class="fa fa-search"></i>
            </div>
            <h3>Browse Products</h3>
            <p>Discover fresh agricultural products from local farmers.</p>
            <a href="<?= $base ?>/browse" class="user-feature-btn">
              <i class="fa fa-search"></i>
              Start Browsing
            </a>
          </div>
          <div class="user-feature-card">
            <div class="user-feature-icon">
              <i class="fa fa-heart"></i>
            </div>
            <h3>Saved Items</h3>
            <p>View your favorite products and watchlist.</p>
            <a href="<?= $base ?>/favorites" class="user-feature-btn">
              <i class="fa fa-heart"></i>
              View Favorites
            </a>
          </div>
          <div class="user-feature-card">
            <div class="user-feature-icon">
              <i class="fa fa-history"></i>
            </div>
            <h3>Order History</h3>
            <p>Track your orders and delivery status.</p>
            <a href="<?= $base ?>/orders" class="user-feature-btn">
              <i class="fa fa-list"></i>
              My Orders
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <!-- Chats Modal -->
  <div id="chatsModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="bg-white rounded-lg shadow-xl max-w-sm w-full max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-900">Your Chats</h2>
          <button onclick="closeChatsModal()" class="text-gray-400 hover:text-gray-600">
            <i class="fa fa-times text-xl"></i>
          </button>
        </div>
        <div id="chatsList" class="p-4 overflow-y-auto max-h-[60vh]">
          <p class="text-gray-500 text-center py-8">Loading chats...</p>
        </div>
      </div>
    </div>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    // Delete listing functionality

    // Chats Modal
    function openChatsModal() {
      document.getElementById('chatsModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      loadChats();
    }

    function closeChatsModal() {
      document.getElementById('chatsModal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function loadChats() {
      const chatsList = document.getElementById('chatsList');
      
      fetch('<?= $base ?>/api/messages/conversations')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.conversations.length > 0) {
            chatsList.innerHTML = data.conversations.map(conv => `
              <div class="flex items-start gap-3 p-3 border-b border-gray-100 hover:bg-gray-50">
                <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                  ${conv.other_name ? conv.other_name.charAt(0).toUpperCase() : 'U'}
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between mb-1">
                    <h3 class="font-semibold text-gray-900 truncate">${conv.other_name || 'Unknown'}</h3>
                    <span class="text-xs text-gray-500">
                      ${conv.last_message_time ? new Date(conv.last_message_time).toLocaleDateString() : ''}
                    </span>
                  </div>
                  ${conv.listing_title ? `<p class="text-xs text-gray-500 mb-1"><i class="fa fa-tag mr-1"></i>${conv.listing_title}</p>` : ''}
                  <p class="text-sm text-gray-600 truncate">${conv.last_message || 'No messages yet'}</p>
                </div>
                <div class="flex items-center gap-2">
                  ${conv.seller_slug ? `
                    <a href="<?= $base ?>/seller/${conv.seller_slug}" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                      <i class="fa fa-reply mr-1"></i>Reply
                    </a>
                  ` : ''}
                </div>
              </div>
            `).join('');
          } else {
            chatsList.innerHTML = '<p class="text-gray-500 text-center py-8">No chats yet. Start a conversation with a seller!</p>';
          }
        })
        .catch(error => {
          console.error('Error loading chats:', error);
          chatsList.innerHTML = '<p class="text-red-500 text-center py-8">Failed to load chats</p>';
        });
    }

    // Close modal on overlay click
    document.getElementById('chatsModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeChatsModal();
      }
    });
  </script>
</body>
</html>
