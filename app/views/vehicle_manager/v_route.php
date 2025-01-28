<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<script>
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>


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

<script>
// Global variables
let currentRoute = null;
let map;
let directionsService;
let directionsRenderer;
let markers = [];

// Map related functions
function clearMarkers() {
  markers.forEach((marker) => marker.setMap(null));
  markers = [];
}

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 15,
    center: { lat: 6.2173037, lng: 80.2538636 }, // Center of Sri Lanka
  });
  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer();
  directionsRenderer.setMap(map);
}

function updateMap() {
  if (!map) {
    console.error("Map not initialized");
    return;
  }

  clearMarkers();

  // Add factory marker
  const factoryMarker = new google.maps.Marker({
    position: factoryLocation,
    map: map,
    label: {
      text: "F",
      color: "white",
    },
    title: "Factory (Start)",
  });
  markers.push(factoryMarker);

  if (currentRoute?.stops?.length > 0) {
    currentRoute.stops.forEach((stop, index) => {
      const supplierMarker = new google.maps.Marker({
        position: stop.location,
        map: map,
        label: {
          text: (index + 1).toString(),
          color: "white",
        },
        title: `Stop ${index + 1}: ${stop.name}`,
      });
      markers.push(supplierMarker);
    });
  }
}

// Supplier and stop management functions
function populateSupplierDropdown(selectedDay) {
  const supplierSelect = document.getElementById("supplierSelect");
  supplierSelect.innerHTML =
    '<option value="" disabled selected>Select a supplier</option>';

  const filteredSuppliers = suppliers.filter((supplier) => {
    const isInCurrentRoute = currentRoute.stops.some(
      (stop) => stop.id === supplier.id
    );
    return supplier.preferred_day === selectedDay && !isInCurrentRoute;
  });

  filteredSuppliers.forEach((supplier) => {
    const option = document.createElement("option");
    option.value = supplier.id;
    option.textContent = `${supplier.name} - ${supplier.average_collection} kg`;
    supplierSelect.appendChild(option);
  });
}

function updateStopList() {
  const stopList = document.getElementById("stopList");
  stopList.innerHTML = "";

  currentRoute.stops.forEach((stop) => {
    const li = document.createElement("li");
    li.innerHTML = `${stop.name} - ${stop.average_collection} kg <span class="remove-stop" data-id="${stop.id}">Remove</span>`;
    stopList.appendChild(li);
  });

  updateCapacity();

  // Add remove stop event listeners
  document.querySelectorAll(".remove-stop").forEach((removeButton) => {
    removeButton.removeEventListener("click", handleRemoveStop);
    removeButton.addEventListener("click", handleRemoveStop);
  });
}

function handleRemoveStop() {
  const stopId = this.getAttribute("data-id");
  const removedStop = currentRoute.stops.find((stop) => stop.id === stopId);

  currentRoute.stops = currentRoute.stops.filter((stop) => stop.id !== stopId);

  if (removedStop) {
    const supplierSelect = document.getElementById("supplierSelect");
    const existingOption = supplierSelect.querySelector(
      `option[value="${removedStop.id}"]`
    );
    if (!existingOption) {
      const option = document.createElement("option");
      option.value = removedStop.id;
      option.textContent = `${removedStop.name} - ${removedStop.average_collection} kg`;
      supplierSelect.appendChild(option);
    }
  }

  updateStopList();
  updateMap();
}

// Capacity management
function updateCapacity() {
  const totalCapacity = currentRoute.stops.reduce(
    (total, stop) => total + parseFloat(stop.average_collection),
    0
  );

  document.getElementById("usedCapacity").textContent = `${Number(
    totalCapacity
  )} kg`;

  const vehicleCapacityText = document.getElementById(
    "vehicleCapacityDisplay"
  ).textContent;
  const vehicleCapacity = parseInt(vehicleCapacityText);

  if (isNaN(vehicleCapacity)) {
    document.getElementById("remainingCapacity").textContent = "0 kg";
  } else {
    document.getElementById("remainingCapacity").textContent = `${
      vehicleCapacity - totalCapacity
    } kg`;
  }
}

// Vehicle management
async function handleVehicleSelect() {
  const selectedOption = this.options[this.selectedIndex];
  const vehicleId = this.value;
  const supplierSelect = document.getElementById("supplierSelect");

  supplierSelect.disabled = !vehicleId;

  if (vehicleId) {
    try {
      const response = await fetch(
        `${URLROOT}/vehiclemanager/getVehicleDetails/${vehicleId}`
      );
      const result = await response.json();

      if (result.status === "success" && result.data) {
        const vehicle = result.data;

        document.getElementById("vehicleNumberDisplay").textContent =
          vehicle.license_plate;
        document.getElementById(
          "vehicleCapacityDisplay"
        ).textContent = `${vehicle.capacity}kg`;
        document.getElementById("vehicleTypeDisplay").textContent =
          vehicle.vehicle_type;

        document.getElementById("usedCapacity").textContent = "0 kg";
        document.getElementById(
          "remainingCapacity"
        ).textContent = `${vehicle.capacity} kg`;

        const imagePath = vehicle.license_plate
          ? `${URLROOT}/public/uploads/vehicle_photos/${vehicle.license_plate}.jpg`
          : `${URLROOT}/public/uploads/vehicle_photos/default-vehicle.jpg`;
        document.getElementById("vehicleImage").src = imagePath;
      }
    } catch (error) {
      console.error("Error loading vehicle details:", error);
    }
  } else {
    document.getElementById("usedCapacity").textContent = "0 kg";
    document.getElementById("remainingCapacity").textContent = "0 kg";
  }
}

