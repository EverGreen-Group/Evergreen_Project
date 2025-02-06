<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/schedule_details/styles.css">
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<!-- <script src="<?php echo URLROOT; ?>/public/js/vehicle_driver/schedule_detials.js"></script> -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>


<?php
function isWithinShiftTime($startTime, $endTime) {
    $now = new DateTime();
    $startDateTime = new DateTime($startTime);
    $endDateTime = new DateTime($endTime);
    return $now >= $startDateTime && $now <= $endDateTime;
}

// Get today's day and current time
$today = date('l');
$currentDateTime = new DateTime();

// Initialize variables
$isCompleted = false;
// Check if today matches the schedule's day and if it's within shift time
$isTodayScheduled = strtolower($data['schedule']->day) === strtolower($today);
$isWithinShiftTime = isWithinShiftTime($data['schedule']->start_time, $data['schedule']->end_time);
$canAccessCollectionFeatures = $isTodayScheduled && $isWithinShiftTime;

// Check if collection exists and has a status
if (isset($data['collection']) && $data['collection']) {
    $isCompleted = (isset($data['collection']->status) && 
                    strtolower($data['collection']->status) === 'completed');
}


// A TEST
$currentDateTime = new DateTime();
$currentDay = $currentDateTime->format('l'); // Get the current day (e.g., 'Monday')
$currentTime = $currentDateTime->format('H:i'); // Get the current time (e.g., '14:00')

// Assuming $data['schedule']->start_time is a datetime string
$scheduleStartTime = new DateTime($data['schedule']->start_time);
$scheduleDay = $scheduleStartTime->format('l'); // Get the scheduled day
$scheduleStartHour = $scheduleStartTime->format('H:i'); // Get the scheduled start time
$scheduleEndHour = (new DateTime($data['schedule']->end_time))->format('H:i'); // Get the scheduled end time

// Check if a collection exists and its status
$collectionExists = isset($data['collection']) && is_object($data['collection']) && !empty((array)$data['collection']);
$collectionStatus = $collectionExists ? $data['collection']->status : null;



//

// Print conditions
// echo "<div class='conditions'>";
// echo "<p>Schedule day: " . htmlspecialchars($data['schedule']->day) . "</p>";
// echo "<p>Today: " . htmlspecialchars($today) . "</p>";
// echo "<p>Is Today Scheduled: " . ($isTodayScheduled ? 'Yes' : 'No') . "</p>";
// echo "<p>Is Within Shift Time: " . ($isWithinShiftTime ? 'Yes' : 'No') . "</p>";
// echo "<p>Can Access Collection Features: " . ($canAccessCollectionFeatures ? 'Yes' : 'No') . "</p>";
// echo "<p>Is Collection Completed: " . ($isCompleted ? 'Yes' : 'No') . "</p>";
// echo "</div>";
?>

<main class="schedule-details-main">
    <!-- <?php print_r($data); ?> -->
    <div class="content-header">
        <div class="header-text">
            <h1>Collection Details</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/vehicledriver/" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
    </div>

    <div class="schedule-content">
        <section class="schedule-info">
            <h2>Schedule Information</h2>
            <p>Week: <?php echo htmlspecialchars($data['schedule']->week_number); ?></p>
            <p>Day: <?php echo htmlspecialchars($data['schedule']->day); ?></p>
            <p>Shift: <?php echo htmlspecialchars($data['schedule']->start_time . ' - ' . $data['schedule']->end_time); ?></p>
        </section>

        <section class="vehicle-info">
            <h2>Vehicle Information</h2>
            <p>Vehicle Type: <?php echo htmlspecialchars($data['schedule']->vehicle_type); ?></p>
            <p>License Plate: <?php echo htmlspecialchars($data['schedule']->license_plate); ?></p>
            <p>Capacity: <?php echo htmlspecialchars($data['schedule']->capacity); ?> kg</p>
        </section>

        <section class="driver-info">
            <h2>Driver Information</h2>
            <p>Name: <?php echo htmlspecialchars($data['schedule']->first_name . ' ' . $data['schedule']->last_name); ?></p>
            <p>Contact: <?php echo htmlspecialchars($data['schedule']->email); ?></p>
        </section>

        <section class="route-info">
            <h2>Route Information</h2>
            <div class="route-header">
                <p><strong>Route Name:</strong> <?php echo htmlspecialchars($data['route']->route_name); ?></p>
                <p><strong>Number of Suppliers:</strong> <?php echo count($data['routeSuppliers']); ?></p>
            </div>
            <?php if (!empty($data['routeSuppliers'])): ?>
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
            <?php else: ?>
                <p>No suppliers assigned to this route.</p>
            <?php endif; ?>
        </section>


        <?php if ($canAccessCollectionFeatures): ?>
            
            <section class="collection-status">
                <h2>Collection Status</h2>

                <?php if ($collectionCompleted): ?>
                    <div class="collection-stage">
                        <div class="collection-actions">
                            <h4>Collection is complete for the day! Come back next week.</h4>
                        </div>
                    </div>
                
                <?php elseif (!isset($data['collection']) || (is_object($data['collection']) && empty((array)$data['collection']))): ?>
                    <!-- No collection exists for this schedule - Show button to create collection -->
                    <div class="collection-stage">
                        <h3>Create Collection</h3>
                        <div class="collection-actions">
                            <form action="<?php echo URLROOT; ?>/vehicledriver/createCollection/<?php echo $data['schedule']->schedule_id; ?>" method="POST">
                                <button type="submit" class="btn-primary">Start Collection</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Collection exists - Show approval status -->
                    <div class="collection-stage approval-status">
                        <h3>Collection Approval Status</h3>
                        
                        <?php 
                        // Safe check for vehicle manager approval
                        if (!property_exists($data['collection'], 'vehicle_manager_approved') || 
                            !$data['collection']->vehicle_manager_approved): 
                        ?>
                            <div class="status-message warning">
                                <i class='bx bx-time'></i>
                                <p>Awaiting Vehicle Manager's Approval</p>
                            </div>

                        <?php else: ?>
                            <!-- Show Go to Collections button only after approval -->
                            <div class="status-message success">
                                <i class='bx bx-check-circle'></i>
                                <p>Approved by Vehicle Manager - Ready to Start Collection</p>
                            </div>
                            
                            <div class="collection-actions">
                                <a href="<?php echo URLROOT; ?>/vehicledriver/collection/<?php echo $data['collection']->collection_id; ?>" 
                                   class="btn-primary">Go to Collections</a>
                            </div>
                        <?php endif; // End of vehicle manager approval check ?>
                    </div>
                <?php endif; // End of collection existence check ?>
            </section>
        <?php endif; ?>

    </div>
</main>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>





<?php require APPROOT . '/views/inc/components/footer.php'; ?> 