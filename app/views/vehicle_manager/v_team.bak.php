<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
        <div class="head-title">
          <div class="left">
            <h1>Teams</h1>
            <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class="bx bx-chevron-right"></i></li>
              <li><a class="active" href="#">Teams</a></li>
            </ul>
          </div>
        </div>

        <div class="stats-tasks-wrapper">
          <div class="stats-container">
            <div class="left-container-2">
              <div class="table-data">
                <div class="collection">
                  <div class="head">
                    <h3>Created Teams</h3>
                  </div>
                  <div class="summary-box">
                    <div class="summary-item">
                      <p>Total Teams</p>
                      <p class="count">21</p>
                    </div>
                    <div class="summary-item">
                      <p>Currently Available</p>
                      <p class="count">13</p>
                    </div>
                    <div class="summary-item">
                      <p>On Duty</p>
                      <p class="count">3</p>
                    </div>
                  </div>
                  <table>
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Team No</th>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Vehicle Used</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>T001</td>
                        <td>Team 1</td>
                        <td>Route A</td>
                        <td>2024-09-10</td>
                        <td>V001</td>
                        <td><span class="status pending">On Duty</span></td>
                      </tr>
                      <tr>
                        <td>T002</td>
                        <td>Team 2</td>
                        <td>Route B</td>
                        <td>2024-09-10</td>
                        <td>V002</td>
                        <td><span class="status completed">Available</span></td>
                      </tr>
                      <tr>
                        <td>T003</td>
                        <td>Team 3</td>
                        <td>Route C</td>
                        <td>2024-09-10</td>
                        <td>V003</td>
                        <td><span class="status completed">Available</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="left-container-2">
              <div class="table-data">
                <div class="collection">
                  <div class="head">
                    <h3>Team History</h3>
                  </div>
                  <table>
                    <thead>
                      <tr>
                        <th>Team ID</th>
                        <th>Shift ID</th>
                        <th>Vehicle ID</th>
                        <th>Route</th>
                        <th>Completed Date Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>T001</td>
                        <td>S001</td>
                        <td>V001</td>
                        <td>Route A</td>
                        <td>2024-09-09 18:30</td>
                      </tr>
                      <tr>
                        <td>T002</td>
                        <td>S002</td>
                        <td>V002</td>
                        <td>Route B</td>
                        <td>2024-09-09 19:15</td>
                      </tr>
                      <tr>
                        <td>T003</td>
                        <td>S003</td>
                        <td>V003</td>
                        <td>Route C</td>
                        <td>2024-09-09 20:00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="stats-tasks-wrapper">
          <div class="stats-container">
            <div class="left-container-2">
              <div class="vehicle-management-box">
                <div class="vehicle-header">
                  <h3>Manage Teams</h3>
                </div>
                <div class="team-management">
                  <div class="create-team">
                    <h4>Create Team</h4>
                    <form id="create-team-form">
                      <input type="text" placeholder="Team Name" required />
                      <input type="text" placeholder="Shift Number" required />
                      <input type="text" placeholder="Route Number" required />
                      <input type="text" placeholder="Driver ID" required />
                      <input type="text" placeholder="Partner ID" required />
                      <button type="submit" class="btn create-btn">
                        Create Team
                      </button>
                    </form>
                  </div>
                  <div class="update-team">
                    <h4>Update Team</h4>
                    <form id="update-team-form">
                      <select id="update-team-select" required>
                        <option value="">Select Team</option>
                        <option value="team1">Team 1</option>
                        <option value="team2">Team 2</option>
                        <option value="team3">Team 3</option>
                      </select>
                      <select id="update-shift-select" required>
                        <option value="">Select Shift</option>
                        <option value="shift1">Shift 1</option>
                        <option value="shift2">Shift 2</option>
                        <option value="shift3">Shift 3</option>
                      </select>
                      <select id="update-route-select" required>
                        <option value="">Select Partner</option>
                        <option value="route1">Route 1</option>
                        <option value="route2">Route 2</option>
                        <option value="route3">Route 3</option>
                      </select>
                      <select id="update-driver-select" required>
                        <option value="">Select Driver</option>
                        <option value="driver1">Driver 1</option>
                        <option value="driver2">Driver 2</option>
                        <option value="driver3">Driver 3</option>
                      </select>
                      <select id="update-partner-select" required>
                        <option value="">Select Partner</option>
                        <option value="partner1">Partner 1</option>
                        <option value="partner2">Partner 2</option>
                        <option value="partner3">Partner 3</option>
                      </select>
                      <button type="submit" class="btn update-btn">
                        Update Team
                      </button>
                    </form>
                  </div>
                  <div class="delete-team">
                    <h4>Delete Team</h4>
                    <form id="delete-team-form">
                      <select id="delete-team-select" required>
                        <option value="">Select Team</option>
                        <option value="team1">Team 1</option>
                        <option value="team2">Team 2</option>
                        <option value="team3">Team 3</option>
                      </select>
                      <button type="submit" class="btn delete-btn">
                        Delete Team
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="left-container-2">
              <div class="vehicle-management-box">
                <div class="vehicle-header">
                  <h3>Team Details</h3>
                  <div class="header-controls">
                    <select class="vehicle-dropdown">
                      <option>Select Team</option>
                      <option>Team 1</option>
                      <option>Team 2</option>
                      <option>Team 3</option>
                    </select>
                  </div>
                </div>
                <div class="vehicle-details">
                  <div class="details-content">
                    <div class="detail-item">
                      <span class="detail-label">Team ID:</span>
                      <span class="detail-value">T001</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Manager Assign ID:</span>
                      <span class="detail-value">M001</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Shift ID:</span>
                      <span class="detail-value">S001</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Vehicle ID:</span>
                      <span class="detail-value">V001</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Status:</span>
                      <span class="detail-value">On Duty</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Assigned Route:</span>
                      <span class="detail-value">Route A</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Driver ID:</span>
                      <span class="detail-value">D001</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Partner ID:</span>
                      <span class="detail-value">P001</span>
                    </div>
                    <div class="detail-item">
                      <span class="detail-label">Current Time:</span>
                      <span class="detail-value">14:30</span>
                    </div>
                  </div>
                </div>
                <div class="team-location">
                  <h4>Team Location</h4>
                  <div class="map-placeholder">
                    <iframe
                      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.81536310695!2d79.81500589657826!3d6.9218368778585315!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2sfr!4v1725986685932!5m2!1sen!2sfr"
                      width="100%"
                      height="100%%"
                      style="border: 0"
                      allowfullscreen=""
                      loading="lazy"
                      referrerpolicy="no-referrer-when-downgrade"
                    >
                    </iframe>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
<!-- MAIN -->
</section>
<!-- CONTENT -->

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
