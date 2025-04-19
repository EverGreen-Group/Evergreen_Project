<?php
/**
 * Send a JSON response with proper headers
 * 
 * @param mixed $data The data to be encoded as JSON
 * @param int $status HTTP status code
 * @return void
 */
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
} 

function getStatusIcon($status) {
    $icons = [
        'pending' => 'time',
        'processing' => 'cog',
        'shipped' => 'car',
        'out_for_delivery' => 'navigation',
        'delivered' => 'check-circle',
        'cancelled' => 'x-circle'
    ];
    
    return $icons[$status] ?? 'help-circle';
} 

//added by theekshana

function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}