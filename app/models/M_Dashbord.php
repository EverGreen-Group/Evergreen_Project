<?php

class M_Dashbord
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getvalidateStocks()
    {
        $sql = "
        SELECT *
        FROM collections
        WHERE status = 'Awaiting Inventory Addition';
        ";
        
        $this->db->query($sql);
        
        return $this->db->resultSet();
    }

    public function addreport($data)
    {
        $sql = "UPDATE stockvalidate SET collection_id = :collection_id, status = :status, report = :report";

        $this->db->query($sql);
        $this->db->bind(':collection_id', $data['collection_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':report', $data['report']);
        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function gettodaytotalstock()
    {
        $sql = "SELECT SUM(total_quantity) AS total_sum
               FROM collections
               WHERE created_at = CURDATE();";

        $this->db->query($sql);

        return $this->db->single();

    }

    public function getvalidatestockdetails()
    {
        $sql = "SELECT 
               c.collection_id,
               sh.route_id,
               sh.driver_id,
               COUNT(csr.supplier_id) AS total_suppliers
               FROM collections c
               JOIN collection_schedules sh ON c.schedule_id = sh.schedule_id
               JOIN collection_supplier_records csr ON c.collection_id = csr.collection_id;";

        $this->db->query($sql);

        return $this->db->single();
        
    }


    public function getleafoflast7days(){
        $this->db->query("SELECT DATE(finalized_at) as date,
        leaf_type_id,
        SUM(actual_weight_kg) as total_quantity
        FROM bag_usage_history
        WHERE finalized_at >= DATE_SUB(CURRENT_DATE,INTERVAL 7 DAY)
        AND is_finalized = 1
        GROUP BY 
        DATE(finalized_at), leaf_type_id
        ORDER BY date DESC, leaf_type_id
        ");

        return $this->db->resultSet();

    }


    public function getBagForCollection($collectionId) {
        $sql = "SELECT * FROM bag_usage_history buh
         INNER JOIN collections c ON c.collection_id = buh.collection_id
         INNER JOIN leaf_types lt ON buh.leaf_type_id = lt.leaf_type_id
         WHERE c.collection_id = :collection_id AND status = 'Awaiting Inventory Addition'";
         $this->db->query($sql);
         $this->db->bind(':collection_id', $collectionId);
         return $this->db->resultSet();
    }

    public function getCompletedBagForCollection($collectionId) {
        $sql = "SELECT * FROM bag_usage_history buh
         INNER JOIN collections c ON c.collection_id = buh.collection_id
         INNER JOIN leaf_types lt ON buh.leaf_type_id = lt.leaf_type_id
         WHERE c.collection_id = :collection_id AND status != 'Awaiting Inventory Addition'";
         $this->db->query($sql);
         $this->db->bind(':collection_id', $collectionId);
         return $this->db->resultSet();
    }

    public function processApproval($historyId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Get the bag details
            $this->db->query('SELECT * FROM bag_usage_history WHERE history_id = :history_id');
            $this->db->bind(':history_id', $historyId);
            $bagDetails = $this->db->single();
            
            if (!$bagDetails) {
                return ['success' => false, 'message' => 'Bag not found'];
            }
            
            
            // Update bag record
            $this->db->query('UPDATE bag_usage_history 
                            SET is_finalized = 1, 
                                finalized_at = NOW(),
                                action = "approved"
                            WHERE history_id = :history_id');
            $this->db->bind(':history_id', $historyId);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to approve bag');
            }
            
            // Get collection ID from bag details
            $collectionId = $bagDetails->collection_id;
            
            // Check if all bags in this collection are finalized
            $this->db->query('SELECT COUNT(*) as count FROM bag_usage_history 
                            WHERE collection_id = :collection_id 
                            AND is_finalized = 0');
            $this->db->bind(':collection_id', $collectionId);
            $result = $this->db->single();
            $unfinishedBags = $result->count;
            
            if ($unfinishedBags == 0) {
                // All bags are finalized, update collection status
                $this->db->query('UPDATE collections 
                                SET status = :status, 
                                    collection_completed_at = NOW()
                                WHERE collection_id = :collection_id');
                $this->db->bind(':status', 'Completed');
                $this->db->bind(':collection_id', $collectionId);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to update collection status');
                }
            }
            
            // Commit the transaction
            $this->db->commit();
            return ['success' => true, 'message' => 'Bag approved successfully'];
            
        } catch (Exception $e) {
            // Roll back the transaction
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getBagDetails($historyId) {
        $this->db->query('SELECT * FROM bag_usage_history WHERE history_id = :history_id');
        $this->db->bind(':history_id', $historyId);
        return $this->db->single();
    }
    
    // public function getLeafTypeRate($leafTypeId) {
    //     // Get the most recent entry for this leaf type
    //     $this->db->query('SELECT * FROM leaf_type_rates 
    //                       WHERE leaf_type_id = :leaf_type_id 
    //                       ORDER BY id DESC 
    //                       LIMIT 1'); // Assuming 'id' is the primary key
    //     $this->db->bind(':leaf_type_id', $leafTypeId);
    //     return $this->db->single(); // This will return the latest row
    // }
    
    public function approveBag($historyId, $paymentAmount) {
        $this->db->query('UPDATE bag_usage_history 
                        SET payment_amount = :payment_amount, 
                            is_finalized = 1, 
                            finalized_at = NOW(),
                            action = "approved"
                        WHERE history_id = :history_id');
        $this->db->bind(':payment_amount', $paymentAmount);
        $this->db->bind(':history_id', $historyId);
        
        return $this->db->execute();
    }
    
    public function countUnfinishedBagsInCollection($collectionId) {
        $this->db->query('SELECT COUNT(*) as count FROM bag_usage_history 
                        WHERE collection_id = :collection_id 
                        AND is_finalized = 0');
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->single();
        return $result->count;
    }
    
    public function updateCollectionStatus($collectionId, $status) {
        $this->db->query('UPDATE collections 
                        SET status = :status, 
                            updated_at = NOW()
                        WHERE collection_id = :collection_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':collection_id', $collectionId);
        
        return $this->db->execute();
    }


    // UPDATE PART

    // Get bag by history_id
    public function getBagByHistoryId($historyId)
    {
        $this->db->query('SELECT * FROM bag_usage_history WHERE history_id = :history_id');
        $this->db->bind(':history_id', $historyId);
        
        return $this->db->single();
    }

    // Get leaf types for dropdown
    public function getLeafTypes()
    {
        $this->db->query('SELECT * FROM leaf_types ORDER BY name');
        return $this->db->resultSet();
    }

    // Update bag details
    public function updateBag($data)
    {
        $this->db->query('UPDATE bag_usage_history SET 
                        actual_weight_kg = :actual_weight_kg,
                        leaf_age = :leaf_age,
                        moisture_level = :moisture_level,
                        deduction_notes = :deduction_notes,
                        leaf_type_id = :leaf_type_id
                        WHERE history_id = :history_id');
        
        $this->db->bind(':actual_weight_kg', $data['actual_weight_kg']);
        $this->db->bind(':leaf_age', $data['leaf_age']);
        $this->db->bind(':moisture_level', $data['moisture_level']);
        $this->db->bind(':deduction_notes', $data['deduction_notes']);
        $this->db->bind(':leaf_type_id', $data['leaf_type_id']);
        $this->db->bind(':history_id', $data['history_id']);
        
        return $this->db->execute();
    }

    // Get collection ID for a bag to use in redirects
    public function getBagCollectionId($historyId)
    {
        $this->db->query('SELECT collection_id FROM bag_usage_history WHERE history_id = :history_id');
        $this->db->bind(':history_id', $historyId);
        
        $result = $this->db->single();
        return $result ? $result->collection_id : null;
    }


    public function getBagById($id)
    {
        $this->db->query('SELECT * FROM collection_bags WHERE bag_id = :bag_id');
        $this->db->bind(':bag_id', $id);
        
        return $this->db->single();
    }



    // BAGS PART HERE!!!!!

    public function getBagsByStatus($status) {
        $this->db->query('SELECT * FROM collection_bags WHERE status = :status AND is_deleted = 0');
        $this->db->bind(':status', $status);
        
        return $this->db->resultSet();
    }

    public function getInactiveBags() {
        $this->db->query('
            SELECT c.*, buh.*
            FROM collection_bags c
            INNER JOIN (
                SELECT bag_id, MAX(finalized_at) AS latest_finalized
                FROM bag_usage_history
                WHERE is_finalized = 1
                GROUP BY bag_id
            ) AS latest ON latest.bag_id = c.bag_id
            INNER JOIN bag_usage_history buh ON buh.bag_id = latest.bag_id AND buh.finalized_at = latest.latest_finalized
            WHERE c.status = "inactive" AND c.is_deleted = 0
        ');

        return $this->db->resultSet();
    }
    
    public function markAsActive($bagId) {
        $this->db->query('UPDATE collection_bags SET status = :status WHERE bag_id = :bag_id');
        $this->db->bind(':status', 'active');
        $this->db->bind(':bag_id', $bagId);
        
        return $this->db->execute();
    }
    
    public function deleteBag($bagId) {
        $this->db->query('UPDATE collection_bags SET is_deleted = 1 AND status = "active" WHERE bag_id = :bag_id');
        $this->db->bind(':bag_id', $bagId);
        
        return $this->db->execute();
    }
    
    public function addBag($data) {
        $this->db->query('INSERT INTO collection_bags (capacity_kg, status) VALUES (:capacity_kg, :status)');
        $this->db->bind(':capacity_kg', $data['capacity_kg']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->execute();
    }
    public function getLastbagId() {
        $this->db->query('SELECT bag_id FROM collection_bags ORDER BY bag_id DESC LIMIT 1');
        
        return $this->db->single();
    }


    // TOTAL QUANTITY, BAGS, APPROVED, REMAINING BAGS IN A GIVEN COLLECTION FOR THE VALIDATION PAGE


    public function getTotalQuantityInACollection($collectionId)
    {
        $this->db->query('SELECT COUNT(*) AS count, SUM(actual_weight_kg) as sum FROM bag_usage_history WHERE collection_id = :collection_id');
        $this->db->bind(':collection_id', $collectionId);
        
        return $this->db->single();
    }

    public function getBagCountsInCollection($collectionId)
    {
        $this->db->query('SELECT COUNT(*) as finalized_count FROM bag_usage_history WHERE collection_id = :collection_id AND is_finalized = 1');
        $this->db->bind(':collection_id', $collectionId);
        $finalizedCount = $this->db->single()->finalized_count;
    
        $this->db->query('SELECT COUNT(*) as non_finalized_count FROM bag_usage_history WHERE collection_id = :collection_id AND is_finalized = 0');
        $this->db->bind(':collection_id', $collectionId);
        $notFinalizedCount = $this->db->single()->non_finalized_count;
    
        return (object)[
            'finalized_count' => $finalizedCount,
            'not_finalized_count' => $notFinalizedCount
        ];
    }


    public function addLeafRate($data)
    {
        // Prepare query
        $this->db->query('INSERT INTO leaf_type_rates (leaf_type_id, rate) VALUES (:leaf_type_id, :rate)');
        
        // Bind values
        $this->db->bind(':leaf_type_id', $data['leaf_type_id']);
        $this->db->bind(':rate', $data['rate']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getcompletecollections()
    {
        $this->db->query('SELECT * FROM collections WHERE status = "Completed"');
        return $this->db->resultSet();
    }
    public function getBagUsageHistory() {
        $sql = "SELECT * FROM bag_usage_history buh INNER JOIN leaf_types lt ON buh.leaf_type_id = lt.leaf_type_id";
        $this->db->query($sql);
        return $this->db->resultSet();

    }


}
