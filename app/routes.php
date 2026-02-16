<?php
/**
 * Définition des routes de l'application
 */

use App\Controllers\AuthController;
use App\Controllers\MessageController;
use App\Controllers\DashboardController;
use App\Middleware\AuthMiddleware;

// Middleware d'authentification
Flight::before('start', function () {
    $publicRoutes = ['/', '/login', '/auto-login'];
    $currentPath = Flight::request()->url;
    
    // Vérifier si la route nécessite une authentification
    $needsAuth = !in_array($currentPath, $publicRoutes) && 
                 strpos($currentPath, '/assets') !== 0 &&
                 strpos($currentPath, '/api') !== 0;
    
    if ($needsAuth && !isset($_SESSION['user'])) {
        // Rediriger vers la page de login
        Flight::redirect('/login');
        return false;
    }
});

// ========================
// Routes d'authentification
// ========================

// Page de login
Flight::route('GET /', function () {
    if (isset($_SESSION['user'])) {
        Flight::redirect('/dashboard');
        return;
    }
    Flight::redirect('/login');
});

Flight::route('GET /login', function () {
    if (isset($_SESSION['user'])) {
        Flight::redirect('/dashboard');
        return;
    }
    $controller = new AuthController();
    $controller->showLogin();
});

// Login via credentials (JSON POST)
Flight::route('POST /login', function () {
    $controller = new AuthController();
    $controller->login();
});

// Auto-login (sélection d'un utilisateur)
Flight::route('POST /auto-login', function () {
    $controller = new AuthController();
    $controller->autoLogin();
});

// Logout
Flight::route('GET /logout', function () {
    $controller = new AuthController();
    $controller->logout();
});

// ========================
// Routes du Dashboard
// ========================

Flight::route('GET /dashboard', function () {
    $controller = new DashboardController();
    $controller->index();
});

// ========================
// Routes des Messages
// ========================

Flight::route('GET /messages', function () {
    $controller = new MessageController();
    $controller->index();
});

// API pour les messages (JSON)
Flight::route('GET /api/messages', function () {
    $controller = new MessageController();
    $controller->getConversations();
});

Flight::route('GET /api/messages/@userId', function ($userId) {
    $controller = new MessageController();
    $controller->getMessages($userId);
});

Flight::route('POST /api/messages/send', function () {
    $controller = new MessageController();
    $controller->sendMessage();
});

Flight::route('POST /api/messages/mark-read/@messageId', function ($messageId) {
    $controller = new MessageController();
    $controller->markAsRead($messageId);
});

Flight::route('POST /api/messages/mark-all-read/@userId', function ($userId) {
    $controller = new MessageController();
    $controller->markAllAsRead($userId);
});

Flight::route('GET /api/messages/unread-count', function () {
    $controller = new MessageController();
    $controller->getUnreadCount();
});

Flight::route('GET /api/messages/refresh', function () {
    $controller = new MessageController();
    $controller->refreshMessages();
});

// API pour les utilisateurs
Flight::route('GET /api/users', function () {
    $controller = new AuthController();
    $controller->getUsers();
});

Flight::route('GET /api/users/online', function () {
    $controller = new AuthController();
    $controller->getOnlineUsers();
});

// Heartbeat pour maintenir le statut en ligne
Flight::route('POST /api/heartbeat', function () {
    $controller = new AuthController();
    $controller->heartbeat();
});
