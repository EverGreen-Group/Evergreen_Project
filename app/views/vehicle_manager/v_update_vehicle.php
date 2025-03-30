<?php require APPROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



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
<script src="<?php echo URLROOT; ?>/public/js/driver_manager/driver.js"></script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Vehicle</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/v_new_vehicle">Vehicles</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update Vehicle</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if(!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo URLROOT; ?>/manager/updateVehicle/<?php echo $vehicle->vehicle_id; ?>" enctype="multipart/form-data">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Vehicle Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="license_plate">License Plate:</label>
                        <input type="text" id="license_plate" name="license_plate" class="form-control" value="<?php echo htmlspecialchars($vehicle->license_plate); ?>" readonly>
                    </div>
                    <div class="info-row">
                        <label class="label" for="status">Status:</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Available" <?php echo $vehicle->status == 'Available' ? 'selected' : ''; ?>>Available</option>
                            <option value="In Use" <?php echo $vehicle->status == 'In Use' ? 'selected' : ''; ?>>In Use</option>
                            <option value="Maintenance" <?php echo $vehicle->status == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                        </select>
                    </div>
                    <div class="info-row">
                        <label class="label" for="capacity">Capacity:</label>
                        <input type="number" id="capacity" name="capacity" class="form-control" value="<?php echo htmlspecialchars($vehicle->capacity); ?>" readonly>
                    </div>
                    <div class="info-row">
                        <label class="label" for="vehicle_type">Vehicle Type:</label>
                        <input type="text" id="vehicle_type" name="vehicle_type" class="form-control" value="<?php echo htmlspecialchars($vehicle->vehicle_type); ?>" readonly>
                    </div>
                    <div class="info-row">
                        <label class="label" for="make">Make:</label>
                        <input type="text" id="make" name="make" class="form-control" value="<?php echo htmlspecialchars($vehicle->make); ?>" readonly>
                    </div>
                    <div class="info-row">
                        <label class="label" for="model">Model:</label>
                        <input type="text" id="model" name="model" class="form-control" value="<?php echo htmlspecialchars($vehicle->model); ?>" readonly>
                    </div>
                    <div class="info-row">
                        <label class="label" for="manufacturing_year">Manufacturing Year:</label>
                        <input type="number" id="manufacturing_year" name="manufacturing_year" class="form-control" value="<?php echo htmlspecialchars($vehicle->manufacturing_year); ?>" readonly>
                    </div>
                    <div class="info-row">
                        <label class="label" for="vehicle_image">Upload New Image:</label>
                        <input type="file" id="vehicle_image" name="vehicle_image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Vehicle</button>
    </form>
</main>

<!-- Add JavaScript to handle selections and validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const daySelect = document.getElementById('day');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const routeSelect = document.getElementById('routeSelect');
    const driverSelect = document.getElementById('driverSelect');
    
    // Summary elements
    const summaryDay = document.getElementById('summaryDay');
    const summaryTime = document.getElementById('summaryTime');
    const summaryRoute = document.getElementById('summaryRoute');
    const summaryDriver = document.getElementById('summaryDriver');
    
    // Update summary when day is selected
    daySelect.addEventListener('change', function() {
        summaryDay.textContent = this.value || 'Not specified';
        updateSummary();
    });
    
    // Update summary when times are entered
    startTimeInput.addEventListener('change', updateTimeDisplay);
    endTimeInput.addEventListener('change', updateTimeDisplay);
    
    function updateTimeDisplay() {
        if (startTimeInput.value && endTimeInput.value) {
            // Format times for display
            const startTime = formatTime(startTimeInput.value);
            const endTime = formatTime(endTimeInput.value);
            summaryTime.textContent = `${startTime} - ${endTime}`;
        } else {
            summaryTime.textContent = 'Not specified';
        }
        updateSummary();
    }
    
    // Format time from 24h to 12h format
    function formatTime(time24) {
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours, 10);
        const period = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${period}`;
    }
    
    // Update summary when route is selected
    routeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const routeName = selectedOption.getAttribute('data-route-name');
            summaryRoute.textContent = routeName;
        } else {
            summaryRoute.textContent = 'Not specified';
        }
        updateSummary();
    });
    
    // Update summary when driver is selected
    driverSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const firstName = selectedOption.getAttribute('data-first-name');
            const lastName = selectedOption.getAttribute('data-last-name');
            summaryDriver.textContent = `${firstName} ${lastName}`;
        } else {
            summaryDriver.textContent = 'Not specified';
        }
        updateSummary();
    });
    
    // Update overall summary
    function updateSummary() {
        // You could add additional validation or UI updates here
    }
    
    // Form validation
    document.getElementById('createScheduleForm').addEventListener('submit', function(e) {
        // Basic validation
        if (!daySelect.value || !startTimeInput.value || !endTimeInput.value || 
            !routeSelect.value || !driverSelect.value) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return;
        }
        
        // Validate time duration
        const startTime = new Date(`2000-01-01T${startTimeInput.value}`);
        let endTime = new Date(`2000-01-01T${endTimeInput.value}`);
        
        // If end time is earlier than start time, assume it's the next day
        if (endTime < startTime) {
            endTime = new Date(`2000-01-02T${endTimeInput.value}`);
        }
        
        const duration = (endTime - startTime) / (1000 * 60 * 60); // Duration in hours
        
        if (duration > 24) {
            e.preventDefault();
            alert('Schedule duration cannot exceed 24 hours');
            return;
        }
        
        // Optional: Check for driver availability
        const selectedDriverId = driverSelect.value;
        const selectedDay = daySelect.value;
        
        // This could be implemented with an AJAX call to check availability
        // For now, it's commented out
        /*
        fetch(`${URLROOT}/collectionschedules/checkDriverAvailability/${selectedDriverId}/${selectedDay}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    alert(`Warning: This driver already has a schedule on ${selectedDay}.`);
                }
            });
        */
    });
});
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