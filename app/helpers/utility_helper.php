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