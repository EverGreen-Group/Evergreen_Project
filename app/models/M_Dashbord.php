<?php

class M_stockvalidate
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getvalidateStocks($status = 'All')
    {
        $sql = "SELECT 
            CONCAT(u.first_name, ' ', u.last_name) AS full_name,
            s.collection_id,
            s.status,
            s.created_at,
            c.total_quantity
        FROM 
            stockvalidate s
        JOIN 
            collections c ON s.collection_id = c.collection_id
        JOIN 
            collection_schedules cs ON c.schedule_id = cs.schedule_id
        JOIN 
            drivers d ON cs.driver_id = d.driver_id
        JOIN 
            users u ON d.user_id = u.user_id";
        
        if ($status !== 'All') {
            $sql .= " WHERE s.status = :status";
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        $this->db->query($sql);
        
        if ($status !== 'All') {
            $this->db->bind(':status', $status);
        }
        
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

    public function getPaymentsData($startDate, $endDate)
    {
        $this->db->query("
            SELECT 
                buh.supplier_id,
                CONCAT(p.first_name, ' ', p.last_name) AS supplier_name,
                sbi.bank_name,
                sbi.branch_name,
                sbi.account_number,
                sbi.account_holder_name,
                SUM(buh.actual_weight_kg) AS total_weight,
                COUNT(buh.bag_id) AS total_bags,
                COUNT(DISTINCT buh.collection_id) AS unique_collections,
                (SUM(buh.actual_weight_kg) * ltr.rate) 
                    + (COUNT(DISTINCT buh.collection_id) * 150) AS total_payment
            FROM bag_usage_history buh
            JOIN suppliers s ON buh.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id
            LEFT JOIN supplier_bank_info sbi ON s.application_id = sbi.application_id
            JOIN (
                SELECT r1.leaf_type_id, r1.rate
                FROM leaf_type_rates r1
                INNER JOIN (
                    SELECT leaf_type_id, MAX(effective_date) AS max_date
                    FROM leaf_type_rates
                    WHERE effective_date <= :endDate
                    GROUP BY leaf_type_id
                ) r2 
                ON r1.leaf_type_id = r2.leaf_type_id 
                AND r1.effective_date = r2.max_date
            ) ltr ON buh.leaf_type_id = ltr.leaf_type_id
            WHERE buh.action = 'approved'
            AND buh.is_finalized = 1
            AND buh.timestamp BETWEEN :startDate AND :endDate
            GROUP BY buh.supplier_id
        ");
    
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
    
        return $this->db->resultSet();
    }
    
    




}
