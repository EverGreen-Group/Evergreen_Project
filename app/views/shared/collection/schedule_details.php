<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<?php
function isWithinShiftTime($startTime, $endTime) {
    $now = new DateTime();
    $startDateTime = new DateTime($startTime);
    $endDateTime = new DateTime($endTime);
    return $now >= $startDateTime && $now <= $endDateTime;
}

// Get today's day and current time
$today = strtolower(date('D'));
$currentDateTime = new DateTime();

// Initialize variables
$isCompleted = false;
$isTodayScheduled = in_array($today, array_map('trim', explode(',', strtolower($data['schedule']->days_of_week))));
$isWithinShiftTime = isWithinShiftTime($data['schedule']->start_time, $data['schedule']->end_time);
$canAccessCollectionFeatures = $isTodayScheduled && $isWithinShiftTime;

// Check if collection exists and has a status
if (isset($data['collection']) && $data['collection']) {
    $isCompleted = (isset($data['collection']->status) && 
                    strtolower($data['collection']->status) === 'completed');
}
?>

<main class="schedule-details-main">
    <div class="content-header">
        <div class="header-text">
            <h1>Collection Details</h1>
        </div>
        <div class="header-actions">
            <a href="<?php if ($data['userRole'] == 'driver' || $data['userRole'] == 'driving_partner'): echo URLROOT; ?>/vehicledriver/<?php else: echo URLROOT; ?>/drivingpartner/<?php endif; ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
    </div>

    <div class="schedule-content">
        <section class="schedule-info">
            <h2>Schedule Information</h2>
            <p>Week: <?php echo htmlspecialchars($data['schedule']->week_number); ?></p>
            <p>Days: <?php echo htmlspecialchars($data['schedule']->days_of_week); ?></p>
            <p>Shift: <?php echo htmlspecialchars($data['schedule']->start_time . ' - ' . $data['schedule']->end_time); ?></p>
        </section>

        <section class="vehicle-info">
            <h2>Vehicle Information</h2>
            <p>Vehicle Type: <?php echo htmlspecialchars($data['vehicle']->vehicle_type); ?></p>
            <p>License Plate: <?php echo htmlspecialchars($data['vehicle']->license_plate); ?></p>
            <p>Capacity: <?php echo htmlspecialchars($data['vehicle']->capacity); ?></p>
        </section>

        <section class="team-info">
            <h2>Team Information</h2>
            <p>Team Name: <?php echo htmlspecialchars($data['team']->team_name); ?></p>
            <p>Driver: <?php echo htmlspecialchars($data['team']->driver_name); ?></p>
            <p>Partner: <?php echo htmlspecialchars($data['team']->partner_name); ?></p>
        </section>

        <section class="route-info">
            <h2>Route Information</h2>
            <div class="route-header">
                <p><strong>Route Name:</strong> <?php echo htmlspecialchars($data['route']->route_name); ?></p>
                <p><strong>Number of Suppliers:</strong> <?php echo count($data['routeSuppliers']); ?></p>
            </div>
            <div class="suppliers-list">
                <h3>Suppliers in Route</h3>
                <table class="suppliers-table">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Location</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['routeSuppliers'] as $supplier): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier->first_name . ' ' . $supplier->last_name); ?></td>
                            <td><?php echo htmlspecialchars($supplier->coordinates); ?></td>
                            <td><?php echo htmlspecialchars($supplier->contact_number); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php if (!$canAccessCollectionFeatures): ?>
            <div class="alert alert-info">
                <h4>Available on collection day</h4>
            </div>
        <?php endif; ?>

        <?php if ($canAccessCollectionFeatures && ($data['userRole'] == 'driver' || $data['userRole'] == 'driving_partner')): ?>
            <section class="ready-status">
                <h2>Collection Status</h2>
                
                <?php if ($data['collection'] === false): ?>
                    <!-- Step 1: Initial state - Partner assigns bags -->
                    <?php if ($data['userRole'] == 'driving_partner'): ?>
                        <div class="collection-stage bag-assignment">
                            <h3>Assign Collection Bags</h3>
                            <div class="bag-assignment-container">
                                <form action="<?php echo URLROOT; ?>/drivingpartner/assignBags/<?php echo htmlspecialchars($data['schedule']->schedule_id); ?>" 
                                      method="POST" class="bag-assignment-form">
                                    <div class="form-group">
                                        <label for="bag-token">Add Bags for Collection</label>
                                        <div class="bag-input-container">
                                            <input type="text" 
                                                   id="bag-token"
                                                   name="bag_token"
                                                   class="bag-token-input" 
                                                   placeholder="Enter bag token..."
                                                   pattern="[A-Za-z0-9]+"
                                                   autocomplete="off">
                                            <button type="button" class="add-bag-btn">Add Bag</button>
                                        </div>
                                    </div>

                                    <div class="assigned-bags-section">
                                        <h4>Assigned Bags</h4>
                                        <div class="assigned-bags-list">
                                            <p class="no-bags-message">No bags assigned yet</p>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Confirm Bag Assignment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="collection-stage waiting">
                            <h3>Waiting for Partner</h3>
                            <p>Waiting for driving partner to assign bags...</p>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <?php
                    // Remove debug print
                    // print_r($data['collection']);
                    
                    // Check manager approval status
                    $managerApproved = isset($data['collection']->vehicle_manager_approved) && 
                                      $data['collection']->vehicle_manager_approved === '1';
                    
                    // Check driver approval status
                    $driverReady = isset($data['collection']->driver_approved) && 
                                   $data['collection']->driver_approved === '1';

                    $status = strtolower($data['collection']->status);

                    if ($status === 'pending'): ?>
                        <?php if (!$managerApproved): ?>
                            <!-- Step 2: Waiting for manager approval -->
                            <div class="collection-stage waiting">
                                <h3>Waiting for Vehicle Manager</h3>
                                <p>Collection setup is being reviewed...</p>
                            </div>
                        <?php elseif ($managerApproved && !$driverReady): ?>
                            <!-- Step 3a: Manager approved, waiting for driver -->
                            <?php if ($data['userRole'] == 'driver'): ?>
                                <div class="collection-stage ready">
                                    <h3>Ready to Begin</h3>
                                    <form action="<?php echo URLROOT; ?>/vehicledriver/setReady/<?php echo $data['collection']->schedule_id; ?>" method="POST">
                                        <button type="submit" class="btn btn-primary">Set Ready</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="collection-stage waiting">
                                    <h3>Waiting for Driver</h3>
                                    <p>Waiting for driver to set ready...</p>
                                </div>
                            <?php endif; ?>
                        <?php elseif ($managerApproved && $driverReady): ?>
                            <!-- Step 3b: Everyone ready, show start button -->
                            <div class="collection-stage ready">
                                <h3>Ready to Start Collection</h3>
                                <?php if ($data['userRole'] == 'driver'): ?>
                                    <form action="<?php echo URLROOT; ?>/vehicledriver/startCollection/<?php echo $data['collection']->collection_id; ?>" method="POST">
                                        <button type="submit" class="btn btn-primary">Start Collection</button>
                                    </form>
                                <?php else: ?>
                                    <p>Driver can now start the collection.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    <?php elseif ($status === 'in progress'): ?>
                        <div class="collection-stage collection-in-progress">
                            <h3>Collection In Progress</h3>
                            <p>Collection started at: <?php echo htmlspecialchars((new DateTime($data['collection']->start_time))->format('H:i')); ?></p>
                            <p>Expected completion by: <?php echo htmlspecialchars((new DateTime($data['schedule']->end_time))->format('H:i')); ?></p>
                            <div class="collection-actions">
                                <?php 
                                $controllerPath = ($data['userRole'] == 'driver') ? 'vehicledriver' : 'drivingpartner';
                                ?>
                                <a href="<?php echo URLROOT . '/' . $controllerPath . '/collectionRoute/' . $data['collection']->collection_id; ?>" class="btn btn-primary">
                                    <i class='bx bx-navigation'></i>
                                    Continue Collection
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</main>

