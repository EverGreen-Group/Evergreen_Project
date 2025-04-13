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
            <h1>Collections</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/supplier/dashboard">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">collections</a></li>
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
            <h3>Past Collections</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Collection ID</th>
                    <th>Quantity</th>
                    <th>Collection Time</th>
                    <th>Supplier Status</th>
                    <th>Collection Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($collectionDetails) && !empty($collectionDetails)): ?>
                    <?php foreach ($collectionDetails as $collections): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($collections->collection_id); ?></td>
                            <td><?php echo htmlspecialchars($collections->quantity); ?></td>
                            <td><?php echo htmlspecialchars($collections->collection_time); ?></td>
                            <td><?php echo htmlspecialchars($collections->status); ?></td>
                            <td><?php echo htmlspecialchars($collections->collection_status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No past collections available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
            
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
  width: 90%;
  margin: 0 auto;
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

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: var(--spacing-md);
  align-items: center;
  justify-content: center;
}

th, td {
  padding: var(--spacing-sm) var(--spacing-md);
  text-align: left;
  border-bottom: 0.5px solid var(--border-color);
}

table tr{
  line-height: 30px;
}

th {
  font-weight: 650;
  font-size: 0.95rem;
}

td {
  font-weight: 400;
  font-size: 0.9rem;
}

</style>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>