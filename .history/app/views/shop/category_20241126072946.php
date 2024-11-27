<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1><?php echo $data['category']->name; ?></h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#"><?php echo $data['category']->name; ?></a></li>
            </ul>
        </div>
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="right">
                <a href="<?php echo URLROOT; ?>/shop/cart" class="btn-cart">
                    <i class='bx bx-cart'></i>
                    <span>Cart (<?php echo $data['cart_count']; ?>)</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php flash('category_message'); ?>

    <div class="products-section">
        <?php if(empty($data['products'])): ?>
            <div class="no-products">
                <i class='bx bx-package'></i>
                <p>No products found in this category.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach($data['products'] as $product): ?>
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
                            
                            <div class="product-overlay">
                                <a href="<?php echo URLROOT; ?>/shop/viewProduct/<?php echo $product->id; ?>" 
                                   class="btn-quick-view">
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
                                    <a href="<?php echo URLROOT; ?>/auth/login" class="btn-login-to-buy">
                                        <i class='bx bx-log-in'></i>
                                        Login to Buy
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* Product Grid Layout */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    padding: 20px;
}

/* Product Card Styles */
.product-card {
    background: var(--light);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    position: relative;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
}

/* Product Badge Styles */
.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 1;
}

.out-of-stock {
    background: #ff4444;
    color: white;
}

.low-stock {
    background: #ffbb33;
    color: white;
}

/* Product Image Container */
.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

/* Product Overlay */
.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

/* Quick View Button */
.btn-quick-view {
    background: var(--green);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-quick-view:hover {
    background: var(--main);
}

/* Product Details */
.product-details {
    padding: 1.5rem;
}

.product-category {
    color: var(--dark-grey);
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.product-name {
    margin: 10px 0;
    font-size: 1.2rem;
}

.product-name a {
    color: var(--dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: var(--main);
}

.product-price {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--green);
    margin: 10px 0;
}

/* Product Meta Information */
.product-meta {
    display: flex;
    justify-content: space-between;
    margin: 15px 0;
    font-size: 0.9rem;
    color: var(--dark-grey);
}

.product-location, .product-stock {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Product Actions */
.product-actions {
    margin-top: 15px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.btn-quantity {
    background: var(--light-grey);
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-quantity:hover {
    background: var(--grey);
}

.quantity-controls input {
    width: 60px;
    text-align: center;
    border: 1px solid var(--grey);
    border-radius: 5px;
    padding: 5px;
}

/* Cart and Login Buttons */
.btn-add-cart, .btn-login-to-buy, .btn-out-of-stock {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-cart {
    background: var(--green);
    color: white;
}

.btn-add-cart:hover {
    background: var(--main);
}

.btn-login-to-buy {
    background: var(--blue);
    color: white;
    text-decoration: none;
}

.btn-login-to-buy:hover {
    background: var(--dark-blue);
}

.btn-out-of-stock {
    background: var(--grey);
    color: white;
    cursor: not-allowed;
}

/* No Products Message */
.no-products {
    text-align: center;
    padding: 50px 20px;
    color: var(--grey);
    background: var(--light);
    border-radius: 12px;
    margin: 20px;
}

.no-products i {
    font-size: 48px;
    margin-bottom: 15px;
    color: var(--dark-grey);
}

.no-products p {
    font-size: 18px;
    color: var(--dark-grey);
}

/* Responsive Adjustments */
@media screen and (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
        padding: 10px;
    }

    .product-image {
        height: 180px;
    }

    .product-details {
        padding: 1rem;
    }
}
</style>

<script>
// You can reuse the JavaScript functions from your index.php file
function validateQuantity(input, max) {
    let value = parseInt(input.value);
    if (value < 1) input.value = 1;
    if (value > max) input.value = max;
}

function updateQuantity(productId, action) {
    const input = document.getElementById(`qty-${productId}`);
    let value = parseInt(input.value);
    
    if (action === 'increase' && value < parseInt(input.max)) {
        input.value = value + 1;
    } else if (action === 'decrease' && value > 1) {
        input.value = value - 1;
    }
}

async function addToCart(productId) {
    const quantity = document.getElementById(`qty-${productId}`).value;
    
    try {
        const response = await fetch(`${URLROOT}/shop/addToCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });

        const data = await response.json();
        if (data.success) {
            document.querySelector('.btn-cart span').textContent = 
                `Cart (${data.cart_count})`;
            showToast(`Added ${quantity} item(s) to cart successfully`);
        } else {
            showToast(data.message || 'Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 