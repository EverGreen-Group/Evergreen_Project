<?php
class LandingController extends Controller {
    public function index() {
        $data = [
            'title' => 'Evergreen Tea Factory',
            'description' => 'Premium Ceylon Tea Direct from Source'
        ];
        
        $this->view('landing/index', $data);
    }
} 