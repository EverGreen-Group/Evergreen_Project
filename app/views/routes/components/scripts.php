<script>
// Define URLROOT for JavaScript
const URLROOT = '<?php echo URLROOT; ?>';

let map, markers = [];
let selectedSuppliers = [];
let vehicleCapacity = 0;
let usedCapacity = 0;

function initMap() {
    const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };
    
    map = new google.maps.Map(document.getElementById("map"), {
        center: factoryLocation,
        zoom: 14,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    
    // Add factory marker
    addMarker(factoryLocation, 'F', 'Factory Location');
}

function addMarker(position, label, title) {
    const marker = new google.maps.Marker({
        position: position,
        map: map,
        label: {
            text: label,
            color: 'white'
        },
        title: title
    });
    markers.push(marker);
    return marker;
}

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

function updateMap() {
    clearMarkers();
    
    // Add factory marker
    const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };
    addMarker(factoryLocation, 'F', 'Factory Location');
    
    // Add supplier markers in order
    selectedSuppliers.forEach((supplier, index) => {
        addMarker(
            { lat: parseFloat(supplier.lat), lng: parseFloat(supplier.lng) },
            (index + 1).toString(),
            `Stop ${index + 1}: Supplier #${supplier.id}`
        );
    });
}

function updateSelectedSuppliersList() {
    const list = document.getElementById('selectedSuppliersList');
    list.innerHTML = '';
    
    if (selectedSuppliers.length === 0) {
        list.innerHTML = '<div class="empty-message">No suppliers selected</div>';
        return;
    }
    
    selectedSuppliers.forEach((supplier, index) => {
        const item = document.createElement('div');
        item.className = 'selected-supplier-item';
        item.innerHTML = `
            <div class="supplier-info">
                <span class="order-number">${index + 1}</span>
                <span class="supplier-name">Supplier #${supplier.id}</span>
                <span class="supplier-stats">
                    Collections: ${supplier.collections} | Avg: ${supplier.avg_collection} kg
                </span>
            </div>
            <button type="button" class="remove-supplier-btn" onclick="removeSupplier(${index})">
                <i class='bx bx-x'></i>
            </button>
        `;
        list.appendChild(item);
    });
}

function addSupplier(supplierId) {
    // Check if supplier is already selected
    if (selectedSuppliers.some(supplier => supplier.id === supplierId)) {
        alert('This supplier is already added to the route!');
        return;
    }

    const button = document.querySelector(`[data-id="${supplierId}"]`);
    const supplierItem = button.closest('.supplier-item');
    const avgCollection = parseFloat(supplierItem.querySelector('[data-avg-collection]').dataset.avgCollection);
    
    // Check if adding this supplier would exceed vehicle capacity
    if (usedCapacity + avgCollection > vehicleCapacity) {
        alert('Adding this supplier would exceed vehicle capacity!');
        return;
    }
    
    // Add supplier to selected list
    const collections = supplierItem.querySelector('[data-collections]').dataset.collections;
    const lat = button.dataset.lat;
    const lng = button.dataset.lng;
    
    selectedSuppliers.push({
        id: supplierId,
        collections: collections,
        avg_collection: avgCollection,
        lat: lat,
        lng: lng
    });
    
    // Update capacity
    usedCapacity += avgCollection;
    updateCapacityDisplay();
    
    updateSelectedSuppliersList();
    updateMap();
    
    // Hide the supplier from unallocated list
    supplierItem.style.display = 'none';
    updateSubmitButton();
}

function removeSupplier(index) {
    const supplier = selectedSuppliers[index];
    const avgCollection = parseFloat(supplier.avg_collection);
    
    selectedSuppliers.splice(index, 1);
    usedCapacity -= avgCollection;
    updateCapacityDisplay();
    
    updateSelectedSuppliersList();
    updateMap();
    
    // Show the supplier back in unallocated list
    const supplierItem = document.querySelector(`[data-id="${supplier.id}"]`).closest('.supplier-item');
    supplierItem.style.display = 'flex';  // or 'block' depending on your styling
    supplierItem.classList.remove('selected');
    updateSubmitButton();
}

