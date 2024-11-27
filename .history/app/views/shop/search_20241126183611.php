<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Search Results</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Search: <?php echo htmlspecialchars($data['query']); ?></a></li>
            </ul>
        </div>
    </div>

    <div class="search-results">
        <?php if(empty($data['products'])): ?>
            <div class="empty-products">
                <i class='bx bx-search-alt'></i>
                <h2>No Products Found</h2>
                <p>We couldn't find any products matching "<?php echo htmlspecialchars($data['query']); ?>"</p>
                <div class="search-suggestions">
                    <h3>Suggestions:</h3>
                    <ul>
                        <li>Check your spelling</li>
                        <li>Try more general keywords</li>
                        <li>Try different keywords</li>
                    </ul>
                </div>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-shop">Browse All Products</a>
            </div>
        <?php else: ?>
            <div class="results-summary">
                <p>Found <?php echo count($data['products']); ?> products for "<?php echo htmlspecialchars($data['query']); ?>"</p>
            </div>
            <div class="product-grid">
                <?php foreach($data['products'] as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image; ?>" 
                                 alt="<?php echo $product->product_name; ?>">
                        </div>
                        <div class="product-content">
                            <h3><?php echo $product->product_name; ?></h3>
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
/* Add the same styles as featured.php plus these additional styles */
.search-results {
    padding: 20px;
}

.results-summary {
    margin-bottom: 20px;
    color: var(--grey);
}

.search-suggestions {
    margin: 20px 0;
    text-align: left;
}

.search-suggestions h3 {
    color: var(--dark);
    margin-bottom: 10px;
}

.search-suggestions ul {
    list-style: disc;
    padding-left: 20px;
    color: var(--grey);
}

.search-suggestions li {
    margin: 5px 0;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 