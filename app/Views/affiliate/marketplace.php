<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Ulimi Marketplace', ENT_QUOTES, 'UTF-8') ?></title>
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
  <section class="relative pt-8 pb-24 md:pt-8 md:pb-40 overflow-hidden px-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
      <div class="flex-1 z-10">
        <span class="inline-block px-4 py-1.5 mb-6 rounded-full bg-[#fdd97b] text-[#785d06] text-[0.75rem] font-bold uppercase tracking-wider">Marketplace</span>
        <h1 class="text-3xl md:text-5xl font-bold text-[#1d1c16] leading-[1.1] tracking-tight mb-8">
          The Future of <span class="text-[#16642e] italic">Malawi's</span> Agricultural Trade
        </h1>
        <p class="text-lg md:text-xl text-[#40493f] max-w-xl mb-10 leading-relaxed">
          Connect directly with verified farmers and commercial buyers. A transparent, secure ecosystem designed to digitize the heart of our economy.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= $base ?>/browse" class="bg-gradient-to-br from-[#16642e] to-[#347e44] text-white px-8 py-4 rounded-full font-bold text-lg atmospheric-shadow hover:scale-105 transition-transform">Explore Marketplace</a>
          <a href="<?= $base ?>/register" class="bg-[#ece8df] text-[#1d1c16] px-8 py-4 rounded-full font-bold text-lg hover:bg-[#e7e2d9] transition-colors">Sell Your Produce</a>
        </div>
      </div>
      <div class="flex-1 relative">
        <div class="w-80% aspect-[1/5] rounded-[2.5rem] overflow-hidden atmospheric-shadow">
          <img src="https://images.pexels.com/photos/33364793/pexels-photo-33364793/free-photo-of-vibrant-local-market-with-fresh-vegetables.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1" alt="Vibrant local market with fresh vegetables" class="w-full h-full object-cover">
        </div>
        <!-- Glassmorphism Stats Card -->
        <div class="absolute -bottom-8 -left-16 md:-left-16 p-4 rounded-3xl bg-[#fef9f0]/70 backdrop-blur-xl atmospheric-shadow max-w-[168px] shadow-2xl border-4 border-[#f8f3ea]">
          <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-outlined text-[#16642e] py-1.5 px-4 bg-[#347e44]/20 rounded-xl text-lg">trending_up</span>
            <span class="text-[10px] font-bold text-[#40493f] uppercase tracking-widest">Market Volume</span>
          </div>
          <div class="text-2xl font-extrabold text-[#1d1c16] tracking-tighter">+MK 200.4M</div>
          <div class="text-xs text-[#40493f]">Trade volume processed this season</div>
        </div>
      </div>
    </div>
    <!-- Abstract Background Shape -->
    <div class="absolute top-0 right-0 -z-10 w-2/3 h-full bg-[#f8f3ea] rounded-bl-[10rem]"></div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 bg-[#16642e] text-white">
    <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-8">
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter">8,800+</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Active Businesses</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter">2,400+</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Live Listings</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter">1,700+</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Products</div>
      </div>
      <div class="text-center">
        <div class="text-4xl md:text-5xl font-extrabold mb-1 tracking-tighter">100%</div>
        <div class="text-[#8bd894] text-sm font-medium uppercase tracking-widest">Secure</div>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="py-24 px-8 bg-[#fef9f0]">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
        <div class="max-w-2xl">
          <span class="text-[#16642e] font-bold text-sm tracking-[0.2em] uppercase mb-4 block">Our Ecosystem</span>
          <h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16] tracking-tight leading-tight">Marketplace Categories</h2>
        </div>
        <p class="text-[#40493f] text-lg max-w-sm">From industrial machinery to heritage seeds, we facilitate the entire agricultural value chain.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Category 1 -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="h-48 overflow-hidden">
            <img alt="Grains and Cereals" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="https://images.pexels.com/photos/18404065/pexels-photo-18404065/free-photo-of-bags-with-sand.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
          </div>
          <div class="p-8 flex flex-col flex-1">
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Grains & Cereals</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">High-quality bulk maize, soybeans, and rice directly from local clusters.</p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Grade A Maize
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Bulk Soybean Supply
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Certified Kilombero Rice
              </li>
            </ul>
            <?php if (\App\Core\Auth::check()): ?>
              <a href="<?= $base ?>/browse" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php else: ?>
              <a href="<?= $base ?>/register" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Category 2 -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="h-48 overflow-hidden">
            <img alt="Fresh Produce" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="https://images.pexels.com/photos/16747109/pexels-photo-16747109/free-photo-of-fresh-vegetables-and-fruits-at-market.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
          </div>
          <div class="p-8 flex flex-col flex-1">
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Fresh Produce</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">Farm-fresh vegetables and seasonal fruits harvested daily for retailers.</p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Organic Vegetables
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Seasonal Fruits
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Farm-to-Table Logistics
              </li>
            </ul>
            <?php if (\App\Core\Auth::check()): ?>
              <a href="<?= $base ?>/browse" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php else: ?>
              <a href="<?= $base ?>/register" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Category 3 -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="h-48 overflow-hidden">
            <img alt="Livestock and Dairy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="https://images.pexels.com/photos/31824306/pexels-photo-31824306/free-photo-of-close-up-of-cattle-eating-in-barn.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
          </div>
          <div class="p-8 flex flex-col flex-1">
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Livestock & Dairy</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">Verified healthy livestock and premium dairy products from certified farms.</p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Healthy Cattle & Goats
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Fresh Milk & Poultry
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Veterinary Verified
              </li>
            </ul>
            <?php if (\App\Core\Auth::check()): ?>
              <a href="<?= $base ?>/browse" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php else: ?>
              <a href="<?= $base ?>/register" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Category 4 -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="h-48 overflow-hidden">
            <img alt="Farm Equipment" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="https://images.pexels.com/photos/32566502/pexels-photo-32566502/free-photo-of-tractor-on-farmland-at-sunset-in-india.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
          </div>
          <div class="p-8 flex flex-col flex-1">
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Farm Equipment</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">Tools for modernization, from hand implements to solar irrigation systems.</p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Irrigation Systems
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Power Tillers & Tractors
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Post-harvest Tools
              </li>
            </ul>
            <?php if (\App\Core\Auth::check()): ?>
              <a href="<?= $base ?>/browse" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php else: ?>
              <a href="<?= $base ?>/register" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Category 5 -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="h-48 overflow-hidden">
            <img alt="Seeds and Inputs" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="https://images.pexels.com/photos/33971787/pexels-photo-33971787/free-photo-of-assorted-bird-seed-mix-with-sunflower-and-corn.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
          </div>
          <div class="p-8 flex flex-col flex-1">
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Seeds & Inputs</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">Foundation for growth. Quality seeds, fertilizers, and organic pest control.</p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> High-Yield Hybrid Seeds
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Organic Fertilizers
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Seedlings & Saplings
              </li>
            </ul>
            <?php if (\App\Core\Auth::check()): ?>
              <a href="<?= $base ?>/browse" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php else: ?>
              <a href="<?= $base ?>/register" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Category 6 -->
        <div class="group flex flex-col bg-[#ffffff] rounded-[2rem] overflow-hidden atmospheric-shadow hover:-translate-y-2 transition-transform duration-300">
          <div class="h-48 overflow-hidden">
            <img alt="Processed Goods" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="https://images.pexels.com/photos/18925018/pexels-photo-18925018/free-photo-of-selection-of-products-in-shop.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
          </div>
          <div class="p-8 flex flex-col flex-1">
            <h3 class="text-2xl font-bold text-[#1d1c16] mb-3">Processed Goods</h3>
            <p class="text-[#40493f] mb-6 text-sm leading-relaxed">Value-added agricultural products from flour to sunflower oils.</p>
            <ul class="space-y-3 mb-8 flex-1">
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Refined Cooking Oils
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Maize & Wheat Flour
              </li>
              <li class="flex items-center gap-3 text-sm font-medium text-[#1d1c16]">
                <span class="material-symbols-outlined text-[#16642e] text-lg">check_circle</span> Packaged Spices
              </li>
            </ul>
            <?php if (\App\Core\Auth::check()): ?>
              <a href="<?= $base ?>/browse" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php else: ?>
              <a href="<?= $base ?>/register" class="w-full py-4 rounded-xl bg-[#ece8df] text-[#1d1c16] font-bold hover:bg-[#16642e] hover:text-white transition-all text-center">Browse Listings</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Use Ulimi Section -->
  <section class="py-24 px-8 bg-[#f8f3ea] rounded-t-[5rem]">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-20">
        <h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16] mb-6 tracking-tight">The Ulimi Advantage</h2>
        <p class="text-[#40493f] text-lg max-w-2xl mx-auto">Digitizing agriculture with trust, transparency, and accessibility at the forefront of everything we do.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        <!-- Value Prop 1 -->
        <div class="flex gap-6">
          <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-[#347e44]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#16642e] text-3xl">public</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-[#1d1c16] mb-2">Nationwide Access</h4>
            <p class="text-[#40493f] text-sm leading-relaxed">Connect with buyers and sellers across all regions of Malawi, bridging the urban-rural divide.</p>
          </div>
        </div>
        <!-- Value Prop 2 -->
        <div class="flex gap-6">
          <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-[#347e44]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#16642e] text-3xl">verified_user</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-[#1d1c16] mb-2">Verified Users</h4>
            <p class="text-[#40493f] text-sm leading-relaxed">Every participant is vetted through a rigorous KYC process ensuring a community of trust.</p>
          </div>
        </div>
        <!-- Value Prop 3 -->
        <div class="flex gap-6">
          <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-[#347e44]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#16642e] text-3xl">payments</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-[#1d1c16] mb-2">Secure Payments</h4>
            <p class="text-[#40493f] text-sm leading-relaxed">Integrated escrow services protect your money until the goods are delivered and verified.</p>
          </div>
        </div>
        <!-- Value Prop 4 -->
        <div class="flex gap-6">
          <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-[#347e44]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#16642e] text-3xl">monitoring</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-[#1d1c16] mb-2">Market Trends</h4>
            <p class="text-[#40493f] text-sm leading-relaxed">Real-time price monitoring and demand insights to help you trade smarter.</p>
          </div>
        </div>
        <!-- Value Prop 5 -->
        <div class="flex gap-6">
          <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-[#347e44]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#16642e] text-3xl">smartphone</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-[#1d1c16] mb-2">Mobile-Friendly</h4>
            <p class="text-[#40493f] text-sm leading-relaxed">Optimized for every device, including basic USSD access for remote locations.</p>
          </div>
        </div>
        <!-- Value Prop 6 -->
        <div class="flex gap-6">
          <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-[#347e44]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#16642e] text-3xl">account_balance_wallet</span>
          </div>
          <div>
            <h4 class="text-xl font-bold text-[#1d1c16] mb-2">Flexible Payments</h4>
            <p class="text-[#40493f] text-sm leading-relaxed">Supports Airtel Money, TNM Mpamba, and all major Malawian bank transfers.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
