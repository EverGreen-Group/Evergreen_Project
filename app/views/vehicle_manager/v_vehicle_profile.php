<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Vehicle Profile</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/manager">Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/vehicle">Vehicles</a></li>
                <li>Vehicle Profile</li>
            </ul>
        </div>
        <div class="action-buttons">
            <a href="<?php echo URLROOT; ?>/manager/updateVehicle/<?php echo $vehicle->vehicle_id; ?>" class="btn btn-primary">
                <i class='bx bx-edit'></i>
                Edit Vehicle
            </a>
        </div>
    </div>

    <div class="vehicle-profile-container">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Vehicle Details</h3>
                </div>
                <div class="vehicle-profile-content">
                    <div class="vehicle-profile-image">
                        <?php if (!empty($vehicle->image_path)): ?>
                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($vehicle->image_path); ?>" alt="Vehicle Image">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class='bx bxs-car'></i>
                                <p>No Image Available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="vehicle-profile-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Vehicle ID</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->vehicle_id); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">License Plate</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->license_plate); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status</span>
                                <span class="value <?php echo strtolower($vehicle->status); ?>">
                                    <?php echo htmlspecialchars($vehicle->status); ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">Vehicle Type</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->vehicle_type); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Make</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->make); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Model</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->model); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Manufacturing Year</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->manufacturing_year); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Capacity</span>
                                <span class="value"><?php echo htmlspecialchars($vehicle->capacity); ?> kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Collection History</h3>
                </div>
                <div class="collection-history">
                    <?php if (!empty($collectionHistory)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Collection ID</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Total Quantity</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($collectionHistory as $collection): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($collection->collection_id); ?></td>
                                        <td><?php echo !empty($collection->start_time) ? date('h:i A', strtotime($collection->start_time)) : 'N/A'; ?></td>
                                        <td><?php echo !empty($collection->end_time) ? date('h:i A', strtotime($collection->end_time)) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($collection->total_quantity); ?> kg</td>
                                        <td><?php echo htmlspecialchars($collection->route_name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php 
                                            if (!empty($collection->driver_first_name) && !empty($collection->driver_last_name)) {
                                                echo htmlspecialchars($collection->driver_first_name . ' ' . $collection->driver_last_name);
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No collection history available for this vehicle.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Assigned Schedules</h3>
                </div>
                <div class="upcoming-schedules">
                    <?php if (!empty($upcomingSchedules)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Schedule ID</th>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingSchedules as $schedule): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($schedule->schedule_id); ?></td>
                                        <td><?php echo htmlspecialchars($schedule->day); ?></td>
                                        <td><?php echo !empty($schedule->start_time) ? date('H:i', strtotime($schedule->start_time)) : 'N/A'; ?></td>
                                        <td><?php echo !empty($schedule->end_time) ? date('H:i', strtotime($schedule->end_time)) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($schedule->route_name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php 
                                            if (!empty($schedule->driver_first_name) && !empty($schedule->driver_last_name)) {
                                                echo htmlspecialchars($schedule->driver_first_name . ' ' . $schedule->driver_last_name);
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No upcoming schedules available for this vehicle.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
    .vehicle-profile-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .vehicle-profile-content {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .vehicle-profile-image {
        width: 300px;
        height: 300px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .vehicle-profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .placeholder-image {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f0f0f0;
        color: #888;
    }

    .placeholder-image i {
        font-size: 80px;
        margin-bottom: 10px;
    }

    .vehicle-profile-info,
    .vehicle-additional-info {
        flex-grow: 1;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
    }

    .info-item .label {
        font-weight: bold;
        color: #666;
        margin-bottom: 5px;
        font-size: 0.9em;
    }

    .info-item .value {
        color: #333;
    }

    .info-item .value.active {
        color: green;
    }

    .info-item .value.inactive {
        color: red;
    }

    /* .collection-history table,
    .upcoming-schedules table {
        width: 100%;
        border-collapse: collapse;
    }

    .collection-history table th,
    .upcoming-schedules table th {
        background-color: #f1f1f1;
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .collection-history table td,
    .upcoming-schedules table td {
        border: 1px solid #ddd;
        padding: 8px;
    } */

    .status-cell.pending {
        color: orange;
    }

    .status-cell.completed {
        color: green;
    }

    .status-cell.cancelled {
        color: red;
    }

    .status-cell.in-progress {
        color: blue;
    }

    .no-data {
        text-align: center;
        color: #888;
        padding: 20px;
    }
</style>

<script>
    // Any additional JavaScript for the profile page can be added here
    document.addEventListener('DOMContentLoaded', function() {
        // Example: Add interactivity or dynamic features
    });
</script>