<?php
// app/models/M_Shift.php
class M_Shift {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllShifts() {
        $this->db->query("SELECT * FROM shifts");
        return $this->db->resultset();
    }
}
?>
