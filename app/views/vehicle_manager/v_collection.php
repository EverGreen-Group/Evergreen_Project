<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
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
            <h1>Collection Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>

        <div class="datetime-display">
            <div class="date">
                <i class='bx bx-calendar'></i>
                <span><?php echo date('l, F j, Y'); ?></span>
            </div>
            <div class="time" id="live-time">
                <i class='bx bx-time-five'></i>
                <span>Loading...</span>
            </div>
        </div>

    </div>

    <div class="action-buttons">
        <a href="#" id="openCreateScheduleModal" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Create a Schedule
        </a>
    </div>


    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <h3><?php echo $stats['vehicles']->total_vehicles; ?></h3>
                <p>Vehicles</p>
                <small><?php echo $stats['vehicles']->total_vehicles; ?> Available</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-user'></i>
            <span class="text">
                <h3><?php echo $stats['drivers']->total_drivers; ?></h3>
                <p>Drivers</p>
                <small><?php echo $stats['drivers']->available_drivers; ?> Available</small>
            </span>
        </li>
    </ul>



    <?php flash('schedule_error'); ?>
    <?php flash('schedule_success'); ?>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Active Collections</h3>
                <i class='bx bx-leaf'></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Route</th>
                        <th>Team</th>
                        <th>Status</th>
                        <th>Detals</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>COL001</td>
                        <td>Route A</td>
                        <td>Team 1</td>
                        <td><span class="status pending">In Progress</span></td>
                        <td><button class="btn btn-primary" onclick="openActiveCollections()">VIEW</button></td>
                    </tr>
                    <tr>
                        <td>COL002</td>
                        <td>Route B</td>
                        <td>Team 2</td>
                        <td><span class="status completed">Completed</span></td>
                        <td><button class="btn btn-primary" onclick="openActiveCollections()">VIEW</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="order">
            <div class="head">
                <h3>Collection Confirmation Request</h3>
                <i class='bx bx-leaf'></i>
            </div>
            <table id="collection-confirmation-table">
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Route</th>
                        <th>Driver</th>
                        <th>Deliveries</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>COL001</td>
                        <td>Route A</td>
                        <td>Driver 1</td>
                        <td><span class="status cancelled">NO</span></td>
                        <td><button class="btn btn-primary" onclick="openCollectionRequestDetailModal()">VIEW</button></td>
                    </tr>
                    <tr>
                        <td>COL002</td>
                        <td>Route B</td>
                        <td>Driver 2</td>
                        <td><span class="status completed">YES</span></td>
                        <td><button class="btn btn-primary" onclick="openCollectionRequestDetailModal()">VIEW</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        
    </script>

    <!-- Collection Schedules Section -->
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
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Shift</th>
                        <th>Week</th>
                        <th>Day</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['schedules']) && !empty($data['schedules'])): ?>
                        <?php foreach($data['schedules'] as $schedule): ?>
                            <tr>
                                <td>CS<?php echo str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $schedule->route_name; ?></td>
                                <td><?php echo $schedule->driver_name; ?></td>
                                <td><?php echo $schedule->license_plate; ?></td>
                                <td><?php echo $schedule->shift_name; ?> (<?php echo $schedule->start_time; ?> - <?php echo $schedule->end_time; ?>)</td>
                                <td>Week <?php echo $schedule->week_number; ?></td>
                                <td><?php echo $schedule->day; ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($schedule->created_at)); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionschedules/toggleActive" method="POST" style="display: inline;">
                                        <button type="submit" class="status-btn <?php echo $schedule->is_active ? 'active' : 'inactive'; ?>" style="background-color: var(--main)"> 
                                            <?php echo $schedule->is_active ? 'Active' : 'Inactive'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionschedules/delete" method="POST" style="display: inline;" 
                                        onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                        <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No schedules found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php flash('schedule_create_error'); ?>
    <?php flash('schedule_create_success'); ?>

