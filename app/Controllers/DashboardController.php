<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\User;

/**
 * Contrôleur pour le Dashboard
 */
class DashboardController extends BaseController
{
    private Message $messageModel;
    private User $userModel;

    public function __construct()
    {
        $this->messageModel = new Message();
        $this->userModel = new User();
    }

    /**
     * Afficher le dashboard
     */
    public function index(): void
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            $this->redirect('/login');
            return;
        }

        // Récupérer quelques statistiques
        $unreadCount = $this->messageModel->countUnread($currentUser['id']);
        $onlineUsers = $this->userModel->getOnlineUsers();

        $this->render('dashboard/index', [
            'unreadCount' => $unreadCount,
            'onlineUsers' => $onlineUsers
        ]);
    }
}
