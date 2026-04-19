<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Customer Support', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    body { 
      font-family: 'Manrope', sans-serif;
      background-color: #f2ede4;
      background-image: 
        radial-gradient(at 0% 0%, rgba(52, 126, 68, 0.03) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(190, 158, 71, 0.05) 0px, transparent 50%);
    }
    
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .atmospheric-shadow {
      box-shadow: 0px 12px 32px rgba(26, 61, 34, 0.06);
    }

    .primary-gradient {
      background: linear-gradient(135deg, #16642e 0%, #347e44 100%);
    }

    .scroll-animate {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s ease-out;
    }

    .scroll-animate.animate-in {
      opacity: 1;
      transform: translateY(0);
    }

    .faq-answer {
      display: none;
    }

    .faq-answer.show {
      display: block;
    }

    .faq-toggle.active + .faq-answer {
      display: block;
    }
  </style>
</head>
<body class="bg-[#f2ede4] text-[#1d1c16]">

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
<?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

<main class="">
<!-- Hero Section -->
<section class="relative min-h-[60vh] flex flex-col items-center justify-center px-6 py-8 overflow-hidden">
<div class="absolute inset-0 -z-10 opacity-5 bg-[#347e44]"></div>
<div class="max-w-4xl w-full text-center space-y-10 relative">
<h1 class="text-5xl md:text-7xl font-bold text-[#1d1c16] leading-[1.1]">
  How can we nurture <br/><span class="text-[#347e44] italic">your growth?</span>
</h1>
<p class="text-xl text-[#40493f] max-w-2xl mx-auto font-medium">
  Access agricultural knowledge base, community wisdom, and direct expert support.
</p>
<div class="relative max-w-2xl mx-auto">
<div class="absolute inset-y-0 left-7 flex items-center pointer-events-none text-[#be9e47]">
  <span class="material-symbols-outlined text-2xl">search</span>
</div>
<input class="w-full pl-16 pr-8 py-6 bg-white/80 backdrop-blur-md rounded-2xl border border-[#f2ede4] shadow-xl focus:ring-2 focus:ring-[#347e44]/20 focus:border-[#347e44] outline-none text-lg transition-all" placeholder="Search for guides, topics, or FAQs..." type="text" id="supportSearch">
<button onclick="performSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 primary-gradient text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition-opacity">Search</button>
</div>
<div class="flex gap-4 justify-center flex-wrap">
<a href="#faq" class="primary-gradient text-white px-8 py-4 rounded-full font-semibold text-lg atmospheric-shadow hover:opacity-90 transition-opacity">Browse FAQs</a>
<a href="#contact-form" class="bg-[#e7e2d9] text-[#1d1c16] px-8 py-4 rounded-full font-semibold text-lg hover:bg-[#ece8df] transition-colors">Contact Support</a>
</div>
</div>
</section>

<!-- Knowledge Base List -->
<section class="max-w-5xl mx-auto py-20 px-6">
<div class="space-y-4 mb-12 text-center">
<span class="text-xs font-bold uppercase tracking-[0.3em] text-[#be9e47]">Knowledge Base</span>
<h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16]">Browse by topic</h2>
</div>
<div class="grid gap-6">
<!-- Topic Item -->
<a class="group glass-card flex flex-col md:flex-row items-center justify-between p-6 md:p-8 rounded-3xl hover:bg-white/60 transition-all duration-500 border border-white/50 gap-6 md:gap-10" href="#knowledge-base">
<div class="flex items-center gap-6 md:gap-10 w-full md:w-auto">
<div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-[#347e44]/10 flex items-center justify-center text-[#347e44] group-hover:bg-[#347e44] group-hover:text-white transition-all duration-500 shrink-0">
  <span class="material-symbols-outlined text-3xl md:text-4xl">auto_stories</span>
</div>
<div>
  <h3 class="text-xl md:text-2xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">Step-by-Step Tutorials</h3>
  <p class="text-[#40493f]/60 mt-2 text-base md:text-lg max-w-md">Foundational guides for setting up and thriving on the platform.</p>
</div>
</div>
<div class="w-12 h-12 rounded-full border border-[#be9e47]/30 flex items-center justify-center text-[#be9e47] group-hover:bg-[#be9e47] group-hover:text-white transition-all duration-500 shrink-0 self-end md:self-center">
  <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</div>
</a>
<!-- Topic Item -->
<a class="group glass-card flex flex-col md:flex-row items-center justify-between p-6 md:p-8 rounded-3xl hover:bg-white/60 transition-all duration-500 border border-white/50 gap-6 md:gap-10" href="#knowledge-base">
<div class="flex items-center gap-6 md:gap-10 w-full md:w-auto">
<div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-[#be9e47]/10 flex items-center justify-center text-[#be9e47] group-hover:bg-[#be9e47] group-hover:text-white transition-all duration-500 shrink-0">
  <span class="material-symbols-outlined text-3xl md:text-4xl">manage_accounts</span>
</div>
<div>
  <h3 class="text-xl md:text-2xl font-bold text-[#1d1c16] group-hover:text-[#be9e47] transition-colors">Account Management</h3>
  <p class="text-[#40493f]/60 mt-2 text-base md:text-lg max-w-md">Manage your profile, preferences, and seller certification status.</p>
</div>
</div>
<div class="w-12 h-12 rounded-full border border-[#be9e47]/30 flex items-center justify-center text-[#be9e47] group-hover:bg-[#be9e47] group-hover:text-white transition-all duration-500 shrink-0 self-end md:self-center">
  <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</div>
</a>
<!-- Topic Item -->
<a class="group glass-card flex flex-col md:flex-row items-center justify-between p-6 md:p-8 rounded-3xl hover:bg-white/60 transition-all duration-500 border border-white/50 gap-6 md:gap-10" href="#knowledge-base">
<div class="flex items-center gap-6 md:gap-10 w-full md:w-auto">
<div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-[#347e44]/10 flex items-center justify-center text-[#347e44] group-hover:bg-[#347e44] group-hover:text-white transition-all duration-500 shrink-0">
  <span class="material-symbols-outlined text-3xl md:text-4xl">shopping_cart</span>
</div>
<div>
  <h3 class="text-xl md:text-2xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">Buying & Selling</h3>
  <p class="text-[#40493f]/60 mt-2 text-base md:text-lg max-w-md">Secure transactions, shipping logistics, and marketplace best practices.</p>
</div>
</div>
<div class="w-12 h-12 rounded-full border border-[#be9e47]/30 flex items-center justify-center text-[#be9e47] group-hover:bg-[#be9e47] group-hover:text-white transition-all duration-500 shrink-0 self-end md:self-center">
  <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</div>
</a>
</div>
</section>

<!-- FAQ Section -->
<section class="py-20 relative overflow-hidden">
<div class="absolute inset-0 bg-white/30 backdrop-blur-sm -z-10"></div>
<div class="max-w-4xl mx-auto px-6 space-y-16">
<div class="text-center space-y-4">
<span class="text-xs font-bold uppercase tracking-[0.3em] text-[#347e44]">Common Inquiries</span>
<h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16]">Frequently asked questions</h2>
</div>
<div class="space-y-6">
<!-- FAQ Item -->
<div class="glass-card rounded-3xl overflow-hidden group hover:border-[#347e44]/30 transition-all duration-300">
  <button class="w-full px-10 py-8 text-left flex justify-between items-center group faq-toggle" onclick="toggleFAQ(this)">
    <span class="text-xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">How do I reset my password?</span>
    <span class="material-symbols-outlined text-[#be9e47] group-hover:rotate-180 transition-transform duration-500">expand_more</span>
  </button>
  <div class="px-10 pb-10 text-[#40493f]/70 leading-relaxed text-lg faq-answer">
    To reset your password, click the 'Forgot Password' link on the login page. Enter your email address and follow the instructions sent to your email to create a new password.
  </div>
</div>
<!-- FAQ Item -->
<div class="glass-card rounded-3xl overflow-hidden group hover:border-[#347e44]/30 transition-all duration-300">
  <button class="w-full px-10 py-8 text-left flex justify-between items-center group faq-toggle" onclick="toggleFAQ(this)">
    <span class="text-xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">How do I update my profile information?</span>
    <span class="material-symbols-outlined text-[#be9e47] group-hover:rotate-180 transition-transform duration-500">expand_more</span>
  </button>
  <div class="px-10 pb-10 text-[#40493f]/70 leading-relaxed text-lg faq-answer">
    Log in to your account and navigate to the Dashboard. Click on 'Profile Settings' where you can update your personal information, contact details, and preferences.
  </div>
</div>
<!-- FAQ Item -->
<div class="glass-card rounded-3xl overflow-hidden group hover:border-[#347e44]/30 transition-all duration-300">
  <button class="w-full px-10 py-8 text-left flex justify-between items-center group faq-toggle" onclick="toggleFAQ(this)">
    <span class="text-xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">What payment methods are accepted?</span>
    <span class="material-symbols-outlined text-[#be9e47] group-hover:rotate-180 transition-transform duration-500">expand_more</span>
  </button>
  <div class="px-10 pb-10 text-[#40493f]/70 leading-relaxed text-lg faq-answer">
    We accept various payment methods including credit/debit cards, mobile money, and bank transfers. Available options may vary based on your location.
  </div>
</div>
<!-- FAQ Item -->
<div class="glass-card rounded-3xl overflow-hidden group hover:border-[#347e44]/30 transition-all duration-300">
  <button class="w-full px-10 py-8 text-left flex justify-between items-center group faq-toggle" onclick="toggleFAQ(this)">
    <span class="text-xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">How do I track my order?</span>
    <span class="material-symbols-outlined text-[#be9e47] group-hover:rotate-180 transition-transform duration-500">expand_more</span>
  </button>
  <div class="px-10 pb-10 text-[#40493f]/70 leading-relaxed text-lg faq-answer">
    Once your order is confirmed, you'll receive a tracking number via email. Use this number in the 'Track Order' section of your dashboard to monitor delivery status.
  </div>
</div>
<!-- FAQ Item -->
<div class="glass-card rounded-3xl overflow-hidden group hover:border-[#347e44]/30 transition-all duration-300">
  <button class="w-full px-10 py-8 text-left flex justify-between items-center group faq-toggle" onclick="toggleFAQ(this)">
    <span class="text-xl font-bold text-[#1d1c16] group-hover:text-[#347e44] transition-colors">The website is loading slowly</span>
    <span class="material-symbols-outlined text-[#be9e47] group-hover:rotate-180 transition-transform duration-500">expand_more</span>
  </button>
  <div class="px-10 pb-10 text-[#40493f]/70 leading-relaxed text-lg faq-answer">
    Try clearing your browser cache and cookies, or try using a different browser. If the problem continues, check your internet connection or contact our technical team.
  </div>
</div>
</div>

<div class="relative max-w-2xl mx-auto mt-12">
<div class="absolute inset-y-0 left-7 flex items-center pointer-events-none text-[#be9e47]">
  <span class="material-symbols-outlined text-2xl">search</span>
</div>
<input class="w-full pl-16 pr-8 py-4 bg-white/80 backdrop-blur-md rounded-2xl border border-[#f2ede4] focus:ring-2 focus:ring-[#347e44]/20 focus:border-[#347e44] outline-none text-lg transition-all" placeholder="Search FAQs..." type="text" id="faqSearch" onkeyup="searchFAQs()">
</div>
</div>
</section>

<!-- BotPenguin AI Assistant -->
<script id="messenger-widget-b" src="https://cdn.botpenguin.com/website-bot.js" defer>69e2c2af977c2b308de622f9,69e2c282d265c6bc6827522b</script>

<!-- Contact Section -->
<section class="py-20 px-6" id="contact-form">
<div class="max-w-6xl mx-auto">
<div class="grid md:grid-cols-2 gap-20 items-center">
<div class="space-y-10">
<div class="space-y-4">
  <span class="text-xs font-bold uppercase tracking-[0.3em] text-[#be9e47]">Direct Support</span>
  <h2 class="text-5xl font-bold text-[#1d1c16] leading-tight">Still looking for <br/>agricultural help?</h2>
</div>
<p class="text-[#40493f]/70 text-xl font-medium">Our expert team is available Monday through Friday to assist with complex inquiries or order issues.</p>
<div class="space-y-6 pt-4">
  <div class="flex items-center gap-6 group">
    <div class="w-12 h-12 rounded-full bg-[#347e44]/10 flex items-center justify-center text-[#347e44] group-hover:scale-110 transition-transform">
      <span class="material-symbols-outlined">mail</span>
    </div>
    <span class="text-lg font-bold text-[#1d1c16]">support@ulimi.mw</span>
  </div>
  <div class="flex items-center gap-6 group">
    <div class="w-12 h-12 rounded-full bg-[#be9e47]/10 flex items-center justify-center text-[#be9e47] group-hover:scale-110 transition-transform">
      <span class="material-symbols-outlined">phone</span>
    </div>
    <span class="text-lg font-bold text-[#1d1c16]">+265 999 137 598</span>
  </div>
  <div class="flex items-center gap-6 group">
    <div class="w-12 h-12 rounded-full bg-[#347e44]/10 flex items-center justify-center text-[#347e44] group-hover:scale-110 transition-transform">
      <span class="material-symbols-outlined">chat</span>
    </div>
    <span class="text-lg font-bold text-[#1d1c16]">Live Chat (9AM - 6PM CAT)</span>
  </div>
</div>
</div>
<div class="relative group aspect-square rounded-[3rem] overflow-hidden atmospheric-shadow">
<img alt="Agricultural support desk" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" src="https://images.pexels.com/photos/33364793/pexels-photo-33364793/free-photo-of-vibrant-local-market-with-fresh-vegetables.jpeg?auto=compress&cs=tinysrgb&w=1260&h=1575&dpr=1">
<div class="absolute inset-0 bg-[#347e44]/20 mix-blend-multiply opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
</div>
</div>
</div>
</section>

<!-- Knowledge Base -->
<section class="py-20 px-6" id="knowledge-base">
<div class="max-w-5xl mx-auto">
<div class="space-y-4 mb-12 text-center">
<span class="text-xs font-bold uppercase tracking-[0.3em] text-[#be9e47]">Knowledge Base</span>
<h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16]">Detailed Guides</h2>
</div>

<div class="relative max-w-2xl mx-auto mb-12">
<div class="absolute inset-y-0 left-7 flex items-center pointer-events-none text-[#be9e47]">
  <span class="material-symbols-outlined text-2xl">search</span>
</div>
<input class="w-full pl-16 pr-8 py-4 bg-white/80 backdrop-blur-md rounded-2xl border border-[#f2ede4] focus:ring-2 focus:ring-[#347e44]/20 focus:border-[#347e44] outline-none text-lg transition-all" placeholder="Search Knowledge Base..." type="text" id="kbSearch" onkeyup="searchKB()">
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="glass-card p-8 rounded-3xl hover:bg-white/60 transition-all duration-300 border border-white/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-6">Platform Tutorials</h3>
  <ul class="space-y-3">
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">How to Register</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">How to Upload Listings</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Navigating the Dashboard</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Managing Your Profile</a></li>
  </ul>
</div>
<div class="glass-card p-8 rounded-3xl hover:bg-white/60 transition-all duration-300 border border-white/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-6">Account Management</h3>
  <ul class="space-y-3">
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">How to Update Profile</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Password Recovery</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Setting Up Notifications</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Privacy Settings</a></li>
  </ul>
</div>
<div class="glass-card p-8 rounded-3xl hover:bg-white/60 transition-all duration-300 border border-white/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-6">Buying & Selling</h3>
  <ul class="space-y-3">
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">How to Place an Order</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Creating Product Listings</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Understanding Payment Methods</a></li>
    <li><a href="#" class="text-[#347e44] font-medium hover:underline">Shipping and Delivery</a></li>
  </ul>
</div>
</div>
</div>
</section>

<!-- Community Forum -->
<section class="py-20 px-6 bg-white/40 backdrop-blur-sm border-y border-[#f2ede4]/50">
<div class="max-w-5xl mx-auto text-center space-y-12">
<div class="space-y-4">
<span class="text-xs font-bold uppercase tracking-[0.3em] text-[#347e44]">Community</span>
<h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16]">Join the Community</h2>
<p class="text-[#40493f]/70 text-xl">Need help from fellow traders or farmers? Join the Ulimi community forum to discuss issues, share tips, and collaborate.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="glass-card p-8 rounded-3xl text-center hover:bg-white/60 transition-all duration-300 border border-white/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-4">Trading Advice</h3>
  <a href="#" class="inline-flex items-center gap-2 text-[#347e44] font-semibold hover:underline">
    <span>Join the Discussion</span>
    <span class="material-symbols-outlined text-sm">arrow_forward</span>
  </a>
