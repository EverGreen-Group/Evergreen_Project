<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Map View - Route #<?php echo $data['route_id']; ?></h1>
        </div>
    </div>
    <div id="map" style="width: 100%; height: 600px;"></div>
</main>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Leaflet Routing Machine CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // We are initializing the map and setting it near Galle, should make it customizable latr, zoom with 14 is looking better
    var map = L.map('map').setView([6.89086094, 79.86760449], 14);

    // using this tile layer just like in ubers minimalistic layoiut (Carto Light - very clean)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap & Carto',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // we are creating a js oject from what we received from the routeSuppliers
    var stops = [
        <?php foreach ($data['routeSuppliers'] as $supplier): ?>
        {
            // the values fromt he backend is sent here but in the object form so that its configurable using js
            lat: <?php echo $supplier->latitude; ?>,
            lng: <?php echo $supplier->longitude; ?>,
            stop_order: <?php echo $supplier->stop_order; ?>,
            full_name: "<?php echo addslashes($supplier->full_name); ?>"
        },
        <?php endforeach; ?>
    ];

    // we have a small problem here, in routeSupplier we can update and delete using the is_deleted field, if we try to bring 
    // this all from the backend straight away it would be unorded even though the stop order is properly set by the manger,
    // so first we have to sort them using the stop order
    // then we will use the map function to put it into a latlng object, so we can print it here

    stops.sort((a, b) => a.stop_order - b.stop_order);
    var waypoints = stops.map(s => L.latLng(s.lat, s.lng));

    // this is additonal stuff, we can use a normal poly line which would be a straight line between the points, but that looks
    // ugly in the pov of the manager, so we are using the routingengine, and create a marker for the latlng object, also when clicking
    // we can show the supplier name and his order.

    // all the other stuff is from the documentation

    var routingControl = L.Routing.control({
        waypoints: waypoints,
        routeWhileDragging: false,
        addWaypoints: false,
        draggableWaypoints: false,
        createMarker: function (i, waypoint, n) {
            return L.marker(waypoint.latLng).bindPopup("Stop " + stops[i].stop_order + ": " + stops[i].full_name);
        },
        lineOptions: {
            styles: [{ color: "#007664", opacity: 0.9, weight: 4 }]
        },
        router: new L.Routing.OSRMv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1'
        }),
        show: false,
        collapsible: true
    }).addTo(map);

    routingControl.getContainer().style.display = 'none'; // Hide routing panel
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
