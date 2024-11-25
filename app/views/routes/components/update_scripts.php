<script>
// Define URLROOT for JavaScript
const URLROOT = '<?php echo URLROOT; ?>';

let map, markers = [];
let selectedSuppliers = [];
let vehicleCapacity = 0;
let usedCapacity = 0;

// Add marker function
function addMarker(location, label, title) {
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        label: label,
        title: title
    });
    markers.push(marker);
    return marker;
}

// Clear markers function
function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

// Initialize map
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

// Update selected suppliers list
function updateSelectedSuppliersList() {
    const selectedSuppliersList = document.getElementById('selectedSuppliersList');
    if (!selectedSuppliersList) return;

    if (selectedSuppliers.length === 0) {
        selectedSuppliersList.innerHTML = '<div class="empty-message">No suppliers selected</div>';
        return;
    }

    selectedSuppliersList.innerHTML = selectedSuppliers.map((supplier, index) => `
        <div class="selected-supplier">
            <div class="supplier-info">
                <span class="supplier-name">Supplier #${supplier.id}</span>
                <div class="supplier-details">
                    <span>Collections: ${supplier.collections}</span>
                    <span>Avg: ${supplier.avg_collection} kg</span>
                </div>
            </div>
            <button type="button" class="remove-supplier-btn" onclick="removeSupplier(${index})">
                <i class='bx bx-x'></i>
            </button>
        </div>
    `).join('');
}

// Update map with suppliers
function updateMap() {
    clearMarkers();
    
    // Add factory marker
    const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };
    addMarker(factoryLocation, 'F', 'Factory Location');
    
    // Add supplier markers
    selectedSuppliers.forEach((supplier, index) => {
        if (supplier.location) {
            addMarker(
                supplier.location,
                (index + 1).toString(),
                `Supplier #${supplier.id}`
            );
        }
    });
}

// Add supplier function
function addSupplier(button) {
    const supplierId = parseInt(button.getAttribute('data-id'));
    const lat = parseFloat(button.getAttribute('data-lat'));
    const lng = parseFloat(button.getAttribute('data-lng'));
    
    // Get supplier details from allSuppliers array
    const allSuppliers = <?php echo json_encode($data['suppliers']); ?>;
    const supplierData = allSuppliers.find(s => parseInt(s.supplier_id) === supplierId);
    
    if (supplierData) {
        selectedSuppliers.push({
            id: supplierId,
            location: { lat, lng },
            collections: supplierData.number_of_collections || 0,
            avg_collection: supplierData.avg_collection || 0,
            stop_order: selectedSuppliers.length + 1
        });
        
        // Update UI
        updateSelectedSuppliersList();
        updateMap();
        updateCapacityDisplay();
        
        // Update unallocated suppliers list
        const currentSelectedIds = selectedSuppliers.map(s => s.id);
        updateUnallocatedSuppliersList(currentSelectedIds);
    }
}

// Remove supplier function
function removeSupplier(index) {
    // Get the supplier before removing it
    const removedSupplier = selectedSuppliers[index];
    
    // Remove from selected suppliers
    selectedSuppliers.splice(index, 1);
    
    // Update stop_order for remaining suppliers
    selectedSuppliers.forEach((supplier, idx) => {
        supplier.stop_order = idx + 1;
    });
    
    // Update UI
    updateSelectedSuppliersList();
    updateMap();
    updateCapacityDisplay();
    
    // Update unallocated suppliers list to include the removed supplier
    const currentSelectedIds = selectedSuppliers.map(s => s.id);
    updateUnallocatedSuppliersList(currentSelectedIds);
}

