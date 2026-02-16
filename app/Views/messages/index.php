<?php
$title = 'Messages';
$page = 'messages';
ob_start();
?>

<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Messages</h1>
            <p class="text-muted mb-0">Centre de communication en temps réel</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" id="refreshBtn" title="Rafraîchir les messages">
                <i class="bi bi-arrow-clockwise me-2"></i>Rafraîchir
            </button>
            <button type="button" class="btn btn-outline-secondary" id="markAllReadBtn">
                <i class="bi bi-check-all me-2"></i>Tout marquer lu
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                <i class="bi bi-plus-lg me-2"></i>Nouveau message
            </button>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="messages-container">
        <div class="messages-layout">
            
            <!-- Conversations Sidebar -->
            <div class="messages-sidebar" id="conversationsSidebar">
                <!-- Sidebar Header -->
                <div class="messages-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="header-title mb-0">Conversations</h5>
                        <span class="badge bg-primary" id="totalUnread"><?= $unreadCount ?? 0 ?></span>
                    </div>
                    <div class="mt-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" class="form-control border-start-0" 
                                   placeholder="Rechercher..." id="searchConversations">
                        </div>
                    </div>
                </div>
                
                <!-- Conversations List -->
                <div class="conversations-list" id="conversationsList">
                    <div class="text-center py-5 text-muted" id="loadingConversations">
                        <div class="spinner-border spinner-border-sm me-2"></div>
                        Chargement...
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-area" id="chatArea">
                <!-- Empty State -->
                <div class="empty-chat" id="emptyChat">
                    <div class="empty-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="empty-text">Sélectionnez une conversation</h5>
                    <p class="text-muted mb-4">Choisissez une conversation existante ou démarrez-en une nouvelle</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                        <i class="bi bi-plus-lg me-2"></i>Nouvelle conversation
                    </button>
                </div>

                <!-- Active Chat (hidden by default) -->
                <div class="active-chat d-none" id="activeChat">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="chat-user-info">
                            <button class="btn btn-link d-lg-none me-2 p-0" id="backToList">
                                <i class="bi bi-arrow-left fs-5"></i>
                            </button>
                            <div class="chat-avatar-container">
                                <img src="/assets/images/avatar-placeholder.svg" class="chat-avatar" id="chatUserAvatar" alt="">
                                <div class="online-indicator" id="chatUserOnline"></div>
                            </div>
                            <div class="chat-details">
                                <h6 class="chat-name" id="chatUserName">Utilisateur</h6>
                                <p class="chat-status" id="chatUserStatus">En ligne</p>
                            </div>
                        </div>
                        <div class="chat-actions">
                            <button class="btn btn-outline-secondary btn-sm" id="refreshChatBtn" title="Rafraîchir">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="chat-messages" id="chatMessages">
                        <!-- Messages will be loaded here -->
                    </div>

                    <!-- Message Input -->
                    <div class="chat-input">
                        <form id="messageForm" class="input-container">
                            <div class="message-input flex-grow-1">
                                <textarea class="form-control" id="messageInput" 
                                          placeholder="Tapez un message..." rows="1"
                                          style="resize: none;"></textarea>
                            </div>
                            <div class="input-actions">
                                <button type="submit" class="btn btn-primary" id="sendBtn" disabled title="Envoyer">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Nouveau Message -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Destinataire</label>
                    <select class="form-select" id="newMessageRecipient">
                        <option value="">Sélectionnez un utilisateur...</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" id="newMessageContent" rows="4" 
                              placeholder="Écrivez votre message..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="sendNewMessageBtn">
                    <i class="bi bi-send me-2"></i>Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Extra CSS for messages
$extraCss = '<link rel="stylesheet" href="/assets/css/messages.css">';

// Extra JS for messages
ob_start();
?>
<script src="/assets/js/messages.js"></script>
<?php
$extraJs = ob_get_clean();

include __DIR__ . '/../layouts/main.php';
?>
