<?php

namespace App\Models;

use PDO;

/**
 * Model pour les utilisateurs
 */
class User extends BaseModel
{
    protected string $table = 'users';

    /**
     * Trouver un utilisateur par username
     */
    public function findByUsername(string $username): ?array
    {
        return $this->findBy('username', $username);
    }

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    /**
     * Récupérer tous les utilisateurs sauf un
     */
    public function getAllExcept(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT id, username, email, avatar, is_online, last_seen 
            FROM {$this->table} 
            WHERE id != ? 
            ORDER BY is_online DESC, username ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les utilisateurs en ligne
     */
    public function getOnlineUsers(): array
    {
        $stmt = $this->db->prepare("
            SELECT id, username, email, avatar, last_seen 
            FROM {$this->table} 
            WHERE is_online = 1 
            ORDER BY username ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mettre à jour le statut en ligne
     */
    public function setOnlineStatus(int $userId, bool $isOnline): bool
    {
        return $this->update($userId, [
            'is_online' => $isOnline ? 1 : 0,
            'last_seen' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mettre à jour last_seen
     */
    public function updateLastSeen(int $userId): bool
    {
        return $this->update($userId, [
            'last_seen' => date('Y-m-d H:i:s'),
            'is_online' => 1
        ]);
    }

    /**
     * Marquer les utilisateurs inactifs comme hors ligne
     * (Plus de 5 minutes sans activité)
     */
    public function markInactiveUsersOffline(): void
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET is_online = 0 
            WHERE is_online = 1 
            AND last_seen < DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");
        $stmt->execute();
    }

    /**
     * Vérifier le mot de passe (pour auto-login, on le simplifie)
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
