<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

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

<style>
main {
    padding: 24px 20px 20px 20px;
}

.head-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.head-title .left h1 {
    font-size: 24px;
    font-weight: 600;
}

.breadcrumb {
    display: flex;
    align-items: center;
    grid-gap: 16px;
}

.breadcrumb li {
    color: var(--dark);
}

.breadcrumb li a {
    color: var(--dark-grey);
    pointer-events: none;
}

.breadcrumb li a.active {
    color: var(--blue);
    pointer-events: unset;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    background: var(--light);
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.empty-state i {
    font-size: 48px;
    color: var(--grey);
    margin-bottom: 16px;
}

.empty-state h2 {
    font-size: 20px;
    color: var(--dark);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--dark-grey);
    margin-bottom: 24px;
}

.btn-start-shopping {
    display: inline-block;
    padding: 8px 24px;
    background: var(--blue);
    color: var(--light);
    border-radius: 5px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-start-shopping:hover {
    background: var(--dark-blue);
    transform: translateY(-2px);
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    padding: 20px;
}

.wishlist-card {
    background: var(--light);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.wishlist-card:hover {
    transform: translateY(-5px);
}

.product-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist-card:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 16px;
}

.product-info h3 {
    font-size: 16px;
    color: var(--dark);
    margin-bottom: 8px;
}

.price {
    font-size: 18px;
    color: var(--blue);
    font-weight: 600;
    margin-bottom: 16px;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-add-cart, .btn-remove {
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-cart {
    background: var(--blue);
    color: var(--light);
    flex: 1;
}

.btn-add-cart:hover {
    background: var(--dark-blue);
}

.btn-remove {
    background: var(--red);
    color: var(--light);
    padding: 8px;
}

.btn-remove:hover {
    background: var(--dark-red);
}

@media screen and (max-width: 768px) {
    .wishlist-grid {
        grid-template-columns: 1fr;
        padding: 10px;
    }

    .product-image {
        height: 180px;
    }
}
</style>

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