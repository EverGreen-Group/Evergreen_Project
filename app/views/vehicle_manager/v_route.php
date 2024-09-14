<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
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
</main>

<style>
  /* Button Styles */
.create-route-btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px;
}

.create-route-btn:hover {
  background-color: #0056b3;
}

/* Routes Container */
.routes-section {
  margin-top: 30px;
}

.routes-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 20px;
  background-color: #f0f0f0;
  padding: 20px;
  border-radius: 10px;
}

/* Route Card Styles */
.route-card {
  background: var(--light);
  border-radius: 10px;
  padding: 15px;
  width: calc(25% - 15px);
  transition: transform 0.3s ease;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: #fff;
  margin: 5% auto;
  padding: 20px;
  border-radius: 10px;
  width: 50%;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: black;
}

.submit-btn {
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.submit-btn:hover {
  background-color: #218838;
}

form label {
  display: block;
  margin: 15px 0 5px;
}

form input,
form select {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.remove-stop {
  color: red;
  cursor: pointer;
  margin-left: 10px;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const routes = [
    { id: 'R1', name: 'Route 1', status: 'Active', stops: [] },
    { id: 'R2', name: 'Route 2', status: 'Inactive', stops: [] },
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

<!-- Google Maps API Script -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>