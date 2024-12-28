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
        $this->db->query("SELECT s.collection_id, s.driver_id, s.status, s.created_at, c.total_quantity
                        FROM stockvalidate s, collections c
                         WHERE s.collection_id = c.collection_id;");
        return $this->db->resultSet();
    }
}
