<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>

<?php print_r($data); ?>
    <div class="head-title">
        <div class="left">
            <h1>Collection Details</h1>
            <ul class="breadcrumb">
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">#TC-2024-001</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Status Card -->
    <div class="status-card">
        <div class="status-header">
            <div class="status-badge ongoing">In Progress</div>
            <div class="collection-id">#20240001</div>
        </div>
    </div>

    <!-- After the status card and before details grid -->
    <div class="map-container">
        <div id="map"></div>
    </div>

    <?php
    $status = "In Progress"; // Replace with actual status variable
    $totalQuantity = "50 kg"; // Replace with actual total quantity variable
    $collectionTime = "08:00 AM"; // Replace with actual collection time variable
    ?>

    <!-- Collection Details -->
    <div class="details-grid">
        <!-- Vehicle Information -->
        <div class="detail-card" onclick="toggleCard(this)">
            <div class="card-header">
                <i class='bx bxs-truck'></i>
                <h3>Collection Information</h3>
            </div>
            <div class="card-content">
                <div class="info-row">
                    <span class="label">Vehicle Number:</span>
                    <span class="value"><?php echo $collectionDetails->license_plate; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Type:</span>
                    <span class="value"><?php echo $collectionDetails->vehicle_type; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value"><?php echo $collectionDetails->collection_status; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Driver:</span>
                    <span class="value"><?php echo $collectionDetails->first_name . ' ' . $collectionDetails->last_name; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Route Name:</span>
                    <span class="value"><?php echo $collectionDetails->route_name; ?></span>
                </div>
            </div>
        </div>


        <!-- Bag Details Section -->
        <div class="bag-details-section">
            <h3>Bag Details</h3>
            <?php
            // Example array of bags; replace with actual data from your database
            $bags = [
                [
                    'bag_id' => 'BAG-001',
                    'actual_weight_kg' => 10.5,
                    'leaf_age' => 'Fresh',
                    'moisture_level' => 'Low',
                    'deduction_notes' => 'None',
                    'leaf_type_id' => 'Leaf Type A'
                ],
                [
                    'bag_id' => 'BAG-002',
                    'actual_weight_kg' => 12.0,
                    'leaf_age' => 'Mature',
                    'moisture_level' => 'Medium',
                    'deduction_notes' => 'Minor damage',
                    'leaf_type_id' => 'Leaf Type B'
                ],
                // Add more bags as needed
            ];

            foreach ($bags as $bag) {
                ?>
                <div class="detail-card" onclick="toggleCard(this)">
                    <div class="card-header">
                        <i class='bx bx-package'></i>
                        <h3>Bag ID: <?php echo $bag['bag_id']; ?></h3>
                    </div>
                    <div class="card-content">
                        <div class="info-row">
                            <span class="label">Actual Weight (kg):</span>
                            <span class="value"><?php echo $bag['actual_weight_kg']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Leaf Age:</span>
                            <span class="value"><?php echo $bag['leaf_age']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Moisture Level:</span>
                            <span class="value"><?php echo $bag['moisture_level']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Deduction Notes:</span>
                            <span class="value"><?php echo $bag['deduction_notes']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Leaf Type ID:</span>
                            <span class="value"><?php echo $bag['leaf_type_id']; ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <!-- After map container -->
    <div class="confirm-container">
    <button class="confirm-button" disabled>
        <i class='bx bx-check'></i>
        <span>Confirm Collection</span>
    </button>
    </div>
</main>

<style>

:root {
  /* Color Variables */
  --primary-color: var(--mainn);
  --secondary-color: #2ecc71;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --background-light: #f8f9fa;
  --border-color: #e0e0e0;
  --success-color: #27ae60;
  --warning-color: #f39c12;
  
  /* Spacing */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;
  
  /* Border Radius */
  --border-radius-sm: 4px;
  --border-radius-md: 8px;
  --border-radius-lg: 12px;
}

.status-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.collection-id {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2b2b2b;
}

.status-time {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.detail-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: max-height 0.3s ease;
}

.card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    color: #008000;
}

.card-header i {
    font-size: 1.5rem;
}

.card-header h3 {
    font-size: 1.1rem;
    margin: 0;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-row:last-child {
    border-bottom: none;
}

.label {
    color: #666;
}

.value {
    font-weight: 500;
    color: #2b2b2b;
}

.timeline-section {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 45px;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ddd;
    border: 4px solid white;
    box-shadow: 0 0 0 2px #ddd;
}

.timeline-item.completed .timeline-marker {
    background: #008000;
    box-shadow: 0 0 0 2px rgba(0, 128, 0, 0.2);
}

.timeline-item.active .timeline-marker {
    background: #008000;
    box-shadow: 0 0 0 2px rgba(0, 128, 0, 0.2);
    animation: pulse 2s infinite;
}

.timeline-content h4 {
    margin: 0;
    color: #2b2b2b;
}

.timeline-content p {
    margin: 5px 0 0;
    color: #666;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 128, 0, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(0, 128, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 128, 0, 0);
    }
}

@media screen and (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .info-row {
        flex-direction: column;
        gap: 5px;
    }
    
    .timeline-item {
        padding-left: 35px;
    }
    
    .timeline-marker {
        width: 16px;
        height: 16px;
    }
}

.pending-value {
    color: #888;
    font-style: italic;
}

.detail-card .info-row {
    padding: 12px 0;
}

.detail-card .label {
    font-weight: 500;
}

.map-container {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

#map {
    width: 100%;
    height: 300px;
    border-radius: 10px;
}

@media screen and (max-width: 768px) {
    #map {
        height: 250px;
    }
}

.confirm-container {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.confirm-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 30px;
    background: #cccccc;  /* Grey background for disabled state */
    color: #666666;      /* Darker grey text */
    border: none;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 500;
    cursor: not-allowed;  /* Shows not-allowed cursor when hovering */
    transition: all 0.3s ease;
}

.confirm-button i {
    font-size: 1.2rem;
}

.section-divider {
  height: 1px;
  background-color: var(--border-color);
  margin: var(--spacing-xl) 0;
}

.card-content {
    display: block;
}

.detail-card .card-content {
    display: none;
}
</style>


<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script>
    let map;
    
    function initMap() {
        const driverLocation = { lat: 6.2173037, lng: 80.2564385 };
        
        map = new google.maps.Map(document.getElementById("map"), {
            center: driverLocation,
            zoom: 18,
            mapTypeControl: false,      // Removes satellite/map option
            zoomControl: false,         // Removes zoom +/- buttons
            streetViewControl: false,   // Removes pegman/street view
            fullscreenControl: true     // Keeps expand option
        });

        // Driver marker
        new google.maps.Marker({
            position: driverLocation,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
            },
            title: "Driver Location"
        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>

<script>
function toggleCard(card) {
    const content = card.querySelector('.card-content');
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
}
</script>
