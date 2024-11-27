<?php
class LandingController extends Controller {
    public function __construct() {
        // Load any models you need
    }

    public function index() {
        $data = [
            'title' => 'Welcome to Evergreen Tea',
            'description' => 'Premium Ceylon Tea from Sri Lanka'
        ];

        $this->view('landing/index', $data);
    }
} 