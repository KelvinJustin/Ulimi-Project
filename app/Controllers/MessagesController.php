<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;

final class MessagesController
{
    public function index(): void
    {
        // Authentication and role checks now handled by 'seller' middleware

        $user = Auth::user();

        if ($user['role'] !== 'seller') {
            http_response_code(403);
            echo 'Access denied. Sellers only.';
            return;
        }

        try {
            $pdo = Database::pdo();

            // Fetch seller's conversations with buyers
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
            $conversations = $stmt->fetchAll();

            View::render('messages.inbox', [
                'title' => 'Messages',
                'conversations' => $conversations,
                'user' => $user,
                'isSeller' => true
            ]);

        } catch (\PDOException $e) {
            error_log('Error fetching messages: ' . $e->getMessage());
            View::render('messages.inbox', [
                'title' => 'Messages',
                'conversations' => [],
                'user' => $user,
                'isSeller' => true,
                'error' => 'Failed to load messages'
            ]);
        }
    }

    public function viewConversation(array $params): void
    {
        // Authentication and role checks now handled by 'seller' middleware

        $user = Auth::user();
        
        if ($user['role'] !== 'seller') {
            http_response_code(403);
            echo 'Access denied. Sellers only.';
            return;
        }

        $conversationId = (int)($params['id'] ?? 0);

        if ($conversationId <= 0) {
            http_response_code(400);
            echo 'Invalid conversation ID';
            return;
        }

        try {
            $pdo = Database::pdo();
            
            // Verify user is part of this conversation as seller
            $stmt = $pdo->prepare("
                SELECT id, buyer_id, seller_id, listing_id
                FROM conversations 
                WHERE id = ? AND seller_id = ?
                LIMIT 1
            ");
            $stmt->execute([$conversationId, $user['id']]);
            $conversation = $stmt->fetch();

            if (!$conversation) {
                http_response_code(404);
                echo 'Conversation not found';
                return;
            }

            // Mark messages as read for this user
            $stmt = $pdo->prepare("
                UPDATE messages
                SET is_read = 1
                WHERE conversation_id = ?
                AND sender_id != ?
                AND is_read = 0
            ");
            $stmt->execute([$conversationId, $user['id']]);

            // Fetch conversation details
            $stmt = $pdo->prepare("
                SELECT 
                    c.id as conversation_id,
                    c.buyer_id,
                    c.listing_id,
                    up.display_name as buyer_name,
                    up.avatar_path as buyer_avatar,
                    cl.title as listing_title
                FROM conversations c
                LEFT JOIN user_profiles up ON c.buyer_id = up.user_id
                LEFT JOIN commodity_listings cl ON c.listing_id = cl.id
                WHERE c.id = ?
                LIMIT 1
            ");
            $stmt->execute([$conversationId]);
            $conversationDetails = $stmt->fetch();

            // Fetch messages
            $stmt = $pdo->prepare("
                SELECT 
                    m.id,
                    m.sender_id,
                    m.message_text,
                    m.image_path,
                    m.created_at,
                    up.display_name as sender_name,
                    up.avatar_path as sender_avatar
                FROM messages m
                LEFT JOIN user_profiles up ON m.sender_id = up.user_id
                WHERE m.conversation_id = ?
                ORDER BY m.created_at ASC
            ");
            $stmt->execute([$conversationId]);
            $messages = $stmt->fetchAll();

            View::render('messages.conversation', [
                'title' => 'Conversation - Seller Dashboard',
                'conversation' => $conversationDetails,
                'messages' => $messages,
                'user' => $user
            ]);

        } catch (\PDOException $e) {
            error_log('Error fetching conversation: ' . $e->getMessage());
            http_response_code(500);
            echo 'Error loading conversation';
        }
    }
}
