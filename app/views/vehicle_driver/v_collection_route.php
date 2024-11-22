<?php
// Assume these are set elsewhere in your application
$pageTitle = "Driver Dashboard";
$driverName = "John Doe";
$teamName = "Alpha Team";
$vehicleInfo = "Toyota Hilux (ABC-1234)";

// Initialize driver location to a default value
$driverLocation = ['lat' => null, 'lng' => null];

// Initialize collections array
$collections = [];

// Populate collections from collectionSupplierRecords
if (!empty($data['collectionSupplierRecords'])) {
    foreach ($data['collectionSupplierRecords'] as $record) {
        $collections[] = [
            'id' => $record->supplier_id,
            'supplierName' => $record->supplier_name,
            'remarks' => $record->notes ?? "No special instructions",
            'location' => [
                'lat' => floatval($record->latitude),
                'lng' => floatval($record->longitude)
            ],
            'address' => $record->address ?? "Address not provided",
            'image' => "https://randomuser.me/api/portraits/men/" . ($record->supplier_id % 10) . ".jpg", // Temporary image solution
            'estimatedCollection' => floatval($record->quantity) ?? 0,
            'arrival_time' => $record->arrival_time ?? null
        ];
    }

    // Update driver location to the first unvisited supplier
    foreach ($collections as $collection) {
        if (empty($collection['arrival_time'])) {
            $driverLocation = $collection['location'];
            break;
        }
    }
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #4CAF50;
        --warning-color: #FF5722;
        --background-color: #F5F7FA;
        --text-color: #333333;
        --card-background: #FFFFFF;
        --card-outline: #E0E0E0;
        --heading-color: #1C2F40;
    }

    body, html {
        font-family: 'Inter', sans-serif;
        color: var(--text-color);
        background-color: var(--background-color);
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
        line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Inter', sans-serif;
        color: var(--heading-color);
        line-height: 1.2;
    }

    .dashboard-container {
        display: grid;
        grid-template-columns: 60% 40%;
        grid-template-rows: auto 1fr auto;
        height: 100vh;
        gap: 0;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    #map-container {
        grid-column: 1 / 2;
        grid-row: 1 / 3;
        height: 100%;
        width: 100%;
        border-radius: 0;
        overflow: hidden;
        background-color: #f5f5f5;
        position: relative;
    }

    .card {
        background: var(--card-background);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        /* outline: 3px solid var(--card-outline); */
    }

    .current-supplier {
        grid-column: 2 / 3;
        grid-row: 1 / 2;
    }

    .supplier-card {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .supplier-image-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem auto;
        position: relative;
    }

    .supplier-profile-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .supplier-info h4 {
        margin: 0 0 10px 0;
        font-size: 1.25rem;
        text-align: center;
        font-weight: 600;
    }

    .supplier-info p {
        margin: 5px 0;
        font-size: 0.9rem;
        color: #555555;
        text-align: center;
    }

    .supplier-info p#supplier-collection {
        font-size: 0.85rem;
        color: #777777;
    }

    .supplier-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 12px;
    }

    .upcoming-collections {
        grid-column: 2 / 3;
        grid-row: 2 / 3;
        overflow-y: auto;
    }

    .collection-item {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding: 10px;
        background-color: #F0F1F5;
        border-radius: 8px;
    }

    .collection-item img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .collection-item-details {
        font-family: 'Inter', sans-serif;
    }

    .collection-item-details strong {
        font-weight: 600;
    }

    .shift-info {
        grid-column: 1 / 3;
        grid-row: 3 / 4;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .shift-details {
        display: flex;
        gap: 20px;
    }

    .shift-detail {
        text-align: center;
    }

    .shift-detail h4 {
        margin: 0;
        font-size: 0.8rem;
        color: #888888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .shift-detail p {
        margin: 5px 0 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .button-row {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        background-color: #2196F3;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.1s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-btn:hover {
        background-color: #1976D2;
    }

    .action-btn:active {
        transform: scale(0.98);
    }

    .action-btn.warning {
        background-color: var(--warning-color);
    }

    .action-btn.warning:hover {
        background-color: #E64A19;
    }

    h3 {
        margin-top: 0;
        color: var(--heading-color);
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .map-label {
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.9);
        padding: 2px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
    }

    /* Media query for screens less than 1200px */
    @media screen and (max-width: 1199px) {
        .dashboard-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 0;
            margin: 0;
        }

        #map-container {
            flex: 1;
            width: 100%;
            height: calc(100vh - 56px);
        }

        /* Hide supplier profile and collection list */
        .current-supplier,
        .upcoming-collections {
            display: none;
        }

        /* Style for bottom action buttons */
        .supplier-actions {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 8px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            height: 56px;
        }
    }

    /* Override the default main padding for this view specifically */
    main {
        padding: 0 !important; /* Use !important to override any existing styles */
    }

    @media screen and (max-width: 1199px) {
        main {
            padding: 0 !important;
        }
    }

    /* Mobile styles for shift-info */
    @media screen and (max-width: 1199px) {
        .shift-info {
            position: fixed;
            bottom: 0;
            left: 60px; /* Account for sidebar */
            right: 0;
            background: white;
            padding: 8px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .mobile-actions {
            display: flex;
            width: 100%;
            gap: 8px;
        }

        .mobile-btn {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: white;
            height: 40px;
        }

        .arrived-btn {
            background-color: var(--primary-color);
        }

        .route-btn {
            background-color: #2196F3;
        }

        /* Hide desktop elements */
        .current-supplier,
        .upcoming-collections,
        .shift-details,
        .button-row:not(.mobile-actions) {
            display: none;
        }
    }
</style>

<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_driver_collections.php'; ?>


<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <?php
    // Hardcoded values
    $data = [
        'schedule' => (object)[
            'start_time' => '08:00:00',
            'end_time' => '17:00:00'
        ],
        'collection' => (object)[
            'collection_id' => 1,
            'start_time' => '08:00:00'
        ],
        'teamName' => 'Alpha Team',
        'vehicleInfo' => 'Toyota Hilux (ABC-1234)',
        'collections' => [
            [
                'id' => 1,
                'supplierName' => "Simaak Niyaz",
                'remarks' => "Meet at the main gate, call upon arrival",
                'location' => ['lat' => 6.2173037, 'lng' => 80.2564385],
                'address' => "123 Tea Lane, Galle",
                'image' => "https://randomuser.me/api/portraits/men/5.jpg",
                'estimatedCollection' => 500,
                'status' => 'Pending',
                'arrival_time' => null
            ],
            [
                'id' => 2,
                'supplierName' => "Mountain Top Tea",
                'remarks' => "Entrance is on the north side of the building",
                'location' => ['lat' => 6.243808243551064, 'lng' => 80.25967072303547],
                'address' => "456 Hill Road, Galle",
                'image' => "https://randomuser.me/api/portraits/men/7.jpg",
                'estimatedCollection' => 350,
                'status' => 'Pending',
                'arrival_time' => null
            ],
            // ... keep other suppliers as is ...
        ]
    ];
    ?>
    <div class="dashboard-container">
        <div id="map-container">
            <?php if (!empty($collections)): ?>
            <div class="mobile-supplier-card" id="mobile-supplier-card">
                <div class="stop-number">1</div>
                <img src="<?php echo $collections[0]['image']; ?>" alt="Supplier" class="supplier-img">
                <div class="supplier-text">
                    <div class="supplier-name"><?php echo $collections[0]['supplierName']; ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="current-supplier card">
            <div class="supplier-card">
                <?php if (!empty($data['collections'])): ?>
                    <?php $currentSupplier = $data['collections'][0]; ?>
                    <div class="supplier-image-container">
                        <img src="<?php echo $currentSupplier['image']; ?>" 
                             alt="<?php echo htmlspecialchars($currentSupplier['supplierName']); ?>"
                             class="supplier-profile-image"
                             onerror="this.src='<?php echo URLROOT; ?>/public/img/default-user.png'">
                    </div>
                    <div class="supplier-info">
                        <h4><?php echo $currentSupplier['supplierName']; ?></h4>
                        <p><?php echo $currentSupplier['remarks']; ?></p>
                        <p id="supplier-collection">Est. Collection: <?php echo $currentSupplier['estimatedCollection']; ?>kg</p>
                        <div class="supplier-actions">
                            <button class="action-btn" onclick="callSupplier()">Call</button>
                            <button class="action-btn" onclick="alertSupplier()">Alert</button>
                            <button class="action-btn" onclick="navigateToSupplier(<?php echo $currentSupplier['location']['lat']; ?>, <?php echo $currentSupplier['location']['lng']; ?>)">
                                <i class="fas fa-directions"></i> Navigate
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="supplier-info">
                        <h4>No Active Collection</h4>
                        <p>There are no suppliers assigned for collection.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="upcoming-collections card">
            <h3>Collection Suppliers</h3>
            <?php if (!empty($data['collections'])): ?>
                <?php foreach ($data['collections'] as $supplier): ?>
                    <div class="collection-item">
                        <div class="supplier-list-image">
                            <img src="<?php echo $supplier['image']; ?>" 
                                 alt="<?php echo htmlspecialchars($supplier['supplierName']); ?>"
                                 onerror="this.src='<?php echo URLROOT; ?>/public/img/default-user.png'">
                        </div>
                        <div class="collection-item-details">
                            <strong><?php echo htmlspecialchars($supplier['supplierName']); ?></strong><br>
                            Status: <span class="status-badge <?php echo strtolower($supplier['status']); ?>">
                                <?php echo $supplier['status']; ?>
                            </span>
                            <?php if ($supplier['arrival_time']): ?>
                                <br><small>Arrived: <?php echo date('H:i', strtotime($supplier['arrival_time'])); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="supplier-actions">
                            <button class="action-btn" onclick="navigateToSupplier(<?php echo $supplier['location']['lat']; ?>, <?php echo $supplier['location']['lng']; ?>)">
                                <i class="fas fa-directions"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No suppliers found in this collection.</p>
            <?php endif; ?>
        </div>

        <div class="shift-info">
            <div class="mobile-actions">
                <button id="arrived-btn" class="mobile-btn arrived-btn">
                    <i class='bx bx-check'></i>
                    Arrived
                </button>
                <button onclick="navigate()" class="mobile-btn route-btn">
                    <i class='bx bx-navigation'></i>
                    Navigate
                </button>
            </div>
        </div>
    </div>
</main>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>
<script>
    let map;
    let directionsService;
    let directionsRenderer;
    let driverMarker;
    let watchId;

    const collections = <?php echo json_encode($collections); ?>;
    let driverLocation = <?php echo json_encode($driverLocation); ?>;
    const URLROOT = '<?php echo URLROOT; ?>';

    function initMap() {
        try {
            // Initialize map with a default center (will be updated when we get location)
            map = new google.maps.Map(document.getElementById("map-container"), {
                center: { lat: 6.927079, lng: 79.861244 }, // Default to Sri Lanka
                zoom: 10,
                disableDefaultUI: true,
                zoomControl: false,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    },
                    {
                        featureType: "transit",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    },
                    { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                    { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                    { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                    {
                        featureType: "administrative.locality",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#d59563" }],
                    },
                    {
                        featureType: "road",
                        elementType: "geometry",
                        stylers: [{ color: "#38414e" }]
                    },
                    {
                        featureType: "road",
                        elementType: "geometry.stroke",
                        stylers: [{ color: "#212a37" }]
                    },
                    {
                        featureType: "road",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#9ca5b3" }]
                    },
                    {
                        featureType: "water",
                        elementType: "geometry",
                        stylers: [{ color: "#17263c" }]
                    },
                    {
                        featureType: "water",
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#515c6d" }]
                    },
                    {
                        featureType: "water",
                        elementType: "labels.text.stroke",
                        stylers: [{ color: "#17263c" }]
                    }
                ],
                disableDefaultUI: false
            });

            // Start watching driver's location
            if (navigator.geolocation) {
                watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        driverLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        
                        // Update driver marker and recenter map
                        addDriverMarker(driverLocation);
                        
                        // Update routes with new driver location
                        updateRoute();
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                        alert('Unable to get your location. Please enable location services.');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }

            // Initialize other map components
            directionsService = new google.maps.DirectionsService();
            firstRouteRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: "#FF0000",
                    strokeWeight: 5
                }
            });

            remainingRouteRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: "#4CAF50",
                    strokeWeight: 5
                }
            });

            console.log('Map initialized successfully');

            remainingRouteRenderer.setMap(map);
            addCustomMarkers();
            firstRouteRenderer.setMap(map);
            updateRoute();
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }

    // Add this function to check if map loads
    window.addEventListener('load', function() {
        console.log('Window loaded');
        if (typeof google === 'undefined') {
            console.error('Google Maps not loaded');
        }
    });

    function navigateToSupplier(lat, lng) {
        const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
        window.open(url, '_blank');
    }

    // Helper function to get the closest supplier
    function getClosestSupplier(driverLocation, collections) {
        let closestSupplier = null;
        let shortestDistance = Infinity;

        collections.forEach(collection => {
            const distance = getDistanceFromLatLng(driverLocation, collection.location);
            if (distance < shortestDistance) {
                shortestDistance = distance;
                closestSupplier = collection;
            }
        });

        return closestSupplier;
    }

    // Helper function to calculate distance between two locations (Haversine formula)
    function getDistanceFromLatLng(point1, point2) {
        const lat1 = point1.lat;
        const lon1 = point1.lng;
        const lat2 = point2.lat;
        const lon2 = point2.lng;
        const R = 6371; // Radius of the earth in km
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const d = R * c; // Distance in km
        return d;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }






    function updateRoute() {
        const driverLoc = {
            lat: <?php echo $driverLocation['lat']; ?>,
            lng: <?php echo $driverLocation['lng']; ?>
        };

        // Sort collections based on distance from the driver's location
        const sortedCollections = [...collections];
        sortedCollections.sort((a, b) => {
            const distanceA = getDistanceFromLatLng(driverLoc, a.location);
            const distanceB = getDistanceFromLatLng(driverLoc, b.location);
            return distanceA - distanceB;
        });

        const firstDestination = sortedCollections[0].location;
        const remainingWaypoints = sortedCollections.slice(1).map(collection => ({
            location: collection.location,
            stopover: true
        }));

        // First route (driver -> closest supplier)
        const firstRouteRequest = {
            origin: driverLoc,
            destination: firstDestination,
            travelMode: "DRIVING"
        };

        // Remaining route (closest supplier -> rest of the collections)
        const remainingRouteRequest = {
            origin: firstDestination,
            destination: sortedCollections[sortedCollections.length - 1].location,
            waypoints: remainingWaypoints,
            travelMode: "DRIVING",
            optimizeWaypoints: true
        };

        // Display the first route
        directionsService.route(firstRouteRequest, (result, status) => {
            if (status === "OK") {
                firstRouteRenderer.setDirections(result);
            }
        });

        // Display the remaining route
        directionsService.route(remainingRouteRequest, (result, status) => {
            if (status === "OK") {
                remainingRouteRenderer.setDirections(result);
                addMarkers(sortedCollections);
            }
        });
    }



    function addMarkers(sortedCollections) {
        // Marker for the first supplier
        const firstSupplier = sortedCollections[0];
        new google.maps.Marker({
            position: firstSupplier.location,
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: "#FF0000", // Red for first supplier
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: "#FFFFFF"
            },
            title: `${firstSupplier.supplierName} - ${firstSupplier.estimatedCollection}kg`
        });

        // Markers for the remaining suppliers
        const remainingSuppliers = sortedCollections.slice(1);
        remainingSuppliers.forEach(collection => {
            new google.maps.Marker({
                position: collection.location,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: "#4CAF50", // Green for remaining suppliers
                    fillOpacity: 1,
                    strokeWeight: 2,
                    strokeColor: "#FFFFFF"
                },
                title: `${collection.supplierName} - ${collection.estimatedCollection}kg`
            });
        });

        // Add the driver's marker
        addDriverMarker(driverLocation);
    }

    function addDriverMarker(position) {
        if (driverMarker) {
            driverMarker.setMap(null);
        }

        driverMarker = new google.maps.Marker({
            position: position,
            map: map,
            icon: {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                scale: 6,
                fillColor: "#FF5722",
                fillOpacity: 1,
                strokeWeight: 2
            }
        });

        map.setCenter(position);
    }


    function addCurrentSupplierMarker() {
        const currentSupplier = collections[0];
        const marker = new google.maps.Marker({
            position: currentSupplier.location,
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: "#4CAF50", // Green color
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: "#FFFFFF"
            },
            title: `${currentSupplier.supplierName} - ${currentSupplier.estimatedCollection}kg`
        });
    }



    function addUpcomingCollectionsMarkers() {
        const upcomingCollections = collections.slice(1);
        upcomingCollections.forEach((collection) => {
            const marker = new google.maps.Marker({
                position: collection.location,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: "#FF5722", // Red color
                    fillOpacity: 1,
                    strokeWeight: 2,
                    strokeColor: "#FFFFFF"
                },
                title: `${collection.supplierName} - ${collection.estimatedCollection}kg`
            });
        });
    }




    function addCustomMarkers() {
        collections.forEach((collection, index) => {
            const marker = new google.maps.Marker({
                position: collection.location,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: "#2196F3",  // Blue marker color
                    fillOpacity: 1,
                    strokeWeight: 2,
                    strokeColor: "#FFFFFF"
                },
                label: {
                    text: `${index + 1}`,  // Just show the order number
                    color: "#FFFFFF",
                    fontSize: "14px",
                    fontWeight: "bold",
                    className: 'map-label',
                },
                title: `Stop ${index + 1}: ${collection.supplierName}`  // Show name only on hover
            });
        });
    }





    function findClosestSupplierOnRoute(route) {
        let closestSupplier = null;
        let minDistance = Infinity;

        for (const collection of collections) {
            const supplierLocation = new google.maps.LatLng(collection.location.lat, collection.location.lng);
            const distance = google.maps.geometry.poly.closestPointOnPolyline(route.overview_path, supplierLocation).distance;

            if (distance < minDistance) {
                minDistance = distance;
                closestSupplier = collection;
            }
        }

        return closestSupplier;
    }


    function addDriverMarker(position) {
        if (driverMarker) {
            driverMarker.setMap(null);
        }

        driverMarker = new google.maps.Marker({
            position: position,
            map: map,
            icon: {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                scale: 6,
                fillColor: "#FF5722",
                fillOpacity: 1,
                strokeWeight: 2,
                rotation: 0
            }
        });

        // Center the map on the driver's location
        map.setCenter(position);
    }


    function updateShiftInfo() {
        const startTime = new Date('<?php echo $data['collection']->start_time; ?>');
        const now = new Date();
        
        // Calculate elapsed time
        const elapsedMs = now - startTime;
        const hours = Math.floor(elapsedMs / (1000 * 60 * 60));
        const minutes = Math.floor((elapsedMs % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((elapsedMs % (1000 * 60)) / 1000);
        
        document.getElementById("elapsed-time").textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Format shift time from schedule
        const shiftStart = '<?php echo date('H:i', strtotime($data['schedule']->start_time)); ?>';
        const shiftEnd = '<?php echo date('H:i', strtotime($data['schedule']->end_time)); ?>';
        document.getElementById("shift-time").textContent = `${shiftStart} - ${shiftEnd}`;
    }

    function callSupplier() {
        alert("Calling supplier...");
        // Implement actual calling functionality here
    }

    function alertSupplier() {
        alert("Alert sent to supplier");
        // Implement actual alerting functionality here
    }

    document.getElementById("arrived-btn").addEventListener("click", function() {
        // Get the current collection ID from the URL
        const urlParts = window.location.pathname.split('/');
        const collectionId = urlParts[urlParts.length - 1];  // Gets the last part of the URL which is the collection ID
        
        // Get the first unvisited supplier
        const currentSupplier = collections.find(supplier => !supplier.arrival_time);
        const supplierId = currentSupplier ? currentSupplier.id : null;

        if (supplierId) {
            markArrival(collectionId, supplierId);
        } else {
            alert('No suppliers left to mark as arrived');
        }
    });

    document.getElementById("delay-btn").addEventListener("click", function() {
        alert("Delay reported. Our team will follow up with the supplier.");
    });

    document.getElementById("cancel-btn").addEventListener("click", function() {
        alert("Collection cancelled. Please provide a reason in the next screen.");
    });

    function markArrival(collectionId, supplierId) {
        console.log(collectionId, supplierId);
        fetch(`${URLROOT}/vehicledriver/markArrival`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                collection_id: collectionId,
                supplier_id: supplierId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Refresh the page or update the UI
                location.reload();
            } else {
                alert('Failed to mark arrival: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark arrival');
        });
    }

    // Initialize the map and update shift info when the window loads
    window.onload = function() {
        initMap();
        updateShiftInfo();
        setInterval(updateShiftInfo, 1000); // Update every second
        updateRoute();
    };

    function navigate() {
        // Get the current supplier's location
        const currentSupplier = collections.find(supplier => !supplier.arrival_time);
        if (!currentSupplier) {
            alert('No destination found');
            return;
        }

        // Open Google Maps with directions
        const destination = `${currentSupplier.location.lat},${currentSupplier.location.lng}`;
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destination}&travelmode=driving`;
        window.open(mapsUrl, '_blank');
    }

    // Add cleanup function to remove geolocation watcher
    function cleanup() {
        if (watchId) {
            navigator.geolocation.clearWatch(watchId);
        }
    }

    // Add event listener for page unload
    window.addEventListener('unload', cleanup);
</script>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>