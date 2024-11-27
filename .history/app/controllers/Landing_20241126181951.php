<?php
class Landing extends Controller {
    private $productModel;

    public function __construct() {
        // Initialize M_Product model using require_once first
        require_once '../app/models/M_Product.php';
        $this->productModel = new M_Product();
    }

    public function index() {
        // Get featured products using the existing method from M_Product
        $featured_products = $this->productModel->getFeaturedProducts();
        
        // Initialize empty array if no products found
        if ($featured_products === false || $featured_products === null) {
            $featured_products = [];
        }
        
        $data = [
            'featured_products' => $featured_products
        ];

        $this->view('landing/index', $data);
    }
}