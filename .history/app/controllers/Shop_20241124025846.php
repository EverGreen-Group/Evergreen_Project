<?php
require_once '../app/models/M_Product.php';
require_once '../app/models/M_Order.php';
require_once '../app/models/M_Cart.php';
require_once '../app/models/M_Category.php';
require_once '../app/models/M_Review.php';
require_once '../app/helpers/auth_middleware.php';

class Shop extends Controller {
    private $productModel;
    private $orderModel;
    private $cartModel;
    private $categoryModel;
    private $reviewModel;

    public function __construct() {
        $this->productModel = new M_Product();
        $this->orderModel = new M_Order();
        $this->cartModel = new M_Cart();
        $this->categoryModel = new M_Category();
        $this->reviewModel = new M_Review();
    }

    // Display shop homepage with categories and featured products
    public function index() {
        $categories = $this->categoryModel->getAllCategories();
        $featuredProducts = $this->productModel->getFeaturedProducts();
        $newArrivals = $this->productModel->getNewArrivals();
        $bestSellers = $this->productModel->getBestSellers();

        if(isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
            $stats = [
                'total_products' => $this->productModel->getTotalProducts(),
                'total_orders' => $this->orderModel->getTotalOrders(),
                'total_revenue' => $this->orderModel->getTotalRevenue(),
                'pending_orders' => $this->orderModel->getPendingOrdersCount()
            ];
        }

        $data = [
            'categories' => $categories,
            'featured_products' => $featuredProducts,
            'new_arrivals' => $newArrivals,
            'best_sellers' => $bestSellers,
            'stats' => $stats ?? null
        ];

        $this->view('shop/index', $data);
    }

    // View products by category
    public function category($categoryId) {
        $category = $this->categoryModel->getCategoryById($categoryId);
        if (!$category) {
            redirect('shop');
        }

        $products = $this->productModel->getProductsByCategory($categoryId);
        $data = [
            'category' => $category,
            'products' => $products
        ];

        $this->view('shop/category', $data);
    }

    // View single product with reviews
    public function product($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            redirect('shop');
        }

        $reviews = $this->reviewModel->getProductReviews($id);
        $relatedProducts = $this->productModel->getRelatedProducts($id, $product->category_id);

        $data = [
            'product' => $product,
            'reviews' => $reviews,
            'related_products' => $relatedProducts
        ];

