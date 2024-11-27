// Global AJAX loading indicator
let loadingIndicator = null;

// Show loading indicator for AJAX requests
axios.interceptors.request.use(function (config) {
    loadingIndicator = Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Hide loading indicator after response
axios.interceptors.response.use(function (response) {
    if (loadingIndicator) {
        loadingIndicator.close();
    }
    return response;
}, function (error) {
    if (loadingIndicator) {
        loadingIndicator.close();
    }
    return Promise.reject(error);
});

// Format dates using moment.js
function formatDate(date, format = 'MMM D, YYYY') {
    return moment(date).format(format);
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Example usage in any view
async function someAjaxFunction() {
    try {
        // Show confirmation
        if (await AjaxUtils.confirm('Are you sure?')) {
            // Make AJAX call
            const response = await AjaxUtils.post('/your/endpoint', {
                data: 'value'
            });
            
            // Show success message
            AjaxUtils.showSuccess('Operation successful!');
            
            // Update UI
            updateUIFunction(response);
        }
    } catch (error) {
        // Error handling is automatic
        console.error(error);
    }
}

// Quantity Control Functions
function validateQuantity(input, max) {
    let value = parseInt(input.value);
    if (value < 1) input.value = 1;
    if (value > max) input.value = max;
}

function updateQuantity(productId, action) {
    const input = document.getElementById(`qty-${productId}`);
    let value = parseInt(input.value);
    const max = parseInt(input.max);
    
    if (action === 'increase' && value < max) {
        input.value = value + 1;
    } else if (action === 'decrease' && value > 1) {
        input.value = value - 1;
    }
}

// Cart Functions
async function addToCart(productId) {
    try {
        const response = await axios.post(`${URLROOT}/shop/addToCart`, {
            product_id: productId,
            quantity: 1  // Or get quantity from input if available
        });

        if (response.data.success) {
            // Update cart count in header
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = `Cart (${response.data.cart_count})`;
            }

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Item added to cart',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(response.data.message || 'Failed to add to cart');
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'An error occurred while adding to cart',
            timer: 2000,
            showConfirmButton: false
        });
    }
}

// Optional: Add a function to update cart count from any page
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = `Cart (${count})`;
    }
}

// Toast Notification Function
function showToast(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
} 