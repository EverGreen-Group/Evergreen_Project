<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Sidebar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>

<!-- Top Nav Bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<script>
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Route #<?php echo htmlspecialchars($data['route_id']); ?></h1>
            <ul class="breadcrumb">
                <li><a href="#"><?php echo htmlspecialchars($data['route_name']); ?></a></li>
            </ul>
        </div>
    </div>

  <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-user'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['number_of_suppliers']) ? (int)$data['number_of_suppliers'] : 0; ?></h3>
                    <p>Number of Suppliers</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-shopping-bag'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['vehicleDetails']->capacity) ? (int)$data['vehicleDetails']->capacity : 0; ?></h3>
                    <p>Remaining Capacity</p>
                </div>
            </div>
        </li>
    </ul>

  <!-- Unassigned Suppliers Section -->
  <div class="table-data">
    <div class="order">
      <div class="head">
          <h3>Unassigned Suppliers</h3>
        <a href="<?php echo URLROOT; ?>/vehiclemanager/unallocatedSuppliers" class="btn btn-primary">
            <i class='bx bx-search'></i>
            View All Unallocated Suppliers
        </a>
      </div>
      <div class="filter-options">
          <form id="addSupplierForm" action="<?php echo URLROOT; ?>/route/addSupplier" method="POST">
              <div class="filter-group">
                  <label for="employee-status">Select suppliers:</label>
                  <select id="employee-status" name="supplier_id" required>
                      <option value="">-- Select Supplier --</option>
                      <?php foreach ($data['unassignedSuppliers'] as $supplier): ?>
                          <option value="<?php echo htmlspecialchars($supplier->supplier_id); ?>">
                            ID: <?php echo htmlspecialchars($supplier->supplier_id); ?> - 
                              <?php echo htmlspecialchars($supplier->full_name); ?> - 
                              Avg Collection: <?php echo htmlspecialchars($supplier->average_collection); ?> kg
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <input type="hidden" name="route_id" value="<?php echo htmlspecialchars($data['route_id']); ?>">
              <button type="submit" class="btn btn-primary">Add Stop</button>
          </form>
      </div>
    </div>
  </div>

  <!-- Route Suppliers Section -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Route Suppliers</h3>
        <a href="<?php echo URLROOT; ?>/vehiclemanager/unallocatedSuppliers" class="btn btn-primary">
            <i class='bx bx-search'></i>
            View In Map
        </a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Supplier Stop</th>
            <th>Supplier Name</th>
            <th>Average Collection (kg)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="supplierTableBody">
            <?php foreach ($data['routeSuppliers'] as $supplier): ?>
                <tr>
                    <td><?php echo htmlspecialchars($supplier->stop_order); ?></td>
                    <td><?php echo htmlspecialchars($supplier->full_name); ?></td>
                    <td><?php echo htmlspecialchars($supplier->average_collection); ?></td>
                    <td>
                        <form action="<?php echo URLROOT; ?>/route/removeSupplier" method="POST">
                            <input type="hidden" name="supplier_id" value="<?php echo htmlspecialchars($supplier->supplier_id); ?>">
                            <input type="hidden" name="route_id" value="<?php echo htmlspecialchars($data['route_id']); ?>">
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>


</main>


<style>
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    transition: transform 0.3s ease;
    
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-content i {
    font-size: 2.5rem;
    color: var(--main);
}

.stat-info h3 {
    font-size: 1.8rem;
    margin-bottom: 5px;
    color: var(--dark);
}

.stat-info p {
    color: #555;
    font-size: 0.9rem;
    font-weight: 500;
    margin: 0;
    opacity: 1;
}

.stat-details {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
}
</style>



<?php 
// Include additional CSS and external scripts
echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/route-management.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC8AYYCYuMkIUAjQWsAwQDiqbMmLa-7eo&callback=initMap"></script>';

require APPROOT . '/views/inc/components/footer.php'; 
?>
