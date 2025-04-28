<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/dashboard_stats.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">


<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Schedule Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>


    </div>

    <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/manager/createSchedule" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Create a Schedule
        </a>
    </div>

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-calendar'></i>
                <div class="stat-info">
                    <h3><?php echo $totalSchedules; ?></h3>
                    <p>Total Schedules</p>
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
                <form action="<?php echo URLROOT; ?>/manager/schedule" method="GET">
                    <div class="filter-group">
                        <label for="route-id">Route:</label>
                        <select id="route-id" name="route_id">
                            <option value="">Select Route</option>
                            <?php foreach ($routes as $route): ?>
                                <option value="<?php echo $route->route_id; ?>" <?php echo (isset($filters['route_id']) && $filters['route_id'] == $route->route_id) ? 'selected' : ''; ?>>
                                    <?php echo $route->route_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="vehicle-id">Vehicle:</label>
                        <select id="vehicle-id" name="vehicle_id">
                            <option value="">Select Vehicle</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?php echo $vehicle->vehicle_id; ?>" <?php echo (isset($filters['vehicle_id']) && $filters['vehicle_id'] == $vehicle->vehicle_id) ? 'selected' : ''; ?>>
                                    <?php echo $vehicle->license_plate; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="driver-id">Driver:</label>
                        <select id="driver-id" name="driver_id">
                            <option value="">Select Driver</option>
                            <?php foreach ($drivers as $driver): ?>
                                <option value="<?php echo $driver->driver_id; ?>" <?php echo (isset($filters['driver_id']) && $filters['driver_id'] == $driver->driver_id) ? 'selected' : ''; ?>>
                                    <?php echo $driver->full_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="day">Day:</label>
                        <select name="day" id="day">
                            <option value="">Select Day</option>
                            <?php 
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            foreach ($days as $d): ?>
                                <option value="<?php echo $d; ?>" 
                                    <?php echo (isset($filters['day']) && $filters['day'] == $d) ? 'selected' : ''; ?>>
                                    <?php echo $d; ?>
                                </option>
                            <?php endforeach; ?>
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
                <h3>Collection Schedules</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($schedules) && !empty($schedules)): ?>
                        <?php foreach ($schedules as $schedule): ?>
                            <tr class="schedule-row" data-schedule-id="<?php echo htmlspecialchars($schedule->schedule_id); ?>">
                                <td><?php echo htmlspecialchars($schedule->schedule_id); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/manageRoute/<?php echo htmlspecialchars($schedule->route_id); ?>" class="route-link">
                                        <?php echo htmlspecialchars($schedule->route_name); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/viewVehicle/<?php echo htmlspecialchars($schedule->vehicle_id); ?>" class="manager-link">
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($schedule->vehicle_image); ?>" alt="Vehicle Photo" class="manager-photo">
                                        <?php echo htmlspecialchars($schedule->license_plate); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/viewDriver/<?php echo htmlspecialchars($schedule->driver_id); ?>" class="manager-link">
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($schedule->driver_image); ?>" alt="Driver Photo" class="manager-photo">
                                        <?php echo htmlspecialchars($schedule->driver_name); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($schedule->day); ?></td>
                                <td><?php echo htmlspecialchars($schedule->start_time); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $schedule->is_active ? 'added' : 'removed'; ?>">
                                        <?php echo $schedule->is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <!-- Edit button with icon -->
                                        <a 
                                            href="<?php echo URLROOT; ?>/manager/updateSchedule/<?php echo $schedule->schedule_id; ?>" 
                                            class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                        >
                                            <i class='bx bx-cog' style="font-size: 24px; color:green;"></i>
                                        </a>
                                        
                                        <!-- Delete button with icon only -->
                                        <form action="<?php echo URLROOT; ?>/manager/deleteSchedule" method="POST" style="margin: 0;">
                                            <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                            <button type="submit" class="btn btn-tertiary" data-confirm="Are you sure you want to delete this schedule?"
                                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;">
                                                <i class='bx bx-trash' style="font-size: 24px; color:red;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No schedules found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>









<style>
.route-link {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: var(--main);
    text-decoration: none;
    transition: color 0.2s;
}

.route-link:hover {
    color: var(--mainn);
}

</style>


</main>





<?php require APPROOT . '/views/inc/components/footer.php'; ?>
