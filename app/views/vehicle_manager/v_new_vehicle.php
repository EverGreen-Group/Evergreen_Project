<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
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
          <h1>Driver Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
      <button class="btn btn-primary" onclick="document.getElementById('addDriverModal').style.display='block'">
          <i class='bx bx-plus'></i>
          Add New Vehicle
      </button>

      <button class="btn btn-primary"
          onclick="document.getElementById('updateDriverModal').style.display='block'" 
          class="btn btn-secondary"
          style="padding: 6px 12px; border: none;"
      >
          Update Existing Vehicle
      </button>
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


  <!-- First Row: Two Charts -->
  <div class="table-data">
    <!-- Report Types Chart -->
    <div class="order">
        <div class="head">
            <h3>Vehicle Usage</h3>
        </div>
        <div class="chart-container-wrapper" style="height: 250px;">
            <canvas id="reportTypesChart"></canvas>
        </div>
        <div class="color-legend">
            <div><span class="legend-color" style="background-color: #FF6384;"></span> Collection Mismatches</div>
            <div><span class="legend-color" style="background-color: #36A2EB;"></span> Delivery Issues</div>
            <div><span class="legend-color" style="background-color: #FFCE56;"></span> Supplier Complaints</div>
            <div><span class="legend-color" style="background-color: #4BC0C0;"></span> Driver Reports</div>
        </div>
    </div>

    <!-- Driver Status Chart -->
    <div class="order">
        <div class="head">
            <h3>Vehicle Allocation</h3>
        </div>
        <div class="chart-container-wrapper" style="height: 250px;">
            <canvas id="driverStatusChart"></canvas>
        </div>
        <div class="color-legend">
            <div><span class="legend-color" style="background-color: #FF9F40;"></span> Unassigned</div>
            <div><span class="legend-color" style="background-color: #4BC0C0;"></span> Available</div>
            <div><span class="legend-color" style="background-color: #36A2EB;"></span> On Delivery</div>
            <div><span class="legend-color" style="background-color: #9966FF;"></span> On Collection</div>
        </div>
    </div>
  </div>


  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Vehicles Currently In Use</h3>
            <i class='bx bx-shopping-bag'></i>
        </div>
        <div class="bags-grid">
            <!-- Vehicle Card 1 -->
            <div class="bag-card" onclick="showVehicleDetails()">
                <div class="bag-icon">
                    <img src="https://winwin.lk/images/f60f70/16753901752224530.jpg" alt="Vehicle Image" class="vehicle-image">
                </div>
                <div class="bag-info">
                    <h4>WP-1234</h4>
                    <span class="status processing">Delivery</span>
                </div>
            </div>

            <!-- Vehicle Card 2 -->
            <div class="bag-card" onclick="showVehicleDetails()">
                <div class="bag-icon">
                    <img src="https://i.ikman-st.com/tata-dimo-batta-2010-for-sale-kurunegala-558/de991e55-8b07-4820-bd8d-0e6c6f21d356/620/466/fitted.jpg" alt="Vehicle Image" class="vehicle-image">
                </div>
                <div class="bag-info">
                    <h4>WP-2345</h4>
                    <span class="status used">Collection</span>
                </div>
            </div>

            <!-- Vehicle Card 3 -->
            <div class="bag-card" onclick="showVehicleDetails()">
                <div class="bag-icon">
                    <img src="https://i.ikman-st.com/tata-dimo-batta-2011-for-sale-kurunegala-620/4d26812b-bd57-4a63-b66d-5cd3bec804f4/620/466/fitted.jpg" alt="Vehicle Image" class="vehicle-image">
                </div>
                <div class="bag-info">
                    <h4>WP-2134</h4>
                    <span class="status used">Collection</span>
                </div>
            </div>

            <!-- Add more vehicle cards as needed -->
        </div>
    </div>
</div>


  <!-- Vehicle Information Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Vehicle Availability</h3>
        <div class="filter-container">
                    <label for="dayFilter">Filter by Day:</label>
                    <select id="dayFilter">
                        <option value="all">All Days</option>
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
            <th>ID</th>
            <th>Plate Number</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if(isset($data['vehicles']) && !empty($data['vehicles'])): ?>
            <?php foreach($data['vehicles'] as $vehicle): ?>
                <tr>
                    <td><?php echo $vehicle->vehicle_id; ?></td>
                    <td><?php echo $vehicle->license_plate; ?></td>
                    <td><?php echo $vehicle->vehicle_type; ?></td>
                    <td><?php echo $vehicle->capacity; ?> Tons</td>
                    
                    <td>
                        <span class="status <?php 
                            echo $vehicle->status === 'Available' ? 'pending' : 
                                ($vehicle->status === 'In Use' ? 'process' : 'completed'); 
                        ?>">
                            <?php echo $vehicle->status; ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr>
                  <td colspan="9" class="text-center">No vehicles found</td>
              </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<!-- View Vehicle Modal -->
<div id="viewVehicleModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewVehicleModal')">&times;</span>
        <h2>View Vehicle Details</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-image">
                <img src="https://winwin.lk/images/f60f70/16753901752224530.jpg" alt="Vehicle Image" class="vehicle-image" />
            </div>
            <div class="vehicle-modal-details">
                <div class="detail-group">
                    <h3>Basic Information</h3>
                    <div class="detail-row">
                        <span class="label">License Plate:</span>
                        <span class="value">WP-1234</span> <!-- Ensure this element exists -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Vehicle Type:</span>
                        <span class="value">TRUCK</span> <!-- Ensure this element exists -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value">AVAILABLE</span> <!-- Ensure this element exists -->
                    </div>
                </div>
                <div class="detail-group">
                    <h3>Specifications</h3>
                    <div class="detail-row">
                        <span class="label">Capacity:</span>
                        <span class="value">2500 kg</span> <!-- Ensure this element exists -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Make:</span>
                        <span class="value">Toyota</span> <!-- Ensure this element exists -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Model:</span>
                        <span class="value">Dyna</span> <!-- Ensure this element exists -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Manufacturing Year:</span>
                        <span class="value">2018</span> <!-- Ensure this element exists -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Color:</span>
                        <span class="value">Blue</span> <!-- Ensure this element exists -->
                    </div>
                </div>
            </div>
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
</main>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>

