/**
 * Messages Component - JavaScript
 * Gère toutes les interactions de la page messages
 */

class MessagesApp {
    constructor() {
        this.conversations = [];
        this.selectedUserId = null;
        this.selectedUser = null;
        this.messages = [];
        this.refreshInterval = null;
        
        this.init();
    }

    init() {
        this.bindElements();
        this.bindEvents();
        this.loadConversations();
        this.startAutoRefresh();
    }

    bindElements() {
        // Sidebar
        this.conversationsList = document.getElementById('conversationsList');
        this.searchInput = document.getElementById('searchConversations');
        this.totalUnreadBadge = document.getElementById('totalUnread');
        this.loadingConversations = document.getElementById('loadingConversations');
        
        // Chat Area
        this.chatArea = document.getElementById('chatArea');
        this.emptyChat = document.getElementById('emptyChat');
        this.activeChat = document.getElementById('activeChat');
        this.chatMessages = document.getElementById('chatMessages');
        
        // Chat Header
        this.chatUserAvatar = document.getElementById('chatUserAvatar');
        this.chatUserName = document.getElementById('chatUserName');
        this.chatUserStatus = document.getElementById('chatUserStatus');
        this.chatUserOnline = document.getElementById('chatUserOnline');
        
        // Message Input
        this.messageForm = document.getElementById('messageForm');
        this.messageInput = document.getElementById('messageInput');
        this.sendBtn = document.getElementById('sendBtn');
        
        // Buttons
        this.refreshBtn = document.getElementById('refreshBtn');
        this.refreshChatBtn = document.getElementById('refreshChatBtn');
        this.markAllReadBtn = document.getElementById('markAllReadBtn');
        this.backToList = document.getElementById('backToList');
        
        // New Message Modal
        this.newMessageRecipient = document.getElementById('newMessageRecipient');
        this.newMessageContent = document.getElementById('newMessageContent');
        this.sendNewMessageBtn = document.getElementById('sendNewMessageBtn');
    }

