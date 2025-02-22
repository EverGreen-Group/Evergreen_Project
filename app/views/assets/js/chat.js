class ChatClient {
    constructor(userId, userRole, userName) {
        this.userId = userId;
        this.userRole = userRole;
        this.userName = userName;
        this.ws = null;
        this.currentChatUserId = null;
        this.connectedUsers = new Map();
        this.initWebSocket();
    }

    initWebSocket() {
        this.ws = new WebSocket('ws://localhost:8080');

        this.ws.onopen = () => {
            console.log('Connected to WebSocket server');
            // Send user information and role
            this.ws.send(JSON.stringify({
                type: 'init',
                userId: this.userId,
                userRole: this.userRole,
                userName: this.userName
            }));
        };

        this.ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            console.log('Received message:', data);
            
            switch(data.type) {
                case 'message':
                    if (data.senderId === this.currentChatUserId) {
                        this.appendMessage(data.message, 'received', data.messageId);
                    }
                    break;
                case 'status':
                    this.updateUserStatus(data.userId, data.status);
                    break;
                case 'init_response':
                    console.log('Connection initialized:', data.message);
                    if (data.onlineUsers) {
                        data.onlineUsers.forEach(userId => {
                            this.updateUserStatus(userId, 'online');
                        });
                    }
                    break;
            }
        };

        this.ws.onclose = () => {
            console.log('Disconnected from WebSocket server');
            // Attempt to reconnect after 3 seconds
            setTimeout(() => this.initWebSocket(), 3000);
        };

        this.ws.onerror = (error) => {
            console.error('WebSocket Error:', error);
        };
    }

    updateUserStatus(userId, status) {
        const userElement = document.querySelector(`.supplier-item[data-user-id="${userId}"]`);
        if (userElement) {
            const statusDot = userElement.querySelector('.status-dot');
            statusDot.className = `status-dot ${status}`;
        }
    }

    sendMessage(message) {
        if (!message || !this.currentChatUserId || !this.ws || this.ws.readyState !== WebSocket.OPEN) {
            return false;
        }

        const messageData = {
            type: 'message',
            userId: this.userId,
            receiverId: this.currentChatUserId,
            message: message
        };

        this.ws.send(JSON.stringify(messageData));
        this.appendMessage(message, 'sent');
        return true;
    }

    appendMessage(message, type, messageId = null) {
        const messagesDiv = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        if (messageId) messageDiv.dataset.messageId = messageId;

        // Create message container
        const messageContainer = document.createElement('div');
        messageContainer.className = 'message-container';

        // Add message content
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        messageContent.innerHTML = `
            <p>${this.escapeHtml(message)}</p>
            <span class="time">${new Date().toLocaleTimeString()}</span>
        `;
        messageContainer.appendChild(messageContent);

        // Add action buttons for sent messages
        if (type === 'sent') {
            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'message-actions';
            actionsDiv.innerHTML = `
                <button class="edit-msg-btn" title="Edit">
                    <i class='bx bx-edit-alt'></i>
                </button>
                <button class="delete-msg-btn" title="Delete">
                    <i class='bx bx-trash'></i>
                </button>
            `;

            // Add event listeners
            const editBtn = actionsDiv.querySelector('.edit-msg-btn');
            const deleteBtn = actionsDiv.querySelector('.delete-msg-btn');

            editBtn.addEventListener('click', () => this.editMessage(messageId));
            deleteBtn.addEventListener('click', () => this.deleteMessage(messageId));

            messageContainer.appendChild(actionsDiv);
        }

        messageDiv.appendChild(messageContainer);
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    setCurrentChatUser(userId) {
        this.currentChatUserId = userId;
        this.loadChatHistory(userId);
    }

    loadChatHistory(userId) {
        const endpoint = this.userRole === 'supplier_manager' ? 'suppliermanager' : 'supplier';
        fetch(`${URLROOT}/${endpoint}/getMessages`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ receiver_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            const messagesDiv = document.getElementById('chat-messages');
            messagesDiv.innerHTML = '';
            if (data.success && Array.isArray(data.messages)) {
                data.messages.forEach(msg => {
                    const type = msg.sender_id == this.userId ? 'sent' : 'received';
                    this.appendMessage(msg.message, type, msg.message_id);
                });
            }
        })
        .catch(error => console.error('Error loading chat history:', error));
    }
}

// Usage example in your view files:
document.addEventListener('DOMContentLoaded', () => {
    const chat = new ChatClient(
        USER_ID,  // Define these variables in your PHP view
        USER_ROLE,
        USER_NAME
    );

    // Message send handler
    document.getElementById('send-message-btn').addEventListener('click', () => {
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        
        if (message && chat.sendMessage(message)) {
            input.value = '';
        }
    });

    // Other event listeners...
});