</div>
<div class="glass-card p-8 rounded-3xl text-center hover:bg-white/60 transition-all duration-300 border border-white/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-4">Agri-Tech Tools</h3>
  <a href="#" class="inline-flex items-center gap-2 text-[#347e44] font-semibold hover:underline">
    <span>Join the Discussion</span>
    <span class="material-symbols-outlined text-sm">arrow_forward</span>
  </a>
</div>
<div class="glass-card p-8 rounded-3xl text-center hover:bg-white/60 transition-all duration-300 border border-white/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-4">Market Insights</h3>
  <a href="#" class="inline-flex items-center gap-2 text-[#347e44] font-semibold hover:underline">
    <span>Join the Discussion</span>
    <span class="material-symbols-outlined text-sm">arrow_forward</span>
  </a>
</div>
</div>
</div>
</section>

<!-- System Status -->
<section class="py-20 px-6">
<div class="max-w-4xl mx-auto text-center space-y-12">
<div class="space-y-4">
<span class="text-xs font-bold uppercase tracking-[0.3em] text-[#be9e47]">System Status</span>
<h2 class="text-4xl md:text-5xl font-bold text-[#1d1c16]">All Systems Operational</h2>
</div>
<div class="inline-flex items-center gap-4 px-8 py-4 bg-[#347e44]/10 rounded-full">
  <div class="w-4 h-4 rounded-full bg-[#28a745] animate-pulse"></div>
  <span class="text-lg font-semibold text-[#1d1c16]">All services running normally</span>
