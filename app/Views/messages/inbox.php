<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
$isSeller = $isSeller ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Messages', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="icon" type="image/png" href="/logo.png">
</head>
<body class="bg-gray-100">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
      <p class="text-gray-600 mt-1"><?= $isSeller ? 'Manage your conversations with buyers' : 'Manage your conversations with sellers' ?></p>
    </div>

    <?php if (isset($error)): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <?php if (empty($conversations)): ?>
      <div class="bg-white rounded-lg shadow p-8 text-center">
        <i class="fa fa-envelope text-gray-300 text-6xl mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">No messages yet</h2>
        <p class="text-gray-500"><?= $isSeller ? 'When buyers message you, they\'ll appear here.' : 'When you message sellers, they\'ll appear here.' ?></p>
      </div>
    <?php else: ?>
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="divide-y divide-gray-200">
          <?php foreach ($conversations as $conversation): ?>
            <div class="hover:bg-gray-50 transition-colors p-4">
              <div class="flex items-start gap-4">
                <!-- Other Person Avatar -->
                <div class="flex-shrink-0">
                  <?php if (!empty($conversation['other_avatar'])): ?>
                    <img src="/<?= htmlspecialchars($conversation['other_avatar'], ENT_QUOTES, 'UTF-8') ?>" 
                         alt="<?= htmlspecialchars($conversation['other_name'], ENT_QUOTES, 'UTF-8') ?>"
                         class="w-12 h-12 rounded-full object-cover">
                  <?php else: ?>
                    <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                      <?= htmlspecialchars(substr(ucfirst($conversation['other_name'] ?? 'U'), 0, 1), ENT_QUOTES, 'UTF-8') ?>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Conversation Info -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">
                      <?= htmlspecialchars(ucfirst($conversation['other_name'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') ?>
                    </h3>
                    <span class="text-xs text-gray-500">
                      <?php if ($conversation['last_message_time']): ?>
                        <?= date('M j, g:i A', strtotime($conversation['last_message_time'])) ?>
                      <?php else: ?>
                        <?= date('M j, g:i A', strtotime($conversation['conversation_created_at'])) ?>
                      <?php endif; ?>
                    </span>
                  </div>

                  <?php if ($conversation['listing_title']): ?>
                    <p class="text-xs text-gray-500 mb-1">
                      <i class="fa fa-tag mr-1"></i>
                      <?= htmlspecialchars($conversation['listing_title'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                  <?php endif; ?>

                  <p class="text-sm text-gray-600 truncate">
                    <?php if ($conversation['last_message']): ?>
                      <?= htmlspecialchars($conversation['last_message'], ENT_QUOTES, 'UTF-8') ?>
                    <?php else: ?>
                      <em class="text-gray-400">No messages yet</em>
                    <?php endif; ?>
                  </p>
                </div>

                <!-- Reply Button -->
                <div class="flex-shrink-0 flex items-center gap-2">
                  <?php if (!$isSeller && !empty($conversation['seller_slug'])): ?>
                    <a href="<?= $base ?>/seller/<?= $conversation['seller_slug'] ?>" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                      <i class="fa fa-reply mr-1"></i>Reply
                    </a>
                  <?php endif; ?>
                  <a href="<?= $base ?>/messages/<?= $conversation['conversation_id'] ?>" 
                     class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors text-sm">
                    View
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
