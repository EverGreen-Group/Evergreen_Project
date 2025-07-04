<?php

class M_Inventory_Config{
    private $db;

    public function __construct()
    {

        $this->db = new Database();
    }

    public function add_inventory_config($data)
    {
        $this->db->query('INSERT INTO inventory_config (normal_leaf_rate, super_leaf_rate, fertilizer_lower_limit, fertilizer_import_lower_limit,fertilizer_import_upper_limit,leaf_age_1,leaf_age_2,leaf_age_3) VALUES (:normal_leaf_rate, :super_leaf_rate, :fertilizer_lower_limit, :fertilizer_import_lower_limit, :fertilizer_import_upper_limit, :leaf_age_1, :leaf_age_2, :leaf_age_3)');
        $this->db->bind(':normal_leaf_rate', $data['normal_leaf_rate']);
        $this->db->bind(':super_leaf_rate', $data['super_leaf_rate']);
        $this->db->bind(':fertilizer_lower_limit', $data['fertilizer_stock_lower']);
        $this->db->bind(':fertilizer_import_lower_limit', $data['fertilizer_stock_mid_low']);
        $this->db->bind(':fertilizer_import_upper_limit', $data['fertilizer_stock_mid_high']);
        $this->db->bind(':leaf_age_1', $data['Leaf_age_1']);
        $this->db->bind(':leaf_age_2', $data['Leaf_age_2']);
        $this->db->bind(':leaf_age_3', $data['Leaf_age_3']);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
        

    }
    public function get_total_tea_weightBymonth() {
        $this->db->query("
           SELECT 
            DATE_FORMAT(finalized_at, '%Y-%m') AS month,
            SUM(actual_weight_kg) AS total_quantity
        FROM bag_usage_history
        WHERE finalized_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY month
        ORDER BY month ASC
        ");
        return $this->db->resultSet();
    }

    public function get_total_tea_weightBymonth_export(){
        $this->db->query("SELECT 
            DATE_FORMAT(create_at, '%Y-%m') AS month,
            SUM(export_quantity) AS total_quantity
        FROM export_data
        WHERE create_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY month
        ORDER BY month ASC
        ");
        return $this->db->resultSet();
    }

    public function get_income_by_month(){
        $this->db->query("SELECT 
            DATE_FORMAT(create_at, '%Y-%m') AS month,
            SUM(export_price * export_quantity) AS total_income
        FROM export_data
        WHERE create_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY month
        ORDER BY month ASC
        ");
        return $this->db->resultSet();
    }



}