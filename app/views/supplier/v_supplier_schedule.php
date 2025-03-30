<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<main>
<div id="toast" class="toast" style="display: none;"></div>
    <div class="head-title">
        <div class="left">
            <h1>Schedule Subscription</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/supplier/dashboard">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Available Schedules</a></li>
            </ul>
        </div>
    </div>

    <?php if (!empty($data['error'])): ?>
        <div class="error-message">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <!-- Current Subscriptions Section -->
    <div class="schedule-section">
        <div class="section-header">
            <h3>Your Current Subscriptions</h3>
        </div>

        <?php if (!empty($data['subscribedSchedules'])): ?>
            <?php foreach($data['subscribedSchedules'] as $schedule): ?>
                <div class="schedule-card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-group">
                                    <div class="info-item">
                                        <i class='bx bx-map'></i>
                                        <span>Route: <strong><?php echo $schedule['route_name']; ?></strong></span>
                                    </div>
                                    <div class="info-item">
                                        <i class='bx bx-calendar'></i>
                                        <span>Collection Day: <strong><?php echo $schedule['day']; ?></strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-group">
                                    <div class="info-item">
                                        <i class='bx bx-time-five'></i>
                                        <span>Time: <strong><?php echo $schedule['shift_time']; ?></strong></span>
                                    </div>
                                    <div class="info-item">
                                        <i class='bx bx-package'></i>
                                        <span>Available Capacity: <strong><?php echo number_format($schedule['remaining_capacity'], 2); ?> kg</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-group">
                                    <div class="info-item">
                                        <i class='bx bx-car'></i>
                                        <span>Vehicle: <strong><?php echo $schedule['vehicle']; ?></strong></span>
                                    </div>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn-unsubscribe" onclick="unsubscribeFromRoute(<?php echo $schedule['schedule_id']; ?>, <?php echo $_SESSION['supplier_id']; ?>)">
                                        <i class='bx bx-x-circle'></i>
                                        Unsubscribe
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-schedule">
                <p class="no-routes-message">You are not subscribed to any collection routes.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Available Schedules Section -->
    <div class="schedule-section">
        <div class="section-header">
            <h3>Available Collection Routes</h3>
        </div>

        <?php if (!empty($data['availableSchedules'])): ?>
            <?php foreach($data['availableSchedules'] as $schedule): ?>
                <div class="schedule-card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-group">
                                    <div class="info-item">
                                        <i class='bx bx-map'></i>
                                        <span>Route: <strong><?php echo $schedule['route_name']; ?></strong></span>
                                    </div>
                                    <div class="info-item">
                                        <i class='bx bx-calendar'></i>
                                        <span>Collection Day: <strong><?php echo $schedule['day']; ?></strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-group">
                                    <div class="info-item">
                                        <i class='bx bx-time-five'></i>
                                        <span>Time: <strong><?php echo $schedule['shift_time']; ?></strong></span>
                                    </div>
                                    <div class="info-item">
                                        <i class='bx bx-package'></i>
                                        <span>Available Capacity: <strong><?php echo number_format($schedule['remaining_capacity'], 2); ?> kg</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-group">
                                    <div class="info-item">
                                        <i class='bx bx-car'></i>
                                        <span>Vehicle: <strong><?php echo $schedule['vehicle']; ?></strong></span>
                                    </div>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn-subscribe" onclick="subscribeToRoute(<?php echo $schedule['schedule_id']; ?>)">
                                        <i class='bx bx-plus-circle'></i>
                                        Subscribe
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-schedule">
                <p class="no-routes-message">No additional collection routes available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
:root {
  /* Color Variables */
  --primary-color: #27ae60;
  --primary-light: rgba(39, 174, 96, 0.1);
  --secondary-color: #2ecc71;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --background-light: #f8f9fa;
  --card-bg: #ffffff;
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
  --border-radius-xl: 16px;
  
  /* Shadow */
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
  --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.07);
}

/* Layout & Common Styles */
main {
  padding: var(--spacing-lg);
  max-width: 1200px;
  margin: 0 auto;
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

/* Schedule Section */
.schedule-section {
  background-color: var(--card-bg);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--shadow-md);
  padding: var(--spacing-lg);
  margin-bottom: var(--spacing-xl);
}

.section-header {
  margin-bottom: var(--spacing-lg);
  border-bottom: 1px solid var(--border-color);
  padding-bottom: var(--spacing-md);
}

.section-header h3 {
  font-size: 1.5rem;
  color: var(--text-primary);
  margin: 0;
}

