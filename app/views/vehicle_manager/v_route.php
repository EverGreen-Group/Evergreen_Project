<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<script>
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/route-page.js"></script>

<!-- MAIN -->
<main>


  <!-- Route Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Route Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
        <a id="createRouteButton" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Create a Route
        </a>
    </div>



<ul class="route-box-info">
    <li>
        <i class='bx bxs-map'></i>
        <span class="text">
            <p>Total Routes</p>
            <h3><?php echo isset($data['totalRoutes']) ? (int)$data['totalRoutes'] : 0; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-check-circle'></i>
        <span class="text">
            <p>Total Active</p>
            <h3><?php echo isset($data['totalActive']) ? (int)$data['totalActive'] : 0; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-x-circle'></i>
        <span class="text">
            <p>Total Inactive</p>
            <h3><?php echo isset($data['totalInactive']) ? (int)$data['totalInactive'] : 0; ?></h3>
        </span>
    </li>
</ul>






  <!-- Modal Form for Creating or Editing a Route -->
  <div id="routeModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Route Details</h2>
      
      <form id="routeForm">
        <div class="modal-two-columns">
            <!-- Left Column - Form Controls -->
            <div class="left-column">
                <input type="hidden" id="routeId" name="routeId">

                <div class="form-group">
                    <label for="routeName">Route Name:</label>
                    <input type="text" id="routeName" name="routeName" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="daySelect">Day:</label>
                        <select id="daySelect" name="day">
                            <option value="" disabled selected>Select a day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="vehicleSelect">Vehicle:</label>
                        <select id="vehicleSelect" name="vehicle" required>
                            <option value="" disabled selected>Select a vehicle</option>
                            <?php foreach ($data['vehicles'] as $vehicle): ?>
                                <option value="<?php echo $vehicle->vehicle_id; ?>" 
                                        data-capacity="<?php echo $vehicle->capacity; ?>">
                                    <?php echo $vehicle->vehicle_number; ?> (<?php echo $vehicle->capacity; ?>kg)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="capacity-info">
                    <div class="capacity-item">
                        <span>Used Capacity:</span>
                        <span id="usedCapacity">0 kg</span>
                    </div>
                    <div class="capacity-item">
                        <span>Remaining Capacity:</span>
                        <span id="remainingCapacity">0 kg</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group supplier-select">
                        <label for="supplierSelect">Select Supplier:</label>
                        <select id="supplierSelect">
                            <option value="" disabled selected>Select a supplier</option>
                        </select>
                    </div>
                    <button type="button" id="addSupplierButton">Add Stop</button>
                </div>

                <h3>Route Stops:</h3>
                <ul id="stopList"></ul>


            </div>

            <!-- Right Column - Vehicle Info and Map -->
            <div class="right-column">
                <!-- Vehicle Info Section -->
                <div class="vehicle-info">
                    <div class="vehicle-image">
                        <img src="<?php echo URLROOT; ?>/public/uploads/vehicle_photos/default-vehicle.jpg" alt="Vehicle" id="vehicleImage">
                    </div>
                    <div class="vehicle-details">
                        <h3>Vehicle Details</h3>
                        <div class="detail-item">
                            <span class="label">Vehicle Number:</span>
                            <span class="value" id="vehicleNumberDisplay">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Capacity:</span>
                            <span class="value" id="vehicleCapacityDisplay">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Type:</span>
                            <span class="value" id="vehicleTypeDisplay">-</span>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div id="map"></div>
            </div>
        </div>

        <!-- Button at the bottom of modal -->
        <div class="modal-footer">
            <button type="submit" class="submit-btn" form="routeForm">Save Route</button>
        </div>
      </form>

      

    </div>
  </div>

<!-- Modal Form for Updating a Route -->
<div id="updateRouteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUpdateModal()">&times;</span>
        <h2 id="updateModalTitle">Update Route</h2>
        
        <form id="updateRouteForm">
            <div class="modal-two-columns">
                <!-- Left Column - Form Controls -->
                <div class="left-column">
                    <input type="hidden" id="updateRouteId" name="routeId">

                    <div class="form-group">
                        <label for="updateRouteName">Route Name:</label>
                        <input type="text" id="updateRouteName" name="routeName" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="updateDaySelect">Day:</label>
                            <select id="updateDaySelect" name="day">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="updateVehicleSelect">Vehicle:</label>
                            <select id="updateVehicleSelect" name="vehicle" required>
                                <option value="" disabled selected>Select a vehicle</option>
                                <?php foreach ($data['vehicles'] as $vehicle): ?>
                                    <option value="<?php echo $vehicle->vehicle_id; ?>" 
                                            data-capacity="<?php echo $vehicle->capacity; ?>">
                                        <?php echo $vehicle->vehicle_number; ?> (<?php echo $vehicle->capacity; ?>kg)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="updateStatus">Status:</label>
                            <select id="updateStatus" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="capacity-info">
                        <div class="capacity-item">
                            <span>Used Capacity:</span>
                            <span id="updateUsedCapacity">0 kg</span>
                        </div>
                        <div class="capacity-item">
                            <span>Remaining Capacity:</span>
                            <span id="updateRemainingCapacity">0 kg</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group supplier-select">
                            <label for="updateSupplierSelect">Select Supplier:</label>
                            <select id="updateSupplierSelect">
                                <option value="" disabled selected>Select a supplier</option>
                            </select>
                        </div>
                        <button type="button" id="AddSupplierButton">Add Stop</button>
                    </div>

                    <h3>Route Stops:</h3>
                    <ul id="updateStopList"></ul>
                </div>

                <!-- Right Column - Vehicle Info and Map -->
                <div class="right-column">
                    <!-- Vehicle Info Section -->
                    <div class="vehicle-info">
                        <div class="vehicle-image">
                            <img src="<?php echo URLROOT; ?>/public/uploads/vehicle_photos/default-vehicle.jpg" alt="Vehicle" id="vehicleImage">
                        </div>
                        <div class="vehicle-details">
                            <h3>Vehicle Details</h3>
                            <div class="detail-item">
                                <span class="label">Vehicle Number:</span>
                                <span class="value" id="vehicleNumberDisplay">-</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Capacity:</span>
                                <span class="value" id="vehicleCapacityDisplay">-</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Type:</span>
                                <span class="value" id="vehicleTypeDisplay">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div id="map"></div>
                </div>
            </div>

            <!-- Button at the bottom of modal -->
            <div class="modal-footer">
                <button type="submit" class="submit-btn">Update Route</button>
            </div>
        </form>
    </div>
