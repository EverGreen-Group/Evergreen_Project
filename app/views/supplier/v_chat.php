<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">

<main>
    <div class="chat-container">
        <!-- Left Sidebar: Supplier Managers List -->
        <div class="suppliers-sidebar">
            <div class="search-box">
                <input type="text" id="manager-search" placeholder="Search managers...">
                <i class='bx bx-search'></i>
            </div>
            
            <!-- Supplier Managers List -->
            <div class="suppliers-list">
                <?php foreach ($data['supplier_managers'] as $manager): ?>
                    <div class="supplier-item" data-user-id="<?php echo $manager->user_id; ?>">
                        <div class="supplier-info">
                            <h4><?php echo htmlspecialchars($manager->first_name . ' ' . $manager->last_name); ?></h4>
                            <p>MGR<?php echo sprintf('%03d', $manager->user_id); ?></p>
                        </div>
                        <div class="status-dot offline"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Middle: Chat Area -->
        <div class="chat-area">
            <!-- Initial State -->
            <div class="no-chat-selected">
                <i class='bx bx-message-square-dots'></i>
                <p>Select a manager to start messaging</p>
            </div>

            <!-- Chat Interface (Hidden initially) -->
            <div class="chat-interface" style="display: none;">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <h3 id="current-chat-name"></h3>
                        <span class="status">Active now</span>
                    </div>
                </div>

                <!-- Chat Messages Section -->
                <div class="messages" id="chat-messages">
                    <?php if (!empty($data['messages'])): ?>
                        <?php foreach ($data['messages'] as $message): ?>
                            <div class="message <?= ($message->sender_id == $_SESSION['supplier_id']) ? 'sent' : 'received' ?>">
                                <div class="row justify-content-<?= ($message->sender_id == $_SESSION['supplier_id']) ? 'start' : 'end' ?>">
                                    <div class="col-sm-10">
                                        <div class="shadow-sm alert <?= ($message->sender_id == $_SESSION['supplier_id']) ? 'alert-primary' : 'alert-success' ?>" data-msg-id="<?php echo $message->message_id; ?>">
                                            <b><?php echo ($message->sender_id == $_SESSION['supplier_id']) ? 'Me' : 'Manager'; ?>: </b><?php echo htmlspecialchars($message->message); ?><br />
                                            <div class="text-right">
                                                <small><i>Sent: <?php echo htmlspecialchars($message->created_at); ?> 
                                                    <?php if ($message->read_at): ?>| Read: <?php echo htmlspecialchars($message->read_at); ?><?php endif; ?>
                                                    <?php if ($message->edited_at): ?>| Edited: <?php echo htmlspecialchars($message->edited_at); ?><?php endif; ?>
                                                    (Type: <?php echo htmlspecialchars($message->message_type ?? 'text'); ?>)
                                                </i></small>
                                            </div>
                                            <?php if ($message->sender_id == $_SESSION['supplier_id']): ?>
                                                <button class="edit-msg-btn btn btn-sm btn-warning mt-1">Edit</button>
                                                <button class="delete-msg-btn btn btn-sm btn-danger mt-1">Delete</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Chat Input Section -->
                <div class="chat-input">
                    <input type="text" id="message-input" placeholder="Type a message...">
                    <button id="send-message-btn">
                        <i class='bx bx-send'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Edit Message Modal -->
<div class="edit-message-modal" style="display: none;">
    <div class="modal-content">
        <h3>Edit Message</h3>
        <textarea id="edit-message-text"></textarea>
        <div class="modal-buttons">
            <button id="save-edit-btn">Save</button>
            <button id="cancel-edit-btn">Cancel</button>
        </div>
    </div>
</div>

