<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
$isLoggedIn = \App\Core\Auth::check();
$user = \App\Core\Auth::user();
$userId = $isLoggedIn && $user ? $user['id'] : 'guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Listing Details', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="bg-gray-50 min-h-screen">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm text-gray-600">
      <a href="<?= $base ?>/browse" class="hover:text-green-600">Browse</a>
      <span class="mx-2">/</span>
      <span class="text-gray-900"><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 lg:p-8">
        <!-- Image Gallery -->
        <div class="space-y-4">
          <?php if (!empty($listing['image_path'])): ?>
            <?php
            $mainImage = $listing['image_path'];
            // Ensure path starts with /
            if (strpos($mainImage, '/') !== 0) {
                $mainImage = '/' . $mainImage;
            }
            ?>
            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-xl overflow-hidden">
              <img id="main-image"
                   src="<?= htmlspecialchars($mainImage, ENT_QUOTES, 'UTF-8') ?>"
                   alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>"
                   class="w-full h-96 object-cover"
                   onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#f5f5f5'; this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#999;\'>No image</div>'">
            </div>
            <?php if (count($images) > 1): ?>
              <div class="grid grid-cols-4 gap-2">
                <?php foreach ($images as $index => $image): ?>
                  <?php
                  $thumbImage = $image;
                  if (strpos($thumbImage, '/') !== 0) {
                      $thumbImage = '/' . $thumbImage;
                  }
                  ?>
                  <button onclick="changeImage('<?= htmlspecialchars($thumbImage, ENT_QUOTES, 'UTF-8') ?>')"
                          class="aspect-square bg-gray-100 rounded-lg overflow-hidden hover:ring-2 hover:ring-green-500 transition-all">
                    <img src="<?= htmlspecialchars($thumbImage, ENT_QUOTES, 'UTF-8') ?>"
                         alt="Image <?= $index + 1 ?>"
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#f5f5f5'; this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#999;\'>No image</div>'">
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-xl flex items-center justify-center h-96">
              <i class="fa fa-image text-6xl text-gray-300"></i>
            </div>
          <?php endif; ?>
        </div>

        <!-- Listing Details -->
        <div class="space-y-6">
          <!-- Category Badge -->
          <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
            <?= htmlspecialchars($listing['commodity_name'], ENT_QUOTES, 'UTF-8') ?>
          </span>

          <!-- Title -->
          <h1 class="text-3xl font-bold text-gray-900">
            <?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>
          </h1>

          <!-- Price -->
          <div class="flex items-baseline gap-2">
            <span class="text-4xl font-bold text-green-600">
              MWK <?= number_format($listing['price'], 2) ?>
            </span>
            <span class="text-lg text-gray-600">
              / <?= htmlspecialchars($listing['unit'], ENT_QUOTES, 'UTF-8') ?>
            </span>
          </div>

          <!-- Quantity -->
          <div class="flex items-center gap-2 text-gray-700">
            <i class="fa fa-cubes text-green-600"></i>
            <span><?= number_format($listing['quantity']) ?> <?= htmlspecialchars($listing['unit'], ENT_QUOTES, 'UTF-8') ?> available</span>
          </div>

          <!-- Location -->
          <div class="flex items-center gap-2 text-gray-700">
            <i class="fa fa-map-marker text-green-600"></i>
            <span><?= htmlspecialchars($listing['location'], ENT_QUOTES, 'UTF-8') ?></span>
          </div>

          <!-- Quality Grade -->
          <?php if (!empty($listing['quality_grade'])): ?>
            <div class="flex items-center gap-2 text-gray-700">
              <i class="fa fa-star text-green-600"></i>
              <span>Quality Grade: <?= htmlspecialchars($listing['quality_grade'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
          <?php endif; ?>

          <!-- Action Buttons -->
          <div class="pt-4 border-t border-gray-200">
            <!-- Quantity Selector -->
            <div class="flex items-center gap-4 mb-4">
              <label class="text-sm font-medium text-gray-700">Quantity:</label>
              <div class="flex items-center gap-2">
                <button onclick="decrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                  <i class="fa fa-minus"></i>
                </button>
                <input type="number" id="quantity" value="1" min="1" max="<?= $listing['quantity'] ?>" 
                       class="w-20 h-10 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <button onclick="incrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
              <span class="text-sm text-gray-500">Max: <?= number_format($listing['quantity']) ?></span>
            </div>

            <div class="flex gap-3">
              <?php if ($isLoggedIn): ?>
                <button onclick="toggleFavorite(<?= $listing['id'] ?>)"
                        id="favorite-btn"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium transition-colors <?= $isFavorite ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                  <i class="fa <?= $isFavorite ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                  <?= $isFavorite ? 'Saved' : 'Save' ?>
                </button>
                <button onclick="addToCart(<?= $listing['id'] ?>)"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                  <i class="fa fa-shopping-cart"></i>
                  Add to Cart
                </button>
              <?php else: ?>
                <a href="<?= $base ?>/login" class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                  <i class="fa fa-shopping-cart"></i>
                  Login to Buy
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Description Section -->
      <div class="border-t border-gray-200 p-6 lg:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
        <div class="prose prose-gray max-w-none text-gray-700">
          <?= nl2br(htmlspecialchars($listing['description'], ENT_QUOTES, 'UTF-8')) ?>
        </div>
      </div>

      <!-- Seller Info -->
      <div class="border-t border-gray-200 p-6 lg:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Seller Information</h2>
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
            <span class="text-2xl font-bold text-green-600">
              <?= htmlspecialchars(substr(ucfirst($listing['seller_name']), 0, 1), ENT_QUOTES, 'UTF-8') ?>
            </span>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900">
              <a href="<?= $base ?>/seller/<?= $listing['seller_slug'] ?? $listing['seller_id'] ?>" class="hover:text-green-600 transition-colors">
                <?= htmlspecialchars(ucfirst($listing['seller_name']), ENT_QUOTES, 'UTF-8') ?>
              </a>
            </h3>
            <p class="text-sm text-gray-600">
              <?= htmlspecialchars($listing['location'], ENT_QUOTES, 'UTF-8') ?>
            </p>
          </div>
        </div>
        <div class="mt-4 flex gap-3">
          <button onclick="contactSeller()" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
            <i class="fa fa-envelope"></i>
            Contact Seller
          </button>
          <a href="<?= $base ?>/browse?seller=<?= $listing['seller_id'] ?>" class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
            <i class="fa fa-list"></i>
            View Other Listings
          </a>
        </div>
      </div>

      <!-- Posted Date -->
      <div class="border-t border-gray-200 p-6 lg:p-8">
        <p class="text-sm text-gray-500">
          Posted on <?= date('F j, Y', strtotime($listing['created_at'])) ?>
        </p>
      </div>
    </div>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    function changeImage(src) {
      document.getElementById('main-image').src = src;
    }

    function incrementQuantity() {
      const input = document.getElementById('quantity');
      const max = parseInt(input.max);
      if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
      }
    }

    function decrementQuantity() {
      const input = document.getElementById('quantity');
      if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
      }
    }

    function toggleFavorite(listingId) {
      const btn = document.getElementById('favorite-btn');
      const isCurrentlyFavorite = btn.classList.contains('bg-red-100');

      fetch('/api/favorites/' + (isCurrentlyFavorite ? 'remove' : 'add'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ listing_id: listingId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if (isCurrentlyFavorite) {
            btn.classList.remove('bg-red-100', 'text-red-700', 'hover:bg-red-200');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            btn.innerHTML = '<i class="fa fa-heart-o"></i> Save';
          } else {
            btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            btn.classList.add('bg-red-100', 'text-red-700', 'hover:bg-red-200');
            btn.innerHTML = '<i class="fa fa-heart"></i> Saved';
          }
        } else {
          alert(data.message || 'Failed to update favorites');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to update favorites. Please try again.');
      });
    }

    function addToCart(listingId) {
      const button = document.querySelector('button[onclick="addToCart(' + listingId + ')"]');
      if (button.disabled) return;

      // Get quantity from input
      const quantityInput = document.getElementById('quantity');
      const quantity = parseInt(quantityInput.value) || 1;

      // Get listing data from the page
      const listingTitle = '<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>';
      const listingPrice = 'MWK <?= number_format($listing['price'], 2) ?>';
      const listingImage = document.getElementById('main-image')?.src || '';

      button.disabled = true;
      button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Adding...';

      fetch('/add-to-cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          listing_id: parseInt(listingId),
          quantity: quantity
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Get cart items from cookie
          const userId = window.ulimiUserId || 'guest';
          const cartCookieName = 'ulimi_cart_user_' + userId;
          let cartItems = getCookie(cartCookieName) ? JSON.parse(getCookie(cartCookieName)) : [];

          // Add to local cart
          const existingItem = cartItems.find(item => item.id === listingId);
          if (existingItem) {
            existingItem.quantity += quantity;
          } else {
            cartItems.push({
              id: listingId,
              title: listingTitle,
              price: listingPrice,
              image: listingImage,
              quantity: quantity
            });
          }

          // Save to cookie
          setCookie(cartCookieName, cartItems, 7);

          // Update cart count
          const cartCount = document.getElementById('cartCount');
          if (cartCount) {
            cartCount.textContent = cartItems.length;
            cartCount.classList.add('cart-count-updated');
            setTimeout(() => cartCount.classList.remove('cart-count-updated'), 300);
          }

          // Show success message
          showNotification(quantity + ' item(s) added to cart successfully!', 'success');

          // Reset button
          button.innerHTML = '<i class="fa fa-shopping-cart"></i> Added';
          setTimeout(() => {
            button.innerHTML = '<i class="fa fa-shopping-cart"></i> Add to Cart';
            button.disabled = false;
          }, 2000);

          // Reset quantity to 1
          quantityInput.value = 1;
        } else {
          showNotification(data.message || 'Failed to add item to cart', 'error');
          button.disabled = false;
          button.innerHTML = '<i class="fa fa-shopping-cart"></i> Add to Cart';
        }
      })
      .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification('An error occurred. Please try again.', 'error');
        button.disabled = false;
        button.innerHTML = '<i class="fa fa-shopping-cart"></i> Add to Cart';
      });
    }

    function setCookie(name, value, days) {
      const expires = new Date();
      expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
      document.cookie = name + '=' + JSON.stringify(value) + ';expires=' + expires.toUTCString() + ';path=/';
    }

    function getCookie(name) {
      const nameEQ = name + '=';
      const ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    }

    function showNotification(message, type) {
      const notification = document.createElement('div');
      notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ' +
        (type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white');
      notification.textContent = message;
      document.body.appendChild(notification);
      setTimeout(() => {
        notification.remove();
      }, 3000);
    }

    function contactSeller() {
      alert('Messaging feature coming soon!');
    }
  </script>
  <script>
    // Pass user ID to JavaScript for user-specific cart cookie
    window.ulimiUserId = '<?= $userId ?>';
  </script>
</body>
</html>
