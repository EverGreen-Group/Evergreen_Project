<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
        <div class="head-title">
          <div class="left">
            <h1>Routes</h1>
            <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class="bx bx-chevron-right"></i></li>
              <li><a class="active" href="#">Routes</a></li>
            </ul>
          </div>
        </div>

        <!-- First Row -->
        <div class="stats-tasks-wrapper">
          <div class="stats-container">
            <div class="left-container-2">
              <div class="table-data">
                <div class="collection">
                  <div class="head">
                    <h3>Routes Overview</h3>
                  </div>
                  <div class="summary-box">
                    <div class="summary-item">
                      <p>Total Routes</p>
                      <p class="count">12</p>
                    </div>
                    <div class="summary-item">
                      <p>Currently Available</p>
                      <p class="count">13</p>
                    </div>
                    <div class="summary-item">
                      <p>Unused</p>
                      <p class="count">2</p>
                    </div>
                  </div>
                  <table>
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Route Name</th>
                        <th>Number of Suppliers</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>R001</td>
                        <td>North Route</td>
                        <td>5</td>
                        <td><span class="status completed">Active</span></td>
                      </tr>
                      <tr>
                        <td>R002</td>
                        <td>South Route</td>
                        <td>3</td>
                        <td><span class="status pending">Inactive</span></td>
                      </tr>
                      <tr>
                        <td>R003</td>
                        <td>East Route</td>
                        <td>4</td>
                        <td><span class="status process">In Progress</span></td>
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
                    <h3>Route Logs</h3>
                  </div>
                  <div class="route-logs">
                    <p>
                      [13:12:24] Order #456 cancelled order. Removed user from
                      Route 3.
                    </p>
                    <p>[13:15:30] New supplier added to Route 2.</p>
                    <p>[13:18:45] Route 1 completed all deliveries.</p>
                    <p>[13:20:10] Route 4 started collection process.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Second Row -->
        <div class="stats-tasks-wrapper">
          <div class="stats-container">
            <div class="left-container-2">
              <div class="table-data">
                <div class="collection">
                  <div class="head">
                    <h3>Unassigned Deliveries</h3>
                  </div>
                  <table>
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Supplier ID</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>UD001</td>
                        <td>S003</td>
                        <td>789 Oak St, Village</td>
                        <td>Perishable</td>
                        <td>200 kg</td>
                      </tr>
                      <tr>
                        <td>UD002</td>
                        <td>S004</td>
                        <td>101 Pine St, Suburb</td>
                        <td>Non-perishable</td>
                        <td>450 kg</td>
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
                    <h3>Unallocated Collections</h3>
                  </div>
                  <table>
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Supplier ID</th>
                        <th>Address</th>
                        <th>Average Amount (kg)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>UC001</td>
                        <td>S001</td>
                        <td>123 Main St, City</td>
                        <td>500</td>
                      </tr>
                      <tr>
                        <td>UC002</td>
                        <td>S002</td>
                        <td>456 Elm St, Town</td>
                        <td>350</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Third Row -->
        <div class="stats-tasks-wrapper">
          <div class="stats-container">
            <div class="left-container-2">
              <div class="vehicle-management-box">
                <div class="vehicle-header">
                  <h3>Manage Routes</h3>
                </div>
                <div class="route-management">
                  <div class="create-route">
                    <h4>Create Route</h4>
                    <form id="create-route-form">
                      <input type="text" placeholder="Route Name" required />
                      <input
                        type="text"
                        placeholder="Start Location"
                        required
                      />
                      <input type="text" placeholder="End Location" required />
                      <button type="submit" class="btn create-btn">
                        Create Route
                      </button>
                    </form>
                  </div>
                  <div class="update-route">
                    <h4>Update Route</h4>
                    <form id="update-route-form">
                      <select id="update-route-select" required>
                        <option value="">Select Route</option>
                        <option value="route1">North Route</option>
                        <option value="route2">South Route</option>
                        <option value="route3">East Route</option>
                      </select>
                      <input type="text" placeholder="New Route Name" />
                      <button type="submit" class="btn update-btn">
                        Update Route
                      </button>
                    </form>
                  </div>
                  <div class="delete-route">
                    <h4>Delete Route</h4>
                    <form id="delete-route-form">
                      <select id="delete-route-select" required>
                        <option value="">Select Route</option>
                        <option value="route1">North Route</option>
                        <option value="route2">South Route</option>
                        <option value="route3">East Route</option>
                      </select>
                      <button type="submit" class="btn delete-btn">
                        Delete Route
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="left-container-2">
              <div class="vehicle-management-box">
                <div class="vehicle-header">
                  <h3>Assign Suppliers to Route</h3>
                </div>
                <div class="assign-supplier">
                  <form id="assign-supplier-form">
                    <select id="supplier-select" required>
                      <option value="">Select Supplier</option>
                      <option value="supplier1">Supplier 1</option>
                      <option value="supplier2">Supplier 2</option>
                      <option value="supplier3">Supplier 3</option>
                    </select>
                    <select id="route-select" required>
                      <option value="">Select Route</option>
                      <option value="route1">North Route</option>
                      <option value="route2">South Route</option>
                      <option value="route3">East Route</option>
                    </select>
                    <button type="submit" class="btn create-btn">
                      Add to Route
                    </button>
                    <button type="button" class="btn delete-btn">
                      Remove from Route
                    </button>
                  </form>
                </div>
                <div class="route-map">
                  <h4>Route Map</h4>
                  <div id="map-placeholder" class="map-placeholder">
                    <iframe
                      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.81536310695!2d79.81500589657826!3d6.9218368778585315!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2sfr!4v1725986685932!5m2!1sen!2sfr"
                      width="100%"
                      height="100%"
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
