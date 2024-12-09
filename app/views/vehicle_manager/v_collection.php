<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>


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

        <style>
        .datetime-display {
            display: flex;
            gap: 2rem;
            background: var(--light);
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .datetime-display .date,
        .datetime-display .time {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            color: var(--dark);
        }

        .datetime-display i {
            font-size: 1.2rem;
            color: var(--main);
        }
    </style>

    <script>
        function updateTime() {
            const timeElement = document.querySelector('#live-time span');
            const now = new Date();
            timeElement.textContent = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            });
        }

        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>
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
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <h3><?php echo $stats['partners']->total_partners; ?></h3>
                <p>Driving Partners</p>
                <small><?php echo $stats['partners']->available_partners; ?> Available</small>
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
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>COL001</td>
                        <td>Route A</td>
                        <td>Team 1</td>
                        <td><span class="status pending">In Progress</span></td>
                        <td>65%</td>
                    </tr>
                    <tr>
                        <td>COL002</td>
                        <td>Route B</td>
                        <td>Team 2</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>100%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="order">
            <div class="head">
                <h3>Today's Routes</h3>
                <i class='bx bx-map'></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>RT001</td>
                        <td>VH001</td>
                        <td>John Doe</td>
                        <td><span class="status completed">Active</span></td>
                    </tr>
                    <tr>
                        <td>RT002</td>
                        <td>VH003</td>
                        <td>Jane Smith</td>
                        <td><span class="status completed">Active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

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
                        <th>Team</th>
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
                                <td><?php echo $schedule->team_name; ?></td>
                                <td><?php echo $schedule->license_plate; ?></td>
                                <td><?php echo $schedule->shift_name; ?> (<?php echo $schedule->start_time; ?> - <?php echo $schedule->end_time; ?>)</td>
                                <td>Week <?php echo $schedule->week_number; ?></td>
                                <td><?php echo $schedule->day; ?></td> <!-- Updated to use the single day value -->
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
                    <label for="team">Team:</label>
                    <select id="team" name="team_id" required>
                        <?php foreach ($data['teams'] as $team): ?>
                            <option value="<?= $team->team_id; ?>"><?= $team->team_name; ?></option>
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

<script>
    document.getElementById('day').addEventListener('change', function() {
        const selectedDay = this.value;
        const routeSelect = document.getElementById('route');

        // Clear existing options
        routeSelect.innerHTML = '<option value="" disabled selected>Select a route</option>';

        // Fetch routes based on the selected day
        fetch(`<?php echo URLROOT; ?>/routes/getRoutesByDay/${selectedDay}`)
            .then(response => response.json())
            .then(data => {
                data.routes.forEach(route => {
                    const option = document.createElement('option');
                    option.value = route.route_id;
                    option.textContent = route.route_name;
                    routeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching routes:', error));
    });
</script>

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
                    <label for="edit_team">Team:</label>
                    <select id="edit_team" name="team_id" required>
                        <?php foreach ($data['teams'] as $team): ?>
                            <option value="<?= $team->team_id; ?>"><?= $team->team_name; ?></option>
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

<script>
   function loadScheduleData(scheduleId) {
       fetch(`<?php echo URLROOT; ?>/collectionschedules/getSchedule/${scheduleId}`)
           .then(response => response.json())
           .then(data => {
               if (data) {
                   console.log(data); // Log the fetched data
                   const routeElement = document.getElementById('edit_route');
                   const teamElement = document.getElementById('edit_team');
                   const shiftElement = document.getElementById('edit_shift');
                   const weekNumberElement = document.getElementById('edit_week_number');
                   const dayElement = document.getElementById('edit_day');

                   console.log(routeElement, teamElement, shiftElement, weekNumberElement, dayElement); // Log the elements

                   if (routeElement) routeElement.value = data.route_id;
                   if (teamElement) teamElement.value = data.team_id;
                   if (shiftElement) shiftElement.value = data.shift_id;
                   if (weekNumberElement) weekNumberElement.value = data.week_number;
                   if (dayElement) dayElement.textContent = data.day; // Set the day as text
               }
           })
           .catch(error => console.error('Error loading schedule data:', error));
   }
</script>






</main>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<script>
let map;
let directionsService;
let directionsRenderer;

function initMap() {
    map = new google.maps.Map(document.getElementById("map-container"), {
        center: { lat: 6.2173037, lng: 80.2564385 }, // Default center
        zoom: 11
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true
    });
}

function updateMap(collectionId) {
    if (!collectionId) return;

    // Fetch collection route data from controller
    fetch(`${URLROOT}/vehiclemanager/getCollectionRoute/${collectionId}`)
        .then(response => response.json())
        .then(data => {
            // Clear previous route
            directionsRenderer.setDirections({routes: []});
            if (window.markers) {
                window.markers.forEach(marker => marker.setMap(null));
            }
            window.markers = [];

            // Create waypoints from supplier locations
            const waypoints = data.suppliers.slice(1, -1).map(stop => ({
                location: { lat: parseFloat(stop.latitude), lng: parseFloat(stop.longitude) },
                stopover: true
            }));

            // Create route
            const request = {
                origin: { lat: parseFloat(data.start_location.latitude), lng: parseFloat(data.start_location.longitude) },
                destination: { lat: parseFloat(data.end_location.latitude), lng: parseFloat(data.end_location.longitude) },
                waypoints: waypoints,
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                    
                    // Add markers for each stop
                    data.suppliers.forEach((stop, index) => {
                        const marker = new google.maps.Marker({
                            position: { 
                                lat: parseFloat(stop.latitude), 
                                lng: parseFloat(stop.longitude) 
                            },
                            map: map,
                            title: stop.name,
                            label: (index + 1).toString()
                        });
                        window.markers.push(marker);

                        // Highlight current stop if exists
                        if (index === data.current_stop) {
                            marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
                        }
                    });
                }
            });
            // Update collection details
            document.getElementById("team-name").textContent = data.team_name;
            document.getElementById("route-name").textContent = data.route_name;
        })
        .catch(error => console.error('Error updating map:', error));
}

// Initialize the map when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    if (typeof google !== 'undefined') {
        initMap();
    }

    // Remove reference to non-existent form
    // const assignCollectionForm = document.getElementById('assignCollectionForm');
    // if (assignCollectionForm) {
    //     assignCollectionForm.addEventListener('submit', function(e) {
    //         // ... form handling code ...
    //     });
    // }

    // Select first collection by default if exists
    const selectElement = document.getElementById('ongoing-collection-select');
    if (selectElement && selectElement.options.length > 1) {
        selectElement.selectedIndex = 1;
        updateCollectionDetails(selectElement.value);
    }
});

