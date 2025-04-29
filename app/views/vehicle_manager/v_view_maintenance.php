<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Vehicle Maintenance Logs</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/vehicle">Vehicles</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Maintenance Logs</a></li>
            </ul>
        </div>
    </div>

    <!-- Section 1: Ongoing Maintenance -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Ongoing Maintenance</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Vehicle</th>
                        <th>Maintenance Type</th>
                        <th>Description</th>
                        <th>Started On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['ongoingMaintenance'])): ?>
                        <?php foreach ($data['ongoingMaintenance'] as $log): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log->log_id); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo htmlspecialchars($log->vehicle_id); ?>" class="vehicle-link">
                                        <?php echo htmlspecialchars($log->license_plate); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($log->maintenance_type); ?></td>
                                <td><?php echo htmlspecialchars($log->description); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($log->created_at)); ?></td>
                                <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="<?php echo URLROOT; ?>/manager/completeMaintenance/<?php echo $log->log_id; ?>" 
                                       class="btn btn-primary" 
                                       style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;">
                                        <i class='bx bx-check' style="font-size: 24px; color: green;"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/manager/updateMaintenance/<?php echo $log->log_id; ?>" 
                                       class="btn btn-tertiary" 
                                       style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;">
                                        <i class='bx bx-edit' style="font-size: 24px; color: orange;"></i>
                                    </a>
                                </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align:center;">No ongoing maintenance logs</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Completed Maintenance -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Completed Maintenance History</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Vehicle</th>
                        <th>Maintenance Type</th>
                        <th>Description</th>
                        <th>Started On</th>
                        <th>Completed On</th>

                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['completedMaintenance'])): ?>
                        <?php foreach ($data['completedMaintenance'] as $log): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log->log_id); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo htmlspecialchars($log->vehicle_id); ?>" class="vehicle-link">
                                        <?php echo htmlspecialchars($log->license_plate); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($log->maintenance_type); ?></td>
                                <td><?php echo htmlspecialchars($log->description); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($log->created_at)); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($log->end_date)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align:center;">No completed maintenance logs</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>