<?php
require_once '../app/models/M_Product.php';
require_once '../app/models/M_Order.php';
require_once '../app/models/M_Cart.php';
require_once '../app/models/M_Category.php';
require_once '../app/models/M_Review.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/utility_helper.php';
// require_once '../app/helpers/payment/PaymentGatewayInterface.php';
// require_once '../app/helpers/payment/StripeGateway.php';
// require_once '../app/helpers/payment/PaypalGateway.php';

class Shop extends Controller {
    private $productModel;
    private $orderModel;
    private $cartModel;
    private $categoryModel;
    private $reviewModel;
    private $paymentGateways;

    public function __construct() {
        $this->productModel = new M_Product();
        $this->orderModel = new M_Order();
        $this->cartModel = new M_Cart();
        $this->categoryModel = new M_Category();
        $this->reviewModel = new M_Review();
        
        // Initialize payment gateways
        // $this->paymentGateways = [
        //     'stripe' => new StripeGateway(),
        //     'paypal' => new PaypalGateway()
        // ];
    }

    // Display shop homepage
    public function index() {
        $data = [
            'categories' => $this->categoryModel->getAllCategories(),
            'featured_products' => $this->productModel->getFeaturedProducts(),
            'new_arrivals' => $this->productModel->getNewArrivals(),
            'best_sellers' => $this->productModel->getBestSellers(),
            'cart_count' => $this->getCartCount()
        ];
        $this->view('shop/index', $data);
    }

    // View products by category
    public function category($categoryId = null) {
        if (!$categoryId) redirect('shop');
        
        $category = $this->categoryModel->getCategoryById($categoryId);
        if (!$category) {
            flash('category_message', 'Category not found', 'alert alert-danger');
            redirect('shop');
        }

        $data = [
            'category' => $category,
            'products' => $this->productModel->getProductsByCategory($categoryId),
            'cart_count' => $this->getCartCount()
        ];
        $this->view('shop/category', $data);
    }

    // View single product
    public function product($id) {
        $product = $this->productModel->getProductWithDetails($id);
        if (!$product) redirect('shop');

        $data = [
            'product' => $product,
            'related_products' => $this->productModel->getRelatedProducts($id, $product->category_id),
            'cart_count' => $this->getCartCount()
        ];
        $this->view('shop/product', $data);
    }

    // Cart management
    public function cart() {
        if (!isLoggedIn()) redirect('users/login');

        $data = [
            'cart_items' => $this->cartModel->getCartItems($_SESSION['user_id']),
            'cart_total' => $this->cartModel->getCartTotal($_SESSION['user_id']),
            'cart_count' => $this->getCartCount()
        ];
        $this->view('shop/cart', $data);
    }

