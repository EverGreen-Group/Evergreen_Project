<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Collection Schedule</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/collectionschedule">Schedules</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update Schedule</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if(!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>
    
    <form id="updateScheduleForm" method="POST" action="<?php echo URLROOT; ?>/manager/updateSchedule/<?php echo $data['schedule']->schedule_id; ?>">
    
    <!-- Schedule Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Schedule Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label" for="day">Day of Week:</label>
                    <select id="day" name="day" class="form-control" required>
                        <option value="">Select Day</option>
                        <option value="Monday" <?php echo ($data['schedule']->day == 'Monday') ? 'selected' : ''; ?>>Monday</option>
                        <option value="Tuesday" <?php echo ($data['schedule']->day == 'Tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                        <option value="Wednesday" <?php echo ($data['schedule']->day == 'Wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                        <option value="Thursday" <?php echo ($data['schedule']->day == 'Thursday') ? 'selected' : ''; ?>>Thursday</option>
                        <option value="Friday" <?php echo ($data['schedule']->day == 'Friday') ? 'selected' : ''; ?>>Friday</option>
                        <option value="Saturday" <?php echo ($data['schedule']->day == 'Saturday') ? 'selected' : ''; ?>>Saturday</option>
                        <option value="Sunday" <?php echo ($data['schedule']->day == 'Sunday') ? 'selected' : ''; ?>>Sunday</option>
                    </select>
                </div>
                <div class="info-row">
                    <label class="label" for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" class="form-control" value="<?php echo $data['schedule']->start_time; ?>" required>
                </div>
                <div class="info-row">
                    <label class="label" for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" class="form-control" value="<?php echo $data['schedule']->end_time; ?>" required>
                </div>
                <div class="info-row">
                    <label class="label" for="status">Status:</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="1" <?php echo ($data['schedule']->is_active == 1) ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo ($data['schedule']->is_active == 0) ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Route Selection Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Select Route</h3>
            </div>
            <div class="section-content">
                <div class="user-selection-container">
                    <select id="routeSelect" name="route_id" class="form-control" required>
                        <option value="">Select a Route</option>
                        <?php foreach ($data['routes'] as $route): ?>
                            <option value="<?= $route->route_id ?>" 
                                    data-route-name="<?= htmlspecialchars($route->route_name) ?>"
                                    data-supplier-count="<?= isset($route->supplier_count) ? $route->supplier_count : '0' ?>"
                                    <?php echo ($data['schedule']->route_id == $route->route_id) ? 'selected' : ''; ?>
                            >
                                R<?= str_pad($route->route_id, 3, '0', STR_PAD_LEFT) ?> - 
                                <?= htmlspecialchars($route->route_name) ?> 
                                (<?= isset($route->supplier_count) ? $route->supplier_count : '0' ?> suppliers)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Selection Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Select Driver</h3>
            </div>
            <div class="section-content">
                <div class="user-selection-container">
                    <select id="driverSelect" name="driver_id" class="form-control" required>
                        <option value="">Select a Driver</option>
                        <?php foreach ($data['drivers'] as $driver): ?>
                            <option value="<?= $driver->driver_id ?>" 
                                    data-first-name="<?= htmlspecialchars($driver->first_name) ?>"
                                    data-last-name="<?= htmlspecialchars($driver->last_name) ?>"
                                    data-nic="<?= htmlspecialchars($driver->nic) ?>"
                                    <?php echo ($data['schedule']->driver_id == $driver->driver_id) ? 'selected' : ''; ?>
                            >
                                D<?= str_pad($driver->driver_id, 3, '0', STR_PAD_LEFT) ?> - 
                                <?= htmlspecialchars($driver->first_name . ' ' . $driver->last_name) ?> 
                                (NIC: <?= htmlspecialchars($driver->nic) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Update Schedule</button>
    </form>
    

<style>
    /* Table Data Container */
    .table-data {
        margin-bottom: 24px;
    }

    .order {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Section Headers */
    .head {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .head h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Content Sections */
    .section-content {
        padding: 8px 0;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        transition: background-color 0.2s;
    }

    .info-row:hover {
        background-color: #f8f9fa;
    }

    .info-row .label {
        flex: 0 0 200px;
        font-size: 14px;
        color: #6c757d;
    }

    .info-row .value {
        flex: 1;
        font-size: 14px;
        color: #2c3e50;
    }

    /* Alert styling */
    .alert {
        padding: 12px 20px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-size: 14px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Breadcrumb Refinements */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .breadcrumb a {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #3b82f6;
    }

    .breadcrumb a.active {
        color: #2c3e50;
        pointer-events: none;
    }

    .breadcrumb i {
        color: #9ca3af;
        font-size: 14px;
    }

    /* Add styles for selection dropdowns */
    .user-selection-container {
        padding: 20px;
    }

    #driverSelect, #routeSelect, #day {
        width: 100%;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Submit button styling */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        margin: 0 0 20px 20px;
    }

    .btn-primary:hover {
        background-color: #059669;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 