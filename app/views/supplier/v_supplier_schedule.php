<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<main>
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

<!-- Add this right after the <main> tag -->
<div id="toast" class="toast" style="display: none;"></div>

<style>
.schedule-section {
    margin: 20px 0;
    padding: 0 10px;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h3 {
    color: var(--dark);
    font-size: 1.1rem;
    font-weight: 600;
}

.schedule-card {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 15px;
    transition: transform 0.2s ease;
    border: 1px solid rgba(var(--main-rgb), 0.1);
}

.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-body {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.schedule-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.info-group {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    border-radius: 6px;
    background: rgba(var(--main-rgb), 0.05);
}

.info-item i {
    font-size: 1.2rem;
    color: var(--main);
    min-width: 24px;
}

.info-item span {
    color: var(--dark);
    font-size: 0.95rem;
    word-break: break-word;
}

.info-item strong {
    color: var(--main);
    font-weight: 600;
}

.action-buttons {
    display: flex;
    justify-content: flex-end;
    min-width: 140px; /* Ensures consistent button width */
}

.btn-subscribe,
.btn-unsubscribe {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-subscribe {
    background: var(--main);
    color: var(--light);
}

.btn-subscribe:hover {
    background: var(--dark);
    transform: translateY(-2px);
}

.btn-unsubscribe {
    background: #dc3545;
    color: var(--light);
}

.btn-unsubscribe:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.no-schedule {
    text-align: center;
    padding: 30px;
    color: var(--grey);
    font-style: italic;
    background: white;
    border-radius: 10px;
    border: 1px dashed rgba(var(--main-rgb), 0.2);
}

/* Your Current Subscriptions section specific styling */
.schedule-section:first-of-type .schedule-card {
    border-left: 4px solid var(--main);
}

/* Available Routes section specific styling */
.schedule-section:last-of-type .schedule-card {
    border-left: 4px solid var(--grey);
}

@media screen and (max-width: 768px) {
    .info-row {
        flex-direction: column;
    }
    
    .action-buttons {
        width: 100%;
    }
    
    .btn-subscribe,
    .btn-unsubscribe {
        width: 100%;
        justify-content: center;
    }

    .head-title {
        padding: 0 10px;
    }

    .schedule-card {
        padding: 15px;
    }

    .info-item {
        padding: 10px;
    }
}

@media screen and (max-width: 480px) {
    .schedule-card {
        padding: 12px;
    }

    .info-item {
        padding: 8px;
    }

    .info-item span {
        font-size: 0.9rem;
    }

    .btn-subscribe,
    .btn-unsubscribe {
        padding: 12px 20px;
        font-size: 1rem;
    }
}

.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 8px;
    z-index: 1000;
    display: none;
    animation: slideIn 0.3s ease-in-out;
    max-width: 350px;
}

.toast.success {
    background-color: #28a745;
    color: white;
}

.toast.error {
    background-color: #dc3545;
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

.no-routes-message {
    color: black;
    font-weight: bold;
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
