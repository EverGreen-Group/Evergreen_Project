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

  <!-- Search and Filter Section -->
  <div class="search-filter-container">
    <div class="search-box">
      <i class='bx bx-search-alt'></i>
      <input type="text" id="searchInput" placeholder="Search appointments...">
    </div>
    <div class="filter-options">
      <select id="statusFilter">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="rejected">Rejected</option>
      </select>
      <select id="dateFilter">
        <option value="all">All Dates</option>
        <option value="today">Today</option>
        <option value="this-week">This Week</option>
        <option value="this-month">This Month</option>
      </select>
    </div>
  </div>

  <!-- Available Time Slots Section -->
  <div class="available-slots-section">
    <div class="section-header">
      <h3><i class='bx bx-calendar-check'></i> Available Time Slots</h3>
    </div>
    <?php if (!empty($data['time_slots'])): ?>
      <div class="slots-container">
        <?php foreach($data['time_slots'] as $slot): ?>
          <div class="slot-card searchable-item">
            <div class="manager-profile">
              <img src="<?php echo URLROOT . '/' . htmlspecialchars($slot->image_path); ?>" alt="Manager Profile" class="manager-image">
              <div class="manager-info">
                <h4><?php echo htmlspecialchars($slot->first_name . ' ' . $slot->last_name); ?></h4>
                <span class="manager-id">Manager #<?php echo $slot->manager_id; ?></span>
              </div>
            </div>
            <div class="slot-details">
              <div class="slot-info">
                <div class="slot-header">
                  <i class='bx bx-calendar'></i>
                  <span class="date"><?php echo date('l, F j, Y', strtotime($slot->date)); ?></span>
                </div>
                <div class="slot-time">
                  <i class='bx bx-time'></i>
                  <span><?php echo date('h:i A', strtotime($slot->start_time)); ?> - <?php echo date('h:i A', strtotime($slot->end_time)); ?></span>
                </div>
              </div>
              <div class="slot-action">
                <form action="<?php echo URLROOT; ?>/Supplier/requestTimeSlot" method="post">
                  <input type="hidden" name="slot_id" value="<?php echo $slot->slot_id; ?>">
                  <button type="submit" class="request-btn"><i class='bx bx-calendar-plus'></i> Request Appointment</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-slots">
        <i class='bx bx-calendar-x empty-icon'></i>
        <p>No available time slots found. Please check back later.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- My Appointment Requests Section -->
  <div class="my-requests-section">
    <div class="section-header">
      <h3><i class='bx bx-time-five'></i> My Appointment Requests</h3>
    </div>
    <?php if (!empty($data['my_requests'])): ?>
      <div class="requests-container">
        <?php foreach($data['my_requests'] as $request): ?>
          <?php if($request->status == 'Pending'): ?>
          <div class="request-card searchable-item" data-status="<?php echo strtolower($request->status); ?>" data-date="<?php echo date('Y-m-d', strtotime($request->date)); ?>">
            <div class="card-content">
              <div class="card-header">
                <div class="manager-profile">
                  <img src="<?php echo URLROOT . '/' . htmlspecialchars($request->image_path); ?>" alt="Manager Profile" class="manager-image">
                  <div class="manager-info">
                    <h4><?php echo htmlspecialchars($request->first_name . ' ' . $request->last_name); ?></h4>
                    <span class="manager-id">Manager #<?php echo $request->manager_id; ?></span>
                  </div>
                </div>
                <div class="status-badge <?php echo strtolower($request->status); ?>">
                  <?php echo $request->status; ?>
                </div>
              </div>
              <div class="card-body">
                <div class="request-info">
                  <div class="info-item">
                    <i class='bx bx-calendar'></i>
                    <span><?php echo date('m/d/Y', strtotime($request->date)); ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-time-five'></i>
                    <span><?php echo date('h:i A', strtotime($request->start_time)); ?> - <?php echo date('h:i A', strtotime($request->end_time)); ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-calendar-check'></i>
                    <span>Requested: <?php echo date('m/d/Y', strtotime($request->submitted_at)); ?></span>
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
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-requests">
        <i class='bx bx-calendar-exclamation empty-icon'></i>
        <p>You haven't made any appointment requests yet.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Confirmed Appointments Section -->
  <div class="confirmed-appointments-section">
    <div class="section-header">
      <h3><i class='bx bx-check-circle'></i> Confirmed Appointments</h3>
    </div>
    <?php if (!empty($data['confirmed_appointments'])): ?>
      <div class="appointments-container">
        <?php foreach($data['confirmed_appointments'] as $appointment): ?>
          <div class="appointment-card searchable-item" data-status="confirmed" data-date="<?php echo date('Y-m-d', strtotime($appointment->date)); ?>">
            <div class="card-content">
              <div class="card-header">
                <div class="manager-profile">
                  <img src="<?php echo URLROOT . '/' . htmlspecialchars($appointment->image_path); ?>" alt="Manager Profile" class="manager-image">
                  <div class="manager-info">
                    <h4><?php echo htmlspecialchars($appointment->first_name . ' ' . $appointment->last_name); ?></h4>
                    <span class="manager-id">Manager #<?php echo $appointment->manager_id; ?></span>
                  </div>
                </div>
                <div class="status-badge confirmed">
                  Confirmed
                </div>
              </div>
              <div class="card-body">
                <div class="appointment-info">
                  <div class="info-item">
                    <i class='bx bx-calendar'></i>
                    <span><?php echo date('m/d/Y', strtotime($appointment->date)); ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-time-five'></i>
                    <span><?php echo date('h:i A', strtotime($appointment->start_time)); ?> - <?php echo date('h:i A', strtotime($appointment->end_time)); ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-check-circle'></i>
                    <span>Confirmed: <?php echo date('m/d/Y', strtotime($appointment->accepted_at)); ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-appointments">
        <i class='bx bx-calendar-check empty-icon'></i>
        <p>You don't have any confirmed appointments.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<!-- Search and Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const dateFilter = document.getElementById('dateFilter');
  const searchableItems = document.querySelectorAll('.searchable-item');

  // Search function
  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    const dateValue = dateFilter.value;

    searchableItems.forEach(item => {
      let shouldShow = true;
      
      // Text search
      if (searchTerm) {
        const itemText = item.textContent.toLowerCase();
        shouldShow = itemText.includes(searchTerm);
      }
      
      // Status filter
      if (shouldShow && statusValue !== 'all') {
        const itemStatus = item.getAttribute('data-status');
        shouldShow = itemStatus === statusValue;
      }
      
      // Date filter
      if (shouldShow && dateValue !== 'all') {
        const itemDate = new Date(item.getAttribute('data-date'));
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (dateValue === 'today') {
          const todayStr = today.toISOString().split('T')[0];
          const itemDateStr = itemDate.toISOString().split('T')[0];
          shouldShow = todayStr === itemDateStr;
        } else if (dateValue === 'this-week') {
          const weekStart = new Date(today);
          weekStart.setDate(today.getDate() - today.getDay());
          const weekEnd = new Date(weekStart);
          weekEnd.setDate(weekStart.getDate() + 6);
          shouldShow = itemDate >= weekStart && itemDate <= weekEnd;
        } else if (dateValue === 'this-month') {
          shouldShow = itemDate.getMonth() === today.getMonth() && 
                        itemDate.getFullYear() === today.getFullYear();
        }
      }
      
      // Show or hide item
      item.style.display = shouldShow ? 'flex' : 'none';
    });
  }

  // Event listeners
  searchInput.addEventListener('input', performSearch);
  statusFilter.addEventListener('change', performSearch);
  dateFilter.addEventListener('change', performSearch);
});
</script>

