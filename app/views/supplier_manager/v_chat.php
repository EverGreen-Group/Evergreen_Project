<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">

<main>
    <div class="chat-container">
        <!-- Left Sidebar: Suppliers List -->
        <div class="suppliers-sidebar">
            <div class="search-box">
                <input type="text" id="supplier-search" placeholder="Search suppliers...">
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
                    <div class="chat-request">
                        <div class="request-info">
                            <h4><?php echo htmlspecialchars($request->first_name . ' ' . $request->last_name); ?></h4>
                            <p>SUP<?php echo sprintf('%03d', $request->user_id); ?></p>
                        </div>
                        <div class="request-actions">
                            <button class="accept-btn" onclick="handleRequestAction('accept', '<?php echo $request->source; ?>', <?php echo $request->request_id; ?>)">
                                Accept
                            </button>
                            <button class="reject-btn" onclick="handleRequestAction('reject', '<?php echo $request->source; ?>', <?php echo $request->request_id; ?>)">
                                Reject
                            </button>
                        </div>
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

// Handle supplier selection
document.querySelectorAll('.supplier-item').forEach(item => {
    item.addEventListener('click', function() {
        currentChatUserId = this.dataset.userId;
        const supplierName = this.querySelector('h4').textContent;
        
        // Update UI
        document.querySelectorAll('.supplier-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        // Show chat interface
        document.querySelector('.no-chat-selected').style.display = 'none';
        document.querySelector('.chat-interface').style.display = 'flex';
        
        // Update chat header
        document.getElementById('current-chat-name').textContent = supplierName;
        
        // Load messages
        loadMessages();
    });
});

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

// Enter key to send message
document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Auto refresh messages
setInterval(loadMessages, 3000);

// Search functionality for suppliers
document.getElementById('supplier-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const supplierItems = document.querySelectorAll('.supplier-item');
    
    supplierItems.forEach(item => {
        const supplierName = item.querySelector('h4').textContent.toLowerCase();
        const supplierId = item.querySelector('p').textContent.toLowerCase();
        
        if (supplierName.includes(searchTerm) || supplierId.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
    
    checkEmptyResults();
});

// Add this to handle empty search results
const checkEmptyResults = () => {
    const supplierItems = document.querySelectorAll('.supplier-item');
    const visibleItems = Array.from(supplierItems).filter(item => 
        item.style.display !== 'none'
    );
    
    const existingNoResults = document.querySelector('.no-results');
    if (existingNoResults) {
        existingNoResults.remove();
    }
    
    if (visibleItems.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'no-results';
        noResults.innerHTML = '<p>No suppliers found</p>';
        document.querySelector('.suppliers-list').appendChild(noResults);
    }
};

// Edit message handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-msg-btn')) {
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
    
    fetch(`${URLROOT}/suppliermanager/editMessage`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            msg_id: currentEditMessageId,
            new_message: newMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.edit-message-modal').style.display = 'none';
            loadMessages();
        }
    });
});

// Delete message handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-msg-btn')) {
        if (confirm('Are you sure you want to delete this message?')) {
            const messageDiv = e.target.closest('.message');
            const msgId = messageDiv.dataset.msgId;
            
            fetch(`${URLROOT}/suppliermanager/deleteMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ msg_id: msgId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadMessages();
                }
            });
        }
    }
});

// Update the accept/reject functions
function handleRequestAction(action, source, requestId) {
    const endpoint = action === 'accept' ? 'acceptChatRequest' : 'rejectChatRequest';
    const button = event.target;
    
    // Disable button to prevent double clicks
    button.disabled = true;
    
    fetch(`${URLROOT}/suppliermanager/${endpoint}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            request_id: requestId,
            source: source 
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Remove the request element with animation
            const requestElement = button.closest('.chat-request');
            requestElement.style.opacity = '0';
            setTimeout(() => {
                requestElement.remove();
                // Check if no more requests
                if (document.querySelectorAll('.chat-request').length === 0) {
                    location.reload(); // Refresh if no more requests
                }
            }, 300);
        } else {
            alert('Failed to process request');
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
        button.disabled = false;
    });
}

// Add this to your view file
document.addEventListener('DOMContentLoaded', function() {
    const suppliersSidebar = document.querySelector('.suppliers-sidebar');
    const requestsSidebar = document.querySelector('.requests-sidebar');
    const toggleSuppliers = document.querySelector('.toggle-suppliers');
    const toggleRequests = document.querySelector('.toggle-requests');

    if(toggleSuppliers) {
        toggleSuppliers.addEventListener('click', () => {
            suppliersSidebar.classList.toggle('active');
        });
    }

    if(toggleRequests) {
        toggleRequests.addEventListener('click', () => {
            requestsSidebar.classList.toggle('active');
        });
    }

    // Close sidebars when clicking outside
    document.addEventListener('click', (e) => {
        if(!e.target.closest('.suppliers-sidebar') && 
           !e.target.closest('.toggle-suppliers') && 
           suppliersSidebar?.classList.contains('active')) {
            suppliersSidebar.classList.remove('active');
        }
        
        if(!e.target.closest('.requests-sidebar') && 
           !e.target.closest('.toggle-requests') && 
           requestsSidebar?.classList.contains('active')) {
            requestsSidebar.classList.remove('active');
        }
    });
});
</script>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>