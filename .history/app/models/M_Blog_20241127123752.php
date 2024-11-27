<?php
class M_Blog {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPosts($page = 1, $perPage = 6) {
        $offset = ($page - 1) * $perPage;
        
        $this->db->query('SELECT b.*, c.name as category_name, u.name as author_name 
                         FROM blog_posts b 
                         LEFT JOIN blog_categories c ON b.category_id = c.id 
                         LEFT JOIN users u ON b.user_id = u.id 
                         WHERE b.status = "published" 
                         ORDER BY b.created_at DESC 
                         LIMIT :offset, :perPage');
                         
        $this->db->bind(':offset', $offset);
        $this->db->bind(':perPage', $perPage);
        
        return $this->db->resultSet();
    }

    public function getTotalPosts() {
        $this->db->query('SELECT COUNT(*) as total FROM blog_posts WHERE status = "published"');
        $row = $this->db->single();
        return $row->total;
    }

    public function getCategories() {
        $this->db->query('SELECT c.*, COUNT(p.id) as post_count 
                         FROM blog_categories c 
                         LEFT JOIN blog_posts p ON c.id = p.category_id 
                         WHERE p.status = "published" 
                         GROUP BY c.id');
        return $this->db->resultSet();
    }

    public function getRecentPosts($limit = 5) {
        $this->db->query('SELECT * FROM blog_posts 
                         WHERE status = "published" 
                         ORDER BY created_at DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getPostById($id) {
        $this->db->query('SELECT b.*, c.name as category_name, u.name as author_name 
                         FROM blog_posts b 
                         LEFT JOIN blog_categories c ON b.category_id = c.id 
                         LEFT JOIN users u ON b.user_id = u.id 
                         WHERE b.id = :id AND b.status = "published"');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getRelatedPosts($categoryId, $currentPostId, $limit = 3) {
        $this->db->query('SELECT * FROM blog_posts 
                         WHERE category_id = :category_id 
                         AND id != :current_id 
                         AND status = "published" 
                         ORDER BY RAND() 
                         LIMIT :limit');
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':current_id', $currentPostId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function searchPosts($searchTerm) {
        $searchTerm = "%$searchTerm%";
        $this->db->query('SELECT b.*, c.name as category_name 
                         FROM blog_posts b 
                         LEFT JOIN blog_categories c ON b.category_id = c.id 
                         WHERE (b.title LIKE :search OR b.content LIKE :search) 
                         AND b.status = "published" 
                         ORDER BY b.created_at DESC');
        $this->db->bind(':search', $searchTerm);
        return $this->db->resultSet();
    }
} 