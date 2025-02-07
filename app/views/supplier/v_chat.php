<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<?php
error_log("Supplier Managers Data: " . print_r($data['supplier_managers'], true));
?>

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
                <?php if(empty($data['supplier_managers'])): ?>
                    <div class="no-managers">
                        <p>No supplier managers found</p>
                        <p class="debug-info" style="color: #666; font-size: 0.8em;">
                            <?php 
                            echo "Session User ID: " . $_SESSION['user_id'] . "<br>";
                            echo "Total Managers: " . count($data['supplier_managers']);
                            ?>
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach($data['supplier_managers'] as $manager): ?>
                        <div class="supplier-item <?php echo $manager->chat_status ? 'active' : ''; ?>" 
                             data-user-id="<?php echo $manager->user_id; ?>">
                            <div class="supplier-info">
                                <h4><?php echo htmlspecialchars($manager->first_name . ' ' . $manager->last_name); ?></h4>
                                <p>MGR<?php echo sprintf('%03d', $manager->user_id); ?></p>
                            </div>
                            <?php if(!$manager->chat_status): ?>
                                <?php if($manager->request_status == 'pending'): ?>
                                    <span class="request-pending">Request Pending</span>
                                <?php else: ?>
                                    <button class="request-chat-btn" onclick="sendChatRequest(<?php echo $manager->user_id; ?>)">
                                        Request Chat
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="status-dot <?php echo $manager->chat_status ? 'online' : 'offline'; ?>"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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

                <div class="messages" id="chat-messages"></div>
                <div class="chat-input">
                    <input type="text" id="message-input" placeholder="Type a message...">
                    <button id="send-message-btn">
                        <i class='bx bx-send'></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Sidebar: Active Chats -->
        <div class="requests-sidebar">
            <h3>Active Chats</h3>
            <?php if(!empty($data['active_chats'])): ?>
                <?php foreach($data['active_chats'] as $chat): ?>
                    <div class="request-card">
                        <div class="supplier-info">
                            <h4><?php echo htmlspecialchars($chat->first_name . ' ' . $chat->last_name); ?></h4>
                            <p>MGR<?php echo sprintf('%03d', $chat->user_id); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-requests">No active chats</p>
            <?php endif; ?>
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
let currentChatUserId = null;
let currentEditMessageId = null;

// Function to load messages
function loadMessages() {
    if (!currentChatUserId) return;
    
    fetch('<?php echo URLROOT; ?>/supplier/getMessages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ receiver_id: currentChatUserId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && Array.isArray(data.messages)) {
            displayMessages(data.messages);
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
    
    sendButton.classList.add('loading');
    sendButton.innerHTML = '<div class="loading-indicator"></div>';
    
    fetch('<?php echo URLROOT; ?>/supplier/sendMessage', {
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
        sendButton.classList.remove('loading');
        sendButton.innerHTML = '<i class="bx bx-send"></i>';
        const messagesDiv = document.getElementById('chat-messages');
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}

// Send chat request function
function sendChatRequest(managerId) {
    fetch(`${URLROOT}/supplier/sendChatRequest`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ manager_id: managerId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Chat request sent successfully!');
            // Update the button to show "Request Pending"
            const button = event.target;
            const supplierItem = button.closest('.supplier-item');
            button.replaceWith(createPendingSpan());
        } else {
            alert('Failed to send chat request: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the chat request');
    });
}

function createPendingSpan() {
    const span = document.createElement('span');
    span.className = 'request-pending';
    span.textContent = 'Request Pending';
    return span;
}

// Event Listeners
document.getElementById('send-message-btn').addEventListener('click', sendMessage);

document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

document.querySelectorAll('.supplier-item').forEach(item => {
    item.addEventListener('click', function() {
        if (this.querySelector('.request-chat-btn')) return; // Skip if it's not an active chat
        
        document.querySelectorAll('.supplier-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        document.querySelector('.no-chat-selected').style.display = 'none';
        document.querySelector('.chat-interface').style.display = 'flex';
        
        currentChatUserId = this.dataset.userId;
        document.getElementById('current-chat-name').textContent = 
            this.querySelector('h4').textContent;
        
        loadMessages();
    });
});

// Message editing and deleting functionality
document.addEventListener('click', function(e) {
    if(e.target.closest('.edit-msg-btn')) {
        const messageDiv = e.target.closest('.message');
        const messageText = messageDiv.querySelector('p').textContent;
        currentEditMessageId = messageDiv.dataset.msgId;
        
        document.getElementById('edit-message-text').value = messageText;
        document.querySelector('.edit-message-modal').style.display = 'block';
    }
});

document.getElementById('save-edit-btn').addEventListener('click', function() {
    const newMessage = document.getElementById('edit-message-text').value;
    
    fetch('<?php echo URLROOT; ?>/supplier/editMessage', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            msg_id: currentEditMessageId,
            new_message: newMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.querySelector('.edit-message-modal').style.display = 'none';
            loadMessages();
        }
    });
});

document.addEventListener('click', function(e) {
    if(e.target.closest('.delete-msg-btn')) {
        if(confirm('Are you sure you want to delete this message?')) {
            const messageDiv = e.target.closest('.message');
            const msgId = messageDiv.dataset.msgId;
            
            fetch('<?php echo URLROOT; ?>/supplier/deleteMessage', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ msg_id: msgId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    loadMessages();
                }
            });
        }
    }
});

// Modal controls
document.getElementById('cancel-edit-btn').addEventListener('click', function() {
    document.querySelector('.edit-message-modal').style.display = 'none';
});

window.addEventListener('click', function(e) {
    if(e.target == document.querySelector('.edit-message-modal')) {
        document.querySelector('.edit-message-modal').style.display = 'none';
    }
});

// Auto refresh messages
setInterval(() => {
    if (currentChatUserId) {
        loadMessages();
    }
}, 5000);

// Search functionality
document.getElementById('manager-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const managerItems = document.querySelectorAll('.supplier-item');
    
    managerItems.forEach(item => {
        const managerName = item.querySelector('h4').textContent.toLowerCase();
        const managerId = item.querySelector('p').textContent.toLowerCase();
        
        if (managerName.includes(searchTerm) || managerId.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 