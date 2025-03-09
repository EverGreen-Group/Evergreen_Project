<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Sidebar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>

<!-- Top Nav Bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<script>
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Route #<?php echo htmlspecialchars($data['route_id']); ?></h1>
            <ul class="breadcrumb">
                <li><a href="#"><?php echo htmlspecialchars($data['route_name']); ?></a></li>
            </ul>
        </div>
    </div>
  <!-- Overview Section -->
  <ul class="route-box-info">
      <li>
          <i class='bx bxs-map'></i>
          <span class="text">
              <p>Selected Day</p>
              <h3><?php echo isset($data['day']) ? $data['day'] : 0; ?></h3>
          </span>
      </li>
      <li>
          <i class='bx bxs-check-circle'></i>
          <span class="text">
              <p>No of Suppliers</p>
              <h3><?php echo isset($data['number_of_suppliers']) ? (int)$data['number_of_suppliers'] : 0; ?></h3>
          </span>
      </li>
      <li>
          <i class='bx bxs-x-circle'></i>
          <span class="text">
              <p>Remaining Capacity</p>
              <h3 id="remaining-capacity-display"><?php echo isset($data['vehicleDetails']->capacity) ? (int)$data['vehicleDetails']->capacity : 0; ?></h3>
          </span>
      </li>
  </ul>

  <!-- Unassigned Suppliers Section -->
  <div class="table-data">
    <div class="order">
      <div class="head">
          <h3>Unassigned Suppliers</h3>
          <i class='bx bx-search'></i>
      </div>
      <div class="filter-options">
          <form id="addSupplierForm" action="<?php echo URLROOT; ?>/route/addSupplier" method="POST">
              <div class="filter-group">
                  <label for="employee-status">Select suppliers:</label>
                  <select id="employee-status" name="supplier_id">
                      <option value="">-- Select Supplier --</option>
                      <!-- Options will be populated here -->
                  </select>
              </div>
              <input type="hidden" name="route_id" value="<?php echo htmlspecialchars($data['route_id']); ?>">
              <button type="submit" class="btn btn-primary">Add Stop</button>
          </form>
      </div>
    </div>
  </div>

  <!-- Route Suppliers Section -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Route Suppliers</h3>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>Supplier Stop</th>
            <th>Supplier Name</th>
            <th>Average Collection (kg)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="supplierTableBody">
        </tbody>
      </table>
    </div>

  </div>

  <!-- Map Section -->
  <div class="table-data">
      <div class="order">
          <div class="head">
              <h3>Map</h3>
          </div>
          <div id="map" style="width: 100%; height: 400px;"></div>
      </div>
  </div>
</main>

<script>
  // Global variables for the map
  const URLROOT = '<?php echo URLROOT; ?>';
  const factoryLocation = { 
      lat: <?php echo M_Route::FACTORY_LAT; ?>, 
      lng: <?php echo M_Route::FACTORY_LONG; ?> 
  };

  // Initialize the Google Map
  let map;
  function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
          zoom: 15,
          center: { lat: 6.2173037, lng: 80.2538636 } // Center of Sri Lanka
      });
      // Add factory marker
      new google.maps.Marker({
          position: factoryLocation,
          map: map,
          label: { text: "F", color: "white" },
          title: "Factory (Start)"
      });
      // Add markers for route suppliers if they have a valid location property
      <?php if(isset($data['route']->suppliers) && !empty($data['route']->suppliers)): ?>
          <?php foreach($data['route']->suppliers as $supplier): ?>
              <?php if(isset($supplier->location) && !empty($supplier->location)): ?>
                  new google.maps.Marker({
                      position: { 
                          lat: <?php echo $supplier->location['lat']; ?>, 
                          lng: <?php echo $supplier->location['lng']; ?> 
                      },
                      map: map,
                      label: { text: "<?php echo substr(htmlspecialchars($supplier->name), 0, 1); ?>", color: "white" },
                      title: "<?php echo htmlspecialchars($supplier->name); ?>"
                  });
              <?php endif; ?>
          <?php endforeach; ?>
      <?php endif; ?>
  }
  // Expose initMap so that the Google Maps API callback can call it
  window.initMap = initMap;
</script>

  <!-- =====================================================
       AJAX METHODS HERE
       ===================================================== -->

