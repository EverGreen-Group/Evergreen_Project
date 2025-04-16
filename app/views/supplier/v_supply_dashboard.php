<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/supplier_dashboard.css">

<main>
    <!-- <?php print_r($data); ?> -->
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
                <span>Leaves supplied</span>
            </div>
            <div class="stat-value">
            <?php echo isset($data['teaLeavesKgLastCollection']) ? $data['teaLeavesKgLastCollection'] : '0'; ?>
                <small>last collection</small>
            </div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bx-leaf'></i>
                <span>Tea Leaves</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['teaLeavesKg']) ? floor($data['teaLeavesKg']) : '0'; ?>
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
                <div class="action-text">View Schedule</div>
                <a href="<?php echo URLROOT; ?>/supplier/schedule" class="action-button">
                    <div class="button-content">
                        <i class='bx bx-calendar-edit'></i>
                        <span>View Your Schedule</span>
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
                <a href="<?php echo URLROOT; ?>/supplier/collections" class="action-button">
                    <div class="button-content">
                        <i class='bx bx-list-ul'></i>
                        <span>Access Past Collections</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Current Schedule Section -->
    <?php if (isset($schedule) && $schedule !== false): ?>
<div class="schedule-section">
    <div class="section-header">
        <h2>Current Collection Details</h2>
    </div>

    <div class="schedule-content">
        <div class="collection-card">
            <!-- Driver and Vehicle Info -->
            <div class="personnel-vehicle-container">
                <!-- Driver Information -->
                <div class="personnel-card">
                    <div class="personnel-image">
                        <?php if (!empty($schedule->driver_image)): ?>
                            <img src="<?php echo URLROOT; ?>/<?php echo $schedule->driver_image; ?>" alt="Driver Photo">
                        <?php else: ?>
                            <div class="default-avatar">
                                <i class='bx bx-user-circle'></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="personnel-details">
                        <h4><?php echo htmlspecialchars($schedule->driver_name); ?></h4>
                        <span class="personnel-id">Driver ID: <?php echo htmlspecialchars($schedule->driver_id); ?></span>
                    </div>
                </div>
                
                <!-- Vehicle Information -->
                <?php if (!empty($collectionDetails) && isset($collectionDetails->image_path)): ?>
                <div class="vehicle-card">
                    <div class="vehicle-image">
                        <img src="<?php echo URLROOT; ?>/<?php echo $collectionDetails->image_path; ?>" alt="Vehicle Photo">
                    </div>
                    <div class="vehicle-details">
                        <h4><?php echo htmlspecialchars($schedule->vehicle_type); ?></h4>
                        <span class="vehicle-id">License: <?php echo htmlspecialchars($schedule->license_plate); ?></span>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            
            <!-- Schedule Details -->
            <div class="collection-details">
                <div class="details-row">
                    <div class="detail-item">
                        <i class='bx bx-calendar'></i>
                        <span><?php echo date('l, F j, Y', strtotime($schedule->start_time)); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <i class='bx bx-time-five'></i>
                        <span><?php echo date('h:i A', strtotime($schedule->start_time)); ?> - <?php echo date('h:i A', strtotime($schedule->end_time)); ?></span>
                    </div>
                </div>
                
                <div class="details-row">
                    <div class="detail-item">
                        <i class='bx bx-map'></i>
                        <span>Route: <?php echo htmlspecialchars($schedule->route_name); ?></span>
                    </div>
                    
                </div>
                
            </div>
            <div class="collection-status">
                <div class="vehicle-location">
                    <a href="https://www.google.com/maps?q=<?php echo $vehicleLocation->latitude; ?>,<?php echo $vehicleLocation->longitude; ?>" target="_blank" class="location-button">
                        <i class='bx bx-map-pin'></i>
                        <span>Track Vehicle Location</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>
</main>

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