// Form submission handlers
async function handleRouteFormSubmit(e) {
  e.preventDefault();

  try {
    const routeData = {
      name: document.getElementById("routeName").value,
      status: document.getElementById("status").value,
      day: document.getElementById("daySelect").value,
      vehicle_id: document.getElementById("vehicleSelect").value,
      stops: currentRoute.stops.map((stop) => ({
        id: parseInt(stop.id),
      })),
    };

    const response = await fetch(`${URLROOT}/vehiclemanager/createRoute`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(routeData),
    });

    const rawResponse = await response.text();
    let result;

    try {
      result = JSON.parse(rawResponse);
    } catch (parseError) {
      console.error("Failed to parse response:", parseError);
      throw new Error("Invalid server response");
    }

    if (result.success) {
      alert(result.message);
      document.getElementById("routeModal").style.display = "none";
      window.location.reload();
    } else {
      throw new Error(result.message || "Failed to create route");
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error creating route: " + error.message);
  }
}

// Main initialization
document.addEventListener("DOMContentLoaded", function () {
  // Initialize DOM elements
  const modal = document.getElementById("routeModal");
  const createRouteButton = document.getElementById("createRouteButton");
  const routeForm = document.getElementById("routeForm");
  const supplierSelect = document.getElementById("supplierSelect");
  const dayFilter = document.getElementById("day-filter");
  const suppliersTable = document.getElementById("suppliers-table");
  const updateModal = document.getElementById("updateRouteModal");

  // Day filter functionality
  if (dayFilter && suppliersTable) {
    dayFilter.addEventListener("change", function () {
      const selectedDay = this.value;
      const tbody = suppliersTable.getElementsByTagName("tbody")[0];
      const rows = tbody.getElementsByTagName("tr");

      for (let row of rows) {
        const preferredDayElement = row.querySelector(".preferred-day");
        const preferredDay = preferredDayElement.textContent.trim();
        row.style.display =
          selectedDay === "" || preferredDay === selectedDay ? "" : "none";
      }
    });
  }

  // Create route button handler
  if (createRouteButton) {
    createRouteButton.addEventListener("click", () => {
      currentRoute = {
        id: `R${Date.now()}`,
        name: "",
        status: "Active",
        stops: [],
      };
      document.getElementById("modalTitle").textContent = "Create Route";
      document.getElementById("routeName").value = "";
      document.getElementById("status").value = "Active";
      modal.style.display = "block";

      setTimeout(() => {
        if (typeof initMap === "function") {
          initMap();
          updateMap();
        }
      }, 100);
    });
  }

  // Add supplier button handler
  const addSupplierButton = document.getElementById("addSupplierButton");
  if (addSupplierButton) {
    addSupplierButton.addEventListener("click", (event) => {
      event.preventDefault();
      const selectedSupplierId = supplierSelect.value;
      const selectedSupplier = suppliers.find(
        (supplier) => supplier.id === selectedSupplierId
      );

      if (
        selectedSupplier &&
        !currentRoute.stops.find((stop) => stop.id === selectedSupplier.id)
      ) {
        currentRoute.stops.push({
          id: selectedSupplier.id,
          name: selectedSupplier.name,
          location: selectedSupplier.location,
          average_collection: selectedSupplier.average_collection,
        });

        const option = supplierSelect.querySelector(
          `option[value="${selectedSupplierId}"]`
        );
        if (option) option.remove();

        updateStopList();
        updateMap();
      }
    });
  }

  // Day select handler
  const daySelect = document.getElementById("daySelect");
  if (daySelect) {
    daySelect.addEventListener("change", function () {
      const selectedDay = this.value;
      populateSupplierDropdown(selectedDay);
    });
  }

  // Vehicle select handler
  const vehicleSelect = document.getElementById("vehicleSelect");
  if (vehicleSelect) {
    vehicleSelect.addEventListener("change", handleVehicleSelect);
  }

  // Form submission handler
  if (routeForm) {
    routeForm.addEventListener("submit", handleRouteFormSubmit);
  }

  // Modal close handlers
  document.querySelector(".close")?.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // Initialize route update functionality
  initializeRouteUpdate();
});

// Route update functionality
function initializeRouteUpdate() {
  const updateModal = document.getElementById("updateRouteModal");
  const updateForm = document.getElementById("updateRouteForm");
  let currentUpdateRoute = null;

  // Add click handlers to update buttons
  document.querySelectorAll(".route-row").forEach((row) => {
    const updateBtn = row.querySelector(".btn-secondary");
    updateBtn?.addEventListener("click", async (e) => {
      e.preventDefault();
      const routeId = row.getAttribute("data-route-id");
      await loadRouteDetails(routeId);
    });
  });

  async function loadRouteDetails(routeId) {
    try {
      const response = await fetch(
        `${URLROOT}/vehiclemanager/getRouteDetails/${routeId}`
      );
      const result = await response.json();

      if (result.success) {
        currentUpdateRoute = {
          route_id: result.route.id,
          route_name: result.route.name,
          status: result.route.status,
          stops: result.route.suppliers.map((supplier) => ({
            supplier_id: supplier.id,
            supplier_name: supplier.name,
            coordinates: supplier.coordinates,
            average_collection: supplier.average_collection || 0,
          })),
        };

        populateUpdateForm(currentUpdateRoute);
        updateModal.style.display = "block";
      } else {
        throw new Error(result.message || "Failed to load route details");
      }
    } catch (error) {
      console.error("Error loading route details:", error);
      alert("Error loading route details: " + error.message);
    }
  }
}

</script>

