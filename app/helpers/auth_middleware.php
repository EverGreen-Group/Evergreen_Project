<?php
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . URLROOT . "/auth/login");
        exit;
    }
}
?>