<style>
  /* Root Variables */
  :root {
    --primary-color: var(--mainn);
    --secondary-color: var(--main);
    --text-primary: #2b2d42;
    --text-secondary: #8d99ae;
    --background-light: #f8f9fa;
    --background-card: #ffffff;
    --border-color: #e9ecef;
    --success-color: #2a9d8f;
    --warning-color: #e9c46a;
    --danger-color: #e76f51;
    --pending-color: #4895ef;
    --confirmed-color: #2a9d8f;
    --rejected-color: #e76f51;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --border-radius-sm: 6px;
    --border-radius-md: 10px;
    --border-radius-lg: 14px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
  }


  main {
    padding: var(--spacing-lg);
    margin: 0 auto;
    /* background-color: var(--background-light); */
  }

  /* Header Styles */
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
    font-weight: 600;
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    list-style: none;
    padding: 0;
    color: var(--text-secondary);
  }

  .breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .breadcrumb a:hover {
    color: var(--primary-color);
  }

  .breadcrumb i {
    color: var(--primary-color);
    font-size: 1.1rem;
  }

  /* Search and Filter Styles */
  .search-filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    justify-content: space-between;
    align-items: center;
    background-color: var(--background-card);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    box-shadow: var(--box-shadow);
  }

  .search-box {
    display: flex;
    align-items: center;
    background-color: var(--background-light);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--border-radius-sm);
    flex-grow: 1;
    max-width: 450px;
  }

  .search-box i {
    color: var(--text-secondary);
    margin-right: var(--spacing-sm);
  }

  .search-box input {
    border: none;
    background: transparent;
    outline: none;
    width: 100%;
    color: var(--text-primary);
  }

  .filter-options {
    display: flex;
    gap: var(--spacing-md);
  }

  .filter-options select {
    padding: var(--spacing-sm) var(--spacing-md);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    background-color: var(--background-light);
    color: var(--text-primary);
    outline: none;
    cursor: pointer;
  }

  /* Section Headers */
  .section-header {
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
  }

  .section-header h3 {
    font-size: 1.25rem;
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .section-header h3 i {
    color: var(--primary-color);
    font-size: 1.3rem;
  }

  /* Available Time Slots */
  .available-slots-section, 
  .my-requests-section, 
  .confirmed-appointments-section {
    margin-bottom: var(--spacing-xl);
    background-color: var(--background-card);
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--box-shadow);
  }

  .slots-container,
  .requests-container,
  .appointments-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: var(--spacing-lg);
  }

  .slot-card, 
  .request-card, 
  .appointment-card {
    background-color: var(--background-light);
    border-radius: var(--border-radius-md);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
  }

  .slot-card:hover, 
  .request-card:hover, 
  .appointment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }

  /* Manager Profile */
  .manager-profile {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
    background-color: white;
  }

  .manager-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-color);
  }

  .manager-info h4 {
    margin: 0;
    font-size: 1rem;
    color: var(--text-primary);
    font-weight: 600;
  }

  .manager-id {
    font-size: 0.85rem;
    color: var(--text-secondary);
  }

  .slot-details {
    padding: var(--spacing-md);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .slot-info {
    margin-bottom: var(--spacing-md);
  }

  .slot-header, .slot-time {
    display: flex;
    align-items: center;
    margin-bottom: var(--spacing-sm);
  }

  .slot-header i, .slot-time i {
    color: var(--primary-color);
    margin-right: var(--spacing-sm);
    font-size: 1.1rem;
  }

  .date {
    font-weight: 600;
    color: var(--text-primary);
  }

  .slot-action {
    margin-top: var(--spacing-md);
  }

  .request-btn {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
    font-weight: 500;
  }

  .request-btn:hover {
    background-color: var(--secondary-color);
  }

  /* Card Content */
  .card-content {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
    background-color: white;
    flex-direction: column;
    gap: var(--spacing-md);
  }

  .card-header .manager-profile {
    padding: 0;
    border-bottom: none;
    width: 100%;
    justify-content: flex-start;
  }

  .status-badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-md);
    border-radius: 50px;
    color: white;
    font-size: 0.875rem;
    font-weight: 500;
    align-self: flex-end;
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

  .card-body {
    padding: var(--spacing-md);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .request-info, .appointment-info {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .info-item i {
    color: var(--primary-color);
    font-size: 1.1rem;
    min-width: 20px;
    text-align: center;
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
    font-weight: 500;
  }

  .cancel-btn:hover {
    background-color: var(--danger-color);
    color: white;
  }

  /* Empty States */
  .no-slots, .no-requests, .no-appointments {
    padding: var(--spacing-xl);
    text-align: center;
    background-color: var(--background-light);
    border-radius: var(--border-radius-md);
    color: var(--text-secondary);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
  }

  .empty-icon {
    font-size: 3rem;
    color: var(--text-secondary);
    opacity: 0.6;
  }

  /* Responsive Design */
  @media (max-width: 992px) {
    .slots-container,
    .requests-container,
    .appointments-container {
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
  }

  @media (max-width: 768px) {
    .search-filter-container {
      flex-direction: column;
      align-items: stretch;
    }
    
    .search-box {
      max-width: none;
    }
    
    .filter-options {
      flex-wrap: wrap;
    }
    
    .filter-options select {
      flex-grow: 1;
    }
    
    .slots-container,
    .requests-container,
    .appointments-container {
      grid-template-columns: 1fr;
    }
    
    .card-header {
      flex-direction: column;
    }
    
    .card-header .manager-profile {
      margin-bottom: var(--spacing-sm);
    }
    
    .status-badge {
      align-self: flex-start;
    }
  }

  @media (max-width: 480px) {
    main {
      padding: var(--spacing-md);
    }
    
    .available-slots-section, 
    .my-requests-section, 
    .confirmed-appointments-section {
      padding: var(--spacing-md);
    }
  }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>