// Update when collection is selected? 
document.getElementById("ongoing-collection-select").addEventListener("change", function() {
    updateMap(this.value);
});
</script>

<script>
    // Example of handling form submission for assigning collection
    document.getElementById('assignCollectionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const route = document.getElementById('route').value;
        const team = document.getElementById('team').value;
        const shift = document.getElementById('shift').value;

        // Log or handle the assigned collection details
        console.log(`Collection Assigned: Route - ${route}, Team - ${team}, Shift - ${shift}`);
        alert('Collection assigned successfully!');
    });

    // Example logic for adding late suppliers to ongoing collections
    document.querySelectorAll('.assign-btn').forEach(button => {
        button.addEventListener('click', function() {
            alert('Supplier added to collection!');
        });
    });
</script>

<style>
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .form-group select {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    
    .btn-submit {
        padding: 0.5rem 1rem;
        background-color: var(--main);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .btn-submit:hover {
        background-color: var(--main-dark);
    }
</style>

<style>
#map-container {
    margin-bottom: 1rem;
    border-radius: 5px;
    overflow: hidden;
}

#collection-details {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
}

#collection-details h4 {
    margin-top: 0;
    margin-bottom: 0.5rem;
}

#ongoing-collection-select {
    margin-left: 1rem;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    border: 1px solid #ddd;
}
</style>

<style>
.status-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-btn.active {
    background-color: #4CAF50;
    color: white;
}

.status-btn.inactive {
    background-color: #f44336;
    color: white;
}

.status-btn:hover {
    opacity: 0.8;
}

.delete-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    background-color: #f44336;
    color: white;
    cursor: pointer;
}

.delete-btn:hover {
    background-color: #da190b;
}
</style>

<style>
.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9em;
    font-weight: 500;
}

.status-badge.ongoing {
    background-color: #ffd700;
    color: #000;
}

.status-badge.completed {
    background-color: #4CAF50;
    color: white;
}

.status-badge.delayed {
    background-color: #ff6b6b;
    color: white;
}

.progress-bar {
    width: 100%;
    height: 10px;
    background-color: #f0f0f0;
    border-radius: 5px;
    position: relative;
    overflow: hidden;
}

.progress-bar .progress {
    height: 100%;
    background-color: #4CAF50;
    border-radius: 5px;
    transition: width 0.3s ease;
}

.progress-bar span {
    position: absolute;
    right: 5px;
    top: -15px;
    font-size: 0.8em;
    color: #666;
}

/* Adjust the grid layout for smaller screens */
@media screen and (max-width: 1200px) {
    .table-data {
        grid-template-columns: 1fr;
    }
}
</style>

<style>
.table-data {
    transition: all 0.3s ease-in-out;
    opacity: 1;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Apply animation when the section appears */
.table-data {
    animation: fadeIn 0.3s ease-in-out;
}
</style>

<style>
.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9em;
    font-weight: 500;
}

.status-badge.pending {
    background-color: #ffa500;
    color: white;
}

.status-badge.in-progress {
    background-color: #007bff;
    color: white;
}

.start-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9em;
}

.start-btn:hover {
    background-color: #45a049;
}

.start-btn i {
    font-size: 1.2em;
}
</style>

