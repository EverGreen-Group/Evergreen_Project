<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<?php
function isWithinShiftTime($startTime, $endTime) {
    $now = new DateTime();
    $startDateTime = new DateTime($startTime);
    $endDateTime = new DateTime($endTime);

    // Debug output
    // echo "Current time: " . $now->format('Y-m-d H:i:s') . "<br>";
    // echo "Start time: " . $startDateTime->format('Y-m-d H:i:s') . "<br>";
    // echo "End time: " . $endDateTime->format('Y-m-d H:i:s') . "<br>";

    return $now >= $startDateTime && $now <= $endDateTime;
}
?>

<main class="schedule-details-main">

    <?php print_r($data['collection']); ?>
    <?php print_r($data['collectionBags']); ?>


    <div class="content-header">
        <div class="header-text">
            <h1>Collection Details</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/vehicledriver/shift" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
    </div>


    <div class="schedule-content">
        <section class="schedule-info">
            <h2>Schedule Information</h2>
            <p>Week: <?php echo $data['schedule']->week_number; ?></p>
            <p>Days: <?php echo $data['schedule']->days_of_week; ?></p>
            <p>Shift: <?php echo $data['schedule']->start_time . ' - ' . $data['schedule']->end_time; ?></p>
        </section>

        <section class="vehicle-info">
            <h2>Vehicle Information</h2>
            <p>Vehicle Type: <?php echo $data['vehicle']->vehicle_type; ?></p>
            <p>License Plate: <?php echo $data['vehicle']->license_plate; ?></p>
            <p>Capacity: <?php echo $data['vehicle']->capacity; ?></p>
        </section>

        <section class="team-info">
            <h2>Team Information</h2>
            <p>Team Name: <?php echo $data['team']->team_name; ?></p>
            <p>Driver: <?php echo $data['team']->driver_name; ?></p>
            <p>Partner: <?php echo $data['team']->partner_name; ?></p>
        </section>


        <section class="route-info">
            <h2>Route Information</h2>
            <div class="route-header">
                <p><strong>Route Name:</strong> <?php echo $data['route']->route_name; ?></p>
                <p><strong>Number of Suppliers:</strong> <?php echo count($data['routeSuppliers']); ?></p>
                
                <!-- Add temporary buttons here -->
                <div class="temp-buttons">
                    <?php if ($data['userRole'] == 'driving_partner'): ?>
                        <a href="<?php echo URLROOT; ?>/drivingpartner/supplier_collection" class="btn btn-primary">
                            View Supplier Collection (Temp)
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($data['userRole'] == 'driver'): ?>
                        <a href="<?php echo URLROOT; ?>/vehicledriver/v_collection_route" class="btn btn-primary">
                            View Collection Route (Temp)
                        </a>
                    <?php endif; ?>
                </div>
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



        <?php if ($data['userRole'] == 'driver' || $data['userRole'] == 'driving_partner'): ?>
            <section class="ready-status">
                <h2>Collection Status</h2>
                <?php 
                $shiftStartTime = date('Y-m-d ') . $data['schedule']->start_time;
                $shiftEndTime = date('Y-m-d ') . $data['schedule']->end_time;
                $isTimeValid = isWithinShiftTime($shiftStartTime, $shiftEndTime);
                ?>

                <?php if ($data['collection'] === false): ?>
                    <!-- No collection exists yet, show initial bag assignment for partner -->
                    <?php if ($data['userRole'] == 'driving_partner'): ?>
                        <div class="collection-stage bag-assignment">
                            <h3>Assign Collection Bags</h3>
                            <div class="bag-assignment-container">
                                <form action="<?php echo URLROOT; ?>/drivingpartner/assignBags/<?php echo $data['schedule']->schedule_id; ?>" 
                                      method="POST" class="bag-assignment-form">
                                    <div class="form-group">
                                        <label>Add Bags for Collection</label>
                                        <div class="bag-input-container">
                                            <input type="text" 
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
                    <!-- Existing collection logic -->
                    <?php if ($data['userRole'] == 'driving_partner' && !$data['collection']->bags): ?>
                        <!-- Rest of your existing code for when collection exists -->
                    <?php elseif (!$data['collection']->vehicle_manager_approved): ?>
                        <!-- Awaiting Vehicle Manager Stage -->
                        <div class="collection-stage waiting-approval">
                            <h3>Awaiting Vehicle Manager</h3>
                            <p>Initial weight and approval pending</p>
                            <?php if ($data['collection']->initial_weight_bridge): ?>
                                <p>Initial Weight: <?php echo $data['collection']->initial_weight_bridge; ?> kg</p>
                            <?php endif; ?>
                            <?php if ($data['collection']->bags): ?>
                                <p>Bags Assigned: <?php echo $data['collection']->bags; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php elseif (!$data['collection']->start_time): ?>
                        <!-- Ready to Start Collection -->
                        <div class="collection-stage ready-to-start">
                            <h3>Ready to Start Collection</h3>
                            
                            <?php if (!$data['collection']->driver_approved): ?>
                                <?php if ($data['userRole'] == 'driver'): ?>
                                    <form action="<?php echo URLROOT; ?>/vehicledriver/setDriverReady/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['schedule']->schedule_id; ?>" 
                                          method="POST">
                                        <button type="submit" class="btn btn-primary">Mark Ready</button>
                                    </form>
                                <?php else: ?>
                                    <p>Waiting for driver to mark ready...</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p>All preparations complete</p>
                                <?php if ($data['userRole'] == 'driver'): ?>
                                    <form action="<?php echo URLROOT; ?>/vehicledriver/collectionRoute/<?php echo $data['collection']->collection_id; ?>" 
                                          method="POST">
                                        <button type="submit" class="btn btn-primary">Start Collection</button>
                                    </form>
                                <?php else: ?>
                                    <p>Waiting for driver to start collection...</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- Collection in Progress -->
                        <div class="collection-in-progress">
                            <h3>Collection in Progress</h3>
                            <?php if ($data['userRole'] == 'driver'): ?>
                                <a href="<?php echo URLROOT; ?>/vehicledriver/collectionRoute/<?php echo $data['collection']->collection_id; ?>" 
                                   class="btn btn-primary">View Collection Route</a>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/drivingpartner/collectionRoute/<?php echo $data['collection']->collection_id; ?>" 
                                   class="btn btn-primary">View Collection Route</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</main>

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

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 