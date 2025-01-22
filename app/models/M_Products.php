<?php
class M_Products{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    

    public function getStockIdByTypeAndGrade($teaType, $grade) {
        $sql = "SELECT ts.stock_id 
                FROM TeaLeafStock ts
                JOIN LeafTypes tlt ON ts.leaf_type_id = tlt.leaf_type_id
                JOIN LeafGradings tg ON ts.grading_id = tg.grading_id
                WHERE tlt.leaf_type_id = :tea_type 
                AND tg.grading_id = :grade";

        $this->db->query($sql);
        $this->db->bind(':tea_type', $teaType);
        $this->db->bind(':grade', $grade);

        $result = $this->db->single();
        return $result ? $result->stock_id : null;
    }

    public function createProduct($data) {
        // First get the stock_id
        $stockId = $this->getStockIdByTypeAndGrade($data['tea_type'], $data['grade']);
        
        if (!$stockId) {
            error_log("No matching stock found for tea type: {$data['tea_type']} and grade: {$data['grade']}");
            return false;
        }

        $sql = "INSERT INTO Products (
                    stock_id,
                    name, 
                    description, 
                    price_per_kg, 
                    weight, 
                    image_path, 
                    is_available
                ) VALUES (
                    :stock_id,
                    :name, 
                    :description, 
                    :price_per_kg, 
                    :weight, 
                    :image_path, 
                    :is_available
                )";

        $this->db->query($sql);
        
        // Use the provided product name instead of constructing it
        $this->db->bind(':stock_id', $stockId);
        $this->db->bind(':name', $data['product_name']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':price_per_kg', floatval($data['price_per_kg']));
        $this->db->bind(':weight', floatval($data['initial_stock']));
        $this->db->bind(':image_path', $data['image_path'] ?? null);
        $this->db->bind(':is_available', 1);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getLeafTypes() {
        $this->db->query("SELECT leaf_type_id, name FROM LeafTypes");
        return $this->db->resultSet();
    }

    public function getLeafGradings() {
        $this->db->query("SELECT grading_id, name FROM LeafGradings");
        return $this->db->resultSet();
    }

    public function getAllProducts() {
        $this->db->query("SELECT 
                            p.*,
                            ts.current_stock, 
                            ts.last_updated, 
                            p.low_stock_threshold,
                            ts.leaf_type_id, 
                            ts.grading_id 
                          FROM Products p 
                          JOIN TeaLeafStock ts ON p.stock_id = ts.stock_id");

        return $this->db->resultSet();
    }

    public function getTeaTypeById($leafTypeId) {
        $this->db->query("SELECT name FROM LeafTypes WHERE leaf_type_id = :leaf_type_id");
        $this->db->bind(':leaf_type_id', $leafTypeId);
        return $this->db->single()->name ?? 'Unknown';
    }

    public function getGradeById($gradingId) {
        $this->db->query("SELECT name FROM LeafGradings WHERE grading_id = :grading_id");
        $this->db->bind(':grading_id', $gradingId);
        return $this->db->single()->name ?? 'Unknown';
    }

    public function getProductById($productId) {
        $this->db->query("SELECT p.*, 
                             ts.current_stock,
                             lt.name as tea_type,
                             lg.name as grade,
                             ts.last_updated
                      FROM Products p
                      JOIN TeaLeafStock ts ON p.stock_id = ts.stock_id
                      JOIN LeafTypes lt ON ts.leaf_type_id = lt.leaf_type_id
                      JOIN LeafGradings lg ON ts.grading_id = lg.grading_id
                      WHERE p.product_id = :product_id");
                      
        $this->db->bind(':product_id', $productId);
        return $this->db->single();
    }

    public function updateProduct($productId, $data) {
        // Start with the base query
        $sql = "UPDATE Products SET updated_at = CURRENT_TIMESTAMP";
        $params = [];

        // Dynamically add fields that are present in the data
        if (isset($data->name)) {
            $sql .= ", name = :name";
            $params[':name'] = $data->name;
        }
        
        if (isset($data->description)) {
            $sql .= ", description = :description";
            $params[':description'] = $data->description;
        }
        
        if (isset($data->price_per_kg)) {
            $sql .= ", price_per_kg = :price_per_kg";
            $params[':price_per_kg'] = $data->price_per_kg;
        }

        $sql .= " WHERE product_id = :product_id";
        $params[':product_id'] = $productId;

        // Prepare and execute the query
        try {
            $this->db->query($sql);
            
            // Bind all parameters
            foreach ($params as $param => $value) {
                $this->db->bind($param, $value);
            }

            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct($productId) {
        // First, get the product details to check if it has an image
        $this->db->query("SELECT image_path FROM Products WHERE product_id = :product_id");
        $this->db->bind(':product_id', $productId);
        $product = $this->db->single();

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Delete the product
            $this->db->query("DELETE FROM Products WHERE product_id = :product_id");
            $this->db->bind(':product_id', $productId);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to delete product from database");
            }

            // If product had an image, delete it from the filesystem
            if ($product && $product->image_path) {
                $imagePath = PUBLICPATH . 'uploads/products/' . $product->image_path;
                if (file_exists($imagePath) && !unlink($imagePath)) {
                    throw new Exception("Failed to delete product image file");
                }
            }

            // If everything went well, commit the transaction
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // If anything went wrong, rollback the transaction
            $this->db->rollBack();
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }

}