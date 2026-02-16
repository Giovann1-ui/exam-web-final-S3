<?php

namespace App\Controllers;

use App\Models\User;
use Flight;

/**
 * Contrôleur d'authentification
 */
class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Afficher la page de login (sélection d'utilisateur pour auto-login)
     */
    public function showLogin(): void
    {
        // Récupérer tous les utilisateurs pour le sélecteur
        $users = $this->userModel->all();
        $this->render('auth/login', ['users' => $users]);
    }

    /**
     * Auto-login : connexion en sélectionnant un utilisateur
     */
    public function autoLogin(): void
    {
        $data = $this->getPostData();
        
        if (!isset($data['user_id'])) {
            $this->jsonError('Veuillez sélectionner un utilisateur');
            return;
        }

        $userId = (int) $data['user_id'];
        $user = $this->userModel->find($userId);

        if (!$user) {
            $this->jsonError('Utilisateur non trouvé');
            return;
        }

        // Mettre à jour le statut en ligne
        $this->userModel->setOnlineStatus($userId, true);

        // Stocker l'utilisateur en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'avatar' => $user['avatar']
        ];
        $_SESSION['last_activity'] = time();

        $this->jsonSuccess(['redirect' => '/messages'], 'Connexion réussie');
    }

    /**
     * Connexion classique via email/username + password (JSON POST)
     */
    public function login(): void
    {
        $data = $this->getPostData();

        $errors = $this->validateRequired($data, ['login', 'password']);
        if (!empty($errors)) {
            $this->jsonError(implode('; ', $errors));
            return;
        }

        $login = trim($data['login']);
        $password = $data['password'];

        // Chercher par email puis par username
        $user = $this->userModel->findByEmail($login) ?? $this->userModel->findByUsername($login);

        if (!$user) {
            $this->jsonError('Utilisateur non trouvé', 404);
            return;
        }

        // Vérifier le mot de passe
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            $this->jsonError('Identifiants invalides', 401);
            return;
        }

        // Mettre à jour le statut en ligne
        $this->userModel->setOnlineStatus((int)$user['id'], true);

        // Stocker l'utilisateur en session (éviter d'exposer le hash)
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'avatar' => $user['avatar'] ?? null
        ];
        $_SESSION['last_activity'] = time();

        $this->jsonSuccess(['redirect' => '/messages'], 'Connexion réussie');
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        if (isset($_SESSION['user'])) {
            // Mettre à jour le statut hors ligne
            $this->userModel->setOnlineStatus($_SESSION['user']['id'], false);
        }

        session_destroy();
        $this->redirect('/login');
    }

    /**
     * API : Récupérer tous les utilisateurs (sauf le courant)
     */
    public function getUsers(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $users = $this->userModel->getAllExcept($currentUser['id']);
        
        // Formater les données
        foreach ($users as &$user) {
            $user['last_seen_formatted'] = $this->formatRelativeTime($user['last_seen']);
        }

        $this->json(['users' => $users]);
    }

    /**
     * API : Récupérer les utilisateurs en ligne
     */
    public function getOnlineUsers(): void
    {
        // D'abord, marquer les inactifs comme hors ligne
        $this->userModel->markInactiveUsersOffline();
        
        $users = $this->userModel->getOnlineUsers();
        $this->json(['users' => $users]);
    }

    /**
     * API : Heartbeat pour maintenir le statut en ligne
     */
    public function heartbeat(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->jsonError('Non authentifié', 401);
            return;
        }

        $this->userModel->updateLastSeen($currentUser['id']);
        $_SESSION['last_activity'] = time();

        // Marquer les utilisateurs inactifs comme hors ligne
        $this->userModel->markInactiveUsersOffline();

        $this->jsonSuccess([], 'Heartbeat OK');
    }
}
