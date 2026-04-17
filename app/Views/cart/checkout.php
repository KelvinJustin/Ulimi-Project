<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Checkout', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://in.paychangu.com/js/popup.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Order Items</h2>
          </div>
          <div class="p-6">
            <?php if (!empty($cartItems)): ?>
              <?php foreach ($cartItems as $item): ?>
                <div class="flex gap-4 py-4 border-b border-gray-100 last:border-0">
                  <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fa fa-image text-2xl text-gray-300"></i>
                  </div>
                  <div class="flex-1">
                    <h3 class="font-semibold text-gray-900"><?= htmlspecialchars(ucfirst($item['title']), ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($item['commodity_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-sm text-gray-600">Seller: <?= htmlspecialchars(ucfirst($item['seller_name']), ENT_QUOTES, 'UTF-8') ?></p>
                  </div>
                  <div class="text-right">
                    <p class="font-semibold text-gray-900">MWK <?= number_format($item['price_per_unit_at_add'], 2) ?></p>
                    <p class="text-sm text-gray-600">Qty: <?= $item['quantity'] ?></p>
                    <p class="text-sm font-medium text-green-600">MWK <?= number_format($item['quantity'] * $item['price_per_unit_at_add'], 2) ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-gray-600">Your cart is empty.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Order Summary</h2>
          </div>
          <div class="p-6">
            <div class="flex justify-between mb-4">
              <span class="text-gray-600">Subtotal</span>
              <span class="font-semibold">MWK <?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="flex justify-between mb-4">
              <span class="text-gray-600">Platform Fee (5%)</span>
              <span class="font-semibold">MWK <?= number_format($platformFee, 2) ?></span>
            </div>
            <div class="flex justify-between mb-4">
              <span class="text-gray-600">Delivery Fee</span>
              <span class="font-semibold">MWK <?= number_format($deliveryFee, 2) ?></span>
            </div>
            <div class="flex justify-between mb-4">
              <span class="text-gray-600">Tax</span>
              <span class="font-semibold">MWK <?= number_format($tax, 2) ?></span>
            </div>
            <div class="border-t border-gray-200 pt-4 mt-4">
              <div class="flex justify-between">
                <span class="text-lg font-bold text-gray-900">Total</span>
                <span class="text-lg font-bold text-green-600">MWK <?= number_format($total, 2) ?></span>
              </div>
            </div>
            <button onclick="makePayment()" class="w-full mt-6 bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
              Place Order
            </button>
            <a href="<?= $base ?>/browse" class="block mt-3 text-center text-gray-600 hover:text-gray-900">
              Continue Shopping
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    function makePayment() {
      // Generate unique transaction reference
      const txRef = 'TX' + Date.now() + Math.floor(Math.random() * 1000000);

      PaychanguCheckout({
        "public_key": "pub-test-HYSBQpa5K91mmXMHrjhkmC6mAjObPJ2u",
        "tx_ref": txRef,
        "amount": <?= $total ?>,
        "currency": "MWK",
        "callback_url": "https://imprecatory-unobligative-genna.ngrok-free.dev/payment/callback",
        "return_url": "https://imprecatory-unobligative-genna.ngrok-free.dev/payment/return",
        "customer": {
          "email": "<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>",
          "first_name": "<?= htmlspecialchars($user['display_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>",
          "last_name": ""
        },
        "customization": {
          "title": "Ulimi Marketplace Order",
          "description": "Payment for agricultural products"
        },
        "meta": {
          "order_id": $999txRef
        }
      });
    }
  </script>
</body>
</html>
