<?php require APPROOT . '/views/inc/landing_header.php'; ?>

<main class="landing-page">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Premium Ceylon Tea</h1>
            <p>Discover our exclusive collection of hand-picked Ceylon tea, directly from the highlands of Sri Lanka.</p>
            <a href="<?php echo URLROOT; ?>/shop" class="btn-shop-now">Shop Now</a>
        </div>
    </section>

    <!-- Browse Categories -->
    <section class="categories">
        <h2>Browse Our Collection</h2>
        <div class="category-grid">
            <div class="category-card">
                <img src="<?php echo URLROOT; ?>/img/categories/black-tea.jpg" alt="Black Tea">
                <h3>Black Tea</h3>
            </div>
            <div class="category-card">
                <img src="<?php echo URLROOT; ?>/img/categories/green-tea.jpg" alt="Green Tea">
                <h3>Green Tea</h3>
            </div>
            <div class="category-card">
                <img src="<?php echo URLROOT; ?>/img/categories/specialty-tea.jpg" alt="Specialty Tea">
                <h3>Specialty Tea</h3>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <h2>Our Premium Products</h2>
        <div class="product-grid">
            <!-- Add your featured products here -->
        </div>
        <a href="<?php echo URLROOT; ?>/shop" class="btn-view-more">View More</a>
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