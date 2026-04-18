<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Conversation', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="icon" type="image/png" href="/logo.png">
</head>
<body class="bg-gray-100">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
      <a href="<?= $base ?>/messages" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
        <i class="fa fa-arrow-left mr-2"></i>Back to Messages
      </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <!-- Conversation Header -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center gap-4">
          <!-- Buyer Avatar -->
          <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center text-white font-bold overflow-hidden">
            <?php if (!empty($conversation['buyer_avatar'])): ?>
              <img src="/<?= htmlspecialchars($conversation['buyer_avatar'], ENT_QUOTES, 'UTF-8') ?>" 
                   alt="<?= htmlspecialchars($conversation['buyer_name'], ENT_QUOTES, 'UTF-8') ?>"
                   class="w-full h-full object-cover">
            <?php else: ?>
              <?= htmlspecialchars(substr(ucfirst($conversation['buyer_name'] ?? 'B'), 0, 1), ENT_QUOTES, 'UTF-8') ?>
            <?php endif; ?>
          </div>

          <!-- Buyer Info -->
          <div class="flex-1">
            <h1 class="text-lg font-semibold text-gray-900">
              <?= htmlspecialchars(ucfirst($conversation['buyer_name'] ?? 'Unknown Buyer'), ENT_QUOTES, 'UTF-8') ?>
            </h1>
            <?php if ($conversation['listing_title']): ?>
              <p class="text-sm text-gray-500">
                <i class="fa fa-tag mr-1"></i>
                <?= htmlspecialchars($conversation['listing_title'], ENT_QUOTES, 'UTF-8') ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Messages -->
      <div class="px-6 py-8 min-h-[400px] max-h-[600px] overflow-y-auto">
        <?php if (empty($messages)): ?>
          <div class="text-center py-12">
            <i class="fa fa-comments text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No messages yet. Start the conversation!</p>
          </div>
        <?php else: ?>
          <div class="space-y-4">
            <?php foreach ($messages as $message): ?>
              <?php $isOtherUser = $message['sender_id'] != $user['id']; ?>
              <div class="flex <?= $isOtherUser ? 'justify-start' : 'justify-end' ?>">
                <div class="flex items-start gap-3 max-w-[70%]">
                  <?php if ($isOtherUser): ?>
                    <!-- Buyer Avatar -->
                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 overflow-hidden">
                      <?php if (!empty($message['sender_avatar'])): ?>
                        <img src="/<?= htmlspecialchars($message['sender_avatar'], ENT_QUOTES, 'UTF-8') ?>" 
                             alt="<?= htmlspecialchars($message['sender_name'], ENT_QUOTES, 'UTF-8') ?>"
                             class="w-full h-full object-cover">
                      <?php else: ?>
                        <?= htmlspecialchars(substr(ucfirst($message['sender_name'] ?? 'B'), 0, 1), ENT_QUOTES, 'UTF-8') ?>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                  <!-- Message Bubble -->
                  <div class="<?= !$isOtherUser ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-900' ?> rounded-lg px-4 py-3 <?= !$isOtherUser ? 'rounded-tr-none' : 'rounded-tl-none' ?>">
                    <?php if ($message['image_path']): ?>
                      <img src="/<?= htmlspecialchars($message['image_path'], ENT_QUOTES, 'UTF-8') ?>" 
                           alt="Message image" 
                           class="max-w-[200px] rounded-lg mb-2">
                    <?php endif; ?>
                    
                    <?php if ($message['message_text']): ?>
                      <p class="text-sm"><?= htmlspecialchars($message['message_text'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    
                    <p class="text-xs <?= !$isOtherUser ? 'text-green-200' : 'text-gray-500' ?> mt-1">
                      <?= date('M j, g:i A', strtotime($message['created_at'])) ?>
                    </p>
                  </div>

                  <?php if (!$isOtherUser): ?>
                    <!-- Seller Avatar (optional, can be omitted) -->
                    <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                      S
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Message Input -->
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <form method="post" action="<?= $base ?>/api/messages/send" id="messageForm" class="flex gap-3">
          <input type="hidden" name="conversation_id" value="<?= $conversation['conversation_id'] ?>">
          <input type="text" 
                 name="message_text"
                 id="messageInput" 
                 placeholder="Type a message..." 
                 required
                 class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
          <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fa fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('messageForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = {
        conversation_id: formData.get('conversation_id'),
        message_text: formData.get('message_text')
      };

      fetch('<?= $base ?>/api/messages/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          this.reset();
          location.reload();
        } else {
          alert('Failed to send message: ' + (result.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to send message');
      });
    });
  </script>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
