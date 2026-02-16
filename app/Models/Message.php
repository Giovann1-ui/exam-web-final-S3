<?php

namespace App\Models;

use PDO;

/**
 * Model pour les messages
 */
class Message extends BaseModel
{
    protected string $table = 'messages';

    /**
     * Récupérer les conversations d'un utilisateur avec le dernier message
     */
    public function getConversations(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.id as user_id,
                u.username,
                u.avatar,
                u.is_online,
                u.last_seen,
                m.content as last_message,
                m.created_at as last_message_time,
                m.sender_id,
                (
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE sender_id = u.id 
                    AND receiver_id = ? 
                    AND is_read = 0
                ) as unread_count
            FROM users u
            INNER JOIN (
                SELECT 
                    CASE 
                        WHEN sender_id = ? THEN receiver_id 
                        ELSE sender_id 
                    END as other_user_id,
                    MAX(id) as last_message_id
                FROM messages
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY other_user_id
            ) last_msgs ON u.id = last_msgs.other_user_id
            INNER JOIN messages m ON m.id = last_msgs.last_message_id
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les messages entre deux utilisateurs
     */
    public function getMessagesBetween(int $userId1, int $userId2, int $limit = 50): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                m.*,
                sender.username as sender_name,
                sender.avatar as sender_avatar,
                receiver.username as receiver_name
            FROM {$this->table} m
            JOIN users sender ON m.sender_id = sender.id
            JOIN users receiver ON m.receiver_id = receiver.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?)
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC
            LIMIT ?
        ");
        $stmt->execute([$userId1, $userId2, $userId2, $userId1, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Envoyer un nouveau message
     */
    public function send(int $senderId, int $receiverId, string $content): int
    {
        return $this->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $content,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(int $messageId): bool
    {
        return $this->update($messageId, [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Marquer tous les messages d'un utilisateur comme lus
     */
    public function markAllAsRead(int $currentUserId, int $senderId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET is_read = 1, read_at = NOW() 
            WHERE sender_id = ? 
            AND receiver_id = ? 
            AND is_read = 0
        ");
        return $stmt->execute([$senderId, $currentUserId]);
    }

    /**
     * Compter les messages non lus pour un utilisateur
     */
    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE receiver_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    /**
     * Compter les messages non lus par expéditeur
     */
    public function countUnreadBySender(int $receiverId): array
    {
        $stmt = $this->db->prepare("
            SELECT sender_id, COUNT(*) as count 
            FROM {$this->table} 
            WHERE receiver_id = ? AND is_read = 0
            GROUP BY sender_id
        ");
        $stmt->execute([$receiverId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les nouveaux messages (pour le rafraîchissement)
     */
    public function getNewMessages(int $userId, string $lastCheck): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                m.*,
                sender.username as sender_name,
                sender.avatar as sender_avatar
            FROM {$this->table} m
            JOIN users sender ON m.sender_id = sender.id
            WHERE m.receiver_id = ? 
            AND m.created_at > ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$userId, $lastCheck]);
        return $stmt->fetchAll();
    }

    /**
     * Supprimer une conversation (tous les messages entre deux utilisateurs)
     */
    public function deleteConversation(int $userId1, int $userId2): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table} 
            WHERE (sender_id = ? AND receiver_id = ?)
               OR (sender_id = ? AND receiver_id = ?)
        ");
        return $stmt->execute([$userId1, $userId2, $userId2, $userId1]);
    }
}
