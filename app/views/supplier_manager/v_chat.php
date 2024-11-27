<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
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
        <!-- Suppliers List -->
        <div class="suppliers-list">
            <div class="search-box">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search suppliers...">
            </div>
            
            <div class="suppliers">
                <div class="supplier active">
                    <img src="<?php echo URLROOT; ?>/public/uploads/supplier_photos/default-supplier.png" alt="Supplier">
                    <div class="supplier-info">
                        <h4>John Doe</h4>
                        <p>SUP001 - Deniyaya Route</p>
                        <span class="last-message">Last message: 5 mins ago</span>
                    </div>
                    <span class="unread-count">2</span>
                </div>
                
                <!-- More suppliers... -->
                <div class="supplier">
                    <img src="<?php echo URLROOT; ?>/public/uploads/supplier_photos/default-supplier.png" alt="Supplier">
                    <div class="supplier-info">
                        <h4>Jane Smith</h4>
                        <p>SUP002 - Morawaka Route</p>
                        <span class="last-message">Last message: Yesterday</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="chat-header">
                <div class="chat-user-info">
                    <h3>John Doe</h3>
                    <span>SUP001 • Online</span>
                </div>
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

.suppliers-list {
    width: 300px;
    background: white;
    border-right: 1px solid var(--grey);
}

.search-box {
    padding: 1rem;
    border-bottom: 1px solid var(--grey);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-box input {
    width: 100%;
    padding: 0.5rem;
    border: none;
    outline: none;
}

.suppliers {
    overflow-y: auto;
    height: calc(100% - 60px);
}

.supplier {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    cursor: pointer;
    transition: background 0.3s;
    position: relative;
}

.supplier:hover, .supplier.active {
    background: #f5f5f5;
}

.supplier img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.supplier-info {
    flex: 1;
}

.supplier-info h4 {
    margin: 0;
    font-size: 0.9rem;
}

.supplier-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #666;
}

.last-message {
    font-size: 0.75rem;
    color: #999;
}

.unread-count {
    background: var(--main);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
}

.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 1rem;
    border-bottom: 1px solid var(--grey);
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

<?php require APPROOT . '/views/inc/components/footer.php'; ?>