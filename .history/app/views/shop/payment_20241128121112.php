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
            <i class='bx bx-package'></i>
            <span>Tracking</span>
        </div>
    </div>

    <div class="payment-content">
        <div class="payment-summary">
            <h2>Order Summary</h2>
            <div class="summary-details">
                <div class="summary-row">
                    <span>Order Total</span>
                    <span>LKR <?php echo number_format($data['total'], 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>LKR 500.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total to Pay</span>
                    <span>LKR <?php echo number_format($data['total'] + 500, 2); ?></span>
                </div>
            </div>
        </div>

        <div class="payment-methods">
            <h2>Select Payment Method</h2>
            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="card" checked>
                    <i class='bx bx-credit-card'></i>
                    <span>Credit/Debit Card</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cash">
                    <i class='bx bx-money'></i>
                    <span>Cash on Delivery</span>
                </label>
            </div>

            <form id="payment-form" class="card-payment-form">
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" id="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="text" id="expiry" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="password" id="cvv" placeholder="123" maxlength="3">
                    </div>
                </div>
                <div class="form-group">
                    <label>Card Holder Name</label>
                    <input type="text" id="card_holder" placeholder="John Doe">
                </div>

                <button type="submit" class="btn-pay">
                    Pay Now LKR <?php echo number_format($data['total'] + 500, 2); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 