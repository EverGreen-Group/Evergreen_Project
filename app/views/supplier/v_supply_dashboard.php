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


    <!-- Current Set Date Card -->
    <div class="schedule-section">
        
        <div class="schedule-card current-schedule">
            <div class="card-content">
                <div class="schedule-info">
                    <div class="info-item">
                        <i class='bx bx-calendar'></i>
                        <span>Every Monday</span>
                    </div>
                    <div class="info-item">
                        <i class='bx bx-time-five'></i>
                        <span>08:00 AM</span>
                    </div>
                </div>
                <div class="schedule-action">
                    <button class="change-schedule-btn">
                        <i class='bx bx-calendar-edit'></i>
                        <span>Request Schedule Change</span>
                    </button>
                </div>
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
                                <span><?php echo date('m/d/Y', strtotime($schedule->start_time)); ?></span> <!-- Format the date -->
                            </div>
                            <div class="info-item">
                                <i class='bx bx-time-five'></i>
                                <span><?php echo date('h:i A', strtotime($schedule->start_time)); ?></span> <!-- Format the time -->
                            </div>
                            <div class="info-item">
                                <i class='bx bx-user'></i>
                                <span>Driver: <?php echo $schedule->driver_id; // You may want to fetch the driver's name ?></span>
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
                                <span class="no-details">Collection hasnt started yet!</span>
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
<script>

</script>

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

/* Schedule Section */
.schedule-section {
  margin-bottom: var(--spacing-xl);
}

.section-header {
  margin-bottom: var(--spacing-lg);
}

.section-header h3 {
  color: var(--text-primary);
  font-size: 1.25rem;
}

.schedule-card {
  position: relative;
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
  background-color: white;
  padding: var(--spacing-lg);
  border-radius: var(--border-radius-lg);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-content {
  flex: 1;
}

.schedule-info {
  display: flex;
  gap: var(--spacing-xl);
  margin-bottom: var(--spacing-md);
}

.info-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
}

.info-item i {
  color: var(--primary-color);
}

.schedule-action {
  display: flex;
  gap: var(--spacing-md);
  align-items: center;
}

/* Buttons and Controls */
.nav-btn {
  background: none;
  border: none;
  color: var(--primary-color);
  cursor: pointer;
  font-size: 1.5rem;
  padding: var(--spacing-sm);
}

.nav-btn:hover {
  color: var(--secondary-color);
}

.view-details-btn, 
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
}

.view-details-btn:hover,
.change-schedule-btn:hover {
  background-color: var(--secondary-color);
}

.schedule-select {
  padding: var(--spacing-sm);
  border-radius: var(--border-radius-sm);
  border: 1px solid var(--border-color);
  outline: none;
}

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.modal.active {
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: white;
  border-radius: var(--border-radius-lg);
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--spacing-lg);
  border-bottom: 1px solid var(--border-color);
}

.modal-body {
  padding: var(--spacing-lg);
}

.detail-group {
  margin-bottom: var(--spacing-lg);
}

.detail-group h4 {
  margin-bottom: var(--spacing-md);
  color: var(--text-primary);
}

.detail-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: var(--spacing-sm);
}

/* Timeline Styles */
.status-timeline {
  position: relative;
  padding-left: var(--spacing-lg);
}

.timeline-item {
  position: relative;
  padding-bottom: var(--spacing-lg);
}

.timeline-dot {
  position: absolute;
  left: -16px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: var(--border-color);
}

.timeline-item.active .timeline-dot {
  background-color: var(--success-color);
}

.timeline-item::before {
  content: '';
  position: absolute;
  left: -11px;
  top: 12px;
  bottom: 0;
  width: 2px;
  background-color: var(--border-color);
}

/* Responsive Design */
@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
  
  .stat-divider {
    display: none;
  }
  
  .schedule-info {
    flex-direction: column;
    gap: var(--spacing-md);
  }
  
  .schedule-action {
    flex-direction: column;
    align-items: stretch;
  }
  
  .modal-content {
    width: 95%;
    margin: var(--spacing-sm);
  }
}
</style>




