<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_driving_partner.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<?php
function isWithinTimeWindow($scheduleTime, $windowMinutes = 10) {
    $scheduleDateTime = new DateTime($scheduleTime);
    $now = new DateTime();
    
    // Get time difference in minutes
    $diff = ($scheduleDateTime->getTimestamp() - $now->getTimestamp()) / 60;
    
    // Return true if we're within the window minutes before the schedule
    return $diff <= $windowMinutes && $diff >= -360; // -360 means 6 hours after start time
}
?>

<main class="schedule-details-main">
    <div class="content-header">
        <div class="header-text">
            <h1>Collection Details</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/vehicledriver/shift" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shifts
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
            </div>
            <div class="suppliers-list">
                <h3>Suppliers in Route</h3>
                <table class="suppliers-table">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['routeSuppliers'] as $supplier): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier->supplier_name); ?></td>
                            <td><?php echo htmlspecialchars($supplier->location); ?></td>
                            <td><?php echo htmlspecialchars($supplier->contact_number); ?></td>
                            <td>
                                <?php if (isset($supplier->collection_status)): ?>
                                    <span class="status-badge <?php echo strtolower($supplier->collection_status); ?>">
                                        <?php echo $supplier->collection_status; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge pending">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>



        <?php if ($data['userRole'] == 'driver' || $data['userRole'] == 'driving_partner'): ?>
            <section class="ready-status">
                <h2>Ready Status</h2>
                <?php 
                $shiftDateTime = date('Y-m-d ') . $data['schedule']->start_time;
                $isTimeValid = isWithinTimeWindow($shiftDateTime);
                ?>

                <?php if ($data['collection'] && $data['collection']->start_time): ?>
                    <div class="collection-started">
                        <p>Collection in progress</p>
                        <a href="<?php echo URLROOT; ?>/vehicledriver/collection/<?php echo $data['collection']->collection_id; ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-route"></i> View Collection Route
                        </a>
                    </div>
                <?php elseif ($data['isReady']): ?>
                    <p>You are marked as ready for this collection.</p>
                    <?php if (!$isTimeValid): ?>
                        <p class="time-notice">Collection will be available 10 minutes before the scheduled time.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($isTimeValid): ?>
                        <form action="<?php echo URLROOT; ?>/vehicledriver/setReady/<?php echo $data['schedule']->schedule_id; ?>" method="POST">
                            <button type="submit" class="btn btn-primary">Mark as Ready</button>
                        </form>
                    <?php else: ?>
                        <div class="time-notice">
                            <p>You can mark yourself as ready 10 minutes before the scheduled time.</p>
                            <p class="countdown" data-start-time="<?php echo $shiftDateTime; ?>">
                                Time until shift: Calculating...
                            </p>
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

    .countdown {
        font-family: monospace;
        font-weight: bold;
        margin-top: 0.5rem;
        color: #2c3e50;
    }
</style>

<script>
function updateCountdown() {
    const countdownElement = document.querySelector('.countdown');
    if (!countdownElement) return;

    const startTime = new Date(countdownElement.dataset.startTime).getTime();
    const windowTime = startTime - (10 * 60 * 1000); // 10 minutes before
    
    function update() {
        const now = new Date().getTime();
        const distance = windowTime - now;
        
        if (distance < 0) {
            countdownElement.innerHTML = "You can now mark yourself as ready!";
            location.reload(); // Refresh to show the ready button
            return;
        }
        
        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        countdownElement.innerHTML = `Time until ready: ${hours}h ${minutes}m ${seconds}s`;
    }
    
    update();
    setInterval(update, 1000);
}

document.addEventListener('DOMContentLoaded', updateCountdown);
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 