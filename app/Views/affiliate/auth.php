<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Account', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    /* Auth page scoped styles */
    .auth-page {
      --auth-bg: var(--mist);
      --auth-card-bg: rgba(255,255,255,0.92);
      --auth-border: rgba(43,42,37,0.12);
      --auth-text: var(--earth);
      --auth-accent: var(--leaf);
      --auth-subtle: var(--text-muted);
      background: var(--auth-bg);
      color: var(--auth-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
    }

    .auth-section {
      padding: 80px 0;
    }

    .auth-container {
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .auth-hero {
      text-align: center;
      padding: 120px 0 80px;
      background: linear-gradient(135deg, rgba(61,107,63,0.08) 0%, rgba(200,168,75,0.07) 100%);
    }

    .auth-hero h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 3.5rem;
      font-weight: 400;
      margin-bottom: 1.5rem;
      color: var(--auth-text);
    }

    .auth-hero p {
      font-size: 1.25rem;
      margin-bottom: 2.5rem;
      color: var(--auth-subtle);
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .auth-options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }

    .auth-option {
      background: var(--auth-card-bg);
      border-radius: 16px;
      padding: 3rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--auth-border);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .auth-option:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(43,42,37,0.12);
    }

    .auth-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: var(--auth-accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
    }

    .auth-option h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--auth-text);
    }

    .auth-option p {
      color: var(--auth-subtle);
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .cta-btn {
      display: inline-block;
      padding: 14px 32px;
      background: var(--auth-accent);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s;
      border: 2px solid var(--auth-accent);
    }

    .cta-btn:hover {
      background: transparent;
      color: var(--auth-accent);
    }

    .cta-btn.secondary {
      background: transparent;
      color: var(--auth-accent);
    }

    .cta-btn.secondary:hover {
      background: var(--auth-accent);
      color: white;
    }

    .features-list {
      list-style: none;
      padding: 0;
      margin: 0;
      text-align: left;
    }

    .features-list li {
      padding: 0.5rem 0;
      padding-left: 1.5rem;
      position: relative;
      color: var(--auth-subtle);
    }

    .features-list li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--auth-accent);
      font-weight: bold;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .auth-hero h1 {
        font-size: 2.5rem;
      }

      .auth-hero p {
        font-size: 1.1rem;
      }

      .auth-options {
        grid-template-columns: 1fr;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .auth-hero {
        padding: 2.75rem 0.875rem;
      }
      
      .auth-hero h1 {
        font-size: 1.875rem;
      }
      
      .auth-hero p {
        font-size: 0.95rem;
      }
      
      .auth-option {
        padding: 1.25rem;
      }
      
      .auth-option h3 {
        font-size: 1.15rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .auth-hero {
        padding: 3rem 1rem;
      }
      
      .auth-hero h1 {
        font-size: 2rem;
      }
      
      .auth-hero p {
        font-size: 1rem;
      }
      
      .auth-option {
        padding: 1.5rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .auth-hero {
        padding: 2rem 0.75rem;
      }
      
      .auth-hero h1 {
        font-size: 1.75rem;
      }
      
      .auth-hero p {
        font-size: 0.95rem;
      }
      
      .auth-option {
        padding: 1rem;
      }
      
      .auth-option h3 {
        font-size: 1.1rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .auth-hero h1 {
        font-size: 1.5rem;
      }
      
      .auth-hero p {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <!-- Hero Section -->
  <section class="auth-hero">
    <div class="auth-container">
      <h1>Welcome to Ulimi</h1>
      <p>Join thousands of farmers, buyers, and suppliers in our Malawi agricultural marketplace. Create your account to start trading with confidence.</p>
    </div>
  </section>

  <!-- Auth Options Section -->
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-options">
        <div class="auth-option">
          <div class="auth-icon">👤</div>
          <h3>Sign Up</h3>
          <p>Create your free account and join our Malawi agricultural marketplace. Start trading in minutes with full protection.</p>
          <ul class="features-list">
            <li>Free registration</li>
            <li>100% deal protection</li>
            <li>AI-powered insights</li>
            <li>Mobile trading support</li>
          </ul>
          <a href="<?= $base ?>/register" class="cta-btn">Create Account</a>
        </div>

        <div class="auth-option">
          <div class="auth-icon">🔐</div>
          <h3>Log In</h3>
          <p>Access your account to manage your listings, track orders, and connect with buyers and suppliers across Malawi.</p>
          <ul class="features-list">
            <li>Secure authentication</li>
            <li>Real-time notifications</li>
            <li>Order tracking</li>
            <li>Dashboard analytics</li>
          </ul>
          <a href="<?= $base ?>/login" class="cta-btn secondary">Sign In</a>
        </div>
      </div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
