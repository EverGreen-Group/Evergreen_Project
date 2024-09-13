<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
        <div class="head-title">
          <div class="left">
            <h1>Vehicle Management</h1>
            <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class="bx bx-chevron-right"></i></li>
              <li><a class="active" href="#">Home</a></li>
            </ul>
          </div>
        </div>

        <div class="head-title stats-tasks-wrapper">
          <!-- Stats and Tasks Wrapper -->
          <div class="stats-container">
            <!-- Left Container -->
            <div class="left-container">
              <!-- Top Row of Cards -->
              <div class="top-row">
                <div class="count-box">
                  <div class="text">
                    <h3>Total Vehicles</h3>
                    <span class="count">20</span>
                    <span class="availability">12 Available</span>
                  </div>
                </div>
                <div class="count-box">
                  <div class="text">
                    <h3>Currently Available</h3>
                    <span class="count">30</span>
                    <span class="availability">16 Available</span>
                  </div>
                </div>
                <div class="count-box">
                  <div class="text">
                    <h3>Under Maintainance</h3>
                    <span class="count">15</span>
                    <span class="availability">6 Available</span>
                  </div>
                </div>
              </div>

              <!-- Bottom Row of Cards -->
              <div class="top-row">
                <div class="count-box">
                  <div class="text">
                    <h3>Vehicle Usage</h3>
                    <span class="count">3600 h</span>
                    <span class="availability">6% Increase</span>
                  </div>
                </div>
                <div class="count-box">
                  <div class="text">
                    <h3>Performance</h3>
                    <span class="count">60% In use</span>
                    <span class="availability">16 Available</span>
                  </div>
                </div>
                <div class="count-box">
                  <div class="text">
                    <h3>Maintainance</h3>
                    <span class="count">In 15 days</span>
                    <span class="availability">V1234</span>
                  </div>
                </div>
              </div>

              <div class="head-title stats-tasks-wrapper">
                <!-- Stats and Tasks Wrapper -->
                <div class="stats-container">
                  <!-- Left Container -->
                  <div class="left-container-2">
                    <div class="table-data" style="width: 860px;">
                      <div class="collection"> <!-- Reuse the existing class -->
                        <div class="head">
                          <h3>Factory Vehicles</h3>
                          <!-- Additional icons can be added here if needed -->
                        </div>
                  
                        <table>
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Plate No</th>
                              <th>Make</th>
                              <th>Model</th>
                              <th>Type</th>
                              <th>Capacity</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>V001</td>
                              <td>ABC 1234</td>
                              <td>Toyota</td>
                              <td>Camry</td>
                              <td>Sedan</td>
                              <td>5</td>
                              <td><span class="status pending">Active</span></td>
                            </tr>
                            <tr>
                              <td>V002</td>
                              <td>XYZ 5678</td>
                              <td>Honda</td>
                              <td>Civic</td>
                              <td>Coupe</td>
                              <td>4</td>
                              <td><span class="status delete-btn">Inactive</span></td>
                            </tr>
                            <tr>
                              <td>V003</td>
                              <td>LMN 9101</td>
                              <td>Ford</td>
                              <td>Focus</td>
                              <td>Hatchback</td>
                              <td>5</td>
                              <td><span class="status pending">Active</span></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  
                  
      
                  <!-- Tasks Box on the right side -->
      
      
                </div>
              </div>
            </div>

            <!-- Tasks Box on the right side -->

            <div class="vehicle-management-box">
              <div class="vehicle-header">
                <h3>Manage Vehicles</h3>
                <div class="header-controls">
                  <label for="vehicle-dropdown">Select Vehicle</label>
                  <select id="vehicle-dropdown" class="vehicle-dropdown">
                    <option value="">Select Vehicle</option>
                    <option value="vehicle1">Vehicle 1</option>
                    <option value="vehicle2">Vehicle 2</option>
                    <!-- Add more options as needed -->
                  </select>
                </div>
              </div>
            
              <div class="vehicle-details">
                <p class="details-title">Vehicle Details</p>
                <div class="details-content">
                  <div class="detail-item">
                    <div class="detail-label">Vehicle ID:</div>
                    <div class="detail-value">V1234</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Plate No:</div>
                    <div class="detail-value">ABC 1234</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Make:</div>
                    <div class="detail-value">Toyota</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Model:</div>
                    <div class="detail-value">Camry</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Type:</div>
                    <div class="detail-value">Sedan</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Capacity:</div>
                    <div class="detail-value">5</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Last Maintenance:</div>
                    <div class="detail-value">2024-08-15</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Next Maintenance:</div>
                    <div class="detail-value">2024-09-15</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Mileage:</div>
                    <div class="detail-value">25,000 km</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Fuel:</div>
                    <div class="detail-value">Petrol</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Insurance:</div>
                    <div class="detail-value">Valid till 2025-06-30</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Reg Date:</div>
                    <div class="detail-value">2022-01-01</div>
                  </div>
                </div>
              </div>
            
              <div class="vehicle-actions">
                <button class="btn create-btn">Create</button>
                <button class="btn update-btn">Update</button>
                <button class="btn delete-btn">Delete</button>
              </div>
            </div>

            

        <!-- SECOND ROW -->

 
      </main>
<!-- MAIN -->
</section>
<!-- CONTENT -->

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
