<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">

<main>
    <div class="chat-container">
        <!-- Left Sidebar: Suppliers List -->
        <div class="suppliers-sidebar">
            <div class="search-box">
                <input type="text" placeholder="Search suppliers...">
                <i class='bx bx-search'></i>
            </div>
            
            <!-- Active Suppliers List -->
            <div class="suppliers-list">
                <?php foreach($data['active_suppliers'] as $supplier): ?>
                    <div class="supplier-item" data-user-id="<?php echo $supplier->user_id; ?>">
                        <div class="supplier-info">
                            <h4><?php echo htmlspecialchars($supplier->first_name . ' ' . $supplier->last_name); ?></h4>
                            <p>SUP<?php echo sprintf('%03d', $supplier->user_id); ?></p>
                        </div>
                        <div class="status-dot <?php echo $supplier->online_status ? 'online' : 'offline'; ?>"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Middle: Chat Area -->
        <div class="chat-area">
            <!-- Initial State -->
            <div class="no-chat-selected">
                <i class='bx bx-message-square-dots'></i>
                <p>Select a supplier to start messaging</p>
            </div>

            <!-- Chat Interface (Hidden initially) -->
            <div class="chat-interface" style="display: none;">
                <!-- Header -->
                <div class="chat-header">
                    <div class="chat-user-info">
                        <h3 id="current-chat-name"></h3>
                        <span class="status">Active now</span>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="chat-content">
                    <div class="messages" id="chat-messages"></div>
                </div>

                <!-- Input Box (Fixed at Bottom) -->
                <div class="chat-footer">
                    <div class="chat-input">
                        <input type="text" id="message-input" placeholder="Type a message...">
                        <button id="send-message-btn">
                            <i class='bx bx-send'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar: Chat Requests -->
        <div class="requests-sidebar">
            <h3>Chat Requests</h3>
            <?php if(!empty($data['chat_requests'])): ?>
                <?php foreach($data['chat_requests'] as $request): ?>
                    <div class="request-card">
                        <div class="supplier-info">
                            <h4><?php echo htmlspecialchars($request->first_name . ' ' . $request->last_name); ?></h4>
                            <p>SUP<?php echo sprintf('%03d', $request->user_id); ?></p>
                        </div>
                        <button class="accept-btn" data-request-id="<?php echo $request->request_id; ?>" 
                                data-user-id="<?php echo $request->user_id; ?>">
                            Accept
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-requests">No pending requests</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
let currentChatUserId = null;
let currentEditMessageId = null;

