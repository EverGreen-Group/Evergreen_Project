<?php

function flash($name = '') {
    if (isset($_SESSION['flash_messages'][$name])) {
        $flash = $_SESSION['flash_messages'][$name];
        echo '<div class="' . $flash['class'] . '" id="msg-flash">' . $flash['message'] . '</div>';
        unset($_SESSION['flash_messages'][$name]);
    }
} 

?>