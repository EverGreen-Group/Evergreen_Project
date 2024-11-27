async function addToCart(productId) {
    try {
        // Check if user is logged in (you can set this in your PHP view)
        if (!LOGGED_IN) {
            Swal.fire({
                icon: 'warning',
                title: 'Please Log In',
                text: 'You need to be logged in to add items to cart',
                showConfirmButton: true,
                confirmButtonText: 'Log In',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${URLROOT}/users/login`;
                }
            });
            return;
        }

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