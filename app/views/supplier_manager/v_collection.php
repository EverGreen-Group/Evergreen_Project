<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Supplier Manager Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bx-leaf'></i>
            <span class="text">
                <h3>12,500</h3>
                <p>Total Collection</p>
                <small>Today's Target (kg)</small>
            </span>
        </li>
        <li>
            <i class='bx bx-group'></i>
            <span class="text">
                <h3>24</h3>
                <p>Active Suppliers</p>
                <small>8 Routes Assigned</small>
            </span>
        </li>
        <li>
            <i class='bx bx-trending-down'></i>
            <span class="text">
                <h3>3</h3>
                <p>Low Performers</p>
                <small>Below 10% Threshold</small>
            </span>
        </li>
    </ul>

    
    <!-- Bar Graph and Collection Stats Row -->
    <div class="table-data">
        <!-- Weekly Collection Graph -->
        <div class="order" style="flex: 1.4;">
            <div class="head">
                <h3>Weekly Collection Overview</h3>
                <div class="head-actions">
                    <button class="btn-download" onclick="downloadMonthlyStats()">
                        <i class='bx bx-download'></i> Download Monthly Stats
                    </button>
                    <select id="week-select">
                        <option value="current">Current Week</option>
                        <option value="last">Last Week</option>
                    </select>
                </div>
            </div>
            <div class="graph-container">
                <canvas id="teaLeavesGraph"></canvas>
            </div>
        </div>

        <!-- Collection Stats -->
        <div class="order" style="flex: 0.8;">
            <div class="head">
                <h3>Today's Collection Stats</h3>
            </div>
            
            <!-- Stats Summary Box -->
            <div class="stats-summary">
                <div class="summary-item">
                    <span class="label">Total Collections</span>
                    <span class="value">24</span>
                </div>
                <div class="divider"></div>
                <div class="summary-item">
                    <span class="label">Assigned Routes</span>
                    <span class="value">8</span>
                </div>
                <div class="divider"></div>
                <div class="summary-item">
                    <span class="label">Expected Collection</span>
                    <span class="value">12,500 kg</span>
                </div>
            </div>

            <!-- Progress Ring Chart -->
            <div class="progress-chart-container">
                <canvas id="collectionProgress"></canvas>
                <div class="progress-center">
                    <span class="progress-percentage" id="progress-percentage">65%</span>
                    <span class="progress-label">Collected</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Ongoing Collections Row -->
    <div class="table-data">
        <!-- Map Section -->
        <div class="order" style="flex: 0.8;">
            <div class="head">
                <h3>Track Ongoing Collection</h3>
                <select id="ongoing-collection-select">
                    <option value="">Select a collection</option>
                    <option value="collection1">Collection 1 - Team A</option>
                    <option value="collection2">Collection 2 - Team B</option>
                </select>
            </div>
            <div id="map-container" style="height: 300px; width: 100%;"></div>
            <div id="collection-details">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="label">Team:</span>
                        <span id="team-name" class="value"></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Route:</span>
                        <span id="route-name" class="value"></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Progress:</span>
                        <span id="collection-progress" class="value"></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Est. Time:</span>
                        <span id="estimated-time" class="value"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ongoing Collections Section -->
        <div class="order" style="flex: 1.4;">
            <div class="head">
                <h3>Ongoing Collections</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Team</th>
                        <th>Route</th>
                        <th>Progress</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>OC001</td>
                        <td>Team Alpha</td>
                        <td>Route 23</td>
                        <td>5/10</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: 45%;">45%</div>
                            </div>
                        </td>
                        <td>
                            <button class="btn-action view-btn" title="View Details">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="btn-action add-supplier-btn" title="Add Supplier">
                                <i class='bx bx-plus'></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>OC002</td>
                        <td>Team Beta</td>
                        <td>Route 15</td>
                        <td>8/12</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: 75%;">75%</div>
                            </div>
                        </td>
                        <td>
                            <button class="btn-action view-btn" title="View Details">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="btn-action add-supplier-btn" title="Add Supplier">
                                <i class='bx bx-plus'></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>OC003</td>
                        <td>Team Gamma</td>
                        <td>Route 31</td>
                        <td>2/8</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: 25%;">25%</div>
                            </div>
                        </td>
                        <td>
                            <button class="btn-action view-btn" title="View Details">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="btn-action add-supplier-btn" title="Add Supplier">
                                <i class='bx bx-plus'></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Completed Collections Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Completed Collections</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Supplier ID</th>
                        <th>Expected (kg)</th>
                        <th>Actual (kg)</th>
                        <th>Variance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CC001</td>
                        <td>SUP123</td>
                        <td>500</td>
                        <td>520</td>
                        <td class="variance positive">+20 kg</td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="btn-action view-btn" title="View Logbook">
                                <i class='bx bx-book-open'></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>CC002</td>
                        <td>SUP456</td>
                        <td>750</td>
                        <td>680</td>
                        <td class="variance negative">-70 kg</td>
                        <td><span class="status-badge completed">Completed</span></td>
                        <td>
                            <button class="btn-action view-btn" title="View Logbook">
                                <i class='bx bx-book-open'></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Collection Constraints Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Collection Constraints</h3>
                <button class="btn-action save-constraints" title="Save Changes">
                    <i class='bx bx-save'></i> Save Changes
                </button>
            </div>

            <!-- Constraint Settings -->
            <div class="constraints-container">
                <div class="constraint-card">
                    <div class="constraint-header">
                        <i class='bx bx-filter-alt'></i>
                        <h4>Variance Thresholds</h4>
                    </div>
                    <div class="constraint-content">
                        <div class="constraint-item">
                            <label>Minimum Collection Threshold</label>
                            <div class="threshold-input">
                                <input type="number" id="minCollectionThreshold" value="10" min="0" max="100">
                                <span class="unit">%</span>
                            </div>
                            <small>Alert if collection is below this % of previous collection</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtered Suppliers Table -->
            <div class="filtered-suppliers">
                <div class="sub-head">
                    <h4>Suppliers Below Threshold</h4>
                    <span class="alert-count">3 suppliers found</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Name</th>
                            <th>Previous Collection</th>
                            <th>Latest Collection</th>
                            <th>Variance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SUP789</td>
                            <td>John Doe</td>
                            <td>500 kg</td>
                            <td>35 kg</td>
                            <td class="variance negative">-93%</td>
                            <td>
                                <button class="btn-action view-btn" title="View History">
                                    <i class='bx bx-history'></i>
                                </button>
                                <button class="btn-action contact-btn" title="Contact Supplier">
                                    <i class='bx bx-phone'></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>SUP456</td>
                            <td>Jane Smith</td>
                            <td>750 kg</td>
                            <td>60 kg</td>
                            <td class="variance negative">-92%</td>
                            <td>
                                <button class="btn-action view-btn" title="View History">
                                    <i class='bx bx-history'></i>
                                </button>
                                <button class="btn-action contact-btn" title="Contact Supplier">
                                    <i class='bx bx-phone'></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
