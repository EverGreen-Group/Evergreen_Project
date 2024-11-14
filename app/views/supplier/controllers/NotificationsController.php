<?php

class NotificationsController {
    public function notify() {
        // Load the notifications view
        $title = 'Notifications';
        $content = BASE_PATH . 'views/pages/Notifications.php';
        include BASE_PATH . 'views/layouts/main.php';
    }
}
