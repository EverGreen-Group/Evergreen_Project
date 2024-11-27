<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Shopping Cart</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Cart</a></li>
            </ul>
        </div>
    </div>

    <div class="cart-container">
        <?php if(empty($data['cart_items'])): ?>
            <div class="empty-cart">
                <i class='bx bx-cart'></i>
                <h2>Your cart is empty</h2>
                <p>Browse our collection and add some tea to your cart!</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-shop">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach($data['cart_items'] as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?php echo URLROOT; ?>/public/img/products/<?php echo $item->product_image; ?>" 
                                 alt="<?php echo $item->product_name; ?>">
                        </div>
                        <div class="item-details">
                            <h3><?php echo $item->product_name; ?></h3>
                            <p class="price">LKR <?php echo number_format($item->price, 2); ?></p>
                            <p class="weight"><?php echo $item->weight . $item->weight_unit; ?></p>
                            
                            <div class="quantity-controls">
                                <button onclick="updateQuantity(<?php echo $item->product_id; ?>, 'decrease')" 
                                        class="btn-quantity">-</button>
                                <span class="quantity"><?php echo $item->quantity; ?></span>
                                <button onclick="updateQuantity(<?php echo $item->product_id; ?>, 'increase')" 
                                        class="btn-quantity">+</button>
                            </div>
                            
                            <button onclick="removeFromCart(<?php echo $item->product_id; ?>)" 
                                    class="btn-remove">Remove</button>
                        </div>
                        <div class="item-subtotal">
                            <p>Subtotal:</p>
                            <h4>LKR <?php echo number_format($item->price * $item->quantity, 2); ?></h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <div class="summary-row">
                    <span>Total Items:</span>
                    <span><?php echo count($data['cart_items']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>LKR <?php echo number_format($data['cart_total'], 2); ?></span>
                </div>
                <button onclick="proceedToCheckout()" class="btn-checkout">
                    Proceed to Checkout
                </button>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-continue">
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.cart-container {
    padding: 20px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
}

.cart-items {
    background: var(--light);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 20px;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.item-image img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0;
}

.btn-quantity {
    background: var(--light);
    border: 1px solid #ddd;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-remove {
    color: var(--red);
    background: none;
    border: none;
    cursor: pointer;
}

.cart-summary {
    background: var(--light);
    padding: 20px;
    border-radius: 15px;
    height: fit-content;
}

.btn-checkout {
    background: var(--success);
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    width: 100%;
    margin-top: 20px;
    cursor: pointer;
}

.empty-cart {
    text-align: center;
    padding: 40px;
}

.empty-cart i {
    font-size: 48px;
    color: var(--grey);
    margin-bottom: 20px;
}
</style>

<script>
async function updateQuantity(productId, action) {
    try {
        const response = await fetch(`${URLROOT}/shop/updateCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                action: action
            })
        });

        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to update cart');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update cart');
    }
}

async function removeFromCart(productId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    try {
        const response = await fetch(`${URLROOT}/shop/removeFromCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        });

        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to remove item');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to remove item');
    }
}

function proceedToCheckout() {
    window.location.href = `${URLROOT}/shop/checkout`;
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 