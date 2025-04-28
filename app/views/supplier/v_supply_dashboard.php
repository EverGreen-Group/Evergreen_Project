<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/supplier_dashboard.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Supplier Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Profile Card Section -->
    <div class="profile-card">
        <div class="profile-image">
            <?php
                $profileImageSrc = URLROOT . '/uploads/supplier_photos/default-supplier.png'; 
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


    <div class="availability-section">
        <div class="availability-container">
            <?php 
            // Assuming we have a status value in data
            $isAvailable = isset($data['is_active']) ? $data['is_active'] : true;
            ?>
            <div class="availability-left">
                <div class="status-pill <?php echo $isAvailable ? 'available' : 'unavailable'; ?>">
                    <?php echo $isAvailable ? 'Available' : 'Unavailable'; ?>
                </div>
            </div>
            <div class="toggle-wrapper">
                <form action="<?php echo URLROOT; ?>/supplier/toggleAvailability" method="POST">
                    <input type="hidden" name="current_status" value="<?php echo $isAvailable ? '1' : '0'; ?>">
                    <label class="toggle-switch">
                        <input type="checkbox" id="toggleAvailability" name="availability" onchange="this.form.submit()" <?php echo $isAvailable ? 'checked' : ''; ?>>
                        <span class="slider round"></span>
                    </label>
                </form>
            </div>
        </div>
    </div>

    <!-- Assigned Schedule Section -->
    <div class="schedule-section">

        <?php if (isset($assignedSchedule) && $assignedSchedule): ?>
        <div class="schedule-content">
            <div class="assigned-schedule-card">
                <div class="schedule-header">
                    <i class='bx bx-calendar-check'></i>
                    <h3>Your Collection Schedule</h3>
                    <div class="next-date">Next: <?php echo date('M d, Y', strtotime('next ' . $assignedSchedule->day)); ?></div>
                </div>
                
                <div class="schedule-info">
                    <div class="schedule-time">
                        <div class="day-badge"><?php echo htmlspecialchars($assignedSchedule->day); ?></div>
                        <div class="time"><?php echo date("h:i A", strtotime($assignedSchedule->start_time)); ?></div>
                        <div class="route"><i class='bx bx-map'></i> <?php echo htmlspecialchars($assignedSchedule->route_name); ?></div>
                    </div>
                    
                    <div class="personnel-vehicle-info">
                        <!-- Driver Info -->
                        <div class="personnel-info">
                            <div class="section-title">Driver</div>
                            <div class="person-card">
                                <div class="person-image">
                                    <img src="<?php echo URLROOT; ?>/<?php echo htmlspecialchars($assignedSchedule->driver_image); ?>" alt="Driver" class="driver-image">
                                </div>
                                <div class="person-details">
                                    <h4><?php echo htmlspecialchars($assignedSchedule->driver_name); ?></h4>
                                    <span><i class='bx bx-phone'></i> <?php echo htmlspecialchars($assignedSchedule->contact_number); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vehicle Info -->
                        <div class="vehicle-info">
                            <div class="section-title">Vehicle</div>
                            <div class="vehicle-card">
                                <div class="vehicle-image">
                                    <img src="<?php echo URLROOT; ?>/<?php echo htmlspecialchars($assignedSchedule->vehicle_image); ?>" alt="Vehicle" class="vehicle-image">
                                </div>
                                <div class="vehicle-details">
                                    <h4><?php echo htmlspecialchars($assignedSchedule->license_plate); ?></h4>
                                    <div class="vehicle-specs">
                                        <span class="spec"><i class='bx bx-car'></i> <?php echo htmlspecialchars($assignedSchedule->make . ' ' . $assignedSchedule->model .' ' . $assignedSchedule->color); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="no-schedule-message">
            <i class='bx bx-calendar-x'></i>
            <p>You're not assigned to any collection schedule yet.</p>
            <a href="<?php echo URLROOT; ?>/supplier/complaints" class="btn-contact-support">Notify Manager</a>
        </div>
        <?php endif; ?>
    </div>


    <!-- Recent Activity Section -->
    <div class="recent-activity-section">
        <div class="section-header">
            <h2><i class='bx bx-history'></i> Recent Activity</h2>
            <a href="<?php echo URLROOT; ?>/supplier/collections/" class="view-all-button">View All <i class='bx bx-right-arrow-alt'></i></a>
        </div>
        
        <div class="activity-list">
            <?php if (isset($data['getLatestCollection']) && $data['getLatestCollection']): ?>
                <?php $collection = $data['getLatestCollection']; ?>
                <div class="collection-activity-item">
                    <div class="activity-content">
                        <div class="activity-header">
                            <h4><?php echo htmlspecialchars($collection->status); ?> Collection</h4>
                            <span class="status-badge <?php echo strtolower(htmlspecialchars($collection->status)); ?>">
                                <?php echo htmlspecialchars($collection->status); ?>
                            </span>
                        </div>
                        <div class="activity-details">
                            <div class="detail-item">
                                <i class='bx bx-calendar'></i>
                                <span><?php echo date('M d, Y', strtotime($collection->start_time)); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class='bx bx-time'></i>
                                <span><?php echo date('h:i A', strtotime($collection->start_time)); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class='bx bx-map'></i>
                                <span><?php echo htmlspecialchars($collection->route_name); ?> Route</span>
                            </div>
                            <div class="detail-item">
                                <i class='bx bx-user'></i>
                                <span>Driver: <?php echo htmlspecialchars($collection->driver_name); ?></span>
                            </div>
                            <?php if (isset($collection->quantity)): ?>
                            <div class="detail-item">
                                <i class='bx bx-package'></i>
                                <span><?php echo htmlspecialchars($collection->quantity); ?> kg collected</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="activity-actions">
                            <a href="<?php echo URLROOT; ?>/supplier/collectionBags/<?php echo $collection->collection_id; ?>" class="btn-view-details">
                                <i class='bx bx-info-circle'></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-activity-message">
                    <i class='bx bx-calendar-x'></i>
                    <p>No recent collections to display.</p>
                </div>
            <?php endif; ?>
        </div>
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


    .profile-card, .availability-section, .schedule-section, .stats-container, .recent-activity-section {
        background-color: #ffffff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 24px;
        overflow: hidden;
        transition: var(--transition);
    }

    .profile-card:hover, .availability-section:hover, .schedule-section:hover, .stats-container:hover, .recent-activity-section:hover {
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

    /* Stats Container */
    .stats-container {
        display: flex;
        padding: 24px;
    }

    .stat-item {
        flex: 1;
        text-align: center;
        padding: 0 15px;
    }

    .stat-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 12px;
    }

    .stat-header i {
        font-size: 2.2rem;
        color: var(--main);
        margin-bottom: 8px;
    }

    .stat-header span {
        color: var(--gray);
        font-size: 0.95rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-dark);
    }

    .stat-value small {
        display: block;
        font-size: 0.85rem;
        font-weight: 400;
        color: var(--gray);
        margin-top: 5px;
    }

    .stat-divider {
        width: 1px;
        background-color: #e0e0e0;
    }

    .status-indicator .active-status {
        color: var(--green);
    }

    .status-indicator .inactive-status {
        color: var(--red);
    }


    .availability-section {
        padding: 20px;
    }

    .availability-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .availability-header h3 {
        margin-bottom: 5px;
        font-size: 1.2rem;
    }

    .availability-header p {
        margin: 0;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .availability-right {
        display: flex;
        align-items: center;
    }

    .status-pill {
        display: inline-block;
        padding: 6px 15px;
        border-radius: var(--radius-lg);
        font-weight: 500;
        font-size: 0.9rem;
        margin-right: 15px;
    }

    .status-pill.available {
        background-color: var(--green-light);
        color: var(--green);
    }

    .status-pill.unavailable {
        background-color: var(--red-light);
        color: var(--red);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: var(--green);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px var(--green);
    }

    input:checked + .slider:before {
        transform: translateX(30px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .schedule-section {
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

    .view-all-button {
        display: flex;
        align-items: center;
        color: var(--main);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .view-all-button i {
        margin-left: 5px;
        transition: transform 0.2s;
    }

    .view-all-button:hover i {
        transform: translateX(3px);
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

    .personnel-vehicle-info {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 24px;
    }

    .personnel-info, .vehicle-info {
        flex: 1;
        min-width: 280px;
    }

    .section-title {
        font-weight: 600;
        color: var(--gray);
        margin-bottom: 12px;
        border-left: 3px solid var(--main);
        padding-left: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .person-card, .vehicle-card {
        display: flex;
        background-color: white;
        border-radius: var(--radius-sm);
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: var(--transition);
    }

    .person-card:hover, .vehicle-card:hover {
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    .person-image, .vehicle-image {
        width: 70px;
        height: 70px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-right: 16px;
    }

    .person-image img, .vehicle-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .person-details h4, .vehicle-details h4 {
        margin: 0 0 8px 0;
        color: var(--gray-dark);
    }

    .person-details span, .vehicle-details span {
        display: flex;
        align-items: center;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .person-details span i, .vehicle-details span i {
        margin-right: 5px;
    }

    .vehicle-specs {
        display: flex;
        flex-direction: column;
        margin-top: 8px;
    }

    .spec {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .spec i {
        margin-right: 8px;
        color: var(--main);
    }

    .schedule-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .btn-action i {
        margin-right: 8px;
    }

    .btn-action.view {
        background-color: var(--main-light);
        color: var(--main);
    }

    .btn-action.view:hover {
        background-color: var(--main);
        color: white;
    }

    .btn-action.report {
        background-color: var(--orange-light);
        color: var(--orange);
    }

    .btn-action.report:hover {
        background-color: var(--orange);
        color: white;
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

    .btn-contact-support {
        display: inline-block;
        padding: 10px 20px;
        background-color: var(--main);
        color: white;
        text-decoration: none;
        border-radius: var(--radius-sm);
        font-weight: 500;
        transition: var(--transition);
    }

    .btn-contact-support:hover {
        background-color: var(--main-dark);
        transform: translateY(-2px);
    }

    /* Recent Activity Section */
    .recent-activity-section {
        padding: 0;
    }

    .activity-list {
        padding: 16px 24px;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 14px 0;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--main-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .activity-icon i {
        font-size: 1.2rem;
        color: var(--main);
    }

    .activity-content p {
        margin: 0 0 5px 0;
        color: var(--gray-dark);
    }

    .activity-time {
        color: var(--gray);
        font-size: 0.85rem;
    }

    .no-activity-message {
        text-align: center;
        padding: 24px;
        color: var(--gray);
    }

    /* Responsive adjustments */
    @media screen and (max-width: 1024px) {
        .stats-container {
            flex-wrap: wrap;
        }
        
        .stat-item {
            flex: 1 0 calc(50% - 30px);
            margin-bottom: 20px;
        }
        
        .stat-divider {
            display: none;
        }
    }

    @media screen and (max-width: 768px) {
        .availability-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .availability-right {
            margin-top: 16px;
            width: 100%;
            justify-content: space-between;
        }
        
        .personnel-vehicle-info {
            flex-direction: column;
        }
        
        .personnel-info, .vehicle-info {
            width: 100%;
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
    }

    @media screen and (max-width: 480px) {
        .stat-item {
            flex: 100%;
        }
        
        .schedule-actions {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
        }
    }



@media screen and (max-width: 768px) {
    .availability-container {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .availability-left {
        margin-bottom: 15px; 
    }
    
    .toggle-wrapper {
        width: 100%;
        display: flex;
        justify-content: flex-start;
    }
    

    .toggle-switch {
        width: 60px;
        height: 30px;
        display: block; 
    }
    

    input:checked + .slider:before {
        transform: translateX(30px);
    }
}


@media screen and (max-width: 480px) {
    .availability-section {
        padding: 15px; 
    }
    
    .toggle-wrapper form {
        width: 100%; 
    }
    
    .status-pill {
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
    }
}
</style>

<style>
/* Improved Recent Activity Section Styling */
.recent-activity-section {
    padding: 0;
    transition: var(--transition);
}

.recent-activity-section:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 24px;
    border-bottom: 1px solid #eee;
    background-color: #f9f9f9;
}

.activity-list {
    padding: 16px 24px;
    background-color: #ffffff;
    border-radius: 0 0 var(--radius) var(--radius);
}

.collection-activity-item {
    display: flex;
    align-items: flex-start;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: var(--radius-sm);
    background-color: #f9f9f9;
    transition: var(--transition);
    border: 1px solid #eee;
}

.collection-activity-item:hover {
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.collection-activity-item:last-child {
    margin-bottom: 0;
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: var(--main-light);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.activity-icon i {
    font-size: 1.4rem;
    color: var(--main);
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.activity-header h4 {
    margin: 0;
    font-size: 1.1rem;
    color: var(--gray-dark);
}



.activity-details {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #e0e0e0;
}

.detail-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: var(--gray);
    margin-right: 15px;
}

.detail-item i {
    margin-right: 8px;
    color: var(--main);
    font-size: 1rem;
}

.activity-actions {
    margin-top: 15px;
    display: flex;
    justify-content: flex-end;
}

.btn-view-details {
    display: inline-flex;
    align-items: center;
    background-color: var(--main-light);
    color: var(--main);
    padding: 8px 16px;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: var(--transition);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-view-details:hover {
    background-color: var(--main);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
}

.btn-view-details i {
    margin-right: 8px;
}

.no-activity-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
    color: var(--gray);
    background-color: #f9f9f9;
    border-radius: 0 0 var(--radius) var(--radius);
}

.no-activity-message i {
    font-size: 3rem;
    margin-bottom: 16px;
    color: var(--gray);
}

.no-activity-message p {
    margin: 0;
    font-size: 1.1rem;
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .collection-activity-item {
        padding: 15px;
    }
    
    .activity-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .status-badge {
        margin-top: 8px;
        align-self: flex-start;
    }

    .activity-details {
        flex-direction: column;
        gap: 10px;
    }

    .detail-item {
        margin-right: 0;
    }
    
    .activity-actions {
        justify-content: flex-start;
    }
}

@media screen and (max-width: 480px) {
    .activity-icon {
        width: 40px;
        height: 40px;
    }
    
    .activity-icon i {
        font-size: 1.2rem;
    }
    
    .btn-view-details {
        width: 100%;
        justify-content: center;
    }
}
</style>

