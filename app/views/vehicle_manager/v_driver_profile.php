<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Driver Profile</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/manager">Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/driver">Drivers</a></li>
                <li>Driver Profile</li>
            </ul>
        </div>
        <div class="action-buttons">
            <a href="<?php echo URLROOT; ?>/manager/updateDriver/<?php echo $driver->driver_id; ?>" class="btn btn-primary">
                <i class='bx bx-edit'></i>
                Edit Driver
            </a>
        </div>
    </div>

    <div class="vehicle-profile-container">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Driver Details</h3>
                </div>
                <div class="vehicle-profile-content">
                    <div class="vehicle-profile-image">
                        <?php if (!empty($driver->image_path)): ?>
                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($driver->image_path); ?>" alt="Driver Image">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class='bx bxs-user'></i>
                                <p>No Image Available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="vehicle-profile-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Driver ID</span>
                                <span class="value"><?php echo htmlspecialchars($driver->driver_id); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">First Name</span>
                                <span class="value"><?php echo htmlspecialchars($driver->first_name); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Last Name</span>
                                <span class="value"><?php echo htmlspecialchars($driver->last_name); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status</span>
                                <span class="value <?php echo strtolower($driver->status); ?>">
                                    <?php echo htmlspecialchars($driver->status); ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">Email</span>
                                <span class="value"><?php echo htmlspecialchars($driver->email); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Phone</span>
                                <span class="value"><?php echo htmlspecialchars($driver->contact_number); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Hire Date</span>
                                <span class="value"><?php echo !empty($driver->hire_date) ? date('d M Y', strtotime($driver->hire_date)) : 'N/A'; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">License Number</span>
                                <span class="value"><?php echo htmlspecialchars($driver->license_number); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Schedules Assigned</h3>
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
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingSchedules as $schedule): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($schedule->schedule_id); ?></td>
                                        <td><?php echo htmlspecialchars($schedule->day); ?></td>
                                        <td><?php echo !empty($schedule->start_time) ? date('H:i', strtotime($schedule->start_time)) : 'N/A'; ?></td>
                                        <td><?php echo !empty($schedule->end_time) ? date('H:i', strtotime($schedule->end_time)) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($schedule->route_id ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($schedule->vehicle_id ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No upcoming schedules available for this driver.</p>
                    <?php endif; ?>
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
                                    <th>Status</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Total Quantity</th>
                                    <th>Bags Collected</th>
                                    <th>Route</th>
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($collectionHistory as $collection): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($collection->collection_id); ?></td>
                                        <td class="status-cell <?php echo strtolower($collection->status); ?>">
                                            <?php echo htmlspecialchars($collection->status); ?>
                                        </td>
                                        <td><?php echo !empty($collection->start_time) ? date('d M Y H:i', strtotime($collection->start_time)) : 'N/A'; ?></td>
                                        <td><?php echo !empty($collection->end_time) ? date('d M Y H:i', strtotime($collection->end_time)) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($collection->total_quantity); ?> kg</td>
                                        <td><?php echo htmlspecialchars($collection->bags ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($collection->route_name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php 
                                            if (!empty($collection->vehicle_make) && !empty($collection->vehicle_model)) {
                                                echo htmlspecialchars($collection->vehicle_make . ' ' . $collection->vehicle_model . ' (' . $collection->license_plate . ')');
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
                        <p class="no-data">No collection history available for this driver.</p>
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

    .vehicle-profile-info {
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
/* 
    .collection-history table,
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
