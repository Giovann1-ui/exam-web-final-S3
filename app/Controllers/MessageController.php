<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\User;
use Flight;

/**
 * Contrôleur pour les messages
 */
class MessageController extends BaseController
{
    private Message $messageModel;
    private User $userModel;

    public function __construct()
    {
        $this->messageModel = new Message();
        $this->userModel = new User();
    }

    /**
     * Afficher la page des messages
     */
    public function index(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->redirect('/login');
            return;
        }

        // Récupérer les conversations
        $conversations = $this->messageModel->getConversations($currentUser['id']);
        
        // Récupérer tous les utilisateurs pour créer une nouvelle conversation
        $users = $this->userModel->getAllExcept($currentUser['id']);

        // Compter les messages non lus
        $unreadCount = $this->messageModel->countUnread($currentUser['id']);

        $this->render('messages/index', [
            'conversations' => $conversations,
            'users' => $users,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * API : Récupérer les conversations
     */
    public function getConversations(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $conversations = $this->messageModel->getConversations($currentUser['id']);
        
        // Formater les données pour le frontend
        $formatted = [];
        foreach ($conversations as $conv) {
            $formatted[] = [
                'id' => $conv['user_id'],
                'name' => $conv['username'],
                'avatar' => $conv['avatar'],
                'online' => (bool) $conv['is_online'],
                'lastMessage' => $conv['last_message'],
                'lastMessageTime' => $this->formatRelativeTime($conv['last_message_time']),
                'lastSeen' => $this->formatRelativeTime($conv['last_seen']),
                'unread' => (int) $conv['unread_count'],
                'isSent' => $conv['sender_id'] == $currentUser['id']
            ];
        }

        $this->json(['conversations' => $formatted]);
    }

    /**
     * API : Récupérer les messages avec un utilisateur
     */
    public function getMessages(int $userId): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        // Récupérer l'utilisateur cible
        $otherUser = $this->userModel->find($userId);
        if (!$otherUser) {
            $this->jsonError('Utilisateur non trouvé', 404);
            return;
        }

        // Récupérer les messages
        $messages = $this->messageModel->getMessagesBetween($currentUser['id'], $userId);

        // Marquer les messages comme lus
        $this->messageModel->markAllAsRead($currentUser['id'], $userId);

        // Formater les messages
        $formatted = [];
        foreach ($messages as $msg) {
            $formatted[] = [
                'id' => $msg['id'],
                'text' => $msg['content'],
                'time' => $this->formatTime($msg['created_at']),
                'sent' => $msg['sender_id'] == $currentUser['id'],
                'read' => (bool) $msg['is_read'],
                'senderName' => $msg['sender_name'],
                'senderAvatar' => $msg['sender_avatar']
            ];
        }

        $this->json([
            'messages' => $formatted,
            'user' => [
                'id' => $otherUser['id'],
                'name' => $otherUser['username'],
                'avatar' => $otherUser['avatar'],
                'online' => (bool) $otherUser['is_online'],
                'lastSeen' => $this->formatRelativeTime($otherUser['last_seen'])
            ]
        ]);
    }

    /**
     * API : Envoyer un message
     */
    public function sendMessage(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $data = $this->getPostData();
        
        // Validation
        $errors = $this->validateRequired($data, ['receiver_id', 'content']);
        if (!empty($errors)) {
            $this->jsonError(implode(', ', $errors));
            return;
        }

        $receiverId = (int) $data['receiver_id'];
        $content = trim($data['content']);

        // Vérifier que le destinataire existe
        $receiver = $this->userModel->find($receiverId);
        if (!$receiver) {
            $this->jsonError('Destinataire non trouvé', 404);
            return;
        }

        // Envoyer le message
        $messageId = $this->messageModel->send($currentUser['id'], $receiverId, $content);

        $this->jsonSuccess([
            'message' => [
                'id' => $messageId,
                'text' => $content,
                'time' => $this->formatTime(date('Y-m-d H:i:s')),
                'sent' => true,
                'read' => false
            ]
        ], 'Message envoyé');
    }

    /**
     * API : Marquer un message comme lu
     */
    public function markAsRead(int $messageId): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $message = $this->messageModel->find($messageId);
        if (!$message) {
            $this->jsonError('Message non trouvé', 404);
            return;
        }

        // Vérifier que l'utilisateur est le destinataire
        if ($message['receiver_id'] != $currentUser['id']) {
            $this->jsonError('Non autorisé', 403);
            return;
        }

        $this->messageModel->markAsRead($messageId);
        $this->jsonSuccess([], 'Message marqué comme lu');
    }

    /**
     * API : Marquer tous les messages d'un utilisateur comme lus
     */
    public function markAllAsRead(int $userId): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $this->messageModel->markAllAsRead($currentUser['id'], $userId);
        $this->jsonSuccess([], 'Tous les messages marqués comme lus');
    }

    /**
     * API : Compter les messages non lus
     */
    public function getUnreadCount(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $count = $this->messageModel->countUnread($currentUser['id']);
        $byUser = $this->messageModel->countUnreadBySender($currentUser['id']);

        $this->json([
            'total' => $count,
            'byUser' => $byUser
        ]);
    }

    /**
     * API : Rafraîchir les messages (récupérer les nouveaux)
     */
    public function refreshMessages(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        // Récupérer le timestamp de dernière vérification
        $lastCheck = $_SESSION['last_message_check'] ?? date('Y-m-d H:i:s', time() - 60);
        
        // Mettre à jour le timestamp
        $_SESSION['last_message_check'] = date('Y-m-d H:i:s');

        // Récupérer les nouveaux messages
        $newMessages = $this->messageModel->getNewMessages($currentUser['id'], $lastCheck);

        // Formater les messages
        $formatted = [];
        foreach ($newMessages as $msg) {
            $formatted[] = [
                'id' => $msg['id'],
                'senderId' => $msg['sender_id'],
                'senderName' => $msg['sender_name'],
                'senderAvatar' => $msg['sender_avatar'],
                'text' => $msg['content'],
                'time' => $this->formatTime($msg['created_at']),
                'read' => (bool) $msg['is_read']
            ];
        }

        // Récupérer aussi les conversations mises à jour
        $conversations = $this->messageModel->getConversations($currentUser['id']);
        $formattedConv = [];
        foreach ($conversations as $conv) {
            $formattedConv[] = [
                'id' => $conv['user_id'],
                'name' => $conv['username'],
                'avatar' => $conv['avatar'],
                'online' => (bool) $conv['is_online'],
                'lastMessage' => $conv['last_message'],
                'lastMessageTime' => $this->formatRelativeTime($conv['last_message_time']),
                'lastSeen' => $this->formatRelativeTime($conv['last_seen']),
                'unread' => (int) $conv['unread_count']
            ];
        }

        $this->json([
            'newMessages' => $formatted,
            'conversations' => $formattedConv,
            'unreadCount' => $this->messageModel->countUnread($currentUser['id'])
        ]);
    }
}
