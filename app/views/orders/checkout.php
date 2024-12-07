<div class="payment-form">
    <h2>Complete Your Payment</h2>
    <div id="payment-element"></div>
    <button id="submit-payment">Pay Now</button>
    <div id="payment-message"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?php echo $data['stripe_public_key']; ?>');
const order = <?php echo json_encode($data['order']); ?>;

let elements;

initialize();
checkStatus();

async function initialize() {
    const { clientSecret } = await fetch("/orders/processPayment", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            order_id: order.id,
            amount: order.grand_total
        }),
    }).then((r) => r.json());

    elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: "<?php echo URLROOT; ?>/orders/confirmation/" + order.id,
        },
    });

    if (error) {
        const messageContainer = document.querySelector("#payment-message");
        messageContainer.textContent = error.message;
    }

    setLoading(false);
}

// ... Add loading state handlers and other UI functions ...
</script> 