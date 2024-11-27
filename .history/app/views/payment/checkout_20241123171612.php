<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="payment-container">
    <div class="payment-form">
        <h2>Payment Details</h2>
        <div id="payment-element">
            <!-- Stripe Elements will be inserted here -->
        </div>
        <button id="submit-payment" class="payment-button">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Pay Now</span>
        </button>
        <div id="payment-message" class="hidden"></div>
    </div>
</div>

<style>
.payment-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
}

.payment-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.payment-button {
    background: #5469d4;
    color: #ffffff;
    border-radius: 4px;
    border: 0;
    padding: 12px 16px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: block;
    width: 100%;
    transition: all 0.2s ease;
    box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
}

.payment-button:hover {
    filter: contrast(115%);
}

.payment-button:disabled {
    opacity: 0.5;
    cursor: default;
}

.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.hidden {
    display: none;
}

#payment-message {
    color: rgb(105, 115, 134);
    font-size: 16px;
    line-height: 20px;
    padding-top: 12px;
    text-align: center;
}
</style>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?php echo $data['stripe_public_key']; ?>');
let elements;

initialize();
checkStatus();

document.querySelector("#payment-form").addEventListener("submit", handleSubmit);

async function initialize() {
    const { clientSecret } = await fetch("<?php echo URLROOT; ?>/payment/processPayment", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            amount: 1000, // Amount in cents
            order_id: "123" // Replace with actual order ID
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
            return_url: "<?php echo URLROOT; ?>/payment/confirmation",
        },
    });

    if (error) {
        const messageContainer = document.querySelector("#payment-message");
        messageContainer.textContent = error.message;
        messageContainer.classList.remove("hidden");
        setLoading(false);
    }
}

function setLoading(isLoading) {
    if (isLoading) {
        document.querySelector("#submit-payment").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("#submit-payment").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 