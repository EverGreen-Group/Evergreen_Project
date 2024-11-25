<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

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

    <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/collectionschedules" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            View Collection Schedules
        </a>
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

    <!-- !empty($data['ongoing_collections'] -->
    <!-- Ongoing Collection Tracking Section -->
    <?php if (true): ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Collections Table -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Collections</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Collection ID</th>
                                <th>Route</th>
                                <th>Team</th>
                                <th>Vehicle</th>
                                <th>Shift</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['ongoing_collections'] as $collection): ?>
                                <tr>
                                    <td><?= $collection->collection_id ?></td>
                                    <td><?= $collection->route_name ?></td>
                                    <td><?= $collection->team_name ?></td>
                                    <td><?= $collection->license_plate ?></td>
                                    <td><?= $collection->shift_name ?></td>
                                    <td><?= $collection->status ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tracking Interface -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Track Ongoing Collection</h3>
                        <select id="ongoing-collection-select">
                            <option value="">Select a collection</option>
                            <?php foreach ($data['ongoing_collections'] as $collection): ?>
                                <option value="<?= $collection->collection_id ?>">
                                    Collection <?= $collection->collection_id ?> - <?= $collection->team_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="map-container" style="height: 200px; width: 100%;"></div>
                    <div id="collection-details">
                        <h4>Collection Details</h4>
                        <p><b>Team: </b><span id="team-name">-</span></p>
                        <p><b>Route: </b><span id="route-name">-</span></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- After the ongoing collections section and before weekly schedule -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Pending Collection Confirmations</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Route</th>
                        <th>Team</th>
                        <th>Vehicle</th>
                        <th>Shift</th>
                        <th>Start Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['pending_collections']) && !empty($data['pending_collections'])): ?>
                        <?php foreach($data['pending_collections'] as $collection): ?>
                            <tr>
                                <td>C<?php echo str_pad($collection->collection_id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $collection->route_name; ?></td>
                                <td><?php echo $collection->team_name; ?></td>
                                <td><?php echo $collection->license_plate; ?></td>
                                <td><?php echo $collection->shift_name; ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($collection->start_time)); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button onclick="window.collectionManager.approveCollection(<?php echo $collection->collection_id; ?>)" 
                                                class="btn-small btn-success">
                                            <i class='bx bx-check'></i> Approve
                                        </button>
                                        <button onclick="window.collectionManager.rejectCollection(<?php echo $collection->collection_id; ?>)" 
                                                class="btn-small btn-danger">
                                            <i class='bx bx-x'></i> Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No pending collections</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add this style section -->
    <style>
    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-small {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.9em;
    }

    .btn-success {
        background-color: #4CAF50;
        color: white;
    }

    .btn-danger {
        background-color: #f44336;
        color: white;
    }

    .btn-small:hover {
        opacity: 0.8;
    }
    </style>

    <!-- Add this script section -->
    <script>
    function approveCollection(collectionId) {
        if (confirm('Are you sure you want to approve this collection?')) {
            fetch(`${URLROOT}/vehiclemanager/approveCollection/${collectionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Collection approved successfully');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to approve collection');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while approving the collection');
            });
        }
    }

    function rejectCollection(collectionId) {
        const reason = prompt('Please enter the reason for rejection:');
        if (reason) {
            fetch(`${URLROOT}/vehiclemanager/rejectCollection/${collectionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Collection rejected successfully');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to reject collection');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rejecting the collection');
            });
        }
    }
    </script>

    <!-- After the ongoing collections section -->
    <?php require APPROOT . '/views/schedule/components/weekly_schedule.php'; ?>
</main>

<script src="<?php echo URLROOT; ?>/js/vehicle_manager/collection_tracking.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>