// Add route selection handling
document.addEventListener('DOMContentLoaded', function() {
    const routeSelect = document.getElementById('routeSelect');
    
    routeSelect.addEventListener('change', function() {
        const routeId = this.value;
        if (routeId) {
            fetch(`${URLROOT}/vehiclemanager/getRouteSuppliers/${routeId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Route Data:', data);
                    if (data.success) {
                        // 1. Update Route Name
                        document.getElementById('routeName').value = data.data.route.name;
                        
                        // 2. Set Vehicle Selection
                        const vehicleSelect = document.getElementById('vehicle');
                        vehicleSelect.value = data.data.route.vehicle_id;
                        
                        // Update capacity from selected vehicle
                        const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                        if (selectedOption) {
                            vehicleCapacity = parseFloat(selectedOption.getAttribute('data-capacity'));
                        }
                        
                        // 3. Clear and update suppliers
                        selectedSuppliers = [];
                        clearMarkers();
                        
                        // Add factory marker
                        const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };
                        addMarker(factoryLocation, 'F', 'Factory Location');
                        
                        // Add suppliers in correct order
                        data.data.suppliers
                            .sort((a, b) => parseInt(a.stop_order) - parseInt(b.stop_order))
                            .forEach(supplier => {
                                selectedSuppliers.push({
                                    id: supplier.id,
                                    location: {
                                        lat: parseFloat(supplier.location.lat),
                                        lng: parseFloat(supplier.location.lng)
                                    },
                                    collections: supplier.number_of_collections,
                                    avg_collection: supplier.avg_collection,
                                    stop_order: supplier.stop_order
                                });
                                
                                // Add marker with stop_order as label
                                addMarker(
                                    {
                                        lat: parseFloat(supplier.location.lat),
                                        lng: parseFloat(supplier.location.lng)
                                    },
                                    supplier.stop_order.toString(),
                                    `Supplier #${supplier.id}`
                                );
                            });
                        
                        // 4. Update UI
                        updateSelectedSuppliersList();
                        updateCapacityDisplay();
                        
                        // 5. Update unallocated suppliers list
                        updateUnallocatedSuppliersList(selectedSuppliers.map(s => s.id));
                        
                        document.getElementById('updateRoute').disabled = false;
                    }
                });
        }
    });
});

// Update unallocated suppliers list function
function updateUnallocatedSuppliersList(selectedSupplierIds) {
    const allSuppliers = <?php echo json_encode($data['suppliers']); ?>;
    const suppliersList = document.querySelector('.supplier-list');
    
    if (suppliersList) {
        suppliersList.innerHTML = allSuppliers
            .filter(supplier => !selectedSupplierIds.includes(parseInt(supplier.supplier_id)))
            .map(supplier => `
                <div class="supplier-item">
                    <div class="supplier-info">
                        <span class="supplier-name">Supplier #${supplier.supplier_id}</span>
                        <div class="supplier-details">
                            <span>Collections: ${supplier.number_of_collections || 0}</span>
                            <span>Avg: ${supplier.avg_collection || 0} kg</span>
                        </div>
                    </div>
                    <button type="button" 
                            class="add-supplier-btn" 
                            data-id="${supplier.supplier_id}"
                            data-lat="${supplier.latitude}"
                            data-lng="${supplier.longitude}"
                            onclick="addSupplier(this)">
                        <i class='bx bx-plus'></i>
                    </button>
                </div>
            `).join('');
    }
}

// Update capacity display
function updateCapacityDisplay() {
    const totalCapacityElement = document.getElementById('totalCapacity');
    const usedCapacityElement = document.getElementById('usedCapacity');
    const remainingCapacityElement = document.getElementById('remainingCapacity');
    const capacityFill = document.getElementById('capacityFill');
    
    if (totalCapacityElement) totalCapacityElement.textContent = vehicleCapacity;
    if (usedCapacityElement) usedCapacityElement.textContent = usedCapacity;
    if (remainingCapacityElement) remainingCapacityElement.textContent = vehicleCapacity - usedCapacity;
    
    if (capacityFill) {
        const percentage = (usedCapacity / vehicleCapacity) * 100;
        capacityFill.style.width = `${percentage}%`;
    }
}
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap">
</script>