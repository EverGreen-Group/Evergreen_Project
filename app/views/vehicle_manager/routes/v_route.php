<?php require APPROOT . '/views/inc/components/header.php'; ?>

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
                <i class='bx bx-map-pin'></i>
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
                <h3>Routes</h3>
                <a href="<?php echo URLROOT; ?>/route/createRoute" class="btn btn-primary">
                    <i class='bx bx-plus'></i>Create a route
                </a>
                <form action="<?php echo URLROOT?>/route/route" method="GET">
                    <label for="search">Search: </label>
                    <input type="text" name="search" placeholder="Search">  <!-- Use 'search' instead of 'Search' -->
                    <button class="btn btn-primary">Search</button>
                </form>

            </div>
            <table>
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Name</th>
                        <th>Suppliers</th>
                        <th>Remaining Capacity</th>
                        <th>Vehicle Assigned</th>
                        <th>Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allRoutes as $route): ?>
                        <tr class="route-row" data-route-id="<?php echo htmlspecialchars($route->route_id); ?>">
                            <td><?php echo htmlspecialchars($route->route_id); ?></td>
                            <td><?php echo htmlspecialchars($route->route_name); ?></td>
                            <td><?php echo htmlspecialchars($route->number_of_suppliers); ?></td>
                            <td><?php echo htmlspecialchars(ceil($route->remaining_capacity)); ?> kg</td>
                            <td>                            
                                <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo $route->vehicle_id; ?>" class="vehicle-link">
                                    <img src="<?php echo URLROOT . '/' . htmlspecialchars($route->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                                    <?php echo htmlspecialchars($route->license_plate); ?>
                                </a>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $route->is_assigned ? 'yes' : 'no'; ?>">
                                    <?php echo $route->is_assigned ? 'Yes' : 'No'; ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a 
                                        href="<?php echo URLROOT; ?>/route/manageRoute/<?php echo $route->route_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bx-cog' style="font-size: 24px; color:green;"></i>
                                    </a>
                                    
                                    <form action="<?php echo URLROOT; ?>/route/deleteRoute/" method="POST" style="margin: 0;"> 
                                        <input type="hidden" name="route_id" value="<?php echo $route->route_id; ?>">
                                        <button type="submit" class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            data-confirm="Are you sure you want to delete this route?">
                                            <i class='bx bx-trash' style="font-size: 24px; color:red;"></i>
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

.yes {
  background-color: var(--mainn);
  color: white;
}

.no {
  background-color: var(--red);
  color: white;
}
</style>


<script src="<?php echo URLROOT; ?>/public/js/route-page.js"></script>

<?php 
// Add stylesheet link
echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/route-management.css">';


require APPROOT . '/views/inc/components/footer.php'; 
?>





<?php require APPROOT . '/views/inc/components/footer.php'; ?>
