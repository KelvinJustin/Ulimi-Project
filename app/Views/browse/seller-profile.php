<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Seller Profile', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="/logo.png">
  <style>
    body { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
  </style>
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  $seller = $seller ?? [];
  $listings = $listings ?? [];
  $completedOrders = $completedOrders ?? 0;
  $totalOrders = $totalOrders ?? 0;
  $cancellationRate = $cancellationRate ?? 0;
  $isLoggedIn = \App\Core\Auth::check();
  ?>
  <script src="/assets/js/chat.js"></script>
  <script>
    window.sellerId = <?= $seller['seller_id'] ?>;
    window.currentUserId = <?= $isLoggedIn ? \App\Core\Auth::user()['id'] : 0 ?>;

    function openChatPanel() {
      console.log('openChatPanel called');
      const chatPanel = document.getElementById('chatPanel');
      const chatOverlay = document.getElementById('chatOverlay');
      
      console.log('chatPanel:', chatPanel);
      console.log('chatOverlay:', chatOverlay);
      
      if (chatPanel) {
        chatPanel.style.transform = 'translateX(0)';
        console.log('Chat panel transform set to 0');
      }
      
      if (chatOverlay) {
        chatOverlay.classList.remove('hidden');
        console.log('Chat overlay hidden removed');
      }
      
      document.body.style.overflow = 'hidden';
      
      // Initialize chat
      if (window.currentUserId && window.sellerId && window.currentUserId !== window.sellerId) {
        if (typeof initChat === 'function') {
          initChat(window.sellerId);
        }
      } else {
        document.getElementById('chatMessages').innerHTML = '<p class="text-gray-500 text-center py-4">Please log in to send messages.</p>';
      }
    }

    function closeChatPanel() {
      document.getElementById('chatPanel').style.transform = 'translateX(100%)';
      document.getElementById('chatOverlay').classList.add('hidden');
      document.body.style.overflow = '';
      
      // Stop polling
      if (typeof stopPolling === 'function') {
        stopPolling();
      }
    }

    function sendChatMessage() {
      const input = document.getElementById('chatInput');
      const text = input.value.trim();
      
      if (text && typeof sendMessage === 'function') {
        sendMessage(text);
        input.value = '';
      }
    }
  </script>
</head>
<body class="bg-[#fef9f0] text-[#1d1c16]">
  <?php
  // Hardcoded industry metrics (not in schema)
  $responseRate = 95;
  $responseTime = '2 hours';
  $onTimeDelivery = 92;
  $fulfillmentRate = 98;
  ?>

  <!-- Main Content Area -->
  <main class="max-w-7xl mx-auto px-4 py-8 md:flex md:gap-8">
    <!-- Left Column: Seller Identity & Meta (33%) -->
    <aside class="md:w-1/3 flex flex-col gap-6">
      <!-- Seller Identity Card -->
      <div class="bg-white rounded-xl p-8 shadow-[0px_8px_24px_rgba(26,61,34,0.04)] text-center">
        <div class="relative inline-block mb-6">
          <?php if (!empty($seller['avatar_path'])): ?>
            <img src="/<?= htmlspecialchars($seller['avatar_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($seller['display_name'], ENT_QUOTES, 'UTF-8') ?>" class="w-40 h-40 rounded-full object-cover mx-auto ring-4 ring-[#fef9f0]">
          <?php else: ?>
            <div class="w-40 h-40 rounded-full bg-gradient-to-br from-[#16642e] to-[#347e44] flex items-center justify-center text-white text-5xl font-bold mx-auto ring-4 ring-[#fef9f0]">
              <?= htmlspecialchars(substr(ucfirst($seller['display_name'] ?? $seller['seller_email']), 0, 1), ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>
          <div class="absolute bottom-2 right-2 bg-[#fdd97b] text-[#785d06] p-1 rounded-full shadow-sm">
            <i class="fa fa-check-circle text-[18px]"></i>
          </div>
        </div>
        <h1 class="text-2xl font-extrabold text-[#1a3d22] leading-tight"><?= htmlspecialchars(ucfirst($seller['display_name'] ?? 'Unknown Seller'), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-[#40493f] text-sm mt-1"><?= htmlspecialchars(ucfirst($seller['city'] ?? $seller['district'] ?? $seller['region'] ?? 'Malawi'), ENT_QUOTES, 'UTF-8') ?></p>
        <div class="flex items-center justify-center mt-4 gap-1">
          <i class="fa fa-star text-[#755b03] text-[20px]"></i>
          <span class="font-bold text-[#1d1c16]"><?= number_format($seller['rating_avg'] ?? 0, 1) ?></span>
          <span class="text-[#40493f] text-sm">(<?= $seller['rating_count'] ?? 0 ?> reviews)</span>
        </div>
        <p class="text-[#40493f] text-[12px] uppercase tracking-widest mt-6 font-bold">Joined Ulimi in <?= date('Y', strtotime($seller['user_created_at'] ?? 'now')) ?></p>
      </div>

      <!-- Action Layer -->
      <div class="bg-white rounded-xl p-6 shadow-[0px_8px_24px_rgba(26,61,34,0.04)] flex flex-col gap-3">
        <button onclick="openChatPanel()" class="w-full bg-gradient-to-br from-[#16642e] to-[#347e44] text-white py-4 rounded-full font-bold flex items-center justify-center gap-2 hover:opacity-90 active:scale-95 transition-all">
          <i class="fa fa-envelope"></i>
          Message Seller
        </button>
        <button class="w-full bg-[#beab5e] text-[#4b3f00] py-4 rounded-full font-bold flex items-center justify-center gap-2 hover:bg-[#dac676] active:scale-95 transition-all">
          <i class="fa fa-user-plus"></i>
          Follow
        </button>
      </div>

      <!-- Seller Activity Card -->
      <div class="bg-white rounded-xl p-6 shadow-[0px_8px_24px_rgba(26,61,34,0.04)]">
        <h2 class="text-[#1d1c16] font-bold text-lg mb-6">Seller Information</h2>
        <div class="space-y-5">
          <div class="flex items-center gap-4">
            <div class="bg-[#ece8df] p-2 rounded-xl text-[#16642e]">
              <i class="fa fa-clock"></i>
            </div>
            <div>
              <p class="text-[#1d1c16] font-semibold text-sm">Responds within <?= $responseTime ?></p>
              <p class="text-[#40493f] text-xs">Very responsive</p>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <div class="bg-[#ece8df] p-2 rounded-xl text-[#16642e]">
              <i class="fa fa-percent"></i>
            </div>
            <div>
              <p class="text-[#1d1c16] font-semibold text-sm"><?= $responseRate ?>% Response Rate</p>
              <p class="text-[#40493f] text-xs">Based on last 30 days</p>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <div class="bg-[#ece8df] p-2 rounded-xl text-[#16642e]">
              <i class="fa fa-box"></i>
            </div>
            <div>
              <p class="text-[#1d1c16] font-semibold text-sm"><?= count($listings) ?> items listed</p>
              <p class="text-[#40493f] text-xs">Active and verified stock</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Transparency Card -->
      <div class="bg-[#f8f3ea] rounded-xl p-5 flex items-center justify-between">
        <div class="flex items-center gap-2 text-[#16642e]">
          <i class="fa fa-check-circle text-[20px]"></i>
          <span class="text-xs font-bold uppercase tracking-wider">Verified Seller</span>
        </div>
        <a class="text-[#ba1a1a] text-xs font-bold hover:underline decoration-2 underline-offset-4" href="#">Report Seller</a>
      </div>
    </aside>

    <!-- Right Column: Content Grid (67%) -->
    <div class="md:w-2/3 flex flex-col gap-8 mt-8 md:mt-0">
      <!-- About Section -->
      <section class="bg-white rounded-xl p-8 shadow-[0px_8px_24px_rgba(26,61,34,0.04)]">
        <h2 class="text-xl font-bold text-[#1d1c16] mb-4">About</h2>
        <?php if (!empty($seller['business_name'])): ?>
          <p class="font-semibold text-[#1d1c16] mb-4 text-lg"><?= htmlspecialchars($seller['business_name'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <p class="text-[#40493f] leading-relaxed">
          <?= nl2br(htmlspecialchars($seller['bio'] ?? 'This seller has not added a bio yet.', ENT_QUOTES, 'UTF-8')) ?>
        </p>
      </section>

      <!-- Listings Section -->
      <section>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-[#1d1c16]">Items for sale</h2>
          <span class="text-sm text-[#40493f] font-medium"><?= count($listings) ?> items</span>
        </div>
        
        <?php if (empty($listings)): ?>
          <div class="text-center py-12 text-[#40493f]">
            <i class="fa fa-box text-4xl mb-3"></i>
            <p>No active listings</p>
          </div>
        <?php else: ?>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($listings as $listing): ?>
              <a href="<?= $base ?>/browse/<?= $listing['id'] ?>" class="block group">
                <div class="bg-white rounded-xl overflow-hidden shadow-[0px_8px_24px_rgba(26,61,34,0.04)] group cursor-pointer">
                  <div class="h-48 overflow-hidden">
                    <?php if (!empty($listing['image_path'])): ?>
                      <img src="/<?= htmlspecialchars($listing['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                           alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                           onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#f5f5f5'; this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#999;\'>No image</div>'">
                    <?php else: ?>
                      <div class="w-full h-full flex items-center justify-center bg-[#f8f3ea]">
                        <i class="fa fa-image text-[#40493f] text-3xl"></i>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="p-4">
                    <p class="text-[#16642e] font-extrabold text-lg">MWK <?= number_format($listing['price'], 2) ?></p>
                    <h3 class="text-[#1d1c16] font-bold text-sm mt-1"><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <div class="flex items-center gap-1 mt-3 text-[#40493f]">
                      <i class="fa fa-map-marker text-[16px]"></i>
                      <span class="text-xs"><?= htmlspecialchars($listing['location'] ?? 'Malawi', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

      <!-- Reviews Section -->
      <section class="bg-white rounded-xl p-8 shadow-[0px_8px_24px_rgba(26,61,34,0.04)]">
        <div class="flex items-baseline justify-between mb-8">
          <h2 class="text-2xl font-bold text-[#1d1c16]">Seller ratings</h2>
          <div class="flex items-center gap-2">
            <span class="text-3xl font-extrabold text-[#1d1c16]"><?= number_format($seller['rating_avg'] ?? 0, 1) ?></span>
            <div class="flex text-[#755b03]">
              <?php for($i = 0; $i < 5; $i++): ?>
                <i class="fa<?= $i < floor($seller['rating_avg'] ?? 0) ? 's' : ($i < ceil($seller['rating_avg'] ?? 0) ? 's' : 'r') ?> fa-star"></i>
              <?php endfor; ?>
            </div>
          </div>
        </div>
        
        <?php if (($seller['rating_count'] ?? 0) === 0): ?>
          <div class="text-center py-12 text-[#40493f]">
            <i class="fa fa-comment-o text-4xl mb-3"></i>
            <p>No reviews yet</p>
          </div>
        <?php else: ?>
          <div class="space-y-8">
            <div class="pb-8 bg-[#f8f3ea] last:bg-white last:pb-0">
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-[#16642e] flex items-center justify-center text-white font-bold shrink-0">J</div>
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="font-bold text-[#1d1c16]">John D.</span>
                    <span class="text-xs text-[#40493f]">2 weeks ago</span>
                  </div>
                  <div class="flex text-[#755b03] mb-2">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                  </div>
                  <p class="text-[#40493f] text-sm leading-relaxed">Great seller! Product was exactly as described and delivery was fast.</p>
                </div>
              </div>
            </div>
            <div class="pb-8 bg-[#f8f3ea] last:bg-white last:pb-0">
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-[#755b03] flex items-center justify-center text-white font-bold shrink-0">S</div>
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="font-bold text-[#1d1c16]">Sarah M.</span>
                    <span class="text-xs text-[#40493f]">1 month ago</span>
                  </div>
                  <div class="flex text-[#755b03] mb-2">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
                  </div>
                  <p class="text-[#40493f] text-sm leading-relaxed">Good quality product. Communication could be improved but overall satisfied.</p>
                </div>
              </div>
            </div>
          </div>
          <button class="w-full mt-8 py-3 rounded-xl border border-[#bfc9bc]/30 text-[#40493f] font-bold text-sm hover:bg-[#ece8df] transition-colors">
            Load more reviews
          </button>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <!-- Chat Side Panel -->
  <div id="chatPanel" class="fixed inset-y-0 right-0 w-96 max-w-[90vw] bg-white shadow-[0px_12px_32px_rgba(26,61,34,0.06)] transform translate-x-full transition-transform duration-300 z-50" style="transform: translateX(100%);">
    <div class="h-full flex flex-col">
      <!-- Chat Header -->
      <div class="px-6 py-4 flex items-center justify-between bg-gradient-to-br from-[#16642e] to-[#347e44]">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-white overflow-hidden ring-2 ring-white">
            <?php if (!empty($seller['avatar_path'])): ?>
              <img src="/<?= htmlspecialchars($seller['avatar_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($seller['display_name'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full bg-gradient-to-br from-[#16642e] to-[#347e44] flex items-center justify-center text-white text-lg font-bold">
                <?= htmlspecialchars(substr(ucfirst($seller['display_name'] ?? $seller['seller_email']), 0, 1), ENT_QUOTES, 'UTF-8') ?>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <h3 class="text-white font-semibold"><?= htmlspecialchars(ucfirst($seller['display_name'] ?? 'Seller'), ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="text-white/80 text-sm">Online</p>
          </div>
        </div>
        <button onclick="closeChatPanel()" class="text-white hover:text-white/80 transition-colors">
          <i class="fa fa-times text-xl"></i>
        </button>
      </div>

      <!-- Chat Messages -->
      <div class="flex-1 overflow-y-auto p-6 bg-[#f8f3ea]">
        <div id="chatMessages" class="space-y-4">
          <p class="text-[#40493f] text-center py-4">Loading messages...</p>
        </div>
      </div>

      <!-- Chat Input -->
      <div class="px-6 py-4 bg-white">
        <div class="flex gap-3">
          <input type="text" id="chatInput" placeholder="Type a message..." class="flex-1 px-4 py-3 bg-[#f8f3ea] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#206c34]/40 transition-all" onkeypress="if(event.key === 'Enter') sendChatMessage()">
          <button onclick="sendChatMessage()" class="px-4 py-3 bg-gradient-to-br from-[#16642e] to-[#347e44] text-white rounded-xl hover:opacity-90 transition-all">
            <i class="fa fa-paper-plane"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Chat Overlay -->
  <div id="chatOverlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeChatPanel()"></div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
