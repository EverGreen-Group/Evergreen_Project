<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Welcome back, Driver!</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li>Overview</li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Upcoming Schedule</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                        <th>Start Time</th>
                        <th>Day</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($todaySchedules)): ?>
                    <?php foreach ($todaySchedules as $schedule): ?>
                        <tr>
                            <td><?= htmlspecialchars($schedule->schedule_id) ?></td>
                            <td><?= htmlspecialchars($schedule->route_name) ?></td>
                            <td><?= htmlspecialchars($schedule->vehicle_type) ?> (<?= htmlspecialchars($schedule->license_plate) ?>)</td>
                            <td><?= htmlspecialchars($schedule->start_time) ?></td>
                            <td><?= htmlspecialchars($schedule->day) ?></td>
                            <td>
                                <?php 
                                // Determine the status based on the current time
                                $currentTime = time();
                                $startTime = strtotime($schedule->start_time);
                                $endTime = strtotime($schedule->end_time);

                                if ($schedule->is_today == 1 && $currentTime >= $startTime && $currentTime <= $endTime) {
                                    echo "Started"; // Schedule is currently active
                                } elseif ($schedule->is_today == 1 && $currentTime < $startTime) {
                                    echo "Upcoming"; // Schedule is yet to start
                                } elseif ($currentTime > $endTime) {
                                    echo "Completed"; // Schedule has ended
                                } else {
                                    echo htmlspecialchars($schedule->schedule_status); // Default status
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                // Check if the schedule is today and if the current time is past the start time
                                if ($schedule->is_today == 1 && $currentTime >= $startTime): ?>
                                    <form action="<?php echo URLROOT; ?>/vehicledriver/createCollection/<?php echo htmlspecialchars($schedule->schedule_id); ?>" method="POST">
                                        <button type="submit" class="btn-primary">Start Collection</button>
                                    </form>
                                <?php else: ?>
                                    <button class="action-button" disabled>Not Available</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No schedules available for today.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>