<!-- Edit Schedule Section -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Edit Schedule</h3>
        </div>
        <form id="editScheduleForm" method="POST" action="<?php echo URLROOT; ?>/collectionschedules/update">
            <div class="form-group">
                <label for="schedule_id">Select Schedule:</label>
                <select id="schedule_id" name="schedule_id" required onchange="loadScheduleData(this.value)">
                    <option value="">Select a schedule</option>
                    <?php foreach ($data['schedules'] as $schedule): ?>
                        <option value="<?= $schedule->schedule_id; ?>">
                            Schedule <?= str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="form-group">
                    <label for="edit_route">Route:</label>
                    <select id="edit_route" name="route_id" required>
                        <?php foreach ($data['routes'] as $route): ?>
                            <option value="<?= $route->route_id; ?>"><?= htmlspecialchars($route->route_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_driver">Driver:</label>
                    <select id="edit_driver" name="driver_id" required>
                        <?php foreach ($data['drivers'] as $driver): ?>
                            <option value="<?= $driver->driver_id; ?>"><?= htmlspecialchars($driver->first_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_shift">Shift:</label>
                    <select id="edit_shift" name="shift_id" required>
                        <?php foreach ($data['shifts'] as $shift): ?>
                            <option value="<?= $shift->shift_id; ?>"><?= $shift->shift_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_week_number">Week:</label>
                    <select id="edit_week_number" name="week_number" required>
                        <option value="1">Week 1</option>
                        <option value="2">Week 2</option>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn-submit">Update Schedule</button>
        </form>
    </div>
</div>


<!-- PART FOR MODAL -->

<div id="collectionRequestDetailsModal" class="modal" onclick="closeModal('collectionRequestDetailsModal')">
    <div class="modal-content" onclick="event.stopPropagation();">
        <span class="close" onclick="closeModal('collectionRequestDetailsModal')">&times;</span>
        <h2>Collection Confirmation Request</h2>
        <div id="collectionRequestDetailsContent">
            <!-- Bag details will be populated here -->
        </div>
    </div>
</div>

<!-- Create Schedule Modal -->
<div id="createScheduleModal" class="modal" onclick="event.stopPropagation(); closeModal('createScheduleModal')">
    <div class="modal-content" style="width: 80%; max-width: 600px;" onclick="event.stopPropagation();">
        <span class="close" onclick="closeModal('createScheduleModal')">&times;</span>
        <h2 style="margin-bottom: 30px;">Create New Schedule</h2>

        <!-- <img src="<?php echo URLROOT; ?>/public/img/schedule_banner.jpg" alt="Banner" style="width: 100%; height: auto; margin-bottom: 20px;"> -->

        <form id="createScheduleForm" method="POST" action="<?php echo URLROOT; ?>/collectionschedules/create">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group">
                    <label for="day">Select Day:</label>
                    <select id="day" name="day" required>
                        <option value="" disabled selected>Select a day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="route">Route:</label>
                    <select id="route" name="route_id" required>
                        <option value="" disabled selected>Select a day first</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="driver">Driver:</label>
                    <select id="driver" name="driver_id" required>
                        <?php foreach ($data['drivers'] as $driver): ?>
                            <option value="<?= $driver->driver_id; ?>"><?= $driver->first_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="shift">Shift:</label>
                    <select id="shift" name="shift_id" required>
                        <?php foreach ($data['shifts'] as $shift): ?>
                            <option value="<?= $shift->shift_id; ?>"><?= $shift->shift_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="week_number">Week:</label>
                    <select style="margin-bottom: 30px;" id="week_number" name="week_number" required>
                        <option value="1">Week 1</option>
                        <option value="2">Week 2</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-secondary">Create Schedule</button>
        </form>
    </div>
</div>


<div id="viewActiveCollectionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewActiveCollectionModal')">&times;</span>
        <h2>Collection #23</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">

                <div class="detail-group">
                    <h3>Statistics</h3>
                    <div class="stats-container">
                        <div class="stat-item">
                            <span class="label">Collections</span>
                            <div class="value" id="numberOfSuppliers">5</div> <!-- Hardcoded -->
                        </div>
                        <div class="stat-item">
                            <span class="label">Collected</span>
                            <div class="value" id="numberCollected">3</div> <!-- Hardcoded -->
                        </div>
                        <div class="stat-item">
                            <span class="label">Remaining</span>
                            <div class="value" id="numberRemaining">2</div> <!-- Hardcoded -->
                        </div>
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Map Location</h3>
                    <div id="map" style="height: 300px; width: 100%;"></div>
                    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>
                    <script>
                        function initMap() {
                            var driverLocation = {lat: 6.2173037, lng: 80.2538636}; // Hardcoded location
                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 14,
                                center: driverLocation
                            });
                            var marker = new google.maps.Marker({
                                position: driverLocation,
                                map: map,
                                title: 'Driver Location'
                            });
                        }
                    </script>
                </div>

                <div class="detail-group">
                    <h3>Driver & Vehicle Information</h3>
                    <div class="info-container">
                        <div class="profile-info">
                            <div class="detail-row">
                                <span class="label">Full Name:</span>
                                <span class="value" id="driverEmail">John Doe</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Email:</span>
                                <span class="value" id="driverEmail">john.doe@example.com</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Phone:</span>
                                <span class="value" id="driverPhone">123-456-7890</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Location:</span>
                                <span class="value" id="driverLocation">Galle</span> <!-- Hardcoded -->
                            </div>
                        </div>

                        <div class="vehicle-info">
                            <div class="detail-row">
                                <span class="label">Vehicle ID:</span>
                                <span class="value" id="vehicleID">V001</span> <!-- Hardcoded -->
                            </div>
                            <div class="detail-row">
                                <span class="label">Vehicle Type:</span>
                                <span class="value" id="vehicleType">Truck</span> <!-- Hardcoded -->
                            </div>
                            <div class="detail-row">
                                <span class="label">Shift Start:</span>
                                <span class="value" id="shiftStart">08:00 AM</span> <!-- Hardcoded -->
                            </div>
                            <div class="detail-row">
                                <span class="label">Shift End:</span>
                                <span class="value" id="shiftEnd">05:00 PM</span> <!-- Hardcoded -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Collections</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Collection ID</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Status Badge</th>
                                <th>Visited Time</th>
                                <th>Collected Time</th>
                                <th>Total Weight</th>
                            </tr>
                        </thead>
                        <tbody id="activeCollections">
                            <tr>
                                <td>COL001</td>
                                <td>Supplier 1</td>
                                <td>Completed</td>
                                <td><span class="badge completed">Completed</span></td>
                                <td>08:30 AM</td>
                                <td>09:00 AM</td>
                                <td>50 kg</td>
                            </tr>
                            <tr>
                                <td>COL002</td>
                                <td>Supplier 2</td>
                                <td>Pending</td>
                                <td><span class="badge pending">Pending</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>COL003</td>
                                <td>Supplier 3</td>
                                <td>Completed</td>
                                <td><span class="badge completed">Completed</span></td>
                                <td>09:15 AM</td>
                                <td>09:45 AM</td>
                                <td>30 kg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="detail-group">
                    <button class="add-button" onclick="addUnallocatedSupplier()">
                        + Add Unallocated Supplier
                    </button> <!-- Button to add unallocated supplier -->
                </div>

                <div class="detail-group">
                    <h3>Fertilizer Delivery</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Requested Fertilizer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="fertilizerDelivery">
                            <tr>
                                <td>Supplier 1</td>
                                <td>Type A</td>
                                <td>Pending</td>
                            </tr>
                            <tr>
                                <td>Supplier 2</td>
                                <td>Type B</td>
                                <td>Completed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="detail-group">
                    <h3>Leaf Types in Collection</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Leaf Type</th>
                                <th>Current Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Normal Leaf</td>
                                <td>50 kg</td>
                            </tr>
                            <tr>
                                <td>Super Leaf</td>
                                <td>20 kg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="detail-group">
                    <h3>Bags Allocation</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Number of Bags</th>
                                <th>Bags Details</th>
                            </tr>
                        </thead>
                        <tbody id="bagsAllocation">
                            <tr>
                                <td>Supplier 1</td>
                                <td>3</td>
                                <td>
                                    <span class="badge" style="background-color: #F4F4F4;">Bag 1</span>, 
                                    <span class="badge" style="background-color: #F4F4F4;">Bag 2</span>, 
                                    <span class="badge" style="background-color: #F4F4F4;">Bag 3</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier 2</td>
                                <td>2</td>
                                <td>
                                    <span class="badge" style="background-color: #F4F4F4;">Bag 4</span>, 
                                    <span class="badge" style="background-color: #F4F4F4;">Bag 5</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>

