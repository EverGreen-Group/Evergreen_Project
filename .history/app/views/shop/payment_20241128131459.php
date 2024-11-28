<?php require APPROOT . '/views/inc/components/header.php'; ?>

<main class="checkout-main">
    <div class="page-header">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/img/logo.png" alt="Furniro">
        </div>
        <h1>Checkout</h1>
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>">Home</a>
            <span>></span>
            <span>Checkout</span>
        </div>
    </div>

    <div class="checkout-container">
        <div class="billing-form">
            <h2>Billing details</h2>
            <form id="checkout-form">
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
                        <option value="Central Province">Central Province</option>
                        <option value="Southern Province">Southern Province</option>
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
            </form>
        </div>

        <div class="order-summary">
            <h3>Product</h3>
            <div class="order-items">
                <?php foreach($data['items'] as $item): ?>
                    <div class="order-item">
                        <span class="item-name">
                            <?php echo $item->product_name; ?> × <?php echo $item->quantity; ?>
                        </span>
                        <span class="item-price">
                            Rs. <?php echo number_format($item->price * $item->quantity, 2); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
                <div class="total-row grand-total">
                    <span>Total</span>
                    <span class="total-amount">Rs. <?php echo number_format($data['total'], 2); ?></span>
                </div>
            </div>

            <div class="payment-methods">
                <div class="payment-method">
                    <input type="radio" id="bank-transfer" name="payment_method" value="bank_transfer" checked>
                    <label for="bank-transfer">Direct Bank Transfer</label>
                    <div class="payment-info">
                        Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.
                    </div>
                </div>

                <div class="payment-method">
                    <input type="radio" id="cod" name="payment_method" value="cod">
                    <label for="cod">Cash on Delivery</label>
                </div>
            </div>

            <div class="privacy-notice">
                Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our 
                <a href="<?php echo URLROOT; ?>/privacy-policy">privacy policy</a>.
            </div>

            <button type="submit" class="btn-place-order">Place order</button>
        </div>
    </div>
</main>

<style>
/* Checkout Styles */
.checkout-main {
    padding: 2rem;
    background: var(--light);
}

.page-header {
    text-align: center;
    margin-bottom: 3rem;
}

.checkout-container {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.billing-form {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #E7E7E7;
    border-radius: 5px;
    background: #fff;
}

.order-summary {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #E7E7E7;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
}

.grand-total {
    font-weight: bold;
    font-size: 1.2rem;
    border-top: 2px solid #E7E7E7;
    margin-top: 1rem;
}

.payment-methods {
    margin: 2rem 0;
}

.payment-method {
    margin-bottom: 1rem;
}

.payment-info {
    margin-top: 0.5rem;
    padding: 1rem;
    background: #F9F9F9;
    font-size: 0.9rem;
    color: #666;
}

.btn-place-order {
    width: 100%;
    padding: 1rem;
    background: #000;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
}

.privacy-notice {
    margin: 1.5rem 0;
    font-size: 0.9rem;
    color: #666;
}

@media (max-width: 768px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const formData = new FormData(this);
        const response = await fetch(`${URLROOT}/shop/processPayment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });

        const data = await response.json();
        if (data.success) {
            window.location.href = `${URLROOT}/shop/tracking/${data.order_id}`;
        } else {
            alert(data.message || 'Payment failed. Please try again.');
        }
    } catch (error) {
        console.error('Payment error:', error);
        alert('An error occurred. Please try again.');
    }
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 