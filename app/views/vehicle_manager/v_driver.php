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
                      <th></th>
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
                          <td>
                              <a href="<?php echo URLROOT; ?>/profile/driver/<?php echo $driver->user_id; ?>" class="btn btn-view">View Profile</a>

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
                      <th></th>
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
                          <td>
                              <a href="<?php echo URLROOT; ?>/profile/driver/<?php echo $driver->user_id; ?>" class="btn btn-view">View Profile</a>
                              

                          </td>
                          <td>
                          <form action="<?php echo URLROOT; ?>/vehiclemanager/removeDriver/<?php echo $driver->user_id; ?>" method="POST" style="display:inline;">
                                  <button type="submit" class="btn btn-remove" onclick="return confirm('Are you sure you want to remove this driver?');">
                                      <i class='bx bx-trash'></i>
                                  </button>
                              </form>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>

</main>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
.btn-view {
    display: inline-block; /* Make it behave like a button */
    padding: 2px 5px; /* Add some padding */
    background-color: var(--main); /* Button background color */
    color: white; /* Text color */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline */
    border: none; /* Remove border */
    border-radius: 4px; /* Slightly round the corners */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.btn-view:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

.btn-remove {
    display: inline-block; /* Make it behave like a button */
    padding: 2px 5px; /* Add some padding */
    background-color: #dc3545; /* Red background color for remove */
    color: white; /* Text color */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline */
    border: none; /* Remove border */
    border-radius: 4px; /* Slightly round the corners */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
    margin-left: 5px; /* Space between buttons */
    cursor: pointer; /* Change cursor to pointer */
}

.btn-remove:hover {
    background-color: #c82333; /* Darker shade on hover */
}
</style>
