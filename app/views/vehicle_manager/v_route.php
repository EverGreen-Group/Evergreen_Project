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
          <h3>20</h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-check-circle'></i>
        <span class="text">
          <p>Total Active</p>
          <h3>18</h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-x-circle'></i>
        <span class="text">
          <p>Total Inactive</p>
          <h3>2</h3>
        </span>
    </li>
  </ul>

  <!-- Team Information Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Route details</h3>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>Route ID</th>
            <th>Name</th>
            <th>Suppliers</th>
            <th>Req Capacity (kg)</th>
            <th>Total distance (km)</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>North</td>
            <td>30</td>
            <td>8000</td>
            <td>20</td>
            <td><span class="status completed">Active</span></td>
          </tr>
          <tr>
            <td>2</td>
            <td>East</td>
            <td>20</td>
            <td>10,000</td>
            <td>18</td>
            <td><span class="status error">Inactive</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

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


  <!-- Unallocated Collection Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Unallocated Collections</h3>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>Supplier ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Avg Collection</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>North</td>
            <td>30</td>
            <td>8000</td>
          </tr>
          <tr>
            <td>2</td>
            <td>East</td>
            <td>20</td>
            <td>10,000</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Row containing both charts side by side -->

  <div class="table-data">
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
  </div>


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
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const routes = [
    { id: 'R1', name: 'Route 1', status: 'Active', stops: [], distance: 45, reqCapacity: 10000 },
    { id: 'R2', name: 'Route 2', status: 'Inactive', stops: [], distance: 85, reqCapacity: 12000 },
  ];

  const suppliers = [
    { id: 'S1', name: 'Supplier A', location: { lat: 6.2173037, lng: 80.2564385 } }, // Evergreen factory
    { id: 'S2', name: 'Supplier B', location: { lat: 6.243808243551064, lng: 80.25967072303547 } }, // supplier 1
    { id: 'S3', name: 'Supplier C', location: { lat: 6.282762791987652, lng: 80.26495604611944 } }, // supplier 2
  ];

  let currentRoute = null;
  let map;
  let directionsService;
  let directionsRenderer;

  const modal = document.getElementById('routeModal');
  const routeForm = document.getElementById('routeForm');
  const supplierSelect = document.getElementById('supplierSelect');
  const stopList = document.getElementById('stopList');
  const routesContainer = document.getElementById('routesContainer');

  function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 8,
      center: { lat: 7.8731, lng: 80.7718 } // Center of Sri Lanka
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

    if (currentRoute.stops.length < 2) {
      directionsRenderer.setDirections({routes: []});
      return;
    }

    const origin = currentRoute.stops[0].location;
    const destination = currentRoute.stops[currentRoute.stops.length - 1].location;
    const waypoints = currentRoute.stops.slice(1, -1).map(stop => ({
      location: stop.location,
      stopover: true
    }));

    directionsService.route(
      {
        origin: origin,
        destination: destination,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING,
      },
      (response, status) => {
        if (status === "OK" && response) {
          directionsRenderer.setDirections(response);
        } else {
          console.error("Directions request failed due to " + status);
        }
      }
    );
  }

  function populateSupplierDropdown() {
    supplierSelect.innerHTML = '<option value="" disabled selected>Select a supplier</option>';
    suppliers.forEach(supplier => {
      const option = document.createElement('option');
      option.value = supplier.id;
      option.textContent = supplier.name;
      supplierSelect.appendChild(option);
    });
  }

  function updateStopList() {
    stopList.innerHTML = '';
    currentRoute.stops.forEach((stop, index) => {
      const li = document.createElement('li');
      li.innerHTML = `${index + 1}. ${stop.name} <span class="remove-stop" data-id="${stop.id}">Remove</span>`;
      stopList.appendChild(li);
    });

    document.querySelectorAll('.remove-stop').forEach(removeButton => {
      removeButton.addEventListener('click', function() {
        const stopId = this.getAttribute('data-id');
        currentRoute.stops = currentRoute.stops.filter(stop => stop.id !== stopId);
        updateStopList();
        updateMap();
      });
    });
  }

  function displayRouteCards() {
    routesContainer.innerHTML = '';
    routes.forEach(route => {
      const card = document.createElement('div');
      card.className = 'route-card';
      card.innerHTML = `
        <h3>${route.name}</h3>
        <p>Status: ${route.status}</p>
        <p>Stops: ${route.stops.length}</p>
        <p>Distance: ${route.distance}</p>
        <p>Capacity: ${route.reqCapacity}</p>
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

  document.getElementById('createRouteButton').addEventListener('click', () => openRouteModal());

  document.getElementById('addSupplierButton').addEventListener('click', () => {
    const selectedSupplierId = supplierSelect.value;
    const selectedSupplier = suppliers.find(supplier => supplier.id === selectedSupplierId);
    if (selectedSupplier && !currentRoute.stops.find(stop => stop.id === selectedSupplier.id)) {
      currentRoute.stops.push(selectedSupplier);
      updateStopList();
      updateMap();
    }
  });

  routeForm.addEventListener('submit', (e) => {
    e.preventDefault();
    currentRoute.name = document.getElementById('routeName').value;
    currentRoute.status = document.getElementById('status').value;
    
    const existingRouteIndex = routes.findIndex(r => r.id === currentRoute.id);
    if (existingRouteIndex !== -1) {
      routes[existingRouteIndex] = currentRoute;
    } else {
      routes.push(currentRoute);
    }
    
    displayRouteCards();
    modal.style.display = 'none';
  });

  document.querySelector('.close').addEventListener('click', () => {
    modal.style.display = 'none';
  });

  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });

  // Initialize
  populateSupplierDropdown();
  displayRouteCards();
});

// Global initMap function for Google Maps callback
function initMap() {
  // This function will be called by the Google Maps API
  // It will trigger the initialization of the map in our application
  const event = new Event('googlemapsloaded');
  window.dispatchEvent(event);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Example route data with average kg of tea leaves added
  const routeData = [
    { routeName: 'Route 1', distance: 120, fuelCost: 50, capacityUtilization: 80, avgKg: 500 },
    { routeName: 'Route 2', distance: 150, fuelCost: 65, capacityUtilization: 90, avgKg: 700 },
    { routeName: 'Route 3', distance: 200, fuelCost: 100, capacityUtilization: 70, avgKg: 450 },
    { routeName: 'Route 4', distance: 90, fuelCost: 40, capacityUtilization: 50, avgKg: 600 },
    { routeName: 'Route 5', distance: 110, fuelCost: 55, capacityUtilization: 85, avgKg: 650 },
  ];

  // Function to calculate efficiency score for each route
  function calculateEfficiencyScore(route) {
    return (route.distance * route.fuelCost) / route.capacityUtilization;
  }

  // Prepare data for the efficiency scatter plot
  const scatterData = routeData.map(route => ({
    x: route.distance,
    y: calculateEfficiencyScore(route),
    label: route.routeName
  }));

  // Create the scatter plot using Chart.js
  const ctxEfficiency = document.getElementById('routeEfficiencyChart').getContext('2d');
  new Chart(ctxEfficiency, {
    type: 'scatter',
    data: {
      datasets: [{
        label: 'Route Efficiency Score vs Distance',
        data: scatterData,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        pointRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          title: {
            display: true,
            text: 'Distance (km)'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Efficiency Score'
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              const route = scatterData[tooltipItem.dataIndex];
              return `${route.label}: Efficiency Score = ${route.y.toFixed(2)}, Distance = ${route.x} km`;
            }
          }
        }
      }
    }
  });

  // Prepare data for the bar chart (average KG of tea leaves per route)
  const avgKgData = routeData.map(route => route.avgKg);
  const routeNames = routeData.map(route => route.routeName);

  // Create the bar chart for average KG per route
  const ctxAvgKg = document.getElementById('avgKgChart').getContext('2d');
  new Chart(ctxAvgKg, {
    type: 'bar',
    data: {
      labels: routeNames,
      datasets: [{
        label: 'Average KG of Tea Leaves',
        data: avgKgData,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Average KG of Tea Leaves'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Routes'
          }
        }
      }
    }
  });
</script>

<!-- Google Maps API Script -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>