<style>
/* Existing styles */

/* Alert Styling */
.alert {
    padding: 20px;
    background-color: #ffdddd;
    color: #a94442;
    border: 1px solid #a94442;
    border-radius: 5px;
    margin: 20px;
}

.alert h4 {
    margin-top: 0;
}

/* Box Info Cards */
.box-info {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 24px;
}

.box-info li {
    flex: 1 1 calc(50% - 10px) !important;
    margin: 5px !important;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.box-info li i {
    font-size: 2rem;
    margin-right: 15px;
    color: var(--primary);
    flex-shrink: 0;
}

.box-info .text {
    flex: 1;
}

.box-info .text p {
    margin: 0;
    font-size: 1rem;
    color: var(--dark);
}

.box-info .text h3 {
    margin: 5px 0;
    font-size: 1.25rem;
    color: var(--primary);
}

.box-info .text span {
    font-size: 0.875rem;
    color: var(--secondary);
}

/* Content Header */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-text h1 {
    margin: 0;
}

.header-actions .btn {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Schedule Content */
.schedule-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.schedule-info, .vehicle-info, .team-info, .route-info {
    background: white;
    padding: 20px;
    border-radius: 8px;
}

.route-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
}

.temp-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    color: white;
    background-color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: 0.9rem;
}

.btn-secondary {
    background-color: #6c757d;
}

