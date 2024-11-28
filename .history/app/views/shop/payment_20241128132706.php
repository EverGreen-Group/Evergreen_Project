<?php require APPROOT . '/views/inc/components/header.php'; ?>

<main class="checkout-main">
    <div class="checkout-header">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/img/logo_horizontal.svg" alt="Evergreen">
            <!-- <span style="color: var(--main)">Evergreen</span> -->
        </div>
        <h1>Checkout</h1>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>">Home</a>
            <span>></span>
            <span>Checkout</span>
        </div>
    </div>

    <div class="checkout-container">
        <div class="billing-section">
            <h2>Billing details</h2>
            <form id="checkout-form" action="<?php echo URLROOT; ?>/shop/processPayment" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Company Name (Optional)</label>
                    <input type="text" name="company_name">
                </div>

                <div class="form-group">
                    <label>Country / Region</label>
                    <select name="country" required>
                        <option value="Sri Lanka">Sri Lanka</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Street address</label>
                    <input type="text" name="street_address" required>
                </div>

                <div class="form-group">
                    <label>Town / City</label>
                    <input type="text" name="city" required>
                </div>

                <div class="form-group">
                    <label>Province</label>
                    <select name="province" required>
                        <option value="Western Province">Western Province</option>
                        <!-- Add other provinces -->
                    </select>
                </div>

                <div class="form-group">
                    <label>ZIP code</label>
                    <input type="text" name="zip_code" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" required>
                </div>

                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Additional information</label>
                    <textarea name="additional_info" placeholder="Additional Information"></textarea>
                </div>
            
        </div>

        <div class="order-summary">
            <h2>Your order</h2>
            <div class="order-details">
                <div class="product-row header">
                    <span>Product</span>
                    <span>Subtotal</span>
                </div>
                <?php foreach($data['items'] as $item): ?>
                <div class="product-row">
                    <span><?php echo $item->product_name; ?> × <?php echo $item->quantity; ?></span>
                    <span>Rs. <?php echo number_format($item->price * $item->quantity, 2); ?></span>
                </div>
                <?php endforeach; ?>
                <div class="product-row subtotal">
                    <span>Subtotal</span>
                    <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
                <div class="product-row total">
                    <span>Total</span>
                    <span class="total-amount">Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
            </div>

            <div class="payment-methods">
                <div class="payment-method">
                    <input type="radio" name="payment_method" id="bank-transfer" value="bank" checked>
                    <label for="bank-transfer">Direct Bank Transfer</label>
                    <div class="payment-info">
                        Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.
                    </div>
                </div>
                <div class="payment-method">
                    <input type="radio" name="payment_method" id="cod" value="cod">
                    <label for="cod">Cash on Delivery</label>
                </div>
            </div>

            <div class="privacy-notice">
                Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our 
                <a href="<?php echo URLROOT; ?>/privacy-policy">privacy policy</a>.
            </div>

            <button type="submit" class="btn-place-order">Place order</button>
            </form>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

