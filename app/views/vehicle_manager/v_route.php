<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

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



  <div class="routes-section">
    <h2>All Routes</h2>
    <button id="createRouteButton" class="create-route-btn">Create Route</button>
    <div id="routesContainer" class="routes-container"></div>
  </div>

  <!-- Modal Form for Creating or Editing a Route -->
  <div id="routeModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Route Details</h2>
      
      <form id="routeForm">
        <input type="hidden" id="routeId" name="routeId">

        <label for="routeName">Route Name:</label>
        <input type="text" id="routeName" name="routeName" required>

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

        <label for="status">Status:</label>
        <select id="status" name="status" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>

        <label for="supplierSelect">Select Supplier:</label>
        <select id="supplierSelect">
          <option value="" disabled selected>Select a supplier</option>
        </select>
        <button type="button" id="addSupplierButton">Add Supplier Stop</button>



        <h3>Route Stops:</h3>
        <ul id="stopList"></ul>

        <div id="map" style="width: 100%; height: 400px;"></div>

        <button type="submit" class="submit-btn">Save Route</button>
      </form>
    </div>
  </div>

  <!-- Add this after your existing create route modal -->
  <div id="editRouteModal" class="modal">
    <div class="modal-content">
        <span class="close-edit">&times;</span>
        <h2>Edit Route</h2>
        
        <form id="editRouteForm">
            <input type="hidden" id="editRouteId" name="editRouteId">

            <label for="editRouteName">Route Name:</label>
            <input type="text" id="editRouteName" name="editRouteName" required>

            <label for="editStatus">Status:</label>
            <select id="editStatus" name="editStatus" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <label for="editSupplierSelect">Select Supplier:</label>
            <select id="editSupplierSelect">
                <option value="" disabled selected>Select a supplier</option>
            </select>
            <button type="button" id="editAddSupplierButton">Add Supplier Stop</button>

            <h3>Route Stops:</h3>
            <ul id="editStopList"></ul>

            <div id="editMap" style="width: 100%; height: 400px;"></div>

            <button type="submit" class="submit-btn">Update Route</button>
        </form>
    </div>
  </div>


  <!-- First row with two tables -->
  <div class="table-data table-container">
    <!-- Left table: Unallocated Suppliers -->
    <div class="order">
        <div class="head">
            <h3>Unallocated Suppliers</h3>
            <i class='bx bx-search'></i>
        </div>
        <table>
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
                            <span class="preferred-day">
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
            <h3>Routes</h3>
            <i class='bx bx-search'></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Route ID</th>
                    <th>Name</th>
                    <th>Suppliers</th>
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
                        <td>
                            <span class="status <?php echo htmlspecialchars($route->status === 'Active' ? 'completed' : 'error'); ?>">
                                <?php echo htmlspecialchars($route->status); ?>
                            </span>
                        </td>
                        <td>
                            <form action="<?php echo URLROOT; ?>/vehiclemanager/deleteRoute/" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete this route?');">
                                <input type="hidden" name="route_id" value="<?php echo $route->route_id; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </div>

  <!-- Separate row for Route Suppliers table -->
  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3 class="route-name">Route: Select a route</h3>
        </div>
        <table id="routeSupplierTable">
            <thead>
                <tr>
                    <th>Stop Order</th>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">Select a route to view suppliers</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>

  <!-- Location Modal -->
  <div id="locationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="locationTitle">Supplier Location</h2>
        <div id="locationMap" style="width: 100%; height: 400px;"></div>
    </div>
  </div>

  <!-- Row containing both charts side by side -->

  <!-- <div class="table-data">
    <div class="order">
    <h3>Route Efficiency and Weight Distribution</h3>
    <div class="chart-row-wrapper">
      <div id="chartContainer" class="chart-container">
        <canvas id="routeEfficiencyChart"></canvas>
      </div>

      <div id="avgKgChartContainer" class="chart-container">
        <canvas id="avgKgChart"></canvas>
      </div>
    </div>
  </div>
  </div> -->


</main>



