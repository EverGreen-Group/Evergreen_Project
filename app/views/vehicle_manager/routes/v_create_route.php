<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/driver_manager/driver.js"></script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Route Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="action-buttons">
        <a href="#" class="btn btn-primary" style="margin-right:20px;">
            <i class='bx bx-brightness'></i>
            Generate Route For Unalloacted Suppliers
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Create Route</h3>
                <i class='bx bx-search'></i>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/route/createRoute" method="POST">
                    <div class="filter-group">
                        <label for="route-name">Route Name:</label>
                        <input type="text" id="route-name" name="route_name" placeholder="Enter route name" required>
                    </div>
                    <div class="filter-group">
                        <label for="route-day">Select Day:</label>
                        <select id="route-day" name="route_day" required onchange="fetchAvailableVehicles(this.value)">
                            <option value="">-- Select Day --</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="select-vehicle">Select Vehicle:</label>
                        <select id="select-vehicle" name="vehicle_id" required>
                            <option value="">-- Select Vehicle --</option>
                            <!-- Vehicle options will be populated here -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Route</button>
                </form>
            </div>
        </div>
    </div>


    <div class="table-data table-container">
    <!-- Left table: Unallocated Suppliers -->
    <div class="order">
    <div class="head">
        <h3>Unallocated Suppliers</h3>
        <div class="filter-container filter-group" style="max-width:100px;">
            <select id="day-filter" name="day_filter" class="filter-select">
                <option value="">All Days</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>
        <i class='bx bx-search'></i>
    </div>
    <table id="suppliers-table">
        <thead>
            <tr>
                <th>Supplier ID</th>
                <th>Name</th>
                <th>Average Collection</th>
                <th>Preferred Day</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['unassignedSuppliersList'] as $supplier): ?>
                <tr>
                    <td><?php echo htmlspecialchars($supplier->supplier_id); ?></td>
                    <td><?php echo htmlspecialchars($supplier->full_name); ?></td>
                    <td><?php echo htmlspecialchars($supplier->average_collection); ?></td>
                    <td>
                        <span class="status completed">
                            <?php echo htmlspecialchars($supplier->preferred_day); ?>
                        </span>
                    </td>
                    <td>
                        <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($supplier->latitude) . ',' . htmlspecialchars($supplier->longitude); ?>" target="_blank" class="location-link">
                            <i class="bx bx-map" style="font-size: 24px; color: #007bff;"></i> <!-- Box icon for location -->
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <!-- Right table: Routes - NO SUPPLIER DETAILS HERE -->
    <div class="order">
        <div class="head">
            <h3>Supplier Allocation</h3>
            <div class="filter-container">
                <!-- Filter options can go here -->
            </div>
            <i class='bx bx-search'></i>
        </div>

        <!-- Canvas for Chart.js -->
        <canvas id="supplierChart" style="max-width: 600px; max-height: 300px; margin: 20px auto;"></canvas>
    </div>
  </div>

    <div class="table-data">

        <div class="order">
            <div class="head">
                <h3>Routes</h3>
                <div class="filter-container filter-group" style="max-width:100px;">
                    <select id="day-filter" name="day_filter" class="filter-select">
                        <option value="">All Days</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <i class='bx bx-search'></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Name</th>
                        <th>Suppliers</th>
                        <th>Day</th>
                        <th>Remaining Capacity</th>
                        <th>Vehicle Assigned</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allRoutes as $route): ?>
                        <tr class="route-row" data-route-id="<?php echo htmlspecialchars($route->route_id); ?>">
                            <td><?php echo htmlspecialchars($route->route_id); ?></td>
                            <td><?php echo htmlspecialchars($route->route_name); ?></td>
                            <td><?php echo htmlspecialchars($route->number_of_suppliers); ?></td>
                            <td><?php echo htmlspecialchars($route->day); ?></td>
                            <td><?php echo htmlspecialchars($route->remaining_capacity); ?></td>
                            <td><?php echo htmlspecialchars($route->vehicle_id); ?></td>
                            <td>
                                <span class="status <?php echo htmlspecialchars($route->status === 'Active' ? 'completed' : 'error'); ?>">
                                    <?php echo htmlspecialchars($route->status); ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <!-- Manage button with icon only -->
                                    <a 
                                        href="<?php echo URLROOT; ?>/route/manageRoute/<?php echo $route->route_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bx-cog' style="font-size: 24px; color:green;"></i> <!-- Boxicon for settings -->
                                    </a>
                                    
                                    <!-- Delete button with icon only -->
                                    <form action="<?php echo URLROOT; ?>/route/deleteRoute/" method="POST" style="margin: 0;"> 
                                        <input type="hidden" name="route_id" value="<?php echo $route->route_id; ?>">
                                        <button type="submit" class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            onclick="return confirm('Are you sure you want to delete this route?');">
                                            <i class='bx bx-trash' style="font-size: 24px; color:red;"></i> <!-- Boxicon for trash -->
                                        </button>
                                    </form>

                                    <!-- Lock/Unlock button with icon only -->
                                    <form action="<?php echo URLROOT; ?>/route/toggleLock" method="POST" style="margin: 0;"> 
                                        <input type="hidden" name="route_id" value="<?php echo $route->route_id; ?>">
                                        <button type="submit" class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            onclick="return confirm('Are you sure you want to toggle the lock for this route?');">
                                            <i class='bx <?= $route->is_locked ? 'bxs-lock' : 'bxs-lock-open' ?>' style="font-size: 24px; color: <?= $route->is_locked ? 'red' : 'green' ?>;"></i> <!-- Boxicon for lock -->
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>