    // Add to cart (AJAX)
    public function addToCart() {
        if (!isLoggedIn()) jsonResponse(['success' => false, 'message' => 'Please login first']);
        
        try {
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;
            
            $result = $this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity);
            
            if ($result) {
                jsonResponse([
                    'success' => true,
                    'message' => 'Item added to cart',
                    'cart_count' => $this->getCartCount()
                ]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Failed to add item']);
            }
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Update cart item quantity
    public function updateCart() {
        if (!isLoggedIn()) jsonResponse(['success' => false, 'message' => 'Please login first']);
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if ($this->cartModel->updateCartItem($_SESSION['user_id'], $productId, $quantity)) {
            jsonResponse([
                'success' => true,
                'cart_total' => $this->cartModel->getCartTotal($_SESSION['user_id']),
                'item_total' => $this->cartModel->getCartItemTotal($_SESSION['user_id'], $productId)
            ]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Failed to update cart']);
        }
    }

    // Remove from cart
    public function removeFromCart($productId) {
        if (!isLoggedIn()) redirect('users/login');
        
        if ($this->cartModel->removeFromCart($_SESSION['user_id'], $productId)) {
            flash('cart_message', 'Item removed from cart');
        } else {
            flash('cart_message', 'Failed to remove item', 'alert alert-danger');
        }
        redirect('shop/cart');
    }

    // Checkout process
    public function checkout() {
        if (!isLoggedIn()) redirect('users/login');
        
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        if (empty($cartItems)) {
            flash('cart_message', 'Your cart is empty', 'alert alert-danger');
            redirect('shop/cart');
        }

        $data = [
            'cart_items' => $cartItems,
            'subtotal' => $this->cartModel->getCartTotal($_SESSION['user_id']),
            'shipping_methods' => $this->cartModel->getShippingMethods(),
            'cart_count' => $this->getCartCount()
        ];
        $this->view('shop/checkout', $data);
    }

    // Process payment
    public function processPayment() {
        if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] != 'POST') redirect('shop');
        
        // Validate and create order
        $orderData = $this->prepareOrderData();
        $orderId = $this->orderModel->createOrder($_SESSION['user_id'], $orderData);
        
        if (!$orderId) {
            flash('payment_error', 'Failed to create order', 'alert alert-danger');
            redirect('shop/checkout');
        }

        // Process payment with selected gateway
        $gateway = $_POST['payment_method'] ?? 'stripe';
        try {
            $result = $this->paymentGateways[$gateway]->processPayment(
                $orderData['grand_total'],
                [
                    'order_id' => $orderId,
                    'order_number' => $orderData['order_number'],
                    'customer_email' => $_SESSION['user_email']
                ]
            );

            if ($result['success']) {
                // Deduct stock and clear cart
                $this->finalizeOrder($orderId, $orderData['items']);
                
                if ($result['type'] === 'redirect') {
                    header('Location: ' . $result['redirect_url']);
                    exit();
                } else {
                    $this->view('shop/payment', [
                        'order' => $this->orderModel->getOrderById($orderId),
                        'payment_form' => $result['form']
                    ]);
                }
            } else {
                throw new Exception($result['message']);
            }
        } catch (Exception $e) {
            $this->orderModel->updateOrderStatus($orderId, 'failed');
            flash('payment_error', $e->getMessage(), 'alert alert-danger');
            redirect('shop/checkout');
        }
    }

    // Payment callback
    public function paymentCallback($gateway) {
        if (!array_key_exists($gateway, $this->paymentGateways)) redirect('shop');
        
        try {
            $verification = $this->paymentGateways[$gateway]->verifyPayment($_REQUEST);
            
            if ($verification['success']) {
                $this->orderModel->updateOrderStatus(
                    $verification['order_id'],
                    'paid'
                );
                flash('order_message', 'Payment successful! Your order is being processed.');
                redirect('shop/orderDetails/' . $verification['order_id']);
            } else {
                throw new Exception($verification['message']);
            }
        } catch (Exception $e) {
            flash('payment_error', $e->getMessage(), 'alert alert-danger');
            redirect('shop/orders');
        }
    }

    // Order management
    public function orders() {
        if (!isLoggedIn()) redirect('users/login');
        
        $data = [
            'orders' => $this->orderModel->getUserOrders($_SESSION['user_id']),
            'cart_count' => $this->getCartCount()
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

        $this->view('shop/orderDetails', [
            'order' => $order,
            'cart_count' => $this->getCartCount()
        ]);
    }

    // Track order
    public function trackOrder($orderNumber = null) {
        if (!isLoggedIn()) redirect('users/login');

        if (!$orderNumber) {
            $this->view('shop/tracking_form', ['cart_count' => $this->getCartCount()]);
            return;
        }

        $order = $this->orderModel->getOrderByNumber($orderNumber, $_SESSION['user_id']);
        if (!$order) {
            flash('order_message', 'Order not found', 'alert alert-danger');
            redirect('shop/orders');
        }

        $data = [
            'order' => $order,
            'tracking' => $this->orderModel->getOrderTracking($order->id),
            'shipping_address' => json_decode($order->shipping_address),
            'cart_count' => $this->getCartCount()
        ];
        $this->view('shop/tracking', $data);
    }

    // Helper methods
    private function getCartCount() {
        return isLoggedIn() ? $this->cartModel->getCartCount($_SESSION['user_id']) : 0;
    }

    private function prepareOrderData() {
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $subtotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
        $shippingFee = 500.00;
        $taxAmount = $subtotal * 0.10;
        
        return [
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'items' => array_map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            }, $cartItems),
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'grand_total' => $subtotal + $shippingFee + $taxAmount,
            'shipping_address' => json_encode([
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'city' => trim($_POST['city']),
                'postal_code' => trim($_POST['postal_code'])
            ]),
            'shipping_method' => $_POST['shipping_method'] ?? 'Standard',
            'payment_method' => $_POST['payment_method'] ?? 'stripe'
        ];
    }

    private function finalizeOrder($orderId, $items) {
        foreach ($items as $item) {
            $this->productModel->deductStock($item['product_id'], $item['quantity']);
        }
        $this->cartModel->clearCart($_SESSION['user_id']);
    }
}