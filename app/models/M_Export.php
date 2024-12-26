<?php
 
class M_Export{
    private $db;

    public function __construct(){

        $this->db= new Database();
    }

    public function add_export_data($data){
        $this->db->query('INSERT INTO export_data (stock_name, export_company, export_date, manager_name,export_price, export_quantity, reg_no) VALUES (:stock_name, :export_company, :export_date, :manager_name, :export_price, :export_quantity, :reg_no)');

        $this->db->bind(':stock_name', $data['stock_name']);
        $this->db->bind(':export_company', $data['export_company']);
        $this->db->bind(':export_date', $data['export_date']);
        $this->db->bind(':manager_name', $data['manager_name']);
        $this->db->bind(':export_price', $data['export_price']);
        $this->db->bind(':export_quantity', $data['export_quantity']);
        $this->db->bind(':reg_no', $data['reg_no']);

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
        
    }
}