</div>
</main>


<script>
    const suppliers = <?php echo json_encode($unallocatedSuppliers); ?>;
    const URLROOT = '<?php echo URLROOT; ?>';
    const routes = <?php echo json_encode($data['allRoutes']); ?>;
    const factoryLocation = { 
        lat: <?php echo M_Route::FACTORY_LAT; ?>, 
        lng: <?php echo M_Route::FACTORY_LONG; ?> 
    };


</script>

<script src="<?php echo URLROOT; ?>/public/js/route-page.js"></script>

<?php 
// Add stylesheet link
echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/route-management.css">';

// Add script links
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
require APPROOT . '/views/inc/components/footer.php'; 
?>


<script>
    // Use the supplier data passed from the controller
    const days = ['MON', 'TUE', 'WED', 'THUR', 'FRI', 'SAT', 'SUN'];
    const supplier = [
        <?php echo implode(',', $data['supplierData']); ?>
    ]; // Use PHP to output the supplier counts
    console.log('Supplier Data:', supplier); 

    const ctx = document.getElementById('supplierChart').getContext('2d');
    const supplierChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                data: supplier,
                backgroundColor: '#007FFC',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 0.5,
                barThickness: 14
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        color: '#333',
                        font: {
                            size: 10,
                        }
                    },
                    ticks: {
                        color: '#333', // Color for y-axis ticks
                    }
                },
                x: {
                    title: {
                        display: true,
                        color: '#333',
                        font: {
                            size: 14,
                        }
                    },
                    ticks: {
                        color: '#333', // Color for x-axis ticks
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: false // Hide the legend
                },
                title: {
                    display: false // Hide the title
                }
            }
        }
    });
</script>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<script>
function fetchAvailableVehicles(day) {
    const vehicleSelect = document.getElementById('select-vehicle');
    vehicleSelect.innerHTML = '<option value="">-- Select Vehicle --</option>'; // Reset options

    if (day) {
        fetch(`<?php echo URLROOT; ?>/route/getAvailableVehicles/${day}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    data.data.forEach(vehicle => {
                        const option = document.createElement('option');
                        option.value = vehicle.vehicle_id; // Assuming vehicle_id is the correct field
                        option.textContent = vehicle.license_plate; // Assuming license_plate is the field to display
                        vehicleSelect.appendChild(option);
                    });
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error fetching vehicles:', error));
    }
}
</script>