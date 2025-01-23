<?php

class M_CollectionApproval
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
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

    public function getAwaitingInventoryCollections() {
        $this->db->query("
        SELECT * FROM collections c

        JOIN collection_schedules cs ON cs.schedule_id = c.schedule_id
        WHERE status = 'Awaiting Inventory Addition'");
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }




}
