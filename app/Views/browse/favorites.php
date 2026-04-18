<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'My Favorites', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="bg-gray-50 min-h-screen">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">My Favorites</h1>
      <p class="text-gray-600">You have <?= $count ?> saved item<?= $count !== 1 ? 's' : '' ?></p>
    </div>

    <?php if (empty($favorites)): ?>
      <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <i class="fa fa-heart-o text-6xl text-gray-300 mb-6"></i>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No favorites yet</h2>
        <p class="text-gray-600 mb-6">Start browsing and save products you love!</p>
        <a href="<?= $base ?>/browse" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
          Browse Products
        </a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach ($favorites as $favorite): ?>
          <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <?php if (!empty($favorite['images'])): ?>
              <img src="<?= $base ?>/<?= htmlspecialchars($favorite['images'][0], ENT_QUOTES, 'UTF-8') ?>" 
                   alt="<?= htmlspecialchars($favorite['title'], ENT_QUOTES, 'UTF-8') ?>" 
                   class="w-full h-48 object-cover">
            <?php else: ?>
              <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                <i class="fa fa-image text-4xl text-gray-300"></i>
              </div>
            <?php endif; ?>
            
            <div class="p-4">
              <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full mb-3">
                <?= htmlspecialchars($favorite['commodity_name'], ENT_QUOTES, 'UTF-8') ?>
              </span>
              <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                <?= htmlspecialchars(ucfirst($favorite['title']), ENT_QUOTES, 'UTF-8') ?>
              </h3>
              <div class="text-2xl font-bold text-green-600 mb-2">
                MWK <?= number_format($favorite['price'], 2) ?>
                <span class="text-sm font-normal text-gray-600">
                  / <?= htmlspecialchars($favorite['unit'], ENT_QUOTES, 'UTF-8') ?>
                </span>
              </div>
              <div class="flex items-center gap-2 text-gray-600 text-sm mb-3">
                <i class="fa fa-map-marker"></i>
                <?= htmlspecialchars($favorite['location'], ENT_QUOTES, 'UTF-8') ?>
              </div>
              <div class="flex items-center gap-2 text-gray-600 text-sm mb-4 pt-3 border-t border-gray-200">
                <i class="fa fa-user"></i>
                <a href="<?= $base ?>/seller/<?= $favorite['seller_slug'] ?? $favorite['seller_id'] ?>" class="hover:text-green-600 transition-colors">
                  <?= htmlspecialchars(ucfirst($favorite['seller_name']), ENT_QUOTES, 'UTF-8') ?>
                </a>
              </div>
              <div class="flex gap-2">
                <a href="<?= $base ?>/browse" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-medium text-center hover:bg-green-700 transition-colors">
                  View Details
                </a>
                <button onclick="removeFavorite(<?= $favorite['id'] ?>)" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                  <i class="fa fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    function removeFavorite(listingId) {
      if (!confirm('Are you sure you want to remove this item from your favorites?')) {
        return;
      }

      fetch('/api/favorites/remove', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ listing_id: listingId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.message || 'Failed to remove favorite');
        }
      })
      .catch(error => {
        console.error('Error removing favorite:', error);
        alert('Failed to remove favorite. Please try again.');
      });
    }
  </script>
</body>
</html>
