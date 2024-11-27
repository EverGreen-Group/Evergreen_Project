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
                <li><a href="<?php echo URLROOT; ?>/shop/categories">Categories</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#"><?php echo $data['category']->name; ?></a></li>
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

    <?php if(!empty($data['products'])): ?>
        <div class="products-grid">
            <?php foreach($data['products'] as $product): ?>
                <div class="product-card">
                    <?php if($product->quantity <= 0): ?>
                        <div class="product-badge out-of-stock">Out of Stock</div>
                    <?php elseif($product->quantity <= 10): ?>
                        <div class="product-badge low-stock">Low Stock</div>
                    <?php endif; ?>ffwfwf

                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>"
                             onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                    </div>

                    <div class="product-details">
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
    <?php else: ?>
        <div class="no-products">
            <i class='bx bx-package'></i>
            <p>No products found in this category.</p>
        </div>
    <?php endif; ?>
</main>

<style>
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

.product-image {
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
    transform: scale(1.1);
}

.product-details {
    padding: 15px;
}

.product-name {
    margin: 0 0 10px;
    font-size: 1.1em;
    color: var(--dark);
}

.product-price {
    font-size: 1.2em;
    color: var(--blue);
    font-weight: 600;
    margin-bottom: 10px;
}

.product-location {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
    font-size: 0.9em;
    margin-bottom: 15px;
}

.product-actions {
    display: flex;
    gap: 10px;
}

.btn-view {
    flex: 1;
    padding: 8px 15px;
    background: var(--green);
    color: var(--light);
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: var(--dark-green);
    transform: translateY(-2px);
}

.btn-add-cart {
    width: 40px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--green);
    color: var(--light);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    background: var(--dark-green);
    transform: translateY(-2px);
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

.no-products {
    text-align: center;
    padding: 50px 20px;
    color: var(--grey);
}

.no-products i {
    font-size: 48px;
    margin-bottom: 20px;
}

@media screen and (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
            document.querySelector('.btn-cart span').textContent = 
                `Cart (${data.cart_count})`;
            showToast('Product added to cart successfully');
        } else {
            showToast('Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 