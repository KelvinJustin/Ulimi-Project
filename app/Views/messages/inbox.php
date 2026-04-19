<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
$isSeller = $isSeller ?? false;
$user = \App\Core\Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Messages', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <link rel="icon" type="image/png" href="/logo.png">
  <style>
    body { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .primary-gradient {
      background: linear-gradient(135deg, #16642e 0%, #347e44 100%);
    }
    .messages-page {
      background: #fef9f0;
      color: #1d1c16;
      line-height: 1.6;
    }
    .conversation-item:hover {
      background: #f8f3ea;
    }
    .conversation-item.active {
      background: #ece8df;
    }
    .message-bubble-sent {
      background: linear-gradient(135deg, #16642e 0%, #347e44 100%);
    }
    .message-bubble-received {
      background: #f8f3ea;
    }
    @media (max-width: 768px) {
      .messages-sidebar {
        width: 100%;
        display: block;
      }
      .messages-chat {
        display: none;
      }
      .messages-sidebar.hidden-mobile {
        display: none;
      }
      .messages-chat.visible-mobile {
        display: flex;
        width: 100%;
      }
    }
  </style>
</head>
<body class="bg-[#fef9f0] text-[#1d1c16]">

  <div class="w-full px-4 sm:px-6 lg:px-8 py-8 h-screen">
    <?php if (isset($error)): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <?php if (empty($conversations)): ?>
      <div class="bg-white rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] p-8 text-center">
        <i class="fa fa-envelope text-[#bfc9bc] text-6xl mb-4"></i>
        <h2 class="text-xl font-semibold text-[#1d1c16] mb-2">No messages yet</h2>
        <p class="text-[#40493f]"><?= $isSeller ? 'When buyers message you, they\'ll appear here.' : 'When you message sellers, they\'ll appear here.' ?></p>
      </div>
    <?php else: ?>
      <div class="flex gap-6 h-[calc(100vh-64px)]">
        <!-- Sidebar - Conversation List -->
        <div class="messages-sidebar w-1/3 min-w-[300px] bg-white rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] overflow-hidden flex flex-col">
          <div class="p-4 border-b border-[#e7e2d9] flex items-center justify-between">
            <a href="<?= $base ?>/dashboard" class="text-[#40493f] hover:text-[#16642e] transition-colors">
              <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-bold text-[#1d1c16]">Conversations</h2>
            <div class="w-6"></div>
          </div>
          <div class="flex-1 overflow-y-auto">
            <?php foreach ($conversations as $conversation): ?>
              <div class="conversation-item p-4 cursor-pointer transition-colors border-b border-[#e7e2d9] last:border-b-0"
                   data-conversation-id="<?= $conversation['conversation_id'] ?>">
                <div class="flex items-start gap-3">
                  <!-- Other Person Avatar -->
                  <div class="flex-shrink-0">
                    <?php if (!empty($conversation['other_avatar'])): ?>
                      <img src="/<?= htmlspecialchars($conversation['other_avatar'], ENT_QUOTES, 'UTF-8') ?>"
                           alt="<?= htmlspecialchars($conversation['other_name'], ENT_QUOTES, 'UTF-8') ?>"
                           class="w-12 h-12 rounded-full object-cover">
                    <?php else: ?>
                      <div class="w-12 h-12 rounded-full bg-[#16642e] flex items-center justify-center text-white font-bold">
                        <?= htmlspecialchars(substr(ucfirst($conversation['other_name'] ?? 'U'), 0, 1), ENT_QUOTES, 'UTF-8') ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Conversation Info -->
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                      <h3 class="text-sm font-semibold text-[#1d1c16] truncate">
                        <?= htmlspecialchars(ucfirst($conversation['other_name'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') ?>
                      </h3>
                      <span class="text-xs text-[#40493f]">
                        <?php if ($conversation['last_message_time']): ?>
                          <?= date('M j, g:i A', strtotime($conversation['last_message_time'])) ?>
                        <?php else: ?>
                          <?= date('M j, g:i A', strtotime($conversation['conversation_created_at'])) ?>
                        <?php endif; ?>
                      </span>
                    </div>

                    <?php if ($conversation['listing_title']): ?>
                      <p class="text-xs text-[#40493f] mb-1">
                        <i class="fa fa-tag mr-1"></i>
                        <?= htmlspecialchars($conversation['listing_title'], ENT_QUOTES, 'UTF-8') ?>
                      </p>
                    <?php endif; ?>

                    <p class="text-sm text-[#40493f] truncate">
                      <?php if ($conversation['last_message']): ?>
                        <?= htmlspecialchars($conversation['last_message'], ENT_QUOTES, 'UTF-8') ?>
                      <?php else: ?>
                        <em class="text-[#bfc9bc]">No messages yet</em>
                      <?php endif; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Main Chat Panel -->
        <div class="messages-chat flex-1 bg-white rounded-[1.5rem] shadow-[0px_12px_32px_rgba(26,61,34,0.06)] overflow-hidden flex flex-col">
          <!-- Chat Header -->
          <div class="p-4 border-b border-[#e7e2d9] bg-[#f8f3ea] flex items-center gap-3">
            <button id="backButton" class="md:hidden p-2 text-[#40493f] hover:text-[#16642e]">
              <i class="fa fa-arrow-left"></i>
            </button>
            <div id="chatHeader" class="flex items-center gap-3 flex-1">
              <div class="w-10 h-10 rounded-full bg-[#16642e] flex items-center justify-center text-white font-bold overflow-hidden">
                <span id="chatAvatar">?</span>
              </div>
              <div>
                <h3 class="text-sm font-semibold text-[#1d1c16]" id="chatName">Select a conversation</h3>
                <p class="text-xs text-[#40493f]" id="chatListing"></p>
              </div>
            </div>
          </div>

          <!-- Messages Container -->
          <div class="flex-1 overflow-y-auto p-6" id="messagesContainer">
            <div class="text-center py-12" id="emptyState">
              <i class="fa fa-comments text-[#bfc9bc] text-5xl mb-4"></i>
              <p class="text-[#40493f]">Select a conversation to start messaging</p>
            </div>
            <div class="space-y-4 hidden" id="messagesList"></div>
          </div>

          <!-- Message Input -->
          <div class="p-4 border-t border-[#e7e2d9] bg-[#f8f3ea]">
            <form id="messageForm" class="flex gap-3">
              <input type="hidden" name="conversation_id" id="conversationId" value="">
              <input type="text"
                     name="message_text"
                     id="messageInput"
                     placeholder="Type a message..."
                     disabled
                     class="flex-1 px-4 py-3 bg-white rounded-xl border-none focus:outline-none focus:ring-2 focus:ring-[#206c34]/40 transition-all">
              <button type="submit" id="sendButton" disabled class="primary-gradient text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition-all disabled:opacity-50">
                <i class="fa fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
    const currentUserId = <?= $user['id'] ?>;
    let activeConversationId = null;
    let lastMessageCount = {};
    let pollingIntervals = {};

    // Mobile back button
    document.getElementById('backButton').addEventListener('click', () => {
      document.querySelector('.messages-sidebar').classList.remove('hidden-mobile');
      document.querySelector('.messages-chat').classList.remove('visible-mobile');
    });

    // Load conversation
    async function loadConversation(conversationId) {
      try {
        const response = await fetch(`<?= $base ?>/api/messages/${conversationId}`);
        const data = await response.json();

        if (data.success) {
          displayConversation(data.conversation, data.messages);
          activeConversationId = conversationId;
          lastMessageCount[conversationId] = data.messages.length;

          // Clear existing polling and start new one
          if (pollingIntervals[conversationId]) {
            clearInterval(pollingIntervals[conversationId]);
          }
          pollingIntervals[conversationId] = setInterval(() => pollMessages(conversationId), 2000);

          // Enable input
          document.getElementById('conversationId').value = conversationId;
          document.getElementById('messageInput').disabled = false;
          document.getElementById('sendButton').disabled = false;

          // Mobile: show chat, hide sidebar
          if (window.innerWidth < 768) {
            document.querySelector('.messages-sidebar').classList.add('hidden-mobile');
            document.querySelector('.messages-chat').classList.add('visible-mobile');
          }
        }
      } catch (error) {
        console.error('Error loading conversation:', error);
      }
    }

    // Display conversation
    function displayConversation(conversation, messages) {
      const emptyState = document.getElementById('emptyState');
      const messagesList = document.getElementById('messagesList');
      const chatName = document.getElementById('chatName');
      const chatListing = document.getElementById('chatListing');
      const chatAvatar = document.getElementById('chatAvatar');

      // Update header
      const otherName = conversation.other_name || 'Unknown';
      chatName.textContent = otherName;
      chatListing.textContent = conversation.listing_title || '';
      chatAvatar.textContent = otherName.charAt(0).toUpperCase();

      // Show messages
      emptyState.classList.add('hidden');
      messagesList.classList.remove('hidden');
      messagesList.innerHTML = '';

      messages.forEach(message => {
        renderMessage(message);
      });

      // Scroll to bottom after a small delay to ensure DOM is updated
      setTimeout(() => {
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;
      }, 100);

      // Update active state in sidebar
      document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
        if (item.dataset.conversationId == conversationId) {
          item.classList.add('active');
        }
      });
    }

    // Render single message
    function renderMessage(message) {
      const messagesList = document.getElementById('messagesList');
      const isOtherUser = message.sender_id != currentUserId;

      const messageHtml = `
        <div class="flex ${isOtherUser ? 'justify-start' : 'justify-end'}">
          <div class="flex items-start gap-3 max-w-[70%]">
            ${isOtherUser ? `
              <div class="w-8 h-8 rounded-full bg-[#16642e] flex items-center justify-center text-white text-sm font-bold flex-shrink-0 overflow-hidden">
                ${message.sender_avatar ? `<img src="/${message.sender_avatar}" alt="${message.sender_name}" class="w-full h-full object-cover">` : (message.sender_name ? message.sender_name.charAt(0).toUpperCase() : 'B')}
              </div>
            ` : ''}
            <div class="${!isOtherUser ? 'message-bubble-sent text-white' : 'message-bubble-received text-[#1d1c16]'} rounded-2xl px-4 py-3 ${!isOtherUser ? 'rounded-tr-sm' : 'rounded-tl-sm'}">
              ${message.image_path ? `<img src="/${message.image_path}" alt="Message image" class="max-w-[200px] rounded-lg mb-2">` : ''}
              ${message.message_text ? `<p class="text-sm">${message.message_text}</p>` : ''}
              <p class="text-xs ${!isOtherUser ? 'text-white/80' : 'text-[#40493f]'} mt-1">
                ${new Date(message.created_at).toLocaleString()}
              </p>
            </div>
            ${!isOtherUser ? `
              <div class="w-8 h-8 rounded-full bg-[#bfc9bc] flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                S
              </div>
            ` : ''}
          </div>
        </div>
      `;
      messagesList.insertAdjacentHTML('beforeend', messageHtml);
    }

    // Poll for new messages
    async function pollMessages(conversationId) {
      try {
        const response = await fetch(`<?= $base ?>/api/messages/${conversationId}`);
        const data = await response.json();

        if (data.success && data.messages.length > lastMessageCount[conversationId]) {
          const newMessages = data.messages.slice(lastMessageCount[conversationId]);
          newMessages.forEach(message => renderMessage(message));
          lastMessageCount[conversationId] = data.messages.length;

          // Scroll to bottom after a small delay to ensure DOM is updated
          setTimeout(() => {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
          }, 100);
        }
      } catch (error) {
        console.error('Error polling messages:', error);
      }
    }

    // Conversation click handlers
    document.querySelectorAll('.conversation-item').forEach(item => {
      item.addEventListener('click', () => {
        const conversationId = item.dataset.conversationId;
        loadConversation(conversationId);
      });
    });

    // Form submission
    document.getElementById('messageForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      if (!activeConversationId) return;

      const formData = new FormData(this);
      const data = {
        conversation_id: formData.get('conversation_id'),
        message_text: formData.get('message_text')
      };

      try {
        const response = await fetch('<?= $base ?>/api/messages/send', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
          this.reset();
          // Immediately poll for new message
          pollMessages(activeConversationId);
        } else {
          alert('Failed to send message: ' + (result.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Failed to send message');
      }
    });
  </script>

</body>
</html>
