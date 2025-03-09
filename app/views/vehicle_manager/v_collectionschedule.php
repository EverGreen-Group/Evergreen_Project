<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script> -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script defer src="<?php echo URLROOT; ?>/public/js/vehicle_manager/collection.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/collection_request_populate.js"></script>



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
        <a href="#" id="openCreateScheduleModal" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Create a Schedule
        </a>
        <a href="<?php echo URLROOT; ?>/reschedule/" class="btn btn-primary">
            <i class='bx bx-calendar-edit'></i>
            Manage Exceptions
        </a>
    </div>






    <!-- Replace the old box-info with new Statistics Cards -->
    <ul class="dashboard-stats">
        <!-- Vehicle Statistics -->
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-car'></i>
                <div class="stat-info">
                    <h3><?php echo $stats['vehicles']->total_vehicles; ?></h3>
                    <p>Total Vehicles</p>
                </div>
            </div>
            <div class="stat-details">
                <span class="active"><?php echo $stats['vehicles']->in_use; ?> In Use</span>
                <span class="available"><?php echo $stats['vehicles']->available_vehicles; ?> Available</span>
            </div>
        </li>

        <!-- Driver Statistics -->
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-user'></i>
                <div class="stat-info">
                    <h3><?php echo $stats['drivers']->total_drivers; ?></h3>
                    <p>Total Drivers</p>
                </div>
            </div>
            <div class="stat-details">
                <span class="active"><?php echo $stats['collections']->in_progress ?? 0; ?> On Duty</span>
                <span class="available"><?php echo $stats['drivers']->available_drivers; ?> Available</span>
            </div>
        </li>

        <!-- Collection Statistics -->
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-package'></i>
                <div class="stat-info">
                    <h3><?php echo (isset($stats['collections']->in_progress) ? ($stats['collections']->in_progress) : 0) ?></h3>
                    <p>Collections in Progress</p>
                </div>
            </div>
            <div class="stat-details">
                <span class="completed"><?php echo $stats['collections']->completed_today; ?> Completed Today</span>
            </div>
        </li>
    </ul>





    <?php flash('schedule_error'); ?>
    <?php flash('schedule_success'); ?>
    
    



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
                                    <div style="display: flex; gap: 5px;">
                                        <!-- Update button with icon only -->
                                        <a 
                                            href="<?php echo URLROOT; ?>/vehiclemanager/updateSchedule/<?php echo $schedule->schedule_id; ?>" 
                                            class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                        >
                                            <i class='bx bx-cog' style="font-size: 24px; color:green;"></i> <!-- Boxicon for settings -->
                                        </a>
                                        
                                        <!-- Delete button with icon only -->
                                        <form action="<?php echo URLROOT; ?>/collectionschedules/delete" method="POST" style="margin: 0;" 
                                            onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                            <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                            <button type="submit" class="btn btn-tertiary" 
                                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;">
                                                <i class='bx bx-trash' style="font-size: 24px; color:red;"></i> <!-- Boxicon for trash -->
                                            </button>
                                        </form>
                                    </div>
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




<script>



    
    function fetchCollections(year, month, day) {
        const date = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        
        // Make an AJAX call to fetch collections for the selected date
        fetch(`<?php echo URLROOT; ?>/vehiclemanager/getCollectionsByDate?date=${date}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                populateCollectionList(data);
            })
            .catch(error => {
                console.error('Error fetching collections:', error);
                const collectionListDiv = document.getElementById('collection-list');
                collectionListDiv.innerHTML = '<p>Error fetching collections. Please try again later.</p>';
            });
    }
    // Function to populate the collection list in table format
    function populateCollectionList(collections) {
        const tbody = document.querySelector('#collection-table tbody');
        tbody.innerHTML = ''; // Clear previous data

        if (collections.length === 0) {
            const noDataRow = document.createElement('tr');
            noDataRow.innerHTML = '<td colspan="6">No collections found for this date.</td>';
            tbody.appendChild(noDataRow);
        } else {
            collections.forEach(collection => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${collection.collection_id}</td>
                    <td>${collection.route_id || 'N/A'}</td>
                    <td>${new Date(collection.start_time).toLocaleTimeString()} - ${new Date(collection.end_time).toLocaleTimeString()}</td>
                    <td>${collection.driver_id || 'N/A'}</td>
                    <td><span class="status ${collection.status.toLowerCase().replace(' ', '-')}">${collection.status}</span></td>
                    <td><button class="btn btn-primary" onclick="window.location.href='<?php echo URLROOT; ?>/collection/details/' + ${collection.collection_id}">VIEW</button></td>
                `;
                tbody.appendChild(row);
            });
        }
    }
</script>
    <!-- Collection Schedules Section -->
    <div class="table-data">

    </div>

    <?php flash('schedule_create_error'); ?>
    <?php flash('schedule_create_success'); ?>


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
                    <label for="shift">Shift:</label>
                    <select id="shift" name="shift_id" required>
                        <?php foreach ($data['shifts'] as $shift): ?>
                            <option value="<?= $shift->shift_id; ?>"><?= $shift->shift_name; ?></option>
                        <?php endforeach; ?>
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

            </div>
            <button type="submit" class="btn-secondary">Create Schedule</button>
        </form>
    </div>
</div>



<style>
    /* Status styles */
.status.pending {
    color: orange; /* Color for Pending status */
}

.status.approved {
    color: green; /* Color for Approved status */
}

.status.rejected {
    color: red; /* Color for Rejected status */
}

.status.awaiting-inventory-addition  {
    color: green; /* Color for Rejected status */
}

.status.completed {
    color: blue; /* Color for Completed status */
}

/* Add more statuses as needed */

/* Add more statuses as needed */

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    transition: transform 0.3s ease;
    
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-content i {
    font-size: 2.5rem;
    color: var(--main);
}

.stat-info h3 {
    font-size: 1.8rem;
    margin-bottom: 5px;
    color: var(--dark);
}

.stat-info p {
    color: #555;
    font-size: 0.9rem;
    font-weight: 500;
    margin: 0;
    opacity: 1;
}

.stat-details {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
}

.stat-details span {
    padding: 4px 8px;
    border-radius: 5px;
}

.stat-details .active {
    background: rgba(var(--main-rgb), 0.1);
    color: var(--main);
}

.stat-details .available {
    background: rgba(39, 174, 96, 0.1);
    color: #27ae60;
}

.stat-details .warning {
    background: rgba(241, 196, 15, 0.1);
    color: #f1c40f;
}

.stat-details .completed {
    background: rgba(46, 204, 113, 0.1);
    color: #2ecc71;
}

.dashboard-stats.secondary {
    margin-top: 20px;
}

.dashboard-stats.secondary .stat-card {
    background: #fff;
    border: 1px solid rgba(var(--main-rgb), 0.1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.dashboard-stats.secondary .stat-content i {
    color: var(--main);
}

.dashboard-stats.secondary .stat-info h3 {
    color: var(--dark);
}

.dashboard-stats.secondary .stat-info p {
    color: #555;
}

.next-schedule-alert {
    display: flex;
    align-items: center;
    gap: 15px;
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    border-left: 4px solid var(--main);
}

.next-schedule-alert i {
    font-size: 2rem;
    color: var(--main);
}

.schedule-info h4 {
    color: var(--dark);
    margin-bottom: 5px;
}

.schedule-info p {
    color: #555;
}
</style>


</main>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>
