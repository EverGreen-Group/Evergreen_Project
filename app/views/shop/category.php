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