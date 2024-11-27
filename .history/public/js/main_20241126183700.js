async function addToCart(productId) {
    try {
        const response = await fetch(`${URLROOT}/shop/addToCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await response.json();
        if (data.success) {
            document.querySelector('.btn-cart span').textContent = 
                `Cart (${data.cart_count})`;
            showToast('Product added to cart successfully');
        } else {
            showToast('Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

// Cart Functions
async function updateQuantity(productId, action) {
    try {
        const response = await fetch(`${URLROOT}/shop/updateCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                action: action
            })
        });

        if (response.ok) {
            location.reload();
        } else {
            showToast('Failed to update cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to update cart', 'error');
    }
}

async function removeFromCart(productId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    try {
        const response = await fetch(`${URLROOT}/shop/removeFromCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        });

        if (response.ok) {
            location.reload();
        } else {
            showToast('Failed to remove item', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to remove item', 'error');
    }
}

// Toast notification function if not already defined
function showToast(message, type = 'success') {
    // Implementation of toast notification
    // You can use your existing toast notification system
} 