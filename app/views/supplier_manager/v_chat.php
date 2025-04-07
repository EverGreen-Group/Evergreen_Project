<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Supplier Communications</h1>
        </div>
    </div>

    <div class="chat-container">
        <!-- Chat Requests -->
        <div class="chat-requests">
            <h2>Chat Requests</h2>
            <div class="request-card">
                <div class="supplier-info">
                    <h4>John Doe</h4>
                    <p>SUP001 - Deniyaya Route</p>
                </div>
                <button class="accept-btn">Accept</button>
            </div>
            <div class="request-card">
                <div class="supplier-info">
                    <h4>Jane Smith</h4>
                    <p>SUP002 - Morawaka Route</p>
                </div>
                <button class="accept-btn">Accept</button>
            </div>
            <!-- More requests... -->
        </div>

        <!-- Chat Area -->
        <div class="chat-area" style="display: none;">
            <div class="chat-header">
                <div class="chat-user-info">
                    <h3 id="chat-supplier-name">John Doe</h3>
                    <span id="chat-supplier-id">SUP001 • Online</span>
                </div>
                <button class="end-chat-btn">End Chat</button>
            </div>

            <div class="messages">
                <div class="message received">
                    <div class="message-content">
                        <p>Good morning, I have a question about today's collection.</p>
                        <span class="time">09:15 AM</span>
                    </div>
                </div>

                <div class="message sent">
                    <div class="message-content">
                        <p>Hello! Sure, how can I help you?</p>
                        <span class="time">09:16 AM</span>
                    </div>
                </div>
            </div>

            <div class="chat-input">
                <input type="text" placeholder="Type a message...">
                <button class="send-btn">
                    <i class='bx bx-send'></i>
                </button>
            </div>
        </div>
    </div>
</main>

<style>
.chat-container {
    display: flex;
    gap: 1rem;
    height: calc(100vh - 200px);
    background: var(--light);
    border-radius: 20px;
    overflow: hidden;
}

.chat-requests {
    width: 300px;
    background: white;
    border-right: 1px solid var(--grey);
    padding: 1rem;
}

.request-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid var(--grey);
    border-radius: 10px;
    margin-bottom: 1rem;
    transition: background 0.3s;
}

.request-card:hover {
    background: #f5f5f5;
}

.accept-btn {
    background: var(--main);
    color: white;
    border: none;
    border-radius: 5px;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.accept-btn:hover {
    background: var(--main-dark);
}

.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 1rem;
    border-bottom: 1px solid var(--grey);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.end-chat-btn {
    background: red;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.end-chat-btn:hover {
    background: darkred;
}

.chat-user-info span {
    font-size: 0.8rem;
    color: #666;
}

.messages {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.message {
    display: flex;
    max-width: 70%;
}

.message.sent {
    margin-left: auto;
}

.message-content {
    padding: 0.8rem;
    border-radius: 10px;
    position: relative;
}

.message.received .message-content {
    background: #f5f5f5;
}

.message.sent .message-content {
    background: var(--main);
    color: white;
}

.time {
    font-size: 0.7rem;
    color: #999;
    margin-top: 0.3rem;
    display: block;
}

.message.sent .time {
    color: rgba(255,255,255,0.8);
}

.chat-input {
    padding: 1rem;
    border-top: 1px solid var(--grey);
    display: flex;
    gap: 1rem;
}

.chat-input input {
    flex: 1;
    padding: 0.8rem;
    border: 1px solid var(--grey);
    border-radius: 20px;
    outline: none;
}

.send-btn {
    background: var(--main);
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.send-btn:hover {
    background: var(--main-dark);
}
</style>

<script>
    // JavaScript to handle accepting chat requests
    document.querySelectorAll('.accept-btn').forEach(button => {
        button.addEventListener('click', function() {
            const requestCard = this.closest('.request-card');
            const supplierName = requestCard.querySelector('h4').innerText;
            const supplierId = requestCard.querySelector('p').innerText.split(' - ')[0]; // Extract supplier ID

            // Update chat area with supplier info
            document.getElementById('chat-supplier-name').innerText = supplierName;
            document.getElementById('chat-supplier-id').innerText = supplierId;

            // Hide chat requests and show chat area
            document.querySelector('.chat-requests').style.display = 'none';
            document.querySelector('.chat-area').style.display = 'flex';
        });
    });

    // JavaScript to handle ending the chat
    document.querySelector('.end-chat-btn').addEventListener('click', function() {
        // Hide chat area and show chat requests again
        document.querySelector('.chat-area').style.display = 'none';
        document.querySelector('.chat-requests').style.display = 'block';
    });
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>