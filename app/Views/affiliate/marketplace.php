<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Ulimi Marketplace', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    /* Marketplace site page scoped styles */
    .marketplace-site-page {
      --marketplace-bg: #f2ede4;
      --marketplace-card-bg: rgba(255,255,255,0.92);
      --marketplace-border: rgba(171,175,159,0.2);
      --marketplace-text: #1a3d22;
      --marketplace-accent: #347e44;
      --marketplace-subtle: #abaf9f;
      background: var(--marketplace-bg);
      background-image: linear-gradient(to right, rgba(26,61,34,0.04) 1px, transparent 1px), linear-gradient(to bottom, rgba(26,61,34,0.04) 1px, transparent 1px);
      background-size: 80px 80px;
      color: var(--marketplace-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
    }

    /* Fresh logo styles for marketplace site */
    .marketplace-site-page header .logo {
      display: flex !important;
      align-items: center !important;
      text-decoration: none !important;
      color: var(--earth) !important;
      font-weight: 600 !important;
      font-size: 1.5rem !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .marketplace-site-page header .logo-mark {
      width: 32px !important;
      height: 32px !important;
      margin-right: 12px !important;
      background: var(--leaf) !important;
      border-radius: 50% !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .marketplace-site-page header .logo-mark svg {
      width: 20px !important;
      height: 20px !important;
      fill: white !important;
      visibility: visible !important;
      opacity: 1 !important;
      display: block !important;
    }

    .marketplace-section {
      padding: 80px 0;
    }

    .marketplace-container {
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .cta-btn {
      display: inline-block;
      padding: 12px 28px;
      background: #347e44;
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s;
      border: 2px solid #347e44;
      font-size: 0.95rem;
    }

    .cta-btn:hover {
      background: transparent;
      color: #347e44;
    }

    .section-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      font-weight: 400;
      text-align: center;
      margin-bottom: 3rem;
      color: #1a3d22;
    }

    /* Categories Section */
    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 2rem;
    }

    .category-card {
      background: #f2ede4;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(26,61,34,0.08);
      border: 1px solid rgba(171,175,159,0.3);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .category-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(26,61,34,0.15);
      border-color: #347e44;
    }

    .category-icon {
      width: 70px;
      height: 70px;
      margin: 0 auto 1.5rem;
      background: linear-gradient(135deg, #347e44 0%, #1a3d22 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      color: white;
    }

    .category-card h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.4rem;
      margin-bottom: 0.75rem;
      color: #1a3d22;
    }

    .category-card p {
      color: #abaf9f;
      margin-bottom: 1rem;
      font-size: 0.95rem;
      line-height: 1.5;
    }

    .category-features {
      list-style: none;
      padding: 0;
      margin: 0 0 1.5rem 0;
      text-align: left;
    }

    .category-features li {
      color: #1a3d22;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      padding-left: 1.5rem;
      position: relative;
      line-height: 1.4;
    }

    .category-features li::before {
      content: '•';
      position: absolute;
      left: 0;
      color: #347e44;
      font-weight: bold;
    }

    /* Why Use Section */
    .why-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }

    .why-item {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(26,61,34,0.08);
      border: 1px solid rgba(171,175,159,0.3);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .why-item:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(26,61,34,0.15);
      border-color: #347e44;
    }

    .why-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 1rem;
      background: linear-gradient(135deg, #347e44 0%, #1a3d22 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .why-item h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
      color: #1a3d22;
    }

    .why-item p {
      color: #abaf9f;
      font-size: 0.9rem;
      line-height: 1.4;
    }

    /* Stats Section - Horizontal Row */
    .stats-section {
      background: linear-gradient(135deg, rgba(52,126,68,0.05) 0%, rgba(190,158,71,0.04) 100%);
      padding: 80px 0;
    }

    .stats-row {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 2rem;
      text-align: center;
    }

    .stat-item {
      flex: 1;
      min-width: 200px;
      padding: 1rem;
    }

    .stat-number {
      font-family: 'DM Serif Display', serif;
      font-size: 3rem;
      font-weight: 400;
      margin-bottom: 0.5rem;
      color: #347e44;
    }

    .stat-label {
      font-size: 1.1rem;
      color: #abaf9f;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .categories-grid {
        grid-template-columns: 1fr;
      }

      .why-grid {
        grid-template-columns: 1fr;
      }

      .stats-row {
        flex-direction: column;
      }

      .stat-item {
        min-width: 100%;
      }
    }

    @media (max-width: 640px) {
      .marketplace-section {
        padding: 60px 0;
      }

      .section-title {
        font-size: 2rem;
      }

      .category-card,
      .why-item {
        padding: 1.5rem;
      }

      .stat-number {
        font-size: 2.5rem;
      }
    }

    @media (max-width: 480px) {
      .section-title {
        font-size: 1.75rem;
      }

      .category-card,
      .why-item {
        padding: 1.25rem;
      }

      .stat-number {
        font-size: 2rem;
      }

      .stat-label {
        font-size: 0.95rem;
      }
    }
