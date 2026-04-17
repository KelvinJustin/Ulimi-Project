<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Ulimi — Agricultural Platform', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="icon" type="image/png" href="/logo.png">
  <style>
    @keyframes ticker {
      from { transform: translateX(0); }
      to { transform: translateX(-50%); }
    }

    /* Scroll animations - Tailwind-compatible approach */
    .scroll-animate {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.6s ease-out;
    }

    .scroll-animate.animate-in {
      opacity: 1;
      transform: translateY(0);
    }

    .scroll-animate:not(.animate-in) {
      opacity: 0.7;
      transform: translateY(10px);
    }

    .scroll-animate-scale {
      opacity: 1;
      transform: scale(1);
      transition: all 0.6s ease-out;
    }

    .scroll-animate-scale.animate-in {
      opacity: 1;
      transform: scale(1);
    }

    .scroll-animate-scale:not(.animate-in) {
      opacity: 0.7;
      transform: scale(0.98);
    }
  </style>
</head>
<body class="m-0 p-0">

  <?php
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  ?>

  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <section class="min-h-screen bg-cream flex flex-col relative overflow-hidden" id="top">
    <div class="absolute inset-0">
      <div class="absolute inset-0" style="background: radial-gradient(ellipse 60% 50% at 70% 40%, rgba(52,126,68,0.08) 0%, transparent 70%), radial-gradient(ellipse 40% 60% at 20% 80%, rgba(190,158,71,0.07) 0%, transparent 60%);"></div>
    </div>
    <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(to right, rgba(26,61,34,0.04) 1px, transparent 1px), linear-gradient(to bottom, rgba(26,61,34,0.04) 1px, transparent 1px); background-size: 80px 80px; mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);"></div>

    <div class="flex-1 flex items-center pt-24 pb-20">
      <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border text-center">
        <div>
          <h1 class="font-head text-[clamp(40px,5vw,62px)] leading-[1.1] tracking-[-1.5px] text-charcoal mb-[22px]">
            The Best <em class="italic text-leaf">Agricultural</em>
            <strong>Platform in Malawi</strong>
          </h1>
          <p class="text-base leading-[1.75] text-text-muted max-w-[600px] mx-auto mb-9">
            Ulimi connects farmers, buyers, and suppliers in real-time — enabling seamless trade through mobile device-based negotiations or digital listings, with AI-powered translation, live price tracking, and secure transactions.
          </p>
          <div class="flex items-center justify-center gap-3.5 flex-wrap">
            <a href="/register" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none bg-leaf text-white transition-all duration-280 ease-custom hover:bg-leaf-light hover:-translate-y-0.5 hover:shadow-lg">
              Sign Up
            </a>
            <a href="#about" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none bg-transparent text-earth border-[1.5px] border-earth/12 transition-all duration-280 ease-custom hover:border-leaf hover:text-leaf hover:bg-leaf/4">Learn More</a>
          </div>
          <div class="flex gap-8 mt-12 pt-8 border-t border-earth/12 justify-center">
            <div>
              <div class="font-head text-[28px] text-leaf leading-none">8,800+</div>
              <div class="text-xs text-text-muted mt-1 uppercase tracking-[0.5px]">Companies Registered</div>
            </div>
            <div>
              <div class="font-head text-[28px] text-leaf leading-none">1750+</div>
              <div class="text-xs text-text-muted mt-1 uppercase tracking-[0.5px]">Services</div>
            </div>
            <div>
              <div class="font-head text-[28px] text-leaf leading-none">100%</div>
              <div class="text-xs text-text-muted mt-1 uppercase tracking-[0.5px]">Deal Protection</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex items-center justify-center py-6 gap-2.5 text-xs text-text-muted border-t border-earth/12">
      <span>Scroll to explore</span>
      <span class="animate-bounce">↓</span>
    </div>
  </section>

  <div class="bg-crop overflow-hidden">
    <div class="flex overflow-hidden">
      <div class="flex items-center gap-0 whitespace-nowrap" id="ticker" style="animation: ticker 25s linear infinite;">
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Maize</span><span class="font-medium">MWK 0.20/kg</span><span class="text-xs text-green-600">▲ 2.4%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Ground Nuts</span><span class="font-medium">MWK 0.25/kg</span><span class="text-xs text-red-600">▼ 0.8%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Soya</span><span class="font-medium">MWK 0.38/kg</span><span class="text-xs text-green-600">▲ 1.1%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Pigeon Peas</span><span class="font-medium">MWK 0.42/kg</span><span class="text-xs text-green-600">▲ 0.6%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Tobacco</span><span class="font-medium">MWK 0.85/kg</span><span class="text-xs text-red-600">▼ 1.3%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Cotton</span><span class="font-medium">MWK 0.32/kg</span><span class="text-xs text-green-600">▲ 0.9%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Beans</span><span class="font-medium">MWK 0.28/kg</span><span class="text-xs text-green-600">▲ 0.3%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Maize</span><span class="font-medium">MWK 0.20/kg</span><span class="text-xs text-green-600">▲ 2.4%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Ground Nuts</span><span class="font-medium">MWK 0.25/kg</span><span class="text-xs text-red-600">▼ 0.8%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Soya</span><span class="font-medium">MWK 0.38/kg</span><span class="text-xs text-green-600">▲ 1.1%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Pigeon Peas</span><span class="font-medium">MWK 0.42/kg</span><span class="text-xs text-green-600">▲ 0.6%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Tobacco</span><span class="font-medium">MWK 0.85/kg</span><span class="text-xs text-red-600">▼ 1.3%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Cotton</span><span class="font-medium">MWK 0.32/kg</span><span class="text-xs text-green-600">▲ 0.9%</span></div>
        <div class="flex items-center gap-2.5 px-6 sm:px-8 py-3.5 border-r border-earth/15 text-xs sm:text-sm font-medium text-charcoal"><span class="opacity-70">Beans</span><span class="font-medium">MWK 0.28/kg</span><span class="text-xs text-green-600">▲ 0.3%</span></div>
      </div>
    </div>
  </div>

  <section class="mt-12 py-12 lg:py-16 bg-white" id="about">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border">
      <div class="inline-block text-xs font-medium uppercase tracking-[1.5px] text-leaf mb-3.5 scroll-animate">How it works</div>
      <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-charcoal mb-16 scroll-animate">Buy and sell at the<br>best possible price</h2>
      <div class="flex flex-wrap gap-6">
        <div class="bg-white rounded-2xl border border-earth/12 p-8 shadow-lg transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-xl flex-1 min-w-[280px] scroll-animate">
          <div class="font-head text-[48px] text-crop leading-none mb-5">01</div>
          <div class="text-lg font-medium text-charcoal mb-3">Register your farm or business</div>
          <div class="text-sm leading-[1.7] text-text-muted">Create your profile for free in minutes. No commitments, no technical skills required. Anyone can trade on Ulimi regardless of business scale.</div>
        </div>
        <div class="bg-white rounded-2xl border border-earth/12 p-8 shadow-lg transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-xl flex-1 min-w-[280px] scroll-animate" style="transition-delay: 0.1s;">
          <div class="font-head text-[48px] text-crop leading-none mb-5">02</div>
          <div class="text-lg font-medium text-charcoal mb-3">Connect with buyers &amp; sellers</div>
          <div class="text-sm leading-[1.7] text-text-muted">Browse Malawi's agricultural marketplace. Filter by commodity, region, and distance. Receive real-time offers and compare them side-by-side.</div>
        </div>
        <div class="bg-white rounded-2xl border border-earth/12 p-8 shadow-lg transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-xl flex-1 min-w-[280px] scroll-animate" style="transition-delay: 0.2s;">
          <div class="font-head text-[48px] text-crop leading-none mb-5">03</div>
          <div class="text-lg font-medium text-charcoal mb-3">Close deals with confidence</div>
          <div class="text-sm leading-[1.7] text-text-muted">Negotiate over phone or through digital listings. Protect 100% of your transaction — money and goods — with zero bureaucracy.</div>
        </div>
      </div>
    </div>
  </section>

  <section class="mt-12 py-12 lg:py-16" id="audience" style="background-color: #f2ede4; background-image: linear-gradient(#e5e0d5 1px, transparent 1px), linear-gradient(90deg, #e5e0d5 1px, transparent 1px); background-size: 100px 100px;">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border">
      <div class="inline-block text-xs font-medium uppercase tracking-[1.5px] text-leaf mb-6 mt-8 scroll-animate">Who is Ulimi for?</div>
      <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-charcoal mb-8 scroll-animate">Built for every role<br>in the ag supply chain</h2>
      <div class="flex flex-wrap gap-3 my-10">
        <button class="tab-btn px-6 py-3 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none transition-all duration-280 ease-custom bg-leaf text-white" data-tab="farmers">For Farmers</button>
        <button class="tab-btn px-6 py-3 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none transition-all duration-280 ease-custom bg-white text-charcoal border border-earth/12 hover:border-leaf hover:text-leaf" data-tab="wholesalers">Wholesalers &amp; Brokers</button>
        <button class="tab-btn px-6 py-3 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none transition-all duration-280 ease-custom bg-white text-charcoal border border-earth/12 hover:border-leaf hover:text-leaf" data-tab="processors">Processors &amp; Manufacturers</button>
        <button class="tab-btn px-6 py-3 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none transition-all duration-280 ease-custom bg-white text-charcoal border border-earth/12 hover:border-leaf hover:text-leaf" data-tab="retailers">Retailers &amp; HoReCa</button>
      </div>
      <div id="tab-farmers" class="tab-content grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12">
        <div>
          <h3 class="font-head text-[28px] leading-[1.2] text-charcoal mb-4">Sell directly to buyers across Malawi</h3>
          <p class="text-base leading-[1.7] text-text-muted mb-6">Whether you have a small farm or a large operation, Ulimi gives you access to processors, manufacturers, retailers, and government buyers across Malawi's agricultural market.</p>
          <ul class="space-y-3 mb-8">
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Wholesale selling across Malawi's regions</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Open your own online retail store and deliver locally</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Purchase farm inputs at the best possible price</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Risk-free transactions with 100% deal protection</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Quick-pay funding for deferred transactions</li>
          </ul>
          <a href="/register" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none bg-leaf text-white transition-all duration-280 ease-custom hover:bg-leaf-light hover:-translate-y-0.5 hover:shadow-lg">Start Selling Now</a>
        </div>
      </div>
      <div id="tab-wholesalers" class="tab-content hidden grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12">
        <div>
          <h3 class="font-head text-[28px] leading-[1.2] text-charcoal mb-4">Find every farmer and supplier across Malawi</h3>
          <p class="text-base leading-[1.7] text-text-muted mb-6">Discover suppliers by commodity, region, and distance. Receive offers, compare prices, send counteroffers, and close the most profitable deals across Malawi.</p>
          <ul class="space-y-3 mb-8">
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Real-time offer comparison and counteroffer tools</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Market monitoring: crops, prices, news by region</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>100% transaction protection for all deals</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Quick-pay funding and fulfillment service</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Expand your network of clients &amp; suppliers across Malawi</li>
          </ul>
          <a href="/register" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none bg-leaf text-white transition-all duration-280 ease-custom hover:bg-leaf-light hover:-translate-y-0.5 hover:shadow-lg">Find Suppliers Now</a>
        </div>
      </div>
      <div id="tab-processors" class="tab-content hidden grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12">
        <div>
          <h3 class="font-head text-[28px] leading-[1.2] text-charcoal mb-4">Source raw materials at peak efficiency</h3>
          <p class="text-base leading-[1.7] text-text-muted mb-6">Find all farmers for a specific commodity, country, and distance from your facility. Monitor harvest conditions and forecasts to make procurement decisions with confidence.</p>
          <ul class="space-y-3 mb-8">
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Filter farmers by commodity, location and distance</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Request dedicated production from farmers</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Compare offers across all input categories in Malawi</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Financing solutions for commodity purchases</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Fulfillment service — let Ulimi manage everything</li>
          </ul>
          <a href="/register" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none bg-leaf text-white transition-all duration-280 ease-custom hover:bg-leaf-light hover:-translate-y-0.5 hover:shadow-lg">Start Sourcing</a>
        </div>
      </div>
      <div id="tab-retailers" class="tab-content hidden grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-12">
        <div>
          <h3 class="font-head text-[28px] leading-[1.2] text-charcoal mb-4">Buy directly from farmers, delivered fresh</h3>
          <p class="text-base leading-[1.7] text-text-muted mb-6">The only working solution for your specific delivery needs. Buy directly from farmers in your region with personal delivery from them, scheduled at a fixed date and time — including next-day options.</p>
          <ul class="space-y-3 mb-8">
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Direct farm-to-store sourcing</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Scheduled delivery at a date and time you choose</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Receive offers from multiple farmers simultaneously</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Guaranteed quality and secured deliveries</li>
            <li class="flex items-start gap-3 text-sm text-charcoal"><span class="text-leaf mt-0.5">✓</span>Submit a request — Ulimi handles the rest</li>
          </ul>
          <a href="/register" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-[50px] font-body text-base font-medium cursor-pointer text-decoration-none border-none bg-leaf text-white transition-all duration-280 ease-custom hover:bg-leaf-light hover:-translate-y-0.5 hover:shadow-lg">Get Started</a>
        </div>
      </div>
    </div>
  </section>

  <section class="mt-12 py-12 lg:py-16 bg-white" id="features">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border">
      <div class="inline-block text-xs font-medium uppercase tracking-[1.5px] text-leaf mb-6 mt-8 scroll-animate">Platform features</div>
      <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-charcoal mb-8 scroll-animate">Everything you need to trade smarter</h2>
      <p class="text-base leading-[1.7] text-text-muted max-w-[600px] mb-16 scroll-animate">Unique, fast, easy, and designed for everyone — regardless of business scale or technological skills.</p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-globe"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Malawi Agricultural Platform</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Locate and connect with buyers, sellers, and suppliers based on commodity and location. The platform provides detailed contacts and activity, helping you find the best match for your trade needs.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.1s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-chart-line"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Real-Time Market Insights</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Stay on top of commodity prices, market fluctuations, and crop conditions across multiple countries. Access AI-powered price forecasts that help you make informed decisions.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.2s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-shield-alt"></i></div>
          <h3 class="font-medium text-charcoal mb-3">100% Deal Protection</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Guarantee secure transactions for both money and goods. Ulimi ensures your trade is protected with no unnecessary bureaucracy, making every deal safer and smoother.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.3s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-handshake"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Negotiation & Listings Tools</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Easily create listings or auctions for your goods. Receive and compare offers in real-time, send counteroffers, and negotiate directly with buyers or suppliers to get the best deal.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.4s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-coins"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Trade Financing</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Avoid timing gaps in payments. Ulimi offers quick-pay funding for farmers and deferred payment options for buyers, allowing you to close deals even if cash flow is tight.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.5s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-language"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Multi-Language Support with AI Translation</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Ulimi's AI-powered translation enables you to communicate in your native language, making it easier to trade with anyone, anywhere, and understand complex market trends.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.6s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-mobile-alt"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Mobile-Friendly Trading</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Continue doing business through your mobile device. Easily search for partners, access listings, and negotiate transactions via phone without needing to be on a desktop.</p>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.7s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-tractor"></i></div>
          <h3 class="font-medium text-charcoal mb-3">Agricultural Supply Platform</h3>
          <p class="text-sm leading-[1.6] text-text-muted">Buy agricultural supplies such as seeds, fertilizers, chemicals, and machinery. Compare prices across Malawi and take advantage of bulk purchasing discounts through group buying.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-12 lg:py-16" id="testimonials" style="background-color: #f2ede4;">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border">
      <div class="text-center mb-16 scroll-animate">
        <div class="inline-block text-xs font-medium uppercase tracking-[1.5px] text-leaf mb-3.5">Testimonials</div>
        <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-charcoal mb-4.5">What our customers say</h2>
        <p class="text-base leading-[1.7] text-text-muted max-w-[600px] mx-auto">Thousands of farmers, traders, and processors are already closing better deals on Ulimi.</p>
      </div>
      <div class="flex flex-wrap gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-xl flex-1 min-w-[300px] scroll-animate">
          <div class="flex items-center gap-1 mb-4">
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
          </div>
          <p class="text-sm leading-[1.7] text-charcoal mb-6">We simply publish our needs and start receiving offers in real time from sellers who meet all our requirements, including delivery dates and times. Their technology for matching and finding new potential business partners works exceptionally well.</p>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-leaf flex items-center justify-center text-white font-head text-lg">K</div>
            <div>
              <div class="font-medium text-charcoal">Kiril</div>
              <div class="text-xs text-text-muted">Retailer · Blantyre</div>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-lg transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-xl flex-1 min-w-[300px] scroll-animate" style="transition-delay: 0.1s;">
          <div class="flex items-center gap-1 mb-4">
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
          </div>
          <p class="text-sm leading-[1.7] text-charcoal mb-6">We make numerous spot deals throughout the year, and thanks to Ulimi, we have been able to find many new suppliers and customers. Their innovative platform where you can compare offers has been particularly valuable.</p>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-leaf flex items-center justify-center text-white font-head text-lg">M</div>
            <div>
              <div class="font-medium text-charcoal">Miroslav</div>
              <div class="text-xs text-text-muted">Trader · Mzuzu</div>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-lg transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-xl flex-1 min-w-[300px] scroll-animate" style="transition-delay: 0.2s;">
          <div class="flex items-center gap-1 mb-4">
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
            <i class="fas fa-star text-crop text-sm"></i>
          </div>
          <p class="text-sm leading-[1.7] text-charcoal mb-6">We were thrilled to discover Ulimi and their solution for tracking farmers based on distance from your location. This has helped us find many new suppliers. Their financing solution for commodity purchases is a game-changer.</p>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-leaf flex items-center justify-center text-white font-head text-lg">M</div>
            <div>
              <div class="font-medium text-charcoal">Mihaela</div>
              <div class="text-xs text-text-muted">Food Manufacturer · Zomba</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="mt-12 py-12 lg:py-16 bg-white" id="additional-services">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border">
      <div class="inline-block text-xs font-medium uppercase tracking-[1.5px] text-leaf mb-3.5">Additional Services</div>
      <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-charcoal mb-12">Trade with complete security</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-shield-alt"></i></div>
          <div class="text-xs font-medium uppercase tracking-[1px] text-earth mb-2">Risk management</div>
          <h3 class="font-head text-2xl text-charcoal mb-4">Deal Protection</h3>
          <p class="text-sm leading-[1.7] text-text-muted mb-6">Protect 100% of your transactions — both money and goods — for all deals across Malawi. No bureaucracy, no risk, guaranteed execution on both sides.</p>
          <a href="#" class="inline-flex items-center gap-2 text-sm font-medium text-leaf hover:text-leaf-light transition-colors duration-280">Learn More <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
        <div class="bg-cream rounded-2xl p-8 transition-all duration-280 ease-custom hover:-translate-y-1 hover:shadow-lg scroll-animate" style="transition-delay: 0.1s;">
          <div class="text-crop text-3xl mb-5"><i class="fas fa-coins"></i></div>
          <div class="text-xs font-medium uppercase tracking-[1px] text-earth mb-2">Financial solutions</div>
          <h3 class="font-head text-2xl text-charcoal mb-4">Funding</h3>
          <p class="text-sm leading-[1.7] text-text-muted mb-6">Quick-pay funding for farmers and buyers. Don't let timing gaps kill a profitable deal. Flexible financing designed specifically for agricultural transactions.</p>
          <a href="#" class="inline-flex items-center gap-2 text-sm font-medium text-leaf hover:text-leaf-light transition-colors duration-280">Learn More <i class="fas fa-arrow-right text-xs"></i></a>
        </div>
      </div>
    </div>
  </section>

  <section class="mt-12 py-12 lg:py-16 bg-cream" id="malawi-registry">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border">
      <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-charcoal mb-4.5 scroll-animate">Discover every participant<br>in agriculture</h2>
      <div class="text-base font-medium text-earth mb-2 scroll-animate">Search by Commodity, Location, Distance</div>
      <p class="text-base leading-[1.7] text-text-muted max-w-[600px] mb-8 scroll-animate">Access companies with up-to-date details and contacts from across Malawi.</p>
      <div class="flex flex-wrap gap-3 mb-12">
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Buyers</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Sellers</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Importers</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Exporters</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Lessees</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Lessors</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Barter Participants</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Service Providers</span>
        <span class="px-4 py-2 bg-white rounded-full text-sm text-charcoal">Service Seekers</span>
      </div>
      <div class="text-sm font-medium uppercase tracking-[1px] text-earth mb-6">Most recently joined</div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="flex items-center gap-3 p-4 bg-white rounded-xl scroll-animate">
          <div class="w-10 h-10 rounded-full bg-leaf flex items-center justify-center text-white font-head text-sm">M</div>
          <div>
            <div class="font-medium text-charcoal text-sm">Malawi Farmers Cooperative</div>
            <div class="text-xs text-text-muted">Malawi, Lilongwe</div>
          </div>
        </div>
        <div class="flex items-center gap-3 p-4 bg-white rounded-xl scroll-animate">
          <div class="w-10 h-10 rounded-full bg-leaf flex items-center justify-center text-white font-head text-sm">C</div>
          <div>
            <div class="font-medium text-charcoal text-sm">Central Region Traders</div>
            <div class="text-xs text-text-muted">Malawi, Salima</div>
          </div>
        </div>
        <div class="flex items-center gap-3 p-4 bg-white rounded-xl scroll-animate">
          <div class="w-10 h-10 rounded-full bg-leaf flex items-center justify-center text-white font-head text-sm">S</div>
          <div>
            <div class="font-medium text-charcoal text-sm">Southern Agri Suppliers</div>
            <div class="text-xs text-text-muted">Malawi, Blantyre</div>
          </div>
        </div>
        <div class="flex items-center gap-3 p-4 bg-white rounded-xl scroll-animate">
          <div class="w-10 h-10 rounded-full bg-leaf flex items-center justify-center text-white font-head text-sm">N</div>
          <div>
            <div class="font-medium text-charcoal text-sm">Northern Market Hub</div>
            <div class="text-xs text-text-muted">Malawi, Mzuzu</div>
          </div>
        </div>
        <div class="flex items-center gap-3 p-4 bg-white rounded-xl scroll-animate">
          <div class="w-10 h-10 rounded-full bg-leaf flex items-center justify-center text-white font-head text-sm">L</div>
          <div>
            <div class="font-medium text-charcoal text-sm">Lake Malawi Fisheries</div>
            <div class="text-xs text-text-muted">Malawi, Mangochi</div>
          </div>
        </div>
        <div class="flex items-center gap-3 p-4 bg-white rounded-xl scroll-animate">
          <div class="w-10 h-10 rounded-full bg-leaf flex items-center justify-center text-white font-head text-sm">S</div>
          <div>
            <div class="font-medium text-charcoal text-sm">Shire Valley Farmers</div>
            <div class="text-xs text-text-muted">Malawi, Chikwawa</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-12 lg:py-16" id="cta" style="background-color: #2d4a3e;">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full box-border text-center">
      <h2 class="font-head text-[clamp(32px,4vw,48px)] leading-[1.15] tracking-[-1px] text-white mb-6 scroll-animate">Join thousands who are already<br>trading smarter</h2>
      <p class="text-base leading-[1.7] text-white max-w-[600px] mx-auto mb-12 scroll-animate">Free registration. No commitments. Start in minutes.</p>
      <div class="flex flex-wrap gap-4 justify-center mb-12">
        <div onclick="window.location.href='<?= $base ?>/register'" class="cursor-pointer px-8 py-4 rounded-[50px] font-body text-base font-medium text-charcoal transition-all duration-280 ease-custom hover:-translate-y-0.5 hover:shadow-lg" style="background-color: #C8A84B;">Create Free Account</div>
        <div onclick="window.location.href='<?= $base ?>/login'" class="cursor-pointer px-8 py-4 rounded-[50px] font-body text-base font-medium bg-cream text-charcoal transition-all duration-280 ease-custom hover:bg-cream/80 hover:-translate-y-0.5 hover:shadow-lg">Log In</div>
      </div>
      <p class="text-sm leading-[1.7] text-white/70">Need help? Call <a href="tel:+265999137598" class="text-white hover:text-white/90 underline underline-offset-2">+265 999 137 598</a> or email <a href="mailto:support@ulimi.mw" class="text-white hover:text-white/90 underline underline-offset-2">support@ulimi.mw</a></p>
    </div>
  </section>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script src="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/js/app.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tabs = document.querySelectorAll('.tab-btn');
      const contents = document.querySelectorAll('.tab-content');

      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          tabs.forEach(t => {
            t.classList.remove('bg-leaf', 'text-white');
            t.classList.add('bg-white', 'text-charcoal', 'border', 'border-earth/12');
          });
          this.classList.remove('bg-white', 'text-charcoal', 'border', 'border-earth/12');
          this.classList.add('bg-leaf', 'text-white');

          const targetId = this.getAttribute('data-tab');
          contents.forEach(content => {
            content.classList.add('hidden');
          });
          document.getElementById('tab-' + targetId).classList.remove('hidden');
        });
      });

      // Scroll animations using Tailwind-compatible approach
      const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
          }
        });
      }, observerOptions);

      // Observe all animate elements
      const animateElements = document.querySelectorAll('.scroll-animate, .scroll-animate-scale');
      animateElements.forEach(el => observer.observe(el));
    });
  </script>
</body>
</html>