<script>
    const URLROOT = 'http://localhost/Evergreen_Project';
    let ws;
    let currentChatUserId = null;
    const onlineUsers = new Set();

    // Initialize WebSocket connection with reconnection logic
    function initWebSocket() {
        ws = new WebSocket('ws://localhost:8080');

        ws.onopen = function() {
            console.log('Connected to WebSocket server');
            // Send initialization message with supplier ID
            ws.send(JSON.stringify({
                type: 'init',
                userId: <?php echo $_SESSION['supplier_id']; ?>
            }));
        };

        ws.onmessage = function(e) {
            const data = JSON.parse(e.data);
            switch (data.type) {
                case 'message':
                case 'sent':
                    if (data.receiverId === currentChatUserId || data.senderId === currentChatUserId) {
                        appendMessage(data);
                    }
                    break;
                case 'status':
                    updateUserStatus(data.userId, data.status);
                    break;
                case 'message_updated':
                    updateMessage(data);
                    break;
                case 'message_deleted':
                    deleteMessage(data.message_id);
                    break;
            }
        };

        ws.onclose = function() {
            console.log('Disconnected from WebSocket server');
            // Update all users to offline status
            document.querySelectorAll('.status-dot').forEach(dot => {
                dot.classList.remove('online');
                dot.classList.add('offline');
            });
            // Attempt to reconnect after 3 seconds with exponential backoff
            setTimeout(() => reconnectWebSocket(), 3000);
        };

        ws.onerror = function(error) {
            console.error('WebSocket error:', error);
        };
    }

    // Reconnect with exponential backoff
    function reconnectWebSocket(attempt = 1) {
        if (attempt > 5) {
            console.error('Max reconnection attempts reached');
            return;
        }
        ws = new WebSocket('ws://localhost:8080');
        ws.onopen = function() {
            console.log(`Reconnected to WebSocket server after attempt ${attempt}`);
            ws.send(JSON.stringify({
                type: 'init',
                userId: <?php echo $_SESSION['supplier_id']; ?>
            }));
        };
        ws.onclose = function() {
            console.log(`Reconnection attempt ${attempt} failed`);
            setTimeout(() => reconnectWebSocket(attempt + 1), 3000 * Math.pow(2, attempt - 1));
        };
        ws.onmessage = ws.onmessage; // Reuse the existing onmessage handler
        ws.onerror = ws.onerror; // Reuse the existing onerror handler
    }

    // Update user's online/offline status
    function updateUserStatus(userId, status) {
        const userElement = document.querySelector(`.supplier-item[data-user-id="${userId}"]`);
        if (userElement) {
            const statusDot = userElement.querySelector('.status-dot');
            statusDot.classList.remove('online', 'offline');
            statusDot.classList.add(status);
            
            if (status === 'online') {
                onlineUsers.add(userId);
            } else {
                onlineUsers.delete(userId);
            }
        }
    }

    // Append or update message in chat
    function appendMessage(data) {
        const messagesDiv = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        const isSent = data.senderId == <?php echo $_SESSION['supplier_id']; ?>;
        const rowClass = isSent ? 'row justify-content-start' : 'row justify-content-end';
        const backgroundClass = isSent ? 'alert-primary' : 'alert-success';
        const from = data.senderName || 'Me'; // Use 'Me' for sent, 'Manager' for received

        messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
        messageDiv.dataset.msgId = data.message_id;
        messageDiv.innerHTML = `
            <div class="${rowClass}">
                <div class="col-sm-10">
                    <div class="shadow-sm alert ${backgroundClass}">
                        <b>${isSent ? 'Me' : 'Manager'}: </b>${data.message}<br />
                        <div class="text-right">
                            <small><i>Sent: ${data.created_at} | ${data.read_at || 'NULL'} ${data.edited_at ? '| Edited: ' + data.edited_at : ''} (Type: ${data.message_type})</i></small>
                        </div>
                        ${isSent ? '<button class="edit-msg-btn btn btn-sm btn-warning mt-1">Edit</button><button class="delete-msg-btn btn btn-sm btn-danger mt-1">Delete</button>' : ''}
                    </div>
                </div>
            </div>
        `;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    // Update message in chat after editing
    function updateMessage(data) {
        const messageDiv = document.querySelector(`[data-msg-id="${data.message_id}"]`);
        if (messageDiv) {
            const isSent = data.senderId == <?php echo $_SESSION['supplier_id']; ?>;
            const rowClass = isSent ? 'row justify-content-start' : 'row justify-content-end';
            const backgroundClass = isSent ? 'alert-primary' : 'alert-success';
            const from = isSent ? 'Me' : 'Manager';

            messageDiv.innerHTML = `
                <div class="${rowClass}">
                    <div class="col-sm-10">
                        <div class="shadow-sm alert ${backgroundClass}">
                            <b>${from}: </b>${data.message}<br />
                            <div class="text-right">
                                <small><i>Sent: ${data.created_at} | ${data.read_at || 'NULL'} | Edited: ${data.edited_at} (Type: ${data.message_type})</i></small>
                            </div>
                            ${isSent ? '<button class="edit-msg-btn btn btn-sm btn-warning mt-1">Edit</button><button class="delete-msg-btn btn btn-sm btn-danger mt-1">Delete</button>' : ''}
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // Delete message from chat
    function deleteMessage(messageId) {
        const messageDiv = document.querySelector(`[data-msg-id="${messageId}"]`);
        if (messageDiv) {
            messageDiv.remove();
        }
    }

    // Send message function
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message || !currentChatUserId || !ws || ws.readyState !== WebSocket.OPEN) {
            console.log('Cannot send message:', { message, currentChatUserId, wsState: ws?.readyState });
            return;
        }
        
        ws.send(JSON.stringify({
            type: 'chat',
            senderId: <?php echo $_SESSION['supplier_id']; ?>,
            receiverId: currentChatUserId,
            message: message
        }));
        
        appendMessage({
            senderId: <?php echo $_SESSION['supplier_id']; ?>,
            receiverId: currentChatUserId,
            message: message,
            created_at: new Date().toLocaleTimeString(),
            message_id: Date.now(), // Temporary; replace with actual message_id from server
            message_type: 'text',
            senderName: 'Me' // Placeholder; replace with actual name from server response
        });
        messageInput.value = '';
    }

    // Event Listeners
    document.getElementById('send-message-btn')?.addEventListener('click', sendMessage);

    document.getElementById('message-input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    document.querySelectorAll('.supplier-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.supplier-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            document.querySelector('.no-chat-selected').style.display = 'none';
            document.querySelector('.chat-interface').style.display = 'flex';
            
            currentChatUserId = this.dataset.userId;
            document.getElementById('current-chat-name').textContent = 
                this.querySelector('h4').textContent;
            
            document.getElementById('chat-messages').innerHTML = '<p>Loading messages...</p>';
            fetchMessages(currentChatUserId);
        });
    });

    function fetchMessages(receiverId) {
        const data = { receiver_id: receiverId };
        fetch(`${URLROOT}/supplier/getMessages`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMessages(data.messages);
            } else {
                console.log('Error:', data.message);
                document.getElementById('chat-messages').innerHTML = 'No messages found.';
            }
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
            document.getElementById('chat-messages').innerHTML = 'Error loading messages.';
        });
    }

    function displayMessages(messages) {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.innerHTML = '';
        
        messages.forEach(message => {
            appendMessage({
                message_id: message.message_id,
                senderId: message.sender_id,
                receiverId: message.receiver_id,
                message: message.message,
                created_at: message.created_at,
                read_at: message.read_at || 'NULL',
                edited_at: message.edited_at || null,
                message_type: message.message_type,
                senderName: message.sender_name || (message.sender_id == <?php echo $_SESSION['supplier_id']; ?> ? 'Me' : 'Manager')
            });
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Edit message handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-msg-btn')) {
            const messageDiv = e.target.closest('.alert');
            const messageId = messageDiv.dataset.msgId;
            const currentMessage = messageDiv.querySelector('p').textContent.replace(/^[^:]+:\s*/, '');

            document.getElementById('edit-message-text').value = currentMessage;
            document.querySelector('.edit-message-modal').style.display = 'block';

            document.getElementById('save-edit-btn').onclick = function() {
                const newMessage = document.getElementById('edit-message-text').value;
                fetch(`${URLROOT}/supplier/editMessage`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message_id: messageId, new_message: newMessage })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ws.send(JSON.stringify({
                            type: 'edit',
                            message_id: messageId,
                            new_message: newMessage,
                            user_id: <?php echo $_SESSION['supplier_id']; ?>
                        }));
                        document.querySelector('.edit-message-modal').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error editing message:', error));
            };

            document.getElementById('cancel-edit-btn').onclick = function() {
                document.querySelector('.edit-message-modal').style.display = 'none';
            };
        }
    });

    // Delete message handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-msg-btn')) {
            if (confirm('Are you sure you want to delete this message?')) {
                const messageDiv = e.target.closest('.alert');
                const messageId = messageDiv.dataset.msgId;
                
                fetch(`${URLROOT}/supplier/deleteMessage`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message_id: messageId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ws.send(JSON.stringify({
                            type: 'delete',
                            message_id: messageId,
                            user_id: <?php echo $_SESSION['supplier_id']; ?>
                        }));
                    }
                })
                .catch(error => console.error('Error deleting message:', error));
            }
        }
    });

    // Search functionality
    document.getElementById('manager-search')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.supplier-item').forEach(item => {
            const managerName = item.querySelector('h4')?.textContent.toLowerCase() || '';
            const managerId = item.querySelector('p')?.textContent.toLowerCase() || '';
            item.style.display = 
                managerName.includes(searchTerm) || managerId.includes(searchTerm) 
                ? 'flex' : 'none';
        });
    });

    // Initialize WebSocket when page loads
    document.addEventListener('DOMContentLoaded', initWebSocket);

    // Sidebar toggle (if needed)
    document.querySelector('.toggle-suppliers')?.addEventListener('click', () => {
        document.querySelector('.suppliers-sidebar')?.classList.toggle('active');
    });
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>