<?php
class Landing extends Controller {
    private $productModel;

    public function __construct() {
        // Initialize the M_Product model
        $this->productModel = new M_Product();
    }

    public function index() {
        // Get featured products using the existing method
        $featured_products = $this->productModel->getFeaturedProducts();
        
        $data = [
            'featured_products' => $featured_products
        ];

        $this->view('landing/index', $data);
    }
} 