<style>
  .route-box-info {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    margin-top: 36px;
    list-style: none;
    padding: 0;
  }

  .route-box-info li {
    flex: 1;
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 24px;
  }

  .route-box-info li i {
    font-size: 36px;
    color: var(--main);
    background: var(--light-main);
    border-radius: 10%;
    padding: 16px;
  }

  .route-box-info li .text h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
  }

  .route-box-info li .text p {
    font-size: 14px;
    color: var(--dark-grey);
    margin: 0;
  }

  .routes-section {
    margin-top: 40px;
  }

  .create-route-btn {
    padding: 10px 20px;
    background-color: var(--main);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
  }

  .create-route-btn:hover {
    background-color: var(--main-dark);
  }

  .routes-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
  }

  .route-card {
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
    width: calc(25% - 15px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s ease;
  }

  .route-card:hover {
    transform: translateY(-5px);
  }

  .route-card h3 {
    margin-top: 0;
    color: var(--dark);
  }

  .route-card p {
    margin: 5px 0;
    color: var(--dark-grey);
  }

  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow-y: auto;
  }

  .modal-content {
    background-color: var(--light);
    margin: 5% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
  }

  .close {
    color: var(--dark-grey);
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover {
    color: var(--dark);
  }

  #routeForm {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  #routeForm label {
    font-weight: bold;
    color: var(--dark);
  }

  #routeForm input,
  #routeForm select {
    width: 100%;
    padding: 8px;
    border: 1px solid var(--dark-grey);
    border-radius: 5px;
    font-size: 14px;
  }

  .submit-btn {
    padding: 10px 20px;
    background-color: var(--main);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    align-self: flex-start;
  }

  .submit-btn:hover {
    background-color: var(--main-dark);
  }

  #stopList {
    list-style-type: none;
    padding: 0;
  }

  #stopList li {
    margin-bottom: 5px;
  }

  .remove-stop {
    color: red;
    cursor: pointer;
    margin-left: 10px;
  }

  @media screen and (max-width: 1024px) {
    .route-card {
      width: calc(33.33% - 13.33px);
    }
  }

  @media screen and (max-width: 768px) {
    .route-card {
      width: calc(50% - 10px);
    }
  }

  @media screen and (max-width: 480px) {
    .route-card {
      width: 100%;
    }
  }


  /* Styles for full-width charts */
  #chartContainer, #avgKgChartContainer {
    width: 30%;
    margin-top: 30px; /* Add some space between the graphs */
  }

  .chart-row-wrapper {
      display: flex;
      justify-content: center;   /* Centers the entire row of charts horizontally */
      align-items: center;       /* Aligns the charts vertically in the row */
      margin: 30px 0;            /* Adds spacing above and below the charts */
    }

    /* Chart container to control the width and height */
    .chart-container {
      width: 50%;                /* Each chart takes 45% of the row's width */
      height: 300px;             /* Set a fixed height */
      margin: 0 20px;            /* Adds space between the two charts */
    }

    /* Ensures the canvas takes full width/height of its container */
    canvas {
      width: 100% !important;
      height: 100% !important;
    }

    /* Add to your existing styles */
    .expand-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        transition: transform 0.3s ease;
    }

    .expand-btn.active i {
        transform: rotate(180deg);
    }

    .expanded-details {
        padding: 20px;
        background: var(--light);
        border-radius: 8px;
        margin: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .supplier-list-container {
        width: 100%;
    }

    .supplier-list-header {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 2fr 1fr;
        gap: 20px;
        padding: 15px 20px;
        background: var(--main);
        color: var(--light);
        font-weight: 600;
    }

    .supplier-list-content {
        max-height: 300px;
        overflow-y: auto;
    }

    .supplier-item {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 2fr 1fr;
        gap: 20px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--grey);
        align-items: center;
    }

    .supplier-item:hover {
        background: var(--grey);
    }

    .supplier-management {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .current-suppliers, .add-supplier {
        flex: 1;
    }

    .supplier-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .supplier-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .remove-supplier {
        color: red;
        cursor: pointer;
    }

    .action-buttons {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .save-changes-btn, .cancel-changes-btn {
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .save-changes-btn {
        background: var(--main);
        color: white;
        border: none;
    }

    .cancel-changes-btn {
        background: #dc3545;
        color: white;
        border: none;
    }

    /* Add to your existing styles */
    .location-link {
        color: var(--main);
        text-decoration: underline;
        cursor: pointer;
    }

    #locationModal {
        display: none;
        position: fixed;
        z-index: 1001;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    #locationModal .modal-content {
        width: 80%;
        max-width: 800px;
    }

    .supplier-timeline {
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        margin-top: 20px;
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .timeline-content {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .expanded-content {
        display: none; /* Hidden by default */
    }

    .expanded-content td {
        padding: 0; /* Remove padding from td */
    }

    .supplier-list {
        width: 100%;
        background: var(--light);
    }

    .supplier-list-header {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 2fr 1fr;
        gap: 20px;
        padding: 15px 20px;
        background: var(--main);
        color: var(--light);
        font-weight: 600;
    }

    .supplier-list-content {
        max-height: 300px;
        overflow-y: auto;
    }

    .supplier-item {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 2fr 1fr;
        gap: 20px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--grey);
        align-items: center;
    }

    .supplier-item:hover {
        background: var(--grey);
    }

    .expanded-content {
        display: none;
        position: relative;
    }

    .expanded-content::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--light);
        z-index: 1;
    }

    .supplier-list {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 2;
        background: var(--light);
    }

    .supplier-list-header {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 2fr 1fr;
        gap: 20px;
        padding: 15px 20px;
        background: var(--main);
        color: var(--light);
        font-weight: 600;
    }

    .supplier-list-content {
        max-height: 300px;
        overflow-y: auto;
    }

    .supplier-item {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 2fr 1fr;
        gap: 20px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--grey);
        align-items: center;
    }

    .supplier-item:hover {
        background: var(--grey);
    }

    .table-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .route-row {
        cursor: pointer;
    }

    .route-row:hover {
        background-color: var(--grey);
    }

    .route-row.selected {
        background-color: var(--grey);
    }

    .route-name {
        color: var(--dark-grey);
        font-size: 14px;
        margin: 0;
    }

    .supplier-details {
        padding: 15px;
        background: var(--light);
        border-radius: 4px;
        margin: 10px;
    }

    .supplier-details h4 {
        margin-bottom: 10px;
        color: var(--dark);
    }

    .supplier-details-table {
        width: 100%;
        border-collapse: collapse;
    }

    .supplier-details-table th,
    .supplier-details-table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .supplier-details-table th {
        background-color: var(--grey);
        font-weight: 600;
    }

    .route-row.active {
        background-color: var(--grey);
    }

    #routeSupplierTable {
        margin-top: 10px;
    }

    /* Add this to your CSS file */
    .delete-btn {
        background-color: red;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: darkred;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Get modal elements
    const modal = document.getElementById('routeModal');
    const createRouteButton = document.getElementById('createRouteButton');

    // Initialize currentRoute
    let currentRoute = null;

    // Create route button click handler
    createRouteButton.addEventListener('click', () => {
        currentRoute = { 
            id: `R${Date.now()}`, // Generate a unique ID
            name: '', 
            status: 'Active', 
            stops: [] 
        };
        document.getElementById('modalTitle').textContent = 'Create Route';
        document.getElementById('routeName').value = '';
        document.getElementById('status').value = 'Active';
        updateStopList();
        modal.style.display = 'block';
        
        // Initialize map if needed
        setTimeout(() => {
            if (typeof initMap === 'function') {
                initMap();
                updateMap();
            }
        }, 100);
    });

    // Close button handler
    document.querySelector('.close').addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Click outside modal to close
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Replace hardcoded suppliers with data from PHP
    const suppliers = <?php echo json_encode($unallocatedSuppliers); ?>;

    
    let map;
    let directionsService;
    let directionsRenderer;

    const routeForm = document.getElementById('routeForm');
    const supplierSelect = document.getElementById('supplierSelect');
    const stopList = document.getElementById('stopList');
    const routesContainer = document.getElementById('routesContainer');

    // Add this at the top with other global variables
    let markers = []; // Array to store all markers

    function clearMarkers() {
        // Remove all markers from the map
        markers.forEach(marker => marker.setMap(null));
        markers = []; // Clear the array
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: { lat: 6.2173037, lng: 80.2538636 } // Center of Sri Lanka
        });
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);
    }



    function updateMap() {
        if (!map) {
            console.error('Map not initialized');
            return;
        }

        clearMarkers(); // Clear existing markers

        const factoryLocation = { 
            lat: <?php echo M_Route::FACTORY_LAT; ?>, 
            lng: <?php echo M_Route::FACTORY_LONG; ?> 
        };

        // Add factory marker
        const factoryMarker = new google.maps.Marker({
            position: factoryLocation,
            map: map,
            label: {
                text: 'F',
                color: 'white'
            },
            title: 'Factory (Start)'
        });
        markers.push(factoryMarker);

        if (currentRoute.stops.length > 0) {
            
            currentRoute.stops.forEach((stop, index) => {
                const supplierMarker = new google.maps.Marker({
                    position: stop.location,
                    map: map,
                    label: {
                        text: (index + 1).toString(),
                        color: 'white'
                    },
                    title: `Stop ${index + 1}: ${stop.name}`
                });
                markers.push(supplierMarker);
            });
        }
    }

    function populateSupplierDropdown(selectedDay) {
        const supplierSelect = document.getElementById('supplierSelect');
        supplierSelect.innerHTML = '<option value="" disabled selected>Select a supplier</option>'; // Reset the dropdown

        // Filter suppliers based on the selected day
        const filteredSuppliers = suppliers.filter(supplier => supplier.preferred_day === selectedDay);
        console.log('Filtered Suppliers:', filteredSuppliers); // Debugging output

        // Populate the supplier dropdown with filtered suppliers
        filteredSuppliers.forEach(supplier => {
            const option = document.createElement('option');
            option.value = supplier.id;
            option.textContent = supplier.name;
            supplierSelect.appendChild(option);
        });
    }

