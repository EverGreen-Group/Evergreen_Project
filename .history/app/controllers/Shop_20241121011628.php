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
        requireAuth();
        
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getCartItems($userId);
        $total = $this->cartModel->getCartTotal($userId);
        $shippingMethods = $this->orderModel->getShippingMethods();

        $data = [
            'items' => $cartItems,
            'total' => $total,
            'shipping_methods' => $shippingMethods
        ];

        $this->view('shop/cart', $data);
    }

    // Cart Actions
    public function addToCart() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $userId = $_SESSION['user_id'];

            // Check stock availability
            $product = $this->productModel->getProductById($productId);
            if ($product->quantity < $quantity) {
                flash('cart_message', 'Insufficient stock available', 'alert alert-danger');
                redirect("shop/product/$productId");
                return;
            }

            if ($this->cartModel->addItem($userId, $productId, $quantity)) {
                flash('cart_message', 'Item added to cart');
            } else {
                flash('cart_message', 'Failed to add item', 'alert alert-danger');
            }
            redirect('shop/cart');
        }
    }

    public function updateCart() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            if ($this->cartModel->updateQuantity($userId, $productId, $quantity)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }

    public function removeFromCart($productId) {
        requireAuth();
        
        $userId = $_SESSION['user_id'];
        if ($this->cartModel->removeItem($userId, $productId)) {
            flash('cart_message', 'Item removed from cart');
        } else {
            flash('cart_message', 'Failed to remove item', 'alert alert-danger');
        }
        redirect('shop/cart');
    }

    // Checkout Process
    public function checkout() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $orderData = [
                'shipping_address' => $_POST['shipping_address'],
                'shipping_method' => $_POST['shipping_method'],
                'payment_method' => $_POST['payment_method'],
                'notes' => $_POST['notes'] ?? null
            ];

            // Validate cart items and stock
            $cartItems = $this->cartModel->getCartItems($userId);
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->stock) {
                    flash('cart_message', 'Some items are out of stock', 'alert alert-danger');
                    redirect('shop/cart');
                    return;
                }
            }

            // Process order
            $orderId = $this->orderModel->createOrder($userId, $orderData);
            if ($orderId) {
                // Clear cart and redirect to order confirmation
                $this->cartModel->clearCart($userId);
                redirect("shop/orderConfirmation/$orderId");
            } else {
                flash('order_message', 'Failed to place order', 'alert alert-danger');
                redirect('shop/cart');
            }
        }
    }

    // Order Management
    public function orders() {
        requireAuth();

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getUserOrders($userId);
        $this->view('shop/orders', ['orders' => $orders]);
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

    public function category($id) {
        // Get category details
        $category = $this->categoryModel->getCategoryById($id);
        
        if (!$category) {
            redirect('shop/categories');
        }

        // Get products in this category
        $products = $this->productModel->getProductsByCategory($id);
        
        // Get cart count if user is logged in
        $cartCount = 0;
        if (isset($_SESSION['user_id'])) {
            $cartCount = $this->cartModel->getCartCount($_SESSION['user_id']);
        }

        $data = [
            'category' => $category,
            'products' => $products,
            'cart_count' => $cartCount
        ];

        $this->view('shop/category_products', $data);
    }
}