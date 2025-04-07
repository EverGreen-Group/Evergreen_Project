<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require APPROOT.'/views/inc/components/sidebar_inventory.php'; ?>
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
        <h1>Manage Collection Bags</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Collection Bags</a></li>
        </ul>
    </div>
  </div>


  <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-shopping-bags'></i>
                <div class="stat-info">
                    <h3><?php echo isset($totalBags) ? $totalBags : 0; ?></h3>
                    <p>Total Bags</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-shopping-bag' ></i>
                <div class="stat-info">
                    <h3><?php echo isset($activeBagsCount) ? $activeBagsCount : 0; ?></h3>
                    <p>Bags In Use</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-shopping-bag' ></i>
                <div class="stat-info">
                    <h3><?php echo isset($inactiveBagsCount) ? $inactiveBagsCount : 0; ?></h3>
                    <p>Available Bags</p>
                </div>
            </div>
        </li>
    </ul>

  <!-- Section 1: Collection Bags In Use -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Collection Bags In Use</h3>
        <a href="<?php echo URLROOT; ?>/inventory/createBag" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Add New Bag
        </a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Bag ID</th>
            <th>Capacity (kg)</th>
            <th>Current Weight (kg)</th>
            <th>Assigned Since</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($activeBags)): ?>
            <?php foreach ($activeBags as $bag): ?>
              <tr>
                <td><?php echo htmlspecialchars($bag->bag_id); ?></td>
                <td><?php echo htmlspecialchars($bag->capacity_kg); ?></td>
                <td><?php echo htmlspecialchars($bag->bag_weight_kg ?? 'Not weighed'); ?></td>
                <td><?php echo htmlspecialchars($bag->assigned_at); ?></td>
                <td>
                  <a href="<?php echo URLROOT; ?>/inventory/markAsInactive/<?php echo $bag->bag_id; ?>" class="btn btn-primary" title="Mark as Inactive">
                    <i class='bx bx-package'></i> Empty Bag
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No bags currently in use</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Section 2: Available Collection Bags -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Available Collection Bags</h3>
      </div>
      <table>
        <thead>
          <tr>
            <th>Bag ID</th>
            <th>Capacity (kg)</th>
            <th>Last Used</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($inactiveBags)): ?>
            <?php foreach ($inactiveBags as $bag): ?>
              <tr>
                <td><?php echo htmlspecialchars($bag->bag_id); ?></td>
                <td><?php echo htmlspecialchars($bag->capacity_kg); ?></td>
                <td><?php echo htmlspecialchars($bag->assigned_at); ?></td>
                <td><span class="status-badge approved">Available</span></td>
                <td>
                  <a href="<?php echo URLROOT; ?>/inventory/deleteBag/<?php echo $bag->bag_id; ?>" class="btn btn-danger" title="Delete">
                    <i class='bx bx-trash'></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No available bags</td></tr>
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
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>