/* Box Layout */
.table-data {
    display: flex;
    flex-wrap: wrap;
    grid-gap: 24px;
    margin-top: 24px;
    width: 100%;
    color: var(--dark);
}

.table-data > div {
    border-radius: 20px;
    background: var(--light);
    padding: 24px;
    overflow-x: auto;
}

.table-data .head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    grid-gap: 16px;
    margin-bottom: 24px;
}

/* Graph Styles */
.graph-container {
    width: 100%;
    height: 400px;
    padding: 20px;
}

#teaLeavesGraph {
    width: 100% !important;
    height: 100% !important;
}

/* Collection Stats */
.stats-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    margin: 1rem 0;
    border: 2px solid var(--main);
    border-radius: 8px;
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

/* Progress Indicators */
.progress-bar {
    width: 100%;
    height: 15px;
    background: var(--grey);
    border-radius: 10px;
    overflow: hidden;
}

.progress {
    height: 100%;
    background: var(--main);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--light);
    font-size: 11px;
}

/* Map Section */
#map-container {
    height: 300px;
    width: 100%;
    margin-bottom: 1rem;
    border-radius: 5px;
    overflow: hidden;
}

#collection-details {
    background: var(--grey);
    padding: 1rem;
    border-radius: 5px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

/* Buttons and Actions */
.btn-download {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--main);
    color: var(--light);
    border-radius: 4px;
    cursor: pointer;
}

.btn-action {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background: transparent;
}

/* Constraints Section */
.constraints-container {
    margin-bottom: 2rem;
}

.constraint-card {
    background: var(--light);
    border: 1px solid var(--grey);
    border-radius: 8px;
    overflow: hidden;
}

.constraint-header {
    background: var(--grey);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 1px solid var(--grey);
}

/* Status Indicators */
.variance {
    font-weight: bold;
}

.variance.positive { color: var(--success); }
.variance.negative { color: var(--danger); }

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.status-badge.completed {
    background: var(--main);
    color: var(--light);
}

/* Table Styles */
.table-data table {
    width: 100%;
    border-collapse: collapse;
}

.table-data table th {
    padding: 12px 10px;
    font-size: 14px;
    text-transform: uppercase;
    background: var(--grey);
    text-align: left;
    font-weight: 600;
}

.table-data table td {
    padding: 12px 10px;
    font-size: 14px;
    border-bottom: 1px solid var(--grey);
    vertical-align: middle;
}

/* Form Elements */
select, input[type="number"] {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid var(--grey);
}

/* Progress Chart */
.progress-chart-container {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 2rem auto;
}

.progress-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.progress-percentage {
    display: block;
    font-size: 1.8rem;
    font-weight: 600;
    color: #007664;
}

.progress-label {
    display: block;
    font-size: 0.9rem;
    color: #666;
    margin-top: 0.2rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<script>
// Weekly collection data (in kilograms)
const weeklyData = {
    current: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        collections: [1200, 1450, 1350, 1600, 1500, 800]
    },
    last: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        collections: [1300, 1550, 1250, 1500, 1400, 750]
    }
};

