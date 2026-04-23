<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Checkout', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="icon" type="image/png" href="<?= $base ?>/logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://in.paychangu.com/js/popup.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .organic-shadow {
      box-shadow: 0px 12px 32px rgba(26, 61, 34, 0.06);
    }
  </style>
</head>
<body class="bg-background text-on-surface min-h-screen">

  <main class="max-w-7xl mx-auto px-6 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      <!-- Left Column: Shipping & Items -->
      <div class="lg:col-span-8 space-y-10">
        <!-- Section Header -->
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-on-surface mb-2">Checkout Details</h1>
          <p class="text-on-surface-variant">Review your agricultural order and finalize shipping information.</p>
        </div>

        <!-- Shipping Details -->
        <section class="bg-surface-container-low rounded-2xl p-8 space-y-6">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">local_shipping</span>
            <h2 class="text-xl font-semibold text-on-surface">Shipping Address</h2>
          </div>
          <div class="bg-surface-container-lowest p-6 rounded-xl border-l-4 border-primary organic-shadow">
            <div class="flex justify-between items-start">
              <div class="space-y-1">
                <p class="font-bold text-on-surface"><?= htmlspecialchars($user['display_name'] ?? 'Your Name', ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-on-surface-variant">Your shipping address will be added here</p>
                <p class="text-sm font-medium text-primary mt-2 flex items-center gap-1">
                  <span class="material-symbols-outlined text-xs">call</span>
                  Contact information
                </p>
              </div>
              <button class="text-sm font-bold text-primary hover:underline transition-all">CHANGE</button>
            </div>
          </div>
        </section>

        <!-- Order Items -->
        <section class="bg-surface-container-low rounded-2xl p-8 space-y-6">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">shopping_basket</span>
            <h2 class="text-xl font-semibold text-on-surface">Order Items</h2>
          </div>
          <div class="space-y-4">
            <?php if (!empty($cartItems)): ?>
              <?php foreach ($cartItems as $item): ?>
                <div class="bg-surface-container-lowest p-4 md:p-6 rounded-xl flex flex-col md:flex-row gap-6 items-center organic-shadow transition-all hover:translate-y-[-2px]">
                  <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0">
                    <?php if (!empty($item['image_path'])): ?>
                      <img src="<?= htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                      <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-300 text-4xl">image</span>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="flex-grow text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                      <span class="bg-secondary-container text-on-secondary-container text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-tighter">Verified Seller</span>
                      <h3 class="text-lg font-bold text-on-surface"><?= htmlspecialchars(ucfirst($item['title']), ENT_QUOTES, 'UTF-8') ?></h3>
                    </div>
                    <p class="text-sm text-on-surface-variant mb-2"><?= htmlspecialchars($item['commodity_name'], ENT_QUOTES, 'UTF-8') ?> • Seller: <?= htmlspecialchars(ucfirst($item['seller_name']), ENT_QUOTES, 'UTF-8') ?></p>
                    <div class="flex items-center justify-center md:justify-start gap-4">
                      <span class="text-on-surface font-semibold">Qty: <?= $item['quantity'] ?></span>
                      <span class="text-primary font-bold">MWK <?= number_format($item['quantity'] * $item['price_per_unit_at_add'], 2) ?></span>
                    </div>
                  </div>
                  <button class="material-symbols-outlined text-on-surface-variant hover:text-error transition-colors">delete_outline</button>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-on-surface-variant">Your cart is empty.</p>
            <?php endif; ?>
          </div>
        </section>
      </div>

      <!-- Right Column: Order Summary -->
      <aside class="lg:col-span-4 sticky top-28">
        <div class="bg-surface-container-lowest rounded-2xl p-8 organic-shadow space-y-8 border-t-4 border-primary-container">
          <h2 class="text-xl font-bold text-on-surface">Order Summary</h2>
          <div class="space-y-4">
            <div class="flex justify-between text-on-surface-variant">
              <span>Subtotal</span>
              <span class="font-medium text-on-surface">MWK <?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="flex justify-between text-on-surface-variant">
              <span>Platform Fee</span>
              <span class="font-medium text-on-surface">MWK <?= number_format($platformFee, 2) ?></span>
            </div>
            <div class="flex justify-between text-on-surface-variant">
              <span>Delivery Fee</span>
              <span class="font-medium text-on-surface">MWK <?= number_format($deliveryFee, 2) ?></span>
            </div>
            <div class="flex justify-between text-on-surface-variant">
              <span class="flex items-center gap-1">
                Tax
                <span class="material-symbols-outlined text-xs">info</span>
              </span>
              <span class="font-medium text-on-surface">MWK <?= number_format($tax, 2) ?></span>
            </div>
            <div class="pt-4 border-t border-surface-container-high flex justify-between items-baseline">
              <span class="text-lg font-bold text-on-surface">Total</span>
              <div class="text-right">
                <span class="block text-2xl font-extrabold text-primary">MWK <?= number_format($total, 2) ?></span>
                <span class="text-[10px] text-on-surface-variant uppercase tracking-widest">Inclusive of VAT</span>
              </div>
            </div>
          </div>
          <div class="space-y-4">
            <div id="wrapper"></div>
            <button type="button" onClick="makePayment()" class="w-full py-5 rounded-full bg-gradient-to-br from-primary to-primary-container text-white font-bold text-lg organic-shadow active:scale-[0.98] transition-all hover:brightness-110 flex items-center justify-center gap-2">
              Pay Now
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
            <div class="flex justify-center">
              <a class="text-sm font-semibold text-on-surface-variant hover:text-primary transition-all flex items-center gap-1" href="<?= $base ?>/browse">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Continue Shopping
              </a>
            </div>
          </div>
          <!-- Trust Elements -->
          <div class="pt-6 border-t border-surface-container-high grid grid-cols-2 gap-4">
            <div class="flex flex-col items-center text-center gap-2">
              <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
              </div>
              <span class="text-[10px] font-bold uppercase text-on-surface-variant tracking-wider">Secure Checkout</span>
            </div>
            <div class="flex flex-col items-center text-center gap-2">
              <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-xl">security</span>
              </div>
              <span class="text-[10px] font-bold uppercase text-on-surface-variant tracking-wider">Escrow Protected</span>
            </div>
          </div>
        </div>
        <!-- Seller Info Snippet -->
        <div class="mt-6 p-4 bg-tertiary-fixed rounded-xl flex items-center gap-4">
          <span class="material-symbols-outlined text-on-tertiary-fixed text-2xl">eco</span>
          <div>
            <p class="text-xs font-bold text-on-tertiary-fixed uppercase">Eco-Tip</p>
            <p class="text-xs text-on-tertiary-fixed-variant">Bulk agricultural orders reduce transport carbon footprint by 15%.</p>
          </div>
        </div>
      </aside>
    </div>
  </main>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    function makePayment(){
      PaychanguCheckout({
        "public_key": "PUB-f8QiKDShT9vkTSySNG0dEgnAUSDJrme5",
        "tx_ref": '' + Math.floor((Math.random() * 1000000000) + 1),
        "amount": <?= $total ?>,
        "currency": "MWK",
        "callback_url": "https://imprecatory-unobligative-genna.ngrok-free.dev/checkout?",
        "return_url": "https://imprecatory-unobligative-genna.ngrok-free.dev/",
        "customer":{
          "email": "knthinda@gmail.com",
          "first_name": "kelvin",
          "last_name": "nthinda",
        },
        "customization": {
          "title": "Ulimi Marketplace Order",
          "description": "Payment for agricultural products",
        },
        "meta": {
          "order_id": Math.floor((Math.random() * 1000000000) + 1)
        }
      });
    }
  </script>
</body>
</html>
