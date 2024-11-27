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

.product-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.05);
}

.image-upload-section {
    margin: 20px 0;
    padding: 20px;
    background: var(--light);
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
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

<!-- Add this right after your main content, before the closing </main> tag -->
<div id="quickViewModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="quick-view-container">
            <div class="quick-view-image">
                <img src="" alt="Product Image" id="quickViewImage">
            </div>
            <div class="quick-view-details">
                <h2 id="quickViewName"></h2>
                <div class="quick-view-category" id="quickViewCategory"></div>
                <div class="quick-view-price" id="quickViewPrice"></div>
                <div class="quick-view-stock" id="quickViewStock"></div>
                <p class="quick-view-description" id="quickViewDescription"></p>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="quick-view-actions">
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn minus"><i class='bx bx-minus'></i></button>
                            <input type="number" id="quickViewQuantity" value="1" min="1">
                            <button type="button" class="qty-btn plus"><i class='bx bx-plus'></i></button>
                        </div>
                        <button onclick="quickViewAddToCart()" class="btn-add-cart">
                            <i class='bx bx-cart-add'></i> Add to Cart
                        </button>
                    </div>
                <?php else: ?>
                    <div class="auth-prompt">
                        <p>Please <a href="<?php echo URLROOT; ?>/users/login">login</a> to make a purchase</p>
                    </div>
                <?php endif; ?>
                
                <a href="" id="quickViewFullDetails" class="btn-view-details">
                    View Full Details
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background: var(--light);
    margin: 5% auto;
    padding: 20px;
    width: 90%;
    max-width: 1000px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.close-modal {
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 28px;
    cursor: pointer;
    color: var(--dark-grey);
    transition: color 0.3s ease;
}

.close-modal:hover {
    color: var(--dark);
}

.quick-view-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.quick-view-image img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    object-fit: cover;
}

.quick-view-details {
    padding: 20px 0;
}

.quick-view-details h2 {
    margin-bottom: 15px;
    color: var(--dark);
}

.quick-view-category {
    color: var(--grey);
    margin-bottom: 10px;
}

.quick-view-price {
    font-size: 1.5em;
    font-weight: 600;
    color: var(--blue);
    margin: 15px 0;
}

.quick-view-stock {
    margin-bottom: 15px;
}

.quick-view-description {
    margin: 20px 0;
    line-height: 1.6;
    color: var(--dark-grey);
}

.quick-view-actions {
    display: flex;
    gap: 15px;
    margin: 20px 0;
}

.btn-view-details {
    display: inline-block;
    padding: 10px 20px;
    background: var(--blue);
    color: var(--light);
    text-decoration: none;
    border-radius: 8px;
    margin-top: 20px;
    transition: background 0.3s ease;
}

.btn-view-details:hover {
    background: var(--dark-blue);
}

@media screen and (max-width: 768px) {
    .quick-view-container {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Quick View functionality
async function showQuickView(productId) {
    try {
        const response = await fetch(`${URLROOT}/shop/getProductDetails/${productId}`);
        const product = await response.json();
        
        if (product) {
            document.getElementById('quickViewImage').src = `${URLROOT}/img/products/${product.primary_image}`;
            document.getElementById('quickViewName').textContent = product.product_name;
            document.getElementById('quickViewCategory').textContent = product.category_name;
            document.getElementById('quickViewPrice').textContent = `Rs. ${parseFloat(product.price).toFixed(2)}`;
            document.getElementById('quickViewDescription').textContent = product.description;
            document.getElementById('quickViewFullDetails').href = `${URLROOT}/shop/product/${product.id}`;
            
            // Update stock status
            const stockElement = document.getElementById('quickViewStock');
            if (product.quantity > 0) {
                stockElement.innerHTML = `<i class='bx bx-check-circle'></i> In Stock (${product.quantity} available)`;
                stockElement.className = 'quick-view-stock in-stock';
                document.getElementById('quickViewQuantity').max = product.quantity;
            } else {
                stockElement.innerHTML = `<i class='bx bx-x-circle'></i> Out of Stock`;
                stockElement.className = 'quick-view-stock out-of-stock';
            }
            
            // Show modal
            document.getElementById('quickViewModal').style.display = 'block';
        }
    } catch (error) {
        console.error('Error fetching product details:', error);
        showToast('Failed to load product details', 'error');
    }
}

// Close modal when clicking outside or on close button
document.querySelector('.close-modal').onclick = () => {
    document.getElementById('quickViewModal').style.display = 'none';
}

window.onclick = (event) => {
    const modal = document.getElementById('quickViewModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Quick View Add to Cart
async function quickViewAddToCart() {
    const quantity = document.getElementById('quickViewQuantity').value;
    const productId = new URL(document.getElementById('quickViewFullDetails').href).pathname.split('/').pop();
    
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
            document.querySelector('.btn-cart span').textContent = `Cart (${data.cart_count})`;
            showToast(`Added ${quantity} item(s) to cart successfully`);
            document.getElementById('quickViewModal').style.display = 'none';
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