</div>
<div class="max-w-2xl mx-auto text-left bg-white/40 backdrop-blur-sm rounded-3xl p-8 border border-[#f2ede4]/50">
  <h3 class="text-xl font-bold text-[#1d1c16] mb-6">Recent Updates</h3>
  <ul class="space-y-4 text-[#40493f]/70">
    <li class="pb-4 border-b border-[#f2ede4]/50">System upgrade completed on April 5th, 2026</li>
    <li class="pb-4 border-b border-[#f2ede4]/50">New features added to the dashboard on April 3rd, 2026</li>
    <li>Performance improvements deployed on April 1st, 2026</li>
  </ul>
</div>
</div>
</section>

<!-- Feedback Section -->
<section class="py-20 px-6 bg-white/40 backdrop-blur-sm border-y border-[#f2ede4]/50">
<div class="max-w-2xl mx-auto text-center space-y-12">
<div class="space-y-4">
<h3 class="text-4xl font-bold text-[#1d1c16]">Was this helpful?</h3>
<p class="text-[#40493f]/70 text-lg">We are constantly refining our platform.</p>
</div>
<form class="space-y-10" id="feedbackForm">
  <div class="flex justify-center gap-8">
    <button class="w-16 h-16 rounded-2xl bg-white border border-[#f2ede4] text-[#40493f]/50 hover:border-[#347e44] hover:text-[#347e44] hover:shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center" type="button">
      <span class="material-symbols-outlined text-3xl">sentiment_very_satisfied</span>
    </button>
    <button class="w-16 h-16 rounded-2xl bg-white border border-[#f2ede4] text-[#40493f]/50 hover:border-[#be9e47] hover:text-[#be9e47] hover:shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center" type="button">
      <span class="material-symbols-outlined text-3xl">sentiment_neutral</span>
    </button>
    <button class="w-16 h-16 rounded-2xl bg-white border border-[#f2ede4] text-[#40493f]/50 hover:border-red-400 hover:text-red-400 hover:shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center" type="button">
      <span class="material-symbols-outlined text-3xl">sentiment_very_dissatisfied</span>
    </button>
  </div>
  <div class="space-y-6 text-left">
    <div>
      <label class="block mb-2 font-semibold text-[#1d1c16]">Your Name</label>
      <input type="text" class="w-full p-4 bg-white rounded-2xl border border-[#f2ede4] focus:ring-4 focus:ring-[#347e44]/5 focus:border-[#347e44] outline-none text-lg transition-all" placeholder="Enter your name" id="feedback-name" name="feedback_name" required>
    </div>
    <div>
      <label class="block mb-2 font-semibold text-[#1d1c16]">Email Address</label>
      <input type="email" class="w-full p-4 bg-white rounded-2xl border border-[#f2ede4] focus:ring-4 focus:ring-[#347e44]/5 focus:border-[#347e44] outline-none text-lg transition-all" placeholder="Enter your email" id="feedback-email" name="feedback_email" required>
    </div>
    <div>
      <label class="block mb-2 font-semibold text-[#1d1c16]">Feedback Category</label>
      <select class="w-full p-4 bg-white rounded-2xl border border-[#f2ede4] focus:ring-4 focus:ring-[#347e44]/5 focus:border-[#347e44] outline-none text-lg transition-all" id="feedback-category" name="feedback_category" required>
        <option value="">Select a category</option>
        <option value="feature-request">Feature Request</option>
        <option value="bug-report">Bug Report</option>
        <option value="general-feedback">General Feedback</option>
      </select>
    </div>
    <div>
      <label class="block mb-2 font-semibold text-[#1d1c16]">Your Message</label>
      <textarea class="w-full p-8 bg-white rounded-3xl border border-[#f2ede4] focus:ring-4 focus:ring-[#347e44]/5 focus:border-[#347e44] outline-none text-lg transition-all" placeholder="Any additional thoughts or suggestions?" rows="4" id="feedback-message" name="feedback_message" required></textarea>
    </div>
  </div>
  <button class="w-full py-5 primary-gradient text-white rounded-2xl font-bold text-lg hover:shadow-2xl hover:opacity-90 transition-all active:scale-[0.98]" type="submit">
    Send Feedback
  </button>
