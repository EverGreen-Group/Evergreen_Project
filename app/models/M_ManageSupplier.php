<?php 

    class M_UpdateSupplier {
        private $db;
    
        public function __construct() {
            $this->db = new Database();
        }
    
        public function getSupplierByUserId($userId) {
            $this->db->query('SELECT * FROM users WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
    
            return $this->db->single();
        }
    
        public function updateSupplier($data) {
            $this->db->query('UPDATE users SET 
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                nic = :nic
                WHERE user_id = :user_id');
    
            // Bind values
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':nic', $data['nic']);
    
            // Execute
            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }


?>