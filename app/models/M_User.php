<?php

class M_User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        $sql = "INSERT INTO users (email, first_name, last_name, nic, gender, date_of_birth, password, role_id, approval_status) 
                VALUES (:email, :first_name, :last_name, :nic, :gender, :date_of_birth, :password, :role_id, :approval_status)";
        
        $this->db->query($sql);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':approval_status', $data['approval_status']);
    
        return $this->db->execute();
    }

    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function login($email, $password) {
        $user = $this->findUserByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function findUserByNIC($nic) {
        $this->db->query("SELECT * FROM users WHERE nic = :nic");
        $this->db->bind(':nic', $nic);
        return $this->db->single();
    }
}
?>