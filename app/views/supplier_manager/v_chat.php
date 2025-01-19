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
                <div class="chat-header">
                    <div class="chat-user-info">
                        <h3 id="current-chat-name"></h3>
                        <span class="status">Active now</span>
                    </div>
                </div>

                <div class="messages" id="chat-messages"></div>
                <div class="chat-input">
                    <input type="text" id="message-input" placeholder="Type a message...">
                    <button id="send-message-btn">
                        <i class='bx bx-send'></i>
                    </button>
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
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ receiver_id: currentChatUserId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && Array.isArray(data.messages)) {
            displayMessages(data.messages);
            // Smooth scroll to bottom after loading messages
            const messagesDiv = document.getElementById('chat-messages');
            messagesDiv.scrollTo({
                top: messagesDiv.scrollHeight,
                behavior: 'smooth'
            });
        }
    });
}

// Send message function
function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-message-btn');
    const message = messageInput.value.trim();
    
    if (!message || !currentChatUserId) return;
    
    // Add loading state
    sendButton.classList.add('loading');
    sendButton.innerHTML = '<div class="loading-indicator"></div>';
    
    fetch('<?php echo URLROOT; ?>/suppliermanager/sendMessage', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            receiver_id: currentChatUserId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            loadMessages();
        }
    })
    .finally(() => {
        // Remove loading state
        sendButton.classList.remove('loading');
        sendButton.innerHTML = '<i class="bx bx-send"></i>';
        
        // Scroll to bottom after new message
        const messagesDiv = document.getElementById('chat-messages');
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}

// Send button click handler
document.getElementById('send-message-btn').addEventListener('click', sendMessage);

// Enter key press handler
document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

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