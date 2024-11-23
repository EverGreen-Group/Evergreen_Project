<?php
class M_Fertilizer{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }

    public function createFertilizer($data){

        $sql = 'INSERT INTO Fertilizer (fertilizer_name, company_name, details, code, price, quantity, unit)
        VALUES(:fertilizer_name, :company_name, :details, :code, :price, :quantity, :unit)';

        $this->db->query($sql);
        $this->db->bind('fertilizer_name', $data['fertilizer_name']);
        $this->db->bind('company_name', $data['company_name']);
        $this->db->bind('details', $data['details']);
        $this->db->bind('code', $data['code']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('quantity', $data['quantity']);
        $this->db->bind('unit', $data['unit']);

        //execute
        if($this->db->execute()){
            return true;
        }
        else{
            return false;
        }
        
    }

    public function getfertilizer(){
        $sql = "SELECT * FROM Fertilizer";
        $this->db->query($sql);

        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFertilizer($id){
        $sql = "DELETE FROM Fertilizer WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}

