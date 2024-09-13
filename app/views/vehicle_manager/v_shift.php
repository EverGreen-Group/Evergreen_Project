<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<!-- MAIN -->
<main>
  <div class="head-title">
    <div class="left">
      <h1>Shifts</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li><i class="bx bx-chevron-right"></i></li>
        <li><a class="active" href="#">Shifts</a></li>
      </ul>
    </div>
  </div>

  <div class="stats-tasks-wrapper">
    <div class="stats-container">
      <!-- Weekly Shift Schedule -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Weekly Shift Schedule</h3>
        </div>
        <div class="shift-schedule">
          <div class="day-column">
            <h4>Monday</h4>
            <div class="shift-bar">Morning (5 teams)</div>
            <div class="shift-bar">Afternoon (3 teams)</div>
            <div class="shift-bar">Night (2 teams)</div>
          </div>
          <div class="day-column">
            <h4>Tuesday</h4>
            <div class="shift-bar">Morning (4 teams)</div>
            <div class="shift-bar">Afternoon (4 teams)</div>
            <div class="shift-bar">Night (2 teams)</div>
          </div>
          <div class="day-column">
            <h4>Wednesday</h4>
            <div class="shift-bar">Morning (4 teams)</div>
            <div class="shift-bar">Afternoon (4 teams)</div>
            <div class="shift-bar">Night (2 teams)</div>
          </div>
          <!-- Add columns for Wednesday through Sunday similarly -->
        </div>
        <div class="shift-schedule">
          <div class="day-column">
            <h4>Thursday</h4>

            <div class="shift-bar">Night (2 teams)</div>
          </div>
          <div class="day-column">
            <h4>Friday</h4>
            <div class="shift-bar">Morning (4 teams)</div>
            <div class="shift-bar">Afternoon (4 teams)</div>
          </div>
          <div class="day-column">
            <h4>Saturday</h4>
            <div class="shift-bar">Morning (4 teams)</div>
            <div class="shift-bar">Afternoon (4 teams)</div>
          </div>
          <!-- Add columns for Wednesday through Sunday similarly -->
        </div>
      </div>

      <!-- Shift Swap Requests -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Shift Swap Requests</h3>
        </div>
        <div class="request-list">
          <div class="request-item">
            <p><strong>Driver ID:</strong> D001</p>
            <p><strong>Current Shift:</strong> Monday Morning</p>
            <p><strong>Preferred Shift:</strong> Tuesday Afternoon</p>
            <p><strong>Swap With:</strong> D005</p>
            <button class="btn approve-btn">Approve</button>
            <button class="btn reject-btn">Reject</button>
          </div>
          <div class="request-item">
            <p><strong>Partner ID:</strong> P003</p>
            <p><strong>Current Shift:</strong> Wednesday Night</p>
            <p><strong>Preferred Shift:</strong> Thursday Morning</p>
            <p><strong>Swap With:</strong> P007</p>
            <button class="btn approve-btn">Approve</button>
            <button class="btn reject-btn">Reject</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="stats-tasks-wrapper">
    <div class="stats-container">
      <!-- Manage Shifts -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Manage Shifts</h3>
        </div>
        <div class="team-management">
          <div class="create-team">
            <h4>Create Shift</h4>
            <form id="create-shift-form">
              <input type="text" placeholder="Shift Name" required />
              <input type="date" placeholder="Date" required />
              <input type="time" placeholder="Start Time" required />
              <input type="time" placeholder="End Time" required />
              <button type="submit" class="btn create-btn">Create Shift</button>
            </form>
          </div>
          <div class="update-team">
            <h4>Assign Personnel to Shift</h4>
            <form id="assign-personnel-form">
              <select id="shift-select" required>
                <option value="">Select Shift</option>
                <option value="shift1">Monday Morning</option>
                <option value="shift2">Monday Afternoon</option>
                <option value="shift3">Monday Night</option>
              </select>
              <select id="personnel-type" required>
                <option value="">Select Type</option>
                <option value="driver">Driver</option>
                <option value="partner">Partner</option>
              </select>
              <select id="personnel-select" required>
                <option value="">Select Personnel</option>
                <option value="D009">D009 - John Doe</option>
                <option value="P011">P011 - Alice Johnson</option>
              </select>
              <button type="submit" class="btn update-btn">Assign to Shift</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Manage Routes -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Manage Routes</h3>
        </div>
        <div class="team-management">
          <div class="create-team">
            <h4>Assign Route to Shift</h4>
            <form id="assign-route-form">
              <select id="shift-select" required>
                <option value="">Select Shift</option>
                <option value="shift1">Monday Morning</option>
                <option value="shift2">Monday Afternoon</option>
                <option value="shift3">Monday Night</option>
              </select>
              <select id="route-select" required>
                <option value="">Select Route</option>
                <option value="route1">Route A</option>
                <option value="route2">Route B</option>
                <option value="route3">Route C</option>
              </select>
              <button type="submit" class="btn create-btn">Assign Route</button>
            </form>
          </div>
          <div class="update-team">
            <h4>Update Route Assignment</h4>
            <form id="update-route-form">
              <select id="shift-select" required>
                <option value="">Select Shift</option>
                <option value="shift1">Monday Morning</option>
                <option value="shift2">Monday Afternoon</option>
                <option value="shift3">Monday Night</option>
              </select>
              <select id="new-route-select" required>
                <option value="">Select New Route</option>
                <option value="route1">Route A</option>
                <option value="route2">Route B</option>
                <option value="route3">Route C</option>
              </select>
              <button type="submit" class="btn update-btn">Update Route</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- New section for unassigned personnel -->
  <div class="stats-tasks-wrapper">
    <div class="stats-container">
      <!-- Unassigned Drivers -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Unassigned Drivers</h3>
        </div>
        <table class="unassigned-table">
          <thead>
            <tr>
              <th>Driver ID</th>
              <th>Name</th>
              <th>Preferred Shift</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>D002</td>
              <td>Jane Smith</td>
              <td>Morning</td>
              <td><button class="btn assign-btn">Assign</button></td>
            </tr>
            <tr>
              <td>D007</td>
              <td>Mike Johnson</td>
              <td>Afternoon</td>
              <td><button class="btn assign-btn">Assign</button></td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>

      <!-- Unassigned Driving Partners -->
      <div class="vehicle-management-box">
        <div class="vehicle-header">
          <h3>Unassigned Driving Partners</h3>
        </div>
        <table class="unassigned-table">
          <thead>
            <tr>
              <th>Partner ID</th>
              <th>Name</th>
              <th>Preferred Shift</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>P004</td>
              <td>Sarah Brown</td>
              <td>Night</td>
              <td><button class="btn assign-btn">Assign</button></td>
            </tr>
            <tr>
              <td>P009</td>
              <td>Tom Davis</td>
              <td>Morning</td>
              <td><button class="btn assign-btn">Assign</button></td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<!-- MAIN -->
<!-- MAIN -->

<!-- CONTENT -->
<?php require APPROOT . '/views/inc/components/footer.php'; ?>
