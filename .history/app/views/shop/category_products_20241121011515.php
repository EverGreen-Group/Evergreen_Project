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
                    <?php endif; ?>

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
}
</style> 