<?php
    class M_LandInspection {
        private $db;
        private $error = '';
    
        public function __construct() {
            $this->db = new Database();
        }
    
        // Submit land inspection request
        public function submitInspectionRequest($data) {
            try {
                // Start a transaction
                $this->db->beginTransaction();
    
                // Prepare SQL to insert into land_inspection_requests table
                $sql = "INSERT INTO land_inspection_requests 
                        (supplier_id, land_area, location, preferred_date, comments) 
                        VALUES (:supplier_id, :land_area, :location, :preferred_date, :comments)";
    
                // Prepare statement
                $stmt = $this->db->prepare($sql);
    
                // Bind parameters
                $stmt->bindValue(':supplier_id', $data['supplier_id'], PDO::PARAM_INT);
                $stmt->bindValue(':land_area', $data['land_area'], PDO::PARAM_STR);
                $stmt->bindValue(':location', $data['location'], PDO::PARAM_STR);
                $stmt->bindValue(':preferred_date', $data['preferred_date'], PDO::PARAM_STR);
                $stmt->bindValue(':comments', $data['comments'] ?? null, PDO::PARAM_STR);
    
                // Execute the statement
                $result = $stmt->execute();
    
                // Get the last inserted request_id
                $request_id = $this->db->lastInsertId();
    
                // If insertion is successful, create a corresponding entry in land_inspections
                if ($result) {
                    $inspectionSql = "INSERT INTO land_inspections 
                                      (request_id, status) 
                                      VALUES (:request_id, 'pending')";
                    
                    $inspectionStmt = $this->db->prepare($inspectionSql);
                    $inspectionStmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
                    $inspectionResult = $inspectionStmt->execute();
    
                    // Commit the transaction
                    $this->db->commit();
    
                    return $result && $inspectionResult;
                }
    
                // Rollback if anything fails
                $this->db->rollBack();
                return false;
    
            } catch (PDOException $e) {
                // Rollback the transaction in case of error
                $this->db->rollBack();
                $this->error = $e->getMessage();
                return false;
            }
        }
    
        // Get previous land inspection requests for a specific supplier
        public function getPreviousInspectionRequests($supplier_id) {
            try {
                $sql = "SELECT 
                            lr.request_id, 
                            lr.supplier_id, 
                            lr.land_area, 
                            lr.location, 
                            lr.preferred_date, 
                            li.inspection_id, 
                            li.status,
                            li.scheduled_date, 
                            li.scheduled_time 
                        FROM land_inspection_requests lr
                        LEFT JOIN land_inspections li ON lr.request_id = li.request_id
                        WHERE lr.supplier_id = :supplier_id
                        ORDER BY lr.preferred_date DESC";
        
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
                $stmt->execute();
        
                return $stmt->fetchAll(PDO::FETCH_OBJ);
        
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return [];
            }
        }
    
        // Get error from last query
        public function getError() {
            return $this->error;
        }

        public function scheduleInspection($request_id, $scheduled_date, $scheduled_time) {
            try {
                $sql = "UPDATE land_inspections 
                        SET status = 'pending', 
                            scheduled_date = :scheduled_date, 
                            scheduled_time = :scheduled_time 
                        WHERE request_id = :request_id";
        
                $stmt = $this->db->prepare($sql);
                
                $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
                $stmt->bindValue(':scheduled_date', $scheduled_date, PDO::PARAM_STR);
                $stmt->bindValue(':scheduled_time', $scheduled_time, PDO::PARAM_STR);
        
                return $stmt->execute();
        
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }

        public function updateInspectionStatus($request_id, $status) {
            try {
                $sql = "UPDATE land_inspections 
                        SET status = :status
                        WHERE request_id = :request_id";
        
                $stmt = $this->db->prepare($sql);
                
                $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
                $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        
                return $stmt->execute();
        
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }

        
        public function getNextLandInspection($supplier_id) {
            try {
                $sql = "SELECT 
                            li.scheduled_date,
                            li.scheduled_time,
                            li.status
                        FROM land_inspection_requests lr
                        JOIN land_inspections li ON lr.request_id = li.request_id
                        WHERE lr.supplier_id = :supplier_id 
                        AND li.scheduled_date >= CURDATE()
                        AND li.status = 'pending'
                        ORDER BY li.scheduled_date ASC 
                        LIMIT 1";
        
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
                $stmt->execute();
        
                return $stmt->fetch(PDO::FETCH_OBJ);
        
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                error_log("Error fetching next land inspection: " . $e->getMessage());
                return null;
            }
        }


    }
?>