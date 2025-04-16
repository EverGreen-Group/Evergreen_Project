<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/dashboard_stats.css">

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>

<script>
function cancelSupplier(recordId) {
    if (confirm('Are you sure you want to mark this supplier as No Show? This action cannot be undone.')) {
        window.location.href = '<?php echo URLROOT; ?>/vehicledriver/cancelSupplierCollection/' + recordId;
    }
}
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1><?php echo "Details for Collection #" . $collectionDetails->collection_id ?></h1>
            
        </div>


    </div>

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-group'></i>
                <div class="stat-info">
                    <h3><?php echo $data['totalSuppliers']; ?></h3>
                    <p>Total Suppliers</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-basket'></i>
                <div class="stat-info">
                    <h3><?php echo $data['vehicleRemainingCapacity'] . ' kg' ?></h3>
                    <p>Remaining Capacity</p>
                </div>
            </div>
        </li>
    </ul>

    <?php
    // Check if latitude and longitude are not null
    // and the collection status is either 'In Progress' or 'Pending'
    //if (!is_null($data['vehicleDetails']->latitude) && 
    //    !is_null($data['vehicleDetails']->longitude) &&
    //   ($collectionDetails->status === 'In Progress' || $collectionDetails->status === 'Pending')): 
    ?>




<div class="table-data">
    
    <div class="order">
        <div class="head">
            <h3>Collection Supplier Record</h3>
            <a href="<?php echo URLROOT; ?>/manager/viewInactiveDrivers" class="btn btn-primary">
                <i class='bx bx-map'></i>
                View Driver Location
            </a>
            <a href="<?php echo URLROOT; ?>/route/manageRoute/<?php echo $data['routeDetails']->route_id; ?>" class="btn btn-primary">
                <i class='bx bx-navigation'></i>
                View Route
            </a>
            <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo $data['vehicleDetails']->vehicle_id; ?>" class="btn btn-primary">
                <i class='bx bxs-truck'></i>
                View Vehicle Profile
            </a>
            <a href="<?php echo URLROOT; ?>/manager/viewDriver/<?php echo $data['driverDetails']->driver_id; ?>" class="btn btn-primary">
                <i class='bx bx-user'></i>
                View Driver Profile
            </a>
            <!-- <a href="<?php echo URLROOT; ?>/manager/endCollection" class="btn btn-tertiary">
                <i class='bx bx-block'></i>
                End Collection
            </a> -->
        </div>
        <table id="driver-information">
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier Name</th>
                    <th>Contact Number</th>
                    <th>Total Quantity</th>
                    <th>Arrival Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($collectionSuppliersDetails)): ?>
                    <?php foreach ($collectionSuppliersDetails as $supplier): ?>
                        <tr>
                            <td><?php echo $supplier->supplier_id; ?></td>
                            <td><?php echo $supplier->first_name . ' ' . $supplier->last_name; ?></td>
                            <td><?php echo $supplier->contact_number; ?></td>
                            <td><?php echo $supplier->quantity; ?> kg</td>
                            <td><?php echo isset($supplier->collection_time) ? $supplier->collection_time : 'Not Collected Yet'; ?></td>
                            <td>
                                <?php 
                                if ($supplier->status === 'Collected') {
                                    echo '<i class="bx bxs-check-circle" style="color: green;font-size:25px;"></i>';
                                } elseif ($supplier->status === 'Added') {
                                    echo '<i class="bx bxs-hourglass" style="color: orange;font-size:25px;"></i>';
                                } else {
                                    echo '<i class="bx bxs-x-circle" style="color: red;font-size:25px;"></i>';
                                }
                                ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <!-- View Profile button with icon only -->
                                    <a 
                                        href="<?php echo URLROOT; ?>/suppliers/view/<?php echo $supplier->supplier_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bxs-user-detail' style="font-size: 24px; color:#2196F3;"></i>
                                    </a>
                                    
                                    <!-- Cancel button with icon only -->
                                    <?php if ($supplier->status === 'Collected' || $supplier->status === 'No Show'): ?>
                                        <button 
                                            class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none; cursor: not-allowed;" 
                                            disabled
                                        >
                                            <i class='bx bx-x-circle' style="font-size: 24px; color:#cccccc;"></i>
                                        </button>
                                    <?php else: ?>
                                        <form action="<?php echo URLROOT; ?>/vehicledriver/cancelSupplierCollection/<?php echo $supplier->record_id; ?>" method="POST" style="margin: 0;"> 
                                            <button 
                                                type="submit" 
                                                class="btn btn-tertiary" 
                                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                                onclick="return confirm('Are you sure you want to cancel this collection?');"
                                            >
                                                <i class='bx bx-x-circle' style="font-size: 24px; color:red;"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No suppliers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Driver Logs</h3>
        </div>
        <table id="bag-logs">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Bag ID</th>
                    <th>Action</th>
                    <th>Weight</th>
                    <th>Leaf Age</th>
                    <th>Moisture</th>
                    <th>Leaf Type</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bagLogs)): ?>
                    <?php foreach ($bagLogs as $log): ?>
                        <tr>
                            <td><?php echo date('H:i:s', strtotime($log->timestamp)); ?></td>
                            <td><?php echo $log->bag_id; ?></td>
                            <td>
                                <?php 
                                if ($log->action_type === 'Added') {
                                    echo '<i class="bx bx-plus-circle" style="color: green;font-size:20px;"></i> Added';
                                } elseif ($log->action_type === 'Updated') {
                                    echo '<i class="bx bx-edit" style="color: blue;font-size:20px;"></i> Updated';
                                } elseif ($log->action_type === 'Removed') {
                                    echo '<i class="bx bx-trash" style="color: red;font-size:20px;"></i> Removed';
                                }
                                ?>
                            </td>
                            <td><?php echo !empty($log->weight_kg) ? $log->weight_kg . ' kg' : '-'; ?></td>
                            <td><?php echo !empty($log->leaf_age) ? $log->leaf_age : '-'; ?></td>
                            <td><?php echo !empty($log->moisture_level) ? $log->moisture_level : '-'; ?></td>
                            <td><?php echo isset($leafTypes[$log->leaf_type_id]) ? $leafTypes[$log->leaf_type_id] : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No bag logs found for this collection</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</main>




<style>
/* Add this to your CSS file or within a <style> tag */
.status-tag {
    display: inline-block; /* Make it an inline-block element */
    padding: 12px 24px; /* Add some padding */
    font-size: 16px; /* Adjust font size */
    color: white; /* Text color */
    background-color: var(--main);
    border-radius: 25px;
    margin-top: 10px; /* Space above the tag */
    font-weight: bold; /* Make the text bold */
    text-align: center; /* Center the text */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effect */
    width: 200px;
    transform: translate(10px,-7px);
}

.status-tag:hover {
    background-color: #218838; /* Darker green on hover */
    transform: translateY(-2px); /* Slight lift effect on hover */
}
</style>



<!-- TEST styles 
MUST IMPORT IT LATER -->




<?php require APPROOT . '/views/inc/components/footer.php'; ?>
