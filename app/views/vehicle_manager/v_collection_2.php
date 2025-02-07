<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>







<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1><?php echo "Details for Collection #" . $collectionDetails->collection_id ?></h1>
        </div>


    </div>


    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <h3 id="totalSuppliers"><?php echo $data['totalSuppliers']; ?></h3>
                <p>Total Suppliers</p>
                <small>Available</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-user'></i>
            <span class="text">
                <h3 id="suppliersLeft"><?php echo $data['remainingSuppliers']; ?></h3>
                <p>Suppliers Left</p>
                <small>Available</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <h3 id="currentCapacity"><?php echo $data['vehicleRemainingCapacity']; ?> kg</h3>
                <p>Capacity Left</p>
                <small>Available</small>
            </span>
        </li>
    </ul>

    <?php
    // Check if latitude and longitude are not null and the collection status is either 'In Progress' or 'Pending'
    if (!is_null($data['vehicleDetails']->latitude) && 
        !is_null($data['vehicleDetails']->longitude && 
        ($collectionDetails->status === 'In Progress' || $collectionDetails->status === 'Pending'))): 
    ?>

    <!-- Map Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Current Driver Location</h3>
            </div>
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
    </div>

    <script>

    let map;
    let directionsService;
    let directionsRenderer;
    let markers = [];

    // Map related functions
    function clearMarkers() {
        markers.forEach((marker) => marker.setMap(null));
        markers = [];
    }

    var suppliersForMap = <?= json_encode($data['suppliersForMap']) ?>;

    function initMap() {
        // Set the center of the map based on the vehicle's location
        var vehicleLocation = {
            lat: <?= $data['vehicleDetails']->latitude ?>,
            lng: <?= $data['vehicleDetails']->longitude ?>
        };

        var factoryLocation = {
            lat: 6.2178314,
            lng: 80.2550494
        };

        // Initialize the map centered at the vehicle's location
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13, // Adjust zoom as needed
            center: vehicleLocation
        });

        // Create a marker for the vehicle location with a custom color
        var vehicleMarker = new google.maps.Marker({
            position: vehicleLocation,
            map: map,
            title: 'Vehicle Location',
            icon: {
                url: "https://maps.google.com/mapfiles/kml/shapes/cabs.png",
                scaledSize: new google.maps.Size(30, 30) 
            }
        });

        // Create a marker for the factory location with a different color
        var factoryMarker = new google.maps.Marker({
            position: factoryLocation,
            map: map,
            title: 'Factory Location',
            icon: {
                url: "https://maps.google.com/mapfiles/kml/shapes/ranger_station.png", 
                scaledSize: new google.maps.Size(30, 30) 
            }
        });

        // Loop through suppliersForMap and create markers
        suppliersForMap.forEach(function(supplier) {
            // Determine the icon based on approval status
            var iconUrl;
            if (supplier.approval_status === 'APPROVED') {
                iconUrl = "http://maps.google.com/mapfiles/ms/icons/orange-dot.png"; // Approved marker color
            } else if (supplier.approval_status === 'PENDING') {
                iconUrl = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"; // Pending marker color
            } else {
                iconUrl = "http://maps.google.com/mapfiles/ms/icons/green-dot.png"; // Default marker color for other statuses
            }

            // Create a marker for each supplier
            var marker = new google.maps.Marker({
                position: supplier.location,
                map: map,
                title: supplier.name,
                icon: {
                    url: iconUrl 
                }
            });
        });
    }
    </script>

    <?php else: ?>
        <div class="table-data">
            <div class="order">
            <p>Driver location is not available or the collection has ended.</p>
            </div>
        </div>
    <?php endif; ?>



     <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Route Information</h3>
            </div>
            <table id="driver-information">
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Route Name</th>
                        <th>Set Day</th>
                        <th>Number of Suppliers</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($data['routeDetails']):?>
                    <tr>
                        <td><?php echo $data['routeDetails']->route_id  ?></td>
                        <td><?php echo $data['routeDetails']->route_name  ?></td>
                        <td><?php echo $data['routeDetails']->day  ?></td>
                        <td><?php echo $data['routeDetails']->number_of_suppliers  ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No routes found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
     </div>

     <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Driver Information</h3>
            </div>
            <table id="driver-information">
                <thead>
                    <tr>
                        <th>Driver ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($data['driverDetails']):?>
                    <tr>
                        <!-- <td colspan="6">No vehicles found</td> -->
                        <td><?php echo $data['driverDetails']->driver_id  ?></td>
                        <td><?php echo $data['driverDetails']->first_name  ?></td>
                        <td><?php echo $data['driverDetails']->last_name  ?></td>
                        <td><?php echo $data['driverDetails']->contact_number  ?></td>
                    </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No vehicle found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="order">
            <div class="head">
                <h3>Vehicle Information</h3>
            </div>
            <table id="vehicle-information">
                <thead>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>License Plate</th>
                        <th>Capacity</th>
                        <th>Color</th>
                        <th>Type</th>
                </thead>
                <tbody>
                    <?php if ($data['vehicleDetails']):?>
                    <tr>
                        <!-- <td colspan="6">No vehicles found</td> -->
                        <td><?php echo $data['vehicleDetails']->vehicle_id  ?></td>
                        <td><?php echo $data['vehicleDetails']->license_plate  ?></td>
                        <td><?php echo $data['vehicleDetails']->capacity  ?></td>
                        <td><?php echo $data['vehicleDetails']->color  ?></td>
                        <td><?php echo $data['vehicleDetails']->vehicle_type  ?></td>
                    </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No vehicle found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

     
     </div>

    
    <div class="head-title">
        <div class="left">
            <h1 style="margin-top:40px;">Collection Information</h1>
        </div>
    </div>


    <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Collection Supplier Record</h3>
        </div>
        <table id="driver-information">
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier Name</th>
                    <th>Contact Number</th>
                    <th>Total Quantity</th>
                    <th>Arrival Time</th>
                    <th>Collected Time</th>
                    <th>Fertilizer Deliveries</th>
                    <th>Approval Status</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($collectionSuppliersDetails)): ?>
                    <?php foreach ($collectionSuppliersDetails as $supplier): ?>
                        <tr>
                            <td><?php echo $supplier->supplier_id; ?></td>
                            <td><?php echo $supplier->supplier_name; ?></td>
                            <td><?php echo $supplier->contact_number; ?></td>
                            <td><?php echo $supplier->quantity; ?> kg</td>
                            <td><?php echo $supplier->collection_time; ?></td>
                            <td><?php echo $supplier->collection_time; ?></td>
                            <td>NO</td>
                            <td>
                                <?php 
                                if ($supplier->approval_status === 'APPROVED') {
                                    echo '<i class="bx bxs-check-circle" style="color: green;font-size:25px;"></i>';
                                } elseif ($supplier->approval_status === 'PENDING') {
                                    echo '<i class="bx bxs-hourglass" style="color: orange;font-size:25px;"></i>';
                                } else {
                                    echo '<i class="bx bxs-x-circle" style="color: red;font-size:25px;"></i>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No suppliers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>

     <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Fertilizer Deliveries</h3>
            </div>
            <table id="driver-information">
                <thead>
                    <tr>
                        <th>Fertilizer ID</th>
                        <th>Fertilizer Name</th>
                        <th>Fertilizer Type</th>
                        <th>Total Quantity</th>
                        <th>Number of Bags</th>
                        <th>Supplier ID</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- <td colspan="6">No drivers found</td> -->
                            <td>F001</td>
                            <td>Ishan</td>
                            <td>CIC</td>
                            <td>40 kg</td>
                            <td>2</td>
                            <td>S001</td>
                            <td>Delivered</td>

                    </tr>
                </tbody>
            </table>
        </div>
     </div>






</main>




<style>
/* Add this to your CSS file or within a <style> tag */
.status-tag {
    display: inline-block; /* Make it an inline-block element */
    padding: 12px 24px; /* Add some padding */
    font-size: 16px; /* Adjust font size */
    color: white; /* Text color */
    background-color: var(--main);
    border-radius: 25px;
    margin-top: 10px; /* Space above the tag */
    font-weight: bold; /* Make the text bold */
    text-align: center; /* Center the text */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effect */
    width: 200px;
    transform: translate(10px,-7px);
}

.status-tag:hover {
    background-color: #218838; /* Darker green on hover */
    transform: translateY(-2px); /* Slight lift effect on hover */
}
</style>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>
