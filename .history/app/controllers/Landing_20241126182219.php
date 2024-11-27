<?php
class Landing extends Controller {
    private $productModel;

    public function __construct() {
        // Initialize M_Product model using the model() method
        $this->productModel = $this->model('M_Product');
    }

    public function index() {
        // Get featured products using the same method as Shop controller
        $featuredProducts = $this->productModel->getFeaturedProducts();
        $newArrivals = $this->productModel->getNewArrivals();
        
        $data = [
            'featured_products' => $featuredProducts,
            'new_arrivals' => $newArrivals
        ];

        $this->view('landing/index', $data);
    }
}