</form>
</div>
</section>
</main>

<script>
  // FAQ Toggle
  function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const isActive = button.classList.contains('active');
    
    // Close all FAQs
    document.querySelectorAll('.faq-toggle').forEach(btn => {
      btn.classList.remove('active');
      btn.nextElementSibling.classList.remove('show');
    });
    
    // Open clicked FAQ if it wasn't active
    if (!isActive) {
      button.classList.add('active');
      answer.classList.add('show');
    }
  }

  // FAQ Search
  function searchFAQs() {
    const searchTerm = document.getElementById('faqSearch').value.toLowerCase();
    const faqItems = document.querySelectorAll('.glass-card');
    
    faqItems.forEach(item => {
      const question = item.querySelector('.faq-toggle')?.textContent.toLowerCase() || '';
      const answer = item.querySelector('.faq-answer')?.textContent.toLowerCase() || '';
      
      if (question.includes(searchTerm) || answer.includes(searchTerm)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }

  // Knowledge Base Search
  function searchKB() {
    const searchTerm = document.getElementById('kbSearch').value.toLowerCase();
    const categories = document.querySelectorAll('#knowledge-base .glass-card');
    
    categories.forEach(category => {
      const links = category.querySelectorAll('a');
      let hasMatch = false;
      
      links.forEach(link => {
        if (link.textContent.toLowerCase().includes(searchTerm)) {
          hasMatch = true;
        }
      });
      
      category.style.display = hasMatch || searchTerm === '' ? 'block' : 'none';
    });
  }

  // Support Search
  function performSearch() {
    const searchTerm = document.getElementById('supportSearch').value.toLowerCase();
    document.getElementById('faq').scrollIntoView({ behavior: 'smooth' });
    document.getElementById('faqSearch').value = searchTerm;
    searchFAQs();
  }

  // Feedback Form
  document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you for your feedback! We appreciate your input and will use it to improve our services.');
    this.reset();
  });

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

  // Scroll Animation Observer
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

  // Observe all scroll-animate elements
  document.querySelectorAll('.scroll-animate').forEach(el => {
    observer.observe(el);
  });

  // Trigger initial animations for elements already in view
  window.addEventListener('load', () => {
    document.querySelectorAll('.scroll-animate').forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight) {
        el.classList.add('animate-in');
      }
    });
  });
</script>

<?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
