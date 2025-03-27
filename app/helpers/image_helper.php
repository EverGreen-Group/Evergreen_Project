<?php

function uploadVehicleImage($file, $license_plate) {
    $targetDir = "uploads/vehicle_photos/"; 
    $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $targetFile = $targetDir . $license_plate . '.' . $imageFileType; // usingg license number as file name

    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['success' => false, 'message' => 'File is not an image.'];
    }

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => true, 'path' => $targetFile];
    } else {
        return ['success' => false, 'message' => 'Error uploading the image.'];
    }
}
?> 