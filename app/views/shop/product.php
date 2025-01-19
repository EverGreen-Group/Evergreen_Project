<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Product Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?php echo URLROOT; ?>/shop/category/<?php echo $data['product']->category_id; ?>">
                    <?php echo $data['product']->category_name; ?>
                </a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#"><?php echo $data['product']->product_name; ?></a></li>
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

    <div class="product-view-container">
        <!-- Product Images Section -->
        <div class="product-images">
            <div class="main-image">
                <img src="<?php echo URLROOT; ?>/img/products/<?php echo $data['product']->images[0]->image; ?>" 
                     alt="<?php echo $data['product']->product_name; ?>" 
                     id="mainProductImage">
            </div>
            <?php if(count($data['product']->images) > 1): ?>
                <div class="thumbnail-gallery">
                    <?php foreach($data['product']->images as $image): ?>
                        <div class="thumbnail" onclick="changeMainImage('<?php echo $image->image; ?>')">
                            <img src="<?php echo URLROOT; ?>/img/products/<?php echo $image->image; ?>" 
                                 alt="Product thumbnail">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Details Section -->
        <div class="product-details">
            <h2 class="product-title"><?php echo $data['product']->product_name; ?></h2>
            
            <div class="product-meta">
                <div class="category">
                    <i class='bx bx-purchase-tag'></i>
                    <?php echo $data['product']->category_name; ?>
                </div>
                <div class="location">
                    <i class='bx bx-map'></i>
                    <?php echo $data['product']->location; ?>
                </div>
            </div>

            <div class="product-price">
                <span class="price">Rs. <?php echo number_format($data['product']->price, 2); ?></span>
                <?php if($data['product']->quantity > 0): ?>
                    <span class="stock-status in-stock">
                        <i class='bx bx-check-circle'></i>
                        In Stock (<?php echo $data['product']->quantity; ?> available)
                    </span>
                <?php else: ?>
                    <span class="stock-status out-of-stock">
                        <i class='bx bx-x-circle'></i>
                        Out of Stock
                    </span>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <h3>Description</h3>
                <p><?php echo $data['product']->description; ?></p>
            </div>

            <?php if($data['product']->specifications): ?>
                <div class="product-specifications">
                    <h3>Specifications</h3>
                    <div class="specs-grid">
                        <?php foreach(json_decode($data['product']->specifications) as $key => $value): ?>
                            <div class="spec-item">
                                <span class="spec-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</span>
                                <span class="spec-value"><?php echo $value; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['user_id']) && $data['product']->quantity > 0): ?>
                <div class="add-to-cart-section">
                    <div class="quantity-controls">
                        <button type="button" onclick="updateQuantity('decrease')" class="qty-btn">
                            <i class='bx bx-minus'></i>
                        </button>
                        <input type="number" id="productQuantity" value="1" 
                               min="1" max="<?php echo $data['product']->quantity; ?>"
                               onchange="validateQuantity(this)">
                        <button type="button" onclick="updateQuantity('increase')" class="qty-btn">
                            <i class='bx bx-plus'></i>
                        </button>
                    </div>
                    <button onclick="addToCart(<?php echo $data['product']->id; ?>)" class="btn-add-to-cart">
                        <i class='bx bx-cart-add'></i>
                        Add to Cart
                    </button>
                </div>
            <?php elseif(!isset($_SESSION['user_id'])): ?>
                <div class="login-prompt">
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn-login">
                        <i class='bx bx-log-in'></i>
                        Login to Purchase
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products Section -->
    <?php if(!empty($data['related_products'])): ?>
        <div class="related-products">
            <h2>Related Products</h2>
            <div class="products-grid">
                <?php foreach($data['related_products'] as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                             alt="<?php echo $product->product_name; ?>">
                        <div class="product-info">
                            <h3><?php echo $product->product_name; ?></h3>
                            <p class="price">Rs. <?php echo number_format($product->price, 2); ?></p>
                            <a href="<?php echo URLROOT; ?>/shop/view/<?php echo $product->id; ?>" 
                               class="btn-view">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<style>
.product-view-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    padding: 20px;
    background: var(--light);
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product-images {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.main-image {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumbnail-gallery {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 10px 0;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.thumbnail:hover {
    border-color: var(--primary);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    padding: 20px;
}

.product-title {
    font-size: 1.8em;
    color: var(--dark);
    margin-bottom: 15px;
}

.product-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    color: var(--grey);
}

.product-meta > div {
    display: flex;
    align-items: center;
    gap: 5px;
}

.product-price {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.price {
    font-size: 1.5em;
    color: var(--primary);
    font-weight: bold;
}

.stock-status {
    display: flex;
    align-items: center;
    gap: 5px;
}

.in-stock {
    color: var(--success);
}

.out-of-stock {
    color: var(--danger);
}

.product-description {
    margin: 20px 0;
}

.product-description h3 {
    margin-bottom: 10px;
    color: var(--dark);
}

.add-to-cart-section {
    display: flex;
    gap: 20px;
    margin-top: 30px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--grey-light);
    padding: 5px;
    border-radius: 8px;
}

.qty-btn {
    background: none;
    border: none;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--dark);
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: var(--grey);
    color: var(--light);
    border-radius: 5px;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>