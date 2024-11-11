<?php

class M_Partner {

    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }
    public function softDeletePartner($partnerId) {
        $this->db->query('UPDATE driving_partners SET is_deleted = 1 WHERE partner_id = :partner_id');
        $this->db->bind(':partner_id', $partnerId);
        return $this->db->execute();
    }
} 