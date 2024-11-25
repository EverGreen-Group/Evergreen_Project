const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});


// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})


if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})


const switchMode = document.getElementById('switch-mode');

// Function to set theme
function setTheme(isDark) {
    if (isDark) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }
    localStorage.setItem('darkMode', isDark);
    switchMode.checked = isDark;
}


// Load saved theme preference
const savedDarkMode = localStorage.getItem('darkMode') === 'true';
setTheme(savedDarkMode);

switchMode.addEventListener('change', function () {
    setTheme(this.checked);
});


// Get the current page from the window location
const currentPage = window.location.pathname.split("/").pop().toLowerCase(); // Get the file name in lowercase

console.log("Current Page: ", currentPage); // Log the current page for debugging

allSideMenu.forEach(item => {
	const href = item.getAttribute('href').toLowerCase(); // Ensure href is lowercase
	console.log("Link Href: ", href); // Log each link href for debugging

	// If the href matches the current page, add the active class
	if(href === currentPage || href.includes(currentPage)) {
		item.parentElement.classList.add('active');
		console.log("Active Link Found: ", href); // Log which link becomes active
	}
});


function submitmessage(event) {
    event.preventDefault(); 
	
    // Get the form data 
    const form = document.querySelector('.complaint-form');
    const formData = new FormData(form);

    // Check if all required fields are filled (basic validation)
    let isFormValid = true;
    formData.forEach((value, key) => {
        if (!value.trim()) {
            isFormValid = false;
        }
    });

    if (isFormValid) {
        alert("Submit Successful");
    } else {
        alert("Unsuccessful: Please fill in all required fields.");
    }
    setTimeout(() => {
        window.location.reload();
    }, 2000);
}


function refreshPage() {
    document.querySelector('.complaint-form').reset();
}


function updatePricePerUnit() {
    const typeSelect = document.getElementById('type_id');
    const pricePerUnitInput = document.getElementById('price_per_unit');
    const totalPriceInput = document.getElementById('total_price');
    const totalAmountInput = document.getElementById('total_amount');

    const selectedType = typeSelect.value;
    // Use the global variable
    const type = window.FERTILIZER_TYPES.find(t => t.type_id == selectedType);

    if (type) {
        const defaultUnit = 'kg';
        pricePerUnitInput.value = type[`price_${defaultUnit}`];

        // Calculate total price if total amount is filled
        const totalAmount = totalAmountInput.value;
        if (totalAmount) {
            totalPriceInput.value = (totalAmount * pricePerUnitInput.value).toFixed(2);
        }
    }
}

// Function to calculate price based on selected options
function calculatePrices() {
    const typeSelect = document.getElementById('type_id');
    const unitSelect = document.getElementById('unit');
    const totalAmountInput = document.getElementById('total_amount');
    const pricePerUnitInput = document.getElementById('price_per_unit');
    const totalPriceInput = document.getElementById('total_price');
    
    if (typeSelect.value && unitSelect.value && totalAmountInput.value) {
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        let unitPrice = 0;
        
        switch(unitSelect.value) {
            case 'kg':
                unitPrice = parseFloat(selectedOption.dataset.unitPriceKg);
                break;
            case 'packs':
                unitPrice = parseFloat(selectedOption.dataset.packPrice);
                break;
            case 'box':
                unitPrice = parseFloat(selectedOption.dataset.boxPrice);
                break;
        }
        
        pricePerUnitInput.value = unitPrice;
        totalPriceInput.value = (unitPrice * parseFloat(totalAmountInput.value)).toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('fertilizerForm');
    
    if(form) {
        // Add event listeners for price calculations
        document.getElementById('type_id').addEventListener('change', calculatePrices);
        document.getElementById('unit').addEventListener('change', calculatePrices);
        document.getElementById('total_amount').addEventListener('input', calculatePrices);
        
        // Initial price calculation
        calculatePrices();
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate form
            const typeId = document.getElementById('type_id').value;
            const unit = document.getElementById('unit').value;
            const totalAmount = document.getElementById('total_amount').value;
            
            if (!typeId || !unit || !totalAmount) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Calculate final prices before submission
            calculatePrices();
            
            try {
                // Get selected fertilizer name
                const typeSelect = document.getElementById('type_id');
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                const fertilizerName = selectedOption.text;
                
                // Create FormData object
                const formData = new FormData(form);
                formData.append('fertilizer_name', fertilizerName);
                
                // Convert FormData to URLSearchParams
                const params = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    params.append(key, value);
                }
                
                // Submit form
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params.toString()
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Request updated successfully!');
                    window.location.href = URLROOT + '/supplier/requestFertilizer';
                } else {
                    alert(result.message || 'Failed to update request');
                }
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the request');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });
    }
});




