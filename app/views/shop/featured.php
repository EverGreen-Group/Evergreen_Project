<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Featured Products</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Featured</a></li>
            </ul>
        </div>
    </div>

    <div class="featured-container">
        <?php if(empty($data['products'])): ?>
            <div class="empty-products">
                <i class='bx bx-package'></i>
                <h2>No Featured Products</h2>
                <p>Check back later for our featured products.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-shop">Browse All Products</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach($data['products'] as $product): ?>
                    <div class="product-card">
                        <div class="product-badge">Featured</div>
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->image ?? 'default.jpg'; ?>" 
                                 alt="<?php echo $product->name; ?>">
                        </div>
                        <div class="product-content">
                            <h3><?php echo $product->name; ?></h3>
                            <p class="product-description"><?php echo substr($product->description, 0, 100); ?>...</p>
                            <div class="product-price">
                                <?php if($product->discount_price): ?>
                                    <span class="original-price">Rs. <?php echo number_format($product->price, 2); ?></span>
                                    <span class="discount-price">Rs. <?php echo number_format($product->discount_price, 2); ?></span>
                                <?php else: ?>
                                    <span class="price">Rs. <?php echo number_format($product->price, 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-meta">
                                <span class="stock <?php echo $product->stock > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                    <?php echo $product->stock > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                </span>
                                <span class="rating">
                                    <i class='bx bxs-star'></i>
                                    <?php echo number_format($product->rating, 1); ?>
                                </span>
                            </div>
                            <div class="product-actions">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" 
                                   class="btn-view">View Details</a>
                                <?php if($product->stock > 0): ?>
                                    <button onclick="addToCart(<?php echo $product->id; ?>)" 
                                            class="btn-cart">
                                        <i class='bx bx-cart-add'></i>
                                    </button>
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
.featured-container {
    padding: 20px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.product-card {
    background: var(--light);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--blue);
    color: var(--light);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 500;
    z-index: 1;
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

.product-content {
    padding: 20px;
}

.product-content h3 {
    color: var(--dark);
    margin-bottom: 10px;
    font-size: 1.2em;
}

.product-description {
    color: var(--grey);
    font-size: 0.9em;
    line-height: 1.5;
    margin-bottom: 15px;
}

.product-price {
    margin-bottom: 15px;
}

.price {
    color: var(--blue);
    font-size: 1.2em;
    font-weight: 600;
}

.original-price {
    color: var(--grey);
    text-decoration: line-through;
    margin-right: 10px;
    font-size: 0.9em;
}

.discount-price {
    color: var(--red);
    font-size: 1.2em;
    font-weight: 600;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 0.9em;
}

.stock {
    padding: 3px 10px;
    border-radius: 15px;
    font-weight: 500;
}

.in-stock {
    background: #d1e7dd;
    color: #0f5132;
}

.out-of-stock {
    background: #f8d7da;
    color: #842029;
}

.rating {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #ffc107;
}

.product-actions {
    display: flex;
    gap: 10px;
}

.btn-view, .btn-cart {
    padding: 8px 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
}

.btn-view {
    background: var(--blue);
    color: var(--light);
    flex: 1;
}

.btn-cart {
    background: var(--green);
    color: var(--light);
    border: none;
    width: 45px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-view:hover, .btn-cart:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.empty-products {
    text-align: center;
    padding: 40px;
    background: var(--light);
    border-radius: 15px;
    margin: 20px auto;
    max-width: 500px;
}

.empty-products i {
    font-size: 48px;
    color: var(--grey);
    margin-bottom: 20px;
}

.empty-products h2 {
    color: var(--dark);
    margin-bottom: 10px;
}

.empty-products p {
    color: var(--grey);
    margin-bottom: 20px;
}

.btn-shop {
    display: inline-block;
    padding: 10px 25px;
    background: var(--blue);
    color: var(--light);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-shop:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

@media screen and (max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }

    .product-content {
        padding: 15px;
    }
}
</style>

<script>
async function addToCart(productId, quantity = 1) {
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
            // Update cart count in header
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
                cartCount.style.display = data.cart_count > 0 ? 'block' : 'none';
            }
            
            // Show success message
            alert('Product added to cart successfully');
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to add product to cart');
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 