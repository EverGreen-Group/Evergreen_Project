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

    <?php flash('cart_message'); ?>

    <div class="cart-container">
        <?php if(empty($data['items'])): ?>
            <div class="empty-cart">
                <i class='bx bx-cart'></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-continue-shopping">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach($data['items'] as $item): ?>
                        <div class="cart-item" id="cart-item-<?php echo $item->product_id; ?>">
                            <div class="item-image">
                                <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->product_image; ?>" 
                                     alt="<?php echo $item->product_name; ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo $item->product_name; ?></h3>
                                <div class="item-price">
                                    Rs. <?php echo number_format($item->price, 2); ?>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn minus" onclick="updateQuantity(<?php echo $item->product_id; ?>, 'decrease')">
                                        <i class='bx bx-minus'></i>
                                    </button>
                                    <input type="number" value="<?php echo $item->quantity; ?>" 
                                           min="1" max="<?php echo $item->stock; ?>"
                                           onchange="updateQuantity(<?php echo $item->product_id; ?>, 'set', this.value)">
                                    <button class="qty-btn plus" onclick="updateQuantity(<?php echo $item->product_id; ?>, 'increase')">
                                        <i class='bx bx-plus'></i>
                                    </button>
                                </div>
                                <div class="item-subtotal">
                                    Subtotal: Rs. <?php echo number_format($item->price * $item->quantity, 2); ?>
                                </div>
                            </div>
                            <button class="remove-item" onclick="removeItem(<?php echo $item->product_id; ?>)">
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
                    <div class="summary-row">
                        <span>Shipping</span>
                        <select id="shipping-method" onchange="updateShipping()">
                            <?php foreach($data['shipping_methods'] as $method): ?>
                                <option value="<?php echo $method->id; ?>" 
                                        data-price="<?php echo $method->price; ?>">
                                    <?php echo $method->name; ?> - Rs. <?php echo number_format($method->price, 2); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="summary-row">
                        <span>Tax (15%)</span>
                        <span>Rs. <?php echo number_format($data['total'] * 0.15, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="grand-total">
                            Rs. <?php echo number_format($data['total'] * 1.15 + ($data['shipping_methods'][0]->price ?? 0), 2); ?>
                        </span>
                    </div>

                    <form action="<?php echo URLROOT; ?>/shop/checkout" method="POST" id="checkout-form">
                        <input type="hidden" name="shipping_method" id="selected-shipping-method" 
                               value="<?php echo $data['shipping_methods'][0]->id ?? ''; ?>">
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" required>
                                <option value="cash">Cash on Delivery</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes">Order Notes (Optional)</label>
                            <textarea name="notes" id="notes"></textarea>
                        </div>

                        <button type="submit" class="btn-checkout">
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.cart-container {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    margin: 20px;
}

.cart-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.cart-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Add more styles... */
</style>

<script>
async function updateQuantity(productId, action, value = null) {
    try {
        let quantity;
        const input = document.querySelector(`#cart-item-${productId} input`);
        const currentQty = parseInt(input.value);
        const maxQty = parseInt(input.max);

        switch(action) {
            case 'increase':
                quantity = currentQty < maxQty ? currentQty + 1 : currentQty;
                break;
            case 'decrease':
                quantity = currentQty > 1 ? currentQty - 1 : 1;
                break;
            case 'set':
                quantity = Math.min(Math.max(1, parseInt(value)), maxQty);
                break;
        }

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
            input.value = quantity;
            updateCartTotals(data.totals);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to update quantity', 'error');
    }
}

async function removeItem(productId) {
    if (!confirm('Are you sure you want to remove this item?')) return;

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
            document.querySelector(`#cart-item-${productId}`).remove();
            updateCartTotals(data.totals);
            
            if (data.totals.items_count === 0) {
                location.reload(); // Show empty cart message
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to remove item', 'error');
    }
}

function updateShipping() {
    const select = document.getElementById('shipping-method');
    const shippingPrice = parseFloat(select.options[select.selectedIndex].dataset.price);
    document.getElementById('selected-shipping-method').value = select.value;
    updateCartTotals({
        subtotal: <?php echo $data['total']; ?>,
        shipping: shippingPrice
    });
}

function updateCartTotals(totals) {
    // Update all price displays
    const subtotal = parseFloat(totals.subtotal);
    const shipping = parseFloat(totals.shipping);
    const tax = subtotal * 0.15;
    const grandTotal = subtotal + shipping + tax;

    // Update DOM elements
    document.querySelector('.summary-row:first-child span:last-child').textContent = 
        `Rs. ${subtotal.toFixed(2)}`;
    document.querySelector('.summary-row:nth-child(3) span:last-child').textContent = 
        `Rs. ${tax.toFixed(2)}`;
    document.getElementById('grand-total').textContent = 
        `Rs. ${grandTotal.toFixed(2)}`;
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 