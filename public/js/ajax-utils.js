// Configure Axios defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add CSRF token if you're using it
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// Global AJAX utility functions
const AjaxUtils = {
    // GET request
    async get(url, params = {}) {
        try {
            const response = await axios.get(url, { params });
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    },

    // POST request
    async post(url, data = {}) {
        try {
            const response = await axios.post(url, data);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    },

    // PUT request
    async put(url, data = {}) {
        try {
            const response = await axios.put(url, data);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    },

    // DELETE request
    async delete(url) {
        try {
            const response = await axios.delete(url);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    },

    // Error handler
    handleError(error) {
        console.error('AJAX Error:', error);
        
        let errorMessage = 'An error occurred';
        
        if (error.response) {
            // Server responded with error
            errorMessage = error.response.data.message || 'Server error occurred';
        } else if (error.request) {
            // Request made but no response
            errorMessage = 'No response from server';
        } else {
            // Request setup error
            errorMessage = error.message;
        }

        // Show error using SweetAlert2
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            confirmButtonColor: '#3085d6'
        });
    },

    // Success message
    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            confirmButtonColor: '#3085d6'
        });
    },

    // Confirmation dialog
    async confirm(message) {
        const result = await Swal.fire({
            icon: 'question',
            title: 'Confirm',
            text: message,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        });
        return result.isConfirmed;
    }
};

// DataTable utility
const DataTableUtils = {
    defaultConfig: {
        responsive: true,
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    },

    // Initialize DataTable with custom config
    init(selector, config = {}) {
        return $(selector).DataTable({
            ...this.defaultConfig,
            ...config
        });
    }
}; 