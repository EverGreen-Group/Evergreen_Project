<?php
class M_Products{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }
    
    

    public function createProduct($data){
        $sql = "INSERT INTO product(product_name, location, details, code, price, profit, margin, quantity, unit) 
                VALUES(:product_name, :location, :details, :code, :price, :profit, :margin, :quantity, :unit)";

        $this->db->query($sql);
        $this->db->bind(':product_name', $data['product-name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':details', $data['details']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':profit', $data['profit']);
        $this->db->bind(':margin', $data['margin']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
}