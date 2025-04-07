<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script> -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/collection.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/collection_request_populate.js"></script>



<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Schedule</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Edit Schedule</h3>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/collectionschedules/update" method="POST">
                    <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">

                    <div class="filter-group">
                        <label for="shift_id">Select Shift:</label>
                        <select id="shift_id" name="shift_id" required onchange="loadDrivers()">
                            <option value="">-- Select Shift --</option>
                            <?php foreach ($shifts as $shift): ?>
                                <option value="<?= $shift->shift_id; ?>" <?= ($shift->shift_id == $schedule->shift_id) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($shift->shift_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="driver_id">Select Driver:</label>
                        <select id="driver_id" name="driver_id" required>
                            <option value="<?php echo $schedule->driver_id; ?>" selected>
                                <?php echo $schedule->first_name; ?>
                            </option>
                            <!-- Drivers will be populated based on selected day and shift -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                </form>
            </div>
        </div>
    </div>


</main>

<script>
    // Load drivers based on selected day and shift
    function loadDrivers() {
        const selectedDay = '<?php echo date("l", strtotime($schedule->date)); ?>';
        const selectedShift = document.getElementById('shift_id').value;
        const currentDriverId = <?php echo $schedule->driver_id; ?>;
        const currentDriverName = '<?php echo $schedule->first_name; ?>';
        
        if (selectedShift) {
            // Clear existing options
            const driverSelect = document.getElementById('driver_id');
            driverSelect.innerHTML = '';
            
            // Add current driver as first option
            const currentOption = document.createElement('option');
            currentOption.value = currentDriverId;
            currentOption.textContent = currentDriverName;
            currentOption.selected = true;
            driverSelect.appendChild(currentOption);
            
            fetch(`${URLROOT}/vehicledriver/getUnallocatedDriversByDayAndShift/${selectedDay}/${selectedShift}`)
                .then(response => response.json())
                .then(data => {
                    if (data.drivers && data.drivers.length > 0) {
                        data.drivers.forEach(driver => {
                            // Don't add duplicate of current driver
                            if (driver.driver_id != currentDriverId) {
                                const option = document.createElement('option');
                                option.value = driver.driver_id;
                                option.textContent = driver.first_name;
                                driverSelect.appendChild(option);
                            }
                        });
                    }
                })
                .catch(error => console.error('Error fetching drivers:', error));
        }
    }

    // Call this function when page loads to ensure driver is populated initially
    document.addEventListener('DOMContentLoaded', function() {
        loadDrivers();
    });
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>