        $this->view('shop/product', $data);
    }

    // Cart Management
    public function cart() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $cartModel = $this->model('M_Cart');
        $data = [
            'cart_items' => $cartModel->getCartItems($_SESSION['user_id']),
            'cart_count' => $cartModel->getCartCount($_SESSION['user_id'])
        ];

        $this->view('shop/cart', $data);
    }

    // Cart Actions
    public function addToCart() {
        if (!isLoggedIn()) {
            jsonResponse(['success' => false, 'message' => 'Please login first']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }

        $data = json_decode(file_get_contents('php://input'));
        
        if (!isset($data->product_id) || !isset($data->quantity)) {
            jsonResponse(['success' => false, 'message' => 'Invalid data']);
        }

        $cartModel = $this->model('M_Cart');
        $success = $cartModel->addToCart(
            $_SESSION['user_id'],
            $data->product_id,
            $data->quantity
        );

        if ($success) {
            jsonResponse([
                'success' => true,
                'cart_count' => $cartModel->getCartCount($_SESSION['user_id']),
                'message' => 'Product added to cart'
            ]);
        }

        jsonResponse([
            'success' => false,
            'message' => 'Failed to add product to cart'
        ]);
    }

    public function updateCart() {
        if (!isLoggedIn()) {
            jsonResponse(['success' => false, 'message' => 'Please login first']);
        }

        $data = json_decode(file_get_contents('php://input'));
        
        if (!isset($data->product_id) || !isset($data->quantity)) {
            jsonResponse(['success' => false, 'message' => 'Invalid data']);
        }

        $cartModel = $this->model('M_Cart');
        $success = $cartModel->updateCartItem(
            $_SESSION['user_id'],
            $data->product_id,
            $data->quantity
        );

        if ($success) {
            $total = $cartModel->getCartTotal($_SESSION['user_id']);
            jsonResponse([
                'success' => true,
                'totals' => [
                    'subtotal' => $total,
                    'shipping' => 0 // Will be calculated based on selected method
                ],
                'message' => 'Cart updated'
            ]);
        }

        jsonResponse([
            'success' => false,
            'message' => 'Failed to update cart'
        ]);
    }

    public function removeFromCart() {
        if (!isLoggedIn()) {
            die(json_encode(['success' => false, 'message' => 'Please login first']));
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die(json_encode(['success' => false, 'message' => 'Invalid request method']));
        }

        $data = json_decode(file_get_contents('php://input'));
        
        if (!isset($data->product_id)) {
            die(json_encode(['success' => false, 'message' => 'Product ID is required']));
        }

        $cartModel = $this->model('M_Cart');
        if ($cartModel->removeItem($_SESSION['user_id'], $data->product_id)) {
            die(json_encode(['success' => true]));
        } else {
            die(json_encode(['success' => false, 'message' => 'Failed to remove item']));
        }
    }

    // Checkout Process
    public function checkout() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $cartModel = $this->model('M_Cart');
        $cartItems = $cartModel->getCartItems($_SESSION['user_id']);
        $total = $cartModel->getCartTotal($_SESSION['user_id']);

        if (empty($cartItems)) {
            redirect('shop/cart');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Create order first
            $orderModel = $this->model('M_Order');
            $orderId = $orderModel->createOrder($_SESSION['user_id'], $total);

            if ($orderId) {
                // Redirect to payment gateway
                redirect('shop/payment/' . $orderId);
            } else {
                die('Something went wrong');
            }
        }

        $data = [
            'cart_items' => $cartItems,
            'total' => $total
        ];

        $this->view('shop/checkout', $data);
    }

    public function payment($orderId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $orderModel = $this->model('M_Order');
        $order = $orderModel->getOrder($orderId);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            redirect('shop');
        }

        // Here you would integrate with your payment gateway
        // For this example, we'll just mark the order as paid
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process payment
            // If payment successful:
            $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
            $this->db->bind(':status', 'paid');
            $this->db->bind(':id', $orderId);
            
            if ($this->db->execute()) {
                redirect('shop/order_success/' . $orderId);
            }
        }

        $data = [
            'order' => $order
        ];

        $this->view('shop/payment', $data);
    }

    public function order_success($orderId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $orderModel = $this->model('M_Order');
        $order = $orderModel->getOrder($orderId);
        $orderItems = $orderModel->getOrderItems($orderId);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            redirect('shop');
        }

        $data = [
            'order' => $order,
            'items' => $orderItems
        ];

        $this->view('shop/order_success', $data);
    }

    // Order Management
    public function orders() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $orderModel = new M_Order();
        $userId = $_SESSION['user_id']; // Make sure this session variable exists
        
        $orders = $orderModel->getUserOrders($userId);
        
 
        
        $data = [
            'orders' => $orders
        ];

        $this->view('shop/orders', $data);
    }

    public function orderDetails($orderId) {
        requireAuth();

        $order = $this->orderModel->getOrderDetails($orderId);
        if (!$order || $order->user_id !== $_SESSION['user_id']) {
            redirect('shop/orders');
        }

        $this->view('shop/orderDetails', ['order' => $order]);
    }

    public function orderConfirmation($orderId) {
        requireAuth();

        $order = $this->orderModel->getOrderDetails($orderId);
        if (!$order || $order->user_id !== $_SESSION['user_id']) {
            redirect('shop/orders');
        }

        $this->view('shop/orderConfirmation', ['order' => $order]);
    }

    // Product Reviews
    public function addReview() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reviewData = [
                'product_id' => $_POST['product_id'],
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'],
                'comment' => $_POST['comment']
            ];

            if ($this->reviewModel->addReview($reviewData)) {
                flash('review_message', 'Review submitted successfully');
            } else {
                flash('review_message', 'Failed to submit review', 'alert alert-danger');
            }
            redirect("shop/product/{$_POST['product_id']}");
        }
    }

    // Search functionality
    public function search() {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

        $results = $this->productModel->searchProducts($query, $category, $sort);
        $categories = $this->categoryModel->getAllCategories();

        $data = [
            'query' => $query,
            'products' => $results,
            'categories' => $categories,
            'selected_category' => $category,
            'sort' => $sort
        ];

        $this->view('shop/search', $data);
    }

    public function categories() {
        $categories = $this->categoryModel->getAllCategoriesWithProducts();
        $featuredCategories = $this->categoryModel->getFeaturedCategories();
        
        $data = [
            'categories' => $categories,
            'featured_categories' => $featuredCategories,
            'title' => 'Shop Categories'
        ];

        $this->view('shop/categories', $data);
    }

    public function categoryProducts($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            redirect('shop/categories');
        }

        $products = $this->productModel->getProductsByCategory($id);
        $data = [
            'category' => $category,
            'products' => $products
        ];

        $this->view('shop/category_products', $data);
    }

    // Add this method to your Shop controller
    public function uploadProductImage() {
        if (!isAdmin()) {
            redirect('shop');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // File upload settings
            $targetDir = PUBLIC_PATH . '/img/products/';
            $allowTypes = ['jpg', 'jpeg', 'png', 'webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            // Handle file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $file = $_FILES['image'];
                $fileName = $file['name'];
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $fileSize = $file['size'];
                $productId = $_POST['product_id'];
                $isPrimary = isset($_POST['is_primary']) ? 1 : 0;

                // Validate file
                if (!in_array($fileType, $allowTypes)) {
                    flash('image_error', 'Invalid file type. Allowed types: ' . implode(', ', $allowTypes));
                    redirect('shop/product/' . $productId);
                }

                if ($fileSize > $maxSize) {
                    flash('image_error', 'File is too large. Maximum size: 5MB');
                    redirect('shop/product/' . $productId);
                }

                // Generate unique filename
                $newFileName = uniqid() . '.' . $fileType;
                $targetFile = $targetDir . $newFileName;

                // Upload file
                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    // If this is primary image, update other images to non-primary
                    if ($isPrimary) {
                        $this->productModel->updatePrimaryImages($productId);
                    }

                    // Save to database
                    if ($this->productModel->addProductImage($productId, $newFileName, $isPrimary)) {
                        flash('image_success', 'Image uploaded successfully');
                    } else {
                        flash('image_error', 'Failed to save image information');
                    }
                } else {
                    flash('image_error', 'Failed to upload image');
                }
            }

            redirect('shop/product/' . $productId);
        }
    }

    public function editOrder($orderId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $order = $this->orderModel->getOrderById($orderId, $_SESSION['user_id']);
        
        if (!$order || !in_array($order->order_status, ['pending', 'processing'])) {
            flash('order_message', 'Order cannot be edited', 'alert alert-danger');
            redirect('shop/orders');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'shipping_address' => trim($_POST['shipping_address']),
                'shipping_method' => trim($_POST['shipping_method']),
                'notes' => trim($_POST['notes']),
                'errors' => []
            ];

            // Validate inputs
            if (empty($data['shipping_address'])) {
                $data['errors']['shipping_address'] = 'Shipping address is required';
            }

            if (empty($data['errors'])) {
                if ($this->orderModel->updateOrder($orderId, $_SESSION['user_id'], $data)) {
                    flash('order_message', 'Order updated successfully');
                    redirect('shop/orders');
                } else {
                    flash('order_message', 'Failed to update order', 'alert alert-danger');
                }
            }
        }

        $data['order'] = $order;
        $this->view('shop/edit_order', $data);
    }

    public function cancelOrder($orderId) {
        if (!isLoggedIn()) {
            jsonResponse(['success' => false, 'message' => 'Please login first']);
        }

        $order = $this->orderModel->getOrderById($orderId, $_SESSION['user_id']);
        
        if (!$order || $order->order_status !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Order cannot be cancelled']);
        }

        if ($this->orderModel->cancelOrder($orderId, $_SESSION['user_id'])) {
            jsonResponse(['success' => true, 'message' => 'Order cancelled successfully']);
        }

        jsonResponse(['success' => false, 'message' => 'Failed to cancel order']);
    }

    public function createCheckoutSession() {
        if (!isLoggedIn()) {
            die(json_encode(['error' => 'Please login first']));
        }

        // Add error logging
        error_log("Starting checkout process for user: " . $_SESSION['user_id']);

        try {
            require_once APPROOT . '/vendor/autoload.php';
            
            // Set your secret key
            \Stripe\Stripe::setApiKey('sk_test_...'); // Your Stripe Secret Key

            $cartModel = $this->model('M_Cart');
            $cartItems = $cartModel->getCartItems($_SESSION['user_id']);

            if (empty($cartItems)) {
                die(json_encode(['error' => 'Cart is empty']));
            }

            $line_items = [];
            foreach ($cartItems as $item) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'lkr',
                        'product_data' => [
                            'name' => $item->product_name,
                            'images' => [URLROOT . '/public/img/products/' . $item->primary_image],
                        ],
                        'unit_amount' => (int)($item->price * 100), // Convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            error_log("Line items created: " . json_encode($line_items));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => URLROOT . '/shop/checkout_success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => URLROOT . '/shop/cart',
            ]);

            error_log("Stripe session created: " . $session->id);
            
            die(json_encode([
                'id' => $session->id,
                'url' => $session->url
            ]));

        } catch(Exception $e) {
            error_log("Stripe error: " . $e->getMessage());
            die(json_encode([
                'error' => $e->getMessage()
            ]));
        }
    }

    public function checkoutSuccess() {
        if (!isset($_GET['session_id'])) {
            redirect('shop/cart');
        }

        try {
            // Verify the payment was successful
            require_once APPROOT . '/libraries/stripe-php/init.php';
            \Stripe\Stripe::setApiKey('your_secret_key');
            
            $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
            
            if ($session->payment_status === 'paid') {
                // Clear the cart
                $cartModel = $this->model('M_Cart');
                $cartModel->clearCart($_SESSION['user_id']);
                
                // Create order
                $orderModel = $this->model('M_Order');
                $orderModel->createOrder($_SESSION['user_id'], $session->id);
                
                // Show success page
                $data = [
                    'title' => 'Order Successful',
                    'session_id' => $_GET['session_id']
                ];
                $this->view('shop/checkout_success', $data);
            } else {
                redirect('shop/cart');
            }
        } catch(Exception $e) {
            redirect('shop/cart');
        }
    }
}