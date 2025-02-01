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

  <?php print_r($data); ?>

  <div class="action-buttons">
        <a href="#" id="openCreateScheduleModal" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Add a vehicle
        </a>
        <a href="#" id="openUpdateScheduleModal" class="btn btn-primary">
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
            <form action="/Evergreen_Project/vehiclemanager/vehicle" method="GET"> <!-- Correct action URL -->
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
                        <img src="https://winwin.lk/images/f60f70/16753901752224530.jpg" alt="<?php echo htmlspecialchars($vehicle->make . ' ' . $vehicle->model); ?>">
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
                    <img src="https://winwin.lk/images/f60f70/16753901752224530.jpg" alt="<?php echo htmlspecialchars($firstVehicle->make . ' ' . $firstVehicle->model); ?>">
                </div>
                
                <div class="details-content">
                    <div class="details-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">License Plate:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->license_plate); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Vehicle Type:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->vehicle_type); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status:</span>
                                <span class="value status-available"><?php echo htmlspecialchars($firstVehicle->status); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="details-section">
                        <h4 class="section-title">Specifications</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Capacity:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->capacity); ?> kg</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Make:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->make); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Model:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->model); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Manufacturing Year:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->manufacturing_year); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Color:</span>
                                <span class="value"><?php echo htmlspecialchars($firstVehicle->color); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p>No vehicle details available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>





  <!-- Add Driver Modal -->
<div id="addDriverModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addDriverModal')">&times;</span>
        <h2>Add New Driver</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <form id="createDriverForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/addDriver">
                    <!-- User Selection -->
                    <div class="detail-group">
                        <h3>Select User</h3>
                        <div class="detail-row">
                            <span class="label">User:</span>
                            <span class="value">
                                <select id="userSelect" name="user_id" class="form-control modal-select" required>
                                    <option value="">Select a User</option>
                                    <?php foreach ($data['users'] as $user): ?>
                                        <option value="<?= $user->user_id ?>" 
                                                data-first-name="<?= htmlspecialchars($user->first_name) ?>"
                                                data-last-name="<?= htmlspecialchars($user->last_name) ?>"
                                                data-email="<?= htmlspecialchars($user->email) ?>"
                                                data-nic="<?= htmlspecialchars($user->nic) ?>"
                                                data-dob="<?= $user->date_of_birth ?>"
                                                data-gender="<?= $user->gender ?>">
                                            <?= htmlspecialchars($user->first_name . ' ' . $user->last_name . ' (' . $user->nic . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>

                    <!-- General Information -->
                    <div class="detail-group">
                        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                            <h3>General Information</h3>
                        </div>

                        <!-- User icon centered in its own detail-row -->
                        <div class="detail-row" style="justify-content: center; margin-bottom: 20px;">
                            <i class='bx bx-user' style="font-size: 2em; color: #3C91E6;"></i>
                        </div>
                        <div class="specifications-container">

                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">First Name:</span>
                                    <span class="value" id="firstName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Last Name:</span>
                                    <span class="value" id="lastName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Email:</span>
                                    <span class="value" id="email"></span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">NIC:</span>
                                    <span class="value" id="nic"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Date of Birth:</span>
                                    <span class="value" id="dateOfBirth"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Gender:</span>
                                    <span class="value" id="gender"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Information -->
                    <div class="detail-group">
                        <h3>Driver Information</h3>
                        <div class="specifications-container">
                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">Address Line 1:</span>
                                    <span class="value">
                                        <input type="text" id="address_line1" name="address_line1" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Address Line 2:</span>
                                    <span class="value">
                                        <input type="text" id="address_line2" name="address_line2" class="form-control">
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">City:</span>
                                    <span class="value">
                                        <input type="text" id="city" name="city" class="form-control" required>
                                    </span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">Contact Number:</span>
                                    <span class="value">
                                        <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Emergency Contact:</span>
                                    <span class="value">
                                        <input type="text" id="emergency_contact" name="emergency_contact" class="form-control" required>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary full-width">ADD DRIVER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Driver Modal -->
<div id="updateDriverModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('updateDriverModal')">&times;</span>
        <h2>Update Driver</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <form id="updateDriverForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/updateDriver">
                    <!-- User Selection -->
                    <div class="detail-group">
                        <h3>Select User</h3>
                        <div class="detail-row">
                            <span class="label">User:</span>
                            <span class="value">
                                <select id="updateUserSelect" name="user_id" class="form-control modal-select" required>
                                    <option value="">Select a User</option>
                                    <?php foreach ($data['update_users'] as $user): ?>
                                        <option value="<?= $user->user_id ?>" 
                                                data-first-name="<?= htmlspecialchars($user->first_name) ?>"
                                                data-last-name="<?= htmlspecialchars($user->last_name) ?>"
                                                data-email="<?= htmlspecialchars($user->email) ?>"
                                                data-nic="<?= htmlspecialchars($user->nic) ?>"
                                                data-dob="<?= $user->date_of_birth ?>"
                                                data-gender="<?= $user->gender ?>">
                                            <?= htmlspecialchars($user->first_name . ' ' . $user->last_name . ' (' . $user->nic . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>

                    <!-- General Information -->
                    <div class="detail-group">
                        <div class="detail-row" style="justify-content: center; margin-bottom: 20px;">
                            <i class='bx bx-user' style="font-size: 2em; color: #3C91E6;"></i>
                        </div>
                        <h3>General Information</h3>
                        <div class="specifications-container">
                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">First Name:</span>
                                    <span class="value" id="updateFirstName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Last Name:</span>
                                    <span class="value" id="updateLastName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Email:</span>
                                    <span class="value" id="updateEmail"></span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">NIC:</span>
                                    <span class="value" id="updateNic"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Date of Birth:</span>
                                    <span class="value" id="updateDateOfBirth"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Gender:</span>
                                    <span class="value" id="updateGender"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Information -->
                    <div class="detail-group">
                        <h3>Driver Information</h3>
                        <div class="specifications-container">
                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">Address Line 1:</span>
                                    <span class="value">
                                        <input type="text" id="updateAddressLine1" name="address_line1" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Address Line 2:</span>
                                    <span class="value">
                                        <input type="text" id="updateAddressLine2" name="address_line2" class="form-control">
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">City:</span>
                                    <span class="value">
                                        <input type="text" id="updateCity" name="city" class="form-control" required>
                                    </span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">Contact Number:</span>
                                    <span class="value">
                                        <input type="text" id="updateContactNumber" name="contact_number" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Emergency Contact:</span>
                                    <span class="value">
                                        <input type="text" id="updateEmergencyContact" name="emergency_contact" class="form-control" required>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary full-width">UPDATE DRIVER</button>
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
}
</script>
</main>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>

