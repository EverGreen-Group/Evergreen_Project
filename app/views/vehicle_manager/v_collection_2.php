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

    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Collection Supplier Record</h3>
                <?php if($collectionDetails->status != 'Awaiting Inventory Addition' && $collectionDetails->status != 'Completed' && $collectionDetails->status != 'Cancelled'): ?>
                    <a href="https://www.google.com/maps?q=<?php echo $vehicleLocation->latitude; ?>,<?php echo $vehicleLocation->longitude; ?>" target="_blank" class="btn btn-primary">
                        <i class='bx bx-map'></i>
                        <span>View Vehicle Location</span>
                    </a>
                <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/route/manageRoute/<?php echo $data['routeDetails']->route_id; ?>" class="btn btn-primary">
                        <i class='bx bx-navigation'></i>
                        View Route
                    </a>
                    <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo $data['vehicleDetails']->vehicle_id; ?>" class="btn btn-primary">
                        <i class='bx bxs-truck'></i>
                        View Vehicle
                    </a>
                    <a href="<?php echo URLROOT; ?>/manager/viewDriver/<?php echo $data['driverDetails']->driver_id; ?>" class="btn btn-primary">
                        <i class='bx bx-user'></i>
                        View Driver
                    </a>

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
                                        <a href="<?php echo URLROOT; ?>/suppliers/view/<?php echo $supplier->supplier_id; ?>" class="btn btn-tertiary" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;">
                                            <i class='bx bxs-user-detail' style="font-size: 24px; color:#2196F3;"></i>
                                        </a>
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
                                    <?php if($log->action_type == 'Added'): ?>
                                        <span class="status-badge added">Added</span>
                                    <?php elseif($log->action_type == 'Updated'): ?>
                                        <span class="status-badge updated">Updated</span>
                                    <?php else: ?>
                                        <span class="status-badge removed">Removed</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo !empty($log->weight_kg) ? $log->weight_kg . ' kg' : '-'; ?></td>
                                <td><?php echo !empty($log->leaf_age) ? $log->leaf_age : '-'; ?></td>
                                <td><?php echo !empty($log->moisture_level) ? $log->moisture_level : '-'; ?></td>
                                <td>
                                    <?php 
                                    $leafTypeName = '-';
                                    foreach ($leafTypes as $leafType) {
                                        if ($leafType->leaf_type_id == $log->leaf_type_id) {
                                            $leafTypeName = $leafType->name; 
                                            break; 
                                        }
                                    }
                                    echo $leafTypeName; 
                                    ?>
                                </td>
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

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
