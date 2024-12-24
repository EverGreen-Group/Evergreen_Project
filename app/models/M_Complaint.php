<?php

class M_Complaint {
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
    
            // Debug logging - print out the entire data array
            error_log('Complaint Data Before Submission: ' . print_r($data, true));
    
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
    
            // Debug: Add error info retrieval
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

    public function getAllComplaints() {
        try {
            $this->db->query("SELECT 
                complaint_id, 
                supplier_id, 
                complaint_type, 
                subject,
                description, 
                submitted_date, 
                submitted_time,
                viewed 
                FROM supplier_complaints 
                ORDER BY submitted_date DESC, submitted_time DESC");
            
            $result = $this->db->resultset();
            return $result ? $result : [];
        } catch (Exception $e) {
            error_log("Exception in getAllComplaints: " . $e->getMessage());
            return [];
        }
    }  

    public function getUnviewedComplaintsCount() {
        try {
            $this->db->query("SELECT COUNT(*) as count FROM supplier_complaints WHERE viewed = 0");
            $result = $this->db->single();
            
            // Check if result is false or null
            if ($result === false || $result === null) {
                error_log("Error in getUnviewedComplaintsCount: Query failed");
                return 0;
            }
            
            return isset($result->count) ? $result->count : 0;
        } catch (Exception $e) {
            error_log("Exception in getUnviewedComplaintsCount: " . $e->getMessage());
            return 0;
        }
    }


    public function getNewComplaintsLastWeek() {
        try {
            $this->db->query("SELECT COUNT(*) as count FROM supplier_complaints 
                             WHERE submitted_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)");
            $result = $this->db->single();
            
            // Check if result is false or null
            if ($result === false || $result === null) {
                error_log("Error in getNewComplaintsLastWeek: Query failed");
                return 0;
            }
            
            return isset($result->count) ? $result->count : 0;
        } catch (Exception $e) {
            error_log("Exception in getNewComplaintsLastWeek: " . $e->getMessage());
            return 0;
        }
    }

    public function markComplaintAsViewed($complaintId) {
        try {
            $this->db->query("UPDATE supplier_complaints SET viewed = 1 WHERE complaint_id = :complaint_id");
            $this->db->bind(':complaint_id', $complaintId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Exception in markComplaintAsViewed: " . $e->getMessage());
            return false;
        }
    }

    public function getComplaintTypeStats() {
        try {
            $this->db->query("SELECT 
                complaint_type,
                COUNT(*) as count
                FROM supplier_complaints 
                GROUP BY complaint_type");
            
            $result = $this->db->resultset();
            return $result ? $result : [];
        } catch (Exception $e) {
            error_log("Exception in getComplaintTypeStats: " . $e->getMessage());
            return [];
        }
    }

    public function getViewedComplaintsCount() {
        try {
            $this->db->query("SELECT COUNT(*) as count FROM supplier_complaints WHERE viewed = 1");
            $result = $this->db->single();
            
            if ($result === false || $result === null) {
                error_log("Error in getViewedComplaintsCount: Query failed");
                return 0;
            }
            
            return isset($result->count) ? $result->count : 0;
        } catch (Exception $e) {
            error_log("Exception in getViewedComplaintsCount: " . $e->getMessage());
            return 0;
        }
    }

}