// Add event listeners
document.querySelectorAll('.add-supplier-btn').forEach(button => {
    button.addEventListener('click', () => addSupplier(button.dataset.id));
});

// Update the vehicle change event listener
document.getElementById('vehicle').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (this.value) {
        vehicleCapacity = parseFloat(selectedOption.dataset.capacity);
        document.getElementById('totalCapacity').textContent = vehicleCapacity;
        document.getElementById('capacityTracker').style.display = 'block';
        updateCapacityDisplay();
    } else {
        document.getElementById('capacityTracker').style.display = 'none';
    }
    
    // Enable/disable submit button based on selections
    updateSubmitButton();
});

function updateCapacityDisplay() {
    const usedCapacityElement = document.getElementById('usedCapacity');
    const remainingCapacityElement = document.getElementById('remainingCapacity');
    const capacityFill = document.getElementById('capacityFill');
    
    usedCapacityElement.textContent = usedCapacity.toFixed(2);
    const remaining = vehicleCapacity - usedCapacity;
    remainingCapacityElement.textContent = remaining.toFixed(2);
    
    // Update capacity bar
    const percentageUsed = (usedCapacity / vehicleCapacity) * 100;
    capacityFill.style.width = `${percentageUsed}%`;
    
    // Update color based on capacity usage
    if (percentageUsed >= 90) {
        capacityFill.style.backgroundColor = '#f44336'; // Red
    } else if (percentageUsed >= 70) {
        capacityFill.style.backgroundColor = '#ff9800'; // Orange
    } else {
        capacityFill.style.backgroundColor = '#4CAF50'; // Green
    }
}

// Move updateSubmitButton to global scope
function updateSubmitButton() {
    const submitButton = document.getElementById('submitRoute');
    const routeName = document.getElementById('routeName').value.trim();
    const vehicleSelected = document.getElementById('vehicle').value !== '';
    const hasSuppliers = selectedSuppliers.length > 0;
    
    submitButton.disabled = !(routeName && vehicleSelected && hasSuppliers);
}

// Add this to handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const submitButton = document.getElementById('submitRoute');
    
    submitButton.addEventListener('click', function() {
        const routeName = document.getElementById('routeName').value.trim();
        const vehicleId = document.getElementById('vehicle').value;

        if (!routeName) {
            alert('Please enter a route name');
            return;
        }

        if (!vehicleId) {
            alert('Please select a vehicle');
            return;
        }

        if (!selectedSuppliers.length) {
            alert('Please select at least one supplier');
            return;
        }

        // Prepare the data
        const routeData = {
            route_name: routeName,
            vehicle_id: vehicleId,
            status: 'Active',
            suppliers: selectedSuppliers.map((supplier, index) => ({
                supplier_id: supplier.id,
                stop_order: index + 1
            }))
        };

        // Send to server using POST
        fetch(`${URLROOT}/vehiclemanager/createRoute`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(routeData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Route created successfully!');
                window.location.href = `${URLROOT}/vehiclemanager/routes`;
            } else {
                alert(data.message || 'Failed to create route');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the route');
        });
    });

    // Add event listeners for form fields
    document.getElementById('routeName').addEventListener('input', updateSubmitButton);
    document.getElementById('vehicle').addEventListener('change', updateSubmitButton);
});

// Add this to your existing JavaScript
document.getElementById('routeDay').addEventListener('change', function() {
    const selectedDay = this.value;
    if (selectedDay) {
        // Clear current suppliers
        clearSuppliers(); // You'll need to implement this function
        
        // Fetch suppliers for selected day
        fetch(`<?php echo URLROOT; ?>/routes/getUnallocatedSuppliersForDay?day=${selectedDay}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update your suppliers list and map
                    updateSuppliersList(data.suppliers); // You'll need to implement this function
                    updateMapMarkers(data.suppliers);    // You'll need to implement this function
                } else {
                    console.error('Error fetching suppliers:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap">
</script>