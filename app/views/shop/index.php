<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/main.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Ceylon Tea Collection</h1>
            <ul class="breadcrumb">
                <li><a href="#">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Products</a></li>
            </ul>
        </div>
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="right">
                <a href="<?php echo URLROOT; ?>/shop/cart" class="btn-cart">
                    <i class='bx bx-cart'></i>
                    <span id="cart-count">Cart</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 && isset($data['stats'])): ?>
        <ul class="box-info">
            <li>
                <i class='bx bxs-shopping-bag'></i>
                <span class="text">
                    <h3><?php echo $data['stats']['total_products']; ?></h3>
                    <p>Total Products</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-cart'></i>
                <span class="text">
                    <h3><?php echo $data['stats']['total_orders']; ?></h3>
                    <p>Total Orders</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-cart'></i>
                <span class="text">
                    <h3><?php echo $data['stats']['pending_orders']; ?></h3>
                    <p>Pending Orders</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-dollar-circle'></i>
                <span class="text">
                    <h3>Rs. <?php echo number_format($data['stats']['total_revenue'], 2); ?></h3>
                    <p>Total Revenue</p>
                </span>
            </li>
        </ul>
    <?php endif; ?>

    <!-- Categories Section -->
    <div class="categories-section">
        <h2>Categories</h2>
        <div class="category-list">
            <?php foreach($data['categories'] as $category): ?>
                <a href="<?php echo URLROOT; ?>/shop/category/<?php echo $category->id; ?>" 
                class="category-card">
                    <div class="category-image">
                        <img src="<?php echo URLROOT; ?>/img/categories/<?php echo $category->image ?? 'default.jpg'; ?>" 
                            alt="<?php echo $category->name; ?>"
                            onerror="this.src='<?php echo URLROOT; ?>/img/categories/default.jpg'">
                    </div>
                    <div class="category-info">
                        <h3><?php echo $category->name; ?></h3>
                        <span>
                            <i class='bx bx-package'></i>
                            <span class="category-count">
                                <?php echo isset($category->product_count) ? $category->product_count : 0; ?> Products
                            </span>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Products -->
    <section class="product-section">
        <div class="section-header">
            <h2>Featured Products</h2>
            <a href="<?php echo URLROOT; ?>/shop/featured" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            <?php foreach($data['featured_products'] as $product): ?>
                <div class="product-card <?php echo ($product->quantity <= 0) ? 'out-of-stock' : ''; ?>">
                    <?php if($product->quantity <= 0): ?>
                        <div class="product-badge">Out of Stock</div>
                    <?php endif; ?>

                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>"
                             onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                        
                        <div class="product-overlay">
                            <a href="<?php echo URLROOT; ?>/shop/viewProduct/<?php echo $product->id; ?>" class="btn-quick-view">
                                <i class='bx bx-search'></i>
                                Quick View
                            </a>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="product-category"><?php echo $product->category_name; ?></div>
                        <h3 class="product-name">
                            <a href="<?php echo URLROOT; ?>/shop/viewProduct/<?php echo $product->id; ?>">
                                <?php echo $product->product_name; ?>
                            </a>
                        </h3>
                        <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                        <div class="product-meta">
                            <div class="product-location">
                                <i class='bx bx-map'></i>
                                <?php echo $product->location; ?>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <?php if($product->quantity > 0): ?>
                                    <form action="<?php echo URLROOT; ?>/shop/addToCart/<?php echo $product->id; ?>" method="POST">
                                        <button type="submit" class="btn-add-cart">
                                            <i class='bx bx-cart-add'></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-out-of-stock" disabled>
                                        <i class='bx bx-x-circle'></i>
                                        Unavailable
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/auth/login" class="btn-login-to-buy">
                                    <i class='bx bx-log-in'></i>
                                    Login to Purchase
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="product-section">
        <div class="section-header">
            <h2>New Arrivals</h2>
            <a href="<?php echo URLROOT; ?>/shop/new" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            <?php foreach($data['new_arrivals'] as $product): ?>
                <div class="product-card <?php echo ($product->quantity <= 0) ? 'out-of-stock' : ''; ?>">
                    <?php if($product->quantity <= 0): ?>
                        <div class="product-badge">Out of Stock</div>
                    <?php endif; ?>

                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>"
                             onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                        
                        <div class="product-overlay">
                            <a href="<?php echo URLROOT; ?>/shop/viewProduct/<?php echo $product->id; ?>" class="btn-quick-view">
                                <i class='bx bx-search'></i>
                                Quick View
                            </a>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="product-category"><?php echo $product->category_name; ?></div>
                        <h3 class="product-name">
                            <a href="<?php echo URLROOT; ?>/shop/viewProduct/<?php echo $product->id; ?>">
                                <?php echo $product->product_name; ?>
                            </a>
                        </h3>
                        <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                        <div class="product-meta">
                            <div class="product-location">
                                <i class='bx bx-map'></i>
                                <?php echo $product->location; ?>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <?php if($product->quantity > 0): ?>
                                    <form action="<?php echo URLROOT; ?>/shop/addToCart/<?php echo $product->id; ?>" method="POST">
                                        <button type="submit" class="btn-add-cart">
                                            <i class='bx bx-cart-add'></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-out-of-stock" disabled>
                                        <i class='bx bx-x-circle'></i>
                                        Unavailable
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/auth/login" class="btn-login-to-buy">
                                    <i class='bx bx-log-in'></i>
                                    Login to Purchase
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
    .product-card.out-of-stock {
        opacity: 0.7;
        position: relative;
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ff4444;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9em;
        z-index: 2;
    }
    
    .btn-out-of-stock {
        width: 100%;
        background: #cccccc;
        color: #666666;
        cursor: not-allowed;
    }
    
    .product-card.out-of-stock .product-overlay {
        background-color: rgba(255, 255, 255, 0.7);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>
<script src="<?php echo URLROOT; ?>/js/main.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>