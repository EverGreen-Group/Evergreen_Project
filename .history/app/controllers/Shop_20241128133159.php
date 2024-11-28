<?php
require_once '../app/models/M_Product.php';
require_once '../app/models/M_Order.php';
require_once '../app/models/M_Cart.php';
require_once '../app/models/M_Category.php';
require_once '../app/models/M_Review.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/utility_helper.php';

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
    public function category($categoryId = null) {
        if ($categoryId === null) {
            redirect('shop');
        }

        // Get category details
        $categoryModel = new M_Category();
        $category = $categoryModel->getCategoryById($categoryId);

        if (!$category) {
            flash('category_message', 'Category not found', 'alert alert-danger');
            redirect('shop');
        }

        // Get products for this category
        $productModel = new M_Product();
        $products = $productModel->getProductsByCategory($categoryId);

        $data = [
            'title' => $category->name . ' - Products',
            'category' => $category,
            'products' => $products,
            'cart_count' => isset($_SESSION['user_id']) ? $this->getCartCount() : 0
        ];

        $this->view('shop/category', $data);
    }

    // Helper method for cart count
    private function getCartCount() {
        $cartModel = new M_Cart();
        return $cartModel->getCartCount($_SESSION['user_id']);
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
            'cart_total' => $cartModel->getCartTotal($_SESSION['user_id'])
        ];

        $this->view('shop/cart', $data);
    }

    // Cart Actions
    public function addToCart() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('shop');
        }

        // Check if it's an AJAX request
        if (!$this->isAjaxRequest()) {
            redirect('shop');
        }

        // Set JSON response header
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Please login to add items to cart'
            ]);
            return;
        }

        // Get and validate POST data
        $data = json_decode(file_get_contents('php://input'));
        
        if (!isset($data->product_id) || !isset($data->quantity)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request data'
            ]);
            return;
        }

        // Validate quantity
        if ($data->quantity < 1) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Quantity must be greater than 0'
            ]);
            return;
        }

        try {
            // Add to cart
            $cartModel = $this->model('M_Cart');
            $result = $cartModel->addToCart(
                $_SESSION['user_id'],
                $data->product_id,
                $data->quantity
            );

            if ($result) {
                // Get updated cart count
                $cartCount = $this->shopModel->getCartCount($_SESSION['user_id']);
                
                echo json_encode([
                    'success' => true,
                    'cart_count' => $cartCount,
                    'message' => 'Item added to cart successfully'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add item to cart'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while adding to cart'
            ]);
        }
    }

    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
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
            jsonResponse(['success' => false, 'message' => 'Please login first']);
        }

        $data = json_decode(file_get_contents('php://input'));
        
        if (!isset($data->product_id)) {
            jsonResponse(['success' => false, 'message' => 'Invalid data']);
        }

        $cartModel = $this->model('M_Cart');
        $success = $cartModel->removeFromCart($_SESSION['user_id'], $data->product_id);

        if ($success) {
            $total = $cartModel->getCartTotal($_SESSION['user_id']);
            $itemsCount = $cartModel->getCartCount($_SESSION['user_id']);
            
            jsonResponse([
                'success' => true,
                'totals' => [
                    'subtotal' => $total,
                    'items_count' => $itemsCount,
                    'shipping' => 0
                ],
                'message' => 'Item removed from cart'
            ]);
        }

        jsonResponse([
            'success' => false,
            'message' => 'Failed to remove item'
        ]);
    }

    // Checkout Process
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('shop/cart');
        }

        if (!isLoggedIn()) {
            flash('cart_message', 'Please login to checkout', 'alert alert-danger');
            redirect('users/login');
        }

        // Get cart items
        $cartModel = $this->model('M_Cart');
        $cartItems = $cartModel->getCartItems($_SESSION['user_id']);

        if (empty($cartItems)) {
            flash('cart_message', 'Your cart is empty', 'alert alert-danger');
            redirect('shop/cart');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }

        $shippingFee = 500.00;
        $taxAmount = $subtotal * 0.10;
        $grandTotal = $subtotal + $shippingFee + $taxAmount;

        // Format cart items for order
        $formattedItems = array_map(function($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ];
        }, $cartItems);

        // Compile shipping address
        $shippingAddress = json_encode([
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'postal_code' => trim($_POST['postal_code'] ?? '')
        ]);

        // Prepare order data according to M_Order requirements
        $orderData = [
            'total_amount' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'shipping_address' => $shippingAddress,
            'billing_address' => $shippingAddress, // Using same address for billing
            'shipping_method' => 'Standard',
            'payment_method' => 'stripe', // or your preferred payment method
            'notes' => $_POST['notes'] ?? '',
            'items' => $formattedItems
        ];

        // Create order
        $orderModel = $this->model('M_Order');
        try {
            $orderId = $orderModel->createOrder($_SESSION['user_id'], $orderData);

            if ($orderId) {
                redirect('shop/payment/' . $orderId);
            } else {
                flash('cart_message', 'Failed to create order', 'alert alert-danger');
                redirect('shop/cart');
            }
        } catch (Exception $e) {
            flash('cart_message', $e->getMessage(), 'alert alert-danger');
            redirect('shop/cart');
        }
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
        // Get search query
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($query)) {
            redirect('shop');
        }

        // Get search results from model
        $products = $this->productModel->searchProducts($query);
        
        $data = [
            'title' => 'Search Results for "' . htmlspecialchars($query) . '"',
            'query' => $query,
            'products' => $products,
            'cart_count' => isset($_SESSION['user_id']) ? $this->getCartCount() : 0
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
    

    public function viewProduct($id = null) {
        if ($id === null) {
            redirect('shop');
        }

        $productModel = $this->model('M_Product');
        $product = $productModel->getProductWithDetails($id);

        if (!$product) {
            flash('product_message', 'Product not found', 'alert alert-danger');
            redirect('shop');
        }

        // Get related products
        $relatedProducts = $productModel->getRelatedProducts($id, $product->category_id);

        $data = [
            'product' => $product,
            'related_products' => $relatedProducts,
            'cart_count' => isset($_SESSION['user_id']) ? 
                $this->model('M_Cart')->getCartCount($_SESSION['user_id']) : 0
        ];

        $this->view('shop/product', $data);
    }

    public function createOrder() {
        if (!$this->isAjaxRequest()) {
            redirect('shop');
        }

        $cartModel = $this->model('M_Cart');
        $orderModel = $this->model('M_Order');

        try {
            $orderId = $orderModel->createOrder($_SESSION['user_id']);
            
            if ($orderId) {
                echo json_encode([
                    'success' => true,
                    'order_id' => $orderId
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create order'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function payment($orderId = null) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Get cart total and items
        $cartModel = $this->cartModel;
        $cartItems = $cartModel->getCartItems($_SESSION['user_id']);
        $cartTotal = $cartModel->getCartTotal($_SESSION['user_id']);

        if (empty($cartItems)) {
            redirect('shop/cart');
        }

        $data = [
            'total' => $cartTotal,
            'items' => $cartItems
        ];

        $this->view('shop/payment', $data);
    }

    public function processPayment()
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get cart items from session
            $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            
            if (empty($cart_items)) {
                redirect('shop/cart');
            }

            // Calculate total amount
            $total_amount = 0;
            foreach ($cart_items as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }

            // Generate order number and tracking number
            $order_number = 'ORD-' . time() . '-' . rand(1000, 9999);
            $tracking_number = 'TRK-' . time() . '-' . rand(1000, 9999);

            // Prepare order data
            $order_data = [
                'user_id' => $_SESSION['user_id'],
                'order_number' => $order_number,
                'tracking_number' => $tracking_number,
                'total_amount' => $total_amount,
                'shipping_address' => trim($_POST['street_address'] . ', ' . 
                                        $_POST['town_city'] . ', ' . 
                                        $_POST['province'] . ' ' . 
                                        $_POST['zip_code']),
                'payment_method' => trim($_POST['payment_method']),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Start transaction
            $this->shopModel->beginTransaction();

            try {
                // Create order
                $order_id = $this->shopModel->createOrder($order_data);

                if ($order_id) {
                    // Add order items
                    foreach ($cart_items as $item) {
                        $order_item = [
                            'order_id' => $order_id,
                            'product_id' => $item['id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price']
                        ];
                        $this->shopModel->addOrderItem($order_item);
                        
                        // Update product stock
                        $this->shopModel->updateProductStock($item['id'], $item['quantity']);
                    }

                    // Clear cart
                    unset($_SESSION['cart']);

                    // Commit transaction
                    $this->shopModel->commit();

                    // Redirect to tracking page
                    redirect('shop/tracking/' . $tracking_number);
                } else {
                    throw new Exception("Failed to create order");
                }
            } catch (Exception $e) {
                // Rollback transaction on error
                $this->shopModel->rollback();
                
                // Log error
                error_log("Payment processing error: " . $e->getMessage());
                
                flash('payment_error', 'An error occurred while processing your payment. Please try again.', 'alert alert-danger');
                redirect('shop/cart');
            }
        } else {
            // GET request - load payment page with cart data
            $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            
            if (empty($cart_items)) {
                redirect('shop/cart');
            }

            $total = 0;
            foreach ($cart_items as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $data = [
                'title' => 'Payment',
                'cart_items' => $cart_items,
                'total' => $total
            ];

            $this->view('shop/payment', $data);
        }
    }

    public function tracking($tracking_number = null)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        if ($tracking_number === null) {
            redirect('shop');
        }

        // Get order details
        $order = $this->shopModel->getOrderByTracking($tracking_number);
        
        if (!$order) {
            flash('tracking_error', 'Order not found', 'alert alert-danger');
            redirect('shop');
        }

        // Get order items
        $order_items = $this->shopModel->getOrderItems($order->id);

        // Get nearby post offices (example data - replace with actual data)
        $post_offices = [
            (object)[
                'name' => 'Central Post office',
                'address' => '851 Row Rd, Suite 216, San Francisco, CA 94010',
                'hours_left' => 'Two hours left',
                'distance' => '9 min'
            ],
            (object)[
                'name' => 'Post office in South Beach',
                'address' => '851 Row Rd, Suite 216, San Francisco, CA 94010',
                'hours_left' => 'Two hours left',
                'distance' => '9 min'
            ]
        ];

        // Map center coordinates (example - replace with actual coordinates)
        $map_center_lat = 37.7749;
        $map_center_lng = -122.4194;

        $data = [
            'title' => 'Order Tracking',
            'order' => $order,
            'order_items' => $order_items,
            'tracking_number' => $tracking_number,
            'post_offices' => $post_offices,
            'map_center_lat' => $map_center_lat,
            'map_center_lng' => $map_center_lng
        ];

        $this->view('shop/tracking', $data);
    }

    // Helper method to check if request is AJAX
    // private function isAjaxRequest() {
    //     return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    //            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    // }

    // Helper method for JSON responses
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}