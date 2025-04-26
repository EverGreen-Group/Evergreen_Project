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
        <h1>All Appointments</h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo URLROOT; ?>/manager/dashboard">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a href="<?php echo URLROOT; ?>/manager/appointments">Appointments</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">All Appointments</a></li>
        </ul>
    </div>
    <div>
        <a href="<?php echo URLROOT; ?>/manager/appointments" class="btn">
            <i class='bx bx-arrow-back'></i>
            <span class="text">Back to Appointments</span>
        </a>
    </div>
  </div>
  
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Appointment Records</h3>
        <div class="search-box">
          <input type="text" id="appointmentSearch" placeholder="Search...">
          <i class='bx bx-search' ></i>
        </div>
      </div>
      <table id="appointmentTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($appointments)): ?>
            <?php foreach ($appointments as $appointment): ?>
              <tr>
                <td><?php echo htmlspecialchars($appointment->appointment_id); ?></td>
                <td>
                  <?php if (isset($appointment->supplier_id)): ?>
                    <a href="<?php echo URLROOT; ?>/manager/managesupplier/<?php echo $appointment->supplier_id; ?>" class="supplier-link">
                      <i class='bx bx-user'></i> ID: <?php echo htmlspecialchars($appointment->supplier_id); ?>
                    </a>
                  <?php else: ?>
                    N/A
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($appointment->date); ?></td>
                <td>
                  <?php 
                    // Get time slot if available
                    echo isset($appointment->start_time) && isset($appointment->end_time) 
                      ? htmlspecialchars($appointment->start_time . ' - ' . $appointment->end_time) 
                      : 'Time not available';
                  ?>
                </td>
                <td>
                  <span class="status-badge <?php echo strtolower($appointment->status); ?>">
                    <?php echo ucfirst(htmlspecialchars($appointment->status)); ?>
                  </span>
                </td>
                <td>
                  <a href="<?php echo URLROOT; ?>/manager/viewAppointment/<?php echo $appointment->appointment_id; ?>" class="btn-view" title="View Details">
                    <i class='bx bx-show'></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No appointments found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<style>
    /* Improved Table Styling */
    .table-data {
        width: 100%;
        margin-top: 1.5rem;
    }
    
    .order {
        width: 100%;
        background: #fff;
        padding: 24px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .search-box {
        position: relative;
        width: 250px;
    }
    
    .search-box input {
        width: 100%;
        height: 36px;
        padding: 0 15px 0 40px;
        border: 1px solid #ddd;
        border-radius: 36px;
        outline: none;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 10px;
        color: #777;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    table th {
        font-weight: 600;
        background-color: #f8f9fa;
        color: #495057;
    }
    
    table tbody tr:hover {
        background-color: #f6f9ff;
    }
    
    /* Status Badge Styling */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }
    
    .accepted, .approved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }
    
    .pending {
        background-color: #FFF4DE;
        color: #FFA800;
    }
    
    .rejected {
        background-color: #FFE2E5;
        color: #F64E60;
    }
    
    /* Button Styling */
    .btn-view {
        display: inline-block;
        padding: 6px 10px;
        background-color: #3B5D50;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-view:hover {
        background-color: #2a4438;
    }
    
    .btn-download {
        display: flex;
        align-items: center;
    }
    
    .btn {
        display: flex;
        align-items: center;
        padding: 0.6rem 1rem;
        background: #3B5D50;
        color: white;
        border-radius: 5px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        background: #2a4438;
    }
    
    .btn i {
        margin-right: 0.5rem;
    }
    
    /* Supplier Link Styling */
    .supplier-link {
        color: #3B5D50;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
    }
    
    .supplier-link i {
        margin-right: 5px;
    }
    
    .supplier-link:hover {
        text-decoration: underline;
    }
</style>

<script>
  // Simple search functionality
  document.addEventListener('DOMContentLoaded', function() {
    const searchBox = document.getElementById('appointmentSearch');
    const table = document.getElementById('appointmentTable');
    const rows = table.getElementsByTagName('tr');
    
    searchBox.addEventListener('keyup', function() {
      const searchTerm = searchBox.value.toLowerCase();
      
      for (let i = 1; i < rows.length; i++) { // Start at 1 to skip header row
        let found = false;
        const cells = rows[i].getElementsByTagName('td');
        
        for (let j = 0; j < cells.length; j++) {
          const cellText = cells[j].textContent.toLowerCase();
          
          if (cellText.indexOf(searchTerm) > -1) {
            found = true;
            break;
          }
        }
        
        rows[i].style.display = found ? '' : 'none';
      }
    });
  });
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>