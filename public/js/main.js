async function addToCart(productId) {
    try {
        const quantity = document.getElementById(`qty-${productId}`)?.value || 1;
        
        const response = await fetch(`${URLROOT}/shop/addToCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Update cart count if you have a cart counter element
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
            }
            
            // Show success message
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                timer: 2000
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Failed to add item to cart',
            icon: 'error'
        });
    }
}

async function updateCartQuantity(productId, element) {
    try {
        const quantity = parseInt(element.value);
        if (quantity < 1) {
            element.value = 1;
            return;
        }

        const response = await axios.post(`${URLROOT}/shop/updateCartItem`, {
            product_id: productId,
            quantity: quantity
        });

        if (response.data.success) {
            updateCartSummary(response.data.summary);
        } else {
            throw new Error(response.data.message);
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: error.response?.data?.message || error.message,
            icon: 'error'
        });
    }
}

async function removeFromCart(productId) {
    try {
        const response = await fetch(`${URLROOT}/shop/removeFromCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: productId
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Remove the cart item element from DOM
            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            if (cartItem) {
                cartItem.remove();
            }

            // Update cart totals
            updateCartTotals(data.totals);

            // If cart is empty, reload the page to show empty cart message
            if (data.totals.items_count === 0) {
                location.reload();
            }

            // Show success message
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                timer: 2000
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Failed to remove item from cart',
            icon: 'error'
        });
    }
}

