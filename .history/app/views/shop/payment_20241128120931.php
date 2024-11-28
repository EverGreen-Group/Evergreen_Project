<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="payment-container">
    <div class="payment-steps">
        <div class="step active">
            <i class='bx bx-cart'></i>
            <span>Cart</span>
        </div>
        <div class="step active">
            <i class='bx bx-credit-card'></i>
            <span>Payment</span>
        </div>
        <div class="step">
            <i class='bx bx-check-circle'></i>
            <span>Confirmation</span>
        </div>
    </div>

    <div class="payment-content">
        <div class="payment-methods">
            <h2>Select Payment Method</h2>
            
            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="card" checked>
                    <div class="option-content">
                        <i class='bx bx-credit-card'></i>
                        <span>Credit/Debit Card</span>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cash">
                    <div class="option-content">
                        <i class='bx bx-money'></i>
                        <span>Cash on Delivery</span>
                    </div>
                </label>
            </div>

            <div class="card-details" id="cardDetails">
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="text" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="text" placeholder="123" maxlength="3">
                    </div>
                </div>

                <div class="form-group">
                    <label>Card Holder Name</label>
                    <input type="text" placeholder="John Doe">
                </div>
            </div>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-items">
                <?php foreach($data['cart_items'] as $item): ?>
                    <div class="summary-item">
                        <span><?php echo $item->product_name; ?> x <?php echo $item->quantity; ?></span>
                        <span>LKR <?php echo number_format($item->price * $item->quantity, 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-total">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>LKR <?php echo number_format($data['cart_total'], 2); ?></span>
                </div>
                <div class="total-row">
                    <span>Shipping</span>
                    <span>LKR 500.00</span>
                </div>
                <div class="total-row">
                    <span>Tax (10%)</span>
                    <span>LKR <?php echo number_format($data['cart_total'] * 0.10, 2); ?></span>
                </div>
                <div class="total-row final">
                    <span>Total</span>
                    <span>LKR <?php echo number_format($data['cart_total'] + 500 + ($data['cart_total'] * 0.10), 2); ?></span>
                </div>
            </div>

            <button class="btn-confirm-payment" onclick="processPayment()">
                Confirm Payment
            </button>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 