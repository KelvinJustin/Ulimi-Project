<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Seller Profile', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="icon" type="image/png" href="/logo.png">
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
<body class="bg-gray-100 text-gray-900">
  <?php
  // Hardcoded industry metrics (not in schema)
  $responseRate = 95;
  $responseTime = '2 hours';
  $onTimeDelivery = 92;
  $fulfillmentRate = 98;
  ?>

  <!-- Profile Picture and Info -->
  <div class="px-4 sm:px-6 lg:px-8 relative pt-8" style="background-color: #347e44; padding-bottom: 32px;">
    <div class="flex items-center gap-4 mb-4">
      <!-- Profile Picture -->
      <div class="w-20 h-20 rounded-full border-4 border-white bg-white overflow-hidden shrink-0">
        <?php if (!empty($seller['avatar_path'])): ?>
          <img src="/<?= htmlspecialchars($seller['avatar_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($seller['display_name'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <div class="w-full h-full bg-green-700 flex items-center justify-center text-white text-2xl font-bold">
            <?= htmlspecialchars(substr(ucfirst($seller['display_name'] ?? $seller['seller_email']), 0, 1), ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Seller Name and Email -->
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars(ucfirst($seller['display_name'] ?? 'Unknown Seller'), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-white/80">@<?= htmlspecialchars(explode('@', $seller['seller_email'])[0], ENT_QUOTES, 'UTF-8') ?></p>
      </div>

      <!-- Message Button -->
      <button onclick="openChatPanel()" class="px-4 py-2 bg-white text-olive rounded font-medium shrink-0">
        <i class="fa fa-envelope mr-2"></i>Message
      </button>
    </div>

    <!-- Stats -->
    <div class="flex items-center gap-6 text-base text-white mb-6">
      <div class="flex items-center gap-2">
        <i class="fa fa-star text-white"></i>
        <span class="font-head text-white font-bold"><?= number_format($seller['rating_avg'] ?? 0, 1) ?></span>
        <span class="text-white">Rating</span>
      </div>
      <div class="flex items-center gap-2">
        <i class="fa fa-shopping-cart text-white"></i>
        <span class="font-head text-white font-bold"><?= $completedOrders ?></span>
        <span class="text-white">Orders</span>
      </div>
      <div class="flex items-center gap-2">
        <i class="fa fa-calendar text-white"></i>
        <span class="text-white">Joined <?= date('F Y', strtotime($seller['user_created_at'] ?? 'now')) ?></span>
      </div>
      <div class="flex items-center gap-2">
        <i class="fa fa-map-marker text-white"></i>
        <span class="text-white"><?= htmlspecialchars(ucfirst($seller['city'] ?? $seller['district'] ?? $seller['region'] ?? 'Malawi'), ENT_QUOTES, 'UTF-8') ?></span>
      </div>
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
      <!-- Left Column -->
      <div class="lg:col-span-2 space-y-8">
        <!-- About Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
          <h2 class="text-xl font-bold text-gray-900 mb-4">About</h2>
          <?php if (!empty($seller['business_name'])): ?>
            <p class="font-semibold text-gray-900 mb-4 text-lg"><?= htmlspecialchars($seller['business_name'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
          <p class="text-gray-700 leading-relaxed">
            <?= nl2br(htmlspecialchars($seller['bio'] ?? 'This seller has not added a bio yet.', ENT_QUOTES, 'UTF-8')) ?>
          </p>
        </div>

        <!-- Listings Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Products</h2>
            <span class="text-sm text-gray-500 font-medium"><?= count($listings) ?> items</span>
          </div>
          
          <?php if (empty($listings)): ?>
            <div class="text-center py-12 text-gray-400">
              <i class="fa fa-box text-4xl mb-3"></i>
              <p>No active listings</p>
            </div>
          <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
              <?php foreach ($listings as $listing): ?>
                <a href="<?= $base ?>/browse/<?= $listing['id'] ?>" class="block group">
                  <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="aspect-square bg-gray-100 relative">
                      <?php if (!empty($listing['image_path'])): ?>
                        <img src="/<?= htmlspecialchars($listing['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                             alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#f5f5f5'; this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#999;\'>No image</div>'">
                      <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                          <i class="fa fa-image text-gray-300 text-3xl"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="p-4">
                      <div class="text-xs text-green-600 font-semibold mb-1 uppercase tracking-wide"><?= htmlspecialchars($listing['commodity_name'] ?? 'Product', ENT_QUOTES, 'UTF-8') ?></div>
                      <h3 class="text-sm font-semibold text-gray-900 truncate mb-2"><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                      <div class="flex items-center gap-1 mb-2">
                        <div class="text-yellow-400 text-xs">
                          <?php for($i = 0; $i < 5; $i++): ?>
                            <i class="fa<?= $i < floor($listing['rating_avg'] ?? 0) ? 's' : 'r' ?> fa-star"></i>
                          <?php endfor; ?>
                        </div>
                        <span class="text-xs text-gray-500">(<?= number_format($listing['rating_avg'] ?? 0, 1) ?> reviews)</span>
                      </div>
                      <div class="flex items-baseline gap-1 mb-2">
                        <span class="text-base font-bold text-green-700">MWK <?= number_format($listing['price'], 2) ?></span>
                        <span class="text-xs text-gray-600">/<?= htmlspecialchars($listing['commodity_unit'] ?? 'unit', ENT_QUOTES, 'UTF-8') ?></span>
                      </div>
                      <div class="flex items-center gap-1 text-xs text-gray-600 mb-2">
                        <i class="fa fa-map-marker text-gray-400"></i>
                        <span><?= htmlspecialchars($listing['location'] ?? 'Malawi', ENT_QUOTES, 'UTF-8') ?></span>
                      </div>
                      <div class="flex flex-wrap gap-1.5">
                        <span class="text-xs px-2 py-1 bg-green-50 text-green-700 rounded-full">Good Condition</span>
                        <span class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-full">In Stock: <?= number_format($listing['quantity']) ?> <?= htmlspecialchars($listing['commodity_unit'] ?? 'unit', ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded-full">Available Now</span>
                      </div>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Reviews</h2>
          
          <?php if (($seller['rating_count'] ?? 0) === 0): ?>
            <div class="text-center py-12 text-gray-400">
              <i class="fa fa-comment-o text-4xl mb-3"></i>
              <p>No reviews yet</p>
            </div>
          <?php else: ?>
            <div class="space-y-6">
              <div class="border-b border-gray-200 pb-6">
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold shrink-0">J</div>
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="font-bold text-gray-900">John D.</span>
                      <span class="text-xs text-gray-400">2 weeks ago</span>
                    </div>
                    <div class="text-yellow-500 text-sm mb-3">
                      <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p class="text-gray-700 leading-relaxed">Great seller! Product was exactly as described and delivery was fast.</p>
                  </div>
                </div>
              </div>
              <div class="border-b border-gray-200 pb-6">
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 rounded-full bg-pink-500 flex items-center justify-center text-white font-bold shrink-0">S</div>
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="font-bold text-gray-900">Sarah M.</span>
                      <span class="text-xs text-gray-400">1 month ago</span>
                    </div>
                    <div class="text-yellow-500 text-sm mb-3">
                      <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
                    </div>
                    <p class="text-gray-700 leading-relaxed">Good quality product. Communication could be improved but overall satisfied.</p>
                  </div>
                </div>
              </div>
            </div>
            <button class="w-full mt-6 py-3 text-green-700 font-semibold hover:bg-green-50 rounded-lg">
              See all reviews
            </button>
          <?php endif; ?>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-8">
        <!-- Performance Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Performance</h2>
          <div class="space-y-4">
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600 font-medium">Response Rate</span>
                <span class="font-bold text-green-600"><?= $responseRate ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: <?= $responseRate ?>%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600 font-medium">On-time Delivery</span>
                <span class="font-bold text-green-600"><?= $onTimeDelivery ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-600 h-2.5 rounded-full" style="width: <?= $onTimeDelivery ?>%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600 font-medium">Cancellation Rate</span>
                <span class="font-bold <?= $cancellationRate < 10 ? 'text-green-600' : 'text-red-600' ?>"><?= $cancellationRate ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-<?= $cancellationRate < 10 ? 'green' : 'red' ?>-600 h-2.5 rounded-full" style="width: <?= $cancellationRate ?>%"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Contact</h2>
          <div class="space-y-3 text-sm">
            <div class="flex items-center gap-3 text-gray-600">
              <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="fa fa-clock text-green-600"></i>
              </div>
              <span class="font-medium">Response time: <?= $responseTime ?></span>
            </div>
            <div class="flex items-center gap-3 text-gray-600">
              <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="fa fa-globe text-green-600"></i>
              </div>
              <span class="font-medium">Languages: English, Chichewa</span>
            </div>
            <div class="flex items-center gap-3 text-gray-600">
              <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="fa fa-credit-card text-green-600"></i>
              </div>
              <span class="font-medium">Accepts: Mobile Money, Bank Transfer</span>
            </div>
          </div>
        </div>

        <!-- Policies -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Policies</h2>
          <div class="space-y-3 text-sm">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                <i class="fa fa-truck text-green-600"></i>
              </div>
              <div>
                <div class="font-semibold text-gray-900 mb-1">Shipping</div>
                <div class="text-gray-600">1-2 business days processing</div>
              </div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                <i class="fa fa-undo text-green-600"></i>
              </div>
              <div>
                <div class="font-semibold text-gray-900 mb-1">Returns</div>
                <div class="text-gray-600">7 days if not as described</div>
              </div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                <i class="fa fa-shield text-green-600"></i>
              </div>
              <div>
                <div class="font-semibold text-gray-900 mb-1">Warranty</div>
                <div class="text-gray-600">Quality guarantee on all products</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Social Links -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8 mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Connect</h2>
          <div class="flex gap-3">
            <a href="#" class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center">
              <i class="fa-brands fa-facebook"></i>
            </a>
            <a href="#" class="w-11 h-11 rounded-full bg-pink-600 text-white flex items-center justify-center">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="#" class="w-11 h-11 rounded-full bg-blue-400 text-white flex items-center justify-center">
              <i class="fa-brands fa-twitter"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chat Side Panel -->
  <div id="chatPanel" class="fixed inset-y-0 right-0 w-96 max-w-[90vw] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50" style="transform: translateX(100%); box-shadow: -4px 0 20px rgba(0,0,0,0.15);">
    <div class="h-full flex flex-col">
      <!-- Chat Header -->
      <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between" style="background-color: #347e44;">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-white overflow-hidden">
            <?php if (!empty($seller['avatar_path'])): ?>
              <img src="/<?= htmlspecialchars($seller['avatar_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($seller['display_name'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <div class="w-full h-full bg-green-700 flex items-center justify-center text-white text-lg font-bold">
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
      <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div id="chatMessages" class="space-y-4">
          <p class="text-gray-500 text-center py-4">Loading messages...</p>
        </div>
      </div>

      <!-- Chat Input -->
      <div class="px-6 py-4 border-t border-gray-200 bg-white">
        <div class="flex gap-3">
          <input type="text" id="chatInput" placeholder="Type a message..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" onkeypress="if(event.key === 'Enter') sendChatMessage()">
          <button onclick="sendChatMessage()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
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