function updateCartSummary(summary) {
    document.getElementById('cart-subtotal').textContent = `Rs. ${summary.subtotal.toFixed(2)}`;
    document.getElementById('cart-items-count').textContent = summary.item_count;
    document.getElementById('cart-total-items').textContent = summary.total_items;
    
    // Update total if shipping is selected
    const shippingSelect = document.getElementById('shipping-method');
    if (shippingSelect) {
        const shippingCost = parseFloat(shippingSelect.value);
        const total = summary.subtotal + shippingCost;
        document.getElementById('cart-total').textContent = `Rs. ${total.toFixed(2)}`;
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

// Tea Regions Interactive Map
document.addEventListener('DOMContentLoaded', function() {
    const regionData = {
        'kandy': {
            title: 'Kandy',
            description: 'Grown in the ancient city of Kandy, the cultural capital of Sri Lanka, this mid-country tea provides a one of a kind appealing flavour. A dark golden liquor with full bodied strong characters, Kandy teas are grown at 2,000 – 4,000 feet above sea level. A tea which can be enjoyed with a touch of cream and sweetener as a relaxing all day beverage.',
            image: 'kandy-tea.png'
        },
        'dimbula': {
            title: 'Dimbula',
            description: 'Known for its exceptional quality, Dimbula tea is cultivated at elevations between 3,500 and 5,000 feet. These teas produce a bright, medium-bodied brew with a golden hue and refreshing character.',
            image: 'dimbula-tea.png'
        },
        'nuwara-eliya': {
            title: 'Nuwara Eliya',
            description: 'Grown at elevations over 6,000 feet, Nuwara Eliya produces the most delicate of Ceylon teas. Light and bright in color, with a crisp, refreshing flavor that carries hints of pine and eucalyptus.',
            image: 'nuwara-tea.png'
        },
        'uva': {
            title: 'Uva',
            description: 'Uva teas, grown at elevations between 3,000 and 5,000 feet, are famous for their distinctive flavor and aroma. The unique climate conditions create a tea that's noticeably different from other regions.',
            image: 'uva-tea.png'
        },
        'ruhuna': {
            title: 'Ruhuna',
            description: 'Grown in southern Sri Lanka at elevations up to 2,000 feet, Ruhuna teas are known for their strong, full-bodied flavor with a rich, dark color and robust character.',
            image: 'ruhuna-tea.png'
        }
    };

    const tabBtns = document.querySelectorAll('.tab-btn');
    const markers = document.querySelectorAll('.marker');
    const contentArea = document.querySelector('.region-content');

    function updateContent(region) {
        const data = regionData[region];
        const content = `
            <div class="region-details active" id="${region}">
                <div class="tea-image">
                    <img src="${URLROOT}/img/teas/${data.image}" alt="${data.title} Tea">
                </div>
                <div class="tea-info">
                    <h3>${data.title}</h3>
                    <p>${data.description}</p>
                </div>
            </div>
        `;
        
        contentArea.innerHTML = content;
    }

    function switchRegion(regionName) {
        // Remove active class from all tabs
        tabBtns.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to selected tab
        const activeTab = document.querySelector(`.tab-btn[data-region="${regionName}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // Remove active class from all markers
        markers.forEach(marker => {
            marker.classList.remove('active');
            marker.style.transform = 'scale(1)';
        });

        // Add active class to selected marker
        const activeMarker = document.querySelector(`.marker[data-region="${regionName}"]`);
        if (activeMarker) {
            activeMarker.classList.add('active');
            activeMarker.style.transform = 'scale(1.5)';
        }

        // Update content
        updateContent(regionName);
    }

    // Event listeners for tabs
    tabBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const region = e.target.dataset.region;
            switchRegion(region);
        });
    });

    // Event listeners for markers
    markers.forEach(marker => {
        marker.addEventListener('click', (e) => {
            const region = e.target.dataset.region;
            switchRegion(region);
        });

        // Hover effects
        marker.addEventListener('mouseenter', (e) => {
            if (!e.target.classList.contains('active')) {
                e.target.style.transform = 'scale(1.2)';
            }
        });

        marker.addEventListener('mouseleave', (e) => {
            if (!e.target.classList.contains('active')) {
                e.target.style.transform = 'scale(1)';
            }
        });
    });

    // Initialize with first region
    switchRegion('kandy');
});

// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const cartItems = document.querySelectorAll('.cart-item');
    
    cartItems.forEach(item => {
        const productId = item.dataset.productId;
        const decreaseBtn = item.querySelector('.decrease');
        const increaseBtn = item.querySelector('.increase');
        const quantitySpan = item.querySelector('.quantity');
        const removeBtn = item.querySelector('.btn-remove');
        
        // Increase quantity
        increaseBtn?.addEventListener('click', () => {
            updateQuantity(productId, 'increase');
        });
        
        // Decrease quantity
        decreaseBtn?.addEventListener('click', () => {
            if (parseInt(quantitySpan.textContent) > 1) {
                updateQuantity(productId, 'decrease');
            }
        });
        
        // Remove item
        removeBtn?.addEventListener('click', () => {
            removeFromCart(productId);
        });
    });
});

// Update quantity function
async function updateQuantity(productId, action) {
    try {
        const response = await fetch(`${URLROOT}/shop/updateCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: action === 'increase' ? 1 : -1
            })
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload(); // Refresh to update totals
        }
    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

// Remove from cart function
async function removeFromCart(productId) {
    if (!confirm('Are you sure you want to remove this item?')) return;
    
    try {
        const response = await fetch(`${URLROOT}/shop/removeFromCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: productId
            })
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error removing item:', error);
    }
}

// Example AJAX request with the proper header
async function makeAjaxRequest(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' // This header is important
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    } catch (error) {
        console.error('AJAX Request failed:', error);
        throw error;
    }
}

// Example usage:
async function updateCart(productId, quantity) {
    try {
        const result = await makeAjaxRequest(`${URLROOT}/shop/updateCart`, {
            product_id: productId,
            quantity: quantity
        });
        
        if (result.success) {
            // Handle success
            location.reload();
        } else {
            // Handle error
            alert(result.message || 'Failed to update cart');
        }
    } catch (error) {
        alert('An error occurred while updating the cart');
    }
}

// Checkout form handling
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const placeOrderBtn = document.querySelector('.btn-place-order');

    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!checkoutForm.checkValidity()) {
                checkoutForm.reportValidity();
                return;
            }

            // Get selected payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            // Collect form data
            const formData = new FormData(checkoutForm);
            formData.append('payment_method', paymentMethod);

            // Process order
            processOrder(Object.fromEntries(formData));
        });
    }

    // Handle payment method change
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            const paymentInfo = document.querySelectorAll('.payment-info');
            paymentInfo.forEach(info => {
                info.style.display = 'none';
            });
            
            if (this.value === 'bank') {
                this.closest('.payment-method').querySelector('.payment-info').style.display = 'block';
            }
        });
    });
});

async function processOrder(orderData) {
    try {
        const response = await fetch(`${URLROOT}/shop/processOrder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();
        
        if (result.success) {
            window.location.href = `${URLROOT}/shop/tracking/${result.order_id}`;
        } else {
            alert(result.message || 'Failed to process order. Please try again.');
        }
    } catch (error) {
        console.error('Order processing error:', error);
        alert('An error occurred while processing your order. Please try again.');
    }
}

async function removeFromCart(event) {
    event.preventDefault();
    
    // Prevent the event from triggering on the icon instead of the button
    const button = event.target.closest('.btn-remove');
    if (!button) return;
    
    // Find the closest cart-item parent element
    const cartItem = button.closest('.cart-item');
    if (!cartItem) {
        console.error('Could not find cart item element');
        return;
    }

    const productId = cartItem.dataset.productId;
    if (!productId) {
        console.error('No product ID found');
        return;
    }

    console.log('Attempting to remove product:', productId); // Debug log

    try {
        const result = await Swal.fire({
            title: 'Remove Item',
            text: 'Are you sure you want to remove this item from your cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel'
        });

        if (!result.isConfirmed) return;

        console.log('Making AJAX request to:', `${URLROOT}/shop/removeFromCart`); // Debug log

        const response = await axios.post(`${URLROOT}/shop/removeFromCart`, {
            product_id: productId
        });

        console.log('Server response:', response.data); // Debug log

        if (response.data.success) {
            // Remove the item from DOM with animation
            cartItem.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                cartItem.remove();
                
                // Update totals
                if (response.data.totals) {
                    updateCartTotals(response.data.totals);
                }
                
                // If cart is empty, refresh the page to show empty cart message
                if (response.data.totals && response.data.totals.items_count === 0) {
                    window.location.reload();
                }
            }, 300);

            Swal.fire({
                title: 'Success!',
                text: 'Item removed from cart',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            throw new Error(response.data.message || 'Failed to remove item');
        }
    } catch (error) {
        console.error('Error details:', error); // Debug log
        Swal.fire({
            title: 'Error!',
            text: error.response?.data?.message || error.message || 'Something went wrong',
            icon: 'error'
        });
    }
}

function updateCartTotals(totals) {
    const subtotalElement = document.querySelector('.summary-row .subtotal');
    const totalElement = document.querySelector('.summary-row.total .total-amount');
    
    if (subtotalElement) {
        subtotalElement.textContent = `LKR ${formatNumber(totals.subtotal)}`;
    }
    
    if (totalElement) {
        const shipping = 500; // Your fixed shipping cost
        const tax = totals.subtotal * 0.10; // 10% tax
        const grandTotal = totals.subtotal + shipping + tax;
        totalElement.textContent = `LKR ${formatNumber(grandTotal)}`;
    }
}

function formatNumber(number) {
    return number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Make sure event listeners are added when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.btn-remove');
    console.log('Found remove buttons:', removeButtons.length); // Debug log
    
    removeButtons.forEach(button => {
        button.addEventListener('click', removeFromCart);
    });
});

// Add this near your other event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Find all remove buttons
    const removeButtons = document.querySelectorAll('.btn-remove');
    
    // Add click event listener to each button
    removeButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            if (!productId) {
                console.error('No product ID found');
                return;
            }

            // Show confirmation dialog
            const confirmed = await Swal.fire({
                title: 'Remove Item',
                text: 'Are you sure you want to remove this item from your cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel'
            });

            if (!confirmed.isConfirmed) return;

            try {
                const response = await axios.post(`${URLROOT}/shop/removeFromCart`, {
                    product_id: productId
                });

                if (response.data.success) {
                    // Remove the item from the page
                    const cartItem = this.closest('.cart-item');
                    cartItem.remove();
                    
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: 'Item removed from cart',
                        icon: 'success',
                        timer: 1500
                    });

                    // Reload page if cart is empty
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        window.location.reload();
                    }
                } else {
                    throw new Error(response.data.message || 'Failed to remove item');
                }
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Failed to remove item',
                    icon: 'error'
                });
            }
        });
    });
});

function incrementQuantity(productId) {
    const input = document.getElementById(`qty-${productId}`);
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    
    if (currentValue < max) {
        input.value = currentValue + 1;
    }
}

function decrementQuantity(productId) {
    const input = document.getElementById(`qty-${productId}`);
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

function validateQuantity(input, maxQuantity) {
    let value = parseInt(input.value);
    
    if (isNaN(value) || value < 1) {
        input.value = 1;
    } else if (value > maxQuantity) {
        input.value = maxQuantity;
    }
} 