// Initialize the chart
const ctx = document.getElementById('teaLeavesGraph').getContext('2d');
let weeklyChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: weeklyData.current.labels,
        datasets: [{
            label: 'Current Week',
            data: weeklyData.current.collections,
            backgroundColor: 'rgba(141, 159, 45, 0.8)',  // #8D9F2D with opacity
            borderColor: '#8D9F2D',
            borderWidth: 1,
            barPercentage: 0.6,
            categoryPercentage: 0.5
        },
        {
            label: 'Previous Week',
            data: weeklyData.last.collections,
            backgroundColor: 'rgba(0, 118, 100, 0.6)', // #007664 with opacity
            borderColor: '#007664',
            borderWidth: 1,
            barPercentage: 0.6,
            categoryPercentage: 0.5
        },
        {
            label: 'Change',
            data: weeklyData.current.collections.map((curr, index) => {
                const prev = weeklyData.last.collections[index];
                return curr - prev;
            }),
            backgroundColor: (context) => {
                const change = context.dataset.data[context.dataIndex];
                return change >= 0 ? 'rgba(76, 175, 80, 0.5)' : 'rgba(244, 67, 54, 0.5)';
            },
            borderColor: (context) => {
                const change = context.dataset.data[context.dataIndex];
                return change >= 0 ? '#4CAF50' : '#f44336';
            },
            borderWidth: 1,
            hidden: true,  // Hidden by default
            barPercentage: 0.4,
            categoryPercentage: 0.5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            x: {
                stacked: false,
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Collection Amount (kg)'
                }
            }
        },
        plugins: {
            title: {
                display: false
            },
            legend: {
                position: 'top',
                align: 'end',
                onClick: function(e, legendItem, legend) {
                    const index = legendItem.datasetIndex;
                    const ci = legend.chart;
                    
                    if (index === 2) {  // Change dataset
                        ci.data.datasets[0].hidden = !ci.data.datasets[0].hidden;
                        ci.data.datasets[1].hidden = !ci.data.datasets[1].hidden;
                        ci.data.datasets[2].hidden = !ci.data.datasets[2].hidden;
                    } else {
                        ci.data.datasets[index].hidden = !ci.data.datasets[index].hidden;
                    }
                    ci.update();
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.dataset.label || '';
                        const value = context.parsed.y;
                        
                        if (context.datasetIndex === 2) {  // Change dataset
                            const sign = value >= 0 ? '+' : '';
                            return `${label}: ${sign}${value} kg`;
                        }
                        return `${label}: ${value} kg`;
                    }
                }
            }
        }
    }
});

// Update the week selection event listener
document.getElementById('week-select').addEventListener('change', function() {
    const selectedWeek = this.value;
    if (selectedWeek === 'current') {
        weeklyChart.data.datasets[0].data = weeklyData.current.collections;
        weeklyChart.data.datasets[1].data = weeklyData.last.collections;
    } else {
        weeklyChart.data.datasets[0].data = weeklyData.last.collections;
        weeklyChart.data.datasets[1].data = weeklyData.current.collections;
    }
    // Recalculate changes
    weeklyChart.data.datasets[2].data = weeklyChart.data.datasets[0].data.map((curr, index) => {
        const prev = weeklyChart.data.datasets[1].data[index];
        return curr - prev;
    });
    weeklyChart.update();
});
</script>

<style>
.collection-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
}

.stat-card {
    background-color: #f8f9fa;
    padding: 1.2rem;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: px solid var(--main);
}

.stat-info {
    flex: 1;
}

.stat-label {
    display: block;
    color: #555;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.stat-subtext {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.2rem;
}

.stat-icon {
    font-size: 2rem;
    color: #007664;
    margin-left: 1rem;
}

.progress-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.progress-info .progress-bar {
    height: 8px;
    margin-top: 0.2rem;
}

.progress-info .progress {
    font-size: 0;
    background-color: #007664;
}
</style>

<style>
.head-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-download {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: #007664;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-download:hover {
    background-color: #005a4d;
}

.btn-download i {
    font-size: 1.1rem;
}
</style>

<script>
function downloadMonthlyStats() {
    // Example data - replace with actual data from your backend
    const monthlyData = [
        ['Date', 'Collection Amount (kg)', 'Number of Suppliers', 'Average per Supplier'],
        ['2024-01-01', '1200', '24', '50'],
        ['2024-01-02', '1450', '24', '60.4'],
        // ... more data rows
    ];

    // Convert data to CSV format
    const csvContent = monthlyData.map(row => row.join(',')).join('\n');

    // Create blob and download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `tea_collection_stats_${new Date().toISOString().slice(0,7)}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('collectionProgress').getContext('2d');
    const progressPercentage = 65; // Example percentage

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [progressPercentage, 100 - progressPercentage],
                backgroundColor: ['#007664', '#e0e0e0'], // Main color and grey for remaining
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%', // Adjust the thickness of the ring
            plugins: {
                tooltip: {
                    enabled: false // Disable tooltips
                }
            }
        }
    });

    // Update the percentage text
    document.getElementById('progress-percentage').textContent = `${progressPercentage}%`;
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>