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

    <!-- Schedule Summary -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Schedule Summary</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Day:</span>
                    <span class="value" id="summaryDay"><?php echo $data['schedule']->day; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Time:</span>
                    <span class="value" id="summaryTime"><?php echo date('h:i A', strtotime($data['schedule']->start_time)) . ' - ' . date('h:i A', strtotime($data['schedule']->end_time)); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Route:</span>
                    <span class="value" id="summaryRoute">
                        <?php 
                        foreach ($data['routes'] as $route) {
                            if ($route->route_id == $data['schedule']->route_id) {
                                echo 'R' . str_pad($route->route_id, 3, '0', STR_PAD_LEFT) . ' - ' . htmlspecialchars($route->route_name);
                                break;
                            }
                        }
                        ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Driver:</span>
                    <span class="value" id="summaryDriver">
                        <?php 
                        foreach ($data['drivers'] as $driver) {
                            if ($driver->driver_id == $data['schedule']->driver_id) {
                                echo 'D' . str_pad($driver->driver_id, 3, '0', STR_PAD_LEFT) . ' - ' . 
                                     htmlspecialchars($driver->first_name . ' ' . $driver->last_name);
                                break;
                            }
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Update Schedule</button>
    </form>
    
<script>
    // Update summary when form fields change
    document.getElementById('day').addEventListener('change', updateSummary);
    document.getElementById('start_time').addEventListener('change', updateSummary);
    document.getElementById('end_time').addEventListener('change', updateSummary);
    document.getElementById('routeSelect').addEventListener('change', updateSummary);
    document.getElementById('driverSelect').addEventListener('change', updateSummary);

    // Initial summary update
    function updateSummary() {
        // Update day
        const daySelect = document.getElementById('day');
        const summaryDay = document.getElementById('summaryDay');
        if (daySelect.value) {
            summaryDay.textContent = daySelect.options[daySelect.selectedIndex].text;
        } else {
            summaryDay.textContent = 'Not specified';
        }

        // Update time
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const summaryTime = document.getElementById('summaryTime');
        
        if (startTime && endTime) {
            // Format times to AM/PM
            const formattedStartTime = formatTime(startTime);
            const formattedEndTime = formatTime(endTime);
            summaryTime.textContent = `${formattedStartTime} - ${formattedEndTime}`;
        } else {
            summaryTime.textContent = 'Not specified';
        }

        // Update route
        const routeSelect = document.getElementById('routeSelect');
        const summaryRoute = document.getElementById('summaryRoute');
        
        if (routeSelect.value) {
            const selectedOption = routeSelect.options[routeSelect.selectedIndex];
            const routeId = routeSelect.value;
            const routeName = selectedOption.getAttribute('data-route-name');
            summaryRoute.textContent = `R${routeId.padStart(3, '0')} - ${routeName}`;
        } else {
            summaryRoute.textContent = 'Not specified';
        }

        // Update driver
        const driverSelect = document.getElementById('driverSelect');
        const summaryDriver = document.getElementById('summaryDriver');
        
        if (driverSelect.value) {
            const selectedOption = driverSelect.options[driverSelect.selectedIndex];
            const driverId = driverSelect.value;
            const firstName = selectedOption.getAttribute('data-first-name');
            const lastName = selectedOption.getAttribute('data-last-name');
            summaryDriver.textContent = `D${driverId.padStart(3, '0')} - ${firstName} ${lastName}`;
        } else {
            summaryDriver.textContent = 'Not specified';
        }
    }

    // Helper function to format time to AM/PM
    function formatTime(timeString) {
        const [hours, minutes] = timeString.split(':');
        let hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12;
        hour = hour ? hour : 12; // Convert 0 to 12
        return `${hour}:${minutes} ${ampm}`;
    }

    // Pad a number with leading zeros
    String.prototype.padStart = String.prototype.padStart || function padStart(targetLength, padString) {
        targetLength = targetLength >> 0;
        padString = String(padString || ' ');
        if (this.length > targetLength) {
            return String(this);
        } else {
            targetLength = targetLength - this.length;
            if (targetLength > padString.length) {
                padString += padString.repeat(targetLength / padString.length);
            }
            return padString.slice(0, targetLength) + String(this);
        }
    };
</script>

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