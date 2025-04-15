<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="checkout-container">
    <h1>Checkout</h1>
    
    <?php flash('checkout_message'); ?>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="cart-items">
            <?php foreach($data['cart_items'] as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo URLROOT; ?>/public/img/products/<?php echo $item->product_image; ?>" 
                         alt="<?php echo $item->product_name; ?>">
                    <div class="item-details">
                        <h3><?php echo $item->product_name; ?></h3>
                        <p>Quantity: <?php echo $item->quantity; ?></p>
                        <p>Price: LKR <?php echo number_format($item->price, 2); ?></p>
                        <p>Subtotal: LKR <?php echo number_format($item->price * $item->quantity, 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="order-totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>LKR <?php echo number_format($data['total_amount'], 2); ?></span>
            </div>
            <div class="total-row">
                <span>Shipping Fee:</span>
                <span>LKR <?php echo number_format($data['shipping_fee'], 2); ?></span>
            </div>
            <div class="total-row">
                <span>Tax (10%):</span>
                <span>LKR <?php echo number_format($data['tax_amount'], 2); ?></span>
            </div>
            <div class="total-row grand-total">
                <span>Grand Total:</span>
                <span>LKR <?php echo number_format($data['grand_total'], 2); ?></span>
            </div>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/shop/checkout" method="POST" id="checkout-form">
        <div class="shipping-details">
            <h2>Shipping Details</h2>
            <div class="form-group">
                <label for="shipping_address">Shipping Address</label>
                <textarea name="shipping_address" id="shipping_address" required></textarea>
            </div>
            <div class="form-group">
                <label for="notes">Order Notes (Optional)</label>
                <textarea name="notes" id="notes"></textarea>
            </div>
        </div>

        <div class="payment-method">
            <h2>Payment Method</h2>
            <div class="form-group">
                <input type="radio" name="payment_method" value="stripe" checked>
                <label>Credit Card (Stripe)</label>
            </div>
        </div>

        <button type="submit" class="btn-place-order">Place Order</button>
    </form>
</div>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.cart-items {
    margin: 20px 0;
}

.cart-item {
    display: flex;
    gap: 20px;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.cart-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.order-totals {
    margin-top: 20px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
}

.grand-total {
    font-weight: bold;
    font-size: 1.2em;
    border-top: 2px solid #333;
    margin-top: 10px;
    padding-top: 10px;
}

.shipping-details, .payment-method {
    margin: 30px 0;
}

.form-group {
    margin: 15px 0;
}

.btn-place-order {
    background: var(--success);
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 1.1em;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 
#payment-message {
    color: #dc3545;
    margin-top: 10px;
}
</style> 