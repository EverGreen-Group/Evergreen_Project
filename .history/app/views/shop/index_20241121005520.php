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
                             alt="<?php echo $category->name; ?>">
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
                    </div>

                    <div class="product-details">
                        <div class="product-category"><?php echo $product->category_name; ?></div>
                        <h3 class="product-name"><?php echo $product->product_name; ?></h3>
                        <div class="product-price">Rs. <?php echo number_format($product->price, 2); ?></div>
                        
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

    <!-- New Arrivals -->
    <section class="product-section">
        <div class="section-header">
            <h2>New Arrivals</h2>
            <a href="<?php echo URLROOT; ?>/shop/new" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            <?php foreach($data['new_arrivals'] as $product): ?>
                <!-- Similar product card structure as above -->
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
                <!-- Similar product card structure as above -->
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
/* Add your CSS styles here */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}

.product-card {
    background: var(--light);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
}

/* Add more styles... */

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