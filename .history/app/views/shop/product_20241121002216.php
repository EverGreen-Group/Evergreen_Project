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
    </div>

    <div class="product-detail-container">
        <div class="product-gallery">
            <div class="main-image">
                <img src="<?php echo URLROOT; ?>/img/products/<?php echo explode(',', $data['product']->images)[0]; ?>" 
                     alt="<?php echo $data['product']->product_name; ?>" 
                     id="mainImage">
            </div>
            <?php if(count(explode(',', $data['product']->images)) > 1): ?>
                <div class="thumbnail-images">
                    <?php foreach(explode(',', $data['product']->images) as $image): ?>
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $image; ?>" 
                             alt="Thumbnail" 
                             onclick="changeMainImage(this.src)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <div class="product-header">
                <h2><?php echo $data['product']->product_name; ?></h2>
                <div class="product-meta">
                    <span class="sku">SKU: <?php echo $data['product']->sku; ?></span>
                    <div class="rating">
                        <?php 
                        $avgRating = $data['average_rating']->avg_rating ?? 0;
                        for($i = 1; $i <= 5; $i++): 
                        ?>
                            <i class='bx <?php echo $i <= $avgRating ? 'bxs-star' : 'bx-star'; ?>'></i>
                        <?php endfor; ?>
                        <span>(<?php echo $data['average_rating']->total_reviews ?? 0; ?> reviews)</span>
                    </div>
                </div>
            </div>

            <div class="product-price">
                <h3>Rs. <?php echo number_format($data['product']->price, 2); ?></h3>
                <?php if($data['product']->quantity > 0): ?>
                    <span class="stock in-stock">
                        <i class='bx bx-check-circle'></i> In Stock
                        (<?php echo $data['product']->quantity; ?> available)
                    </span>
                <?php else: ?>
                    <span class="stock out-of-stock">
                        <i class='bx bx-x-circle'></i> Out of Stock
                    </span>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <h4>Product Details</h4>
                <p><?php echo $data['product']->details; ?></p>
            </div>

            <?php if(isset($_SESSION['user_id']) && $data['product']->quantity > 0): ?>
                <form action="<?php echo URLROOT; ?>/shop/addToCart" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn minus"><i class='bx bx-minus'></i></button>
                            <input type="number" id="quantity" name="quantity" value="1" 
                                   min="1" max="<?php echo min(5, $data['product']->quantity); ?>">
                            <button type="button" class="qty-btn plus"><i class='bx bx-plus'></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn-add-to-cart">
                        <i class='bx bx-cart-add'></i>
                        Add to Cart
                    </button>
                </form>
            <?php elseif(!isset($_SESSION['user_id'])): ?>
                <div class="auth-prompt">
                    <p>Please <a href="<?php echo URLROOT; ?>/auth/login">login</a> to make a purchase</p>
                </div>
            <?php elseif($_SESSION['approval_status'] != 'Approved'): ?>
                <div class="auth-prompt">
                    <p>Your account is pending approval</p>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                <div class="admin-actions">
                    <a href="<?php echo URLROOT; ?>/admin/editProduct/<?php echo $data['product']->id; ?>" 
                       class="btn-edit">
                        <i class='bx bx-edit'></i>
                        Edit Product
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.product-detail-container {
    padding: 24px 0;
}

.product-detail-card {
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-images {
    position: relative;
}

.main-image {
    width: 100%;
    height: 400px;
    border-radius: 12px;
    overflow: hidden;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.product-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: 16px;
}

.product-header h2 {
    font-size: 1.5rem;
    color: var(--dark);
}

.stock-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.stock-badge.out-of-stock { background: #fce8e6; color: #d93025; }
.stock-badge.low-stock { background: #fef7e0; color: #b06000; }
.stock-badge.in-stock { background: #e6f4ea; color: #1e8e3e; }

.product-meta {
    display: flex;
    gap: 24px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--dark-grey);
}

.meta-item i {
    font-size: 1.25rem;
}

.product-price {
    margin: 16px 0;
}

.product-price h3 {
    font-size: 2rem;
    color: var(--main);
    margin-bottom: 4px;
}

.price-label {
    color: var(--dark-grey);
    font-size: 0.875rem;
}

.product-description h4 {
    margin-bottom: 12px;
    color: var(--dark);
}

.product-description p {
    color: var(--dark-grey);
    line-height: 1.6;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    background: var(--light-main);
    color: var(--main);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: var(--main);
    color: var(--light);
}

input[type="number"] {
    width: 60px;
    height: 32px;
    text-align: center;
    border: 1px solid var(--grey);
    border-radius: 8px;
}

.btn-add-to-cart {
    width: 100%;
    padding: 12px;
    background: var(--main);
    color: var(--light);
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.btn-add-to-cart:hover {
    opacity: 0.9;
}

.auth-prompt {
    text-align: center;
    padding: 16px;
    background: var(--light-main);
    border-radius: 8px;
}

.auth-prompt a {
    color: var(--main);
    text-decoration: none;
    font-weight: 500;
}

.admin-actions {
    margin-top: 16px;
}

.btn-edit {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--grey);
    color: var(--dark);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-edit:hover {
    background: var(--dark-grey);
    color: var(--light);
}

@media screen and (max-width: 768px) {
    .product-detail-card {
        grid-template-columns: 1fr;
    }

    .main-image {
        height: 300px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const minusBtn = document.querySelector('.qty-btn.minus');
    const plusBtn = document.querySelector('.qty-btn.plus');

    if (minusBtn && plusBtn && quantityInput) {
        minusBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        plusBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });

        quantityInput.addEventListener('change', () => {
            const value = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            if (value < 1) quantityInput.value = 1;
            if (value > maxValue) quantityInput.value = maxValue;
        });
    }
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 