<script>

document.addEventListener("DOMContentLoaded", function () {
    const routeId = "<?php echo $data['route_id']; ?>";
    const routeDay = "<?php echo $data['day']; ?>"; // Get route day from PHP

    fetchUnallocatedSuppliers(routeDay);
    fetchRouteSuppliers(routeId);

    function fetchUnallocatedSuppliers(routeDay) {
        fetch('<?php echo URLROOT; ?>/route/getUnallocatedSuppliers/' + routeDay)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById("employee-status");
                select.innerHTML = ""; // Clear existing options
                
                if (data.success) {
                    data.data.forEach(supplier => {
                        let option = document.createElement("option");
                        option.value = supplier.supplier_id; // Use supplier_id as the value
                        option.textContent = supplier.full_name; // Use full_name for display
                        select.appendChild(option);
                    });
                } else {
                    console.error('Error fetching suppliers:', data.message);
                }
            })
            .catch(error => console.error('Error fetching unallocated suppliers:', error));
    }

    function updateMap(suppliers) {
        if (!map) {
            console.error("Map not initialized");
            return;
        }

        // Clear existing markers
        if (window.supplierMarkers) {
            window.supplierMarkers.forEach(marker => marker.setMap(null));
        }
        window.supplierMarkers = [];

        suppliers.forEach(supplier => {
            let marker = new google.maps.Marker({
                position: { lat: supplier.lat, lng: supplier.lng },
                map: map,
                label: { text: supplier.name.charAt(0), color: "white" },
                title: supplier.name
            });

            window.supplierMarkers.push(marker);
        });
    }


    function fetchRouteSuppliers(routeId) {
        fetch(URLROOT + '/route/getRouteSuppliers/' + routeId)
            .then(response => response.json())
            .then(suppliers => {
                const tableBody = document.getElementById("supplierTableBody");
                tableBody.innerHTML = ""; // Clear existing rows

                let supplierMarkers = []; // To store map markers
                let totalAverageCollection = 0; // Initialize total average collection

                suppliers.forEach(supplier => {
                    let row = `
                        <tr>
                            <td>${supplier.stop_order || 'N/A'}</td>
                            <td>${supplier.full_name}</td>
                            <td>${supplier.average_collection} kg</td>
                            <td>
                                <form action="<?php echo URLROOT; ?>/route/removeSupplier" method="POST" onsubmit="return confirm('Are you sure you want to remove this supplier?');">
                                    <input type="hidden" name="route_id" value="<?php echo htmlspecialchars($data['route_id']); ?>">
                                    <input type="hidden" name="supplier_id" value="${supplier.supplier_id}">
                                    <button type="submit" class="btn btn-tertiary">Remove</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;

                    // Add to total average collection
                    totalAverageCollection += parseFloat(supplier.average_collection) || 0; // Ensure it's a number

                    // Ensure coordinates exist
                    if (supplier.coordinates) {
                        let coords = supplier.coordinates.split(','); // "6.22132097, 80.24471090" → [6.22132097, 80.24471090]
                        if (coords.length === 2) {
                            let lat = parseFloat(coords[0].trim());
                            let lng = parseFloat(coords[1].trim());

                            supplierMarkers.push({ lat, lng, name: supplier.full_name });
                        }
                    }
                });

                // Update the map with the new supplier markers
                updateMap(supplierMarkers);

                // Calculate remaining capacity
                const vehicleCapacity = <?php echo $data['vehicleDetails']->capacity; ?>; // Get vehicle capacity from PHP
                const remainingCapacity = vehicleCapacity - totalAverageCollection;

                // Update the remaining capacity display
                document.getElementById("remaining-capacity-display").textContent = `${remainingCapacity.toFixed(2)} kg`; // Update the display
            })
            .catch(error => console.error('Error fetching route suppliers:', error));
    }

});

function confirmRemove() {
    const confirmed = confirm('Are you sure you want to remove this supplier?');
    console.log('Confirmation:', confirmed); // Log confirmation result
    return confirmed; // Return true to submit the form, false to prevent submission
}

</script>



<?php 
// Include additional CSS and external scripts
echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/route-management.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>';

require APPROOT . '/views/inc/components/footer.php'; 
?>
