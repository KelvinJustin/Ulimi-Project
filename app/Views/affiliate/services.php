<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Services', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    body { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .primary-gradient {
      background: linear-gradient(135deg, #16642e 0%, #347e44 100%);
    }
    .services-page {
      background: #fef9f0;
      color: #1d1c16;
      line-height: 1.6;
    }

    /* Hero section responsive fixes */
    @media (max-width: 1024px) {
      .hero-image-container {
        aspect-ratio: 3/4 !important;
        max-height: 400px;
      }
    }

    @media (max-width: 768px) {
      .hero-image-container {
        aspect-ratio: 4/5 !important;
        max-height: 350px;
      }
      .hero-accent-blob {
        display: none;
      }
    }

    @media (max-width: 480px) {
      .hero-image-container {
        aspect-ratio: 4/5 !important;
        max-height: 280px;
      }
    }

    @media (max-width: 360px) {
      .hero-image-container {
        aspect-ratio: 4/5 !important;
        max-height: 240px;
      }
    }

    /* Prevent horizontal overflow */
    .hero-section {
      overflow-x: hidden;
    }
  </style>
</head>
<body class="bg-[#fef9f0] text-[#1d1c16]">

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <!-- Hero Section -->
  <section class="max-w-7xl mx-auto px-6 py-20 hero-section">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div class="space-y-8">
        <div class="space-y-4">
          <span class="inline-block px-4 py-1.5 rounded-full bg-[#fdd97b] text-[#785d06] text-xs font-bold uppercase tracking-widest">Our Services</span>
          <h1 class="text-6xl font-bold text-[#1d1c16] tracking-tighter leading-tight">
            Cultivating <span class="text-[#16642e] italic">Growth</span> for the Global South.
          </h1>
          <p class="text-lg text-[#40493f] leading-relaxed max-w-xl">
            Ulimi provides a comprehensive ecosystem for farmers, buyers, and suppliers. From transparent marketplace trading to AI-driven demand forecasting, we bridge the gap between traditional harvest and modern commerce.
          </p>
        </div>
        <div class="flex items-center gap-4">
          <a href="<?= $base ?>/register" class="primary-gradient text-white px-8 py-4 rounded-full font-bold text-lg hover:shadow-lg active:scale-95 transition-all">Get Started</a>
          <button class="bg-[#ece8df] text-[#1d1c16] px-8 py-4 rounded-full font-bold text-lg hover:bg-[#e7e2d9] transition-all">Watch Demo</button>
        </div>
      </div>
      <div class="relative">
        <div class="hero-image-container aspect-[1/5] rounded-[2rem] overflow-hidden shadow-2xl relative z-10">
          <img src="https://images.pexels.com/photos/30750514/pexels-photo-30750514/free-photo-of-vibrant-market-scene-with-banana-vendors.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1" alt="Vibrant market scene with banana vendors" class="w-full h-full object-cover">
        </div>
        <!-- Asymmetric accent -->
        <div class="hero-accent-blob absolute -bottom-6 -left-6 w-48 h-48 bg-[#fdd97b] rounded-3xl -z-10 opacity-50 blur-2xl"></div>
      </div>
    </div>
  </section>

  <!-- Services Grid Section -->
  <section class="bg-[#f8f3ea] py-24">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between items-end mb-16">
        <div class="max-w-2xl">
          <h2 class="text-4xl font-bold text-[#1d1c16] mb-4">What We Offer</h2>
          <p class="text-[#40493f]">Tailored tools designed to empower every link in the agricultural value chain.</p>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1: Marketplace -->
        <div class="bg-white p-8 rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] hover:-translate-y-2 transition-transform duration-300">
          <div class="w-14 h-14 rounded-2xl bg-[#16642e]/10 flex items-center justify-center text-[#16642e] mb-6">
            <span class="material-symbols-outlined text-3xl">storefront</span>
          </div>
          <h3 class="text-xl font-bold text-[#1d1c16] mb-3">Marketplace Trading</h3>
          <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
            Secure, transparent transactions with real-time price tracking and integrated secure payments.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Secure Escrow</span>
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Price Index</span>
          </div>
        </div>

        <!-- Card 2: AI Insights -->
        <div class="bg-white p-8 rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] hover:-translate-y-2 transition-transform duration-300">
          <div class="w-14 h-14 rounded-2xl bg-[#6d5e18]/10 flex items-center justify-center text-[#6d5e18] mb-6">
            <span class="material-symbols-outlined text-3xl">psychology</span>
          </div>
          <h3 class="text-xl font-bold text-[#1d1c16] mb-3">AI-Powered Insights</h3>
          <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
            Leverage predictive analytics and deep market trends to forecast demand and optimize planting seasons.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Forecasting</span>
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Yield Predictor</span>
          </div>
        </div>

        <!-- Card 3: Financing -->
        <div class="bg-white p-8 rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] hover:-translate-y-2 transition-transform duration-300">
          <div class="w-14 h-14 rounded-2xl bg-[#755b03]/10 flex items-center justify-center text-[#755b03] mb-6">
            <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
          </div>
          <h3 class="text-xl font-bold text-[#1d1c16] mb-3">Financing Solutions</h3>
          <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
            Access trade financing, crop insurance, and flexible repayment terms tailored to agricultural cycles.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Insurance</span>
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Credit Score</span>
          </div>
        </div>

        <!-- Card 4: Logistics -->
        <div class="bg-white p-8 rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] hover:-translate-y-2 transition-transform duration-300">
          <div class="w-14 h-14 rounded-2xl bg-[#16642e]/10 flex items-center justify-center text-[#16642e] mb-6">
            <span class="material-symbols-outlined text-3xl">local_shipping</span>
          </div>
          <h3 class="text-xl font-bold text-[#1d1c16] mb-3">Malawi Logistics</h3>
          <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
            Integrated logistics network with automated shipping coordination and quality inspections at every hub.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Tracking</span>
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">QC Nodes</span>
          </div>
        </div>

        <!-- Card 5: Mobile Trading -->
        <div class="bg-white p-8 rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] hover:-translate-y-2 transition-transform duration-300">
          <div class="w-14 h-14 rounded-2xl bg-[#6d5e18]/10 flex items-center justify-center text-[#6d5e18] mb-6">
            <span class="material-symbols-outlined text-3xl">phone_iphone</span>
          </div>
          <h3 class="text-xl font-bold text-[#1d1c16] mb-3">Mobile Trading</h3>
          <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
            Negotiate and trade on the go with full offline mode and innovative voice-to-trade transactions.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Offline Mode</span>
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Voice Cmds</span>
          </div>
        </div>

        <!-- Card 6: Trust & Safety -->
        <div class="bg-white p-8 rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] hover:-translate-y-2 transition-transform duration-300">
          <div class="w-14 h-14 rounded-2xl bg-[#755b03]/10 flex items-center justify-center text-[#755b03] mb-6">
            <span class="material-symbols-outlined text-3xl">verified_user</span>
          </div>
          <h3 class="text-xl font-bold text-[#1d1c16] mb-3">Trust & Safety</h3>
          <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
            Advanced identity verification, escrow protection, and rapid dispute resolution for total peace of mind.
          </p>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">KYC Verified</span>
            <span class="px-3 py-1 bg-[#f8f3ea] rounded-full text-xs font-medium text-[#40493f]">Escrow</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="py-24 bg-[#fef9f0]">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-20">
        <h2 class="text-4xl font-bold text-[#1d1c16] mb-4">How It Works</h2>
        <p class="text-[#40493f]">Empowering your agricultural journey in four simple steps.</p>
      </div>
      <div class="relative grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Connection Line (Desktop only) -->
        <div class="hidden md:block absolute top-12 left-0 right-0 h-0.5 bg-[#bfc9bc]/30 -z-10"></div>
        <!-- Step 1 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-lg border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">1</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Sign Up</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Create your profile as a farmer, buyer, or supplier in minutes.</p>
        </div>
        <!-- Step 2 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-lg border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">2</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Browse & Connect</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Discover trusted partners and live marketplace listings near you.</p>
        </div>
        <!-- Step 3 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-lg border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">3</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Negotiate & Trade</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Directly negotiate prices and secure deals via protected escrow.</p>
        </div>
        <!-- Step 4 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-lg border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">4</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Grow Your Business</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Expand your reach and maximize your harvest value consistently.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="max-w-7xl mx-auto px-6 mb-24">
    <div class="primary-gradient rounded-[2.5rem] p-12 md:p-20 text-center text-white overflow-hidden relative">
      <div class="relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to transform your harvest?</h2>
        <p class="text-lg mb-10 max-w-2xl mx-auto opacity-90">Join thousands of farmers and traders already leveraging the Ulimi ecosystem for better growth and secure commerce.</p>
        <a href="<?= $base ?>/register" class="bg-white text-[#16642e] px-10 py-4 rounded-full font-bold text-lg shadow-xl hover:scale-105 active:scale-95 transition-all inline-block">Start Your Journey Now</a>
      </div>
      <!-- Background decorative elements -->
      <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#fdd97b]/20 rounded-full blur-3xl"></div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
