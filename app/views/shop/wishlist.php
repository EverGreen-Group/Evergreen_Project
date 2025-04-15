<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/main.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>My Wishlist</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Wishlist</a></li>
            </ul>
        </div>
    </div>

    <?php if (empty($data['items'])) : ?>
        <div class="empty-state">
            <i class='bx bx-heart'></i>
            <h2>No items yet</h2>
            <p>You haven't placed any items in your wishlist yet.</p>
            <a href="<?php echo URLROOT; ?>/shop" class="btn-start-shopping">
                Start Shopping
            </a>
        </div>
    <?php else : ?>
        <div class="wishlist-grid">
            <?php foreach ($data['items'] as $item) : ?>
                <div class="wishlist-card">
                    <div class="product-image">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->main_image; ?>" 
                             alt="<?php echo $item->product_name; ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo $item->product_name; ?></h3>
                        <div class="price">Rs. <?php echo number_format($item->price, 2); ?></div>
                        <div class="actions">
                            <button class="btn-add-cart" 
                                    data-product-id="<?php echo $item->product_id; ?>">
                                <i class='bx bx-cart-add'></i> Add to Cart
                            </button>
                            <button class="btn-remove" 
                                    data-product-id="<?php echo $item->product_id; ?>">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Add to Cart
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            try {
                const response = await fetch('<?php echo URLROOT; ?>/shop/addToCart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Added to cart successfully');
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add item to cart');
            }
        });
    });

    // Handle Remove from Wishlist
    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            try {
                const response = await fetch('<?php echo URLROOT; ?>/shop/toggleWishlist', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.closest('.wishlist-card').remove();
                    if (document.querySelectorAll('.wishlist-card').length === 0) {
                        location.reload();
                    }
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to remove item from wishlist');
            }
        });
    });
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>