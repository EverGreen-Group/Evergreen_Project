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
        $this->db->query('INSERT INTO machines (machine_name, brand, started_date, model_number, next_maintenance, total_working_hours, special_notes)
                          VALUES (:machine_name, :brand, :started_date, :model_number, :next_maintenance, :total_working_hours, :special_notes)');

        // Bind parameters
        $this->db->bind(':machine_name', $data['machine_name']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':started_date', $data['started_date']);
        $this->db->bind(':model_number', $data['model_number']);
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

    public function getmachineById($id){

        $this->db->query('SELECT * FROM machines WHERE id = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
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

    public function leafprice($data)
    {
        $this->db->query('INSERT INTO leaf_price (normal_leaf_rate, super_leaf_rate, date) VALUES (:normal_leaf_rate, :super_leaf_rate, :date)');

        // Bind parameters
        $this->db->bind(':leaf_name', $data['leaf_name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':date', $data['date']);

        // Execute and return result
        return $this->db->execute();
    }

    public function getleafprice()
    {
        $this->db->query('SELECT * FROM leaf_price ORDER BY id DESC LIMIT 1');
        return $this->db->resultSet();
    }

    public function machinetimeduration()
    {
        $this->db->query('SELECT DAYNAME(start_time) AS dayname, machine_id, 
        ROUND(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) / 3600, 2) AS total_usage_hours 
        FROM machine_usage WHERE start_time >= CURDATE() - INTERVAL 7 DAY AND end_time IS NOT NULL AND TIMESTAMPDIFF(SECOND, start_time, end_time) >= 0 
        GROUP BY dayname, machine_id;');
        return $this->db->resultSet();
    }
}
