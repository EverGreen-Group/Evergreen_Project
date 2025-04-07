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
        $this->db->bind(':fertilizer_lower_limit', $data['fertilizer_lower_limit']);
        $this->db->bind(':fertilizer_import_lower_limit', $data['fertilizer_import_lower_limit']);
        $this->db->bind(':fertilizer_import_upper_limit', $data['fertilizer_import_upper_limit']);
        $this->db->bind(':leaf_age_1', $data['leaf_age_1']);
        $this->db->bind(':leaf_age_2', $data['leaf_age_2']);
        $this->db->bind(':leaf_age_3', $data['leaf_age_3']);
        

    }
}