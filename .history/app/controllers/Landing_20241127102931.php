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

    public function contact() {
        $data = [
            'title' => 'Contact Us',
            'description' => 'Get in touch with Evergreen Tea Factory'
        ];

        $this->view('landing/contact', $data);
    }

    public function submitContact() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'message_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter message';
            }

            // Make sure no errors
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['message_err'])) {
                // Validated
                $contactModel = $this->model('M_Contact');
                if ($contactModel->saveMessage($data)) {
                    flash('contact_message', 'Message sent successfully');
                    redirect('landing/contact');
                } else {
                    flash('contact_message', 'Something went wrong', 'alert alert-danger');
                    redirect('landing/contact');
                }
            } else {
                // Load view with errors
                $this->view('landing/contact', $data);
            }
        } else {
            redirect('landing/contact');
        }
    }
}