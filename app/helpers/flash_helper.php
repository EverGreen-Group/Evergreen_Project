<?php
// Flash message helper
function setFlashMessage($message, $type = 'success') {
    // Use actual session storage instead of JavaScript injection
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}