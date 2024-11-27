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
                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>">
                    </div>
                    <div class="product-category">
                        <?php echo $product->category_name; ?>
                    </div>
                    <h3 class="product-name">
                        <?php echo $product->name; ?>
                    </h3>
                    <div class="product-price">
                        Rs. <?php echo number_format($product->price, 2); ?>
                    </div>
                    <div class="product-meta">
                        <div class="product-location">
                            <?php echo $product->location; ?>
                        </div>
                        <div class="product-stock">
                            <?php echo $product->stock; ?> in stock
                        </div>
                    </div>
                    <div class="product-actions">
                        <input type="number" value="1" min="1" max="<?php echo $product->stock; ?>" class="quantity-input">
                        <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product->id; ?>)">
                            Add to Cart
                        </button>
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

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
<script src="<?php echo URLROOT; ?>/js/main.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 