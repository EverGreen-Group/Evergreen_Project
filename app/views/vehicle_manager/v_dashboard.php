<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Vehicle Manager Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
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
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <h3><?php echo $stats['partners']->total_partners; ?></h3>
                <p>Driving Partners</p>
                <small><?php echo $stats['partners']->available_partners; ?> Available</small>
            </span>
        </li>
    </ul>


    <!-- Ongoing Collection Tracking Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Track Ongoing Collection</h3>
                <select id="ongoing-collection-select">
                    <option value="">Select a collection</option>
                    <option value="collection1">Collection 1 - Team A</option>
                    <option value="collection2">Collection 2 - Team B</option>
                    <!-- Add more ongoing collections as needed -->
                </select>
            </div>
            <div id="map-container" style="height: 400px; width: 100%;"></div>
            <div id="collection-details">
                <h4>Collection Details</h4>
                <p><b>Team: </b><span id="team-name"></span></p>
                <p><b>Route: </b><span id="route-name"></span></p>
                <p><b>Progress: </b><span id="collection-progress"></span></p>
                <p><b>Estimated Time Remaining: </b><span id="estimated-time"></span></p>
            </div>
        </div>
    </div>


    <!-- Collection Skeletons Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Created Collections</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Skeleton ID</th>
                        <th>Route ID</th>
                        <th>Team ID</th>
                        <th>Vehicle ID</th>
                        <th>Shift ID</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['skeletons']) && !empty($data['skeletons'])): ?>
                        <?php foreach($data['skeletons'] as $skeleton): ?>
                            <tr>
                                <td>CS<?php echo str_pad($skeleton->skeleton_id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $skeleton->route_id; ?></td>
                                <td><?php echo $skeleton->team_id; ?></td>
                                <td><?php echo $skeleton->vehicle_id; ?></td>
                                <td><?php echo $skeleton->shift_id; ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($skeleton->created_at)); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionskeletons/toggleActive" method="POST" style="display: inline;">
                                        <input type="hidden" name="skeleton_id" value="<?php echo $skeleton->skeleton_id; ?>">
                                        <button type="submit" class="status-btn <?php echo $skeleton->is_active ? 'active' : 'inactive'; ?>">
                                            <?php echo $skeleton->is_active ? 'Active' : 'Inactive'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionskeletons/delete" method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this skeleton?');">
                                        <input type="hidden" name="skeleton_id" value="<?php echo $skeleton->skeleton_id; ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No collections found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create and Edit Collection Skeleton Section -->
    <div class="table-data">
        <div class="order" style="display: flex; gap: 20px;">
            <!-- Create Form -->
            <div style="flex: 1;">
                <div class="head">
                    <h3>Create New Collection</h3>
                </div>
                <form id="createSkeletonForm" method="POST" action="<?php echo URLROOT; ?>/collectionskeletons/create">
                    <div class="form-group">
                        <label for="route">Route:</label>
                        <select id="route" name="route_id" required>
                            <?php foreach ($data['routes'] as $route): ?>
                                <option value="<?= $route->route_id; ?>">Route ID: <?= $route->route_id . ", Number of Suppliers: " . $route->number_of_suppliers; ?></option>
                            <?php endforeach; ?>
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
                        <label for="vehicle">Vehicle:</label>
                        <select id="vehicle" name="vehicle_id" required>
                            <?php foreach ($data['vehicles'] as $vehicle): ?>
                                <option value="<?= $vehicle->vehicle_id; ?>"><?= $vehicle->license_plate; ?></option>
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

                    <button type="submit" class="btn-submit">Create Collection</button>
                </form>
            </div>

            <!-- Edit Form -->
            <div style="flex: 1;">
                <div class="head">
                    <h3>Edit Collection</h3>
                </div>
                <form id="editSkeletonForm" method="POST" action="<?php echo URLROOT; ?>/collectionskeletons/update">
                    <div class="form-group">
                        <label for="skeleton_id">Select Skeleton:</label>
                        <select id="skeleton_id" name="skeleton_id" required onchange="loadSkeletonData(this.value)">
                            <option value="">Select a collection</option>
                            <?php foreach ($data['skeletons'] as $skeleton): ?>
                                <option value="<?= $skeleton->skeleton_id; ?>">
                                    CS<?= str_pad($skeleton->skeleton_id, 3, '0', STR_PAD_LEFT); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Route and Team in same row -->
                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="edit_route">Route:</label>
                            <select id="edit_route" name="route_id" required>
                                <?php foreach ($data['routes'] as $route): ?>
                                    <option value="<?= $route->route_id; ?>">Route ID: <?= $route->route_id . ", Number of Suppliers: " . $route->number_of_suppliers; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="edit_team">Team:</label>
                            <select id="edit_team" name="team_id" required>
                                <?php foreach ($data['teams'] as $team): ?>
                                    <option value="<?= $team->team_id; ?>"><?= $team->team_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_vehicle">Vehicle:</label>
                        <select id="edit_vehicle" name="vehicle_id" required>
                            <?php foreach ($data['vehicles'] as $vehicle): ?>
                                <option value="<?= $vehicle->vehicle_id; ?>"><?= $vehicle->license_plate; ?></option>
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

                    <button type="submit" class="btn-submit">Update Collection</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function loadSkeletonData(skeletonId) {
        // Find the skeleton data from the existing skeletons
        <?php echo 'const skeletons = ' . json_encode($data['skeletons']) . ';'; ?>
        
        const skeleton = skeletons.find(s => s.skeleton_id === skeletonId);
        if (skeleton) {
            // Populate the edit form fields
            document.getElementById('edit_route').value = skeleton.route_id;
            document.getElementById('edit_team').value = skeleton.team_id;
            document.getElementById('edit_vehicle').value = skeleton.vehicle_id;
            document.getElementById('edit_shift').value = skeleton.shift_id;
        }
    }
    </script>

    <!-- Weekly Collection Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Weekly Collection</h3>
                <form>
                    <div class="form-group">
                        <label for="day-select">Select Day:</label>
                        <select id="day-select">
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <!-- Add options for all the days -->
                        </select>
                    </div>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Team</th>
                        <th>Suppliers</th>
                        <th>Estimated Collection (kg)</th>
                        <th>Shift</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>W001</td>
                        <td>Team 1</td>
                        <td>10</td>
                        <td>1500 kg</td>
                        <td>Morning</td>
                        <td>8:00 AM</td>
                    </tr>
                    <tr>
                        <td>W002</td>
                        <td>Team 2</td>
                        <td>15</td>
                        <td>2000 kg</td>
                        <td>Afternoon</td>
                        <td>1:00 PM</td>
                    </tr>
                    <!-- Add more weekly collections as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inactive Suppliers Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Inactive Suppliers</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Name</th>
                        <th>Average Collection (kg)</th>
                        <th>Collection Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>S1</td>
                        <td>Supplier One</td>
                        <td>1200 kg</td>
                        <td><span class="status error">Not available</span></td>
                    </tr>
                    <tr>
                        <td>S2</td>
                        <td>Supplier Two</td>
                        <td>800 kg</td>
                        <td><span class="status completed">Available</span></td>
                    </tr>
                    <!-- Add more inactive suppliers as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Missed Collections Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Suppliers Who Missed Collection</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Name</th>
                        <th>Average Collection (kg)</th>
                        <th>Ready for Collection Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>S3</td>
                        <td>Supplier Three</td>
                        <td>900 kg</td>
                        <td><span class="status completed">Ready for Collection</span></td>
                    </tr>
                    <tr>
                        <td>S4</td>
                        <td>Supplier Four</td>
                        <td>650 kg</td>
                        <td><span class="status error">Not Ready</span></td>
                    </tr>
                    <!-- Add more missed suppliers as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ongoing Collections Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Ongoing Collections</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Team</th>
                        <th>Suppliers Collected</th>
                        <th>Suppliers Left</th>
                        <th>Vehicle Capacity</th>
                        <th>Elapsed Time</th>
                        <th>Add Late Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Team 1</td>
                        <td>5/10</td>
                        <td>5</td>
                        <td>50%</td>
                        <td>1:15:30</td>
                        <td><button class="btn-submit assign-btn">Add Supplier</button></td>
                    </tr>
                    <tr>
                        <td>Team 2</td>
                        <td>3/15</td>
                        <td>12</td>
                        <td>30%</td>
                        <td>0:45:20</td>
                        <td><button class="btn-submit assign-btn">Add Supplier</button></td>
                    </tr>
                    <!-- Add more ongoing collections as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Finished Collections Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Finished Collections</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Team</th>
                        <th>Route</th>
                        <th>Shift</th>
                        <th>Suppliers Collected</th>
                        <th>Status</th>
                        <th>Completed Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>C001</td>
                        <td>Team 1</td>
                        <td>Route 1</td>
                        <td>Morning</td>
                        <td>10/10</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>11:00 AM</td>
                    </tr>
                    <tr>
                        <td>C002</td>
                        <td>Team 2</td>
                        <td>Route 2</td>
                        <td>Afternoon</td>
                        <td>15/15</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>4:00 PM</td>
                    </tr>
                    <!-- Add more finished collections as needed -->
                </tbody>
            </table>
        </div>
    </div>