// Function to load messages
function loadMessages() {
    if (!currentChatUserId) return;
    
    fetch('<?php echo URLROOT; ?>/suppliermanager/getMessages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            other_user_id: currentChatUserId
        })
    })
    .then(response => response.json())
    .then(messages => {
        const messagesDiv = document.getElementById('chat-messages');
        messagesDiv.innerHTML = '';
        
        messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${msg.outgoing_msg_id == <?php echo $_SESSION['user_id']; ?> ? 'sent' : 'received'}`;
            messageDiv.dataset.msgId = msg.msg_id;
            
            let messageContent = `
                <div class="message-content">
                    <p>${msg.msg}</p>
                    ${msg.edited_at ? '<small class="edited-tag">(edited)</small>' : ''}
                </div>
            `;
            
            // Add edit/delete buttons only for user's own messages
            if (msg.outgoing_msg_id == <?php echo $_SESSION['user_id']; ?>) {
                messageContent += `
                    <div class="message-actions">
                        <button class="edit-msg-btn"><i class='bx bx-edit'></i></button>
                        <button class="delete-msg-btn"><i class='bx bx-trash'></i></button>
                    </div>
                `;
            }
            
            messageDiv.innerHTML = messageContent;
            messagesDiv.appendChild(messageDiv);
        });
        
        // Scroll to bottom
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}

// Send message function
function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message || !currentChatUserId) return;
    
    setLoadingState(true);
    
    fetch('<?php echo URLROOT; ?>/suppliermanager/sendMessage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            receiver_id: currentChatUserId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        setLoadingState(false);
        if (data.success) {
            messageInput.value = '';
            loadMessages();
            const messagesDiv = document.getElementById('chat-messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } else {
            alert('Failed to send message. Please try again.');
        }
    })
    .catch(error => {
        setLoadingState(false);
        console.error('Error:', error);
        alert('An error occurred while sending the message.');
    });
}

// Send button click handler
document.getElementById('send-message-btn').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent form submission if within a form
    sendMessage();
});

// Enter key press handler
document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) { // Allow Shift+Enter for new line
        e.preventDefault(); // Prevent default enter behavior
        sendMessage();
    }
});

// Add visual feedback for send button
document.getElementById('send-message-btn').addEventListener('mousedown', function() {
    this.style.transform = 'scale(0.95)';
});

document.getElementById('send-message-btn').addEventListener('mouseup', function() {
    this.style.transform = 'scale(1)';
});

// Add loading state
function setLoadingState(isLoading) {
    const sendButton = document.getElementById('send-message-btn');
    const messageInput = document.getElementById('message-input');
    
    if (isLoading) {
        sendButton.disabled = true;
        messageInput.disabled = true;
        sendButton.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
    } else {
        sendButton.disabled = false;
        messageInput.disabled = false;
        sendButton.innerHTML = '<i class="bx bx-send"></i>';
    }
}

// Click handler for supplier items
document.querySelectorAll('.supplier-item').forEach(item => {
    item.addEventListener('click', function() {
        // Remove active class from all items
        document.querySelectorAll('.supplier-item').forEach(i => i.classList.remove('active'));
        // Add active class to clicked item
        this.classList.add('active');
        
        // Show chat interface
        document.querySelector('.no-chat-selected').style.display = 'none';
        document.querySelector('.chat-interface').style.display = 'flex';
        
        // Set current chat user
        currentChatUserId = this.dataset.userId;
        document.getElementById('current-chat-name').textContent = 
            this.querySelector('h4').textContent;
        
        // Load messages
        loadMessages();
    });
});

// Accept chat request
document.querySelectorAll('.accept-btn').forEach(button => {
    button.addEventListener('click', function() {
        const requestId = this.dataset.requestId;
        const userId = this.dataset.userId;
        
        fetch('<?php echo URLROOT; ?>/suppliermanager/acceptChatRequest', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ request_id: requestId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Remove request card
                this.closest('.request-card').remove();
                // Refresh suppliers list
                location.reload(); // You might want to handle this more elegantly
            }
        });
    });
});

// Edit message
document.addEventListener('click', function(e) {
    if(e.target.closest('.edit-msg-btn')) {
        const messageDiv = e.target.closest('.message');
        const messageText = messageDiv.querySelector('p').textContent;
        currentEditMessageId = messageDiv.dataset.msgId;
        
        document.getElementById('edit-message-text').value = messageText;
        document.querySelector('.edit-message-modal').style.display = 'block';
    }
});

// Save edited message
document.getElementById('save-edit-btn').addEventListener('click', function() {
    const newMessage = document.getElementById('edit-message-text').value;
    
    fetch('<?php echo URLROOT; ?>/suppliermanager/editMessage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            msg_id: currentEditMessageId,
            new_message: newMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.querySelector('.edit-message-modal').style.display = 'none';
            loadMessages(); // Reload messages to show the edit
        }
    });
});

// Delete message
document.addEventListener('click', function(e) {
    if(e.target.closest('.delete-msg-btn')) {
        if(confirm('Are you sure you want to delete this message?')) {
            const messageDiv = e.target.closest('.message');
            const msgId = messageDiv.dataset.msgId;
            
            fetch('<?php echo URLROOT; ?>/suppliermanager/deleteMessage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    msg_id: msgId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    loadMessages(); // Reload messages to remove the deleted one
                }
            });
        }
    }
});

// Cancel edit
document.getElementById('cancel-edit-btn').addEventListener('click', function() {
    document.querySelector('.edit-message-modal').style.display = 'none';
});

// Close modal if clicked outside
window.addEventListener('click', function(e) {
    if(e.target == document.querySelector('.edit-message-modal')) {
        document.querySelector('.edit-message-modal').style.display = 'none';
    }
});

// Auto refresh messages every 5 seconds
setInterval(() => {
    if (currentChatUserId) {
        loadMessages();
    }
}, 5000);
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>