<?php require APPROOT . '/views/inc/landing_header.php'; ?>

<main class="landing-page">
    <!-- Hero Section -->
    <section class="hero" id="hero" style="background-image: url('<?php echo URLROOT; ?>/img/categories/bg.jpg');">
        <div class="hero-content">
            <h1>Premium Ceylon Tea</h1>
            <p>Discover our exclusive collection of hand-picked Ceylon tea, directly from the highlands of Sri Lanka.</p>
            <a href="<?php echo URLROOT; ?>/shop" class="btn-shop-now">Shop Now</a>
        </div>
    </section>

    <!-- Browse Categories -->
    <section class="categories">
        <div class="section-header">
            <h2>Browse Our Collection</h2>
            <p>Discover our handpicked selection of premium Ceylon teas</p>
        </div>
        
        <div class="category-grid">
            <a href="<?php echo URLROOT; ?>/shop/category/blacktea" class="category-card">
                <div class="category-image">
                    <img src="<?php echo URLROOT; ?>/img/categories/blacktea.jpg" alt="Black Tea">
                </div>
                <div class="category-content">
                    <h3>Black Tea</h3>
                    <p>Traditional Ceylon Black Tea</p>
                    <span class="explore-link">Explore <i class='bx bx-right-arrow-alt'></i></span>
                </div>
            </a>

            <a href="<?php echo URLROOT; ?>/shop/category/greentea" class="category-card">
                <div class="category-image">
                    <img src="<?php echo URLROOT; ?>/img/categories/greentea.jpg" alt="Green Tea">
                </div>
                <div class="category-content">
                    <h3>Green Tea</h3>
                    <p>Fresh & Natural Green Tea</p>
                    <span class="explore-link">Explore <i class='bx bx-right-arrow-alt'></i></span>
                </div>
            </a>

            <a href="<?php echo URLROOT; ?>/shop/category/specialty" class="category-card">
                <div class="category-image">
                    <img src="<?php echo URLROOT; ?>/img/categories/specialty.jpg" alt="Specialty Tea">
                </div>
                <div class="category-content">
                    <h3>Specialty Tea</h3>
                    <p>Premium Specialty Blends</p>
                    <span class="explore-link">Explore <i class='bx bx-right-arrow-alt'></i></span>
                </div>
            </a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="section-header">
            <h2>Our Premium Products</h2>
            <p>Hand-picked selection of our finest teas</p>
        </div>
        
        <div class="product-grid">
            <?php if(isset($data['featured_products']) && !empty($data['featured_products'])): ?>
                <?php foreach($data['featured_products'] as $product): ?>
                    <div class="product-card">
                        <div class="product-badge">Featured</div>
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/img/products/<?php echo $product->primary_image ?? 'default.jpg'; ?>" 
                                 alt="<?php echo $product->product_name; ?>">
                        </div>
                        <div class="product-content">
                            <h3><?php echo $product->product_name; ?></h3>
                            <p class="product-description"><?php echo substr($product->description ?? '', 0, 100); ?>...</p>
                            <div class="product-price">
                                <?php if(isset($product->discount_price) && $product->discount_price > 0): ?>
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
                                <?php if(isset($product->rating)): ?>
                                <span class="rating">
                                    <i class='bx bxs-star'></i>
                                    <?php echo number_format($product->rating, 1); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="product-actions">
                                <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" 
                                   class="btn-view">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">No featured products available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Management Links -->
    <section class="management-section">
        <h2>Management Portal</h2>
        <div class="management-grid">
            <a href="<?php echo URLROOT; ?>/inventory" class="management-card">
                <i class='bx bx-box'></i>
                <h3>Inventory Management</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/vehicles" class="management-card">
                <i class='bx bx-car'></i>
                <h3>Vehicle Management</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/suppliers" class="management-card">
                <i class='bx bx-store'></i>
                <h3>Supplier Management</h3>
            </a>
        </div>
    </section>

    <!-- Factory Story -->
    <section class="factory-story">
        <div class="story-content">
            <h2>Our Heritage</h2>
            <p>Experience the finest Ceylon tea, crafted with over 50 years of expertise in the highlands of Sri Lanka.</p>
            <a href="<?php echo URLROOT; ?>/about" class="btn-learn-more">Learn More</a>
        </div>
    </section>
</main>

<?php require APPROOT . '/views/inc/landing_footer.php'; ?> 