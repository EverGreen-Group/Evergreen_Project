<?php
class M_Supplier {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllSuppliers() {
        $this->db->query('
        SELECT 
            s.supplier_id,
            s.is_active,
            u.first_name,
            u.last_name,
            sp.profile_image
        FROM suppliers s 
        JOIN users u ON s.user_id = u.user_id 
        LEFT JOIN supplier_photos sp ON s.supplier_id = sp.supplier_id
        WHERE s.is_active = 1
        ');
        
        return $this->db->resultSet();
    }

    /* CREATE SUPPLIER!!! THIS HAPPENS INSIDE THE APPLICATION */
    public function createSupplier($data) {
        $this->db->query('
            INSERT INTO suppliers (profile_id, contact_number, application_id, latitude, longitude, is_active, is_deleted, number_of_collections, average_collection)
            VALUES (:profile_id, :contact_number, :application_id, :latitude, :longitude, :is_active, :is_deleted, :number_of_collections, :average_collection)
        ');
    
        $this->db->bind(':profile_id', $data['profile_id']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':application_id', $data['application_id']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':is_active', $data['is_active']);
        $this->db->bind(':is_deleted', $data['is_deleted']);
        $this->db->bind(':number_of_collections', $data['number_of_collections']);
        $this->db->bind(':average_collection', $data['average_collection']);
    
        return $this->db->execute();
    }


    public function confirmSupplierRole($applicationId) {
        // Step 1: Get the user_id from the supplier_applications table
        $sql1 = "SELECT user_id, primary_phone FROM supplier_applications WHERE application_id = :application_id";
        $this->db->query($sql1);
        $this->db->bind(':application_id', $applicationId);
        $userIdResult = $this->db->single(); // Assuming single() returns an object

        // Check if userId was found
        if (!$userIdResult) {
            error_log("No user found for application ID: " . $applicationId); // Log the error
            return false; // No user found for the given application ID
        }
        $userId = $userIdResult->user_id;
        $primaryPhone = $userIdResult->primary_phone;

        // Step 2: Get latitude and longitude from application_addresses
        $sql2 = "SELECT latitude, longitude FROM application_addresses WHERE application_id = :application_id";
        $this->db->query($sql2);
        $this->db->bind(':application_id', $applicationId);
        $addressData = $this->db->single(); // Assuming single() returns an object

        // Check if address data was found
        if (!$addressData) {
            error_log("No address found for application ID: " . $applicationId); // Log the error
            return false; // No address found for the given application ID
        }

        // Step 3: Insert the new supplier record into the suppliers table
        $sql3 = "INSERT INTO suppliers (user_id, contact_number application_id, latitude, longitude, preferred_day, is_active, is_deleted) 
                  VALUES (:user_id, :contact_number, :application_id, :latitude, :longitude, 'Monday', 1, 0)";
        $this->db->query($sql3);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':contact_number', $primaryPhone);
        $this->db->bind(':application_id', $applicationId);
        $this->db->bind(':latitude', $addressData->latitude);
        $this->db->bind(':longitude', $addressData->longitude);

        // Step 4: Execute the query and check if it was successful
        if ($this->db->execute()) {
            // Step 5: Update the user's role_id to 5
            $sql4 = "UPDATE users SET role_id = 5 WHERE user_id = :user_id";
            $this->db->query($sql4);
            $this->db->bind(':user_id', $userId);
            return $this->db->execute(); // Returns true on success
        }

        return false; // Return false if the insertion failed
    }

    public function checkApplicationStatus($userId) {
        $sql = "SELECT COUNT(*) as count FROM supplier_applications WHERE user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->count > 0;

    }

    public function getSupplierDetailsByUserId($userId) {
        $sql = "
            SELECT s.*, u.email 
            FROM suppliers s
            LEFT JOIN profiles p on s.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE p.user_id = :user_id AND s.is_deleted = 0
        ";
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single(); // Fetch a single record
        return $result; // Return the supplier details along with user info
    }

