<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="shop-container">
        <div class="shop-header">
            <h1>Tea Collection</h1>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo URLROOT; ?>/shop/cart" class="cart-button">
                    <i class='bx bx-cart'></i> View Cart
                </a>
            <?php endif; ?>
        </div>

        <div class="products-grid">
            <?php foreach($data['products'] as $product): ?>
                <div class="product-card">
                    <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->id; ?>.jpg" 
                         alt="<?php echo $product->product_name; ?>" 
                         class="product-image">
                    <div class="product-details">
                        <h3><?php echo $product->product_name; ?></h3>
                        <p class="product-location">Origin: <?php echo $product->location; ?></p>
                        <p class="product-price">$<?php echo number_format($product->price, 2); ?></p>
                        <div class="product-actions">
                            <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" 
                               class="view-details-btn">View Details</a>
                            <?php if($product->quantity > 0): ?>
                                <span class="stock-status in-stock">In Stock</span>
                            <?php else: ?>
                                <span class="stock-status out-of-stock">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main> 