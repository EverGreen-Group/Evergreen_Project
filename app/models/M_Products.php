<?php
class M_Products{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }
    
    

    public function createProduct($data){
        $sql = "INSERT INTO product(product_name, location, details, grade, price, quantity, unit,  image_path) 
                VALUES(:product_name, :location, :details, :grade, :price, :quantity, :unit, :image_path)";

        $this->db->query($sql);
        $this->db->bind(':product_name', $data['product-name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':details', $data['details']);
        $this->db->bind(':grade', $data['grade']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':image_path', $data['image_path']);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getproduct(){
        $sql = "SELECT * FROM product";
        $this->db->query($sql);
        
        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllProducts() {
        $sql = "SELECT * FROM product ORDER BY product_name ASC";
        $this->db->query($sql);
        
        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getProductById($id) {
        $this->db->query('SELECT * FROM product WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function deleteProduct($id) {
        $this->db->query('DELETE FROM product WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateProduct($data) {
        $sql = "UPDATE product 
                SET product_name = :product_name,
                    location = :location,
                    details = :details,
                    grade = :grade,
                    price = :price,
                    quantity = :quantity,
                    unit= :unit";
        
        // Only update image if a new one is provided
        if (!empty($data['image_path'])) {
            $sql .= ", image_path = :image_path";
        }
        
        $sql .= " WHERE id = :id";

        $this->db->query($sql);
        
        // Bind all the parameters
        $this->db->bind(':product_name', $data['product-name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':details', $data['details']);
        $this->db->bind(':grade', $data['grade']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':id', $data['id']);
        
        // Only bind image_path if it exists
        if (!empty($data['image_path'])) {
            $this->db->bind(':image_path', $data['image_path']);
        }

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function searchProducts($search) {
        $sql = "SELECT * FROM product WHERE product_name LIKE :search ";
        $this->db->query($sql);
        $this->db->bind(':search', "%$search%");
        
        try {
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
}