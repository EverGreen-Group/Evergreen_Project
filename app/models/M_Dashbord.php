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
}
