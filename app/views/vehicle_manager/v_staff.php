<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
  <div class="head-title">
    <div class="left">
      <h1>Staff Management</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li><i class="bx bx-chevron-right"></i></li>
        <li><a class="active" href="#">Staff Management</a></li>
      </ul>
    </div>
  </div>

  <div class="stats-tasks-wrapper">
    <div class="stats-container">
      <!-- Driver Details -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Driver Details</h3>
        </div>
        <table class="staff-table">
          <thead>
            <tr>
              <th>Driver ID</th>
              <th>Name</th>
              <th>License Number</th>
              <th>Experience</th>
              <th>Current Vehicle</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>D001</td>
              <td>John Doe</td>
              <td>DL12345678</td>
              <td>5 years</td>
              <td>VH001</td>
              <td>
                <button class="btn edit-btn">Edit</button>
                <button class="btn delete-btn">Delete</button>
              </td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>

                <!-- Vehicle Managers -->
                <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Vehicle Managers</h3>
        </div>
        <table class="staff-table">
          <thead>
            <tr>
              <th>Manager ID</th>
              <th>Name</th>
              <th>Teams Managed</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>VM001</td>
              <td>Alice Johnson</td>
              <td>3</td>
              <td>
                <button class="btn view-btn">View Teams</button>
              </td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>


    </div>
  </div>

  <div class="stats-tasks-wrapper">
    <div class="stats-container">

          <!-- Driving Partner Details -->
          <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Driving Partner Details</h3>
        </div>
        <table class="staff-table">
          <thead>
            <tr>
              <th>Partner ID</th>
              <th>Name</th>
              <th>Specialization</th>
              <th>Current Driver</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>P001</td>
              <td>Jane Smith</td>
              <td>Navigation Expert</td>
              <td>D001</td>
              <td>
                <button class="btn edit-btn">Edit</button>
                <button class="btn delete-btn">Delete</button>
              </td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>

      <!-- Add New Staff Member -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Add New Staff</h3>
        </div>
        <form id="add-staff-form">
          <select id="staff-type" required>
            <option value="">Select Staff Type</option>
            <option value="driver">Driver</option>
            <option value="partner">Driving Partner</option>
          </select>
          <input type="text" placeholder="Employee ID" required />
          <input type="text" placeholder="License Number (for Drivers)" />
          <input type="text" placeholder="Specialization (for Partners)" />
          <button type="submit" class="btn create-btn">Add Staff Member</button>
        </form>
      </div>


    </div>
  </div>

  <!-- Change Log -->
  <div class="stats-tasks-wrapper">
    <div class="stats-container">
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Staff Change Log</h3>
        </div>
        <div class="change-log">
          <div class="log-entry">
            <p><strong>Date:</strong> 2023-09-11 14:30</p>
            <p><strong>Action:</strong> Added new driver</p>
            <p><strong>Details:</strong> Driver ID: D005, Name: Michael Brown</p>
          </div>
          <div class="log-entry">
            <p><strong>Date:</strong> 2023-09-10 09:15</p>
            <p><strong>Action:</strong> Updated driving partner details</p>
            <p><strong>Details:</strong> Partner ID: P003, New specialization: Route Optimization</p>
          </div>
          <!-- Add more log entries as needed -->
        </div>
      </div>
    </div>
  </div>
</main>
<!-- MAIN -->
<!-- MAIN -->
<!-- MAIN -->

<!-- CONTENT -->
<?php require APPROOT . '/views/inc/components/footer.php'; ?>
