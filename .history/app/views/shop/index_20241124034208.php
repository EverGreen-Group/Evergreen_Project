<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

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
                    <span>Cart (<?php echo $data['cart_count'] ?? 0; ?>)</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
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
                            <?php echo isset($category->product_count) ? $category->product_count : 0; ?> Products
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
                <div class="product-card">
                    <?php if($product->quantity <= 0): ?>
                        <div class="product-badge out-of-stock">Out of Stock</div>
                    <?php elseif($product->quantity <= 10): ?>
                        <div class="product-badge low-stock">Low Stock</div>
                    <?php endif; ?>

                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>"
                             onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                        
                        <!-- Quick view overlay -->
                        <div class="product-overlay">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" 
                               class="btn-quick-view">
                                <i class='bx bx-search'></i>
                                Quick View
                            </a>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="product-category"><?php echo $product->category_name; ?></div>
                        <h3 class="product-name">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>">
                                <?php echo $product->product_name; ?>
                            </a>
                        </h3>
                        <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                        <div class="product-meta">
                            <div class="product-location">
                                <i class='bx bx-map'></i>
                                <?php echo $product->location; ?>
                            </div>
                            <div class="product-stock">
                                <i class='bx bx-package'></i>
                                <?php echo $product->quantity; ?> in stock
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <?php if($product->quantity > 0): ?>
                                    <div class="quantity-controls">
                                        <button onclick="updateQuantity(<?php echo $product->id; ?>, 'decrease')" 
                                                class="btn-quantity">
                                            <i class='bx bx-minus'></i>
                                        </button>
                                        <input type="number" id="qty-<?php echo $product->id; ?>" 
                                               value="1" min="1" max="<?php echo $product->quantity; ?>"
                                               onchange="validateQuantity(this, <?php echo $product->quantity; ?>)">
                                        <button onclick="updateQuantity(<?php echo $product->id; ?>, 'increase')" 
                                                class="btn-quantity">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                    </div>
                                    <button onclick="addToCart(<?php echo $product->id; ?>)" 
                                            class="btn-add-cart">
                                        <i class='bx bx-cart-add'></i>
                                        Add to Cart
                                    </button>
                                <?php else: ?>
                                    <button class="btn-out-of-stock" disabled>
                                        <i class='bx bx-x-circle'></i>
                                        Out of Stock
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/users/login" class="btn-login-to-buy">
                                    <i class='bx bx-log-in'></i>
                                    Login to Buy
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
                <div class="product-card">
                    <?php if($product->quantity <= 0): ?>
                        <div class="product-badge out-of-stock">Out of Stock</div>
                    <?php elseif($product->quantity <= 10): ?>
                        <div class="product-badge low-stock">Low Stock</div>
                    <?php endif; ?>

                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>"
                             onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                    </div>

                    <div class="product-details">
                        <div class="product-category"><?php echo $product->category_name; ?></div>
                        <h3 class="product-name"><?php echo $product->product_name; ?></h3>
                        <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                        <div class="product-location">
                            <i class='bx bx-map'></i>
                            <?php echo $product->location; ?>
                        </div>
                        
                        <div class="product-actions">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" 
                               class="btn-view">View Details</a>
                            <?php if(isset($_SESSION['user_id']) && $product->quantity > 0): ?>
                                <button onclick="addToCart(<?php echo $product->id; ?>)" class="btn-add-cart">
                                    <i class='bx bx-cart-add'></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Best Sellers -->
    <section class="product-section">
        <div class="section-header">
            <h2>Best Sellers</h2>
            <a href="<?php echo URLROOT; ?>/shop/best-sellers" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            <?php foreach($data['best_sellers'] as $product): ?>
                <div class="product-card">
                    <?php if($product->quantity <= 0): ?>
                        <div class="product-badge out-of-stock">Out of Stock</div>
                    <?php elseif($product->quantity <= 10): ?>
                        <div class="product-badge low-stock">Low Stock</div>
                    <?php endif; ?>

                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>"
                             onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                    </div>

                    <div class="product-details">
                        <div class="product-category"><?php echo $product->category_name; ?></div>
                        <h3 class="product-name"><?php echo $product->product_name; ?></h3>
                        <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                        <div class="product-location">
                            <i class='bx bx-map'></i>
                            <?php echo $product->location; ?>
                        </div>
                        
                        <div class="product-actions">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" 
                               class="btn-view">View Details</a>
                            <?php if(isset($_SESSION['user_id']) && $product->quantity > 0): ?>
                                <button onclick="addToCart(<?php echo $product->id; ?>)" class="btn-add-cart">
                                    <i class='bx bx-cart-add'></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
/* Add your CSS styles here */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    padding: 20px;
}

.product-card {
    background: var(--light);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.product-image {
    height: 220px;
    overflow: hidden;
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-details {
    padding: 20px;
}

.product-category {
    color: #666;
    font-size: 0.9em;
    margin-bottom: 8px;
}

.product-name {
    color: var(--dark);
    font-size: 1.2em;
    margin-bottom: 10px;
    line-height: 1.4;
}

.product-price {
    color: var(--blue);
    font-size: 1.3em;
    font-weight: 600;
    margin-bottom: 15px;
}

.product-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-view {
    flex: 1;
    padding: 10px 15px;
    background: var(--green);
    color: var(--light);
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-view:hover {
    
    transform: translateY(-2px);
}

.btn-add-cart {
    width: 45px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--green);
    color: var(--light);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    background: var(--dark-green);
    transform: translateY(-2px);
}

.categories-section {
    padding: 20px;
}

.category-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.category-card {
    background: var(--light);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: var(--dark);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.category-image {
    height: 150px;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-info {
    padding: 15px;
}

.category-info h3 {
    margin: 0 0 10px;
    font-size: 1.1em;
    color: var(--dark);
}

.category-info span {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--blue);
    font-size: 0.9em;
}

.category-info span i {
    font-size: 1.1em;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 20px 0;
}

.view-all {
    color: var(--blue);
    text-decoration: none;
    font-size: 0.9em;
    transition: color 0.3s ease;
}

.view-all:hover {
    color: var(--dark-blue);
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.8em;
    font-weight: 500;
}

.out-of-stock {
    background: var(--red);
    color: var(--light);
}

.low-stock {
    background: var(--yellow);
    color: var(--dark);
}

@media screen and (max-width: 768px) {
    .category-list {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .category-image {
        height: 120px;
    }
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-overlay:hover {
    opacity: 1;
}

.btn-quick-view {
    background: var(--light);
    color: var(--dark);
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-quick-view:hover {
    background: var(--dark);
    color: var(--light);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.btn-quantity {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light);
    color: var(--dark);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-quantity:hover {
    background: var(--dark);
    color: var(--light);
}

.btn-out-of-stock {
    background: var(--red);
    color: var(--light);
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-out-of-stock:hover {
    background: var(--dark-red);
}

.btn-login-to-buy {
    background: var(--blue);
    color: var(--light);
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-login-to-buy:hover {
    background: var(--dark-blue);
}
</style>

<script>
async function addToCart(productId) {
    try {
        const response = await fetch(`${URLROOT}/shop/addToCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await response.json();
        if (data.success) {
            // Update cart count
            document.querySelector('.btn-cart span').textContent = 
                `Cart (${data.cart_count})`;
            
            // Show success message
            showToast('Product added to cart successfully');
        } else {
            showToast('Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

// Add more JavaScript functions...
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 