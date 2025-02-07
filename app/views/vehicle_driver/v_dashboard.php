<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<style>
:root {
  /* Color Variables */
  --primary-color: var(--mainn);
  --secondary-color: #2ecc71;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --background-light: #f8f9fa;
  --border-color: #e0e0e0;
  --success-color: #27ae60;
  --warning-color: #f39c12;
  
  /* Spacing */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;
  
  /* Border Radius */
  --border-radius-sm: 4px;
  --border-radius-md: 8px;
  --border-radius-lg: 12px;
}

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
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.detail-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: max-height 0.3s ease;
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

.btn-primary {
    background-color: var(--secondary-color);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: var(--border-radius-md);
    cursor: pointer;
}

.btn-primary:hover {
    background-color: darkgreen; /* Darker shade for hover effect */
}
</style>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Welcome back, Driver!</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li>Overview</li>
            </ul>
        </div>
    </div>

    <div class="details-grid">
        <?php if (!empty($todaySchedules)): ?>
            <?php foreach ($todaySchedules as $schedule): ?>
                <div class="detail-card">
                    <div class="card-header">
                        <i class='bx bxs-calendar'></i>
                        <h3>Schedule ID: <?= htmlspecialchars($schedule->schedule_id) ?></h3>
                    </div>
                    <div class="card-content">
                        <div class="info-row">
                            <span class="label">Route:</span>
                            <span class="value"><?= htmlspecialchars($schedule->route_name) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Vehicle:</span>
                            <span class="value"><?= htmlspecialchars($schedule->vehicle_type) ?> (<?= htmlspecialchars($schedule->license_plate) ?>)</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Start Time:</span>
                            <span class="value"><?= htmlspecialchars($schedule->start_time) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Day:</span>
                            <span class="value"><?= htmlspecialchars($schedule->day) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="value">
                                <?php 
                                // Determine the status based on the current time
                                $currentTime = time();
                                $startTime = strtotime($schedule->start_time);
                                $endTime = strtotime($schedule->end_time);

                                if ($schedule->is_today == 1 && $currentTime >= $startTime && $currentTime <= $endTime) {
                                    echo "Started"; // Schedule is currently active
                                } elseif ($schedule->is_today == 1 && $currentTime < $startTime) {
                                    echo "Upcoming"; // Schedule is yet to start
                                } elseif ($currentTime > $endTime) {
                                    echo "Completed"; // Schedule has ended
                                } else {
                                    echo htmlspecialchars($schedule->schedule_status); // Default status
                                }
                                ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Actions:</span>
                            <span class="value">
                                <?php 
                                // Check if the schedule is today and if the current time is past the start time
                                if ($schedule->is_today == 1 && $currentTime >= $startTime && $currentTime < $endTime): ?>
                                    <form action="<?php echo URLROOT; ?>/vehicledriver/createCollection/<?php echo htmlspecialchars($schedule->schedule_id); ?>" method="POST">
                                        <button type="submit" class="btn-primary">Start Collection</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-primary" disabled>Not Available</button>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="detail-card">
                <div class="card-header">
                    <h3>No schedules available for today.</h3>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Collection Details -->
    <div class="details-grid">
        <!-- Vehicle Information -->
        <div class="detail-card" onclick="toggleCard(this)">
            <div class="card-header">
                <i class='bx bxs-truck'></i>
                <h3>Upcoming Schedule</h3>
            </div>
            <div class="card-content">
                <div class="info-row">
                    <span class="label">Schedule ID:</span>
                    <span class="value"></span>
                </div>
                <div class="info-row">
                    <span class="label">Route:</span>
                    <span class="value"></span>
                </div>
                <div class="info-row">
                    <span class="label">Vehicle:</span>
                    <span class="value"></span>
                </div>
                <div class="info-row">
                    <span class="label">Start Time:</span>
                    <span class="value"></span>
                </div>
                <div class="info-row">
                    <span class="label">Day:</span>
                    <span class="value"></span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value"></span>
                </div>
                <div class="info-row">
                    <span class="label">Action:</span>
                    <a class="btn btn-primary" href="">Start Collection</a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>