<?php

class M_Machine
{
    private $db;

    public function __construct()
    {
        $this->db = new Database; // Assume you have a Database class for DB operations
    }

    public function insertMachineData($data)
    {
        $this->db->query('INSERT INTO machines (machine_name, brand, started_date, last_maintenance, next_maintenance, total_working_hours, special_notes)
                          VALUES (:machine_name, :brand, :started_date, :last_maintenance, :next_maintenance, :total_working_hours, :special_notes)');

        // Bind parameters
        $this->db->bind(':machine_name', $data['machine_name']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':started_date', $data['started_date']);
        $this->db->bind(':last_maintenance', $data['last_maintenance']);
        $this->db->bind(':next_maintenance', $data['next_maintenance']);
        $this->db->bind(':total_working_hours', $data['total_working_hours']);
        $this->db->bind(':special_notes', $data['special_notes']);

        // Execute and return result
        return $this->db->execute();
    }

    public function getmachines()
    {
        $this->db->query('SELECT * FROM machines');
        return $this->db->resultSet();
    }

    public function gettimesofmachine()
    {
        $this->db->query('SELECT id,machine_name,total_working_hours FROM machines');
        return $this->db->resultSet();
    }

    public function updateMachineByStatus($id, $status)
    {
        $this->db->query('UPDATE machines SET status = :status WHERE id = :id');
    
        // Bind the values
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);

        return $this->db->execute();
        
    }
}