</div>


<script>


</script>





  <!-- First row with two tables -->
  <div class="table-data table-container">
    <!-- Left table: Unallocated Suppliers -->
    <div class="order">
    <div class="head">
        <h3>Unallocated Suppliers</h3>
        <div class="filter-container">
            <label for="day-filter">Filter by Day:</label>
            <select id="day-filter">
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
                <th>Location</th>
                <th>Preferred Day</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['unassignedSuppliersList'] as $supplier): ?>
                <tr>
                    <td><?php echo htmlspecialchars($supplier->supplier_id); ?></td>
                    <td><?php echo htmlspecialchars($supplier->first_name); ?></td>
                    <td>
                        <a href="#" class="location-link" 
                            data-coordinates="<?php echo htmlspecialchars($supplier->coordinates); ?>"
                            data-name="<?php echo htmlspecialchars($supplier->supplier_name); ?>">
                            <?php echo htmlspecialchars($supplier->coordinates); ?>
                        </a>
                    </td>
                    <td>
                        <span class="status completed">
                            <?php echo $supplier->preferred_day; ?>
                        </span>
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

  <!-- First row with two tables -->
  <div class="table-data">

    <div class="order">
        <div class="head">
            <h3>Routes</h3>
            <div class="filter-container">
                <label for="routes-day-filter">Filter by Day:</label>
                <select id="routes-day-filter">
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
                    <th>Expected Collection</th>
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
                        <td>0</td>
                        <td>0</td>
                        <td>
                            <span class="status <?php echo htmlspecialchars($route->status === 'Active' ? 'completed' : 'error'); ?>">
                                <?php echo htmlspecialchars($route->status); ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <!-- Change the form to a button that triggers our JavaScript -->
                                <button 
                                    class="btn btn-secondary update-route-btn" 
                                    data-route-id="<?php echo $route->route_id; ?>"
                                >
                                    Update
                                </button>
                                
                                <!-- Keep the delete form as is -->
                                <form action="<?php echo URLROOT; ?>/vehiclemanager/deleteRoute/" method="POST" style="margin: 0;" 
                                    onsubmit="return confirm('Are you sure you want to delete this route?');">
                                    <input type="hidden" name="route_id" value="<?php echo $route->route_id; ?>">
                                    <button type="submit" class="btn btn-tertiary">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>';

require APPROOT . '/views/inc/components/footer.php'; 
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const routeDayFilter = document.getElementById("routes-day-filter");
        const routesTable = document.querySelector("table tbody");

        routeDayFilter.addEventListener("change", function() {
            const selectedDay = this.value;
            console.log("Selected day:", selectedDay); // Debug log

            const rows = routesTable.getElementsByTagName("tr");
            console.log("Found rows:", rows.length); // Debug log

            Array.from(rows).forEach((row, index) => {
                const dayCell = row.getElementsByTagName("td")[3]; // Index 3 is the Day column
                if (dayCell) {
                    const routeDay = dayCell.textContent.trim();
                    console.log(`Row ${index}: ${routeDay} comparing with ${selectedDay}`); // Debug log
                    
                    const shouldShow = selectedDay === "" || routeDay === selectedDay;
                    console.log(`Row ${index} should be ${shouldShow ? 'shown' : 'hidden'}`); // Debug log
                    
                    row.style.display = shouldShow ? "" : "none";
                } else {
                    console.log(`Row ${index}: No day cell found`); // Debug log
                }
            });
        });
    });
</script>

<script>
    // Hardcoded data for the number of suppliers allocated for each day
    const days = ['MON', 'TUE', 'WED', 'THUR', 'FRI', 'SAT', 'SUN'];
    const supplier = [5, 7, 3, 9, 6, 4, 8]; // Example data

    const ctx = document.getElementById('supplierChart').getContext('2d');
    const supplierChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                data: supplier,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 0.5
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