</style>
</head>
<body>

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <!-- Marketplace Categories Section -->
  <section class="marketplace-section">
    <div class="marketplace-container">
      <h2 class="section-title">Marketplace Categories</h2>
      <div class="categories-grid">
        <div class="category-card">
          <div class="category-icon"><i class="fa fa-seedling"></i></div>
          <h3>Grains & Cereals</h3>
          <p>Wheat, maize, rice, soybeans, and other staple crops from verified suppliers.</p>
          <ul class="category-features">
            <li>Bulk & wholesale options</li>
            <li>Regional availability</li>
            <li>Quality grades listed</li>
          </ul>
          <?php if (\App\Core\Auth::check()): ?>
            <a href="<?= $base ?>/browse" class="cta-btn">Browse Listings</a>
          <?php else: ?>
            <a href="<?= $base ?>/register" class="cta-btn">Browse Listings</a>
          <?php endif; ?>
        </div>

        <div class="category-card">
          <div class="category-icon"><i class="fa fa-apple"></i></div>
          <h3>Fresh Produce</h3>
          <p>Seasonal fruits and vegetables sourced directly from farms.</p>
          <ul class="category-features">
            <li>Freshness guaranteed</li>
            <li>Fast regional delivery</li>
            <li>Farm-to-market supply</li>
          </ul>
          <?php if (\App\Core\Auth::check()): ?>
            <a href="<?= $base ?>/browse" class="cta-btn">Browse Listings</a>
          <?php else: ?>
            <a href="<?= $base ?>/register" class="cta-btn">Browse Listings</a>
          <?php endif; ?>
        </div>

        <div class="category-card">
          <div class="category-icon"><i class="fa fa-cow"></i></div>
          <h3>Livestock & Dairy</h3>
          <p>Cattle, poultry, goats, milk, eggs, and meat products.</p>
          <ul class="category-features">
            <li>Certified producers</li>
            <li>Health & quality standards</li>
            <li>Live animals and processed goods</li>
          </ul>
          <?php if (\App\Core\Auth::check()): ?>
            <a href="<?= $base ?>/browse" class="cta-btn">Browse Listings</a>
          <?php else: ?>
            <a href="<?= $base ?>/register" class="cta-btn">Browse Listings</a>
          <?php endif; ?>
        </div>

        <div class="category-card">
          <div class="category-icon"><i class="fa fa-tractor"></i></div>
          <h3>Farm Equipment</h3>
          <p>Tractors, irrigation systems, tools, and machinery.</p>
          <ul class="category-features">
            <li>New & used equipment</li>
            <li>Spare parts available</li>
            <li>Rental options</li>
          </ul>
          <?php if (\App\Core\Auth::check()): ?>
            <a href="<?= $base ?>/browse" class="cta-btn">Browse Listings</a>
          <?php else: ?>
            <a href="<?= $base ?>/register" class="cta-btn">Browse Listings</a>
          <?php endif; ?>
        </div>

        <div class="category-card">
          <div class="category-icon"><i class="fa fa-flask"></i></div>
          <h3>Seeds & Inputs</h3>
          <p>Seeds, fertilizers, pesticides, and soil enhancers.</p>
          <ul class="category-features">
            <li>Hybrid & organic seeds</li>
            <li>Trusted agro-dealers</li>
            <li>Seasonal supply availability</li>
          </ul>
          <?php if (\App\Core\Auth::check()): ?>
            <a href="<?= $base ?>/browse" class="cta-btn">Browse Listings</a>
          <?php else: ?>
            <a href="<?= $base ?>/register" class="cta-btn">Browse Listings</a>
          <?php endif; ?>
        </div>

        <div class="category-card">
          <div class="category-icon"><i class="fa fa-box"></i></div>
          <h3>Processed & Value-Added Goods</h3>
          <p>Packaged foods, milled grains, oils, and agro-processed products.</p>
          <ul class="category-features">
            <li>Export-ready products</li>
            <li>Certified processing standards</li>
            <li>Bulk supply options</li>
          </ul>
          <?php if (\App\Core\Auth::check()): ?>
            <a href="<?= $base ?>/browse" class="cta-btn">Browse Listings</a>
          <?php else: ?>
            <a href="<?= $base ?>/register" class="cta-btn">Browse Listings</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Use Ulimi Marketplace Section -->
  <section class="marketplace-section">
    <div class="marketplace-container">
      <h2 class="section-title">Why Use Ulimi Marketplace</h2>
      <div class="why-grid">
        <div class="why-item">
          <div class="why-icon"><i class="fa fa-globe"></i></div>
          <h3>Nationwide Market Access</h3>
          <p>Connect with buyers and suppliers across Malawi</p>
        </div>

        <div class="why-item">
          <div class="why-icon"><i class="fa fa-check-circle"></i></div>
          <h3>Verified Sellers & Buyers</h3>
          <p>Trade with trusted, screened participants</p>
        </div>

        <div class="why-item">
          <div class="why-icon"><i class="fa fa-shield"></i></div>
          <h3>Secure Payments</h3>
          <p>Escrow-backed transactions for safer trading</p>
        </div>

        <div class="why-item">
          <div class="why-icon"><i class="fa fa-chart-line"></i></div>
          <h3>Market Prices & Trends</h3>
          <p>Stay updated with current pricing and demand</p>
        </div>

        <div class="why-item">
          <div class="why-icon"><i class="fa fa-mobile"></i></div>
          <h3>Mobile-Friendly Trading</h3>
          <p>Buy and sell easily from any device</p>
        </div>

        <div class="why-item">
          <div class="why-icon"><i class="fa fa-credit-card"></i></div>
          <h3>Flexible Payment Options</h3>
          <p>Mobile money, bank transfer, and digital payments</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Marketplace Stats Section -->
  <section class="stats-section">
    <div class="marketplace-container">
      <h2 class="section-title">Marketplace Stats</h2>
      <div class="stats-row">
        <div class="stat-item">
          <div class="stat-number">8,800+</div>
          <div class="stat-label">Active Businesses</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">2,400+</div>
          <div class="stat-label">Live Listings</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">1,700+</div>
          <div class="stat-label">Products Available</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">100%</div>
          <div class="stat-label">Secure Transactions</div>
        </div>
      </div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
