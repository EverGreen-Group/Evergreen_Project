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
            INSERT INTO suppliers (profile_id, contact_number, application_id, latitude, longitude, address, is_active, is_deleted, number_of_collections, average_collection)
            VALUES (:profile_id, :contact_number, :application_id, :latitude, :longitude, :address, :is_active, :is_deleted, :number_of_collections, :average_collection)
        ');
    
        $this->db->bind(':profile_id', $data['profile_id']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':application_id', $data['application_id']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':is_active', $data['is_active']);
        $this->db->bind(':is_deleted', $data['is_deleted']);
        $this->db->bind(':number_of_collections', $data['number_of_collections']);
        $this->db->bind(':average_collection', $data['average_collection']);
    
        $this->db->execute();

        $this->db->query('
            UPDATE users SET role_id = 5 WHERE user_id = :user_id
        ');

        $this->db->bind(':user_id', $data['user_id']);

        return $this->db->execute();



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
        return $result->count ?? 0;
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


    public function getAllSuppliersDetails() {
        $this->db->query("
            SELECT s.*,p.*,u.email
            FROM suppliers s
            JOIN profiles p ON s.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE s.is_deleted = 0
            ORDER BY s.supplier_id DESC
        ");

        return $this->db->resultSet();
    }


    public function getFilteredSuppliers($supplier_id, $name, $nic, $contact_number, $application_id, $supplier_status) {
        $sql = "
            SELECT s.*,p.*,u.email
            FROM suppliers s
            JOIN profiles p ON s.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE s.is_deleted = 0
        ";

        $params = [];

        if ($supplier_id) {
            $sql .= " AND s.supplier_id = :supplier_id";
            $params[':supplier_id'] = $supplier_id;
        }

        if ($name) {
            $sql .= " AND (p.first_name LIKE :first_name OR p.last_name LIKE :last_name)";
            $params[':first_name'] = '%' . $name . '%';
            $params[':last_name'] = '%' . $name . '%';
        }

        if ($nic) {
            $sql .= " AND p.nic LIKE :nic";
            $params[':nic'] = '%' . $nic . '%';
        }

        if ($contact_number) {
            $sql .= " AND (p.contact_number LIKE :contact_number OR s.contact_number LIKE :supplier_contact)";
            $params[':contact_number'] = '%' . $contact_number . '%';
            $params[':supplier_contact'] = '%' . $contact_number . '%';
        }

        if ($application_id) {
            $sql .= " AND s.application_id = :application_id";
            $params[':application_id'] = $application_id;
        }

        if ($supplier_status) {
            if ($supplier_status == 'Active') {
                $sql .= " AND s.is_active = 1";
            } else if ($supplier_status == 'Inactive') {
                $sql .= " AND s.is_active = 0";
            }
        }

        $sql .= " ORDER BY s.supplier_id DESC";

        $this->db->query($sql);

        // Bind parameters
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    public function getTotalSuppliers() {
        $this->db->query("SELECT COUNT(*) as total FROM suppliers WHERE is_deleted = 0");
        $row = $this->db->single();
        return $row->total;
    }

    public function getActiveSuppliers() {
        $this->db->query("SELECT COUNT(*) as active FROM suppliers WHERE is_active = 1 AND is_deleted = 0");
        $row = $this->db->single();
        return $row->active;
    }

    public function getInactiveSuppliers() {
        $this->db->query("
            SELECT s.supplier_id, s.contact_number as supplier_contact, s.application_id, 
                   s.is_active, s.number_of_collections, s.average_collection,
                   p.first_name, p.last_name, p.nic, p.city, p.contact_number,
                   u.email, u.account_status
            FROM suppliers s
            JOIN profiles p ON s.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE s.is_active = 0 AND s.is_deleted = 0
            ORDER BY s.supplier_id DESC
        ");

        return $this->db->resultSet();
    }

    public function getSupplierProfile($userId) {
        $this->db->query("SELECT profile_id FROM profiles WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $profile = $this->db->single();
        
        if (!$profile) {
            return false;
        }
        
        $profileId = $profile->profile_id;
        
        $this->db->query("SELECT * FROM suppliers WHERE profile_id = :profile_id");
        $this->db->bind(':profile_id', $profileId);
        $supplier = $this->db->single();
        
        if (!$supplier) {
            return false;
        }
        
        $this->db->query("SELECT * FROM profiles WHERE profile_id = :profile_id");
        $this->db->bind(':profile_id', $profileId);
        $profileData = $this->db->single();
        
        $this->db->query("SELECT email FROM users WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $user = $this->db->single();
        
        
        return [
            'profile' => $profileData,
            'supplier' => $supplier,
            'user' => $user
        ];
    }
    
    public function updateSupplierProfile($data) {
        $this->db->beginTransaction();
        
        try {
            $this->db->query("UPDATE suppliers SET contact_number = :contact_number WHERE supplier_id = :supplier_id");
            $this->db->bind(':contact_number', $data['supplier_contact']);
            $this->db->bind(':supplier_id', $data['supplier_id']);
            $this->db->execute();
            
            if (isset($data['image_path']) && !empty($data['image_path'])) {
                $this->db->query("UPDATE profiles SET image_path = :image_path WHERE profile_id = :profile_id");
                $this->db->bind(':image_path', $data['image_path']);
                $this->db->bind(':profile_id', $data['profile_id']);
                $this->db->execute();
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }


    public function getSupplierEarnings($supplierId, $month = 'all', $year = null) {
        $sql = "SELECT * FROM supplier_daily_earnings WHERE supplier_id = :supplier_id";

        if ($month !== 'all' && is_numeric($month)) {
            $sql .= " AND MONTH(collection_date) = :month";
        }
        if ($year !== null) {
            $sql .= " AND YEAR(collection_date) = :year";
        }
        

        $sql .= " ORDER BY collection_date DESC";
        
        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplierId);
        
        if ($month !== 'all' && is_numeric($month)) {
            $this->db->bind(':month', $month);
        }
        if ($year !== null) {
            $this->db->bind(':year', $year);
        }
        
        return $this->db->resultSet();
    }

    


} 