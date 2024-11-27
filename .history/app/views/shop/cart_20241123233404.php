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
        <?php if(empty($data['items'])): ?>
            <div class="empty-cart">
                <i class='bx bx-cart'></i>
                <h2>Your cart is empty</h2>
                <p>Add some products to your cart and they will appear here.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-shop">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach($data['items'] as $item): ?>
                    <div class="cart-item" data-id="<?php echo $item->product_id; ?>">
                        <div class="item-image">
                            <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->image ?? 'default.jpg'; ?>" 
                                 alt="<?php echo $item->name; ?>">
                        </div>
                        <div class="item-details">
                            <h3><?php echo $item->name; ?></h3>
                            <p class="price">Rs. <?php echo number_format($item->price, 2); ?></p>
                            <div class="quantity-controls">
                                <button onclick="updateQuantity(<?php echo $item->product_id; ?>, 'decrease')" 
                                        class="btn-quantity">
                                    <i class='bx bx-minus'></i>
                                </button>
                                <input type="number" 
                                       value="<?php echo $item->quantity; ?>" 
                                       min="1" 
                                       max="<?php echo $item->stock; ?>"
                                       onchange="updateQuantity(<?php echo $item->product_id; ?>, 'set', this.value)">
                                <button onclick="updateQuantity(<?php echo $item->product_id; ?>, 'increase')" 
                                        class="btn-quantity">
                                    <i class='bx bx-plus'></i>
                                </button>
                            </div>
                        </div>
                        <div class="item-total">
                            <p>Subtotal:</p>
                            <h4>Rs. <?php echo number_format($item->price * $item->quantity, 2); ?></h4>
                        </div>
                        <button onclick="removeFromCart(<?php echo $item->product_id; ?>)" 
                                class="btn-remove">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
                <a href="<?php echo URLROOT; ?>/shop/checkout" class="btn-checkout">
                    Proceed to Checkout
                </a>
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
    grid-template-columns: 100px 1fr auto auto;
    gap: 20px;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 100px;
    height: 100px;
    overflow: hidden;
    border-radius: 10px;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details h3 {
    margin-bottom: 10px;
    color: var(--dark);
}

.price {
    color: var(--blue);
    font-weight: 500;
    margin-bottom: 10px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-quantity {
    background: var(--light);
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-quantity:hover {
    background: #f5f5f5;
}

.quantity-controls input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
}

.btn-remove {
    background: none;
    border: none;
    color: var(--red);
    cursor: pointer;
    padding: 5px;
    transition: all 0.3s ease;
}

.btn-remove:hover {
    transform: scale(1.1);
}

.cart-summary {
    background: var(--light);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: fit-content;
}

.cart-summary h3 {
    margin-bottom: 20px;
    color: var(--dark);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: var(--dark);
}

.summary-row.total {
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: 15px;
    font-weight: bold;
    font-size: 1.1em;
}

.btn-checkout, .btn-continue {
    display: block;
    width: 100%;
    padding: 12px;
    text-align: center;
    border-radius: 8px;
    margin-bottom: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-checkout {
    background: var(--blue);
    color: var(--light);
}

.btn-continue {
    background: var(--grey);
    color: var(--dark);
}

.btn-checkout:hover, .btn-continue:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.empty-cart {
    text-align: center;
    padding: 40px;
    background: var(--light);
    border-radius: 15px;
    grid-column: 1 / -1;
}

.empty-cart i {
    font-size: 48px;
    color: var(--grey);
    margin-bottom: 20px;
}

@media screen and (max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
    }

    .cart-item {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .item-image {
        margin: 0 auto;
    }

    .quantity-controls {
        justify-content: center;
    }
}
</style>

<script>
async function updateQuantity(productId, action, value = null) {
    let quantity;
    const input = document.querySelector(`.cart-item[data-id="${productId}"] input`);
    const currentQuantity = parseInt(input.value);
    const maxStock = parseInt(input.max);

    switch(action) {
        case 'increase':
            quantity = currentQuantity + 1;
            if (quantity > maxStock) return;
            break;
        case 'decrease':
            quantity = currentQuantity - 1;
            if (quantity < 1) return;
            break;
        case 'set':
            quantity = parseInt(value);
            if (quantity < 1 || quantity > maxStock) return;
            break;
    }

    try {
        const response = await fetch(`${URLROOT}/shop/updateCart`, {
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
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update cart');
    }
}

async function removeFromCart(productId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
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

        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to remove item from cart');
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 