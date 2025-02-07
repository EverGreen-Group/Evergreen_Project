<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/vehicle.js"></script>

<!-- MAIN -->
<main>
  <!-- Team Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Vehicle Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>


  <div class="action-buttons">
        <a href="#" id="openAddVehicleModal" class="btn btn-primary" onclick="openModal('addVehicleModal')">
            <i class='bx bx-plus'></i>
            Add a vehicle
        </a>
        <a href="#" id="openUpdateScheduleModal" class="btn btn-primary" onclick="openModal('updateVehicleModal')">
            <i class='bx bx-analyse'></i>
            Update Vehicle
        </a>
        <a href="#" class="btn btn-primary">
            <i class='bx bx-show'></i>
            View All Vehicles
        </a>
    </div>


  <ul class="box-info">
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
          <p>Total Vehicles</p>
          <h3><?php echo $totalVehicles; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-x'></i>
        <span class="text">
          <p>Available Vehicles</p>
          <h3><?php echo $availableVehicles; ?></h3>
        </span>
    </li>
  </ul>


  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Search Filters</h3>
            <i class='bx bx-search'></i>
        </div>
        <div class="filter-options">
            <form action="/Evergreen_Project/vehiclemanager/vehicle" method="GET">
                <div class="filter-group">
                    <label for="license-plate">License Plate:</label>
                    <input type="text" id="license-plate" name="license_plate" placeholder="Enter license plate">
                </div>
                <div class="filter-group">
                    <label for="vehicle-type">Vehicle Type:</label>
                    <select id="vehicle-type" name="vehicle_type">
                        <option value="">Select Vehicle Type</option>
                        <option value="Truck">Truck</option>
                        <option value="Van">Van</option>
                        <option value="Car">Car</option>
                        <option value="Bus">Bus</option>
                        <option value="Three-Wheeler">Three-Wheeler</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="capacity">Capacity:</label>
                    <input type="number" id="capacity" name="capacity" placeholder="Enter capacity" step="0.01">
                </div>
                <div class="filter-group">
                    <label for="make">Make:</label>
                    <input type="text" id="make" name="make" placeholder="Enter vehicle make">
                </div>
                <div class="filter-group">
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model" placeholder="Enter vehicle model">
                </div>
                <div class="filter-group">
                    <label for="manufacturing-year">Manufacturing Year:</label>
                    <input type="number" id="manufacturing-year" name="manufacturing_year" placeholder="Enter year" min="1900" max="2100">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>




  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Filtered Vehicles</h3>
            <button class="filter-btn">
                <i class='bx bx-filter'></i>
                Filter availability by day
            </button>
        </div>
        <div class="vehicle-grid">
            <?php if (!empty($vehicles)): ?>
                <?php foreach ($vehicles as $vehicle): ?>
                    <div class="vehicle-card" 
                         data-license-plate="<?php echo htmlspecialchars($vehicle->license_plate); ?>"
                         data-vehicle-type="<?php echo htmlspecialchars($vehicle->vehicle_type); ?>"
                         data-status="<?php echo htmlspecialchars($vehicle->status); ?>"
                         data-capacity="<?php echo htmlspecialchars($vehicle->capacity); ?>"
                         data-make="<?php echo htmlspecialchars($vehicle->make); ?>"
                         data-model="<?php echo htmlspecialchars($vehicle->model); ?>"
                         data-manufacturing-year="<?php echo htmlspecialchars($vehicle->manufacturing_year); ?>"
                         data-color="<?php echo htmlspecialchars($vehicle->color); ?>"
                         onclick="updateVehicleDetails(this)">
                        <img src="<?php echo URLROOT; ?>/uploads/vehicle_photos/<?php echo htmlspecialchars($vehicle->license_plate); ?>.jpg" alt="<?php echo htmlspecialchars($vehicle->make . ' ' . $vehicle->model); ?>">
                        <div class="card-content">
                            <div class="card-title">
                                <h4><?php echo htmlspecialchars($vehicle->license_plate); ?></h4>
                                <button class="bookmark-btn">
                                    <i class='bx bx-bookmark'></i>
                                </button>
                            </div>
                            <div class="vehicle-specs">
                                <div class="spec">
                                    <span class="spec-label">Make:</span>
                                    <span class="spec-value"><?php echo htmlspecialchars($vehicle->make); ?></span>
                                </div>
                                <div class="spec">
                                    <span class="spec-label">Model:</span>
                                    <span class="spec-value"><?php echo htmlspecialchars($vehicle->model); ?></span>
                                </div>
                                <div class="spec">
                                    <span class="spec-label">Color:</span>
                                    <span class="spec-value"><?php echo htmlspecialchars($vehicle->color); ?></span>
                                </div>
                            </div>
                            <div class="capacity"><?php echo htmlspecialchars($vehicle->capacity); ?> kg</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No vehicles found matching your criteria.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Vehicle Details Section -->
    <div class="order vehicle-details">
        <div class="head">
            <h3>Vehicle Details</h3>
        </div>
        <div class="details-container">
            <?php if (!empty($vehicles)): ?>
                <?php $firstVehicle = $vehicles[0]; // Display details of the first vehicle as an example ?>
                <div class="vehicle-image">
                    <img id="vehicleImage" src="<?php echo URLROOT; ?>/uploads/vehicle_photos/<?php echo htmlspecialchars($firstVehicle->license_plate); ?>.jpg" alt="<?php echo htmlspecialchars($firstVehicle->make . ' ' . $firstVehicle->model); ?>">
                </div>
                
                <div class="details-content">
                    <div class="details-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">License Plate:</span>
                                <span class="value" data-label="license-plate"><?php echo htmlspecialchars($firstVehicle->license_plate); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Vehicle Type:</span>
                                <span class="value" data-label="vehicle-type"><?php echo htmlspecialchars($firstVehicle->vehicle_type); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status:</span>
                                <span class="value status-available" data-label="status"><?php echo htmlspecialchars($firstVehicle->status); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="details-section">
                        <h4 class="section-title">Specifications</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Capacity:</span>
                                <span class="value" data-label="capacity"><?php echo htmlspecialchars($firstVehicle->capacity); ?> kg</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Make:</span>
                                <span class="value" data-label="make"><?php echo htmlspecialchars($firstVehicle->make); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Model:</span>
                                <span class="value" data-label="model"><?php echo htmlspecialchars($firstVehicle->model); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Manufacturing Year:</span>
                                <span class="value" data-label="manufacturing-year"><?php echo htmlspecialchars($firstVehicle->manufacturing_year); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Color:</span>
                                <span class="value" data-label="color"><?php echo htmlspecialchars($firstVehicle->color); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Vehicle Button -->
                    <div class="remove-vehicle">
                        <form action="<?php echo URLROOT; ?>/vehiclemanager/removeVehicle" method="POST" onsubmit="return confirm('Are you sure you want to remove this vehicle?');">
                            <input type="hidden" name="license_plate" value="<?php echo htmlspecialchars($firstVehicle->license_plate); ?>">
                            <button type="submit" class="btn btn-danger" style="width:100%;">Remove Vehicle</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <p>No vehicle details available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>





  <!-- Add Vehicle Modal -->
