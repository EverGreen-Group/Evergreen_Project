<?php

class Complaint {
    /*
    // Function to create a new complaint
    public function create($type, $description, $email, $phone) {
        // Assume you have a database connection set up
        $db = Database::connect();
        $query = $db->prepare("INSERT INTO complaints (type, description, email, phone) VALUES (?, ?, ?, ?)");
        $query->execute([$type, $description, $email, $phone]);
    }*/
    
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function submitComplaint($data) {
        try {
            // Start transaction
            $this->db->beginTransaction();
    
            // Debug: Log transaction start
            error_log('Starting complaint submission transaction');
    
            // Prepare SQL query to insert complaint
            $this->db->query("INSERT INTO supplier_complaints 
                (supplier_id, complaint_type, subject, description, images, priority_level) 
                VALUES 
                (:supplier_id, :complaint_type, :subject, :description, :images, :priority_level)");
    
            // Bind parameters
            $this->db->bind(':supplier_id', $data['supplier_id']);
            $this->db->bind(':complaint_type', $data['complaint_type']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':images', $data['images']);
            $this->db->bind(':priority_level', $data['priority_level']);
    
            // Execute the query
            $result = $this->db->execute();
            
            if (!$result) {
                // Get detailed error information
                $errorInfo = $this->db->errorInfo();
                throw new Exception('Failed to submit complaint: ' . print_r($errorInfo, true));
            }
    
            // Commit transaction
            $this->db->commit();
    
            // Debug: Log successful submission
            error_log('Complaint submitted successfully');
            return true;
    
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            
            // Detailed error logging
            error_log('Complaint Submission Error: ' . $e->getMessage());
            error_log('Complaint Data: ' . print_r($data, true));
            
            return false;
        }
    }

}
