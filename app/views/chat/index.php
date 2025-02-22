
Hello

<div class="chat-area">
    <div class="messages" id="messages">
        <?php
        // Assuming you have sender_id and receiver_id available
        if(isset($data['messages']) && !empty($data['messages'])) {
            foreach($data['messages'] as $message) {
                $messageClass = ($message->sender_id == $_SESSION['user_id']) ? 'sent' : 'received';
                ?>
                <div class="message <?php echo $messageClass; ?>">
                    <div class="message-content"><?php echo htmlspecialchars($message->message); ?></div>
                    <div class="message-time">
                        <?php echo date('H:i', strtotime($message->created_at)); ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <!-- ... rest of chat area ... -->
</div> 