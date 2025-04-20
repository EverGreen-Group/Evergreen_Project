<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">

<!-- Add jQuery before Toastr -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

<main>
    <!-- Rest of the HTML remains the same -->
    <div class="chat-container">
        <div class="managers-sidebar">
            <div class="search-box">
                <input type="text" id="manager-search" placeholder="Search managers...">
                <i class='bx bx-search'></i>
            </div>
            <div class="managers-list">
                <?php foreach($data['active_managers'] as $manager): ?>
                    <div class="user-item" data-user-id="<?php echo $manager->user_id; ?>">
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

    Chat.role = 'Manager';
    Chat.endpoint = 'supplier';

    Chat.bindUserSelection('Manager', 'supplier');
    Chat.bindSearch('manager-search');
    Chat.bindMessageActions('supplier');
</script>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>