    bindEvents() {
        // Search
        this.searchInput?.addEventListener('input', () => this.filterConversations());
        
        // Message Form
        this.messageForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });
        
        // Message Input
        this.messageInput?.addEventListener('input', () => {
            this.sendBtn.disabled = !this.messageInput.value.trim();
            this.autoResizeTextarea();
        });
        
        // Enter to send (Shift+Enter for new line)
        this.messageInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.messageInput.value.trim()) {
                    this.sendMessage();
                }
            }
        });
        
        // Refresh buttons
        this.refreshBtn?.addEventListener('click', () => this.refresh());
        this.refreshChatBtn?.addEventListener('click', () => this.loadMessages(this.selectedUserId));
        
        // Mark all as read
        this.markAllReadBtn?.addEventListener('click', () => this.markAllAsRead());
        
        // Back to list (mobile)
        this.backToList?.addEventListener('click', () => this.showConversationsList());
        
        // New Message
        this.sendNewMessageBtn?.addEventListener('click', () => this.sendNewMessage());
    }

    // ========================
    // API Calls
    // ========================

    async loadConversations() {
        try {
            const response = await fetch('/api/messages');
            const data = await response.json();
            
            if (data.conversations) {
                this.conversations = data.conversations;
                this.renderConversations();
            }
        } catch (error) {
            console.error('Erreur lors du chargement des conversations:', error);
            this.showError('Erreur de chargement des conversations');
        }
    }

    async loadMessages(userId) {
        if (!userId) return;
        
        this.showLoadingMessages();
        
        try {
            const response = await fetch(`/api/messages/${userId}`);
            const data = await response.json();
            
            if (data.messages) {
                this.messages = data.messages;
                this.selectedUser = data.user;
                this.renderMessages();
                this.updateChatHeader();
                this.scrollToBottom();
                
                // Update conversation unread count
                this.updateConversationUnread(userId, 0);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des messages:', error);
            this.showError('Erreur de chargement des messages');
        }
    }

    async sendMessage() {
        const content = this.messageInput.value.trim();
        if (!content || !this.selectedUserId) return;
        
        // Disable input while sending
        this.sendBtn.disabled = true;
        this.messageInput.disabled = true;
        
        try {
            const response = await fetch('/api/messages/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    receiver_id: this.selectedUserId,
                    content: content
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Add message to list
                this.messages.push(data.message);
                this.renderMessages();
                this.scrollToBottom();
                
                // Clear input
                this.messageInput.value = '';
                this.messageInput.style.height = 'auto';
                
                // Update conversation list
                this.updateConversationPreview(this.selectedUserId, content);
            } else {
                this.showError(data.error || 'Erreur lors de l\'envoi');
            }
        } catch (error) {
            console.error('Erreur lors de l\'envoi:', error);
            this.showError('Erreur lors de l\'envoi du message');
        } finally {
            this.sendBtn.disabled = false;
            this.messageInput.disabled = false;
            this.messageInput.focus();
        }
    }

    async sendNewMessage() {
        const recipientId = this.newMessageRecipient.value;
        const content = this.newMessageContent.value.trim();
        
        if (!recipientId || !content) {
            alert('Veuillez sélectionner un destinataire et écrire un message');
            return;
        }
        
        try {
            const response = await fetch('/api/messages/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    receiver_id: recipientId,
                    content: content
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('newMessageModal'));
                modal.hide();
                
                // Clear form
                this.newMessageRecipient.value = '';
                this.newMessageContent.value = '';
                
                // Reload conversations and select the new one
                await this.loadConversations();
                this.selectConversation(parseInt(recipientId));
            } else {
                alert(data.error || 'Erreur lors de l\'envoi');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi du message');
        }
    }

    async markAllAsRead() {
        if (!this.selectedUserId) {
            // Mark all conversations as read
            for (const conv of this.conversations) {
                if (conv.unread > 0) {
                    await fetch(`/api/messages/mark-all-read/${conv.id}`, { method: 'POST' });
                }
            }
        } else {
            // Mark current conversation as read
            await fetch(`/api/messages/mark-all-read/${this.selectedUserId}`, { method: 'POST' });
        }
        
        await this.loadConversations();
        this.updateTotalUnread();
    }

    async refresh() {
        // Add spinning animation
        const icon = this.refreshBtn.querySelector('i');
        icon.classList.add('spin');
        
        try {
            const response = await fetch('/api/messages/refresh');
            const data = await response.json();
            
            if (data.conversations) {
                this.conversations = data.conversations;
                this.renderConversations();
            }
            
            if (data.newMessages && data.newMessages.length > 0) {
                // If we have a selected conversation, check if new messages are for it
                const currentConvMessages = data.newMessages.filter(
                    m => m.senderId == this.selectedUserId
                );
                
                if (currentConvMessages.length > 0) {
                    this.messages.push(...currentConvMessages.map(m => ({
                        id: m.id,
                        text: m.text,
                        time: m.time,
                        sent: false,
                        read: m.read
                    })));
                    this.renderMessages();
                    this.scrollToBottom();
                }
                
                // Show notification for other messages
                const otherMessages = data.newMessages.filter(
                    m => m.senderId != this.selectedUserId
                );
                
                if (otherMessages.length > 0) {
                    this.showNotification(`${otherMessages.length} nouveau(x) message(s)`);
                }
            }
            
            // Update unread count
            if (data.unreadCount !== undefined) {
                this.updateTotalUnread(data.unreadCount);
            }
        } catch (error) {
            console.error('Erreur lors du rafraîchissement:', error);
        } finally {
            setTimeout(() => icon.classList.remove('spin'), 500);
        }
    }

    // ========================
    // Rendering
    // ========================

    renderConversations() {
        if (!this.conversationsList) return;
        
        if (this.conversations.length === 0) {
            this.conversationsList.innerHTML = `
                <div class="empty-conversations">
                    <i class="bi bi-chat-dots"></i>
                    <p>Aucune conversation</p>
                </div>
            `;
            return;
        }
        
        this.conversationsList.innerHTML = this.conversations.map(conv => `
            <a href="#" class="conversation-item ${conv.id == this.selectedUserId ? 'active' : ''} ${conv.unread > 0 ? 'unread' : ''}"
               data-user-id="${conv.id}">
                <div class="conversation-avatar">
                    <img src="${conv.avatar}" alt="${conv.name}">
                    ${conv.online ? '<div class="online-indicator"></div>' : ''}
                </div>
                <div class="conversation-info">
                    <div class="conversation-header">
                        <h6 class="conversation-name">${this.escapeHtml(conv.name)}</h6>
                        <span class="conversation-time">${conv.lastMessageTime}</span>
                    </div>
                    <p class="conversation-preview">${conv.isSent ? 'Vous: ' : ''}${this.escapeHtml(conv.lastMessage)}</p>
                    <div class="conversation-footer">
                        ${conv.unread > 0 ? `<span class="unread-badge">${conv.unread}</span>` : '<span></span>'}
                    </div>
                </div>
            </a>
        `).join('');
        
        // Bind click events
        this.conversationsList.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                this.selectConversation(parseInt(item.dataset.userId));
            });
        });
        
        this.updateTotalUnread();
    }

    renderMessages() {
        if (!this.chatMessages) return;
        
        if (this.messages.length === 0) {
            this.chatMessages.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-dots fs-1 mb-3 d-block"></i>
                    <p>Aucun message. Commencez la conversation !</p>
                </div>
            `;
            return;
        }
        
        // Group messages by date
        const today = new Date().toDateString();
        let currentDate = null;
        let html = '';
        
        this.messages.forEach(msg => {
            // Add date separator if needed (simplified - always show "Aujourd'hui")
            if (currentDate !== today) {
                html += `
                    <div class="date-separator">
                        <span class="date-label">Aujourd'hui</span>
                    </div>
                `;
                currentDate = today;
            }
            
            html += `
                <div class="message ${msg.sent ? 'own-message' : ''}">
                    ${!msg.sent ? `<img src="${this.selectedUser?.avatar || '/assets/images/avatar-placeholder.svg'}" class="message-avatar" alt="">` : ''}
                    <div class="message-bubble">
                        <div class="message-content">
                            <p>${this.escapeHtml(msg.text)}</p>
                        </div>
                        <div class="message-info">
                            <span class="message-time">${msg.time}</span>
                            ${msg.sent ? `
                                <span class="message-status">
                                    <i class="bi ${msg.read ? 'bi-check-all' : 'bi-check'}"></i>
                                </span>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        this.chatMessages.innerHTML = html;
    }

    // ========================
    // UI Updates
    // ========================

    selectConversation(userId) {
        this.selectedUserId = userId;
        
        // Update UI
        this.conversationsList.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.toggle('active', parseInt(item.dataset.userId) === userId);
        });
        
        // Show chat area
        this.emptyChat.classList.add('d-none');
        this.activeChat.classList.remove('d-none');
        
        // Load messages
        this.loadMessages(userId);
        
        // On mobile, hide sidebar
        if (window.innerWidth < 992) {
            document.getElementById('conversationsSidebar').classList.remove('mobile-show');
        }
    }

    updateChatHeader() {
        if (!this.selectedUser) return;
        
        this.chatUserAvatar.src = this.selectedUser.avatar;
        this.chatUserName.textContent = this.selectedUser.name;
        
        if (this.selectedUser.online) {
            this.chatUserStatus.textContent = '● En ligne';
            this.chatUserStatus.style.color = '#22c55e';
            this.chatUserOnline.style.display = 'block';
        } else {
            this.chatUserStatus.textContent = `Vu ${this.selectedUser.lastSeen}`;
            this.chatUserStatus.style.color = '#9ca3af';
            this.chatUserOnline.style.display = 'none';
        }
    }

    updateConversationPreview(userId, lastMessage) {
        const item = this.conversationsList.querySelector(`[data-user-id="${userId}"]`);
        if (item) {
            const preview = item.querySelector('.conversation-preview');
            const time = item.querySelector('.conversation-time');
            if (preview) preview.textContent = `Vous: ${lastMessage}`;
            if (time) time.textContent = 'À l\'instant';
        }
    }

    updateConversationUnread(userId, count) {
        const conv = this.conversations.find(c => c.id === userId);
        if (conv) {
            conv.unread = count;
        }
        
        const item = this.conversationsList.querySelector(`[data-user-id="${userId}"]`);
        if (item) {
            item.classList.toggle('unread', count > 0);
            const badge = item.querySelector('.unread-badge');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'inline' : 'none';
            }
        }
        
        this.updateTotalUnread();
    }

    updateTotalUnread(count) {
        if (count === undefined) {
            count = this.conversations.reduce((sum, c) => sum + c.unread, 0);
        }
        
        if (this.totalUnreadBadge) {
            this.totalUnreadBadge.textContent = count;
            this.totalUnreadBadge.style.display = count > 0 ? 'inline' : 'none';
        }
        
        // Update header badge
        const headerBadge = document.getElementById('unreadBadge');
        if (headerBadge) {
            headerBadge.textContent = count;
            headerBadge.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    filterConversations() {
        const query = this.searchInput.value.toLowerCase().trim();
        
        this.conversationsList.querySelectorAll('.conversation-item').forEach(item => {
            const name = item.querySelector('.conversation-name')?.textContent.toLowerCase() || '';
            const preview = item.querySelector('.conversation-preview')?.textContent.toLowerCase() || '';
            
            const matches = name.includes(query) || preview.includes(query);
            item.style.display = matches ? '' : 'none';
        });
    }

    showConversationsList() {
        document.getElementById('conversationsSidebar').classList.add('mobile-show');
    }

    showLoadingMessages() {
        if (this.chatMessages) {
            this.chatMessages.innerHTML = `
                <div class="loading-messages">
                    <div class="spinner-border spinner-border-sm me-2"></div>
                    Chargement des messages...
                </div>
            `;
        }
    }

    scrollToBottom() {
        if (this.chatMessages) {
            setTimeout(() => {
                this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
            }, 100);
        }
    }

    autoResizeTextarea() {
        if (this.messageInput) {
            this.messageInput.style.height = 'auto';
            this.messageInput.style.height = Math.min(this.messageInput.scrollHeight, 120) + 'px';
        }
    }

    // ========================
    // Auto Refresh
    // ========================

    startAutoRefresh() {
        // Refresh every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.refresh();
        }, 30000);
    }

    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
    }

    // ========================
    // Helpers
    // ========================

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showError(message) {
        console.error(message);
        // Could show a toast notification here
    }

    showNotification(message) {
        // Simple notification using browser notification API if available
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Nouveau message', { body: message });
        }
    }
}

// Add CSS for spinning animation
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 0.5s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.messagesApp = new MessagesApp();
});
