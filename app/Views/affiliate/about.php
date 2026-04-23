<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'About Ulimi', ENT_QUOTES, 'UTF-8') ?></title>
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
    .primary-gradient {
      background: linear-gradient(135deg, #16642e 0%, #347e44 100%);
    }
  </style>
</head>
<body class="bg-[#fef9f0] text-[#1d1c16]">

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
<?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

<main class="pt-24">
<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-8 pb-8 grid md:grid-cols-2 gap-16 items-center">
<div class="space-y-8">
<h1 class="text-6xl md:text-7xl font-bold text-[#1d1c16] leading-[1.1] tracking-tight">About Ulimi</h1>
<p class="text-lg text-[#40493f] leading-relaxed max-w-xl">
  We are transforming the agricultural supply chain in Malawi through a decentralized digital conservatory. By bridging the gap between local growers and global buyers, we nurture a ecosystem where every harvest finds its true value.
</p>
<div class="flex gap-4">
<a href="<?= $base ?>/register" class="primary-gradient text-white px-8 py-4 rounded-full font-bold text-lg atmospheric-shadow hover:opacity-90 transition-opacity">Get Started</a>
<button class="bg-[#e7e2d9] text-[#1d1c16] px-8 py-4 rounded-full font-bold text-lg hover:bg-[#ece8df] transition-colors">Our Network</button>
</div>
</div>
<div class="relative group">
<div class="absolute -inset-4 bg-[#347e44]/10 rounded-[2.5rem] blur-2xl group-hover:bg-[#347e44]/20 transition-all"></div>
<img class="relative w-[80%] h-[350px] object-cover rounded-[2rem] atmospheric-shadow" alt="aerial view of crowded cattle market next to field" src="https://images.pexels.com/photos/33786829/pexels-photo-33786829/free-photo-of-aerial-view-of-crowded-cattle-market-next-to-field.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
</div>
</section>

<!-- Mission & Vision -->
<section class="bg-[#f8f3ea] py-24">
<div class="max-w-7xl mx-auto px-8 grid md:grid-cols-2 gap-8">
<div class="bg-white p-12 rounded-[1.5rem] atmospheric-shadow flex flex-col justify-between">
<div>
<span class="text-[#16642e] font-bold tracking-[0.2em] text-[0.65rem] uppercase mb-4 block">Purpose</span>
<h2 class="text-3xl font-semibold mb-6">Our Mission</h2>
<p class="text-[#40493f] text-lg leading-relaxed">
  To build an accessible, transparent, and efficient trade network that empowers Malawian farmers. We dismantle the barriers of traditional logistics to ensure that the fruits of labor reach the market with integrity and speed.
</p>
</div>
<div class="mt-8">
<span class="material-symbols-outlined text-[#347e44] text-5xl">diversity_2</span>
</div>
</div>
<div class="bg-[#347e44] p-12 rounded-[1.5rem] atmospheric-shadow text-[#d7ffd6] flex flex-col justify-between">
<div>
<span class="text-white/70 font-bold tracking-[0.2em] text-[0.65rem] uppercase mb-4 block">The Future</span>
<h2 class="text-3xl font-semibold mb-6">Our Vision</h2>
<p class="text-white/90 text-lg leading-relaxed">
  To become the leading agri-tech solution in Malawi, setting the gold standard for digital commerce in Africa. We envision a future where technology and tradition harmonize to create sustainable wealth for every agricultural community.
</p>
</div>
<div class="mt-8">
<span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">visibility</span>
</div>
</div>
</div>
</section>

<!-- Core Values -->
<section class="max-w-7xl mx-auto px-8 py-24">
<div class="text-center mb-16">
<h2 class="text-4xl font-bold mb-4">Our Core Values</h2>
<div class="h-1 w-20 bg-[#beab5e] mx-auto rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-8">
<div class="p-8 bg-[#f8f3ea] rounded-[1.5rem] text-center hover:bg-[#ece8df] transition-colors">
<span class="material-symbols-outlined text-[#16642e] text-4xl mb-6">verified_user</span>
<h3 class="text-xl font-bold mb-3">Integrity</h3>
<p class="text-sm text-[#40493f]">Radical transparency in every transaction and partnership we form.</p>
</div>
<div class="p-8 bg-[#f8f3ea] rounded-[1.5rem] text-center hover:bg-[#ece8df] transition-colors">
<span class="material-symbols-outlined text-[#16642e] text-4xl mb-6">lightbulb</span>
<h3 class="text-xl font-bold mb-3">Innovation</h3>
<p class="text-sm text-[#40493f]">Pioneering digital solutions tailored for the unique Malawian landscape.</p>
</div>
<div class="p-8 bg-[#f8f3ea] rounded-[1.5rem] text-center hover:bg-[#ece8df] transition-colors">
<span class="material-symbols-outlined text-[#16642e] text-4xl mb-6">eco</span>
<h3 class="text-xl font-bold mb-3">Sustainability</h3>
<p class="text-sm text-[#40493f]">Fostering growth that respects the land and future generations.</p>
</div>
<div class="p-8 bg-[#f8f3ea] rounded-[1.5rem] text-center hover:bg-[#ece8df] transition-colors">
<span class="material-symbols-outlined text-[#16642e] text-4xl mb-6">handshake</span>
<h3 class="text-xl font-bold mb-3">Empowerment</h3>
<p class="text-sm text-[#40493f]">Providing the tools for farmers to take control of their economic destiny.</p>
</div>
</div>
</section>

<!-- Approach -->
<section class="max-w-7xl mx-auto px-8 py-24 grid md:grid-cols-2 gap-20 items-center">
<div class="order-2 md:order-1 relative">
<div class="absolute -top-10 -left-10 w-40 h-40 bg-[#fdd97b] rounded-full opacity-20 blur-3xl"></div>
<img class="relative rounded-[2rem] atmospheric-shadow" alt="smartphone in field" src="https://images.pexels.com/photos/36470055/pexels-photo-36470055/free-photo-of-selection-of-products-in-shop.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
</div>
<div class="order-1 md:order-2 space-y-8">
<span class="bg-[#fdd97b] text-[#785d06] px-4 py-1.5 rounded-full text-[0.65rem] font-bold uppercase tracking-widest">Our Approach</span>
<h2 class="text-4xl font-bold leading-tight">Revolutionizing Trade Through Direct Connections</h2>
<div class="space-y-6">
<div class="flex gap-4">
<div class="w-12 h-12 shrink-0 bg-[#e7e2d9] rounded-xl flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e]">bolt</span>
</div>
<div>
<h4 class="font-bold">Faster Exchange</h4>
<p class="text-[#40493f] text-sm">Real-time matching algorithms reduce time-to-market by 40%.</p>
</div>
</div>
<div class="flex gap-4">
<div class="w-12 h-12 shrink-0 bg-[#e7e2d9] rounded-xl flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e]">shield</span>
</div>
<div>
<h4 class="font-bold">Safer Transactions</h4>
<p class="text-[#40493f] text-sm">End-to-end encryption and verified seller profiles ensure peace of mind.</p>
</div>
</div>
<div class="flex gap-4">
<div class="w-12 h-12 shrink-0 bg-[#e7e2d9] rounded-xl flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e]">public</span>
</div>
<div>
<h4 class="font-bold">Total Accessibility</h4>
<p class="text-[#40493f] text-sm">Optimized for low-bandwidth environments to reach remote districts.</p>
</div>
</div>
</div>
</div>
</section>

<!-- Meet the Team -->
<section class="max-w-7xl mx-auto px-8 py-24 mb-20">
<div class="text-center mb-16">
<h2 class="text-4xl font-bold mb-4">Meet the Visionaries</h2>
<p class="text-[#40493f]">The minds behind the agricultural revolution in Malawi.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
<!-- Team Card 1 - CEO -->
<div class="bg-white p-6 rounded-[1.5rem] atmospheric-shadow text-center group border border-transparent hover:border-[#bfc9bc]/20 transition-all">
<div class="relative w-32 h-32 mx-auto mb-4 bg-[#e7e2d9] rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e] text-5xl">person</span>
</div>
<h3 class="text-lg font-bold">Owen Gerald</h3>
<p class="text-[#16642e] font-semibold text-xs mb-3">CEO</p>
<p class="text-xs text-[#40493f] leading-relaxed px-2">An accountant with deep knowledge of sales and marketing, driving strategic growth and financial excellence.</p>
</div>
<!-- Team Card 2 - Technical Head -->
<div class="bg-white p-6 rounded-[1.5rem] atmospheric-shadow text-center group border border-transparent hover:border-[#bfc9bc]/20 transition-all">
<div class="relative w-32 h-32 mx-auto mb-4 bg-[#e7e2d9] rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e] text-5xl">person</span>
</div>
<h3 class="text-lg font-bold">Kelvin Nthinda</h3>
<p class="text-[#16642e] font-semibold text-xs mb-3">Technical Expert Head</p>
<p class="text-xs text-[#40493f] leading-relaxed px-2">Technical expert leading the development of innovative solutions for the agricultural marketplace.</p>
</div>
<!-- Team Card 3 - COO -->
<div class="bg-white p-6 rounded-[1.5rem] atmospheric-shadow text-center group border border-transparent hover:border-[#bfc9bc]/20 transition-all">
<div class="relative w-32 h-32 mx-auto mb-4 bg-[#e7e2d9] rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e] text-5xl">person</span>
</div>
<h3 class="text-lg font-bold">Zinyengo Nkandawire</h3>
<p class="text-[#16642e] font-semibold text-xs mb-3">COO</p>
<p class="text-xs text-[#40493f] leading-relaxed px-2">Streamlining operations and ensuring every connection on Ulimi translates to real impact.</p>
</div>
<!-- Team Card 4 - Marketing Professional -->
<div class="bg-white p-6 rounded-[1.5rem] atmospheric-shadow text-center group border border-transparent hover:border-[#bfc9bc]/20 transition-all">
<div class="relative w-32 h-32 mx-auto mb-4 bg-[#e7e2d9] rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e] text-5xl">person</span>
</div>
<h3 class="text-lg font-bold">Mbayomo</h3>
<p class="text-[#16642e] font-semibold text-xs mb-3">Marketing Professional</p>
<p class="text-xs text-[#40493f] leading-relaxed px-2">Marketing expert driving brand awareness and customer engagement across Malawi's agricultural sector.</p>
</div>
<!-- Team Card 5 - Accounting Professional -->
<div class="bg-white p-6 rounded-[1.5rem] atmospheric-shadow text-center group border border-transparent hover:border-[#bfc9bc]/20 transition-all">
<div class="relative w-32 h-32 mx-auto mb-4 bg-[#e7e2d9] rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-[#16642e] text-5xl">person</span>
</div>
<h3 class="text-lg font-bold">Atusaye</h3>
<p class="text-[#16642e] font-semibold text-xs mb-3">Accounting Professional</p>
<p class="text-xs text-[#40493f] leading-relaxed px-2">Accounting professional ensuring financial accuracy and compliance for all marketplace transactions.</p>
</div>
</div>
</section>
</main>

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

<?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
