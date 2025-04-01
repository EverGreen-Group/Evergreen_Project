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


function uploadDriverImage($file, $uniqueId) {
    // Set the base upload directory relative to the document root
    $uploadBaseDir = $_SERVER['DOCUMENT_ROOT'] . '/Evergreen_Project/public/uploads/drivers/';
    
    // Create upload directory if it doesn't exist
    if (!file_exists($uploadBaseDir)) {
        mkdir($uploadBaseDir, 0777, true);
    }
    
    // Get file info
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    // Get file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    // Allowed file types
    $allowed = array('jpg', 'jpeg', 'png');
    
    // Check if file type is allowed
    if (in_array($fileActualExt, $allowed)) {
        // Check for errors
        if ($fileError === 0) {
            // Check file size (5MB limit)
            if ($fileSize < 5000000) {
                // Create a unique file name
                $fileNameNew = "driver_" . $uniqueId . "." . $fileActualExt;
                $fileDestination = $uploadBaseDir . $fileNameNew;
                
                // For database, store the public URL path
                $dbFilePath = '/uploads/drivers/' . $fileNameNew;
                
                // Upload file
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    return [
                        'success' => true,
                        'path' => $dbFilePath
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Error moving uploaded file. Permissions issue with directory: ' . $uploadBaseDir
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Your file is too large (max 5MB)'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'There was an error uploading your file'
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'You cannot upload files of this type (allowed: jpg, jpeg, png)'
        ];
    }
}
?> 