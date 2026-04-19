<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;

final class MessageController
{
    public function createConversation(Request $request): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $user = Auth::user();
        $sellerId = (int)($request->input('seller_id') ?? 0);
        $listingId = (int)($request->input('listing_id') ?? 0);

        if ($sellerId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid seller ID']);
            return;
        }

        if ($sellerId === $user['id']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cannot create conversation with yourself']);
            return;
        }

        try {
            $pdo = Database::pdo();

            // Check if conversation already exists
            $stmt = $pdo->prepare("
                SELECT id FROM conversations 
                WHERE buyer_id = ? AND seller_id = ? AND (listing_id = ? OR listing_id IS NULL)
                LIMIT 1
            ");
            $stmt->execute([$user['id'], $sellerId, $listingId ?: null]);
            $existing = $stmt->fetch();

            if ($existing) {
                echo json_encode(['success' => true, 'conversation_id' => $existing['id']]);
                return;
            }

            // Create new conversation
            $stmt = $pdo->prepare("
                INSERT INTO conversations (buyer_id, seller_id, listing_id)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$user['id'], $sellerId, $listingId ?: null]);

            echo json_encode(['success' => true, 'conversation_id' => $pdo->lastInsertId()]);

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function getMessages(Request $request, array $params): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $user = Auth::user();
        $conversationId = $params['id'] ?? 0;

        error_log("Fetching messages for conversation ID: $conversationId, User ID: {$user['id']}");

        try {
            $pdo = Database::pdo();

            // First check if conversation exists at all
            $stmt = $pdo->prepare("SELECT id, buyer_id, seller_id FROM conversations WHERE id = ?");
            $stmt->execute([$conversationId]);
            $conversation = $stmt->fetch();

            if (!$conversation) {
                error_log("Conversation $conversationId not found");
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Conversation not found']);
                return;
            }

            error_log("Conversation exists: buyer_id={$conversation['buyer_id']}, seller_id={$conversation['seller_id']}");

            // Verify user is part of the conversation
            if ($conversation['buyer_id'] != $user['id'] && $conversation['seller_id'] != $user['id']) {
                error_log("User {$user['id']} not authorized for conversation $conversationId");
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            // Get the other user's ID
            $otherUserId = ($conversation['buyer_id'] == $user['id']) ? $conversation['seller_id'] : $conversation['buyer_id'];

            // Fetch other user's name
            $stmt = $pdo->prepare("
                SELECT up.display_name as other_name, up.avatar_path as other_avatar
                FROM user_profiles up
                WHERE up.user_id = ?
            ");
            $stmt->execute([$otherUserId]);
            $otherUser = $stmt->fetch();

            if ($otherUser) {
                $conversation['other_name'] = $otherUser['other_name'];
                $conversation['other_avatar'] = $otherUser['other_avatar'];
            } else {
                $conversation['other_name'] = 'Unknown';
                $conversation['other_avatar'] = null;
            }

            // Fetch listing title if exists
            $stmt = $pdo->prepare("
                SELECT title as listing_title
                FROM commodity_listings
                WHERE id = (SELECT listing_id FROM conversations WHERE id = ?)
            ");
            $stmt->execute([$conversationId]);
            $listing = $stmt->fetch();
            if ($listing) {
                $conversation['listing_title'] = $listing['listing_title'];
            }

            error_log("User authorized, fetching messages...");

            // Mark messages as read for this user
            $stmt = $pdo->prepare("
                UPDATE messages
                SET is_read = 1
                WHERE conversation_id = ?
                AND sender_id != ?
                AND is_read = 0
            ");
            $stmt->execute([$conversationId, $user['id']]);

            // Fetch messages for this conversation
            $stmt = $pdo->prepare("
                SELECT
                    m.id,
                    m.conversation_id,
                    m.sender_id,
                    m.message_text,
                    m.image_path,
                    m.offer_price_per_unit,
                    m.currency,
                    m.created_at,
                    up.display_name as sender_name,
                    up.avatar_path as sender_avatar
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                LEFT JOIN user_profiles up ON u.id = up.user_id
                WHERE m.conversation_id = ?
                ORDER BY m.created_at ASC
            ");
            $stmt->execute([$conversationId]);
            $messages = $stmt->fetchAll();

            error_log("Fetched " . count($messages) . " messages");

            echo json_encode(['success' => true, 'messages' => $messages, 'conversation' => $conversation]);

        } catch (\PDOException $e) {
            error_log('Error fetching messages: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function sendMessage(Request $request): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $user = Auth::user();
        $conversationId = (int)($request->input('conversation_id') ?? 0);
        $messageText = trim($request->input('message_text') ?? '');

        if ($conversationId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid conversation ID']);
            return;
        }

        if (empty($messageText)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Message text is required']);
            return;
        }

        try {
            $pdo = Database::pdo();

            // Verify user is part of this conversation
            $stmt = $pdo->prepare("
                SELECT id FROM conversations 
                WHERE id = ? AND (buyer_id = ? OR seller_id = ?)
                LIMIT 1
            ");
            $stmt->execute([$conversationId, $user['id'], $user['id']]);
            if (!$stmt->fetch()) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            // Insert the message (is_read = 0 by default)
            $stmt = $pdo->prepare("
                INSERT INTO messages (conversation_id, sender_id, message_text, is_read)
                VALUES (?, ?, ?, 0)
            ");
            $stmt->execute([$conversationId, $user['id'], $messageText]);

            echo json_encode(['success' => true, 'message_id' => $pdo->lastInsertId()]);

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function uploadImage(Request $request): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
            return;
        }

        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF allowed.']);
            return;
        }

        if ($file['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'File too large. Maximum 5MB.']);
            return;
        }

        // Create upload directory
        $uploadDir = __DIR__ . '/../../public/uploads/messages';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('msg_', true) . '.' . $extension;
        $targetPath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode(['success' => true, 'image_path' => 'uploads/messages/' . $filename]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
        }
    }

    public function getUnreadCount(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $user = Auth::user();

        try {
            $pdo = Database::pdo();

            // Count unread messages (is_read = 0 and sender is not current user)
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as unread_count
                FROM messages m
                JOIN conversations c ON m.conversation_id = c.id
                WHERE (c.buyer_id = ? OR c.seller_id = ?)
                AND m.sender_id != ?
                AND m.is_read = 0
            ");
            $stmt->execute([$user['id'], $user['id'], $user['id']]);
            $result = $stmt->fetch();

            echo json_encode(['success' => true, 'unread_count' => $result['unread_count'] ?? 0]);

        } catch (\PDOException $e) {
            error_log('Error fetching unread count: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function getConversations(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $user = Auth::user();

        try {
            $pdo = Database::pdo();

            // Determine if user is buyer or seller and fetch conversations accordingly
            if ($user['role'] === 'seller') {
                // Seller's conversations with buyers
                $stmt = $pdo->prepare("
                    SELECT 
                        c.id as conversation_id,
                        c.buyer_id,
                        c.seller_id,
                        c.listing_id,
                        c.created_at as conversation_created_at,
                        up.display_name as other_name,
                        up.avatar_path as other_avatar,
                        cl.title as listing_title,
                        u.slug as seller_slug,
                        (
                            SELECT m.message_text 
                            FROM messages m 
                            WHERE m.conversation_id = c.id 
                            ORDER BY m.created_at DESC 
                            LIMIT 1
                        ) as last_message,
                        (
                            SELECT m.created_at 
                            FROM messages m 
                            WHERE m.conversation_id = c.id 
                            ORDER BY m.created_at DESC 
                            LIMIT 1
                        ) as last_message_time
                    FROM conversations c
                    LEFT JOIN user_profiles up ON c.buyer_id = up.user_id
                    LEFT JOIN commodity_listings cl ON c.listing_id = cl.id
                    LEFT JOIN users u ON c.seller_id = u.id
                    WHERE c.seller_id = ?
                    ORDER BY COALESCE(last_message_time, c.created_at) DESC
                ");
                $stmt->execute([$user['id']]);
            } else {
                // Buyer's conversations with sellers
                $stmt = $pdo->prepare("
                    SELECT 
                        c.id as conversation_id,
                        c.buyer_id,
                        c.seller_id,
                        c.listing_id,
                        c.created_at as conversation_created_at,
                        up.display_name as other_name,
                        up.avatar_path as other_avatar,
                        cl.title as listing_title,
                        u.slug as seller_slug,
                        (
                            SELECT m.message_text 
                            FROM messages m 
                            WHERE m.conversation_id = c.id 
                            ORDER BY m.created_at DESC 
                            LIMIT 1
                        ) as last_message,
                        (
                            SELECT m.created_at 
                            FROM messages m 
                            WHERE m.conversation_id = c.id 
                            ORDER BY m.created_at DESC 
                            LIMIT 1
                        ) as last_message_time
                    FROM conversations c
                    LEFT JOIN user_profiles up ON c.seller_id = up.user_id
                    LEFT JOIN commodity_listings cl ON c.listing_id = cl.id
                    LEFT JOIN users u ON c.seller_id = u.id
                    WHERE c.buyer_id = ?
                    ORDER BY COALESCE(last_message_time, c.created_at) DESC
                ");
                $stmt->execute([$user['id']]);
            }
            
            $conversations = $stmt->fetchAll();

            echo json_encode(['success' => true, 'conversations' => $conversations]);

        } catch (\PDOException $e) {
            error_log('Error fetching conversations: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function getConversationBySeller(Request $request): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $user = Auth::user();
        $sellerId = (int)($request->input('seller_id') ?? 0);
        $listingId = (int)($request->input('listing_id') ?? 0);

        if ($sellerId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid seller ID']);
            return;
        }

        try {
            $pdo = Database::pdo();

            // Find existing conversation
            $stmt = $pdo->prepare("
                SELECT id FROM conversations 
                WHERE buyer_id = ? AND seller_id = ? AND (listing_id = ? OR listing_id IS NULL)
                LIMIT 1
            ");
            $stmt->execute([$user['id'], $sellerId, $listingId ?: null]);
            $conversation = $stmt->fetch();

            if ($conversation) {
                echo json_encode(['success' => true, 'conversation_id' => $conversation['id']]);
            } else {
                echo json_encode(['success' => true, 'conversation_id' => null]);
            }

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
