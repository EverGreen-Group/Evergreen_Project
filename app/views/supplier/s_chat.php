<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<main>
    <div class="chat-container">
        <div class="suppliers-sidebar">
            <div class="search-box">
                <input type="text" id="manager-search" placeholder="Search managers...">
                <i class='bx bx-search'></i>
            </div>
            <div class="suppliers-list">
                <?php foreach($data['active_managers'] as $manager): ?>
                    <div class="manager-item" data-user-id="<?php echo $manager->user_id; ?>">
                        <div class="manager-info">
                            <h4><?php echo htmlspecialchars(($manager->first_name && $manager->last_name) ? $manager->first_name . ' ' . $manager->last_name : 'MGR' . sprintf('%03d', $manager->user_id)); ?></h4>
                            <p>MGR<?php echo sprintf('%03d', $manager->user_id);?></p>
                        </div>
                        <div class="status-dot offline"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="chat-area">
            <div class="no-chat-selected">
                <i class='bx bx-message-square-dots'></i>
                <p>Select a manager to start messaging</p>
            </div>
            <div class="chat-interface" style="display: none;">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <h3 id="current-chat-name"></h3>
                        <span class="status">Active now</span>
                    </div>
                </div>
                <div class="messages" id="chat-messages">
                    <p>Loading messages...</p>
                </div>
                <div class="chat-input">
                    <input type="text" id="message-input" placeholder="Type a message...">
                    <button id="send-message-btn">
                        <i class='bx bx-send'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="edit-message-modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Message <span class="close-modal" style="float: right; cursor: pointer;">×</span></h3>
            <textarea id="edit-message-text" rows="3"></textarea>
            <button id="save-edit-btn" class="btn btn-primary">Save</button>
            <button id="cancel-edit-btn" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</main>

<script>
const URLROOT = '<?php echo URLROOT; ?>';
let ws;
let currentChatUserId = null;
const onlineUsers = new Set();

function initWebSocket() {
    ws = new WebSocket('ws://localhost:8080');
    ws.onopen = function() {
        console.log('Connected to WebSocket server');
        ws.send(JSON.stringify({
            type: 'init',
            userId: <?php echo $_SESSION['user_id']; ?>
        }));
    };
    ws.onmessage = function(e) {
        const data = JSON.parse(e.data);
        console.log('WebSocket message received:', data);
        switch (data.type) {
            case 'message':
                if (data.senderId == currentChatUserId && data.receiverId == <?php echo $_SESSION['user_id']; ?>) {
                    appendMessage(data);
                }
                break;
            case 'sent':
                if (data.senderId == <?php echo $_SESSION['user_id']; ?> && data.receiverId == currentChatUserId) {
                    appendMessage(data);
                }
                break;
            case 'status':
                updateUserStatus(data.userId, data.status);
                break;
            case 'message_updated':
                if (data.senderId == currentChatUserId || data.receiverId == currentChatUserId) {
                    updateMessage(data);
                }
                break;
            case 'message_deleted':
                if (data.senderId == currentChatUserId || data.receiverId == currentChatUserId) {
                    deleteMessage(data.message_id);
                }
                break;
        }
    };
    ws.onclose = function() {
        console.log('Disconnected from WebSocket server');
        document.querySelectorAll('.status-dot').forEach(dot => {
            dot.classList.remove('online');
            dot.classList.add('offline');
        });
        setTimeout(() => reconnectWebSocket(), 3000);
    };
    ws.onerror = function(error) {
        console.error('WebSocket error:', error);
    };
}

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
            userId: <?php echo $_SESSION['user_id']; ?>
        }));
    };
    ws.onclose = function() {
        console.log(`Reconnection attempt ${attempt} failed`);
        setTimeout(() => reconnectWebSocket(attempt + 1), 3000 * Math.pow(2, attempt - 1));
    };
    ws.onmessage = ws.onmessage;
    ws.onerror = ws.onerror;
}

