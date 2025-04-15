<?php
require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<style>
    /* Clean, simple dashboard styling */
    main {
        padding: 20px;
        font-family: 'Nunito', Arial, sans-serif;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .dashboard-header {
        margin-bottom: 25px;
    }
    
    .dashboard-header h1 {
        font-size: 24px;
        margin-bottom: 8px;
        color: #333;
    }
    
    .breadcrumb {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }
    
    .breadcrumb li {
        color: #666;
    }
    
    .breadcrumb li a {
        color: #0066cc;
        text-decoration: none;
        margin-right: 8px;
    }
    
    .breadcrumb li a:after {
        content: "›";
        margin-left: 8px;
    }
    
    .section {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .section-title {
        font-size: 18px;
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    
    .today-schedule {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    
    .schedule-info {
        line-height: 1.6;
    }
    
    .info-group {
        margin-bottom: 12px;
    }
    
    .info-label {
        font-weight: 600;
        color: #555;
        display: inline-block;
        width: 100px;
    }
    
    .vehicle-image {
        text-align: center;
    }
    
    .vehicle-image img {
        max-width: 100%;
        max-height: 150px;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    
    .action-btn {
        display: inline-block;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        text-align: center;
        text-decoration: none;
    }
    
    .action-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }
    
    .upcoming-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .upcoming-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 12px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    
    .upcoming-table td {
        padding: 12px;
        border-bottom: 1px solid #dee2e6;
        color: #333;
    }
    
    .no-schedule {
        color: #6c757d;
        font-style: italic;
        padding: 30px 0;
        text-align: center;
    }

    @media (max-width: 768px) {
        .today-schedule {
            grid-template-columns: 1fr;
        }
    }
</style>

<main>
    <div class="dashboard-header">
        <h1>Driver Dashboard</h1>
        <ul class="breadcrumb">
            <li><a href="<?= URLROOT; ?>">Home</a></li>
            <li>Dashboard</li>
        </ul>
    </div>

    <!-- Today's Schedule Section -->
    <section class="section">
        <h2 class="section-title">Today's Schedule</h2>
        
        <?php if (!empty($data['todaySchedules'])): 
            $schedule = $data['todaySchedules'][0]; 
            $currentTime = time();
            $startTime = strtotime($schedule->start_time);
        ?>
            <div class="today-schedule">
                <div class="schedule-info">
                    <div class="info-group">
                        <span class="info-label">Route:</span>
                        <span><?= htmlspecialchars($schedule->route_name) ?></span>
                    </div>
                    
                    <div class="info-group">
                        <span class="info-label">Vehicle:</span>
                        <span><?= htmlspecialchars($schedule->vehicle_type) ?> (<?= htmlspecialchars($schedule->license_plate) ?>)</span>
                    </div>
                    
                    <div class="info-group">
                        <span class="info-label">Start Time:</span>
                        <span><?= date('h:i A', strtotime($schedule->start_time)) ?></span>
                    </div>
                    
                    <div class="info-group">
                        <span class="info-label">Date:</span>
                        <span><?= date('M d, Y', strtotime($schedule->start_time)) ?> (<?= htmlspecialchars($schedule->day) ?>)</span>
                    </div>

                    <?php if ($currentTime >= $startTime): ?>
                        <form action="<?= URLROOT ?>/vehicledriver/createCollection/<?= htmlspecialchars($schedule->schedule_id) ?>" method="POST">
                            <button type="submit" class="action-btn">Start Collection</button>
                        </form>
                    <?php else: ?>
                        <button class="action-btn" disabled>Not Available Yet</button>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($schedule->image_path)): ?>
                    <div class="vehicle-image">
                        <img src="<?= URLROOT . '/' . htmlspecialchars($schedule->image_path) ?>" alt="Vehicle Image">
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="no-schedule">
                <p>No schedule assigned for today.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Upcoming Schedules Section -->
    <section class="section">
        <h2 class="section-title">Upcoming Schedules</h2>
        
        <?php if (!empty($data['upcomingSchedules'])): ?>
            <table class="upcoming-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['upcomingSchedules'] as $schedule): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($schedule->start_time)) ?></td>
                            <td><?= htmlspecialchars($schedule->day) ?></td>
                            <td><?= date('h:i A', strtotime($schedule->start_time)) ?></td>
                            <td><?= htmlspecialchars($schedule->route_name) ?></td>
                            <td><?= htmlspecialchars($schedule->vehicle_type) ?> (<?= htmlspecialchars($schedule->license_plate) ?>)</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-schedule">
                <p>No upcoming schedules available.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>