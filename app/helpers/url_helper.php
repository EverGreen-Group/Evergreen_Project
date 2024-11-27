<?php
// Helper functions for URL manipulation

// Redirect to a specific page
function redirect($page) {
    header('Location: ' . URLROOT . '/' . $page);
    exit();
} 

function storePreviousUrl() {
    if (!isset($_SESSION['previous_url'])) {
        $_SESSION['previous_url'] = $_SERVER['REQUEST_URI'];
    }
}

function getPreviousUrl() {
    $previous = $_SESSION['previous_url'] ?? URLROOT;
    unset($_SESSION['previous_url']); // Clear it after use
    return $previous;
} 