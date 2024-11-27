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

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 