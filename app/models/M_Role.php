<?php

class M_Role {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllRoles() {
        $this->db->query("
            SELECT * FROM roles ORDER BY role_id;
            ");
    

        return $this->db->resultSet();
    }



} 