<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'About Ulimi', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    /* About page scoped styles */
    .about-page {
      --about-bg: #f2ede4;
      --about-card-bg: rgba(255,255,255,0.92);
      --about-border: rgba(171,175,159,0.2);
      --about-text: #1a3d22;
      --about-accent: #347e44;
      --about-subtle: #abaf9f;
      background: var(--about-bg);
      color: var(--about-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
    }

    .about-section {
      padding: 80px 0;
    }

    .about-container {
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .about-hero {
      text-align: center;
      padding: 120px 0 80px;
      background: linear-gradient(135deg, rgba(61,107,63,0.08) 0%, rgba(200,168,75,0.07) 100%);
    }

    .about-hero h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 3.5rem;
      font-weight: 400;
      margin-bottom: 1.5rem;
      color: var(--about-text);
    }

    .about-hero p {
      font-size: 1.25rem;
      margin-bottom: 2.5rem;
      color: var(--about-subtle);
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .cta-btn {
      display: inline-block;
      padding: 14px 32px;
      background: var(--about-accent);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s;
      border: 2px solid var(--about-accent);
    }

    .cta-btn:hover {
      background: transparent;
      color: var(--about-accent);
    }

    .section-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      text-align: center;
      margin-bottom: 3rem;
      color: var(--about-text);
    }

    .mission,
    .vision,
    .approach {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
    }

    .mission h2,
    .vision h2,
    .approach h2 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      margin-bottom: 1.5rem;
      color: var(--about-text);
    }

    .mission p,
    .vision p,
    .approach p {
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
      color: var(--about-subtle);
    }

    .values {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      margin-bottom: 4rem;
    }

    .value {
      background: var(--about-card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--about-border);
      text-align: center;
    }

    .value h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--about-accent);
    }

    .value p {
      color: var(--about-subtle);
      line-height: 1.6;
    }

    .stats {
      background: linear-gradient(135deg, rgba(61,107,63,0.05) 0%, rgba(200,168,75,0.04) 100%);
      padding: 4rem 0;
      margin-bottom: 4rem;
    }

    .stats .about-container {
      text-align: center;
    }

    .stats h2 {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      margin-bottom: 3rem;
      color: var(--about-text);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
    }

    .stat {
      text-align: center;
    }

    .stat h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 3rem;
      font-weight: 400;
      margin-bottom: 0.5rem;
      color: var(--about-accent);
    }

    .stat p {
      font-size: 1.1rem;
      color: var(--about-subtle);
    }

    .approach ul {
      text-align: left;
      list-style: none;
      padding: 0;
      margin: 2rem 0;
    }

    .approach li {
      padding: 0.75rem 0;
      padding-left: 2rem;
      position: relative;
      color: var(--about-subtle);
    }

    .approach li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--about-accent);
      font-weight: bold;
    }

    .team {
      text-align: center;
    }

    .team p {
      max-width: 600px;
      margin: 0 auto 3rem;
      color: var(--about-subtle);
    }

    .team-members {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .team-member {
      background: var(--about-card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--about-border);
      text-align: center;
    }

    .team-member img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 1.5rem;
      border: 4px solid var(--about-accent);
    }

    .team-member h4 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.25rem;
      margin-bottom: 0.5rem;
      color: var(--about-text);
    }

    .team-member p {
      color: var(--about-subtle);
      margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .about-hero h1 {
        font-size: 2.5rem;
      }

      .about-hero p {
        font-size: 1.1rem;
      }

      .values {
        grid-template-columns: 1fr;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .team-members {
        grid-template-columns: 1fr;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .about-hero {
        padding: 2.75rem 0.875rem;
      }
      
      .about-hero h1 {
        font-size: 1.875rem;
      }
      
      .about-hero p {
        font-size: 0.95rem;
      }
      
      .stats-grid {
        gap: 0.875rem;
      }
      
      .stat-card {
        padding: 1.25rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .about-hero {
        padding: 3rem 1rem;
      }
      
      .about-hero h1 {
        font-size: 2rem;
      }
      
      .about-hero p {
        font-size: 1rem;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .stat-card {
        padding: 1.5rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .about-hero {
        padding: 2rem 0.75rem;
      }
      
      .about-hero h1 {
        font-size: 1.75rem;
      }
      
      .about-hero p {
        font-size: 0.95rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .about-hero h1 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body class="about-page">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <!-- Hero Section -->
  <section class="about-hero">
    <div class="about-container">
      <h1>About Ulimi</h1>
      <p>Ulimi is transforming the agricultural supply chain, connecting farmers, buyers, and suppliers across Malawi with a seamless, AI-powered platform. Our mission is to make agricultural trade faster, safer, and more accessible to everyone.</p>
      <a href="<?= $base ?>/register" class="cta-btn">Get Started</a>
    </div>
  </section>

  <!-- Mission Section -->
  <section class="about-section mission">
    <div class="about-container">
      <h2>Our Mission</h2>
      <p>At Ulimi, we believe that the future of agriculture lies in accessible, transparent, and efficient trade. Our goal is to provide farmers and agribusinesses with the tools they need to expand their reach, secure better deals, and overcome barriers to market access.</p>
      <p>We bridge the gap between local producers and markets across Malawi, empowering them to trade on their own terms, at competitive prices, with complete deal protection.</p>
    </div>
  </section>

  <!-- Vision Section -->
  <section class="about-section vision">
    <div class="about-container">
      <h2>Our Vision</h2>
      <p>We envision a Malawi where every farmer, no matter the size of their operation, can easily connect with buyers, suppliers, and markets. Ulimi strives to be the leading agri-tech solution in Malawi, providing real-time market intelligence, secure transactions, and access to funding.</p>
      <p>Our platform uses cutting-edge technology to break down language barriers, track commodity prices, and offer financing solutions to make agricultural trade a reality for every player in Malawi.</p>
    </div>
  </section>

  <!-- Core Values Section -->
  <section class="about-section">
    <div class="about-container">
      <h2 class="section-title">Our Core Values</h2>
      <div class="values">
        <div class="value">
          <h3>Integrity</h3>
          <p>We are committed to maintaining transparency, honesty, and fairness in every interaction, ensuring that every transaction is secure and reliable.</p>
        </div>
        <div class="value">
          <h3>Innovation</h3>
          <p>We continually push the boundaries of technology to offer smarter, more efficient tools for farmers, suppliers, and buyers in the agriculture sector.</p>
        </div>
        <div class="value">
          <h3>Sustainability</h3>
          <p>We aim to support practices that ensure long-term environmental, economic, and social sustainability in the agricultural industry.</p>
        </div>
        <div class="value">
          <h3>Empowerment</h3>
          <p>We empower our users by giving them the tools, insights, and opportunities they need to grow their businesses, expand their markets, and improve their livelihoods.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="about-container">
      <h2>Ulimi by the Numbers</h2>
      <div class="stats-grid">
        <div class="stat">
          <h3>8,800+</h3>
          <p>Companies Registered</p>
        </div>
        <div class="stat">
          <h3>130+</h3>
          <p>Countries Served</p>
        </div>
        <div class="stat">
          <h3>100%</h3>
          <p>Deal Protection</p>
        </div>
        <div class="stat">
          <h3>2,400+</h3>
          <p>Active Listings Today</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Approach Section -->
  <section class="about-section approach">
    <div class="about-container">
      <h2>Our Approach to Malawi Agricultural Trade</h2>
      <p>Ulimi is revolutionizing agricultural trade by making it faster, safer, and more accessible. Our platform connects farmers with buyers, suppliers, and investors in real-time, removing the barriers to entry that often exist in traditional markets. We empower agricultural businesses by:</p>
      <ul>
        <li>Providing access to a marketplace for agricultural goods across Malawi</li>
        <li>Offering real-time price tracking, market insights, and AI-powered predictions</li>
        <li>Ensuring risk-free transactions with 100% deal protection</li>
        <li>Enabling users to access financing and payment solutions designed specifically for the agri-business sector</li>
      </ul>
    </div>
  </section>

  <!-- Team Section -->
  <section class="about-section team">
    <div class="about-container">
      <h2 class="section-title">Meet the Team</h2>
      <p>Behind Ulimi is a diverse team of experts with a passion for agriculture and technology. Our team is dedicated to building innovative solutions that support farmers and agribusinesses across Malawi.</p>
      <div class="team-members">
        <div class="team-member">
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23e8f5e9'/%3E%3Ctext x='50' y='55' text-anchor='middle' font-family='Arial' font-size='40' fill='%233d6b3f'%3EJD%3C/text%3E%3C/svg%3E" alt="John Doe">
          <h4>John Doe</h4>
          <p>Co-Founder & CEO</p>
        </div>
        <div class="team-member">
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23e8f5e9'/%3E%3Ctext x='50' y='55' text-anchor='middle' font-family='Arial' font-size='40' fill='%233d6b3f'%3EJS%3C/text%3E%3C/svg%3E" alt="Jane Smith">
          <h4>Jane Smith</h4>
          <p>Chief Technology Officer</p>
        </div>
        <div class="team-member">
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23e8f5e9'/%3E%3Ctext x='50' y='55' text-anchor='middle' font-family='Arial' font-size='40' fill='%233d6b3f'%3EMJ%3C/text%3E%3C/svg%3E" alt="Michael Johnson">
          <h4>Michael Johnson</h4>
          <p>Chief Operations Officer</p>
        </div>
      </div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
