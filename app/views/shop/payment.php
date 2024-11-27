<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_shop.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Payment</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/shop">Shop</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?php echo URLROOT; ?>/shop/cart">Cart</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Payment</a></li>
            </ul>
        </div>
    </div>

    <div class="payment-container">
        <div class="payment-form">
            <h2>Payment Details</h2>
            <form id="paymentForm" onsubmit="processPayment(event)">
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="text" id="expiryDate" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="text" id="cvv" placeholder="123" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Card Holder Name</label>
                    <input type="text" id="cardName" placeholder="John Doe" required>
                </div>

                <button type="submit" class="btn-pay">
                    Pay LKR <?php echo number_format($data['order']->total_amount, 2); ?>
                </button>
            </form>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <!-- Display order details here -->
        </div>
    </div>
</main>

<style>
.payment-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
    padding: 20px;
}

.payment-form {
    background: var(--light);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Add more styles... */
</style>

<script>
async function processPayment(event) {
    event.preventDefault();
    
    try {
        // Simulate payment processing
        const response = await fetch(`${URLROOT}/shop/processPayment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                order_id: '<?php echo $data['order']->id; ?>',
                // Add payment details
            })
        });

        const result = await response.json();
        
        if (result.success) {
            showToast('Payment successful!');
            // Redirect to orders page after successful payment
            setTimeout(() => {
                window.location.href = `${URLROOT}/shop/orders`;
            }, 2000);
        } else {
            showToast(result.message || 'Payment failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Payment processing failed', 'error');
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 