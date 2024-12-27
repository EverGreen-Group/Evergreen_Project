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
                        <td><button class="btn btn-primary">VIEW</button></td>
                    </tr>
                    <tr>
                        <td>COL002</td>
                        <td>Route B</td>
                        <td>Team 2</td>
                        <td><span class="status completed">Completed</span></td>
                        <td><button class="btn btn-primary">VIEW</button></td>
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

<!-- Create New Schedule Section -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Create New Schedule</h3>
        </div>
        <form id="createScheduleForm" method="POST" action="<?php echo URLROOT; ?>/collectionschedules/create">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">

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
                    <select id="week_number" name="week_number" required>
                        <option value="1">Week 1</option>
                        <option value="2">Week 2</option>
                    </select>
                </div>


            </div>

            <button type="submit" class="btn-submit">Create Schedule</button>
        </form>
    </div>
</div>

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


</main>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>