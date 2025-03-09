<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <!-- Dashboard Header -->
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

    <!-- Schedule Subscription Section -->
    <div class="schedule-card subscription-schedule">
        <div class="card-content">
            <div class="schedule-info">
                <div class="info-item">
                    <i class='bx bx-calendar'></i>
                    <span>Manage Your Schedule Subscription</span>
                </div>
            </div>
            <div class="schedule-action">
                <a href="<?php echo URLROOT; ?>/supplier/schedule" class="change-schedule-btn">
                    <i class='bx bx-calendar-edit'></i>
                    <span>Access Schedule Subscription</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="schedule-card past-collections">
        <div class="card-content">
            <div class="schedule-info">
                <div class="info-item">
                    <i class='bx bx-history'></i>
                    <span>View Past Collections</span>
                </div>
            </div>
            <div class="schedule-action">
                <a href="<?php echo URLROOT; ?>/supplier/pastCollections" class="change-schedule-btn">
                    <i class='bx bx-list-ul'></i>
                    <span>Access Past Collections</span>
                </a>
            </div>
        </div>
    </div>

    <div class="section-divider"></div>

    <!-- Schedule Section -->
    <div class="schedule-section">
        <div class="section-header">
            <h3>Upcoming Schedule</h3>
        </div>

        <?php if (!empty($data['todaySchedules'])): ?>
            <?php $schedule = $data['todaySchedules'][0]; // Access the first schedule ?>
            <div class="schedule-card">
                <div class="card-content">
                    <div class="card-header">
                        <div class="status-badge today">Collection Schedule #<?php echo $schedule->schedule_id; ?></div>
                    </div>
                    <div class="card-body">
                        <div class="schedule-info">
                            <div class="info-item">
                                <i class='bx bx-calendar'></i>
                                <span><?php echo date('m/d/Y', strtotime($schedule->start_time)); ?></span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-time-five'></i>
                                <span><?php echo date('h:i A', strtotime($schedule->start_time)); ?></span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-user'></i>
                                <span>Driver: <?php echo $schedule->driver_id; ?></span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-car'></i>
                                <span>Vehicle: <?php echo $schedule->license_plate; ?></span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Current Status: <?php echo $schedule->schedule_status; ?></span>
                            </div>
                        </div>
                        <div class="schedule-action">
                            <?php if (!empty($data['collectionId'])): ?>
                                <a href="<?php echo URLROOT; ?>/Supplier/collection/<?php echo $data['collectionId']; ?>" class="view-details-btn">
                                    <i class='bx bx-info-circle'></i>
                                    <span>View Details</span>
                                </a>
                            <?php else: ?>
                                <span class="no-details">Collection hasn't started yet!</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-schedule">
                <p>No upcoming schedules for today.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="section-divider"></div>
</main>

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
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
  --danger-color: #e74c3c;
  
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

/* Layout & Common Styles */
main {
  padding: var(--spacing-lg);
  max-width: 1200px;
  margin: 0 auto;
}

.section-divider {
  height: 1px;
  background-color: var(--border-color);
  margin: var(--spacing-xl) 0;
}

/* Dashboard Header */
.head-title {
  margin-bottom: var(--spacing-xl);
}

.head-title h1 {
  color: var(--text-primary);
  font-size: 1.75rem;
  margin-bottom: var(--spacing-sm);
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  list-style: none;
  padding: 0;
}

.breadcrumb a {
  color: var(--text-secondary);
  text-decoration: none;
}

.breadcrumb i {
  color: var(--primary-color);
}


/* Stats Container */
.stats-container {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: var(--spacing-lg);
  background-color: white;
  padding: var(--spacing-lg);
  border-radius: var(--border-radius-lg);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: var(--spacing-xl);
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.stat-header {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  color: var(--text-secondary);
}

.stat-header i {
  font-size: 1.5rem;
  color: var(--primary-color);
}

.stat-value {
  font-size: 2rem;
  font-weight: bold;
  color: var(--text-primary);
}

.stat-value small {
  font-size: 0.875rem;
  color: var(--text-secondary);
  font-weight: normal;
}

.stat-divider {
  width: 1px;
  background-color: var(--border-color);
}

/* Schedule Cards */
.schedule-card {
  background: white;
  border-radius: var(--border-radius-lg);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: var(--spacing-lg);
  margin-bottom: var(--spacing-lg);
}

.card-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.schedule-info {
  display: flex;
  flex-wrap: wrap;
  gap: var(--spacing-md);
}

.info-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
}

.info-item i {
  font-size: 1.25rem;
  color: var(--primary-color);
}

.schedule-action {
  display: flex;
  gap: var(--spacing-md);
}

.change-schedule-btn {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding: var(--spacing-sm) var(--spacing-md);
  border-radius: var(--border-radius-sm);
  border: none;
  background-color: var(--primary-color);
  color: white;
  cursor: pointer;
  text-decoration: none;
  transition: background-color 0.3s;
}

.change-schedule-btn:hover {
  background-color: var(--secondary-color);
}

.view-details-btn {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 500;
}

.view-details-btn:hover {
  color: var(--secondary-color);
}

/* Schedule Section */
.schedule-section {
  background-color: var(--background-light);
  padding: var(--spacing-lg);
  border-radius: var(--border-radius-lg);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.section-header {
  margin-bottom: var(--spacing-lg);
}

.section-header h3 {
  font-size: 1.5rem;
  color: var(--text-primary);
  margin: 0;
}

.card-header {
  margin-bottom: var(--spacing-md);
}

.status-badge {
  display: inline-block;
  padding: var(--spacing-xs) var(--spacing-md);
  border-radius: var(--border-radius-sm);
  background-color: var(--primary-color);
  color: white;
  font-weight: 500;
}

.status-badge.today {
  background-color: var(--primary-color);
}

.card-body {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.no-details {
  color: var(--text-secondary);
  font-style: italic;
}

.no-schedule {
  text-align: center;
  padding: var(--spacing-xl);
  color: var(--text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
  
  .stat-divider {
    display: none;
  }
  
  .card-content,
  .card-body,
  .availability-status {
    flex-direction: column;
    align-items: flex-start;
    gap: var(--spacing-md);
  }
  
  .schedule-action {
    margin-top: var(--spacing-md);
  }
  
  .schedule-info {
    flex-direction: column;
    gap: var(--spacing-sm);
  }
}
</style>

<style>
/* Availability Section Styles */
.availability-section {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 20px;
    margin-bottom: 30px;
}

.availability-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.availability-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.availability-left i {
    color: #2ecc71;
    font-size: 20px;
}

.availability-label {
    font-weight: 600;
    color: #333;
    margin-right: 15px;
}

.status-pill {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
}

.status-pill.available {
    background-color: rgba(46, 204, 113, 0.1);
    color: #2ecc71;
}

.status-pill.unavailable {
    background-color: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
}

/* Toggle Switch Styles */
.toggle-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}

.toggle-description {
    color: #888;
    font-size: 14px;
    margin: 0;
    max-width: 350px;
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
    background-color: #2ecc71;
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

@media (max-width: 768px) {
  .availability-container {
    flex-direction: row !important;
    justify-content: space-between;
    align-items: center;
  }
    
  .toggle-wrapper {
    /* Remove width: 100% so it doesn’t force a line break */
    /* width: 100%; */
    margin-left: auto; /* pushes toggle-wrapper to the right */
    justify-content: flex-end; /* aligns its contents to the right */
  }
}

</style>