// Event listener for the daySelect dropdown
document.getElementById('daySelect').addEventListener('change', function() {
    const selectedDay = this.value;
    console.log('Selected Day:', selectedDay); // Log the selected day
    console.log('Available Suppliers:', suppliers); // Log the suppliers array

    populateSupplierDropdown(selectedDay); // Call the function to populate suppliers
});

    function updateStopList() {
        stopList.innerHTML = '';
        currentRoute.stops.forEach((stop, index) => {
            const li = document.createElement('li');
            li.innerHTML = `${stop.name} <span class="remove-stop" data-id="${stop.id}">Remove</span>`;
            stopList.appendChild(li);
        });

        document.querySelectorAll('.remove-stop').forEach(removeButton => {
            removeButton.addEventListener('click', function() {
                const stopId = this.getAttribute('data-id');
                const removedStop = currentRoute.stops.find(stop => stop.id === stopId);
                
                // Remove from current stops
                currentRoute.stops = currentRoute.stops.filter(stop => stop.id !== stopId);
                
                // Add back to dropdown
                if (removedStop) {
                    const option = document.createElement('option');
                    option.value = removedStop.id;
                    option.textContent = removedStop.name;
                    supplierSelect.appendChild(option);
                }
                
                updateStopList();
                updateMap(); 
            });
        });
    }

    // Add this before displayRouteCards function
    const routes = <?php echo json_encode($data['allRoutes']); ?>;

    function displayRouteCards() {
        routesContainer.innerHTML = '';
        routes.forEach(route => {
            const card = document.createElement('div');
            card.className = 'route-card';
            card.innerHTML = `
                <h3>${route.route_name}</h3>
                <p>Status: ${route.status}</p>
                <p>Stops: ${route.number_of_suppliers}</p>
            `;
            card.addEventListener('click', () => openRouteModal(route));
            routesContainer.appendChild(card);
        });
    }

    function openRouteModal(route = null) {
        currentRoute = route ? JSON.parse(JSON.stringify(route)) : { id: `R${routes.length + 1}`, name: '', status: 'Active', stops: [] };
        document.getElementById('modalTitle').textContent = route ? 'Edit Route' : 'Create Route';
        document.getElementById('routeId').value = currentRoute.id;
        document.getElementById('routeName').value = currentRoute.name;
        document.getElementById('status').value = currentRoute.status;
        updateStopList();
        modal.style.display = 'block';
        setTimeout(() => {
            initMap();
            updateMap();
        }, 100);
    }

    document.getElementById('addSupplierButton').addEventListener('click', () => {
        const selectedSupplierId = supplierSelect.value;
        const selectedSupplier = suppliers.find(supplier => supplier.id === selectedSupplierId);
        
        if (selectedSupplier && !currentRoute.stops.find(stop => stop.id === selectedSupplier.id)) {
            currentRoute.stops.push({
                id: selectedSupplier.id,
                name: selectedSupplier.name,
                location: selectedSupplier.location
            });
            
            // Remove from dropdown
            const option = supplierSelect.querySelector(`option[value="${selectedSupplierId}"]`);
            if (option) {
                option.remove();
            }
            
            updateStopList();
            updateMap(); // This will recalculate Dijkstra's and update markers
        }
    });

    routeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const routeData = {
                name: document.getElementById('routeName').value,
                status: document.getElementById('status').value,
                stops: currentRoute.stops.map(stop => ({
                    id: parseInt(stop.id)
                }))
            };

            console.log('Sending data:', routeData); // Debug log

            const response = await fetch('<?php echo URLROOT; ?>/vehiclemanager/createRoute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(routeData)
            });

            // Log the raw response for debugging
            const rawResponse = await response.text();
            console.log('Raw response:', rawResponse);

            // Try to parse the response
            let result;
            try {
                result = JSON.parse(rawResponse);
                console.log('Parsed response:', result);
            } catch (parseError) {
                console.error('Failed to parse response:', parseError);
                throw new Error('Invalid server response');
            }

            if (result.success) {
                alert(result.message);
                modal.style.display = 'none';
                window.location.reload();
            } else {
                throw new Error(result.message || 'Failed to create route');
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Error creating route: ' + error.message);
        }
    });

    // Initialize
    populateSupplierDropdown();
    displayRouteCards();

    // Add these variables with your other global variables
    const editModal = document.getElementById('editRouteModal');
    const editRouteForm = document.getElementById('editRouteForm');
    const editSupplierSelect = document.getElementById('editSupplierSelect');
    const editStopList = document.getElementById('editStopList');
    let editingRoute = null;
    let editMap = null;
    let editDirectionsService = null;
    let editDirectionsRenderer = null;

    function displayRouteCards() {
        routesContainer.innerHTML = '';
        routes.forEach(route => {
            const card = document.createElement('div');
            card.className = 'route-card';
            card.innerHTML = `
                <h3>${route.route_name}</h3>
                <p>Status: ${route.status}</p>
                <p>Stops: ${route.number_of_suppliers}</p>
            `;
            card.addEventListener('click', () => openEditRouteModal(route));
            routesContainer.appendChild(card);
        });
    }

    async function openEditRouteModal(route) {
        console.log('Opening edit modal with route:', route); // Debug log

        editingRoute = {
            id: route.route_id,
            name: route.route_name,
            status: route.status,
            start_location: {
                lat: parseFloat(route.start_location_lat),
                lng: parseFloat(route.start_location_long)
            },
            end_location: {
                lat: parseFloat(route.end_location_lat),
                lng: parseFloat(route.end_location_long)
            },
            stops: []
        };

        try {
            const url = `<?php echo URLROOT; ?>/vehiclemanager/getRouteSuppliers/${route.route_id}`;
            console.log('Fetching suppliers for route:', route.route_id); // Debug log
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const routeSuppliers = await response.json();
            console.log('Received suppliers:', routeSuppliers); // Debug log
            
            // Update the stops array with the received suppliers
            if (routeSuppliers.success) {
                editingRoute.stops = routeSuppliers.data.suppliers; // Access the suppliers correctly
            } else {
                throw new Error(routeSuppliers.message || 'Failed to load suppliers');
            }
            
            // Update the UI
            updateEditStopList();
            
        } catch (error) {
            console.error('Error fetching route suppliers:', error);
            alert('Error loading route details: ' + error.message);
        }
    }

    function populateEditSupplierDropdown() {
        editSupplierSelect.innerHTML = '<option value="" disabled selected>Select a supplier</option>';
        suppliers.forEach(supplier => {
            // Only add suppliers not already in the route
            if (!editingRoute.stops.find(stop => stop.id === supplier.id)) {
                const option = document.createElement('option');
                option.value = supplier.id;
                option.textContent = supplier.name;
                editSupplierSelect.appendChild(option);
            }
        });
    }

    function updateEditStopList() {
        const stopList = document.getElementById('editStopList');
        stopList.innerHTML = ''; // Clear existing stops

        if (Array.isArray(editingRoute.stops) && editingRoute.stops.length > 0) {
            editingRoute.stops.forEach((stop, index) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <span class="stop-number">${index + 1}</span>
                    <span class="supplier-name">${stop.name}</span>
                    <button type="button" class="remove-stop" onclick="removeStop(this)">×</button>
                `;
                stopList.appendChild(li);
            });
        } else {
            const li = document.createElement('li');
            li.textContent = 'No stops available';
            stopList.appendChild(li);
        }
    }

    // Add event listeners
    document.querySelector('.close-edit').addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    document.getElementById('editAddSupplierButton').addEventListener('click', () => {
        const selectedSupplierId = editSupplierSelect.value;
        const selectedSupplier = suppliers.find(supplier => supplier.id === selectedSupplierId);
        
        if (selectedSupplier && !editingRoute.stops.find(stop => stop.id === selectedSupplier.id)) {
            editingRoute.stops.push({
                id: selectedSupplier.id,
                name: selectedSupplier.name,
                location: selectedSupplier.location
            });
            updateEditStopList();
            updateEditMap();
            
            const option = editSupplierSelect.querySelector(`option[value="${selectedSupplierId}"]`);
            if (option) {
                option.remove();
            }
        }
    });

    editRouteForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const routeData = {
            id: editingRoute.id,
            name: document.getElementById('editRouteName').value,
            status: document.getElementById('editStatus').value,
            stops: editingRoute.stops.map(stop => ({
                id: parseInt(stop.id.replace('S', ''))
            }))
        };

        try {
            const response = await fetch('<?php echo URLROOT; ?>/vehiclemanager/updateRoute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(routeData)
            });

            const result = await response.json();

            if (result.success) {
                alert('Route updated successfully!');
                editModal.style.display = 'none';
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating the route');
        }
    });


    // Helper function to add markers
    function addRouteMarkers(map, routePoints) {
        // Add factory marker
        new google.maps.Marker({
            position: routePoints[0].location,
            map: map,
            label: {
                text: 'F',
                color: 'white'
            },
            title: 'Factory (Start)',
        });

        // Add numbered markers for suppliers
        routePoints.slice(1).forEach((stop, index) => {
            new google.maps.Marker({
                position: stop.location,
                map: map,
                label: {
                    text: (index + 1).toString(),
                    color: 'white'
                },
                title: `Stop ${index + 1}: ${stop.name}`
            });
        });
    }

    function updateSupplierList(expandedRow, suppliers) {
        const listContent = expandedRow.querySelector('.supplier-list-content');
        listContent.innerHTML = '';
        
        suppliers.forEach((supplier, index) => {
            const supplierItem = document.createElement('div');
            supplierItem.className = 'supplier-item';
            supplierItem.innerHTML = `
                <div>${index + 1}</div>
                <div>${supplier.supplier_name}</div>
                <div>${supplier.contact_number}</div>
                <div>${supplier.address}</div>
                <div>${supplier.daily_capacity} kg</div>
            `;
            listContent.appendChild(supplierItem);
        });
    }

    const routeRows = document.querySelectorAll('.route-row');
    const supplierTableBody = document.querySelector('#routeSupplierTable tbody');
    const routeNameDisplay = document.querySelector('.route-name');

    routeRows.forEach(row => {
        row.addEventListener('click', async function() {
            // Remove selected class from all rows
            document.querySelectorAll('.route-row').forEach(r => r.classList.remove('selected'));
            // Add selected class to clicked row
            this.classList.add('selected');

            const routeId = this.dataset.routeId;
            const routeName = this.querySelector('td:nth-child(2)').textContent;

            try {
                const response = await fetch(`<?php echo URLROOT; ?>/vehiclemanager/getRouteSuppliers/${routeId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch suppliers');
                }
                
                const result = await response.json();
                console.log('Received data:', result); // Debug log
                
                if (!result.success) {
                    throw new Error(result.error || 'Failed to load suppliers');
                }

                // Update route name display
                routeNameDisplay.textContent = `Route: ${result.data.route.name}`;
                
                // Clear existing table content
                supplierTableBody.innerHTML = '';
                
                // Check if suppliers exist
                if (!result.data.suppliers || result.data.suppliers.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = '<td colspan="4" style="text-align: center;">No suppliers assigned to this route</td>';
                    supplierTableBody.appendChild(emptyRow);
                    return;
                }
                
                // Add each supplier to the table
                result.data.suppliers.forEach((supplier, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${supplier.stop_order || index + 1}</td>
                        <td>${supplier.id || 'N/A'}</td>
                        <td>${supplier.name || 'N/A'}</td>
                        <td>${supplier.location.lat}, ${supplier.location.lng}</td>
                    `;
                    supplierTableBody.appendChild(row);
                });
                
            } catch (error) {
                console.error('Error:', error);
                routeNameDisplay.textContent = 'Error loading suppliers';
                supplierTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center;">Error loading suppliers</td></tr>';
            }
        });
    });




});

</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Google Maps API Script -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>