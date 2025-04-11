<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/driver_manager/driver.js"></script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Route Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-calendar'></i>
                <div class="stat-info">
                    <h3><?php echo $totalRoutes; ?></h3>
                    <p>Total Routes</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-time'></i>
                <div class="stat-info">
                    <h3><?php echo $unassignedRoutes; ?></h3>
                    <p>Total Unassigned</p>
                </div>
            </div>
        </li>
    </ul>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Create Route</h3>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/route/createRoute" method="POST">
                    <div class="filter-group">
                        <label for="route-name">Route Name:</label>
                        <input type="text" id="route-name" name="route_name" placeholder="Enter route name" required>
                    </div>
                    <div class="filter-group">
                        <label for="select-vehicle">Select Vehicle:</label>
                        <select id="select-vehicle" name="vehicle_id" required>
                            <option value="">-- Select Vehicle --</option>
                            <?php foreach ($data['availableVehicles'] as $vehicle): ?>
                                <option value="<?php echo $vehicle->vehicle_id; ?>">
                                    <?php echo $vehicle->license_plate; ?> - 
                                    <?php echo $vehicle->vehicle_type; ?> - 
                                    <?php echo $vehicle->make; ?> - 
                                    <?php echo $vehicle->model; ?> - 
                                    Capacity: <?php echo $vehicle->capacity; ?> - 
                                    Color: <?php echo $vehicle->color; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Route</button>
                </form>
            </div>
        </div>
    </div>


    <div class="table-data">

        <div class="order">
            <div class="head">
                <h3>Routes</h3>
                <a href="<?php echo URLROOT; ?>/manager/viewInactiveDrivers" class="btn btn-primary">
                    <i class='bx bx-show'></i>
                    View Unassigned Suppliers
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Name</th>
                        <th>Suppliers</th>
                        <th>Remaining Capacity</th>
                        <th>Vehicle Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allRoutes as $route): ?>
                        <tr class="route-row" data-route-id="<?php echo htmlspecialchars($route->route_id); ?>">
                            <td><?php echo htmlspecialchars($route->route_id); ?></td>
                            <td><?php echo htmlspecialchars($route->route_name); ?></td>
                            <td><?php echo htmlspecialchars($route->number_of_suppliers); ?></td>
                            <td><?php echo htmlspecialchars($route->remaining_capacity); ?></td>
                            <td>                            
                                <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo $route->vehicle_id; ?>" class="vehicle-link">
                                    <img src="<?php echo URLROOT . '/' . htmlspecialchars($route->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                                    <?php echo htmlspecialchars($route->license_plate); ?>
                                </a>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <!-- Manage button with icon only -->
                                    <a 
                                        href="<?php echo URLROOT; ?>/route/manageRoute/<?php echo $route->route_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bx-cog' style="font-size: 24px; color:green;"></i> <!-- Boxicon for settings -->
                                    </a>
                                    
                                    <!-- Delete button with icon only -->
                                    <form action="<?php echo URLROOT; ?>/route/deleteRoute/" method="POST" style="margin: 0;"> 
                                        <input type="hidden" name="route_id" value="<?php echo $route->route_id; ?>">
                                        <button type="submit" class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            onclick="return confirm('Are you sure you want to delete this route?');">
                                            <i class='bx bx-trash' style="font-size: 24px; color:red;"></i> <!-- Boxicon for trash -->
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</main>

<style>
/* Add these styles to your existing CSS file */

/* Status badges for supplier count */
.status-badge.low {
    background-color: #E8FFF3;
    color: #1BC5BD;
}

.status-badge.medium {
    background-color: #FFF4DE;
    color: #FFA800;
}

.status-badge.high {
    background-color: #FFE2E5;
    color: #F64E60;
}

/* Vehicle display styles */
.vehicle-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: inherit;
}

.vehicle-icon {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    margin-right: 8px;
    background-color: #f1f1f1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vehicle-icon i {
    font-size: 18px;
    color: #555;
}

/* Capacity indicator */
.capacity-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.capacity-bar {
    flex-grow: 1;
    height: 8px;
    background-color: #f1f1f1;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.capacity-fill {
    position: absolute;
    height: 100%;
    background-color: #1BC5BD;
    border-radius: 4px;
}

.capacity-text {
    font-size: 12px;
    white-space: nowrap;
}

/* Action buttons container */
.action-buttons {
    display: flex;
    gap: 5px;
}

/* No need to modify your existing button styles as they're already inline */

/* Enhancing the table */
.table-data .order table {
    width: 100%;
    border-collapse: collapse;
}

.table-data .order table th {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-weight: 600;
}

.table-data .order table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
}

.route-row:hover {
    background-color: #f9f9f9;
}

/* Empty state for when no routes exist */
.empty-state {
    padding: 40px 20px;
    text-align: center;
    color: #888;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #ddd;
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}
</style>


<script src="<?php echo URLROOT; ?>/public/js/route-page.js"></script>

<?php 
// Add stylesheet link
echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/route-management.css">';


require APPROOT . '/views/inc/components/footer.php'; 
?>





<?php require APPROOT . '/views/inc/components/footer.php'; ?>
