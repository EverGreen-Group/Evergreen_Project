<?php
require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<style>
    /* Updated styling to match supplier dashboard */
    main {
        padding: var(--spacing-lg);
        max-width: 1200px;
        margin: 0 auto;
        /* background-color: var(--background-light); */
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
        align-items: center;
    }
    
    .breadcrumb li {
        color: #666;
        display: flex;
        align-items: center;
    }
    
    .breadcrumb li a {
        color: #0066cc;
        text-decoration: none;
        margin-right: 8px;
    }
    
    .breadcrumb li i {
        margin-right: 5px;
        font-size: 16px;
    }
    
    .section {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 24px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .section:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .section-title {
        font-size: 18px;
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #333;
        font-weight: 600;
    }
    
    .today-schedule {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        margin: 8px 0;
        color: #333;
    }
    
    .info-item strong {
        width: 120px;
        font-weight: 600;
        color: #555;
    }
    
    /* Styling collection status to match supplier's pill style */
    .status-pill {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .completed {
        background-color: #e6f7ed;
        color: #28a745;
    }
    
    .pending {
        background-color: #fff4e5;
        color: #ff9800;
    }
    
    .upcoming-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .upcoming-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 14px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    
    .upcoming-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    
    .upcoming-table tr:hover td {
        background-color: #f9f9f9;
    }
    
    .no-schedule {
        color: #6c757d;
        font-style: italic;
        padding: 30px 0;
        text-align: center;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .action-btn {
        display: inline-block;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .action-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }
    
    .action-btn:hover:not(:disabled) {
        background-color: #218838;
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
            <li>
                <i class='bx bx-home'></i>
                <a href="<?= URLROOT; ?>">Home</a>
            </li>
            <li>Dashboard</li>
        </ul>
    </div>

    <!-- Today's Schedule Section -->
    <section class="section">
        <h2 class="section-title">Today's Schedule</h2>
        
        <?php if (!empty($data['schedule'])): ?>
            <div class="today-schedule">
                <div class="info-item">
                    <strong>Schedule ID:</strong>
                    <span><?= htmlspecialchars($data['schedule']->schedule_id) ?></span>
                </div>
                <div class="info-item">
                    <strong>Day:</strong>
                    <span><?= htmlspecialchars($data['schedule']->day) ?></span>
                </div>
                <div class="info-item">
                    <strong>Time:</strong>
                    <span><?= date('h:i A', strtotime($data['schedule']->start_time)) ?> - <?= date('h:i A', strtotime($data['schedule']->end_time)) ?></span>
                </div>
                <div class="info-item">
                    <strong>Route:</strong>
                    <span><?= htmlspecialchars($data['schedule']->route_name) ?></span>
                </div>
                <div class="info-item">
                    <strong>Vehicle:</strong>
                    <span><?= htmlspecialchars($data['schedule']->vehicle_type) ?> (<?= htmlspecialchars($data['schedule']->license_plate) ?>)</span>
                </div>
                <div class="info-item">
                    <strong>Status:</strong>
                    <?php if (isset($data['collection_completed'])): ?>
                        <span class="status-pill completed">
                            Completed
                        </span>
                    <?php else: ?>
                        <form action="<?= URLROOT ?>/vehicledriver/createCollection/<?= htmlspecialchars($schedule->schedule_id) ?>" method="POST">
                            <button type="submit" class="action-btn">Start Collection</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="no-schedule">
                <p>No schedule available for today.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Upcoming Schedules Section -->
    <section class="section">
        <h2 class="section-title">Assigned Schedules</h2>
        
        <?php if (!empty($data['allSchedules'])): ?>
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
                    <?php foreach ($data['allSchedules'] as $schedule): ?>
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
                <p>No assigned schedules available.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
