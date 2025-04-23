<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<main>
    <!-- Rest of the HTML remains the same -->
    <div class="chat-container">
        <div class="suppliers-sidebar">
            <div class="search-box">
                <input type="text" id="supplier-search" placeholder="Search suppliers...">
                <i class='bx bx-search'></i>
            </div>
            <div class="suppliers-list">
                <?php foreach($data['active_suppliers'] as $supplier): ?>
                    <div class="user-item" data-user-id="<?php echo $supplier->user_id; ?>">
                        <div class="supplier-info">
                            <h4><?php echo htmlspecialchars(($supplier->first_name && $supplier->last_name) ? $supplier->first_name . ' ' . $supplier->last_name : 'SUP' . sprintf('%03d', $supplier->user_id)); ?></h4>
                            <p>SUP<?php echo sprintf('%03d', $supplier->user_id);?></p>
                        </div>
                        <div class="status-dot offline"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="chat-area">
            <div class="no-chat-selected">
                <i class='bx bx-message-square-dots'></i>
                <p>Select a supplier to start messaging</p>
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

    <!-- Edit Message Modal -->
    <div class="edit-message-modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Message <span class="close-modal" style="float: right; cursor: pointer;">×</span></h3>
            <textarea id="edit-message-text" rows="3"></textarea>
            <div class="button-row">
                <button id="save-edit-btn" class="btn btn-primary">Save</button>
                <button id="cancel-edit-btn" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="view-details-modal" style="display: none;">
        <div class="modal-content">
            <h3>Message Details <span class="close-modal" style="float: right; cursor: pointer;">×</span></h3>
            <div id="message-details-content">
                <p><strong>Sent:</strong> <span id="detail-sent-time"></span></p>
                <p><strong>Read:</strong> <span id="detail-read-time"></span></p>
                <p><strong>Edited:</strong> <span id="detail-edited-time"></span></p>
                <p><strong>Type:</strong> <span id="detail-message-type"></span></p>
            </div>
            <div class="button-row">
                <button id="close-details-btn" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Dialog -->
    <div class="confirm-modal" style="display: none;">
        <div class="modal-content">
            <h3>Confirm Action</h3>
            <p id="confirm-message">Are you sure you want to delete this message?</p>
            <div class="button-row">
                <button id="confirm-ok-btn" class="btn btn-danger">OK</button>
                <button id="confirm-cancel-btn" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
</main>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script type="module">
    import Chat from '<?php echo URLROOT; ?>/js/chat.js';

    Chat.init(
        '<?php echo URLROOT; ?>',
        <?php echo $_SESSION['user_id']; ?>,
        ''
    );

    Chat.role = 'Supplier';
    Chat.endpoint = 'manager';

    Chat.bindUserSelection('Supplier', 'manager');
    Chat.bindSearch('supplier-search');
    Chat.bindMessageActions('manager');
</script>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>