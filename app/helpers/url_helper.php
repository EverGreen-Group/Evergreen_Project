<?php
// Helper functions for URL manipulation

// Redirect to a specific page
function redirect($page) {
    header('Location: ' . URLROOT . '/' . $page);
    exit();
} 