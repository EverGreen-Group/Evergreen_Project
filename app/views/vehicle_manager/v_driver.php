<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/vehicle.js"></script>

<!-- MAIN -->
<main>


  <!-- Driver Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Driver Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/manager/createDriver" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Add new driver
        </a>
    </div>

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-user'></i>
                <div class="stat-info">
                    <h3><?php echo $total_drivers; ?></h3>
                    <p>Total Drivers</p>
                </div>
            </div>
        </li>

        <!-- <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-check'></i>
                <div class="stat-info">
                    <h3><?php echo $on_duty_drivers; ?></h3>
                    <p>Currently On Duty</p>
                </div>
            </div>
        </li> -->

    </ul>

  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Search Filters</h3>
            <i class='bx bx-search'></i>
        </div>
        <div class="filter-options">
            <form action="<?php echo URLROOT; ?>/manager/driver" method="GET">
                <div class="filter-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter driver name">
                </div>
                <div class="filter-group">
                    <label for="nic">NIC:</label>
                    <input type="text" id="nic" name="nic" placeholder="Enter NIC">
                </div>
                <div class="filter-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" placeholder="Enter contact number">
                </div>
                <div class="filter-group">
                    <label for="license_number">License Number:</label>
                    <input type="text" id="license_number" name="license_number" placeholder="Enter license number">
                </div>
                <div class="filter-group">
                    <label for="status">Status:</label>
                    <select id="status" name="driver_status">
                        <option value="">Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>

<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Drivers</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Driver ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>NIC</th>
                    <th>Contact Number</th>
                    <th>License Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drivers as $driver): ?>
                    <tr class="driver-row" data-driver-id="<?php echo htmlspecialchars($driver->driver_id); ?>">
                        <td><?php echo htmlspecialchars($driver->driver_id); ?></td>
                        <td><?php echo htmlspecialchars($driver->first_name . ' ' . $driver->last_name); ?></td>
                        <td><?php echo htmlspecialchars($driver->email); ?></td>
                        <td><?php echo htmlspecialchars($driver->nic); ?></td>
                        <td><?php echo htmlspecialchars($driver->contact_number); ?></td>
                        <td><?php echo htmlspecialchars($driver->license_number); ?></td>
                        <td>
                                    <span class="status-badge <?php echo $driver->status == 'Active' ? 'added' : 'removed'; ?>">
                                        <?php echo $driver->status; ?>
                                    </span>
                                </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <!-- View button with icon -->
                                <a 
                                    href="<?php echo URLROOT; ?>/manager/viewDriver/<?php echo $driver->driver_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-show' style="font-size: 24px; color:blue;"></i>
                                </a>

                                <!-- Manage button with icon only -->
                                <a 
                                    href="<?php echo URLROOT; ?>/manager/updateDriver/<?php echo $driver->driver_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-pencil' style="font-size: 24px; color:green;"></i>
                                </a>
                                
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
