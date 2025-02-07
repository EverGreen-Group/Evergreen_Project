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

        $data = [
            'categories' => $categories,
            'featured_products' => $featuredProducts,
            'new_arrivals' => $newArrivals,
            'best_sellers' => $bestSellers
        ];

        $this->view('shop/index', $data);
    }

    // View products by category
    public function category($categoryId = null) {
        if ($categoryId === null) {
            redirect('shop');
        }

        $category = $this->categoryModel->getCategoryById($categoryId);
        if (!$category) {
            flash('category_message', 'Category not found', 'alert alert-danger');
            redirect('shop');
        }

        $products = $this->productModel->getProductsByCategory($categoryId);

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
        return $this->cartModel->getCartCount($_SESSION['user_id']);
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

        $data = [
            'cart_items' => $this->cartModel->getCartItems($_SESSION['user_id']),
            'cart_total' => $this->cartModel->getCartTotal($_SESSION['user_id'])
        ];

        $this->view('shop/cart', $data);
    }

    // Add to cart
    public function addToCart($productId = null) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if ($this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity)) {
                flash('cart_message', 'Item added to cart successfully');
            } else {
                flash('cart_message', 'Failed to add item to cart', 'alert alert-danger');
            }
        }
        
        redirect('shop/cart');
    }

    // Remove from cart
    public function removeFromCart($productId = null) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        if ($this->cartModel->removeFromCart($_SESSION['user_id'], $productId)) {
            flash('cart_message', 'Item removed from cart successfully');
        } else {
            flash('cart_message', 'Failed to remove item from cart', 'alert alert-danger');
        }
        
        redirect('shop/cart');
    }

    // Checkout Process
    public function payment() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('shop/cart');
        }

        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
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

        // Prepare order data
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

        // Create order and deduct stock
        try {
            $orderId = $this->orderModel->createOrder($_SESSION['user_id'], $orderData);

            if ($orderId) {
                // Deduct stock for each item in the order
                foreach ($formattedItems as $item) {
                    $this->productModel->deductStock($item['product_id'], $item['quantity']);
                }

                // Clear the cart after successful order creation
                $this->cartModel->clearCart($_SESSION['user_id']);

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
        
        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        
        $data = [
            'orders' => $orders
        ];

        $this->view('shop/orders', $data);
    }

    // View order details
    public function orderDetails($orderId) {
        requireAuth();

        $order = $this->orderModel->getOrderDetails($orderId);
        if (!$order || $order->user_id !== $_SESSION['user_id']) {
            redirect('shop/orders');
        }

        $this->view('shop/orderDetails', ['order' => $order]);
    }

    // Cancel order
    public function cancelOrder($orderId) {
        if (!isLoggedIn()) {
            jsonResponse(['success' => false, 'message' => 'Please login first']);
        }

        $order = $this->orderModel->getOrderById($orderId, $_SESSION['user_id']);
        
        if (!$order || $order->order_status !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Order cannot be cancelled']);
        }

        if ($this->orderModel->cancelOrder($orderId, $_SESSION['user_id'])) {
            // Restore stock for each item in the order
            foreach ($order->items as $item) {
                $this->productModel->restoreStock($item->product_id, $item->quantity);
            }

            jsonResponse(['success' => true, 'message' => 'Order cancelled successfully']);
        }

        jsonResponse(['success' => false, 'message' => 'Failed to cancel order']);
    }

    public function trackOrder($orderNumber = null) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // If no order number is provided, show the tracking form
        if ($orderNumber === null) {
            $this->view('shop/tracking_form', [
                'title' => 'Track Order'
            ]);
            return;
        }

        // Get order details
        $order = $this->orderModel->getOrderByNumber($orderNumber, $_SESSION['user_id']);
        if (!$order) {
            flash('order_message', 'Order not found', 'alert alert-danger');
            redirect('shop/orders');
        }

        $data = [
            'title' => 'Track Order #' . $orderNumber,
            'order' => $order,
            'tracking' => $this->orderModel->getOrderTracking($order->id),
            'shipping_address' => json_decode($order->shipping_address),
            'items' => $this->orderModel->getOrderItems($order->id),
            'cart_count' => $this->getCartCount()
        ];

        $this->view('shop/tracking', $data);
    }

    public function activeDeliveries() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $activeOrders = $this->orderModel->getUserActiveOrders($_SESSION['user_id']);

        $data = [
            'title' => 'Active Deliveries',
            'active_orders' => $activeOrders,
            'cart_count' => $this->getCartCount()
        ];

        $this->view('shop/active_deliveries', $data);
    }
}