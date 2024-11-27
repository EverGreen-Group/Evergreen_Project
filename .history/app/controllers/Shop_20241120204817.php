<?php
require_once '../app/models/M_Product.php';
require_once '../app/models/M_Order.php';
require_once '../app/models/M_Cart.php';
require_once '../app/helpers/auth_middleware.php';

class Shop extends Controller {
    private $productModel;
    private $orderModel;
    private $cartModel;

    public function __construct() {
        $this->productModel = new M_Product();
        $this->orderModel = new M_Order();
        $this->cartModel = new M_Cart();
    }

    // Display all products (public access)
    public function index() {
        $products = $this->productModel->getAllProducts();
        $this->view('shop/index', ['products' => $products]);
    }

    // View single product (public access)
    public function product($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            redirect('shop');
        }
        $this->view('shop/product', ['product' => $product]);
    }

    // Add to cart (requires login)
    public function addToCart() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $userId = $_SESSION['user_id'];

            if ($this->cartModel->addItem($userId, $productId, $quantity)) {
                flash('cart_message', 'Item added to cart');
            } else {
                flash('cart_message', 'Failed to add item', 'alert alert-danger');
            }
            redirect('shop/cart');
        }
    }

    // View cart (requires login)
    public function cart() {
        requireAuth();
        
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getCartItems($userId);
        $total = $this->cartModel->getCartTotal($userId);

        $this->view('shop/cart', [
            'items' => $cartItems,
            'total' => $total
        ]);
    }

    // Checkout process (requires login)
    public function checkout() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $shippingAddress = $_POST['shipping_address'];

            // Create order from cart
            if ($this->orderModel->createOrderFromCart($userId, $shippingAddress)) {
                $this->cartModel->clearCart($userId);
                flash('order_message', 'Order placed successfully');
                redirect('shop/orders');
            } else {
                flash('order_message', 'Failed to place order', 'alert alert-danger');
                redirect('shop/cart');
            }
        }

        redirect('shop/cart');
    }

    // View orders (requires login)
    public function orders() {
        requireAuth();

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getUserOrders($userId);
        $this->view('shop/orders', ['orders' => $orders]);
    }
} 