</main>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<script>
let map;
let directionsService;
let directionsRenderer;
const collections = {
    collection1: {
        team: "Team A",
        route: "Evergreen Route",
        progress: "1/3 suppliers",
        estimatedTime: "2 hours",
        stops: [
            { name: "Evergreen Tea Factory", location: { lat: 6.2173037, lng: 80.2564385 } },
            { name: "Supplier 1", location: { lat: 6.243808243551064, lng: 80.25967072303547 } },
            { name: "Supplier 2", location: { lat: 6.282762791987652, lng: 80.26495604611944 } }
        ],
        currentStop: 0
    },
    collection2: {
        team: "Team B",
        route: "Southern Route",
        progress: "2/4 suppliers",
        estimatedTime: "1.5 hours",
        stops: [
            { name: "Start Point", location: { lat: 6.0535, lng: 80.2210 } },
            { name: "Supplier A", location: { lat: 6.0825, lng: 80.2510 } },
            { name: "Supplier B", location: { lat: 6.1125, lng: 80.2810 } },
            { name: "End Point", location: { lat: 6.1425, lng: 80.3110 } }
        ],
        currentStop: 2
    }
};

function initMap() {
    map = new google.maps.Map(document.getElementById("map-container"), {
        center: { lat: 6.2173037, lng: 80.2564385 }, // Centered on Evergreen Tea Factory
        zoom: 11
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true // We'll add custom markers
    });
}

