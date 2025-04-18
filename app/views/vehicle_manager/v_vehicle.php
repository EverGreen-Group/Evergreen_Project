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
  <!-- Vehicle Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Vehicle Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/manager/createVehicle" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Add new vehicle
        </a>
    </div>


  <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-car'></i>
                <div class="stat-info">
                    <h3><?php echo $totalVehicles; ?></h3>
                    <p>Total Vehicles</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-wrench'></i>
                <div class="stat-info">
                    <h3><?php echo $totalMaintainance; ?></h3>
                    <p>In Maintainance</p>
                </div>
            </div>
        </li>

    </ul>

  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Search Filters</h3>
            <i class='bx bx-search'></i>
        </div>
        <div class="filter-options">
            <form action="/Evergreen_Project/manager/vehicle" method="GET">
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
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Maintenance">Maintenance</option>
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
            <h3>Vehicles</h3>
            <!-- <a href="<?php echo URLROOT; ?>/manager/createVehicle" class="btn btn-primary">
                <i class='bx bx-show'></i>
                View Removed Vehicles
            </a> -->
        </div>
        <table>
            <thead>
                <tr>
                    <th>Vehicle ID</th>
                    <th>License Plate</th>
                    <th>Capacity</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allVehicles as $vehicle): ?>
                    <tr class="vehicle-row" data-vehicle-id="<?php echo htmlspecialchars($vehicle->vehicle_id); ?>">
                        <td><?php echo htmlspecialchars($vehicle->vehicle_id); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->license_plate); ?></td>

                        <td><?php echo htmlspecialchars(floor($vehicle->capacity)) . ' kg'; ?></td>
                        <td><?php echo htmlspecialchars($vehicle->vehicle_type); ?></td>
                        <td>
                            <span class="status-badge <?php echo $vehicle->status == 'Active' ? 'added' : 'oranged'; ?>">
                                <?php echo $vehicle->status == 'Active' ? 'ACTIVE' : 'MAINTAINANCE'; ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <!-- View button -->
                                <a 
                                    href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo $vehicle->vehicle_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-show' style="font-size: 24px; color:blue;"></i> <!-- Boxicon for view -->
                                </a>

                                <!-- Manage button  -->
                                <a 
                                    href="<?php echo URLROOT; ?>/manager/updateVehicle/<?php echo $vehicle->vehicle_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-cog' style="font-size: 24px; color:green;"></i> <!-- Boxicon for settings -->
                                </a>

                                <!-- <a 
                                    href="<?php echo URLROOT; ?>/manager/sendToMaintenance/<?php echo $vehicle->vehicle_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    data-confirm="Send this vehicle to maintenance?"
                                >
                                    <i class='bx bx-wrench' style="font-size: 24px; color:orange;"></i>
                                </a> -->
                                
                                <!-- Delete button  -->
                                <a href="<?php echo URLROOT; ?>/manager/deleteVehicle/<?php echo $vehicle->vehicle_id; ?>" 
                                   class="btn btn-tertiary" 
                                   style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                   data-confirm="Do you want to delete this vehicle : <?php echo $vehicle->vehicle_id . ' | ' .  $vehicle->license_plate; ?>" 
                                   >
                                    <i class='bx bx-trash' style="font-size: 24px; color:red;"></i> <!-- Boxicon for trash -->
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