<div id="addVehicleModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addVehicleModal')">&times;</span>
        <h2>Add New Vehicle</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <form id="createVehicleForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/addVehicle" enctype="multipart/form-data">
                    <!-- Vehicle Information -->
                    <div class="detail-group">
                        <h3>Vehicle Information</h3>
                        <div class="specifications-container">
                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">License Plate:</span>
                                    <span class="value">
                                        <input type="text" id="license_plate" name="license_plate" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Vehicle Type:</span>
                                    <span class="value">
                                        <select id="vehicle_type" name="vehicle_type" class="form-control" required>
                                            <option value="">Select Vehicle Type</option>
                                            <option value="Truck">Truck</option>
                                            <option value="Van">Van</option>
                                            <option value="Car">Car</option>
                                            <option value="Bus">Bus</option>
                                            <option value="Three-Wheeler">Three-Wheeler</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Make:</span>
                                    <span class="value">
                                        <input type="text" id="make" name="make" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Model:</span>
                                    <span class="value">
                                        <input type="text" id="model" name="model" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Manufacturing Year:</span>
                                    <span class="value">
                                        <input type="number" id="manufacturing_year" name="manufacturing_year" class="form-control" min="1900" max="2100" required>
                                    </span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">Color:</span>
                                    <span class="value">
                                        <input type="text" id="color" name="color" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Capacity:</span>
                                    <span class="value">
                                        <input type="number" id="capacity" name="capacity" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Upload Image:</span>
                                    <span class="value">
                                        <input type="file" id="vehicle_image" name="vehicle_image" class="form-control" accept="image/*" required>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary full-width">ADD VEHICLE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Vehicle Modal -->
