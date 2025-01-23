<?php
class M_Stock{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    

    public function getTotalStockByTeaLeafType() {
        $sql = "SELECT lt.name AS leaf_type, lt.leaf_type_id,
                       SUM(ts.current_stock) AS total_stock,
                       COUNT(DISTINCT ts.grading_id) AS grading_count
                FROM TeaLeafStock ts
                JOIN LeafTypes lt ON ts.leaf_type_id = lt.leaf_type_id
                GROUP BY lt.leaf_type_id";

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function addStock($teaTypeId, $gradingId, $quantity, $notes) {
        $sql = "INSERT INTO TeaLeafStock (leaf_type_id, grading_id, current_stock, last_added_quantity, notes) 
                VALUES (:leaf_type_id, :grading_id, :quantity, :quantity, :notes)";

        $this->db->query($sql);
        $this->db->bind(':leaf_type_id', $teaTypeId);
        $this->db->bind(':grading_id', $gradingId);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':notes', $notes);

        try {
            return $this->db->execute(); // Execute the query
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getTeaTypes() {
        $this->db->query("SELECT leaf_type_id, name FROM LeafTypes");
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

    public function getGradingsByTeaType($teaTypeId) {
        $this->db->query("SELECT grading_id, name FROM LeafGradings WHERE leaf_type_id = :teaTypeId");
        $this->db->bind(':teaTypeId', $teaTypeId);
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

    public function getStockDetailsByTeaType($teaTypeId) {
        $this->db->query("
            SELECT g.name AS grading_name, 
                   SUM(ts.current_stock) AS total_stock, 
                   MAX(ts.last_added_quantity) AS last_added, 
                   MAX(ts.last_deducted_quantity) AS last_deducted
            FROM TeaLeafStock ts
            JOIN LeafGradings g ON ts.grading_id = g.grading_id
            WHERE ts.leaf_type_id = :teaTypeId
            GROUP BY g.grading_id
        ");
        $this->db->bind(':teaTypeId', $teaTypeId);
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

}