/* Schedule Cards */
.schedule-card {
  background-color: var(--card-bg);
  border-radius: var(--border-radius-lg);
  border: 1px solid var(--border-color);
  box-shadow: var(--shadow-sm);
  margin-bottom: var(--spacing-md);
  transition: transform 0.2s, box-shadow 0.2s;
  overflow: hidden;
}

.schedule-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-lg);
}

/* Your Current Subscriptions specific styling */
.schedule-section:first-of-type .schedule-card {
  border-left: 4px solid var(--primary-color);
}

/* Available Routes specific styling */
.schedule-section:last-of-type .schedule-card {
  border-left: 4px solid var(--text-secondary);
}

.card-content {
  padding: var(--spacing-lg);
}

.card-body {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

/* Information Layout */
.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: var(--spacing-lg);
}

.info-group {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--spacing-md);
}

.info-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding: var(--spacing-sm);
  border-radius: var(--border-radius-sm);
  background-color: var(--primary-light);
}

.info-item i {
  font-size: 1.2rem;
  color: var(--primary-color);
  min-width: 24px;
}

.info-item span {
  color: var(--text-primary);
  font-size: 0.95rem;
}

.info-item strong {
  color: var(--text-primary);
  font-weight: 600;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  justify-content: flex-end;
  min-width: 140px;
}

.btn-subscribe,
.btn-unsubscribe {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding: var(--spacing-sm) var(--spacing-md);
  border: none;
  border-radius: var(--border-radius-md);
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-subscribe {
  background-color: var(--primary-color);
  color: white;
}

.btn-subscribe:hover {
  background-color: var(--secondary-color);
}

.btn-unsubscribe {
  background-color: var(--danger-color);
  color: white;
}

.btn-unsubscribe:hover {
  background-color: #c82333;
}

/* No Schedule Message */
.no-schedule {
  text-align: center;
  padding: var(--spacing-xl);
  color: var(--text-secondary);
  background-color: white;
  border-radius: var(--border-radius-md);
  border: 1px dashed var(--border-color);
}

.no-routes-message {
  color: var(--text-primary);
  font-weight: 500;
}

/* Toast Notifications */
.toast {
  position: fixed;
  top: var(--spacing-lg);
  right: var(--spacing-lg);
  padding: var(--spacing-md) var(--spacing-lg);
  border-radius: var(--border-radius-md);
  z-index: 1000;
  display: none;
  animation: slideIn 0.3s ease-in-out;
  max-width: 350px;
  box-shadow: var(--shadow-md);
}

.toast.success {
  background-color: var(--success-color);
  color: white;
}

.toast.error {
  background-color: var(--danger-color);
  color: white;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}

/* Responsive Design */
@media screen and (max-width: 768px) {
  .info-row {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .action-buttons {
    width: 100%;
    justify-content: flex-start;
    margin-top: var(--spacing-sm);
  }
  
  .btn-subscribe,
  .btn-unsubscribe {
    width: 100%;
    justify-content: center;
    padding: var(--spacing-md);
  }
}

@media screen and (max-width: 480px) {
  .card-content {
    padding: var(--spacing-md);
  }
  
  .info-group {
    grid-template-columns: 1fr;
  }
}

.error-message {
  background-color: var(--danger-color);
  color: white;
  padding: var(--spacing-md);
  border-radius: var(--border-radius-md);
  margin-bottom: var(--spacing-lg);
}
</style>

<script>
function showToast(message, type = 'error') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';

    // Hide after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-in-out';
        setTimeout(() => {
            toast.style.display = 'none';
            toast.style.animation = '';
        }, 300);
    }, 3000);
}

function subscribeToRoute(scheduleId) {
    if (confirm('Are you sure you want to subscribe to this collection route?')) {
        fetch(URLROOT + '/supplier/subscribeToRoute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'schedule_id=' + scheduleId + '&supplier_id=' + <?php echo $_SESSION['supplier_id']; ?>
        })
        .then(response => response.text())
        .then(text => {
            const result = JSON.parse(text);
            if (result.success) {
                showToast('Successfully subscribed to route!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to subscribe to route');
            }
        })
        .catch(() => {
            showToast('An error occurred. Please try again.');
        });
    }
}

function unsubscribeFromRoute(scheduleId, supplierId) {
    if (confirm('Are you sure you want to unsubscribe from this collection route?')) {
        fetch(URLROOT + '/supplier/unsubscribeFromRoute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'schedule_id=' + scheduleId + '&supplier_id=' + supplierId
        })
        .then(response => response.text())
        .then(text => {
            const result = JSON.parse(text);
            if (result.success) {
                showToast('Successfully unsubscribed from route!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to unsubscribe from route');
            }
        })
        .catch(() => {
            showToast('An error occurred. Please try again.');
        });
    }
}
</script>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>