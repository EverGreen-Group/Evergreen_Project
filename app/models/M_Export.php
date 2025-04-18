<?php

class M_Export
{
    private $db;

    public function __construct()
    {

        $this->db = new Database();
    }

    public function add_export_data($data)
    {
        $this->db->query('INSERT INTO export_data (stock_name, export_company, note, manager_name,export_price, export_quantity, reg_no) VALUES (:stock_name, :export_company, :note, :manager_name, :export_price, :export_quantity, :reg_no)');

        $this->db->bind(':stock_name', $data['stock_name']);
        $this->db->bind(':export_company', $data['export_company']);
        $this->db->bind(':note', $data['note']);
        $this->db->bind(':manager_name', $data['manager_name']);
        $this->db->bind(':export_price', $data['export_price']);
        $this->db->bind(':export_quantity', $data['export_quantity']);
        $this->db->bind(':reg_no', $data['reg_no']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function get_export_data()
    {
        $this->db->query('SELECT id,stock_name,export_company,note,
        manager_name,export_price,export_quantity,reg_no,DATE(create_at) AS create_at FROM export_data');
        return $this->db->resultSet();
    }

    public function get_export_data_by_id($id)
    {
        $this->db->query('SELECT * FROM export_data WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function update_export_data($id, $data)
    {
        $this->db->query('UPDATE export_data SET stock_name = :stock_name, export_company = :export_company, note = :note, manager_name = :manager_name, export_price = :export_price, export_quantity = :export_quantity, reg_no = :reg_no WHERE id = :id');

        $this->db->bind(':id', $id);
        $this->db->bind(':stock_name', $data['stock_name']);
        $this->db->bind(':export_company', $data['export_company']);
        $this->db->bind(':note', $data['note']);
        $this->db->bind(':manager_name', $data['manager_name']);
        $this->db->bind(':export_price', $data['export_price']);
        $this->db->bind(':export_quantity', $data['export_quantity']);
        $this->db->bind(':reg_no', $data['reg_no']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_lastmonth_exportdata()
    {
        $this->db->query('SELECT id,stock_name,export_price,export_quantity,reg_no,DATE(create_at) AS create_at FROM export_data WHERE create_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)');
        return $this->db->resultSet();

    }

    public function get_tea_export_data_last12months()
    {
        $this->db->query("
        SELECT 
            DATE_FORMAT(create_at, '%Y-%m') AS month,
            stock_name,
            SUM(export_quantity) AS total_quantity
        FROM export_data
        WHERE create_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY month, stock_name
        ORDER BY month ASC
    ");
        return $this->db->resultSet();
    }

}