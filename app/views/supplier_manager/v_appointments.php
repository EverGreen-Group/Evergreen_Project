<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<main>
  <div class="head-title">
    <div class="left">
        <h1>Manage Appointments</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Appointments</a></li>
        </ul>
    </div>
  </div>

  <!-- Section 1: Your Time Slots -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>My Time Slots</h3>
        <div class="action-buttons">
          <form action="<?php echo URLROOT; ?>/manager/appointments" method="GET" class="search-form">
            <div class="filter-group">
              <label for="status">Status:</label>
              <select id="status" name="status">
                <option value="">All Statuses</option>
                <option value="Available" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                <option value="Booked" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Booked') ? 'selected' : ''; ?>>Booked</option>
              </select>
            </div>
            <div class="filter-group">
              <label for="date">Date:</label>
              <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="<?php echo URLROOT; ?>/manager/appointments" class="btn btn-primary">Reset</a>
          </form>
          <a href="<?php echo URLROOT; ?>/manager/createSlot" class="btn btn-primary create-slot-btn">
            <i class='bx bx-plus'></i>
            Create Time Slot
          </a>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Slot ID</th>
            <th>Date</th>
            <th>Time Range</th>
            <th>Status</th>
            <th>Cancellation</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($timeSlots)): ?>
            <?php foreach ($timeSlots as $slot): ?>
              <tr>
                <td><?php echo htmlspecialchars($slot->slot_id); ?></td>
                <td><?php echo htmlspecialchars($slot->date); ?></td>
                <td><?php echo htmlspecialchars($slot->start_time . ' - ' . $slot->end_time); ?></td>
                <td>
                    <span class="status-badge <?php echo strtolower($slot->status === 'Booked' ? 'oranged' : 'added'); ?>">
                        <?php echo ucfirst($slot->status); ?>
                    </span>
                </td>
                <td>
                  <?php if ($slot->status == 'Available'): ?>
                    <a href="<?php echo URLROOT; ?>/manager/cancelSlot/<?php echo $slot->slot_id; ?>" 
                       class="btn btn-secondary" 
                       style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                       title="Cancel Slot" 
                       data-confirm="Are you sure that you want to remove this time slot?">
                        <i class='bx bx-trash' style="font-size: 24px; color:red;"></i>
                    </a>
                  <?php else: ?>
                    <span class="disabled-action btn" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none; cursor: not-allowed; margin-left:10px;">
                        <i class='bx bx-trash' style="font-size: 24px; color: grey;"></i>
                    </span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">No slots created yet</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Section 2: Incoming Appointment Requests -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Incoming Requests</h3>
        <a href="<?php echo URLROOT; ?>/manager/rejectedAppointments" class="btn btn-primary">
          <i class='bx bx-show'></i>
          View Rejected Requests
        </a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Request ID</th>
            <th>Supplier</th>
            <th>Requested Slot</th>
            <th>Submitted At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($incomingRequests)): ?>
            <?php foreach ($incomingRequests as $req): ?>
              <tr>
                <td><?php echo htmlspecialchars($req->request_id); ?></td>
                <td>
                    <a href="<?php echo URLROOT; ?>/manager/manageSupplier/<?php echo htmlspecialchars($req->supplier_id); ?>" class="manager-link">
                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($req->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                        <?php echo htmlspecialchars($req->supplier_name); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($req->date . ' (' . $req->start_time . ' - ' . $req->end_time . ')'); ?></td>
                <td><?php echo htmlspecialchars($req->submitted_at); ?></td>
                <td>
                  <form method="POST" action="<?php echo URLROOT; ?>/manager/respondRequest" style="display: flex; gap: 5px;">
                    <input type="hidden" name="request_id" value="<?php echo $req->request_id; ?>">
                    <button name="action" value="accept" class="btn btn-primary" title="Accept"><i class='bx bx-check'></i></button>
                    <button name="action" value="reject" class="btn btn-danger" title="Reject"><i class='bx bx-x'></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No incoming requests</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Section 3: Accepted Appointments -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Accepted Appointments</h3>
        <a href="<?php echo URLROOT; ?>/manager/allAppointments" class="btn btn-primary">
          <i class='bx bx-search-alt-2'></i>
          View All Appointments
        </a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Appointment ID</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($acceptedAppointments)): ?>
            <?php foreach ($acceptedAppointments as $app): ?>
              <tr>
                <td><?php echo htmlspecialchars($app->appointment_id); ?></td>
                <td>
                    <a href="<?php echo URLROOT; ?>/manager/manageSupplier/<?php echo htmlspecialchars($app->supplier_id); ?>" class="manager-link">
                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($app->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                        <?php echo htmlspecialchars($app->supplier_name); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($app->date); ?></td>
                <td><?php echo htmlspecialchars(date('h:i A',strtotime($app->start_time))); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">No accepted appointments</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<style>
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .pending {
        background-color:rgb(214, 159, 49);
        color: #FFA800;
    }

    .booked {
        background-color: #FFF4DE;
        color: #FFA800;
    }
    
    .approved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }
    
    .rejected, .auto-rejected {
        background-color: #FFE2E5;
        color: #F64E60;
    }
    
    .constraint-group {
        margin-bottom: 20px;
    }
    
    .constraint-group h4 {
        margin-bottom: 10px;
        color: #333;
    }
    
    .constraint-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .constraint-item label {
        width: 250px;
        font-weight: 500;
    }
    
    .constraint-item input {
        width: 150px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .constraint-display-item {
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 10px;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .manager-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit; /* Inherit text color */
    }

    .manager-photo {
        width: 30px; /* Set the desired width */
        height: 30px; /* Set the desired height */
        border-radius: 50%; /* Make it circular */
        margin-right: 8px; /* Space between image and name */
        object-fit: cover; /* Ensure the image covers the area */
    }

    /* Improved styles for action buttons and search form */
    .action-buttons {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .search-form {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-left: auto; /* push form to the right */
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .filter-group label {
        font-size: 12px;
        margin-bottom: 4px;
    }

    .filter-group select,
    .filter-group input {
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        min-width: 120px;
        max-width: 150px;
        height: 38px;
    }

    /* Style for all buttons in the search form */
    .search-form .btn {
        padding: 8px 15px;
        height: 38px;
        font-size: 14px;
        display: flex;
        align-items: right;
        justify-content: center;
        border-radius: 4px;
        margin: 0;
    }

    .create-slot-btn {
        white-space: nowrap;
        height: 38px;
        padding: 8px 15px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    /* Make it responsive */
    @media (max-width: 992px) {
        .action-buttons {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .search-form {
            width: 100%;
            flex-wrap: wrap;
        }
        
        .create-slot-btn {
            margin-top: 10px;
            align-self: flex-start;
        }
    }

    @media (max-width: 768px) {
        .filter-group {
            min-width: 45%;
        }
        
        .search-form .btn {
            margin-top: 10px;
        }
    }

    @media (max-width: 576px) {
        .filter-group {
            min-width: 100%;
            margin-bottom: 10px;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-form .btn {
            width: 100%;
        }
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
