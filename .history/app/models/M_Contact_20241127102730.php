<?php
class M_Contact {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Save contact message to database
    public function saveMessage($data) {
        // Prepare query
        $this->db->query('INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get all messages (for admin)
    public function getAllMessages() {
        $this->db->query('SELECT * FROM contact_messages ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Get single message by ID (for admin)
    public function getMessageById($id) {
        $this->db->query('SELECT * FROM contact_messages WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Mark message as read (for admin)
    public function markAsRead($id) {
        $this->db->query('UPDATE contact_messages SET is_read = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
} 