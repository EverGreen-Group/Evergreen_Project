<?php
class PhotoHelper {
    // Base upload directory
    private static $uploadDir = 'public/uploads/';

    // Default photos
    private static $defaults = [
        'supplier' => 'default_supplier.jpg',
        'vehicle' => 'default_vehicle.jpg',
        'employee' => 'default_profile.jpg'
    ];

    // Photo directories
    private static $directories = [
        'supplier' => 'supplier_photos/',
        'vehicle' => 'vehicle_photos/',
        'employee' => 'employee_photos/'
    ];

    /**
     * Get the full path for a photo
     * @param string $type Type of photo (supplier, vehicle, employee)
     * @param string|null $filename The filename
     * @return string The full path to the photo
     */
    public static function getPhotoPath($type, $filename = null) {
        if (empty($filename) || $filename === null) {
            return self::$uploadDir . self::$directories[$type] . self::$defaults[$type];
        }
        return self::$uploadDir . self::$directories[$type] . $filename;
    }

    /**
     * Get URL for a photo
     * @param string $type Type of photo
     * @param string|null $filename The filename
     * @return string The URL to the photo
     */
    public static function getPhotoUrl($type, $filename = null) {
        return URLROOT . '/' . self::getPhotoPath($type, $filename);
    }

    /**
     * Check if photo exists
     * @param string $type Type of photo
     * @param string $filename The filename
     * @return bool Whether the photo exists
     */
    public static function photoExists($type, $filename) {
        return file_exists(self::getPhotoPath($type, $filename));
    }

    /**
     * Get appropriate photo URL (returns default if file doesn't exist)
     * @param string $type Type of photo
     * @param string|null $filename The filename
     * @return string The appropriate photo URL
     */
    public static function getSafePhotoUrl($type, $filename = null) {
        if ($filename && self::photoExists($type, $filename)) {
            return self::getPhotoUrl($type, $filename);
        }
        return self::getPhotoUrl($type);
    }
} 