function updateMap(collectionId) {
    const collection = collections[collectionId];
    if (!collection) return;

    // Clear previous route
    directionsRenderer.setDirections({routes: []});

    // Remove previous markers
    if (window.markers) {
        window.markers.forEach(marker => marker.setMap(null));
    }
    window.markers = [];

    // Create route
    const waypoints = collection.stops.slice(1, -1).map(stop => ({
        location: stop.location,
        stopover: true
    }));

    const request = {
        origin: collection.stops[0].location,
        destination: collection.stops[collection.stops.length - 1].location,
        waypoints: waypoints,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(result);
            
            // Add markers for each stop
            collection.stops.forEach((stop, index) => {
                const marker = new google.maps.Marker({
                    position: stop.location,
                    map: map,
                    title: stop.name,
                    label: (index + 1).toString()
                });
                window.markers.push(marker);

                // Highlight current stop
                if (index === collection.currentStop) {
                    marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
                }
            });
        }
    });

    // Update collection details
    document.getElementById("team-name").textContent = collection.team;
    document.getElementById("route-name").textContent = collection.route;
    document.getElementById("collection-progress").textContent = collection.progress;
    document.getElementById("estimated-time").textContent = collection.estimatedTime;
}

document.getElementById("ongoing-collection-select").addEventListener("change", function() {
    updateMap(this.value);
});

// Initialize the map with the first collection when the page loads
window.onload = function() {
    initMap();
    updateMap('collection1');
};
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



<?php require APPROOT . '/views/inc/components/footer.php'; ?>