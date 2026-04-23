<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Services', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    body { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .atmospheric-shadow {
      box-shadow: 0px 12px 32px rgba(26, 61, 34, 0.06);
    }
  </style>
</head>
<body class="bg-[#fef9f0] text-[#1d1c16]">

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <!-- Hero Section -->
  <section class="relative pt-16 pb-24 md:pt-19 md:pb-40 overflow-hidden px-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
      <div class="flex-1 z-10">
        <span class="inline-block px-4 py-1.5 mb-6 rounded-full bg-[#fdd97b] text-[#785d06] text-[0.75rem] font-bold uppercase tracking-wider">Our Services</span>
        <h1 class="text-3xl md:text-5xl font-bold text-[#1d1c16] leading-[1.1] tracking-tight mb-8">
          Cultivating <span class="text-[#16642e] italic">Growth</span> for the Nation
        </h1>
        <p class="text-lg md:text-xl text-[#40493f] max-w-xl mb-10 leading-relaxed">
          Ulimi provides a comprehensive ecosystem for farmers, buyers, and suppliers. From transparent marketplace trading to AI-driven demand forecasting, we bridge the gap between traditional harvest and modern commerce.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= $base ?>/register" class="bg-gradient-to-br from-[#16642e] to-[#347e44] text-white px-8 py-4 rounded-full font-bold text-lg atmospheric-shadow hover:scale-105 transition-transform">Get Started</a>
          <button class="bg-[#ece8df] text-[#1d1c16] px-8 py-4 rounded-full font-bold text-lg hover:bg-[#e7e2d9] transition-colors">Watch Demo</button>
        </div>
      </div>
      <div class="flex-1 relative">
        <div class="w-[90%] aspect-[2/5] rounded-[2.5rem] overflow-hidden atmospheric-shadow">
          <img src="https://images.pexels.com/photos/36470055/pexels-photo-36470055/free-photo-of-vibrant-daily-life-at-jinja-central-market.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1" alt="Vibrant daily life at Jinja central market" class="w-full h-full object-cover">
        </div>
        <!-- Glassmorphism Stats Card -->
        <div class="absolute -bottom-8 -left-8 md:-left-16 p-4 rounded-3xl bg-[#fef9f0]/70 backdrop-blur-xl atmospheric-shadow max-w-[168px] shadow-2xl border-4 border-[#f8f3ea]">
          <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-outlined text-[#16642e] px-4 py-1.5 bg-[#347e44]/20 rounded-xl text-lg">trending_up</span>
            <span class="text-[10px] font-bold text-[#40493f] uppercase tracking-widest">Market Volume</span>
          </div>
          <div class="text-2xl font-extrabold text-[#1d1c16] tracking-tighter">+MK 200.4M</div>
          <div class="text-xs text-[#40493f]">Trade volume processed this season</div>
        </div>
      </div>
    </div>
    <!-- Abstract Background Shape -->
    <div class="absolute top-0 right-0 -z-10 w-[47%] h-full bg-[#f8f3ea] rounded-bl-[10rem]"></div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 bg-[#16642e] text-white">
    <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-8">
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter counter" data-target="8800">0</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Active Businesses</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter counter" data-target="2400">0</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Live Listings</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter counter" data-target="1700">0</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Products</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter counter" data-target="100" data-suffix="%">0%</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Secure</div>
      </div>
    </div>
  </section>

  <!-- Services Grid Section -->
  <section class="py-24 px-8 bg-[#fef9f0]">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
        <div class="max-w-2xl">
          <span class="text-[#16642e] font-bold text-sm tracking-[0.2em] uppercase mb-4 block">Our Ecosystem</span>
          <h2 class="text-4xl md:text-5xl font-bold text-[#16642e] tracking-tight leading-tight">What We Offer</h2>
        </div>
        <p class="text-[#40493f] text-lg max-w-sm">Tailored tools designed to empower every link in the agricultural value chain.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1: Marketplace -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="p-8 flex flex-col flex-1">
            <div class="w-14 h-14 rounded-2xl bg-[#6d5e18]/10 flex items-center justify-center text-[#6d5e18] mb-6">
              <span class="material-symbols-outlined text-3xl">storefront</span>
            </div>
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Marketplace Trading</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
              Secure, transparent transactions with real-time price tracking and integrated secure payments.
            </p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Secure Escrow
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Price Index
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Real-time Tracking
              </li>
            </ul>
            <button class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all">Learn More</button>
          </div>
        </div>

        <!-- Card 2: AI Insights -->
        <!-- <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="p-8 flex flex-col flex-1">
            <div class="w-14 h-14 rounded-2xl bg-[#6d5e18]/10 flex items-center justify-center text-[#6d5e18] mb-6">
              <span class="material-symbols-outlined text-3xl">psychology</span>
            </div>
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">AI-Powered Insights</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
              Leverage predictive analytics and deep market trends to forecast demand and optimize planting seasons.
            </p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Demand Forecasting
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Yield Predictor
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Market Trends
              </li>
            </ul>
            <button class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all">Learn More</button>
          </div>
        </div> -->

        <!-- Card 3: Financing -->
        <!-- <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="p-8 flex flex-col flex-1">
            <div class="w-14 h-14 rounded-2xl bg-[#755b03]/10 flex items-center justify-center text-[#755b03] mb-6">
              <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
            </div>
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Financing Solutions</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
              Access trade financing, crop insurance, and flexible repayment terms tailored to agricultural cycles.
            </p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Crop Insurance
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Credit Score
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Flexible Terms
              </li>
            </ul>
            <button class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all">Learn More</button>
          </div>
        </div> -->

        <!-- Card 4: Logistics -->
        <!-- <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="p-8 flex flex-col flex-1">
            <div class="w-14 h-14 rounded-2xl bg-[#16642e]/10 flex items-center justify-center text-[#16642e] mb-6">
              <span class="material-symbols-outlined text-3xl">local_shipping</span>
            </div>
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Malawi Logistics</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
              Integrated logistics network with automated shipping coordination and quality inspections at every hub.
            </p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Real-time Tracking
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> QC Nodes
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Hub Network
              </li>
            </ul>
            <button class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all">Learn More</button>
          </div>
        </div> -->

        <!-- Card 5: Mobile Trading -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="p-8 flex flex-col flex-1">
            <div class="w-14 h-14 rounded-2xl bg-[#6d5e18]/10 flex items-center justify-center text-[#6d5e18] mb-6">
              <span class="material-symbols-outlined text-3xl">phone_iphone</span>
            </div>
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Mobile Trading</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
              Negotiate and trade on the go with full offline mode and innovative voice-to-trade transactions.
            </p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Offline Mode
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Voice Commands
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> USSD Access
              </li>
            </ul>
            <button class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all">Learn More</button>
          </div>
        </div>

        <!-- Card 6: Trust & Safety -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="p-8 flex flex-col flex-1">
            <div class="w-14 h-14 rounded-2xl bg-[#755b03]/10 flex items-center justify-center text-[#755b03] mb-6">
              <span class="material-symbols-outlined text-3xl">verified_user</span>
            </div>
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Trust & Safety</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">
              Advanced identity verification, escrow protection, and rapid dispute resolution for total peace of mind.
            </p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> KYC Verified
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Escrow Protection
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Dispute Resolution
              </li>
            </ul>
            <button class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all">Learn More</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="py-24 px-8 bg-[#f8f3ea] rounded-t-[5rem]">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-20">
        <h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16] mb-6 tracking-tight">How It Works</h2>
        <p class="text-[#40493f] text-lg max-w-2xl mx-auto">Empowering your agricultural journey in four simple steps.</p>
      </div>
      <div class="relative grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Step 1 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-[#ffffff] flex items-center justify-center atmospheric-shadow border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">1</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Sign Up</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Create your profile as a farmer, buyer, or supplier in minutes.</p>
        </div>
        <!-- Step 2 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-[#ffffff] flex items-center justify-center atmospheric-shadow border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">2</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Browse & Connect</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Discover trusted partners and live marketplace listings near you.</p>
        </div>
        <!-- Step 3 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-[#ffffff] flex items-center justify-center atmospheric-shadow border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
            <span class="text-3xl font-extrabold text-[#16642e]">3</span>
          </div>
          <h4 class="text-lg font-bold text-[#1d1c16] mb-2">Negotiate & Trade</h4>
          <p class="text-sm text-[#40493f] leading-relaxed">Directly negotiate prices and secure deals via protected escrow.</p>
        </div>
        <!-- Step 4 -->
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-full bg-[#ffffff] flex items-center justify-center atmospheric-shadow border-4 border-[#fef9f0] mb-6 group-hover:border-[#a7f5ae] transition-all">
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
    <div class="bg-gradient-to-br from-[#16642e] to-[#347e44] rounded-[2.5rem] p-12 md:p-20 text-center text-white overflow-hidden relative">
      <div class="relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to transform your harvest?</h2>
        <p class="text-lg mb-10 max-w-2xl mx-auto opacity-90">Join thousands of farmers and traders already leveraging the Ulimi ecosystem for better growth and secure commerce.</p>
        <a href="<?= $base ?>/register" class="bg-white text-[#16642e] px-10 py-4 rounded-full font-bold text-lg atmospheric-shadow hover:scale-105 active:scale-95 transition-all inline-block">Start Your Journey Now</a>
      </div>
      <!-- Background decorative elements -->
      <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#fdd97b]/20 rounded-full blur-3xl"></div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Counter animation
      const counters = document.querySelectorAll('.counter');

      function animateCounter(counter) {
        const target = +counter.getAttribute('data-target');
        const suffix = counter.getAttribute('data-suffix') || '';
        const addPlus = !suffix && counter.textContent.includes('+');
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
          current += increment;
          if (current < target) {
            const displayValue = Math.ceil(current);
            counter.textContent = displayValue.toLocaleString() + suffix + (addPlus && displayValue >= target ? '+' : '');
            requestAnimationFrame(updateCounter);
          } else {
            counter.textContent = target.toLocaleString() + suffix + (addPlus ? '+' : '');
          }
        };

        updateCounter();
      }

      // Start counter animation when page loads
      setTimeout(() => {
        counters.forEach((counter, index) => {
          setTimeout(() => {
            animateCounter(counter);
          }, index * 200); // Stagger animations
        });
      }, 500);
    });
  </script>
</body>
</html>
