<?php require APPROOT . '/views/inc/components/header.php'; ?>

<main class="payment-main">
    <div class="payment-container">
        <div class="order-progress">
            <div class="progress-step completed">
                <i class='bx bx-cart'></i>
                <span>Cart</span>
            </div>
            <div class="progress-step active">
                <i class='bx bx-credit-card'></i>
                <span>Payment</span>
            </div>
            <div class="progress-step">
                <i class='bx bx-package'></i>
                <span>Delivery</span>
            </div>
        </div>

        <div class="payment-grid">
            <!-- Order Summary -->
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-items">
                    <?php foreach($data['items'] as $item): ?>
                        <div class="summary-item">
                            <img src="<?php echo URLROOT; ?>/img/products/<?php echo $item->product_image; ?>" 
                                 alt="<?php echo $item->product_name; ?>">
                            <div class="item-info">
                                <h4><?php echo $item->product_name; ?></h4>
                                <p>Quantity: <?php echo $item->quantity; ?></p>
                                <p class="item-price">LKR <?php echo number_format($item->price * $item->quantity, 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="summary-totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>LKR <?php echo number_format($data['total'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Shipping</span>
                        <span>LKR 500.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total</span>
                        <span>LKR <?php echo number_format($data['total'] + 500, 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="payment-form-container">
                <h2>Payment Details</h2>
                <form id="payment-form" class="payment-form">
                    <!-- Delivery Address -->
                    <div class="form-section">
                        <h3>Delivery Address</h3>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" required></textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h3>Payment Method</h3>
                        <div class="payment-methods">
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="card" checked>
                                <i class='bx bx-credit-card'></i>
                                <span>Credit/Debit Card</span>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cod">
                                <i class='bx bx-money'></i>
                                <span>Cash on Delivery</span>
                            </label>
                        </div>
                    </div>

                    <!-- Card Details (shown/hidden based on payment method) -->
                    <div id="card-details" class="form-section">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="text" name="expiry" id="expiry" placeholder="MM/YY">
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="password" name="cvv" id="cvv" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Complete Order - LKR <?php echo number_format($data['total'] + 500, 2); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 