function openActiveCollections() {
  // Show the modal
  document.getElementById("viewActiveCollectionModal").style.display = "block";
}

</script>

</main>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
.info-container {
    display: flex;
    justify-content: space-between; /* Space between driver and vehicle info */
    margin-bottom: 15px; /* Space below the info section */
}

.profile-info, .vehicle-info {
    flex: 1; /* Allow both sections to take equal space */
    margin-right: 20px; /* Space between driver and vehicle info */
}

.vehicle-info {
    margin-right: 0; /* Remove margin for the last item */
}

.label {
    font-weight: bold; /* Keep labels bold for clarity */
}

.value {
    margin-left: 5px; /* Space between label and value */
}

.badge {
    display: inline-block; /* Make it inline-block for padding */
    padding: 5px 10px; /* Padding for the badge */
    border-radius: 3px; /* Rounded corners */
    color: #333; /* Darker text color for visibility */

    margin-right: 5px; /* Space between badges */
}

.completed {
    background-color: var(--mainn); /* Green for completed */
}

.pending {
    background-color: #ffc107; /* Yellow for pending */
}

.stats-container {
    display: flex;
    justify-content: space-between; /* Space between stats */
    margin-bottom: 15px; /* Space below the stats section */
    background-color: #f4f4f4;
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Padding inside the outline */
}

.stat-item {
    flex: 1; /* Allow each stat item to take equal space */
    text-align: center; /* Center the text */
}

.stat-item .label {
    font-weight: bold; /* Keep labels bold for clarity */
    display: block; /* Make label a block element */
    margin-bottom: 5px; /* Space between label and value */
    color: var(--main);
}

.stat-item .value {
    font-size: 1.5em; /* Increase font size for the value */
    font-weight: bold; /* Make the value bold */
    color: var(--dark); /* Darker color for better visibility */
}

.add-button {
    background-color: var(--main); /* Green background */
    color: white; /* White text */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px 15px; /* Padding */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    display: flex; /* Flexbox for icon and text */
    align-items: center; /* Center items vertically */
}

.add-button i {
    margin-right: 5px; /* Space between icon and text */
}

.add-button:hover {
    background-color: #218838; /* Darker green on hover */
}
</style>