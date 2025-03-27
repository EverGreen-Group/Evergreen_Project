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
        <a href="<?php echo URLROOT; ?>/vehiclemanager/createVehicle" class="btn btn-primary">
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
                <i class='bx bx-check'></i>
                <div class="stat-info">
                    <h3><?php echo $availableVehicles; ?></h3>
                    <p>Currently Available</p>
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
            <h3>Vehicles</h3>
            <a href="<?php echo URLROOT; ?>/vehiclemanager/createVehicle" class="btn btn-primary">
                <i class='bx bx-show'></i>
                View Inactive Vehicles
            </a>
            <a href="<?php echo URLROOT; ?>/vehiclemanager/createVehicle" class="btn btn-primary">
                <i class='bx bx-show'></i>
                View Deleted Vehicles
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Vehicle ID</th>
                    <th>License Plate</th>
                    <th>Status</th>
                    <th>Capacity</th>
                    <th>Type</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Manufacturing Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allVehicles as $vehicle): ?>
                    <tr class="vehicle-row" data-vehicle-id="<?php echo htmlspecialchars($vehicle->vehicle_id); ?>">
                        <td><?php echo htmlspecialchars($vehicle->vehicle_id); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->license_plate); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->status); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->capacity); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->vehicle_type); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->make); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->model); ?></td>
                        <td><?php echo htmlspecialchars($vehicle->manufacturing_year); ?></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <!-- View button with icon -->
                                <a 
                                    href="<?php echo URLROOT; ?>/vehiclemanager/viewVehicle/<?php echo $vehicle->vehicle_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-show' style="font-size: 24px; color:blue;"></i> <!-- Boxicon for view -->
                                </a>

                                <!-- Manage button with icon only -->
                                <a 
                                    href="<?php echo URLROOT; ?>/vehiclemanager/updateVehicle/<?php echo $vehicle->vehicle_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-cog' style="font-size: 24px; color:green;"></i> <!-- Boxicon for settings -->
                                </a>
                                
                                <!-- Delete button with icon only -->
                                <a href="<?php echo URLROOT; ?>/vehiclemanager/deleteVehicle/<?php echo $vehicle->vehicle_id; ?>" 
                                   class="btn btn-tertiary" 
                                   style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
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

