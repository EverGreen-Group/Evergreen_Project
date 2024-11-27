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
        <?php if(empty($data['cart_items'])): ?>
            <div class="empty-cart">
                <i class='bx bx-cart-alt'></i>
                <h2>Your cart is empty</h2>
                <p>Browse our collection and add some tea to your cart!</p>
                <a href="<?php echo URLROOT; ?>/shop" class="btn-shop">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <div class="cart-header">
                    <span>Product</span>
                    <span>Price</span>
                    <span>Quantity</span>
                    <span>Total</span>
                    <span>Action</span>
                </div>
                
                <?php foreach($data['cart_items'] as $item): ?>
                    <div class="cart-item">
                        <div class="item-product">
                        <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->image ?? 'default.jpg'; ?>" 
                            alt="<?php echo $item->name; ?>"
                            onerror="this.src='<?php echo URLROOT; ?>/img/products/default.jpg'">
                                 
                            <div class="product-info">
                                <h3><?php echo $item->product_name; ?></h3>
                                <p class="weight"><?php echo $item->weight . $item->weight_unit; ?></p>
                            </div>
                        </div>
                        
                        <div class="item-price">
                            LKR <?php echo number_format($item->price, 2); ?>
                        </div>
                        
                        <div class="quantity-controls">
                            <button onclick="updateQuantity(<?php echo $item->product_id; ?>, 'decrease')" 
                                    class="btn-quantity" <?php echo $item->quantity <= 1 ? 'disabled' : ''; ?>>
                                <i class='bx bx-minus'></i>
                            </button>
                            <span class="quantity"><?php echo $item->quantity; ?></span>
                            <button onclick="updateQuantity(<?php echo $item->product_id; ?>, 'increase')" 
                                    class="btn-quantity">
                                <i class='bx bx-plus'></i>
                            </button>
                        </div>
                        
                        <div class="item-total">
                            LKR <?php echo number_format($item->price * $item->quantity, 2); ?>
                        </div>
                        
                        <button onclick="removeFromCart(<?php echo $item->product_id; ?>)" 
                                class="btn-remove" title="Remove item">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal (<?php echo count($data['cart_items']); ?> items)</span>
                    <span>LKR <?php echo number_format($data['cart_total'], 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping Fee</span>
                    <span>LKR <?php echo number_format(500.00, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (10%)</span>
                    <span>LKR <?php echo number_format($data['cart_total'] * 0.10, 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Estimated Total</span>
                    <span>LKR <?php echo number_format($data['cart_total'] + 500 + ($data['cart_total'] * 0.10), 2); ?></span>
                </div>
                
                <form action="<?php echo URLROOT; ?>/shop/checkout" method="POST" class="checkout-form">
                    <div class="shipping-address">
                        <h4>Shipping Address</h4>
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" name="full_name" id="full_name" required 
                                   placeholder="Enter your full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" required 
                                   placeholder="Enter your phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Street Address *</label>
                            <textarea name="address" id="address" required 
                                      placeholder="Enter your street address"></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" name="city" id="city" required 
                                       placeholder="Enter your city">
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code *</label>
                                <input type="text" name="postal_code" id="postal_code" required 
                                       placeholder="Enter postal code">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Order Notes (Optional)</label>
                            <textarea name="notes" id="notes" 
                                      placeholder="Add any special notes for delivery"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout">
                        <i class='bx bx-credit-card-front'></i>
                        Proceed to Payment
                    </button>
                </form>
                
                <a href="<?php echo URLROOT; ?>/shop" class="btn-continue">
                    <i class='bx bx-arrow-back'></i>
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
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

.cart-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 80px;
    padding: 15px;
    background: var(--grey-light);
    border-radius: 10px 10px 0 0;
    font-weight: 600;
}

.cart-items {
    background: var(--light);
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 80px;
    align-items: center;
    padding: 20px 15px;
    border-bottom: 1px solid var(--grey-light);
}

.item-product {
    display: flex;
    gap: 15px;
    align-items: center;
}

.item-product img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.product-info h3 {
    margin-bottom: 5px;
    font-size: 1.1em;
}

.weight {
    color: var(--dark-grey);
    font-size: 0.9em;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--grey-light);
    padding: 8px;
    border-radius: 8px;
    width: fit-content;
}

.btn-quantity {
    background: var(--light);
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-quantity:hover:not([disabled]) {
    background: var(--main);
    color: var(--light);
}

.btn-quantity[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity {
    font-weight: 600;
    min-width: 30px;
    text-align: center;
}

.btn-remove {
    color: var(--green);
    border: none;
    cursor: pointer;
    font-size: 1.2em;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-remove:hover {
    background: var(--dark-green);
}

.cart-summary {
    background: var(--light);
    padding: 25px;
    border-radius: 15px;
    height: fit-content;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.cart-summary h3 {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--grey-light);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    color: var(--dark-grey);
}

.summary-row.total {
    font-weight: 600;
    font-size: 1.1em;
    color: var(--dark);
    border-top: 2px solid var(--grey-light);
    margin-top: 10px;
    padding-top: 20px;
}

.btn-checkout {
    background: var(--green);
    color: white;
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-checkout:hover {
    background: var(--dark-green);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 128, 0, 0.15);
}

.btn-checkout i {
    font-size: 1.2em;
}

.btn-continue {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
    color: var(--primary);
    text-decoration: none;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-continue:hover {
    background: var(--grey-light);
}

.empty-cart {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: var(--light);
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.empty-cart i {
    font-size: 64px;
    color: var(--grey);
    margin-bottom: 20px;
}

.empty-cart h2 {
    margin-bottom: 10px;
    color: var(--dark);
}

.empty-cart p {
    color: var(--dark-grey);
    margin-bottom: 25px;
}

.btn-shop {
    display: inline-block;
    background: var(--primary);
    color: var(--light);
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-shop:hover {
    background: var(--primary-dark);
}

@media screen and (max-width: 1024px) {
    .cart-container {
        grid-template-columns: 1fr;
    }
}

@media screen and (max-width: 768px) {
    .cart-header {
        display: none;
    }
    
    .cart-item {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .item-price, .item-total {
        text-align: left;
    }
    
    .btn-remove {
        justify-self: flex-end;
    }
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


</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 