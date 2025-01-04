<?php

class M_stockvalidate
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getvalidateStocks()
    {
        $this->db->query("SELECT 
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
    users u ON d.user_id = u.user_id;");
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

        return $this->db->query($sql);

        

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

        return $this->db->query($sql);

        
    }
}