/* Suppliers List */
.suppliers-list {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
}

.suppliers-table {
    width: 100%;
    border-collapse: collapse;
}

.suppliers-table th,
.suppliers-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.suppliers-table tr.current {
    background: #e3f2fd;
}

.suppliers-table tr.completed {
    background: #f1f8e9;
}

.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.status.added {
    background: #e3f2fd;
    color: #1976D2;
}

.status.completed {
    background: #f1f8e9;
    color: #43A047;
}

/* Collection Status */
.ready-status {
    margin-top: 20px;
}

.collection-stage {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.collection-stage.waiting {
    border-left: 4px solid #ffc107;
}

.collection-stage.collection-in-progress {
    border-left: 4px solid #28a745;
}

.bag-assignment-form {
    margin-top: 20px;
}

.bag-input-container {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.bag-token-input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.assigned-bags-list {
    margin-top: 15px;
    display: grid;
    gap: 10px;
}

.assigned-bag {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

.remove-bag {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
}

.form-actions {
    margin-top: 20px;
}

.form-actions .btn {
    width: 100%;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .temp-buttons {
        flex-direction: column;
    }

    .box-info li {
        flex: 1 1 100% !important;
        margin: 5px !important;
        padding: 15px;
    }

    .box-info li i {
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .box-info .text p {
        font-size: 0.9rem;
    }

    .box-info .text h3 {
        font-size: 1.1rem;
    }

    .box-info .text span {
        font-size: 0.8rem;
    }

    .route-header {
        flex-direction: column;
        gap: 0.5rem;
    }

    .route-header p {
        margin: 0;
    }

    /* Adjustments for scheduled details */
    .schedule-details-main {
        padding: 0.5rem;
    }

    .accordion-item {
        padding: 0.8rem;
    }

    .accordion-header h2 {
        font-size: 1rem;
    }
}

/* Further mobile-specific adjustments for very small screens */
@media (max-width: 360px) {
    .box-info li {
        padding: 10px !important;
    }

    .box-info li i {
        font-size: 1.2rem !important;
        margin-right: 8px !important;
    }

    .box-info .text p {
        font-size: 0.85rem !important;
    }

    .box-info .text h3 {
        font-size: 1rem !important;
    }

    .box-info .text span {
        font-size: 0.75rem !important;
    }

    .form-actions .btn {
        font-size: 0.9rem;
    }
}
</style>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.bag-assignment-form');
    const input = document.querySelector('.bag-token-input');
    const addButton = document.querySelector('.add-bag-btn');
    const bagsList = document.querySelector('.assigned-bags-list');
    const assignedBags = new Set();

    // Add bag when button is clicked
    addButton.addEventListener('click', function() {
        addBag();
    });

    // Add bag when Enter is pressed
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addBag();
        }
    });

    function addBag() {
        const token = input.value.trim();
        if (!token) return;

        if (assignedBags.has(token)) {
            alert('This bag is already assigned');
            return;
        }

        // Add bag to the list
        assignedBags.add(token);
        
        // Create bag element
        const bagElement = document.createElement('div');
        bagElement.className = 'assigned-bag';
        bagElement.dataset.token = token;
        bagElement.innerHTML = `
            <span class="bag-token">${token}</span>
            <button type="button" class="remove-bag-btn" onclick="removeBag('${token}')">
                <i class='bx bx-trash'></i>
            </button>
            <input type="hidden" name="bags[]" value="${token}">
        `;

        // Remove "No bags" message if it exists
        const noBagsMessage = bagsList.querySelector('.no-bags-message');
        if (noBagsMessage) {
            noBagsMessage.remove();
        }

        // Add bag to the list
        bagsList.appendChild(bagElement);
        
        // Clear input
        input.value = '';
        input.focus();
    }

    // Make removeBag function global so onclick can access it
    window.removeBag = function(token) {
        const bagElement = document.querySelector(`.assigned-bag[data-token="${token}"]`);
        if (bagElement) {
            assignedBags.delete(token);
            bagElement.remove();

            // Add "No bags" message if list is empty
            if (assignedBags.size === 0) {
                bagsList.innerHTML = '<p class="no-bags-message">No bags assigned yet</p>';
            }
        }
    };
});
</script>