function updateUserStatus(userId, status) {
    const userElement = document.querySelector(`.manager-item[data-user-id="${userId}"]`);
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

function appendMessage(data) {
    const messagesDiv = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    const isSent = data.senderId == <?php echo $_SESSION['user_id']; ?>;
    const rowClass = isSent ? 'row justify-content-start' : 'row justify-content-end';
    const backgroundClass = isSent ? 'alert-primary' : 'alert-success';
    const from = data.senderName || 'Manager';

    messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
    messageDiv.dataset.msgId = data.message_id;
    messageDiv.innerHTML = `
        <div class="${rowClass}">
            <div class="col-sm-10">
                <div class="shadow-sm alert ${backgroundClass}">
                    <b>${from}: </b>${data.message}<br />
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

function updateMessage(data) {
    if (data.senderId == currentChatUserId || data.receiverId == currentChatUserId) {
        const messageDiv = document.querySelector(`[data-msg-id="${data.message_id}"]`);
        if (messageDiv) {
            const isSent = data.senderId == <?php echo $_SESSION['user_id']; ?>;
            const rowClass = isSent ? 'row justify-content-start' : 'row justify-content-end';
            const backgroundClass = isSent ? 'alert-primary' : 'alert-success';
            const from = data.senderName || 'Manager';

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
}

function deleteMessage(messageId) {
    const messageDiv = document.querySelector(`[data-msg-id="${messageId}"]`);
    if (messageDiv) {
        messageDiv.remove();
    }
}

function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message || !currentChatUserId || !ws || ws.readyState !== WebSocket.OPEN) {
        console.log('Cannot send message:', { message, currentChatUserId, wsState: ws?.readyState });
        return;
    }
    
    ws.send(JSON.stringify({
        type: 'chat',
        senderId: <?php echo $_SESSION['user_id']; ?>,
        receiverId: currentChatUserId,
        message: message
    }));
    
    fetch(`${URLROOT}/supplier/sendMessage`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ receiver_id: currentChatUserId, message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Error saving message:', data.message);
        }
    })
    .catch(error => console.error('Error saving message:', error));
    
    messageInput.value = '';
}

document.getElementById('send-message-btn')?.addEventListener('click', sendMessage);

document.getElementById('message-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

document.querySelectorAll('.manager-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.manager-item').forEach(i => i.classList.remove('active'));
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
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            displayMessages(data.messages);
        } else {
            console.log('Error from server:', data.message);
            document.getElementById('chat-messages').innerHTML = 'No messages found.';
        }
    })
    .catch(error => {
        console.error('Error fetching messages:', error.message);
        document.getElementById('chat-messages').innerHTML = 'Error loading messages.';
    });
}

function displayMessages(messages) {
    const messagesContainer = document.getElementById('chat-messages');
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = 'No messages found.';
        return;
    }
    
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
            senderName: message.sender_name
        });
    });
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-msg-btn')) {
        console.log('Edit button clicked');
        const messageDiv = e.target.closest('.message');
        const messageId = messageDiv.dataset.msgId;
        const currentMessage = messageDiv.querySelector('b').nextSibling.textContent.trim();

        document.getElementById('edit-message-text').value = currentMessage;
        document.querySelector('.edit-message-modal').style.display = 'block';
        console.log('Modal should be visible');
        console.log('Modal display style:', document.querySelector('.edit-message-modal').style.display);

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
                        user_id: <?php echo $_SESSION['user_id']; ?>,
                        senderId: <?php echo $_SESSION['user_id']; ?>,
                        receiverId: currentChatUserId
                    }));
                    document.querySelector('.edit-message-modal').style.display = 'none';
                }
            })
            .catch(error => console.error('Error editing message:', error));
        };

        document.getElementById('cancel-edit-btn').onclick = function() {
            document.querySelector('.edit-message-modal').style.display = 'none';
        };

        document.querySelector('.close-modal').onclick = function() {
            document.querySelector('.edit-message-modal').style.display = 'none';
        };
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-msg-btn')) {
        console.log('Delete button clicked');
        e.stopPropagation();
        if (confirm('Are you sure you want to delete this message?')) {
            const messageDiv = e.target.closest('.message');
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
                        user_id: <?php echo $_SESSION['user_id']; ?>,
                        senderId: <?php echo $_SESSION['user_id']; ?>,
                        receiverId: currentChatUserId
                    }));
                }
            })
            .catch(error => console.error('Error deleting message:', error));
        }
    }
});

document.getElementById('manager-search')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.manager-item').forEach(item => {
        const managerName = item.querySelector('h4')?.textContent.toLowerCase() || '';
        const managerId = item.querySelector('p')?.textContent.toLowerCase() || '';
        item.style.display = 
            managerName.includes(searchTerm) || managerId.includes(searchTerm) 
            ? 'flex' : 'none';
    });
});

document.addEventListener('DOMContentLoaded', initWebSocket);
</script>

<style>
.edit-message-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
}

.edit-message-modal .modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    width: 400px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.edit-message-modal textarea {
    width: 100%;
    margin-bottom: 10px;
    resize: vertical;
}

.edit-message-modal button {
    margin-right: 10px;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>