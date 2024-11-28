document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('payment-form');
    const cardNumber = document.getElementById('card_number');
    const expiry = document.getElementById('expiry');
    const cvv = document.getElementById('cvv');

    // Format card number
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})/g, '$1 ').trim();
        e.target.value = value;
    });

    // Format expiry date
    expiry.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0,2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // Handle form submission
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch(`${URLROOT}/shop/processPayment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                    card_number: cardNumber.value,
                    expiry: expiry.value,
                    cvv: cvv.value,
                    card_holder: document.getElementById('card_holder').value
                })
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
}); 