<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
  <!-- Team Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Driver Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/addDriver" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Add New Driver
      </a>

      <a href="<?php echo URLROOT; ?>/vehiclemanager/updateDriver" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Update Driver
      </a>
  </div>


  <ul class="box-info">
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
          <p>Total Drivers</p>
          <h3><?php echo $total_drivers; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-check'></i>
        <span class="text">
          <p>On Duty</p>
          <h3><?php echo $on_duty_drivers; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-x'></i>
        <span class="text">
          <p>Unassigned Drivers</p>
          <h3><?php echo $unassigned_drivers_count; ?></h3>
        </span>
    </li>
  </ul>

  <div class="table-data table-container">
      <div class="order">
          <div class="head">
              <h3>Unassigned Drivers</h3>
          </div>
          <table>
              <thead>
                  <tr>
                      <th>Driver ID</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($data['unassigned_drivers'] as $driver): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($driver->driver_id); ?></td>
                          <td><?php echo htmlspecialchars($driver->driver_name); ?></td>
                          <td><?php echo htmlspecialchars($driver->contact_number); ?></td>
                          <td>
                              <span class="status completed"><?php echo htmlspecialchars($driver->status); ?></span>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>

  <!-- Section for All Drivers -->
  <div class="table-data table-container">
      <div class="order">
          <div class="head">
              <h3>All Drivers</h3>
          </div>
          <table>
              <thead>
                  <tr>
                      <th>Driver ID</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($data['all_drivers'] as $driver): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($driver->driver_id); ?></td>
                          <td><?php echo htmlspecialchars($driver->driver_name); ?></td>
                          <td><?php echo htmlspecialchars($driver->contact_number); ?></td>
                          <td>
                              <span class="status completed"><?php echo htmlspecialchars($driver->status); ?></span>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>

</main>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>
