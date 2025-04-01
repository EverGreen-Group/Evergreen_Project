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

    <!-- Availability Toggle Section -->
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

    <div class="stats-container">
        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bxs-calendar-check'></i>
                <span>Collections</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['total_collections']) ? $data['total_collections'] : '3'; ?>
                <small>this month</small>
            </div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bx-leaf'></i>
                <span>Tea Leaves</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['total_quantity']) ? $data['total_quantity'] : '120'; ?>
                <small>kg this month</small>
            </div>
        </div>
    </div>

    <!-- Action Cards Section -->
    <div class="action-cards-container">
        <div class="action-card">
            <div class="action-content">
                <div class="action-icon">
                    <i class='bx bx-calendar'></i>
                </div>
                <div class="action-text">Manage Your Schedule Subscription</div>
                <a href="<?php echo URLROOT; ?>/supplier/schedule" class="action-button">
                    <div class="button-content">
                        <i class='bx bx-calendar-edit'></i>
                        <span>Access Schedule Subscription</span>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="action-card">
            <div class="action-content">
                <div class="action-icon">
                    <i class='bx bx-history'></i>
                </div>
                <div class="action-text">View Past Collections</div>
                <a href="<?php echo URLROOT; ?>/supplier/pastCollections" class="action-button">
                    <div class="button-content">
                        <i class='bx bx-list-ul'></i>
                        <span>Access Past Collections</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="schedule-section">
        <div class="section-header">
            <h3>Upcoming Schedule</h3>
        </div>

        <div class="schedule-content">
            <?php if (!empty($data['todaySchedules'])): ?>
                <?php $schedule = $data['todaySchedules'][0]; // Access the first schedule ?>
                <div class="collection-card">
                    <div class="collection-header">
                        <div class="collection-badge">Collection Schedule #<?php echo $schedule->schedule_id; ?></div>
                    </div>
                    
                    <div class="collection-details">
                        <div class="details-row">
                            <div class="detail-item">
                                <i class='bx bx-calendar'></i>
                                <span><?php echo date('m/d/Y', strtotime($schedule->start_time)); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class='bx bx-time-five'></i>
                                <span><?php echo date('h:i A', strtotime($schedule->start_time)); ?></span>
                            </div>
                        </div>
                        
                        <div class="details-row">
                            <div class="detail-item">
                                <i class='bx bx-user'></i>
                                <span>Driver: <?php echo htmlspecialchars($schedule->driver_id); ?> - <?php echo htmlspecialchars($schedule->driver_name); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class='bx bx-car'></i>
                                <span>Vehicle: <?php echo $schedule->license_plate; ?></span>
                            </div>
                        </div>
                        
                        <div class="details-row">
                            <div class="detail-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Current Status: <?php echo $schedule->schedule_status; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="collection-status">
                        <?php if (!empty($data['collectionId'])): ?>
                            <a href="<?php echo URLROOT; ?>/Supplier/collection/<?php echo $data['collectionId']; ?>" class="view-details-link">
                                <i class='bx bx-info-circle'></i>
                                <span>View Details</span>
                            </a>
                        <?php else: ?>
                            <span class="collection-message">Collection hasn't started yet!</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-schedule">
                    <p>No upcoming schedules for today.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
