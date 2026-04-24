// Chat polling client for real-time messaging
let currentConversationId = null;
let currentSellerId = null;
let currentListingId = null;
let pollInterval = null;
let lastMessageCount = 0;

const API_BASE = window.location.origin;

// Initialize chat for a seller
function initChat(sellerId, listingId = null) {
    currentSellerId = sellerId;
    currentListingId = listingId;
    
    // Get or create conversation
    getOrCreateConversation(sellerId, listingId);
}

// Get or create conversation
async function getOrCreateConversation(sellerId, listingId) {
    try {
        const response = await fetch(`${API_BASE}/api/messages/conversation/by-seller`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                seller_id: sellerId,
                listing_id: listingId
            })
        });
        
        const data = await response.json();
        
        if (data.success && data.conversation_id) {
            currentConversationId = data.conversation_id;
            loadMessages(currentConversationId);
            startPolling(currentConversationId);
        } else if (data.success && !data.conversation_id) {
            // No conversation exists yet, create one
            createConversation(sellerId, listingId);
        }
    } catch (error) {
        console.error('Error getting conversation:', error);
    }
}

// Create new conversation
async function createConversation(sellerId, listingId) {
    try {
        const response = await fetch(`${API_BASE}/api/messages/conversation/create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                seller_id: sellerId,
                listing_id: listingId
            })
        });
        
        const data = await response.json();
        
        if (data.success && data.conversation_id) {
            currentConversationId = data.conversation_id;
            loadMessages(currentConversationId);
            startPolling(currentConversationId);
        }
    } catch (error) {
        console.error('Error creating conversation:', error);
    }
}

// Load messages
async function loadMessages(conversationId) {
    try {
        const response = await fetch(`${API_BASE}/api/messages/${conversationId}`);
        const data = await response.json();
        
        if (data.success) {
            renderMessages(data.messages);
            lastMessageCount = data.messages.length;
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

// Start polling for new messages
function startPolling(conversationId) {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
    
    pollInterval = setInterval(() => {
        loadMessages(conversationId);
    }, 2000); // Poll every 2 seconds
}

// Stop polling
function stopPolling() {
    if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = null;
    }
}

// Send message
async function sendMessage(text, imagePath = null) {
    if (!currentConversationId || !text.trim()) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/api/messages/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                conversation_id: currentConversationId,
                message_text: text
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Immediately reload messages to show the new message
            loadMessages(currentConversationId);
            return true;
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
    
    return false;
}

// Upload image
async function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    
    try {
        const response = await fetch(`${API_BASE}/api/messages/upload`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success && data.image_path) {
            return data.image_path;
        }
    } catch (error) {
        console.error('Error uploading image:', error);
    }
    
    return null;
}

// Render messages in chat panel
function renderMessages(messages) {
    const messagesContainer = document.getElementById('chatMessages');
    if (!messagesContainer) return;
    
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = '<p class="text-gray-500 text-center py-4">No messages yet. Start the conversation!</p>';
        return;
    }
    
    messages.forEach(message => {
        const messageEl = document.createElement('div');
        const isCurrentUser = message.sender_id === getCurrentUserId();
        
        messageEl.className = `flex ${isCurrentUser ? 'justify-end' : 'justify-start'} mb-4`;
        
        let content = '';
        
        if (message.image_path) {
            content += `<img src="/${message.image_path}" alt="Image" class="max-w-[200px] rounded-lg mb-2">`;
        }
        
        if (message.message_text) {
            content += `<p class="text-sm">${escapeHtml(message.message_text)}</p>`;
        }
        
        const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        messageEl.innerHTML = `
            <div class="${isCurrentUser ? 'bg-green-600 text-white rounded-tr-none' : 'bg-white rounded-tl-none'} rounded-lg px-4 py-2 max-w-xs shadow-sm">
                ${content}
                <p class="text-xs ${isCurrentUser ? 'text-green-200' : 'text-gray-500'} mt-1">${time}</p>
            </div>
        `;
        
        messagesContainer.appendChild(messageEl);
    });
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Get current user ID (from the page or session)
function getCurrentUserId() {
    // This should be set in the page template
    return window.currentUserId || null;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Update notification badge
async function updateNotificationBadge() {
    try {
        const response = await fetch(`${API_BASE}/api/messages/unread-count`);
        
        // Check if response is JSON before parsing
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Response is not JSON (likely HTML error page or redirect)
            // Skip this update silently
            return;
        }

        const data = await response.json();

        if (data.success) {
            const badge = document.getElementById('messageBadge');
            const mobileBadge = document.getElementById('mobileMessageBadge');

            if (badge) {
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            if (mobileBadge) {
                if (data.unread_count > 0) {
                    mobileBadge.textContent = data.unread_count;
                    mobileBadge.classList.remove('hidden');
                } else {
                    mobileBadge.classList.add('hidden');
                }
            }
        }
    } catch (error) {
        // Silently fail - don't spam console
    }
}

// Initialize notification polling
function startNotificationPolling() {
    // Disabled for now - requires authentication check on backend
    // TODO: Re-enable when authentication is properly implemented for API routes
    return;
    
    updateNotificationBadge();
    setInterval(updateNotificationBadge, 10000); // Check every 10 seconds
}

// Start notification polling on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startNotificationPolling);
} else {
    startNotificationPolling();
}
