<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
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



    <!-- Collection Details -->
    <div class="details-grid">
        <!-- Vehicle Information -->
        <div class="detail-card">
            <div class="card-header">
                <i class='bx bxs-truck'></i>
                <h3>Vehicle Information</h3>
            </div>
            <div class="card-content">
                <div class="info-row">
                    <span class="label">Vehicle Number:</span>
                    <span class="value">CP-2030</span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle Type:</span>
                    <span class="value">Lorry</span>
                </div>
            </div>
        </div>

        <!-- Team Information -->
        <div class="detail-card">
            <div class="card-header">
                <i class='bx bxs-group'></i>
                <h3>Collection Team</h3>
            </div>
            <div class="card-content">
                <div class="info-row">
                    <span class="label">Team ID:</span>
                    <span class="value">Team #05</span>
                </div>
                <div class="info-row">
                    <span class="label">Driver:</span>
                    <span class="value">John Doe</span>
                </div>
            </div>
        </div>

        <!-- Collection Details -->
        <div class="detail-card">
            <div class="card-header">
                <i class='bx bx-package'></i>
                <h3>Collection Details</h3>
            </div>
            <div class="card-content">
                <div class="info-row">
                    <span class="label">Scheduled Time:</span>
                    <span class="value">08:00 AM</span>
                </div>
                <div class="info-row">
                    <span class="label">Expected Quantity:</span>
                    <span class="value">20 kg</span>
                </div>
            </div>
        </div>

        <!-- Tea Leaf Details Card -->
        <div class="detail-card">
            <div class="card-header">
                <i class='bx bx-leaf'></i>
                <h3>Tea Leaf Details</h3>
            </div>
            <div class="card-content">
                <div class="info-row">
                    <span class="label">Leaf Type:</span>
                    <span class="value pending-value">Pending</span>
                </div>
                <div class="info-row">
                    <span class="label">Price per KG:</span>
                    <span class="value pending-value">Pending</span>
                </div>
                <div class="info-row">
                    <span class="label">Number of Bags:</span>
                    <span class="value pending-value">Pending</span>
                </div>
                <div class="info-row">
                    <span class="label">Total Weight:</span>
                    <span class="value pending-value">Pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Timeline -->
    <div class="timeline-section">
        <h3>Collection Progress</h3>
        <div class="timeline">
            <div class="timeline-item completed">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Collection Scheduled</h4>
                    <p>08:00 AM</p>
                </div>
            </div>
            <div class="timeline-item active">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Vehicle Dispatched</h4>
                    <p>08:15 AM</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Arrived at Location</h4>
                    <p>Pending</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Collection Complete</h4>
                    <p>Pending</p>
                </div>
            </div>
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
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap">
</script>



<style>
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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .detail-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
</style>

