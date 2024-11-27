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
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="right">
                <a href="<?php echo URLROOT; ?>/shop/cart" class="btn-cart">
                    <i class='bx bx-cart'></i>
                    <span>Cart (<?php echo isset($data['cart_count']) ? $data['cart_count'] : 0; ?>)</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php flash('category_message'); ?>

    <?php if(!empty($data['featured_categories'])): ?>
    <div class="featured-categories">
        <h2>Featured Categories</h2>
        <div class="category-grid">
            <?php foreach($data['featured_categories'] as $category): ?>
                <div class="category-card featured">
                    <div class="category-image">
                        <img src="<?php echo URLROOT; ?>/img/categories/<?php echo $category->image ?? 'default.jpg'; ?>" 
                             alt="<?php echo $category->name; ?>">
                        <div class="category-overlay">
                            <a href="<?php echo URLROOT; ?>/shop/category/<?php echo $category->id; ?>" 
                               class="btn-quick-view">
                                <i class='bx bx-search'></i>
                                View Products
                            </a>
                        </div>
                    </div>
                    <div class="category-content">
                        <h3><?php echo $category->name; ?></h3>
                        <p><?php echo $category->description; ?></p>
                        <div class="category-stats">
                            <span><i class='bx bx-package'></i> <?php echo $category->product_count; ?> Products</span>
                            <?php if($category->subcategory_count > 0): ?>
                                <span><i class='bx bx-folder'></i> <?php echo $category->subcategory_count; ?> Subcategories</span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo URLROOT; ?>/shop/category/<?php echo $category->id; ?>" 
                           class="btn-view">Browse Products</a>
                    </div>
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
                    <div class="category-image">
                        <img src="<?php echo URLROOT; ?>/img/categories/<?php echo $category->image ?? 'default.jpg'; ?>" 
                             alt="<?php echo $category->name; ?>">
                        <div class="category-overlay">
                            <a href="<?php echo URLROOT; ?>/shop/category/<?php echo $category->id; ?>" 
                               class="btn-quick-view">
                                <i class='bx bx-search'></i>
                                View Products
                            </a>
                        </div>
                    </div>
                    <div class="category-content">
                        <h3><?php echo $category->name; ?></h3>
                        <p><?php echo $category->description; ?></p>
                        <div class="category-stats">
                            <span><i class='bx bx-package'></i> <?php echo $category->product_count; ?> Products</span>
                            <?php if($category->subcategory_count > 0): ?>
                                <span><i class='bx bx-folder'></i> <?php echo $category->subcategory_count; ?> Subcategories</span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo URLROOT; ?>/shop/category/<?php echo $category->id; ?>" 
                           class="btn-view">Browse Products</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<style>
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    padding: 20px;
}

.category-card {
    background: var(--light);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.category-card.featured {
    border: 2px solid var(--blue);
}

.category-image {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-content {
    padding: 20px;
}

.category-card h3 {
    color: var(--dark);
    margin-bottom: 10px;
    font-size: 1.2em;
}

.category-card p {
    color: #666;
    margin-bottom: 15px;
    font-size: 0.9em;
    line-height: 1.5;
}

.category-stats {
    display: flex;
    gap: 15px;
    color: var(--blue);
    font-weight: 500;
    margin-bottom: 20px;
    font-size: 0.9em;
}

.category-stats span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.category-stats i {
    font-size: 1.1em;
}

.btn-view {
    display: inline-block;
    padding: 10px 25px;
    background: var(--green);
    color: var(--light);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    width: 100%;
}

.btn-view:hover {
    background: var(--dark-green);
    transform: translateY(-2px);
}

h2 {
    padding: 20px 20px 0;
    color: var(--dark);
    font-size: 1.5em;
}

.featured-categories {
    margin-bottom: 30px;
}

@media screen and (max-width: 768px) {
    .category-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .category-image {
        height: 180px;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>