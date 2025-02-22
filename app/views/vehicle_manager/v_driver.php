<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/driver.js"></script>

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
          Add New Driver
      </button>

      <button class="btn btn-primary"
          onclick="document.getElementById('updateDriverModal').style.display='block'" 
          class="btn btn-secondary"
          style="padding: 6px 12px; border: none;"
      >
          Update Existing Driver
      </button>
  </div>


  <ul class="box-info">
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
          <p>Total Drivers</p>
          <h3><?php echo $total_drivers; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-check'></i>
        <span class="text">
          <p>On Duty</p>
          <h3><?php echo $on_duty_drivers; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-x'></i>
        <span class="text">
          <p>Unassigned Drivers</p>
          <h3><?php echo $unassigned_drivers_count; ?></h3>
        </span>
    </li>
  </ul>


  <!-- First Row: Two Charts -->
  <div class="table-data">
    <!-- Report Types Chart -->
    <div class="order">
        <div class="head">
            <h3>Monthly Driver Report Types</h3>
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
            <h3>Driver Status Distribution</h3>
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

  <!-- Second Row: Reports Table -->
  <div class="table-data">
    <div class="order" style="width: 100%;"> <!-- Added full width -->
        <div class="head">
            <h3>Driver Reports</h3>
            <i class='bx bx-file'></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Type</th>
                    <th>Reporter</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample Reports -->
                <tr>
                    <td>REP001</td>
                    <td>
                        <span class="report-type collection">Collection Mismatch</span>
                    </td>
                    <td>John (Inventory Manager)</td>
                    <td><?php echo date('Y-m-d'); ?></td>
                    <td><span class="status pending">Pending</span></td>
                    <td>
                        <button class="btn btn-primary" onclick="viewReportDetails(1)">
                            <i class='bx bx-show'></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>REP002</td>
                    <td>
                        <span class="report-type delivery">Delivery Delay</span>
                    </td>
                    <td>Factory Manager</td>
                    <td><?php echo date('Y-m-d', strtotime('-2 days')); ?></td>
                    <td><span class="status completed">Resolved</span></td>
                    <td>
                        <button class="btn btn-primary" onclick="viewReportDetails(2)">
                            <i class='bx bx-show'></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>REP003</td>
                    <td>
                        <span class="report-type supplier">Supplier Complaint</span>
                    </td>
                    <td>Sarah (Supplier)</td>
                    <td><?php echo date('Y-m-d', strtotime('-1 day')); ?></td>
                    <td><span class="status error">Urgent</span></td>
                    <td>
                        <button class="btn btn-primary" onclick="viewReportDetails(3)">
                            <i class='bx bx-show'></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>REP004</td>
                    <td>
                        <span class="report-type driver">Driver Report</span>
                    </td>
                    <td>Pam (Driver)</td>
                    <td><?php echo date('Y-m-d', strtotime('-3 days')); ?></td>
                    <td><span class="status process">In Progress</span></td>
                    <td>
                        <button class="btn btn-primary" onclick="viewReportDetails(4)">
                            <i class='bx bx-show'></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>

  <!-- Section for All Drivers -->
  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>All Drivers</h3>
            <div class="filter-container">
                <label for="drivers-status-filter">Filter by Status:</label>
                <select id="drivers-status-filter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="On Leave">On Leave</option>
                </select>
            </div>
            <i class='bx bx-search'></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>License</th>
                    <th>Experience</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['all_drivers'] as $driver): ?>
                    <tr class="driver-row" data-driver-id="<?php echo htmlspecialchars($driver->driver_id); ?>">
                        <td>DR<?php echo str_pad($driver->driver_id, 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($driver->driver_name); ?></td>
                        <td><?php echo htmlspecialchars($driver->contact_number); ?></td>
                        <td><?php echo htmlspecialchars($driver->license_number ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($driver->experience ?? '0'); ?> years</td>
                        <td>
                            <span class="status <?php echo strtolower($driver->status) === 'active' ? 'error' : 'completed'; ?>">
                                <?php echo htmlspecialchars($driver->status); ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <!-- View Profile Button - Changed to button style -->
                                <button 
                                    onclick="showDriverProfile()" 
                                    class="btn btn-secondary"
                                    style="padding: 6px 12px; border: none;"
                                >
                                    Profile
                                </button>
                                
                                
                                <!-- Remove Driver Form -->
                                <form action="<?php echo URLROOT; ?>/vehiclemanager/removeDriver/<?php echo $driver->user_id; ?>" 
                                      method="POST" 
                                      style="margin: 0;" 
                                      onsubmit="return confirm('Are you sure you want to remove this driver?');">
                                    <button type="submit" class="btn btn-tertiary" style="padding: 6px 12px;">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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


<script>

function showDriverProfile() {
  // Show the modal
  document.getElementById("viewDriverProfileModal").style.display = "block";
}
</script>

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

<!-- View Driver Profile Modal -->
<div id="viewDriverProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewDriverProfileModal')">&times;</span>
        <h2>Driver Profile</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <div class="detail-group">
                    <h3>Personal Information</h3>
                    <div class="profile-header">
                        <div class="profile-image">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Driver Profile Image">
                        </div>
                        <div class="profile-info">
                            <div class="name-row">
                            <span id="driverFirstName">John</span>
                            <span id="driverLastName">Doe</span>
                            </div>
                            <div class="detail-row">
                            <span class="label">Email:</span>
                            <span class="value" id="driverEmail">john.doe@example.com</span>
                            </div>
                            <div class="detail-row">
                            <span class="label">Phone:</span>
                            <span class="value" id="driverPhone">123-456-7890</span>
                            </div>
                        </div>
                        </div>
                </div>

                <div class="detail-group">
                    <h3>Driver Address</h3>
                    <div class="detail-row">
                        <span class="label">Address Line 1:</span>
                        <span class="value" id="driverAddressLine1">123 Main St</span> <!-- Hardcoded -->
                    </div>
                    <div class="detail-row">
                        <span class="label">Address Line 2:</span>
                        <span class="value" id="driverAddressLine2">Apt 4B</span> <!-- Hardcoded -->
                    </div>
                    <div class="detail-row">
                        <span class="label">City:</span>
                        <span class="value" id="driverCity">New York</span> <!-- Hardcoded -->
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Past Collections</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Collection ID</th>
                                <th>Vehicle</th>
                                <th>Shift Start</th>
                                <th>Shift End</th>
                                <th>Status</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody id="pastCollections">
                            <tr>
                                <td>COL001</td>
                                <td>Vehicle 1</td>
                                <td>08:00 AM</td>
                                <td>10:00 AM</td>
                                <td>Completed</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>COL002</td>
                                <td>Vehicle 2</td>
                                <td>09:00 AM</td>
                                <td>11:00 AM</td>
                                <td>Completed</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>COL003</td>
                                <td>Vehicle 3</td>
                                <td>10:00 AM</td>
                                <td>12:00 PM</td>
                                <td>Pending</td>
                                <td>2 hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="detail-group">
                    <h3>Driver Statistics</h3>
                    <div class="driver-stats">
                        <!-- <div class="stat-item">
                            <span class="label">Date Joined:</span>
                            <span class="value">January 1, 2020</span> 
                        </div> -->
                        <div class="stat-item">
                            <span class="label">Collections Completed:</span>
                            <span class="value">50</span> <!-- Hardcoded -->
                        </div>
                        <div class="stat-item">
                            <span class="label">Reports Received:</span>
                            <span class="value">2</span> <!-- Hardcoded -->
                        </div>
                        <div class="stat-item">
                            <span class="label">Deliveries Completed:</span>
                            <span class="value">45</span> <!-- Hardcoded -->
                        </div>
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Past Deliveries</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Delivery ID</th>
                                <th>Vehicle</th>
                                <th>Delivery Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="pastDeliveries">
                            <tr>
                                <td>DEL001</td>
                                <td>Vehicle 1</td>
                                <td>12:00 PM</td>
                                <td>Delivered</td>
                            </tr>
                            <tr>
                                <td>DEL002</td>
                                <td>Vehicle 2</td>
                                <td>01:00 PM</td>
                                <td>Delivered</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="detail-group">
                    <h3>Reports</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Report ID</th>
                                <th>Date</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="driverReports">
                            <tr>
                                <td>REP001</td>
                                <td>March 1, 2021</td>
                                <td>No issues reported</td>
                            </tr>
                            <tr>
                                <td>REP002</td>
                                <td>April 15, 2021</td>
                                <td>Minor issues reported</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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

