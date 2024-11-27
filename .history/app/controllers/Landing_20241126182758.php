<?php
class Landing extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        // Initialize both models
        $this->productModel = $this->model('M_Product');
        $this->categoryModel = $this->model('M_Category');
    }

    public function index() {
        // Get both featured products and categories
        $featuredProducts = $this->productModel->getFeaturedProducts();
        $featuredCategories = $this->categoryModel->getFeaturedCategories();
        
        $data = [
            'featured_products' => $featuredProducts,
            'featured_categories' => $featuredCategories
        ];

        $this->view('landing/index', $data);
    }
}