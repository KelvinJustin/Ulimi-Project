<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Services', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    /* Services page scoped styles */
    .services-page {
      --services-bg: #f2ede4;
      --services-card-bg: rgba(255,255,255,0.92);
      --services-border: rgba(171,175,159,0.2);
      --services-text: #1a3d22;
      --services-accent: #347e44;
      --services-subtle: #abaf9f;
      background: var(--services-bg);
      color: var(--services-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
    }

    /* Fresh logo styles for services site */
    .services-page header .logo {
      display: flex !important;
      align-items: center !important;
      text-decoration: none !important;
      color: #1a3d22 !important;
      font-weight: 600 !important;
      font-size: 1.5rem !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .services-page header .logo-mark {
      width: 32px !important;
      height: 32px !important;
      margin-right: 12px !important;
      background: #347e44 !important;
      border-radius: 50% !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .services-page header .logo-mark svg {
      width: 20px !important;
      height: 20px !important;
      fill: white !important;
      visibility: visible !important;
      opacity: 1 !important;
      display: block !important;
    }

    .services-section {
      padding: 80px 0;
    }

    .services-container {
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .services-hero {
      text-align: center;
      padding: 120px 0 80px;
      background: linear-gradient(135deg, rgba(61,107,63,0.08) 0%, rgba(200,168,75,0.07) 100%);
    }

    .services-hero h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 3.5rem;
      font-weight: 400;
      margin-bottom: 1.5rem;
      color: var(--services-text);
    }

    .services-hero p {
      font-size: 1.25rem;
      margin-bottom: 2.5rem;
      color: var(--services-subtle);
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .cta-btn {
      display: inline-block;
      padding: 14px 32px;
      background: var(--services-accent);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s;
      border: 2px solid var(--services-accent);
    }

    .cta-btn:hover {
      background: transparent;
      color: var(--services-accent);
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 2rem;
      margin-bottom: 4rem;
    }

    .service-card {
      background: var(--services-card-bg);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--services-border);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .service-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(43,42,37,0.12);
    }

    .service-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: var(--services-accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
    }

    .service-card h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--services-text);
    }

    .service-card p {
      color: var(--services-subtle);
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }

    .service-features {
      list-style: none;
      padding: 0;
      margin: 0;
      text-align: left;
    }

    .service-features li {
      padding: 0.5rem 0;
      padding-left: 1.5rem;
      position: relative;
      color: var(--services-subtle);
    }

    .service-features li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--services-accent);
      font-weight: bold;
    }

    .process-section {
      background: linear-gradient(135deg, rgba(61,107,63,0.05) 0%, rgba(200,168,75,0.04) 100%);
      padding: 80px 0;
    }

    .process-steps {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }

    .process-step {
      text-align: center;
    }

    .step-number {
      width: 60px;
      height: 60px;
      margin: 0 auto 1.5rem;
      background: var(--services-accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'DM Serif Display', serif;
      font-size: 1.5rem;
      color: white;
    }

    .process-step h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.25rem;
      margin-bottom: 1rem;
      color: var(--services-text);
    }

    .process-step p {
      color: var(--services-subtle);
    }

    .section-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      text-align: center;
      margin-bottom: 3rem;
      color: var(--services-text);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .services-hero h1 {
        font-size: 2.5rem;
      }

      .services-hero p {
        font-size: 1.1rem;
      }

      .services-grid {
        grid-template-columns: 1fr;
      }

      .process-steps {
        grid-template-columns: 1fr;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .services-hero {
        padding: 2.75rem 0.875rem;
      }
      
      .services-hero h1 {
        font-size: 1.875rem;
      }
      
      .services-hero p {
        font-size: 0.95rem;
      }
      
      .section-title {
        font-size: 1.875rem;
      }
      
      .service-card {
        padding: 1.25rem;
      }
      
      .process-step {
        padding: 1.25rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .services-hero {
        padding: 3rem 1rem;
      }
      
      .services-hero h1 {
        font-size: 2rem;
      }
      
      .services-hero p {
        font-size: 1rem;
      }
      
      .section-title {
        font-size: 2rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .services-hero {
        padding: 2rem 0.75rem;
      }
      
      .services-hero h1 {
        font-size: 1.75rem;
      }
      
      .services-hero p {
        font-size: 0.95rem;
      }
      
      .section-title {
        font-size: 1.75rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .services-hero h1 {
        font-size: 1.5rem;
      }
      
      .section-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <div class="services-page">
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <!-- Hero Section -->
  <section class="services-hero">
    <div class="services-container">
      <h1>Our Services</h1>
      <p>Ulimi offers a comprehensive suite of services designed to empower farmers, buyers, and suppliers in the agricultural marketplace. From secure transactions to AI-powered insights, we provide everything you need to succeed.</p>
      <a href="<?= $base ?>/register" class="cta-btn">Get Started</a>
    </div>
  </section>

  <!-- Services Grid -->
  <section class="services-section">
    <div class="services-container">
      <h2 class="section-title">What We Offer</h2>
      <div class="services-grid">
        <div class="service-card">
          <div class="service-icon"><i class="fa fa-exchange"></i></div>
          <h3>Marketplace Trading</h3>
          <p>Connect directly with buyers and sellers in our Malawi agricultural marketplace with secure, transparent transactions.</p>
          <ul class="service-features">
            <li>Real-time price tracking</li>
            <li>Secure payment processing</li>
            <li>100% deal protection</li>
            <li>Malawi buyer network</li>
          </ul>
        </div>

        <div class="service-card">
          <div class="service-icon"><i class="fa fa-brain"></i></div>
          <h3>AI-Powered Insights</h3>
          <p>Leverage artificial intelligence to make smarter trading decisions with predictive analytics and market intelligence.</p>
          <ul class="service-features">
            <li>Price predictions</li>
            <li>Market trend analysis</li>
            <li>Demand forecasting</li>
            <li>Risk assessment</li>
          </ul>
        </div>

        <div class="service-card">
          <div class="service-icon"><i class="fa fa-money"></i></div>
          <h3>Financing Solutions</h3>
          <p>Access tailored financing options to grow your agricultural business with flexible payment terms and competitive rates.</p>
          <ul class="service-features">
            <li>Trade financing</li>
            <li>Inventory funding</li>
            <li>Crop insurance</li>
            <li>Flexible repayment</li>
          </ul>
        </div>

        <div class="service-card">
          <div class="service-icon"><i class="fa fa-globe"></i></div>
          <h3>Malawi Logistics</h3>
          <p>Streamline your supply chain with our integrated logistics network connecting producers to markets across Malawi.</p>
          <ul class="service-features">
            <li>Shipping coordination</li>
            <li>Customs clearance</li>
            <li>Quality inspection</li>
            <li>Real-time tracking</li>
          </ul>
        </div>

        <div class="service-card">
          <div class="service-icon"><i class="fa fa-mobile"></i></div>
          <h3>Mobile Trading</h3>
          <p>Trade on the go with our mobile-optimized platform, enabling negotiations and transactions from anywhere.</p>
          <ul class="service-features">
            <li>Mobile negotiations</li>
            <li>Push notifications</li>
            <li>Offline mode</li>
            <li>Voice transactions</li>
          </ul>
        </div>

        <div class="service-card">
          <div class="service-icon"><i class="fa fa-shield"></i></div>
          <h3>Trust & Safety</h3>
          <p>Trade with confidence knowing every transaction is protected by our comprehensive security and verification system.</p>
          <ul class="service-features">
            <li>Identity verification</li>
            <li>Escrow protection</li>
            <li>Dispute resolution</li>
            <li>Fraud prevention</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Process Section -->
  <section class="process-section">
    <div class="services-container">
      <h2 class="section-title">How It Works</h2>
      <div class="process-steps">
        <div class="process-step">
          <div class="step-number">1</div>
          <h3>Sign Up</h3>
          <p>Create your free account and complete your profile to access our marketplace.</p>
        </div>
        <div class="process-step">
          <div class="step-number">2</div>
          <h3>Browse & Connect</h3>
          <p>Explore listings or post your products. Connect with buyers or suppliers directly.</p>
        </div>
        <div class="process-step">
          <div class="step-number">3</div>
          <h3>Negotiate & Trade</h3>
          <p>Use our platform to negotiate terms and complete secure transactions with full protection.</p>
        </div>
        <div class="process-step">
          <div class="step-number">4</div>
          <h3>Grow Your Business</h3>
          <p>Access insights, financing, and logistics support to scale your agricultural operations.</p>
        </div>
      </div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
