<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Payment Successful', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="bg-gray-50 min-h-screen">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <div class="bg-green-600 px-8 py-12 text-center">
        <div class="mx-auto w-20 h-20 bg-white rounded-full flex items-center justify-center mb-6">
          <i class="fa fa-check text-4xl text-green-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">Payment Successful!</h1>
        <p class="text-green-100">Thank you for your order</p>
      </div>

      <div class="p-8">
        <div class="mb-6">
          <p class="text-gray-600 mb-2">Transaction Reference:</p>
          <p class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($tx_ref ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
          <p class="text-green-800">
            <i class="fa fa-info-circle mr-2"></i>
            Your payment has been processed successfully. You will receive a confirmation email shortly.
          </p>
        </div>

        <div class="flex gap-4">
          <a href="<?= $base ?>/dashboard" class="flex-1 text-center bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
            View Order
          </a>
          <a href="<?= $base ?>/browse" class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 transition-colors">
            Continue Shopping
          </a>
        </div>
      </div>
    </div>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
