<?php
require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/driver_dashboard.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Driver Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/VehicleDriver/dashboard/">Dashboard</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Profile Card Section -->
    <div class="profile-card">
        <div class="profile-image">
            <?php
                $profileImageSrc = URLROOT . '/uploads/driver_photos/default-driver.png'; 
                if (isset($_SESSION['profile_image_path']) && !empty($_SESSION['profile_image_path'])) {
                    $profileImageSrc = URLROOT . '/' . $_SESSION['profile_image_path'];
                }
            ?>
            <img src="<?php echo htmlspecialchars($profileImageSrc); ?>" alt="Profile Photo">
        </div>
        <div class="profile-info">
            <?php if (isset($_SESSION['full_name'])): ?>
                <h2 class="welcome-text">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h2>
                <p class="last-login-text">Last login: <?php echo isset($_SESSION['last_login']) ? htmlspecialchars($_SESSION['last_login']) : date('M d, Y h:i A'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Today's Schedule Section -->
    <div class="schedule-section">
        <div class="section-header">
            <h2><i class='bx bx-calendar-check'></i> Today's Schedule</h2>
        </div>

        <?php if (!empty($data['schedule'])): ?>
        <div class="schedule-content">
            <div class="assigned-schedule-card">
                <div class="schedule-header">
                    <i class='bx bx-calendar-check'></i>
                    <h3>Collection Schedule</h3>
                    <div class="next-date">Today: <?php echo date('M d, Y'); ?></div>
                </div>
                
                <div class="schedule-info">
                    <div class="schedule-time">
                        <div class="day-badge"><?php echo htmlspecialchars($data['schedule']->day); ?></div>
                        <div class="time">
                            <?php echo date("h:i A", strtotime($data['schedule']->start_time)); ?>
                        </div>
                        <div class="route"><i class='bx bx-map'></i> <?php echo htmlspecialchars($data['schedule']->route_name); ?></div>
                    </div>
                    
                    <div class="schedule-details">
                        <div class="detail-item">
                            <strong>Schedule ID:</strong>
                            <span><?php echo htmlspecialchars($data['schedule']->schedule_id); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Vehicle:</strong>
                            <span><?php echo htmlspecialchars($data['schedule']->vehicle_type); ?> (<?php echo htmlspecialchars($data['schedule']->license_plate); ?>)</span>
                        </div>
                        <div class="detail-item">
                            <strong>Status:</strong>
                            <?php if (isset($data['collection_completed'])): ?>
                                <span class="status-pill completed">Completed</span>
                            <?php else: ?>
                                <span class="status-pill pending">Pending</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!isset($data['collection_completed'])): ?>
                        <div class="schedule-actions">
                            <form action="<?php echo URLROOT; ?>/vehicledriver/createCollection/<?php echo htmlspecialchars($data['schedule']->schedule_id); ?>" method="POST">
                                <button type="submit" class="btn-action start">
                                    <i class='bx bx-play-circle'></i> Start Collection
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="no-schedule-message">
            <i class='bx bx-calendar-x'></i>
            <p>No schedule available for today.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Assigned Schedules Section -->
    <div class="upcoming-schedules-section">
        <div class="section-header">
            <h2><i class='bx bx-calendar'></i> Assigned Schedules</h2>
        </div>
        
        <?php if (!empty($data['allSchedules'])): ?>
        <div class="table-container">
            <table class="schedules-table">
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
                        <td><?php echo date('M d, Y', strtotime($schedule->start_time)); ?></td>
                        <td><?php echo htmlspecialchars($schedule->day); ?></td>
                        <td><?php echo date('h:i A', strtotime($schedule->start_time)); ?></td>
                        <td><?php echo htmlspecialchars($schedule->route_name); ?></td>
                        <td><?php echo htmlspecialchars($schedule->vehicle_type); ?> (<?php echo htmlspecialchars($schedule->license_plate); ?>)</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-schedule-message">
            <i class='bx bx-calendar-x'></i>
            <p>No assigned schedules available.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<style>
    :root {
        --main-light: #e6f3ff;
        --main-dark: #2a75c0;
        --green: #28a745;
        --green-light: #e6f7e9;
        --red: #dc3545;
        --red-light: #ffefef;
        --orange: #fd7e14;
        --orange-light: #fff8e6;
        --gray: #666;
        --gray-light: #f5f5f5;
        --gray-dark: #333;
        --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        --radius: 10px;
        --radius-sm: 5px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    /* Basic Layout & Typography */
    main {
        padding: 24px;
        color: var(--gray-dark);
    }

    h1, h2, h3, h4 {
        margin: 0;
        font-weight: 600;
    }

    .head-title {
        margin-bottom: 24px;
    }

    .head-title h1 {
        font-size: 1.8rem;
        color: var(--gray-dark);
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 8px 0 0;
    }

    .breadcrumb li {
        display: flex;
        align-items: center;
        color: var(--gray);
    }

    .breadcrumb li a {
        color: var(--main);
        text-decoration: none;
    }

    .breadcrumb li i {
        margin-right: 5px;
    }

    /* Card Components */
    .profile-card, .schedule-section, .upcoming-schedules-section {
        background-color: #ffffff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 24px;
        overflow: hidden;
        transition: var(--transition);
    }

    .profile-card:hover, .schedule-section:hover, .upcoming-schedules-section:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    /* Profile Card Styling */
    .profile-card {
        display: flex;
        align-items: center;
        padding: 24px;
    }

    .profile-image {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        border: 3px solid #fff;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info h2 {
        margin-bottom: 8px;
        font-size: 1.5rem;
    }

    .last-login-text {
        color: var(--gray);
        margin: 0;
        font-size: 0.9rem;
    }

    /* Schedule Section */
    .schedule-section, .upcoming-schedules-section {
        padding: 0;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid #eee;
    }

    .section-header h2 {
        display: flex;
        align-items: center;
        font-size: 1.3rem;
        color: var(--gray-dark);
    }

    .section-header h2 i {
        margin-right: 10px;
        color: var(--main);
    }

    .assigned-schedule-card {
        background-color: #f9f9f9;
        border-radius: 0 0 var(--radius) var(--radius);
        overflow: hidden;
    }

    .schedule-header {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        background-color: var(--green-light);
        position: relative;
    }

    .schedule-header i {
        margin-right: 12px;
        font-size: 1.5rem;
        color: var(--green);
    }

    .schedule-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: var(--gray-dark);
        flex-grow: 1;
    }

    .next-date {
        background-color: var(--green);
        color: white;
        padding: 5px 12px;
        border-radius: var(--radius-lg);
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .schedule-info {
        padding: 24px;
    }

    .schedule-time {
        display: flex;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px dashed #e0e0e0;
    }

    .day-badge {
        background-color: var(--main);
        color: white;
        padding: 8px 16px;
        border-radius: var(--radius-lg);
        font-weight: 500;
        margin-right: 16px;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 5px rgba(60, 145, 230, 0.3);
    }

    .time {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-dark);
        margin-right: 20px;
        letter-spacing: 0.5px;
    }

    .route {
        display: flex;
        align-items: center;
        color: var(--gray);
        font-size: 1rem;
    }

    .route i {
        margin-right: 5px;
        color: var(--main);
    }

    .schedule-details {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .detail-item {
        display: flex;
        align-items: center;
    }

    .detail-item strong {
        width: 120px;
        font-weight: 600;
        color: var(--gray-dark);
    }

    .status-pill {
        display: inline-block;
        padding: 6px 15px;
        border-radius: var(--radius-lg);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .status-pill.completed {
        background-color: var(--green-light);
        color: var(--green);
    }

    .status-pill.pending {
        background-color: var(--orange-light);
        color: var(--orange);
    }

    .schedule-actions {
        margin-top: 16px;
    }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        border: none;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-action i {
        margin-right: 8px;
    }

    .btn-action.start {
        background-color: var(--green);
        color: white;
    }

    .btn-action.start:hover {
        background-color: var(--green);
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .no-schedule-message {
        text-align: center;
        padding: 40px 24px;
        color: var(--gray);
    }

    .no-schedule-message i {
        font-size: 3.5rem;
        margin-bottom: 16px;
        display: block;
        color: var(--gray);
    }

    .no-schedule-message p {
        margin-bottom: 20px;
        font-size: 1.1rem;
    }

    /* Table Styling */
    .table-container {
        padding: 16px 24px;
        overflow-x: auto;
    }

    .schedules-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .schedules-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 14px;
        font-weight: 600;
        color: var(--gray-dark);
        border-bottom: 2px solid #dee2e6;
    }

    .schedules-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #eee;
        color: var(--gray-dark);
    }

    .schedules-table tr:hover td {
        background-color: #f9f9f9;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
        .schedule-time {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .day-badge, .time {
            margin-bottom: 10px;
        }
        
        .profile-card {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-image {
            margin-right: 0;
            margin-bottom: 16px;
        }
        
        .schedule-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .schedule-header h3 {
            margin-bottom: 10px;
        }
        
        .next-date {
            align-self: flex-start;
        }
    }

    @media screen and (max-width: 480px) {
        .detail-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .detail-item strong {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .btn-action {
            width: 100%;
        }
    }
</style>