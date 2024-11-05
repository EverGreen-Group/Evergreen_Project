<?php
class Pages extends Controller {
    public function __construct() {
        // Any constructor logic if needed
    }

    public function index() {
        $data = [
            'title' => 'Welcome to Evergreen'
        ];
        
        $this->view('pages/landing', $data);
    }
}