<style>
    .schedule-details-main {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .schedule-header h1 {
        font-size: 1.8rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .schedule-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .schedule-content section {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .schedule-content section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .schedule-content h2 {
        color: #2c3e50;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .schedule-content p {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        color: #4a5568;
        line-height: 1.6;
    }

    .schedule-content p strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .ready-status {
        grid-column: 1 / -1;
        background-color: #f8f9fa !important;
        border: 1px solid #e0e0e0;
    }

    .ready-status form {
        margin-top: 1rem;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: #86E211;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #78cc0f;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background-color: #F06E6E;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #e85c5c;
        transform: translateY(-1px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .schedule-details-main {
            padding: 1rem;
        }

        .schedule-content {
            grid-template-columns: 1fr;
        }

        .schedule-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }

    /* Add to your existing styles */
    .route-info {
        grid-column: 1 / -1; /* Make it full width */
    }

    .route-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .suppliers-list {
        margin-top: 1.5rem;
    }

    .suppliers-list h3 {
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .suppliers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .suppliers-table th,
    .suppliers-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .suppliers-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-badge.collected {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-badge.no-show {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-badge.skipped {
        background-color: #e0e0e0;
        color: #4a5568;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header-text {
        flex: 1;
    }

    .breadcrumb {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        gap: 0.5rem;
    }

    .breadcrumb-item {
        color: #6c757d;
    }

    .breadcrumb-item a {
        color: #2c3e50;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #86E211;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        padding-right: 0.5rem;
        color: #6c757d;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .collection-started {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background-color: #d1fae5;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .collection-started p {
        color: #065f46;
        font-weight: 500;
        margin: 0;
    }

    .time-notice {
        background-color: #fff3cd;
        color: #856404;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
        text-align: center;
    }
<<<<<<< HEAD
=======

    /* Extra small screen adjustments */
    @media (max-width: 528px) {
        /* Header adjustments */
        .content-header {
            padding: 0.8rem;
        }

        .header-text h1 {
            font-size: 1rem;
        }

        .btn-secondary {
            padding: 0.5rem;
        }

        .btn-secondary i {
            font-size: 1.2rem;
            margin: 0;
        }

        .btn-text {
            display: none;
        }

        /* Supplier table adjustments */
        .suppliers-table th:not(:first-child),
        .suppliers-table td:not(:first-child) {
            display: none;
        }

        .suppliers-table th:first-child {
            width: 100%;
        }

        .suppliers-table {
            margin: 0 -1rem;
            width: calc(100% + 2rem);
        }

        /* Ready status adjustments */
        .ready-status {
            text-align: center;
        }

        .ready-status h2 {
            font-size: 1.1rem;
        }

        .ready-status .btn {
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }

        .collection-started {
            flex-direction: column;
            gap: 0.8rem;
            text-align: center;
        }

        .collection-started .btn {
            width: 100%;
        }

        /* Route information adjustments */
        .route-header {
            flex-direction: column;
            gap: 0.5rem;
        }

        .route-header p {
            margin: 0;
        }

        /* Status badge adjustments */
        .status-badge {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }

        /* General spacing adjustments */
        .schedule-details-main {
            padding: 0.5rem;
        }

        .accordion-item {
            padding: 0.8rem;
        }

        .accordion-header h2 {
            font-size: 1rem;
        }
    }

    .collection-stage {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .bag-assignment-form {
        margin-top: 20px;
    }

    .bag-input-container {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .bag-token-input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .assigned-bags-list {
        margin-top: 15px;
        display: grid;
        gap: 10px;
    }

    .assigned-bag {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .remove-bag {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
    }

    .waiting-approval {
        border-left: 4px solid #ffc107;
    }

    .collection-in-progress {
        border-left: 4px solid #28a745;
    }

    .temp-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
        .temp-buttons {
            flex-direction: column;
        }
    }
>>>>>>> simaak
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 