<?php

class Admin extends Controller {
    private $userModel;

    public function __construct() {
        // Check if user is logged in and is admin
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            redirect('users/login');
        }
        
        $this->userModel = $this->model('M_User');
    }

    public function index() {
        $data = [
            'title' => 'Admin Dashboard'
        ];
        
        $this->view('admin/index', $data);
    }

    public function profile() {
        $userInfo = $this->userModel->getUserById($_SESSION['user_id']);
        
        $data = [
            'title' => 'Admin Profile',
            'user' => $userInfo
        ];
        
        $this->view('admin/profile', $data);
    }

    public function settings() {
        $data = [
            'title' => 'Admin Settings'
        ];
        
        $this->view('admin/settings', $data);
    }
} 