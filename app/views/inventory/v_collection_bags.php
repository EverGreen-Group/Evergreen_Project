<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
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
        <i class='bx bxs-shopping-bag'></i>
        <div class="stat-info">
          <h3><?php echo isset($activeBagsCount) ? $activeBagsCount : 0; ?></h3>
          <p>Bags In Use</p>
        </div>
      </div>
    </li>

    <li class="stat-card">
      <div class="stat-content">
        <i class='bx bx-shopping-bag'></i>
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
        <a href="<?php echo URLROOT; ?>/inventory/viewBagUsageHistory" class="btn btn-primary">
          <i class='bx bx-show'></i>
          View Bag Usage History
        </a>

      </div>
      <table>
        <thead>
          <tr>
            <th>Bag ID</th>
            <th>Capacity (kg)</th>
            <th>Assigned Since</th>
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
                <td>
                  <a href="<?php echo URLROOT; ?>/inventory/markAsActive/<?php echo $bag->bag_id; ?>"
                    class="btn btn-primary" title="Mark as Inactive">
                    <i class='bx bx-package'></i> Empty Bag
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="text-align:center;">No bags currently in use</td>
            </tr>
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
            <th>Last Used</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($activeBags)): ?>
            <?php foreach ($activeBags as $bag): ?>
              <tr>
                <td><?php echo htmlspecialchars($bag->bag_id); ?></td>
                <td><?php echo htmlspecialchars($bag->capacity_kg); ?></td>
                <td><?php echo htmlspecialchars($bag->assigned_at); ?></td>
                <td><span class="status-badge added">Available</span></td>
                <td>
                  <a href="<?php echo URLROOT; ?>/inventory/deleteBag/<?php echo $bag->bag_id; ?>" class="btn btn-danger"
                    title="Delete">
                    <i class='bx bx-trash'></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="text-align:center;">No available bags</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>


</main>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>