<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Leaf Supply Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>


    
    <!-- Bar Graph and Collection Stats Row -->
    <div class="table-data">
        <!-- Monthly Collection Graph -->
        <div class="order" style="flex: 1.4;">
            <div class="head">
                <h3>Monthly Collection Overview - 2024</h3>
                <div class="head-actions">
                    <button class="btn-download" onclick="downloadMonthlyStats()">
                        <i class='bx bx-download'></i> Export Report
                    </button>
                </div>
            </div>
            <div class="graph-container">
                <canvas id="monthlyCollectionGraph"></canvas>
            </div>
        </div>

        <!-- Collection Stats -->
        <div class="order" style="flex: 0.8;">
            <div class="head">
                <h3>Route Distribution</h3>
            </div>
            


            <!-- Route Distribution Pie Chart -->
            <div class="route-chart-container">
                <canvas id="routeDistributionChart"></canvas>
            </div>
        </div>
    </div>



    <!-- Routes and Suppliers -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Route Details</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Route Name</th>
                        <th>Total Suppliers</th>
                        <th>Total Supply (kg)</th>
                        <th>Average Supply (kg)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="route-row" data-route="route1">
                        <td>Deniyaya Route</td>
                        <td>8</td>
                        <td>2,500</td>
                        <td>312.5</td>
                        <td><span class="status-badge completed">Active</span></td>
                        <td>
                            <button class="btn-action toggle-suppliers" onclick="toggleSuppliers('route1')">
                                <i class='bx bx-chevron-down'></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Suppliers for Route 1 -->
                    <tr class="supplier-details route1" style="display: none;">
                        <td colspan="6">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Supplier ID</th>
                                        <th>Name</th>
                                        <th>Monthly Average (kg)</th>
                                        <th>Last Supply</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>SUP001</td>
                                        <td>John Doe</td>
                                        <td>350</td>
                                        <td>320</td>
                                        <td><span class="performance good">Above Average</span></td>
                                    </tr>
                                    <tr>
                                        <td>SUP002</td>
                                        <td>Jane Smith</td>
                                        <td>280</td>
                                        <td>260</td>
                                        <td><span class="performance average">Average</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- Repeat for other routes -->
                    <tr class="route-row" data-route="route2">
                        <td>Morawaka Route</td>
                        <td>6</td>
                        <td>1,800</td>
                        <td>300</td>
                        <td><span class="status-badge completed">Active</span></td>
                        <td>
                            <button class="btn-action toggle-suppliers" onclick="toggleSuppliers('route2')">
                                <i class='bx bx-chevron-down'></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Suppliers for Route 2 -->
                    <tr class="supplier-details route2" style="display: none;">
                        <td colspan="6">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Supplier ID</th>
                                        <th>Name</th>
                                        <th>Monthly Average (kg)</th>
                                        <th>Last Supply</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>SUP003</td>
                                        <td>Mike Johnson</td>
                                        <td>310</td>
                                        <td>290</td>
                                        <td><span class="performance average">Average</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
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
    height: 350px;
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
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<script>
// Monthly collection data
const monthlyData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    collections: [35000, 32000, 38000, 40000, 35000, 37000, 42000, 41000, 38000, 36000, 39000, 41000]
};

// Route distribution data
const routeData = {
    labels: ['Route A', 'Route B', 'Route C', 'Route D', 'Route E', 'Route F', 'Route G', 'Route H'],
    collections: [2500, 1800, 1500, 1700, 1200, 1400, 1300, 1100]
};

// Initialize Monthly Collection Chart
const monthlyChart = new Chart(document.getElementById('monthlyCollectionGraph'), {
    type: 'bar',
    data: {
        labels: monthlyData.labels,
        datasets: [{
            data: monthlyData.collections,
            backgroundColor: 'rgba(53, 127, 164, 0.8)',
            borderColor: '#357FA4',
            borderWidth: 1,
            barPercentage: 0.7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Collection Amount (kg)'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `Collection: ${context.raw.toLocaleString()} kg`;
                    }
                }
            }
        }
    }
});

// Initialize Route Distribution Pie Chart
const routeChart = new Chart(document.getElementById('routeDistributionChart'), {
    type: 'pie',
    data: {
        labels: routeData.labels,
        datasets: [{
            data: routeData.collections,
            backgroundColor: [
                '#FF9F40',
                '#4BC0C0',
                '#36A2EB',
                '#9966FF',
                '#FF6384',
                '#FFD700',
                '#90EE90',
                '#87CEEB'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 12
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${value.toLocaleString()}kg (${percentage}%)`;
                    }
                }
            }
        }
    }
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

<style>
.supplier-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.supplier-table th,
.supplier-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.supplier-table th {
    background: #f5f5f5;
    font-weight: 600;
    font-size: 0.85rem;
}

.supplier-details td {
    padding: 0 15px;
    background: #f8f9fa;
}
</style>

<script>
function toggleSuppliers(routeId) {
    const supplierRow = document.querySelector(`.supplier-details.${routeId}`);
    const button = document.querySelector(`[data-route="${routeId}"] .toggle-suppliers`);
    
    if (supplierRow.style.display === 'none') {
        supplierRow.style.display = 'table-row';
        button.classList.add('active');
    } else {
        supplierRow.style.display = 'none';
        button.classList.remove('active');
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>