<div id="updateVehicleModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('updateVehicleModal')">&times;</span>
        <h2>Update Vehicle</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <form id="updateVehicleForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/updateVehicle" enctype="multipart/form-data">
                    <!-- Vehicle Selection -->
                    <div class="vehicle-selection">
                        <h3>Select Vehicle to Update</h3>
                        <select id="vehicleSelect" class="form-control" onchange="populateUpdateModal(this.value)">
                            <option value="">Select a Vehicle</option>
                            <?php foreach ($data['vehicles'] as $vehicle): ?>
                                <option value="<?php echo htmlspecialchars($vehicle->license_plate); ?>"
                                        data-vehicle-type="<?php echo htmlspecialchars($vehicle->vehicle_type); ?>"
                                        data-make="<?php echo htmlspecialchars($vehicle->make); ?>"
                                        data-model="<?php echo htmlspecialchars($vehicle->model); ?>"
                                        data-manufacturing-year="<?php echo htmlspecialchars($vehicle->manufacturing_year); ?>"
                                        data-color="<?php echo htmlspecialchars($vehicle->color); ?>"
                                        data-capacity="<?php echo htmlspecialchars($vehicle->capacity); ?>">
                                    <?php echo htmlspecialchars($vehicle->license_plate); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="detail-group">
                        <h3>Vehicle Information</h3>
                        <div class="specifications-container">
                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">License Plate:</span>
                                    <span class="value">
                                        <input type="text" id="update_license_plate" name="license_plate" class="form-control" required readonly>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Vehicle Type:</span>
                                    <span class="value">
                                        <select id="update_vehicle_type" name="vehicle_type" class="form-control" required>
                                            <option value="">Select Vehicle Type</option>
                                            <option value="Truck">Truck</option>
                                            <option value="Van">Van</option>
                                            <option value="Car">Car</option>
                                            <option value="Bus">Bus</option>
                                            <option value="Three-Wheeler">Three-Wheeler</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Make:</span>
                                    <span class="value">
                                        <input type="text" id="update_make" name="make" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Model:</span>
                                    <span class="value">
                                        <input type="text" id="update_model" name="model" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Manufacturing Year:</span>
                                    <span class="value">
                                        <input type="number" id="update_manufacturing_year" name="manufacturing_year" class="form-control" min="1900" max="2100" required>
                                    </span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">Color:</span>
                                    <span class="value">
                                        <input type="text" id="update_color" name="color" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Capacity:</span>
                                    <span class="value">
                                        <input type="number" id="update_capacity" name="capacity" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Upload New Image:</span>
                                    <span class="value">
                                        <input type="file" id="update_vehicle_image" name="vehicle_image" class="form-control" accept="image/*">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary full-width">UPDATE VEHICLE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.modal-select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
    font-size: 14px;
}

.modal-select:focus {
    outline: none;
    border-color: #3C91E6;
    box-shadow: 0 0 0 2px rgba(60, 145, 230, 0.1);
}
</style>

<script>
function updateVehicleDetails(card) {
    // Get data attributes from the clicked card
    const licensePlate = card.getAttribute('data-license-plate');
    const vehicleType = card.getAttribute('data-vehicle-type');
    const status = card.getAttribute('data-status');
    const capacity = card.getAttribute('data-capacity');
    const make = card.getAttribute('data-make');
    const model = card.getAttribute('data-model');
    const manufacturingYear = card.getAttribute('data-manufacturing-year');
    const color = card.getAttribute('data-color');

    // Update the vehicle details section
    document.querySelector('.vehicle-details .value[data-label="license-plate"]').textContent = licensePlate;
    document.querySelector('.vehicle-details .value[data-label="vehicle-type"]').textContent = vehicleType;
    document.querySelector('.vehicle-details .value[data-label="status"]').textContent = status;
    document.querySelector('.vehicle-details .value[data-label="capacity"]').textContent = capacity + ' kg';
    document.querySelector('.vehicle-details .value[data-label="make"]').textContent = make;
    document.querySelector('.vehicle-details .value[data-label="model"]').textContent = model;
    document.querySelector('.vehicle-details .value[data-label="manufacturing-year"]').textContent = manufacturingYear;
    document.querySelector('.vehicle-details .value[data-label="color"]').textContent = color;

    // Update the vehicle image
    const vehicleImage = document.getElementById('vehicleImage');
    vehicleImage.src = "<?php echo URLROOT; ?>/uploads/vehicle_photos/" + licensePlate + ".jpg";
    vehicleImage.alt = make + ' ' + model; // Update alt text for accessibility
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function populateUpdateModal(licensePlate) {
    const selectedOption = document.querySelector(`#vehicleSelect option[value="${licensePlate}"]`);
    
    if (selectedOption) {
        // Get data attributes from the selected option
        const vehicleType = selectedOption.getAttribute('data-vehicle-type');
        const make = selectedOption.getAttribute('data-make');
        const model = selectedOption.getAttribute('data-model');
        const manufacturingYear = selectedOption.getAttribute('data-manufacturing-year');
        const color = selectedOption.getAttribute('data-color');
        const capacity = selectedOption.getAttribute('data-capacity');

        // Populate the modal fields
        document.getElementById('update_license_plate').value = licensePlate;
        document.getElementById('update_vehicle_type').value = vehicleType;
        document.getElementById('update_make').value = make;
        document.getElementById('update_model').value = model;
        document.getElementById('update_manufacturing_year').value = manufacturingYear;
        document.getElementById('update_color').value = color;
        document.getElementById('update_capacity').value = capacity;

        // Open the update vehicle modal
        openModal('updateVehicleModal');
    }
}
</script>
</main>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>

