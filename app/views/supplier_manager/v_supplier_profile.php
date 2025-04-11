<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/supplier/supplier.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Supplier Profile</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/manager">Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/supplier">Suppliers</a></li>
                <li>Supplier Profile</li>
            </ul>
        </div>
        <?php if (RoleHelper::hasAnyRole([RoleHelper::ADMIN])): ?>
            <div class="action-buttons">
                <a href="<?php echo URLROOT; ?>/manager/updateSupplier/<?php echo $supplier->supplier_id; ?>" class="btn btn-primary">
                    <i class='bx bx-edit'></i>
                    Edit Supplier
                </a>
            </div>
        <?php endif; ?>

    </div>

    <div class="vehicle-profile-container">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Supplier Details</h3>
                </div>
                <div class="vehicle-profile-content">
                    <div class="vehicle-profile-image">
                        <?php if (!empty($supplier->image_path)): ?>
                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($supplier->image_path); ?>" alt="Supplier Image">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class='bx bxs-user'></i>
                                <p>No Image Available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="vehicle-profile-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Supplier ID</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->supplier_id); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">First Name</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->first_name); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Last Name</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->last_name); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status</span>
                                <span class="value <?php echo $supplier->is_active ? 'active' : 'inactive'; ?>">
                                    <?php echo $supplier->is_active ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">Email</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->email); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Phone</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->contact_number); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Address</span>
                                <span class="value">
                                    <?php 
                                    $address = [];
                                    if (!empty($supplier->address_line1)) $address[] = htmlspecialchars($supplier->address_line1);
                                    if (!empty($supplier->address_line2)) $address[] = htmlspecialchars($supplier->address_line2);
                                    if (!empty($supplier->city)) $address[] = htmlspecialchars($supplier->city);
                                    echo !empty($address) ? implode(', ', $address) : 'N/A';
                                    ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="label">Approved Date</span>
                                <span class="value"><?php echo !empty($supplier->approved_at) ? date('d M Y', strtotime($supplier->approved_at)) : 'N/A'; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Collections Count</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->number_of_collections); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Average Collection</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->average_collection); ?> kg</span>
                            </div>
                            <div class="info-item">
                                <span class="label">NIC</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->nic); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Application ID</span>
                                <span class="value"><?php echo htmlspecialchars($supplier->application_id); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Schedules Assigned</h3>
                </div>
                <div class="upcoming-schedules">
                    <?php if (!empty($upcomingSchedules)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Schedule ID</th>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingSchedules as $schedule): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($schedule->schedule_id); ?></td>
                                        <td><?php echo htmlspecialchars($schedule->day); ?></td>
                                        <td><?php echo !empty($schedule->start_time) ? date('H:i', strtotime($schedule->start_time)) : 'N/A'; ?></td>
                                        <td><?php echo !empty($schedule->end_time) ? date('H:i', strtotime($schedule->end_time)) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($schedule->route_id ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($schedule->driver_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($schedule->vehicle_id ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No upcoming schedules available for this supplier.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Collection History</h3>
                </div>
                <div class="collection-history">
                    <?php if (!empty($collectionHistory)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Collection ID</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Driver</th>
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($collectionHistory as $collection): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($collection->collection_id); ?></td>
                                        <td><?php echo !empty($collection->created_at) ? date('d M Y', strtotime($collection->created_at)) : 'N/A'; ?></td>

                                        <td><?php echo htmlspecialchars($collection->total_quantity ?? '0.00'); ?> kg</td>
                                        <td><?php echo htmlspecialchars($collection->driver_id ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($collection->vehicle_id ?? 'N/A'); ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No collection history available for this supplier.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Supplier Location</h3>
                </div>
                <div class="supplier-map">
                    <?php if (!empty($supplier->latitude) && !empty($supplier->longitude)): ?>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                        <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const supplierLat = <?php echo $supplier->latitude; ?>;
                                const supplierLng = <?php echo $supplier->longitude; ?>;
                                const supplierName = "<?php echo htmlspecialchars($supplier->first_name . ' ' . $supplier->last_name); ?>";

                                const map = L.map('map').setView([supplierLat, supplierLng], 25);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map);

                                L.marker([supplierLat, supplierLng])
                                    .addTo(map)
                                    .bindPopup(supplierName)
                                    .openPopup();
                            });
                        </script>
                    <?php else: ?>
                        <p class="no-data">No location information available for this supplier.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
    .vehicle-profile-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .vehicle-profile-content {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .vehicle-profile-image {
        width: 300px;
        height: 300px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .vehicle-profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .placeholder-image {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f0f0f0;
        color: #888;
    }

    .placeholder-image i {
        font-size: 80px;
        margin-bottom: 10px;
    }

    .vehicle-profile-info {
        flex-grow: 1;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
    }

    .info-item .label {
        font-weight: bold;
        color: #666;
        margin-bottom: 5px;
        font-size: 0.9em;
    }

    .info-item .value {
        color: #333;
    }

    .info-item .value.active {
        color: green;
    }

    .info-item .value.inactive {
        color: red;
    }

    .status-cell.pending {
        color: orange;
    }

    .status-cell.completed {
        color: green;
    }

    .status-cell.cancelled {
        color: red;
    }

    .status-cell.in-progress {
        color: blue;
    }

    .no-data {
        text-align: center;
        color: #888;
        padding: 20px;
    }
</style>