<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

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

    <!-- Add right after the breadcrumb and before Box Info -->
    <div class="search-container">
        <div class="search-box">
            <i class='bx bx-search'></i>
            <input type="text" id="supplierSearch" placeholder="Search supplier by name or ID...">
        </div>
        <div class="supplier-select">
            <select id="currentSupplier" onchange="updateDashboard(this.value)">
                <option value="">Select a Supplier</option>
                <option value="1">John Doe (ID: S1001)</option>
                <option value="2">Jane Smith (ID: S1002)</option>
                <option value="3">Mike Johnson (ID: S1003)</option>
            </select>
        </div>

        <a href="#" class="btn-download" id="monthlyStatementBtn" onclick="viewMonthlyStatement()">
            <i class='bx bxs-navigation'></i> View Monthly Statement
        </a>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bx-leaf'></i>
            <span class="text">
                <h3>250</h3>
                <p>Today's Collection</p>
                <small>Daily Target: 300kg</small>
            </span>
        </li>
        <li>
            <i class='bx bx-calendar-check'></i>
            <span class="text">
                <h3>18</h3>
                <p>Collection Days</p>
                <small>This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bx-trending-up'></i>
            <span class="text">
                <h3>92%</h3>
                <p>Performance Rate</p>
                <small>Based on Target</small>
            </span>
        </li>
    </ul>

    
    <!-- Bar Graph and Collection Stats Row -->
    <div class="table-data">
        <!-- Weekly Collection Graph -->
        <div class="order" style="flex: 0.5;">
            <div class="head">
                <h3>Weekly Collection Overview</h3>
                <div class="head-actions">
                    <a href="<?php echo URLROOT; ?>/supplier_manager/supplierStatement/<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>" 
                       class="btn-download" 
                       id="statementBtn">
                        <i class='bx bx-file'></i> Monthly Statement
                    </a>
                    <button class="btn-download" onclick="downloadMonthlyStats()">
                        <i class='bx bx-download'></i> Download Stats
                    </button>
                    <select id="time-period">
                        <option value="week">Weekly View</option>
                        <option value="month">Monthly View</option>
                    </select>
                </div>
            </div>
            <div class="graph-container">
                <canvas id="teaLeavesGraph"></canvas>
            </div>
        </div>



        <!-- Supplier Profile Card -->
        <div class="order" style="flex: 0.5;">
            <div class="profile-top">
                <div class="profile-image">
                    <img src="https://i.pravatar.cc/150?img=68" alt="Supplier Avatar" class="profile-avatar">
                </div>
                <div class="profile-info">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">First Name</span>
                            <span class="detail-value">John</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Name</span>
                            <span class="detail-value">Doe</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">johndoe@gmail.com</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">NIC</span>
                            <span class="detail-value">199934567890</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Gender</span>
                            <span class="detail-value">Male</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date of Birth</span>
                            <span class="detail-value">January 15, 1999</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="table-data">
        <div class="order" style="flex: 0.6;">
            <div class="head">
                <h3>Supplier Location</h3>
            </div>
            <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
        </div>

        <div class="order" style="flex: 0.4;">
            <div class="head">
                <h3>Recent Collections</h3>
            </div>
            <table class="collection-history">
                <tbody>
                    <tr class="collection-entry">
                        <td class="collection-header">
                            04/03/2024 Type: S 1628 / 9005
                        </td>
                    </tr>
                    <tr class="collection-details">
                        <td>
                            <span class="weight">84.00</span>
                            <span class="deduction">3.50</span>
                            <span class="final-weight">80.50</span>
                            <span class="unit">kg</span>
                        </td>
                    </tr>
                    <tr class="collection-entry">
                        <td class="collection-header">
                            03/03/2024 Type: S 1627 / 9004
                        </td>
                    </tr>
                    <tr class="collection-details">
                        <td>
                            <span class="weight">92.00</span>
                            <span class="deduction">4.00</span>
                            <span class="final-weight">88.00</span>
                            <span class="unit">kg</span>
                        </td>
                    </tr>
                    <tr class="collection-entry">
                        <td class="collection-header">
                            04/03/2024 Type: B 1627 / 9004
                        </td>
                    </tr>
                    <tr class="collection-details">
                        <td>
                            <span class="weight">92.00</span>
                            <span class="deduction">4.00</span>
                            <span class="final-weight">88.00</span>
                            <span class="unit">kg</span>
                        </td>
                    </tr>
                    <!-- Add more entries as needed -->
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
    const supplierLocation = { lat: 6.2173037, lng: 80.2564385 };

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: supplierLocation,
        mapId: "4504f8b37365c3d0",
        disableDefaultUI: true,
        zoomControl: true
    });

    const marker = new google.maps.Marker({
        position: supplierLocation,
        map: map,
        title: "John Doe's Location",
        icon: {
            url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
        }
    });

    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="padding: 10px;">
                <h3 style="margin: 0 0 5px 0;">John Doe</h3>
                <p style="margin: 0;">Daily Collection Point</p>
            </div>`
    });

    marker.addListener("click", () => {
        infoWindow.open(map, marker);
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
document.getElementById('time-period').addEventListener('change', function() {
    const selectedPeriod = this.value;
    if (selectedPeriod === 'week') {
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

<style>
.supplier-card {
    background: var(--light);
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.supplier-image-container {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 1rem;
}

.supplier-profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid var(--main);
}

.status-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 2px solid white;
}

.status-indicator.active {
    background-color: var(--success);
}

.supplier-info {
    text-align: center;
}

.supplier-info h4 {
    margin: 0;
    font-size: 1.4rem;
    color: var(--dark);
}

.supplier-id {
    color: var(--grey);
    font-size: 0.9rem;
    margin: 0.3rem 0;
}

.supplier-stats {
    font-size: 1rem;
    color: var(--dark);
    margin: 0.5rem 0;
}

.supplier-rating {
    color: #ffc107;
    font-size: 1.2rem;
    margin: 0.5rem 0;
}

.supplier-rating small {
    color: var(--grey);
    margin-left: 0.5rem;
}

.supplier-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    background: var(--main);
    color: var(--light);
    cursor: pointer;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: var(--main-dark);
}

.action-btn i {
    font-size: 1.1rem;
}
</style>

<style>
.tea-distribution {
    display: flex;
    flex-direction: column;
    height: calc(100% - 60px); /* Subtract header height */
    padding: 1rem;
}

.chart-container {
    flex: 1;
    min-height: 200px; /* Adjusted height */
    position: relative;
    margin: 0.5rem 0;
}

.distribution-legend {
    padding: 1rem;
    background: rgba(0, 0, 0, 0.03);
    border-radius: 8px;
}

.legend-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 1rem;
}

.dot.normal { background-color: #4CAF50; }
.dot.super { background-color: #2196F3; }

.legend-info {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.type {
    color: var(--dark);
    font-weight: 500;
}

.amount {
    color: var(--grey);
    font-size: 0.9rem;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tea Types Distribution Chart
    const teaTypesCtx = document.getElementById('teaTypesChart').getContext('2d');
    new Chart(teaTypesCtx, {
        type: 'pie',
        data: {
            labels: ['Normal Leaves', 'Super Leaves'],
            datasets: [{
                data: [1250, 750],
                backgroundColor: ['#4CAF50', '#2196F3'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${value}kg (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Update chart when period changes
    document.getElementById('leaf-period').addEventListener('change', function() {
        // Add logic to update chart data based on selected period
    });
});
</script>

<style>
.profile-main {
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
}

.profile-header {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.profile-header h1 {
    font-size: 2.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.profile-actions {
    margin-top: 0;
}

.profile-container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0);
    overflow: hidden;
}

.profile-content {
    display: flex;
    flex-direction: column;
}

.profile-top {
    display: flex;
    align-items: center;
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
    border: 3px solid #86E211;
}

.profile-image {
    margin-right: 2rem;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0);
}

.profile-info {
    flex: 1;
    padding: 1rem;
}

.profile-name {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.profile-id {
    color: #7f8c8d;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.profile-contact {
    margin-top: 1rem;
}

.info-row-group {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.info-row {
    flex: 1;
    display: flex;
    background: #f5f5f5;
    padding: 0.75rem;
    border-radius: 4px;
}

.info-row label {
    width: 120px;
    color: #666;
    font-weight: 500;
}

.info-row p {
    margin: 0;
    color: #333;
}

.profile-contact p {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #444;
}

.profile-contact i {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 50%;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Icon specific colors */
.profile-contact i.fa-user { color: #2196F3; }
.profile-contact i.fa-envelope { color: #4CAF50; }
.profile-contact i.fa-id-card { color: #FF9800; }
.profile-contact i.fa-venus-mars { color: #9C27B0; }
.profile-contact i.fa-calendar-alt { color: #F44336; }

/* Responsive design */
@media (max-width: 768px) {
    .profile-contact {
        grid-template-columns: 1fr; /* Single column on mobile */
    }
}

.profile-details {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0);
}

.profile-details h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2c3e50;
    border-bottom: 2px solid #ecf0f1;
    padding-bottom: 0.5rem;
}

.detail-grid, .metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.detail-item, .metric-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
}

.detail-label, .metric-label {
    font-size: 0.85rem;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 0.25rem;
}

.detail-value, .metric-value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 500;
}

.metric-item {
    text-align: center;
}

.metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #27ae60;
    display: block;
    margin-bottom: 0.5rem;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.info-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
    display: flex;
    flex-direction: column;
}

.info-card.horizontal {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
}

.info-card.horizontal .card-image {
    flex: 0 0 200px;
    height: auto;
}

.info-card.horizontal .card-content {
    flex: 1;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.team-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-content {
    flex: 1;
    padding: 1rem;
}

.card-content h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.card-content p {
    font-size: 0.95rem;
    color: #34495e;
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.card-content p strong {
    font-weight: 600;
    color: #333;
}

.large-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2980b9;
    margin-bottom: 0.25rem;
}

.work-history {
    margin-bottom: 2rem;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.history-table th,
.history-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.history-table th {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #2c3e50;
    background-color: #f8f9fa;
}

.history-table td {
    font-size: 0.95rem;
}

.performance {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.performance.high {
    background-color: #d4edda;
    color: #155724;
}

.performance.medium {
    background-color: #fff3cd;
    color: #856404;
}

.performance.low {
    background-color: #f8d7da;
    color: #721c24;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.95rem;
    transition: background-color 0.3s, transform 0.1s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background-color: #86E211;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #F06E6E;
}

.btn-secondary:hover {
    background-color: #545b62;
}

.financial-grid {
    grid-template-columns: 1fr 2fr;
}

.financial-table {
    width: 100%;
    border-collapse: collapse;
}

.financial-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.financial-table td {
    padding: 0.5rem;
    font-size: 0.95rem;
}

.financial-table .amount {
    text-align: right;
    font-weight: 600;
}

.financial-table .amount.positive {
    color: #28a745;
}

.financial-table .amount.negative {
    color: #dc3545;
}

.large-text {
    font-size: 1.75rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 0.25rem;
}

.chart-container {
    width: 100%;
    max-width: 800px;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.detail-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.detail-label {
    display: block;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.detail-value {
    color: #333;
    font-weight: 500;
}

.detail-grid.single-row {
    grid-template-columns: repeat(4, 1fr);  /* Creates 4 equal columns */
    gap: 1rem;
}
</style>

<style>
.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.status.completed {
    background: #86E211;
    color: white;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

table th {
    font-weight: 600;
    color: #666;
}
</style>

<style>
.collection-history {
    width: 100%;
    border-collapse: collapse;
}

.collection-entry {
    background: #f8f9fa;
}

.collection-header {
    padding: 12px;
    font-weight: 500;
    color: #333;
    border-bottom: 1px solid #eee;
}

.collection-details td {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
    color: #666;
}

.collection-details span {
    display: inline-block;
    margin-right: 15px;
}

.weight::before {
    content: "Weight: ";
    color: #888;
}

.deduction::before {
    content: "Deduction: ";
    color: #888;
}

.final-weight::before {
    content: "Final: ";
    color: #888;
}

.unit {
    color: #888;
}
</style>

<style>
.search-container {
    display: flex;
    gap: 1rem;
    margin: 24px 0;
    align-items: center;
}

.search-box {
    flex: 1;
    position: relative;
    max-width: 500px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.search-box input {
    width: 100%;
    padding: 12px 20px 12px 45px;
    border: 2px solid #eee;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-box input:focus {
    border-color: #86E211;
    outline: none;
}

.supplier-select select {
    padding: 12px 20px;
    border: 2px solid #eee;
    border-radius: 10px;
    font-size: 1rem;
    min-width: 250px;
    background: white;
    cursor: pointer;
}

.supplier-select select:focus {
    border-color: #86E211;
    outline: none;
}
</style>

<script>
// Add this function to handle supplier search
document.getElementById('supplierSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const select = document.getElementById('currentSupplier');
    const options = select.options;

    for (let i = 0; i < options.length; i++) {
        const text = options[i].text.toLowerCase();
        if (text.includes(searchTerm) || options[i].value.includes(searchTerm)) {
            options[i].style.display = '';
        } else {
            options[i].style.display = 'none';
        }
    }
});

// Add this function to update the dashboard when a supplier is selected
function updateDashboard(supplierId) {
    if (!supplierId) return;

    // Fetch supplier data from backend
    fetch(`/api/supplier/${supplierId}`)
        .then(response => response.json())
        .then(data => {
            // Update profile info
            document.querySelector('.profile-info .detail-value').textContent = data.firstName;
            document.querySelector('.profile-info .detail-value:nth-child(2)').textContent = data.lastName;
            // ... update other profile fields

            // Update collection stats
            document.querySelector('.box-info li:nth-child(1) h3').textContent = data.todayCollection;
            document.querySelector('.box-info li:nth-child(2) h3').textContent = data.collectionDays;
            document.querySelector('.box-info li:nth-child(3) h3').textContent = data.performanceRate;

            // Update map
            updateMap(data.location);

            // Update collection history
            updateCollectionHistory(data.recentCollections);

            // Update graph
            updateCollectionGraph(data.collectionData);
        })
        .catch(error => console.error('Error:', error));
}
</script>

<script>
function viewMonthlyStatement() {
    const supplierId = document.getElementById('currentSupplier').value;
    if (!supplierId) {
        alert('Please select a supplier first');
        return;
    }
    
    window.location.href = `<?php echo URLROOT; ?>/suppliermanager/supplierStatement/${supplierId}`;
}

// Update button state when supplier changes
document.getElementById('currentSupplier').addEventListener('change', function() {
    const monthlyStatementBtn = document.getElementById('monthlyStatementBtn');
    if (this.value) {
        monthlyStatementBtn.classList.remove('disabled');
    } else {
        monthlyStatementBtn.classList.add('disabled');
    }
});
</script>

<style>
.btn-download.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
