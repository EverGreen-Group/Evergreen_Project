class M_SupplierPhoto {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSupplierPhoto($supplierId) {
        $this->db->query("
            SELECT * FROM supplier_photos 
            WHERE supplier_id = :supplier_id
        ");
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single();
    }

    public function updateProfileImage($supplierId, $imagePath) {
        $this->db->query("
            INSERT INTO supplier_photos (supplier_id, profile_image)
            VALUES (:supplier_id, :image_path)
            ON DUPLICATE KEY UPDATE 
                profile_image = :image_path,
                updated_at = CURRENT_TIMESTAMP
        ");
        
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':image_path', $imagePath);
        
        return $this->db->execute();
    }

    public function deleteProfileImage($supplierId) {
        // Get current image path before deleting
        $photo = $this->getSupplierPhoto($supplierId);
        if ($photo && $photo->profile_image) {
            $imagePath = PUBLICPATH . '/uploads/supplier_photos/' . $photo->profile_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->db->query("
            UPDATE supplier_photos 
            SET profile_image = NULL 
            WHERE supplier_id = :supplier_id
        ");
        
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->execute();
    }
} 