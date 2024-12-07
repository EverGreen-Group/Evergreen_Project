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
            <form id="checkout-form" action="<?php echo URLROOT; ?>/shop/createOrder" method="POST">
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

                <input type="hidden" name="payment_method" id="selected_payment_method" value="bank">

                <div class="payment-methods">
                    <div class="payment-method">
                        <input type="radio" name="payment_method_radio" id="bank-transfer" value="bank" checked 
                               onclick="updatePaymentMethod(this.value)">
                        <label for="bank-transfer">Direct Bank Transfer</label>
                        <div class="payment-info">
                            Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.
                        </div>
                    </div>
                    <div class="payment-method">
                        <input type="radio" name="payment_method_radio" id="cod" value="cod" 
                               onclick="updatePaymentMethod(this.value)">
                        <label for="cod">Cash on Delivery</label>
                    </div>
                </div>

                <input type="hidden" name="total_amount" value="<?php echo $data['total']; ?>">
                <input type="hidden" name="shipping_fee" value="500.00">
                <input type="hidden" name="tax_amount" value="<?php echo $data['total'] * 0.10; ?>">
                <input type="hidden" name="grand_total" value="<?php echo $data['total'] + 500 + ($data['total'] * 0.10); ?>">
            
                
            </form>
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

            <div class="privacy-notice">
                Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our 
                <a href="<?php echo URLROOT; ?>/privacy-policy">privacy policy</a>.
            </div>

                     <button type="button" onclick="placeOrder()" class="place-order-btn">
                        Place Order
                    </button>

                    <div class="order-actions">
                    <a href="<?php echo URLROOT; ?>/shop/cart" class="back-to-cart">
                        Back to Cart
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<script>
function updatePaymentMethod(value) {
    document.getElementById('selected_payment_method').value = value;
}

function placeOrder() {
    const form = document.getElementById('checkout-form');
    
    if (!validateForm()) {
        return;
    }

    // Submit the form
    form.submit();
}

function validateForm() {
    const form = document.querySelector('form');
    const required = ['first_name', 'last_name', 'email', 'phone', 'street_address', 'city', 'province', 'zip_code'];
    
    for (let field of required) {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input.value.trim()) {
            alert(`Please fill in ${field.replace('_', ' ')}`);
            input.focus();
            return false;
        }
    }

    // Email validation
    const email = form.querySelector('[name="email"]');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
        alert('Please enter a valid email address');
        email.focus();
        return false;
    }

    // Phone validation
    const phone = form.querySelector('[name="phone"]');
    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(phone.value.trim())) {
        alert('Please enter a valid 10-digit phone number');
        phone.focus();
        return false;
    }

    return true;
}

function generateOrderId() {
    return 'ORD-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
}

function getCartItems() {
    // Get cart items from the order summary section
    const items = [];
    const itemRows = document.querySelectorAll('.product-row:not(.header):not(.subtotal):not(.shipping):not(.tax):not(.total)');
    
    itemRows.forEach(row => {
        const productText = row.querySelector('span:first-child').textContent;
        const priceText = row.querySelector('span:last-child').textContent;
        
        // Extract product name and quantity
        const [name, quantity] = productText.split('×').map(str => str.trim());
        // Extract price
        const price = parseFloat(priceText.replace('Rs. ', '').replace(',', ''));
        
        items.push({
            name: name,
            quantity: parseInt(quantity),
            price: price,
            subtotal: price * parseInt(quantity)
        });
    });
    
    return items;
}

function saveOrder(order) {
    // Get existing orders or initialize empty array
    let orders = JSON.parse(localStorage.getItem('orders') || '[]');
    
    // Add new order
    orders.push(order);
    
    // Save back to localStorage
    localStorage.setItem('orders', JSON.stringify(orders));
    
    // Clear cart
    localStorage.removeItem('cart');
}
</script>

<style>
.place-order-btn {
    width: 100%;
    padding: 15px;
    background-color: #006837;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
}

.place-order-btn:hover {
    background-color: #005229;
}

.back-to-cart {
    display: block;
    text-align: center;
    color: #666;
    text-decoration: none;
    margin-top: 10px;
}

.back-to-cart:hover {
    text-decoration: underline;
}
</style>

