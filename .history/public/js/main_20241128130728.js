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



// Example AJAX function
async function updateCart(productId, quantity) {
    try {
        const response = await fetch(`${URLROOT}/shop/updateCart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'  // This is the important header
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
} 