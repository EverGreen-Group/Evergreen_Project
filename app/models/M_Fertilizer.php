<?php
class M_Fertilizer
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function createFertilizer($data)
    {

        $sql = 'INSERT INTO Fertilizer (fertilizer_name, company_name, details, code, price, quantity, unit,image_path)
        VALUES(:fertilizer_name, :company_name, :details, :code, :price, :quantity, :unit, :image_path)';

        $this->db->query($sql);
        $this->db->bind('fertilizer_name', $data['fertilizer_name']);
        $this->db->bind('company_name', $data['company_name']);
        $this->db->bind('details', $data['details']);
        $this->db->bind('code', $data['code']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('quantity', $data['quantity']);
        $this->db->bind('unit', $data['unit']);
        $this->db->bind('image_path', $data['image_path']);

        //execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function getfertilizer()
    {
        $sql = "SELECT * FROM Fertilizer";
        $this->db->query($sql);

        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFertilizer($id)
    {
        $sql = "DELETE FROM Fertilizer WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function getFertilizerById($id)
    {
        $sql = "SELECT * FROM Fertilizer WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateFertilizer($id, $data)
    {
        $sql = 'UPDATE Fertilizer SET fertilizer_name = :fertilizer_name, company_name = :company_name, details = :details, code = :code, price = :price, quantity = :quantity, unit = :unit, image_path = :image_path WHERE id = :id';

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':fertilizer_name', $data['fertilizer_name']);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':details', $data['details']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':image_path', $data['image_path']);

        return $this->db->execute();
    }

    public function updatFertilizerwhenapprove($id,$quantity)
    {
        $sql = "UPDATE Fertilizer SET quantity=:quantity WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }


    public function get_last_6month_quatity()
    {
        $this->db->query("SELECT DATE_FORMAT(create_at, '%Y-%m') AS month, 
        fertilizer_name, SUM(quantity) AS total_quantity FROM Fertilizer WHERE create_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
        GROUP BY month ORDER BY month ASC;");

        return $this->db->resultset();
    }
}

