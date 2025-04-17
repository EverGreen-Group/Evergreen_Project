const Chat = {
    URLROOT: '',
    currentUserId: null,
    currentChatUserId: null,
    lastMessageId: 0,
    role: '',
    endpoint: '',
    pollingInterval: null,
    sentMessages: new Set(),
    isSendingMessage: false,

    init(urlRoot, userId, csrfToken) {
        this.URLROOT = urlRoot;
        this.currentUserId = userId;

        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: '3000'
            };
        } else {
            console.warn('Toastr is not loaded. Notifications will be logged to the console.');
        }

        this.bindEvents();
    },

    bindEvents() {
        document.getElementById('send-message-btn')?.addEventListener('click', () => this.sendMessage());
        document.getElementById('message-input')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
    },

    async fetchWithRetry(url, options, retries = 3, backoff = 1000) {
        for (let i = 0; i < retries; i++) {
            try {
                const response = await fetch(url, options);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return await response.json();
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, backoff * Math.pow(2, i)));
            }
        }
    },

    async sendMessage() {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();

        if (!message || !this.currentChatUserId) {
            this.showError('Cannot send message: Message or recipient missing.');
            return;
        }

        const messageKey = `${this.currentUserId}:${this.currentChatUserId}:${message}:${Date.now()}`;
        if (this.sentMessages.has(messageKey)) {
            this.showError('Duplicate message detected. Please wait before sending the same message again.');
            return;
        }
        this.sentMessages.add(messageKey);
        setTimeout(() => this.sentMessages.delete(messageKey), 5000);

        this.isSendingMessage = true;
        try {
            const data = await this.fetchWithRetry(`${this.URLROOT}/${this.endpoint}/sendMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: this.currentChatUserId, message })
            });

            if (data.success) {
                const existingMessage = document.querySelector(`[data-msg-id="${data.data.message_id}"]`);
                if (!existingMessage) {
                    this.appendMessage({
                        message_id: data.data.message_id,
                        sender_id: this.currentUserId,
                        receiver_id: this.currentChatUserId,
                        message,
                        created_at: data.data.created_at,
                        read_at: null,
                        edited_at: null,
                        message_type: 'text',
                        sender_name: 'Me'
                    });
                    this.lastMessageId = Math.max(this.lastMessageId, data.data.message_id);
                    console.log(`sendMessage updated lastMessageId to: ${this.lastMessageId}`);
                }
                messageInput.value = '';
            } else {
                this.showError(data.message || 'Error sending message');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError(`Failed to send message: ${error.message}`);
            this.sentMessages.delete(messageKey);
        } finally {
            this.isSendingMessage = false;
        }
    },

    async fetchMessages(receiverId, endpoint) {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.innerHTML = '<div class="spinner">Loading...</div>';

        try {
            const data = await this.fetchWithRetry(`${this.URLROOT}/${endpoint}/getMessages`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: receiverId, last_message_id: 0 })
            });

            if (data.success && data.data && Array.isArray(data.data.messages)) {
                this.displayMessages(data.data.messages, true, this.role);
                this.startPolling(endpoint);
            } else {
                messagesContainer.innerHTML = 'No messages found.';
                const errorMessage = data.message || 'Invalid response structure';
                this.showError(errorMessage);
                console.error('fetchMessages: Invalid response structure', data);
            }
        } catch (error) {
            console.error('Error fetching messages:', error);
            messagesContainer.innerHTML = 'Error loading messages.';
            this.showError(`Failed to load messages: ${error.message}`);
        }
    },

    async fetchNewMessages(receiverId, endpoint) {
        try {
            console.log(`Fetching new messages with lastMessageId: ${this.lastMessageId}`);
            const data = await this.fetchWithRetry(`${this.URLROOT}/${endpoint}/getMessages`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: receiverId, last_message_id: this.lastMessageId })
            });

            console.log('fetchNewMessages response:', data);

            if (data.success && data.data && Array.isArray(data.data.messages)) {
                this.displayMessages(data.data.messages, false, this.role);
                if (data.data.messages.length > 0) {
                    if (typeof toastr !== 'undefined') {
                        toastr.info('New message received!', 'Message');
                    } else {
                        console.log('New message received!');
                    }
                }
            } else {
                const errorMessage = data.message || 'Invalid response structure in fetchNewMessages';
                this.showError(errorMessage);
                console.error('fetchNewMessages: Invalid response structure', data);
            }
        } catch (error) {
            console.error('Error fetching new messages:', error);
            this.showError(`Failed to load new messages: ${error.message}`);
        }
    },

    startPolling(endpoint) {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
        this.pollingInterval = setInterval(() => {
            if (this.currentChatUserId && !this.isSendingMessage) {
                this.fetchNewMessages(this.currentChatUserId, endpoint);
            }
        }, 3000);
    },

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    },

    displayMessages(messages, clearPrevious, role) {
        const messagesContainer = document.getElementById('chat-messages');
        if (clearPrevious) messagesContainer.innerHTML = '';

        if (!messages || !Array.isArray(messages)) {
            messagesContainer.innerHTML = 'No messages available.';
            return;
        }

        if (messages.length === 0 && clearPrevious) {
            messagesContainer.innerHTML = 'No messages found.';
            return;
        }

        messages.forEach(message => {
            const existingMessage = document.querySelector(`[data-msg-id="${message.message_id}"]`);
            if (!existingMessage) {
                this.appendMessage(message);
                this.lastMessageId = Math.max(this.lastMessageId, message.message_id);
                console.log(`Updated lastMessageId to: ${this.lastMessageId}`);
            } else {
                console.log(`Message with ID ${message.message_id} already displayed, skipping.`);
            }
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    },

    appendMessage(data) {
        const messagesDiv = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        const isSent = data.sender_id == this.currentUserId;
        const rowClass = isSent ? 'row justify-content-start' : 'row justify-content-end';
        const backgroundClass = isSent ? 'alert-primary' : 'alert-success';
        const from = isSent ? 'Me' : (data.sender_name || this.role);

        messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
        messageDiv.dataset.msgId = data.message_id;
        messageDiv.dataset.sentTime = data.created_at || 'N/A';
        messageDiv.dataset.readTime = data.read_at || 'NULL';
        messageDiv.dataset.editedTime = data.edited_at || 'NULL';
        messageDiv.dataset.messageType = data.message_type || 'text';
        messageDiv.innerHTML = `
            <div class="${rowClass}">
                <div class="col-sm-10">
                    <div class="shadow-sm alert ${backgroundClass}">
                        <b>${from}: </b>${data.message}<br />
                        ${isSent ? `
                            <button class="view-details-btn btn btn-icon" title="View Details">
                                <i class='bx bx-star'></i>
                            </button>
                            <button class="edit-msg-btn btn btn-icon" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="delete-msg-btn btn btn-icon" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    },

    updateMessage(data) {
        const messageDiv = document.querySelector(`[data-msg-id="${data.message_id}"]`);
        if (messageDiv) {
            const isSent = data.sender_id == this.currentUserId;
            const rowClass = isSent ? 'row justify-content-start' : 'row justify-content-end';
            const backgroundClass = isSent ? 'alert-primary' : 'alert-success';
            const from = isSent ? 'Me' : (data.sender_name || this.role);

            messageDiv.dataset.sentTime = data.created_at || 'N/A';
            messageDiv.dataset.readTime = data.read_at || 'NULL';
            messageDiv.dataset.editedTime = data.edited_at || 'NULL';
            messageDiv.dataset.messageType = data.message_type || 'text';
            messageDiv.innerHTML = `
                <div class="${rowClass}">
                    <div class="col-sm-10">
                        <div class="shadow-sm alert ${backgroundClass}">
                            <b>${from}: </b>${data.message}<br />
                            ${isSent ? `
                                <button class="view-details-btn btn btn-icon" title="View Details">
                                    <i class='bx bx-star'></i>
                                </button>
                                <button class="edit-msg-btn btn btn-icon" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="delete-msg-btn btn btn-icon" title="Delete">
                                    <i class='bx bx-trash'></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }
    },

    deleteMessage(messageId) {
        const messageDiv = document.querySelector(`[data-msg-id="${messageId}"]`);
        if (messageDiv) messageDiv.remove();
    },

    bindUserSelection(role, endpoint) {
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelectorAll('.user-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                document.querySelector('.no-chat-selected').style.display = 'none';
                document.querySelector('.chat-interface').style.display = 'flex';

                this.currentChatUserId = item.dataset.userId;
                this.lastMessageId = 0;
                document.getElementById('current-chat-name').textContent = item.querySelector('h4').textContent;

                this.fetchMessages(this.currentChatUserId, endpoint);
            });
        });
    },

    bindSearch(searchInputId) {
        document.getElementById(searchInputId)?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.user-item').forEach(item => {
                const name = item.querySelector('h4')?.textContent.toLowerCase() || '';
                const id = item.querySelector('p')?.textContent.toLowerCase() || '';
                item.style.display = name.includes(searchTerm) || id.includes(searchTerm) ? 'flex' : 'none';
            });
        });
    },

    bindMessageActions(endpoint) {
        document.addEventListener('click', async (e) => {
            if (e.target.closest('.edit-msg-btn')) {
                const messageDiv = e.target.closest('.message');
                const messageId = messageDiv.dataset.msgId;
                const currentMessage = messageDiv.querySelector('b').nextSibling.textContent.trim();

                document.getElementById('edit-message-text').value = currentMessage;
                document.querySelector('.edit-message-modal').style.display = 'block';

                document.getElementById('save-edit-btn').onclick = async () => {
                    const newMessage = document.getElementById('edit-message-text').value.trim();
                    if (!newMessage) {
                        this.showError('Message cannot be empty.');
                        return;
                    }

                    try {
                        const data = await this.fetchWithRetry(`${this.URLROOT}/${endpoint}/editMessage`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ message_id: messageId, new_message: newMessage })
                        });

                        if (data.success) {
                            this.updateMessage({
                                message_id: messageId,
                                sender_id: this.currentUserId,
                                receiver_id: this.currentChatUserId,
                                message: newMessage,
                                created_at: messageDiv.dataset.sentTime,
                                read_at: messageDiv.dataset.readTime,
                                edited_at: new Date().toISOString(),
                                message_type: messageDiv.dataset.messageType,
                                sender_name: 'Me'
                            });
                            document.querySelector('.edit-message-modal').style.display = 'none';
                        } else {
                            this.showError(data.message || 'Error editing message');
                        }
                    } catch (error) {
                        console.error('Error editing message:', error);
                        this.showError(`Failed to edit message: ${error.message}`);
                    }
                };

                document.getElementById('cancel-edit-btn').onclick = () => {
                    document.querySelector('.edit-message-modal').style.display = 'none';
                };
            }

            if (e.target.closest('.delete-msg-btn')) {
                document.querySelector('.confirm-modal').style.display = 'block';
                const messageDiv = e.target.closest('.message');
                const messageId = messageDiv.dataset.msgId;

                document.getElementById('confirm-ok-btn').onclick = async () => {
                    try {
                        const data = await this.fetchWithRetry(`${this.URLROOT}/${endpoint}/deleteMessage`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ message_id: messageId })
                        });

                        if (data.success) {
                            this.deleteMessage(messageId);
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Message deleted successfully!', 'Success');
                            } else {
                                console.log('Message deleted successfully!');
                            }
                        } else {
                            this.showError(data.message || 'Error deleting message');
                        }
                    } catch (error) {
                        console.error('Error deleting message:', error);
                        this.showError(`Failed to delete message: ${error.message}`);
                    }
                    document.querySelector('.confirm-modal').style.display = 'none';
                };

                document.getElementById('confirm-cancel-btn').onclick = () => {
                    document.querySelector('.confirm-modal').style.display = 'none';
                };
            }

            if (e.target.closest('.view-details-btn')) {
                const messageDiv = e.target.closest('.message');
                const sentTime = messageDiv.dataset.sentTime || 'N/A';
                const readTime = messageDiv.dataset.readTime || 'Not read';
                const editedTime = messageDiv.dataset.editedTime || 'Not edited';
                const messageType = messageDiv.dataset.messageType || 'text';

                document.getElementById('detail-sent-time').textContent = sentTime;
                document.getElementById('detail-read-time').textContent = readTime;
                document.getElementById('detail-edited-time').textContent = editedTime;
                document.getElementById('detail-message-type').textContent = messageType;

                document.querySelector('.view-details-modal').style.display = 'block';
            }
        });

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelector('.edit-message-modal').style.display = 'none';
                document.querySelector('.view-details-modal').style.display = 'none';
            });
        });

        document.getElementById('close-details-btn')?.addEventListener('click', () => {
            document.querySelector('.view-details-modal').style.display = 'none';
        });
    },

    showError(message) {
        if (typeof toastr !== 'undefined') {
            toastr.error(message, 'Error');
        } else {
            console.error(message);
        }
    }
};

export default Chat;