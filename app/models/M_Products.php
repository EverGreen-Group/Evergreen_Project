<?php
class M_Products{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }
    
    

    public function createProduct($data){
        $sql = "INSERT INTO Product(product_name, location, details, price, profit, margin, quantity) VALUES(:product_name, :location, :details, :price, :profit, :margin, :quantity)";



        $this->db->query($sql);
        $this->db->bind(':product_name', $data['product-name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':details', $data['details']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':profit', $data['profit']);
        $this->db->bind(':margin', $data['margin']);
        $this->db->bind(':quantity', $data['quantity']);

        //execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}