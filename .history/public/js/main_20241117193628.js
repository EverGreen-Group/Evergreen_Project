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