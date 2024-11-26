<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>


  <!-- Route Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Route Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
      <a href="<?php echo URLROOT; ?>/routes/create" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Create Route
      </a>

      <a href="<?php echo URLROOT; ?>/routes/update" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Update Route
      </a>
  </div>


<!-- Route Capacity Overview -->
<!-- <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Route Capacity Overview</h3>
        </div>
        <div class="shifts-overview">

            <div class="shift-block">
                <div class="shift-header">
                    <i class='bx bx-sun'></i>
                    <h4>Morning Shift (6AM-2PM)</h4>
                </div>
                <div class="routes-list">
                    <div class="route-card">
                        <div class="route-info">
                            <span class="route-name">Route A1</span>
                            <span class="route-path">16 Suppliers </span>
                        </div>
                        <div class="capacity-wrapper">
                            <div class="capacity-bar">
                                <div class="capacity-fill" style="width: 60%"></div>
                            </div>
                            <span class="capacity-text">600/1000kg</span>
                        </div>
                    </div>
                    <div class="route-card">
                        <div class="route-info">
                            <span class="route-name">Route A2</span>
                            <span class="route-path">16 Suppliers</span>
                        </div>
                        <div class="capacity-wrapper">
                            <div class="capacity-bar">
                                <div class="capacity-fill" style="width: 85%"></div>
                            </div>
                            <span class="capacity-text">850/1000kg</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="shift-block">
                <div class="shift-header">
                    <i class='bx bx-sun'></i>
                    <h4>Afternoon Shift (2PM-10PM)</h4>
                </div>
                <div class="routes-list">
                    <div class="route-card">
                        <div class="route-info">
                            <span class="route-name">Route B1</span>
                            <span class="route-path">16 Suppliers</span>
                        </div>
                        <div class="capacity-wrapper">
                            <div class="capacity-bar">
                                <div class="capacity-fill" style="width: 45%"></div>
                            </div>
                            <span class="capacity-text">450/1000kg</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shift-block">
                <div class="shift-header">
                    <i class='bx bx-moon'></i>
                    <h4>Night Shift (10PM-6AM)</h4>
                </div>
                <div class="routes-list">
                    <div class="route-card">
                        <div class="route-info">
                            <span class="route-name">Route C1</span>
                            <span class="route-path">16 Suppliers</span>
                        </div>
                        <div class="capacity-wrapper">
                            <div class="capacity-bar">
                                <div class="capacity-fill" style="width: 25%"></div>
                            </div>
                            <span class="capacity-text">250/1000kg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->



  <!-- First row with Routes table -->
  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Routes</h3>
            <i class='bx bx-search'></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Route ID</th>
                    <th>Name</th>
                    <th>Suppliers</th>
                    <th>Vehicle ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allRoutes as $route): ?>
                    <tr class="route-row" data-route-id="<?php echo htmlspecialchars($route->route_id); ?>">
                        <td><?php echo htmlspecialchars($route->route_id); ?></td>
                        <td><?php echo htmlspecialchars($route->route_name); ?></td>
                        <td><?php echo htmlspecialchars($route->number_of_suppliers); ?></td>
                        <td><?php echo htmlspecialchars($route->license_plate); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </div>

  <!-- Second row with Unallocated Suppliers table -->
  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Unallocated Suppliers</h3>
            <i class='bx bx-search'></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['unassignedSuppliersList'] as $supplier): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($supplier->supplier_id); ?></td>
                        <td><?php echo htmlspecialchars($supplier->first_name); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </div>




</main>


<style>
       /* Add these new styles */
       .shifts-overview {
        display: flex;
        gap: 20px;
        padding: 15px;
    }

    .shift-block {
        flex: 1;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }

    .shift-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .shift-header i {
        font-size: 1.2rem;
        color: var(--main);
    }

    .shift-header h4 {
        font-size: 1rem;
        color: #333;
        margin: 0;
    }

    /* Capacity Overview Route Cards */
    .shifts-overview .routes-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .shifts-overview .route-card {
        background: white;
        border-radius: 6px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        width: auto; /* Override the width from other route cards */
        transform: none; /* Remove hover transform */
        cursor: default; /* Remove pointer cursor */
    }

    .shifts-overview .route-info {
        margin-bottom: 8px;
    }

    .shifts-overview .route-name {
        font-weight: 600;
        color: #2c3e50;
        display: block;
        margin-bottom: 4px;
    }

    .shifts-overview .route-path {
        font-size: 0.85rem;
        color: #666;
        display: block;
    }

    .capacity-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .capacity-bar {
        flex: 1;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .capacity-text {
        font-size: 0.85rem;
        color: #666;
        min-width: 90px;
        text-align: right;
    }

    /* Capacity fill colors based on percentage */
    .capacity-fill {
        height: 100%;
        background-color: #4CAF50;
        transition: width 0.3s ease;
    }

    .capacity-fill[style*="width: 7"], 
    .capacity-fill[style*="width: 8"] {
        background-color: #ff9800;
    }

    .capacity-fill[style*="width: 9"] {
        background-color: #f44336;
    }

    /* Routes Section Cards (different from capacity overview cards) */
    .routes-section .route-card {
        background: var(--light);
        border-radius: 10px;
        padding: 15px;
        width: calc(25% - 15px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .routes-section .route-card:hover {
        transform: translateY(-5px);
    }

    /* Responsive Design */
    @media screen and (max-width: 1200px) {
        .shifts-overview {
            flex-direction: column;
        }

        .shift-block {
            width: 100%;
        }
    }

    @media screen and (max-width: 1024px) {
        .routes-section .route-card {
            width: calc(33.33% - 13.33px);
        }
    }

    @media screen and (max-width: 768px) {
        .routes-section .route-card {
            width: calc(50% - 10px);
        }
    }

    @media screen and (max-width: 480px) {
        .routes-section .route-card {
            width: 100%;
        }
    }

    #map {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        background-color: #f5f5f5; /* Light grey background before map loads */
    }

    .route-map-section {
        flex: 1;
        min-height: 400px;
        background: var(--light);
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .route-actions {
        display: flex;
        gap: 10px;
    }

    .btn-create, .btn-update {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-create {
        background-color: var(--blue);
        color: white;
    }

    .btn-update {
        background-color: var(--yellow);
        color: var(--dark);
    }

    .btn-create:hover {
        background-color: #2980b9;
    }

    .btn-update:hover {
        background-color: #f39c12;
    }

    .btn-create:disabled, .btn-update:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .btn-create i, .btn-update i {
        font-size: 16px;
    }
</style>

<!-- Google Maps API Script -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&libraries=places,geometry&callback=initMap"></script>

<script>
    function createRoute() {
        // Add your create route logic here
        console.log('Create route clicked');
    }

    function updateRoute() {
        // Add your update route logic here
        console.log('Update route clicked');
    }
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>