<style>
/* Add this to your existing styles */
.status {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9em;
    font-weight: 500;
}

.status.pending {
    background-color: #ffd700;
    color: #000;
}

.status.in-progress {
    background-color: #007bff;
    color: white;
}

.status.completed {
    background-color: #4CAF50;
    color: white;
}

.status.cancelled {
    background-color: #f44336;
    color: white;
}
</style>

<script>
function markNoShow(recordId) {
    if (confirm('Are you sure you want to mark this supplier as No Show?')) {
        fetch(`${URLROOT}/vehiclemanager/updateSupplierStatus/${recordId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: 'No Show' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the collection details
                updateCollectionDetails(document.getElementById('ongoing-collection-select').value);
            } else {
                alert('Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    }
}

function removeSupplier(recordId) {
    if (confirm('Are you sure you want to remove this supplier from the collection?')) {
        fetch(`${URLROOT}/vehiclemanager/removeCollectionSupplier/${recordId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'Removed' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the collection details
                updateCollectionDetails(document.getElementById('ongoing-collection-select').value);
            } else {
                alert('Can only remove suppliers that were added to the collection prior to collection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the supplier');
        });
    }
}

// Update the collection details function
function updateCollectionDetails(collectionId) {
    if (!collectionId) return;
    console.log('Updating collection details for:', collectionId);

    // Update map
    updateMap(collectionId);

    // Fetch collection details and supplier records
    fetch(`${URLROOT}/vehiclemanager/getCollectionDetails/${collectionId}`)
        .then(response => response.json())
        .then(data => {
            // Update basic details
            console.log('Received data:', data);
            document.getElementById("team-name").textContent = data.team_name;
            document.getElementById("route-name").textContent = data.route_name;

            // Update supplier status table
            const tbody = document.getElementById('supplier-status-body');
            tbody.innerHTML = '';

            data.suppliers.forEach(supplier => {
                const row = `
                    <tr>
                        <td>${supplier.supplier_name}</td>
                        <td>${supplier.status}</td>
                        <td>${supplier.quantity || '0'}</td>
                        <td>${supplier.collection_time || '-'}</td>
                        <td>
                            <div class="action-buttons">
                                ${supplier.status !== 'No Show' ? 
                                    `<button onclick="markNoShow(${supplier.record_id})" class="btn-small btn-warning">
                                        <i class='bx bx-x'></i> No Show
                                    </button>` : ''
                                }
                                ${supplier.status !== 'Removed' ? 
                                    `<button onclick="removeSupplier(${supplier.record_id})" class="btn-small btn-danger">
                                        <i class='bx bx-trash'></i> Remove
                                    </button>` : ''
                                }
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching collection details');
        });
}

// Add event listener for dropdown change
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('ongoing-collection-select');
    if (selectElement) {
        selectElement.addEventListener('change', function() {
            updateCollectionDetails(this.value);
        });

        // Select first collection by default if exists
        if (selectElement.options.length > 1) {
            selectElement.selectedIndex = 1;
            updateCollectionDetails(selectElement.value);
        }
    }
});
</script>

<style>
.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-small {
    padding: 3px 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.8em;
}

.btn-warning {
    background-color: #ffa500;
    color: white;
}

.btn-danger {
    background-color: #f44336;
    color: white;
}

.btn-small:hover {
    opacity: 0.8;
}
</style>

<script>
function updateCountdown() {
    const countdownElement = document.querySelector('.countdown');
    if (!countdownElement) return;

    const startTime = new Date(countdownElement.dataset.startTime).getTime();
    const endTime = new Date(countdownElement.dataset.endTime).getTime();
    const windowTime = startTime - (10 * 60 * 1000); // 10 minutes before
    let hasReloaded = false; // Flag to prevent multiple reloads

    function update() {
        const now = new Date().getTime();
        const distanceToStart = windowTime - now;
        const distanceToEnd = endTime - now;

        if (distanceToStart < 0 && distanceToEnd > 0 && !hasReloaded) {
            countdownElement.innerHTML = "You can now mark yourself as ready!";
            // Optionally, you can enable the "Mark as Ready" button here
            // document.querySelector('.btn-primary').disabled = false;
            hasReloaded = true; // Set the flag to true to prevent further reloads
            // location.reload(); // Uncomment if you still want to reload once
            return;
        }

        if (distanceToStart > 0) {
            const hours = Math.floor(distanceToStart / (1000 * 60 * 60));
            const minutes = Math.floor((distanceToStart % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distanceToStart % (1000 * 60)) / 1000);

            countdownElement.innerHTML = `Time until ready: ${hours}h ${minutes}m ${seconds}s`;
        } else {
            countdownElement.innerHTML = "Shift has started, you can still mark yourself as ready!";
        }
    }

    update();
    setInterval(update, 1000);
}

document.addEventListener('DOMContentLoaded', updateCountdown);





<?php require APPROOT . '/views/inc/components/footer.php'; ?>