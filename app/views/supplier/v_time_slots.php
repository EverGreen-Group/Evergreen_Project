<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/appointments/styles.css">

<main>
  <!-- Page Header -->
  <div class="head-title">
    <div class="left">
      <h1>Appointment Bookings</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Appointment Bookings</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- Available Time Slots Section -->
  <div class="available-slots-section">
    <div class="section-header">
      <h3>Available Time Slots</h3>
    </div>
    <?php if (!empty($data['time_slots'])): ?>
      <div class="slots-container">
        <?php foreach($data['time_slots'] as $slot): ?>
          <div class="slot-card">
            <div class="slot-info">
              <div class="slot-header">
                <i class='bx bx-calendar'></i>
                <span class="date"><?php echo date('l, F j, Y', strtotime($slot->date)); ?></span>
              </div>
              <div class="slot-time">
                <i class='bx bx-time'></i>
                <span><?php echo date('h:i A', strtotime($slot->start_time)); ?> - <?php echo date('h:i A', strtotime($slot->end_time)); ?></span>
              </div>
              <div class="slot-manager">
                <i class='bx bx-user'></i>
                <span>Manager Name: <?php echo $slot->manager_name; ?></span>
              </div>
            </div>
            <div class="slot-action">
              <form action="<?php echo URLROOT; ?>/Supplier/requestTimeSlot" method="post">
                <input type="hidden" name="slot_id" value="<?php echo $slot->slot_id; ?>">
                <button type="submit" class="request-btn">Request Appointment</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-slots">
        <p>No available time slots found. Please check back later.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- My Appointment Requests Section -->
  <div class="my-requests-section">
    <div class="section-header">
      <h3>My Appointment Requests</h3>
    </div>
    <?php if (!empty($data['my_requests'])): ?>
      <?php foreach($data['my_requests'] as $request): ?>
        <div class="request-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge <?php echo strtolower($request->status); ?>">
                <?php echo $request->status; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="request-info">
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span>Date: <?php echo date('m/d/Y', strtotime($request->date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-time-five'></i>
                  <span>Time: <?php echo date('h:i A', strtotime($request->start_time)); ?> - <?php echo date('h:i A', strtotime($request->end_time)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-user'></i>
                  <span>Manager: <?php echo isset($request->manager_name) ? $request->manager_name : 'Manager #' . $request->manager_id; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calendar-check'></i>
                  <span>Requested on: <?php echo date('m/d/Y', strtotime($request->submitted_at)); ?></span>
                </div>
              </div>
              <?php if($request->status === 'Pending'): ?>
                <div class="request-action">
                  <button class="cancel-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/cancelRequest/<?php echo $request->request_id; ?>'">
                    <i class='bx bx-x'></i> Cancel Request
                  </button>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-requests">
        <p>You haven't made any appointment requests yet.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Confirmed Appointments Section -->
  <div class="confirmed-appointments-section">
    <div class="section-header">
      <h3>Confirmed Appointments</h3>
    </div>
    <?php if (!empty($data['confirmed_appointments'])): ?>
      <?php foreach($data['confirmed_appointments'] as $appointment): ?>
        <div class="appointment-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge confirmed">
                Confirmed
              </div>
            </div>
            <div class="card-body">
              <div class="appointment-info">
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span>Date: <?php echo date('m/d/Y', strtotime($appointment->date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-time-five'></i>
                  <span>Time: <?php echo date('h:i A', strtotime($appointment->start_time)); ?> - <?php echo date('h:i A', strtotime($appointment->end_time)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-user'></i>
                  <span>Manager: <?php echo isset($appointment->manager_name) ? $appointment->manager_name : 'Manager #' . $appointment->manager_id; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-check-circle'></i>
                  <span>Confirmed on: <?php echo date('m/d/Y', strtotime($appointment->accepted_at)); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-appointments">
        <p>You don't have any confirmed appointments.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
  /* Root Variables */
  :root {
    --primary-color: var(--mainn);
    --secondary-color: #2ecc71;
    --text-primary: #2c3e50;
    --text-secondary: #7f8c8d;
    --background-light: #f8f9fa;
    --border-color: #e0e0e0;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --pending-color: #3498db;
    --confirmed-color: #2ecc71;
    --rejected-color: #e74c3c;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
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

  .head-title {
    margin-bottom: var(--spacing-xl);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
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

  .section-header {
    margin-bottom: var(--spacing-md);
  }

  .section-header h3 {
    font-size: 1.25rem;
    color: var(--text-primary);
  }

  /* Flash Messages */
  .alert {
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    border-radius: var(--border-radius-md);
    background-color: var(--success-color);
    color: white;
  }

  .alert-error {
    background-color: var(--danger-color);
  }

  /* Available Time Slots Section */
  .available-slots-section {
    margin-bottom: var(--spacing-xl);
  }

  .slots-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-lg);
  }

  .slot-card {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: var(--spacing-lg);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
  }

  .slot-info {
    margin-bottom: var(--spacing-md);
  }

  .slot-header {
    display: flex;
    align-items: center;
    margin-bottom: var(--spacing-sm);
  }

  .slot-header i, .slot-time i, .slot-manager i {
    color: var(--primary-color);
    margin-right: var(--spacing-sm);
    font-size: 1.25rem;
  }

  .slot-time, .slot-manager {
    display: flex;
    align-items: center;
    margin-bottom: var(--spacing-sm);
  }

  .date {
    font-weight: bold;
    color: var(--text-primary);
  }

  .slot-action {
    margin-top: var(--spacing-md);
  }

  .request-btn {
    width: 100%;
    padding: var(--spacing-sm);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .request-btn:hover {
    background-color: var(--secondary-color);
  }

  .no-slots, .no-requests, .no-appointments {
    padding: var(--spacing-lg);
    text-align: center;
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    color: var(--text-secondary);
  }

  /* My Requests Section */
  .my-requests-section {
    margin-bottom: var(--spacing-xl);
  }

  .request-card, .appointment-card {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: var(--spacing-md);
    padding: var(--spacing-lg);
  }

  .card-header {
    margin-bottom: var(--spacing-md);
  }

  .status-badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    color: white;
    font-size: 0.875rem;
  }

  .status-badge.pending {
    background-color: var(--pending-color);
  }

  .status-badge.confirmed, .status-badge.accepted {
    background-color: var(--confirmed-color);
  }

  .status-badge.rejected {
    background-color: var(--rejected-color);
  }

  .request-info, .appointment-info {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    align-self: center;
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .info-item i {
    color: var(--primary-color);
  }

  .request-action {
    display: flex;
    justify-content: flex-end;
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border-color);
  }

  .cancel-btn {
    padding: var(--spacing-sm) var(--spacing-md);
    background-color: transparent;
    border: 1px solid var(--danger-color);
    color: var(--danger-color);
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .cancel-btn:hover {
    background-color: var(--danger-color);
    color: white;
  }

  /* Confirmed Appointments Section */
  .confirmed-appointments-section {
    margin-bottom: var(--spacing-xl);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .slots-container {
      grid-template-columns: 1fr;
    }
    
    .request-info, .appointment-info {
      grid-template-columns: 1fr;
    }
  }
</style>