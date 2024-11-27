<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Shop Categories</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Categories</a></li>
            </ul>
        </div>
    </div>

    <?php if(!empty($data['featured_categories'])): ?>
    <div class="featured-categories">
        <h2>Featured Categories</h2>
        <div class="category-grid">
            <?php foreach($data['featured_categories'] as $category): ?>
                <div class="category-card featured">
                    <h3><?php echo $category->name; ?></h3>
                    <p><?php echo $category->description; ?></p>
                    <div class="category-stats">
                        <span><?php echo $category->product_count; ?> Products</span>
                    </div>
                    <a href="<?php echo URLROOT; ?>/shop/categoryProducts/<?php echo $category->id; ?>" 
                       class="btn-view">View Products</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="all-categories">
        <h2>All Categories</h2>
        <div class="category-grid">
            <?php foreach($data['categories'] as $category): ?>
                <div class="category-card">
                    <h3><?php echo $category->name; ?></h3>
                    <p><?php echo $category->description; ?></p>
                    <div class="category-stats">
                        <span><?php echo $category->product_count; ?> Products</span>
                    </div>
                    <a href="<?php echo URLROOT; ?>/shop/categoryProducts/<?php echo $category->id; ?>" 
                       class="btn-view">View Products</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<style>
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
}

.category-card {
    background: var(--light);
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-card.featured {
    border: 2px solid var(--blue);
}

.category-card h3 {
    color: var(--dark);
    margin-bottom: 10px;
}

.category-card p {
    color: var(--grey);
    margin-bottom: 15px;
    font-size: 0.9em;
}

.category-stats {
    color: var(--blue);
    font-weight: 500;
    margin-bottom: 15px;
}

.btn-view {
    display: inline-block;
    padding: 8px 20px;
    background: var(--blue);
    color: var(--light);
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-view:hover {
    background: var(--dark-blue);
}

h2 {
    padding: 20px 20px 0;
    color: var(--dark);
}

.featured-categories {
    margin-bottom: 30px;
}

@media screen and (max-width: 768px) {
    .category-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 