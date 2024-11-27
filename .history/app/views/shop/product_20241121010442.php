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
                            <input type="number" name="quantity" id="quantity" value="1" 
                                   min="1" max="<?php echo $data['product']->quantity; ?>">
                            <button type="button" class="qty-btn plus"><i class='bx bx-plus'></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn-add-cart">
                        <i class='bx bx-cart-add'></i> Add to Cart
                    </button>
                </form>
            <?php elseif(!isset($_SESSION['user_id'])): ?>
                <div class="auth-prompt">
                    <p>Please <a href="<?php echo URLROOT; ?>/users/login">login</a> to make a purchase</p>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                <div class="admin-actions">
                    <a href="<?php echo URLROOT; ?>/admin/editProduct/<?php echo $data['product']->id; ?>" 
                       class="btn-edit">
                        <i class='bx bx-edit'></i> Edit Product
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Reviews Section -->
    <section class="reviews-section">
        <h3>Customer Reviews</h3>
        <?php if(!empty($data['reviews'])): ?>
            <div class="reviews-list">
                <?php foreach($data['reviews'] as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <span class="reviewer-name">
                                    <?php echo $review->first_name . ' ' . $review->last_name; ?>
                                </span>
                                <span class="review-date">
                                    <?php echo date('M d, Y', strtotime($review->created_at)); ?>
                                </span>
                            </div>
                            <div class="rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class='bx <?php echo $i <= $review->rating ? 'bxs-star' : 'bx-star'; ?>'></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-comment"><?php echo $review->comment; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-reviews">No reviews yet. Be the first to review this product!</p>
        <?php endif; ?>

        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="add-review-form">
                <h4>Write a Review</h4>
                <form action="<?php echo URLROOT; ?>/shop/addReview" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                    <div class="rating-input">
                        <label>Rating:</label>
                        <div class="star-rating">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>">
                                <label for="star<?php echo $i; ?>"><i class='bx bx-star'></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Your Review:</label>
                        <textarea name="comment" id="comment" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit-review">Submit Review</button>
                </form>
            </div>
        <?php endif; ?>
    </section>

    <!-- Related Products -->
    <section class="related-products">
        <h3>Related Products</h3>
        <div class="products-grid">
            <?php foreach($data['related_products'] as $product): ?>
                <!-- Similar product card structure as in index.php -->
            <?php endforeach; ?>
        </div>
    </section>

    <?php if(isAdmin()): ?>
        <div class="image-upload-section">
            <h3>Add Product Image</h3>
            <form action="<?php echo URLROOT; ?>/shop/uploadProductImage" 
                  method="POST" 
                  enctype="multipart/form-data">
                
                <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                
                <div class="form-group">
                    <label for="image">Select Image:</label>
                    <input type="file" name="image" id="image" required accept="image/*">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_primary"> Set as primary image
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
        </div>
    <?php endif; ?>
</main>

<style>
/* Add your CSS styles here */
.product-detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    padding: 20px;
    background: var(--light);
    border-radius: 10px;
    margin: 20px;
}

/* Add more styles... */
</style>

<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}

// Quantity controls
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
    }
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>