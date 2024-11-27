<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="container">
    <div class="payment-form">
        <h2>Complete Payment</h2>
        <div id="payment-element"></div>
        <button id="submit-payment">Pay Now</button>
        <div id="payment-message"></div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?php echo STRIPE_PUBLIC_KEY; ?>');
const elements = stripe.elements();

// Create payment element
const paymentElement = elements.create('payment');
paymentElement.mount('#payment-element');

// Handle form submission
const form = document.getElementById('payment-form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const {error} = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: '<?php echo URLROOT; ?>/shop/confirmation',
        },
    });

    if (error) {
        const messageDiv = document.getElementById('payment-message');
        messageDiv.textContent = error.message;
    }
});
</script>

<style>
.payment-form {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#payment-element {
    margin: 20px 0;
}

#submit-payment {
    background: #32CD32;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

#payment-message {
    color: #dc3545;
    margin-top: 10px;
}
</style> 