    public function getSupplierStatus($supplierId) {
        $sql = "SELECT is_active FROM suppliers WHERE supplier_id = :supplier_id";
        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplierId);
        $result = $this->db->single(); // Fetch the single record

        // Return the actual is_active value
        return $result ? $result->is_active : null; // Return the is_active value or null if not found
    }

    public function updateSupplierStatus($supplierId, $isActive) {
        $sql = "UPDATE suppliers SET is_active = :is_active WHERE supplier_id = :supplier_id";
        $this->db->query($sql);
        $this->db->bind(':is_active', $isActive);
        $this->db->bind(':supplier_id', $supplierId);
        
        return $this->db->execute(); // Returns true on success
    }


    public function getSupplierById($supplierId) {
        $sql = "
        SELECT s.*,p.*,u.email, CONCAT(p.first_name, ' ', p.last_name) AS supplier_name FROM suppliers s
        INNER JOIN profiles p ON p.profile_id = s.profile_id
        INNER JOIN users u ON p.user_id = u.user_id
        WHERE s.supplier_id = :supplier_id;
        ";

        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single();
        
    }

    public function addComplaint($data)
    {
        $this->db->query("INSERT INTO complaints (supplier_id, complaint_type, subject, description, priority, image_path) 
                          VALUES (:supplier_id, :complaint_type, :subject, :description, :priority, :image_path)");
    
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':complaint_type', $data['complaint_type']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':image_path', $data['image_path']);
    
        return $this->db->execute();
    }
    

    public function getComplaints()
    {
        $this->db->query("SELECT c.*, CONCAT(p.first_name, ' ', p.last_name) as supplier_name, p.image_path
                          FROM complaints c 
                          JOIN suppliers s ON c.supplier_id = s.supplier_id 
                          JOIN profiles p On s.profile_id = p.profile_id 
                          WHERE c.status != 'Deleted' 
                          ORDER BY 
                              CASE c.priority 
                                  WHEN 'high' THEN 1 
                                  WHEN 'medium' THEN 2 
                                  WHEN 'low' THEN 3 
                              END,
                              c.created_at DESC");
        
        return $this->db->resultSet();
    }
    
    public function getComplaintById($id)
    {
        $this->db->query("SELECT c.*, CONCAT(p.first_name, ' ', p.last_name) as supplier_name, p.image_path, u.email, p.contact_number as phone 
                          FROM complaints c 
                          JOIN suppliers s ON c.supplier_id = s.supplier_id
                          JOIN profiles p On s.profile_id = p.profile_id
                          JOIN users u on p.user_id = u.user_id 
                          WHERE c.complaint_id = :id AND c.status != 'Deleted'");
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    public function getTotalComplaints()
    {
        $this->db->query("SELECT COUNT(*) as count FROM complaints WHERE status != 'Deleted'");
        $result = $this->db->single();
        return $result->count;
    }

    public function getComplaintsByStatus($status)
    {
        $this->db->query("SELECT c.*, CONCAT(p.first_name, ' ', p.last_name) as supplier_name, p.image_path 
                          FROM complaints c 
                          JOIN suppliers s ON c.supplier_id = s.supplier_id 
                          JOIN profiles p On s.profile_id = p.profile_id 
                          WHERE c.status = :status 
                          ORDER BY 
                              CASE c.priority 
                                  WHEN 'high' THEN 1 
                                  WHEN 'medium' THEN 2 
                                  WHEN 'low' THEN 3 
                              END,
                              c.created_at DESC");
        
        $this->db->bind(':status', $status);
        
        return $this->db->resultSet();
    }

    public function updateStatus($data)
    {
        $this->db->query("UPDATE complaints SET status = :status WHERE complaint_id = :complaint_id");
        
        $this->db->bind(':complaint_id', $data['complaint_id']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->execute();
    }

    public function deleteComplaint($id)
    {
        // Using soft delete by updating to delete
        $this->db->query("UPDATE complaints SET status = 'Deleted' WHERE complaint_id = :id");
        
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    


} 