// Get all delete buttons when the page loads
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    // Add click event listener to each delete button
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            confirmDelete(orderId);
        });
    });
});


// Function to show confirmation dialog
function confirmDelete(orderId) {
    const modal = document.getElementById('deleteModal');
    if (!modal) {
        console.error('Delete modal not found');
        return;
    }
    
    // Show the modal
    modal.style.display = 'flex';
    
    // Store the order ID
    window.deleteOrderId = orderId;
    
    // Set up the confirm button event listener
    const confirmButton = document.getElementById('confirmDeleteBtn');
    if (confirmButton) {
        // Remove existing listeners to prevent duplicates
        const newConfirmButton = confirmButton.cloneNode(true);
        confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
        
        // Add new event listener
        newConfirmButton.addEventListener('click', function() {
            executeDelete(orderId);
        });
    }
};


// Function to close modal
function closeModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
    }
};


// Function to execute delete
function executeDelete(orderId) {
    if (!orderId) {
        console.error('No order ID provided');
        return;
    }
    
    // Create a POST request with CSRF token if needed
    const formData = new FormData();
    formData.append('_method', 'POST');

    fetch(`${URLROOT}/Supplier/deleteFertilizerRequest/${orderId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Order deleted successfully!');
            window.location.reload();
        } else {
            throw new Error(data.message || 'Delete failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete the order: ' + error.message);
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    })
    .finally(() => {
        closeModal();
    });
};


// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeModal();
    }
};


// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});









//PAYMENTS CHART
// Hardcoded data
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const income = [5000, 7000, 8000, 10000, 12000, 14000, 13000, 11000, 15000, 16000, 18000, 20000];
const cost = [3000, 4000, 5000, 6000, 700, 8000, 7500, 6000, 9000, 200, 12000, 13000];

const payconfig = {
  type: "bar",
  data: {
    labels: months,
    datasets: [
      {
        label: "Income",
        data: income,
        backgroundColor: "rgba(75, 192, 192, 0.6)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
      {
        label: "Cost",
        data: cost,
        backgroundColor: "rgba(255, 99, 132, 0.6)",
        borderColor: "rgba(255, 99, 132, 1)",
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: "top",
      },
      title: {
        display: true,
        text: "Income vs Cost for Orders (Yearly)",
      },
    },
    scales: {
      x: {
        beginAtZero: true,
      },
      y: {
        beginAtZero: true,
      },
    },
  },
};

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById("incomeCostChart").getContext("2d");
    new Chart(ctx, payconfig); 
});


document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('paymentAnalysisChart').getContext('2d');
    var paymentAnalysisChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['fertilizer', 'teapackets', 'income'],
            datasets: [{
                data: [120, 10, 200 ], 
                backgroundColor: [
                    'rgba(255, 99, 35, 0.8)',
                    'rgba(95, 162, 235, 0.8)',
                    'rgba(255, 10, 86, 0.8)'
                ],
                borderColor: [
                    'rgba(200, 200, 200, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Payments'
                }
            },
            scales: {
                x: {
                  beginAtZero: true,
                },
                y: {
                  beginAtZero: true,
                },
              },
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('comparePaymentsChart').getContext('2d');
    var comparePaymentsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['payments', 'income', 'profit', 'loss'],
            datasets: [{
                data: [4050, 5020, 930, 50 ], 
                backgroundColor: [
                    'rgba(45, 99, 35, 0.8)',
                    'rgba(95, 67, 87, 0.8)',
                    'rgba(180, 20, 180, 1)',
                    'rgba(93, 65, 89, 1)'
                ],
                borderColor: [
                    'rgba(200, 200, 200, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Payment vs Income'
                }
            },
            scales: {
                x: {
                  beginAtZero: true,
                },
                y: {
                  beginAtZero: true,
                },
              },
        }
    });
});









/* FERTILIZER ORDER LINE CHART */
const teaLeavesCollectionChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], 
        datasets: [{
            label: 'Tea Leaves Collections',
            data: [32, 210, 583, 156, 284, 515, 502, 389, 412, 479, 500, 0], // Example data 
            fill: false,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Tea Leaves Collections (Monthly)'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Amount'
                },
                beginAtZero: true
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('fertilizerOrdersChart').getContext('2d');
    new Chart(ctx, teaLeavesCollectionChart); 
});

/* FERTILIZER CHART */
var ctx = document.getElementById('fertilizerChart').getContext('2d');
var fertilizerChart = {
    type: 'line',
    data: {
        labels: ['June', 'July', 'August', 'September', 'October', 'November'],
        datasets: [{
            label: 'Requests',
            data: [120, 10, 200, 180, 220, 80], // Example data 
            fill: false,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Requests (Monthly)'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Number of Requests'
                },
                beginAtZero: true
            }
        }
    }
};
document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('fertilizerChart').getContext('2d');
        new Chart(ctx, fertilizerChart); 
});

//FERTILIZER REQUEST PIE CHART
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('fertilizerRequestChart').getContext('2d');
    var fertilizerRequestChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['June', 'July', 'August', 'September', 'October', 'November'],
            datasets: [{
                data: [120, 10, 200, 180, 220, 80 ], // Example data for tea orders
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Fertilizer Request History (Monthly Distribution)'
                }
            }
        }
    });
});







// UPDATE PRICE CALCULATIONS WHEN UNIT OR TYPE CHANGES
document.getElementById('type_id').addEventListener('change', updatePrice);
document.getElementById('unit').addEventListener('change', updatePrice);
document.getElementById('total_amount').addEventListener('input', updatePrice);

function updatePrice() {
    const typeSelect = document.getElementById('type_id');
    const unitSelect = document.getElementById('unit');
    const amountInput = document.getElementById('total_amount');
    const pricePerUnitInput = document.getElementById('price_per_unit');
    const totalPriceInput = document.getElementById('total_price');

    if (!typeSelect.value || !unitSelect.value || !amountInput.value) {
        return;
    }

    const selectedOption = typeSelect.options[typeSelect.selectedIndex];
    let pricePerUnit = 0;

    switch(unitSelect.value) {
        case 'kg':
            pricePerUnit = parseFloat(selectedOption.dataset.unitPriceKg);
            break;
        case 'packs':
            pricePerUnit = parseFloat(selectedOption.dataset.packPrice);
            break;
        case 'box':
            pricePerUnit = parseFloat(selectedOption.dataset.boxPrice);
            break;
    }

    pricePerUnitInput.value = pricePerUnit;
    totalPriceInput.value = (pricePerUnit * parseFloat(amountInput.value)).toFixed(2);
}
























































/* SUPPLEMENT MANAGER */
/* DASHBOARD 
const data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [{
        label: 'Tea Leaves Collected (kg)',
        data: [500, 540, 120, 750, 900, 500, 290, 600, 670, 750, 900, 0], // Match the labels
        backgroundColor: 'rgba(0, 198, 172, 0.712)',
        borderColor: 'rgba(0, 162, 141, 0.712)',
        borderWidth: 1,
    }]
};

const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { enabled: true },
        },
        scales: {
            x: { title: { display: true, text: 'Months' } },
            y: { title: { display: true, text: 'Amount (kg)' }, beginAtZero: true },
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('teaLeavesGraph').getContext('2d');
    new Chart(ctx, config); 
});*/

/*  LEAF SUPPLY 
const leafdata = {
    labels: ['sent', 'pending', 'confirmed', 'reported'],
    datasets: [{
        label: 'Tea Leaves Confirmations',
        data: [70, 48, 12, 1], 
        backgroundColor: ['#007664bc','#ecb500bc','#8d9f2dbc','#ff0800bc'],
        borderColor: 'rgba(0, 0, 0, 0.3)',
        borderWidth: 1,
    }]
};

const leafconfig = {
    type: 'bar',
    data: leafdata,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { enabled: true },
        },
        scales: {
            x: { title: { display: true, text: 'Confirmation Status' } },
            y: { title: { display: true, text: 'No of Suppliers' }, beginAtZero: true },
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('teaLeavesConfirmationGraph').getContext('2d');
    new Chart(ctx, leafconfig); 
});*/

/*
const pie_leafconfig = {
    type: 'pie',
    data: leafdata,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: { enabled: true },
        },
        scales: {
            x: { title: { display: true, text: 'Confirmation Status' } },
            y: { title: { display: true, text: 'No of Suppliers' }, beginAtZero: true },
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('teaLeavesConfirmationChart').getContext('2d');
    new Chart(ctx, pie_leafconfig); 
});*/
