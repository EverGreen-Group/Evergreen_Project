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
          <h1>Team Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <ul class="team-box-info">
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
          <p>Total Teams</p>
          <h3><?php echo $data['teamStats']['total']; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-check'></i>
        <span class="text">
          <p>On Duty</p>
          <h3><?php echo $data['teamStats']['active']; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-x'></i>
        <span class="text">
          <p>Unassigned Teams</p>
          <h3><?php echo $data['teamStats']['inactive']; ?></h3>
        </span>
    </li>
  </ul>


  <div class="team-cards-section">
    <h2>All Teams</h2>
    <div class="team-cards-container" id="teamCardsContainer">
        <?php foreach ($data['teams'] as $team): ?>
            <a href="javascript:void(0);" onclick="showTeamDetails(<?php echo htmlspecialchars(json_encode($team)); ?>)" class="team-card" style="display: flex; margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; border-radius: 8px; text-decoration: none; color: inherit; position: relative;">
                <div class="delete-overlay">
                    <button onclick="event.stopPropagation(); deleteTeam(<?php echo $team->team_id; ?>)" class="delete-btn">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
                
                <div class="team-icons">
                    <img src="<?php echo $team->driver_image_url ?: 'https://randomuser.me/api/portraits/men/1.jpg'; ?>" alt="Driver Icon" title="Driver">
                    <img src="<?php echo $team->partner_image_url ?: 'https://randomuser.me/api/portraits/women/2.jpg'; ?>" alt="Partner Icon" title="Partner">
                </div>
                <div class="team-card-info">
                    <h3><?php echo htmlspecialchars($team->team_name); ?></h3>
                    <p>Status: <span class="<?php echo strtolower($team->status); ?>"><?php echo $team->status; ?></span></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
  </div>

  <!-- Expandable Team Details Section -->
  <div id="teamDetailsSection" class="team-details" style="display: none;">
      <div class="header">
          <h2>Team Details</h2>
          <div class="close-button" onclick="closeTeamDetails()">✖ Close</div>
      </div>
      <div class="team-info">
          <div class="info-row images-row">
              <div class="image-container">
                  <h3>Driver</h3>
                  <img id="detailDriverImage" src="" alt="Driver Image" class="team-image">
              </div>
              <div class="image-container">
                  <h3>Driving Partner</h3>
                  <img id="detailPartnerImage" src="" alt="Partner Image" class="team-image">
              </div>
          </div>
          <div class="info-row">
              <div class="info-label">Team ID:</div>
              <div class="info-value" id="detailTeamId"></div>
          </div>
          <div class="info-row">
              <div class="info-label">Team Name:</div>
              <div class="info-value" id="detailTeamName"></div>
          </div>
          <div class="info-row">
              <div class="info-label">Driver:</div>
              <div class="info-value" id="detailDriver"></div>
          </div>
          <div class="info-row">
              <div class="info-label">Partner:</div>
              <div class="info-value" id="detailPartner"></div>
          </div>
          <div class="info-row">
              <div class="info-label">Status:</div>
              <div class="info-value" id="detailStatus"></div>
          </div>
          <div class="info-row">
              <div class="info-label">Number of Collections:</div>
              <div class="info-value" id="detailNumberOfCollections"></div>
          </div>
          <div class="info-row">
              <div class="info-label">Total Quantity Collected:</div>
              <div class="info-value" id="detailTotalQuantityCollected"></div>
          </div>
      </div>
  </div>


  <!-- Unassigned Drivers and Partners in same row -->
  <div class="table-data table-container">
      <!-- Left table: Unassigned Drivers -->
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
                          <td><?php echo htmlspecialchars($driver->contact_number ?? 'N/A'); ?></td>
                          <td>
                              <span class="status completed">Available</span>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>

      <!-- Right table: Unassigned Partners -->
      <div class="order">
          <div class="head">
              <h3>Unassigned Driving Partners</h3>
          </div>
          <table>
              <thead>
                  <tr>
                      <th>Partner ID</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($data['unassigned_partners'] as $partner): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($partner->partner_id); ?></td>
                          <td><?php echo htmlspecialchars($partner->partner_name); ?></td>
                          <td><?php echo htmlspecialchars($partner->contact_number ?? 'N/A'); ?></td>
                          <td>
                              <span class="status completed">Available</span>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  </div>

  <!-- Team Forms Section -->
  <div class="table-data">
      <div class="order" style="display: flex; gap: 20px;">
          <!-- Create Team Form -->
          <div style="flex: 1;">
              <div class="head">
                  <h3>Create New Team</h3>
              </div>
              <form id="createTeamForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/createTeam">
                  <div style="display: flex; gap: 20px;">
                      <div class="form-group" style="flex: 1;">
                          <label for="team_name">Team Name:</label>
                          <input type="text" id="team_name" name="team_name" required>
                      </div>
                      <div class="form-group" style="flex: 1;">
                          <label for="status">Status:</label>
                          <select id="status" name="status" required>
                              <option value="Active">Active</option>
                              <option value="Inactive">Inactive</option>
                          </select>
                      </div>
                  </div>

                  <div style="display: flex; gap: 20px;">
                      <div class="form-group" style="flex: 1;">
                          <label for="driver_id">Driver:</label>
                          <select id="driver_id" name="driver_id">
                              <option value="">None</option>
                              <?php foreach ($data['unassigned_drivers'] as $driver): ?>
                                  <option value="<?php echo $driver->driver_id; ?>">
                                      <?php echo htmlspecialchars($driver->driver_name); ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                      <div class="form-group" style="flex: 1;">
                          <label for="partner_id">Driving Partner:</label>
                          <select id="partner_id" name="partner_id">
                              <option value="">None</option>
                              <?php foreach ($data['unassigned_partners'] as $partner): ?>
                                  <option value="<?php echo $partner->partner_id; ?>">
                                      <?php echo htmlspecialchars($partner->partner_name); ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                  </div>

                  <button type="submit" class="btn-submit">Create Team</button>
              </form>
          </div>

          <!-- Vertical Separator -->
          <div class="vertical-separator"></div>

          <!-- Update Team Form -->
          <div style="flex: 1;">
              <div class="head">
                  <h3>Update Team</h3>
              </div>
              <form id="updateTeamForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/updateTeam">
                  <div style="display: flex; gap: 20px;">
                      <div class="form-group" style="flex: 1;">
                          <label for="edit_team_select">Select Team:</label>
                          <select id="edit_team_select" name="team_id" required onchange="loadTeamData(this.value)">
                              <option value="">Select a team</option>
                              <?php foreach ($data['teams'] as $team): ?>
                                  <option value="<?php echo $team->team_id; ?>">
                                      <?php echo htmlspecialchars($team->team_name); ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                      <div class="form-group" style="flex: 1;">
                          <label for="edit_team_name">Team Name:</label>
                          <input type="text" id="edit_team_name" name="team_name" required>
                      </div>
                  </div>

                  <div style="display: flex; gap: 20px;">
                      <div class="form-group" style="flex: 1;">
                          <label for="edit_driver_id">Driver:</label>
                          <select id="edit_driver_id" name="driver_id">
                              <option value="">None</option>
                              <?php 
                              // First, show the current driver of the selected team if exists
                              foreach ($data['teams'] as $team): 
                                  if (!empty($team->driver_id)): ?>
                                      <option value="<?php echo $team->driver_id; ?>" 
                                              data-team-id="<?php echo $team->team_id; ?>">
                                          <?php echo htmlspecialchars($team->driver_full_name); ?>
                                      </option>
                                  <?php endif;
                              endforeach; 
                              
                              // Then show unassigned drivers
                              foreach ($data['unassigned_drivers'] as $driver): ?>
                                  <option value="<?php echo $driver->driver_id; ?>">
                                      <?php echo htmlspecialchars($driver->driver_name); ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                      <div class="form-group" style="flex: 1;">
                          <label for="edit_partner_id">Driving Partner:</label>
                          <select id="edit_partner_id" name="partner_id">
                              <option value="">None</option>
                              <?php 
                              // First, show the current partner of the selected team if exists
                              foreach ($data['teams'] as $team): 
                                  if (!empty($team->partner_id)): ?>
                                      <option value="<?php echo $team->partner_id; ?>"
                                              data-team-id="<?php echo $team->team_id; ?>">
                                          <?php echo htmlspecialchars($team->partner_full_name); ?>
                                      </option>
                                  <?php endif;
                              endforeach; 
                              
                              // Then show unassigned partners
                              foreach ($data['unassigned_partners'] as $partner): ?>
                                  <option value="<?php echo $partner->partner_id; ?>">
                                      <?php echo htmlspecialchars($partner->partner_name); ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                  </div>

                  <div style="display: flex; gap: 20px;">
                      <div class="form-group" style="flex: 1;">
                          <label for="edit_status">Status:</label>
                          <select id="edit_status" name="status" required>
                              <option value="Active">Active</option>
                              <option value="Inactive">Inactive</option>
                          </select>
                      </div>
                      <div class="form-group" style="flex: 1;">
                          <!-- Empty div for alignment -->
                      </div>
                  </div>

                  <button type="submit" class="btn-submit">Update Team</button>
              </form>
          </div>
      </div>
  </div>

  <style>
    /* Button Styles */
    .create-team-btn {
      padding: 10px 20px;
      background-color: #007664;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
    }

    .create-team-btn:hover {
      background-color: #007664;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover,
    .close:focus {
      color: black;
    }

    .submit-btn {
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #218838;
    }

    form label {
      display: block;
      margin: 15px 0 5px;
    }

    form input {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

  </style>

  <!-- Internal CSS -->
  <style>
    .team-box-info {
      display: flex;
      justify-content: space-between;
      gap: 24px;
      margin-top: 36px;
      list-style: none;
      padding: 0;
    }

    .team-box-info li {
      flex: 1;
      background: var(--light);
      border-radius: 20px;
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 40px;
      position: relative;
    }

    .team-box-info li i {
      font-size: 48px;
      color: var(--main);
      background: var(--light-main);
      border-radius: 10%;
      padding: 20px;
    }

    .team-box-info li .text {
      display: flex;
      flex-direction: column;
    }

    .team-box-info li .text h3 {
      font-size: 32px;
      font-weight: 600;
      color: var(--dark);
    }

    .team-box-info li .text p {
      font-size: 20px;
      color: var(--dark);
    }

    .team-box-info li .text small {
      font-size: 16px;
      color: var(--dark-grey);
    }

    .team-icons {
      display: flex;
      gap: 15px; /* Adds space between images */
      margin-bottom: 10px; /* Adds space between images and text */
    }

    .team-card-info {
      padding-top: 10px;
    }


    .chart-row {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 40px;
    }

    .chart-container {
      flex: 1;
      max-width: calc(50% - 10px);
      padding: 20px;
      background: var(--light);
      border-radius: 20px;
      text-align: center;
      box-sizing: border-box;
    }

    .chart-wrapper {
      height: 300px;
      width: 100%;
    }

    /* .team-cards-section {
      margin-top: 40px;
      background-color: #f5f5f5;
      padding: 20px;
      border-radius: 10px;
    } */

    .team-cards-section {
      margin-top: 40px;
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      color: var(--dark);
    }

    .team-cards-section h2 {
      margin-right: auto;
      font-size: 24px;
      font-weight: 600;
    }

    .team-cards-section p {
      margin-right: auto;
      font-weight: 600;
      margin: 8px 0;
    }

    .team-cards-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }

    .team-card {
      background: var(--light);
      border-radius: 10px;
      padding: 15px;
      width: calc(33.33% - 123.33px);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s ease;
      display: flex;
      align-items: center;
    }

    .team-card:hover {
      transform: translateY(-5px);
    }

    .team-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
      gap: 5px;
      margin-right: 10px;
    }

    .team-card-info {
      flex-grow: 1;
    }

    .team-card h3 {
      margin-top: 0;
      color: var(--dark);
    }

    .team-card p {
      margin: 5px 0;
      color: var(--dark-grey);
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      overflow-y: auto;
    }

    .modal-content {
      background-color: var(--light);
      margin: 5% auto;
      padding: 20px;
      border-radius: 10px;
      width: 80%;
      max-width: 600px;
    }

    .close {
      color: var(--dark-grey);
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: var(--dark);
    }

    /* Form styles */
    #teamForm {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    .form-group {
      width: calc(50% - 7.5px);
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: var(--dark);
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--dark-grey);
      border-radius: 5px;
      font-size: 14px;
    }

    /* Button styles */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }

    .btn-primary {
      background-color: var(--main);
      color: var(--light);
    }

    .btn-primary:hover {
      background-color: var(--main-dark);
    }

    .btn-danger {
      background-color: #dc3545;
      color: var(--light);
    }

    .btn-danger:hover {
      background-color: #c82333;
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }

    .add-team-btn {
      margin-top: 20px;
    }

    /* Responsive design */
    @media screen and (max-width: 1024px) {
      .team-card {
        width: calc(50% - 10px);
      }
    }

    @media screen and (max-width: 768px) {
      .team-card {
        width: 100%;
      }

      .modal-content {
        width: 90%;
      }

      .form-group {
        width: 100%;
      }
    }

    /* Add this new style for the team details modal */
    .team-member-images {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
    }

    .team-member-images div {
      text-align: center;
    }

    .team-member-images img {
      width: 220px;
      height: 220px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 10px;
    }
  </style>



<style>
  .unassigned-section {
      margin-top: 40px;
      background-color: #f9f9f9; /* Match the background color of team cards */
      padding: 20px;
      border-radius: 10px;
    }

    .unassigned-cards-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }

    .unassigned-card {
      background: var(--light); /* Match the background color */
      border-radius: 10px;
      padding: 15px;
      width: calc(33.33% - 100px); /* Adjust for margin or padding */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      display: flex; /* Use flexbox for layout */
      align-items: center; /* Center items vertically */
      cursor: pointer; /* Change cursor to pointer */
    }

    .unassigned-card:hover {
      transform: translateY(-5px); /* Add hover effect */
    }

    .unassigned-card img {
      width: 80px; /* Set image size */
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
      margin-right: 10px; /* Space between image and text */
    }

    .team-card-info {
      display: flex; /* Use flexbox for text layout */
      flex-direction: column; /* Stack text vertically */
    }

    @media screen and (max-width: 1024px) {
      .unassigned-card {
        width: calc(33.33% - 20px); /* Adjust width for medium screens */
      }
    }

    @media screen and (max-width: 768px) {
      .unassigned-card {
        width: 100%; /* Full width for smaller screens */
      }
    }

  .close {
    color: var(--dark-grey);
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover {
    color: var(--dark);
  }

  .status.active {
    background: #86e49d;
    color: #006b21;
  }

  .status.inactive {
      background: #d893a3;
      color: #b30021;
  }

  @media screen and (max-width: 1024px) {
    .unassigned-card {
      width: calc(33.33% - 13.33px);
    }
  }

  @media screen and (max-width: 768px) {
    .unassigned-card {
      width: calc(50% - 10px);
    }
  }
</style>




<!-- JavaScript -->
<script>
function showTeamDetails(team) {
    console.log("Team details:", team); // Log the team object to check if it's being passed correctly

    // Populate the details section with team information
    document.getElementById('detailTeamId').innerText = team.team_id;
    document.getElementById('detailTeamName').innerText = team.team_name;
    document.getElementById('detailDriver').innerText = team.driver_full_name + ' (' + team.driver_id + ')';
    document.getElementById('detailPartner').innerText = team.partner_full_name + ' (' + team.partner_id + ')';
    document.getElementById('detailStatus').innerText = team.status;

    // Populate new fields
    document.getElementById('detailNumberOfCollections').innerText = team.number_of_collections || 'N/A'; // Assuming this data is available
    document.getElementById('detailTotalQuantityCollected').innerText = team.total_quantity_collected || '0.00'; // Assuming this data is available

    // Populate images
    document.getElementById('detailDriverImage').src = team.driver_image_url || 'https://randomuser.me/api/portraits/men/1.jpg';
    document.getElementById('detailPartnerImage').src = team.partner_image_url || 'https://randomuser.me/api/portraits/women/2.jpg';

    // Show the details section
    document.getElementById('teamDetailsSection').style.display = 'block';
}

function closeTeamDetails() {
    document.getElementById('teamDetailsSection').style.display = 'none';
}

function loadTeamData(teamId) {
    const driverSelect = document.getElementById('edit_driver_id');
    const partnerSelect = document.getElementById('edit_partner_id');
    const teamNameInput = document.getElementById('edit_team_name');
    const statusSelect = document.getElementById('edit_status');

    // Reset all fields if no team is selected
    if (!teamId) {
        teamNameInput.value = '';
        statusSelect.value = 'Active';
        driverSelect.value = ''; // Reset to "None"
        partnerSelect.value = ''; // Reset to "None"
        return;
    }
    
    const team = <?php echo json_encode($data['teams']); ?>.find(t => t.team_id == teamId);
    if (team) {
        // Set basic team info
        teamNameInput.value = team.team_name;
        statusSelect.value = team.status;

        // Reset both dropdowns to "None" first
        driverSelect.value = '';
        partnerSelect.value = '';

        // Set driver if exists
        if (team.driver_id) {
            const driverOption = Array.from(driverSelect.options).find(
                option => option.value === team.driver_id.toString() && 
                option.getAttribute('data-team-id') === team.team_id.toString()
            );
            if (driverOption) {
                driverOption.selected = true;
            }
        }

        // Set partner if exists
        if (team.partner_id) {
            const partnerOption = Array.from(partnerSelect.options).find(
                option => option.value === team.partner_id.toString() && 
                option.getAttribute('data-team-id') === team.team_id.toString()
            );
            if (partnerOption) {
                partnerOption.selected = true;
            }
        }
    }
}

// Call loadTeamData with no arguments when the page loads to set initial state
document.addEventListener('DOMContentLoaded', () => loadTeamData());

function removeTeamMember(type) {
    const teamId = document.getElementById('edit_team_select').value;
    if (!teamId) {
        alert('Please select a team first');
        return;
    }

    if (confirm(`Are you sure you want to remove the ${type} from this team?`)) {
        const selectElement = document.getElementById(`edit_${type}_id`);
        selectElement.value = ''; // Reset the selection
        
        // You can add an AJAX call here to update the database immediately
        // fetch(`<?php echo URLROOT; ?>/vehiclemanager/removeTeamMember/${teamId}/${type}`, {
        //     method: 'POST'
        // }).then(response => response.json())
        //   .then(data => {
        //     if (data.success) {
        //         alert(`${type} has been removed from the team`);
        //     }
        // });
    }
}

function deleteTeam(teamId) {
    if (confirm('Are you sure you want to delete this team?')) {
        fetch(`<?php echo URLROOT; ?>/vehiclemanager/deleteTeam/${teamId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fixed selector to find the team card
                const teamCard = document.querySelector(`.team-card[onclick*="team_id\\\":${teamId}"]`);
                if (teamCard) {
                    teamCard.remove();
                    // Optionally refresh the page to update stats
                    window.location.reload();
                }
            } else {
                alert('Failed to delete team');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the team');
        });
    }
}
</script>

<style>

.team-details {
    margin-top: 20px;
    padding: 20px;
    border-radius: 8px;
    background-color: #f9f9f9; /* White background for contrast */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.team-details h2 {
      margin-right: auto;
      font-size: 24px;
      font-weight: 600;
    }

.team-details h3 {
    margin-bottom: 15px;
    color: #333; /* Dark color for the heading */
    font-size: 24px; /* Larger font size for the heading */
}

.team-info {
    display: flex;
    flex-direction: column; /* Stack items vertically */
    gap: 10px; /* Space between rows */
}

.images-row {
    display: flex;
    justify-content: space-between; /* Space the image containers evenly */
    align-items: center; /* Center items vertically */
    gap: 5px; /* Optional: small gap between images */
}

.image-container {
    display: flex;
    flex-direction: column; /* Stack label and image vertically */
    align-items: center; /* Center items */
    flex: 1; /* Allow containers to grow equally */
}

.team-image {
    border-radius: 5px;
    width: 320px; /* Increased width for images */
    height: 320px; /* Increased height for images */
    object-fit: cover; /* Maintain aspect ratio and cover the area */
    border: 1px solid #ddd; /* Optional: border around images */
}

.info-row {
    display: flex;
    justify-content: space-between; /* Space between label and value */
    padding: 10px;
    border: 1px solid #ddd; /* Border around each row */
    border-radius: 5px; /* Rounded corners */
    background-color: #f9f9f9; /* Light background for rows */
}

.info-label {
    
    color: #333; /* Darker text for better readability */
}

.info-value {
    color: #555; /* Slightly lighter color for values */
}

.header {
    display: flex; /* Use flexbox for header layout */
    justify-content: space-between; /* Space between title and close button */
    align-items: center; /* Center items vertically */
    margin-bottom: 15px; /* Space below the header */
}

.close-button {
    cursor: pointer; /* Change cursor to pointer on hover */
    color: #342e37; /* Red color for visibility */
    font-size: 14px; /* Font size for the close button */
    padding: 5px 10px; /* Padding for the button */
    border: 1px solid #342e37; /* Optional: border around the button */
    border-radius: 5px; /* Rounded corners for the button */
    background-color: #ffffff; /* White background for the button */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.expand-button {
    background-color: #007bff; /* Bootstrap primary color */
    color: white; /* Text color */
    border: none; /* Remove border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px 15px; /* Padding for the button */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s; /* Smooth transition */
}

.expand-button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

</style>


<style>
    /* Existing styles... */

    /* New styles for vehicle cards section */
    .vehicle-cards-section {
      margin-top: 40px;
      background-color: #f5f5f5;
      padding: 20px;
      border-radius: 10px;
    }

    .vehicle-cards-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }

    .vehicle-card {
      background: var(--light);
      border-radius: 10px;
      padding: 15px;
      width: calc(33.33% - 83.33px);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s ease;
      display: flex;
      align-items: center;
    }

    .vehicle-card:hover {
      transform: translateY(-5px);
    }

    .vehicle-card img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 5px;
      margin-right: 15px;
    }

    .vehicle-card-info {
      flex-grow: 1;
    }

    .vehicle-card h3 {
      margin-top: 0;
      color: var(--dark);
    }

    .vehicle-card p {
      margin: 5px 0;
      color: var(--dark-grey);
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      overflow-y: auto;
    }

    .modal-content {
      background-color: var(--light);
      margin: 5% auto;
      padding: 20px;
      border-radius: 10px;
      width: 80%;
      max-width: 600px;
    }

    .close {
      color: var(--dark-grey);
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: var(--dark);
    }

    /* Form styles */
    #vehicleForm {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    .form-group {
      width: calc(50% - 7.5px);
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: var(--dark);
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--dark-grey);
      border-radius: 5px;
      font-size: 14px;
    }

    /* Button styles */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }

    .btn-primary {
      background-color: var(--main);
      color: var(--light);
    }

    .btn-primary:hover {
      background-color: var(--main-dark);
    }

    .btn-danger {
      background-color: #dc3545;
      color: var(--light);
    }

    .btn-danger:hover {
      background-color: #c82333;
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }

    .add-vehicle-btn {
      margin-top: 20px;
    }

    /* Responsive design */
    @media screen and (max-width: 1024px) {
      .vehicle-card {
        width: calc(50% - 10px);
      }
    }

    @media screen and (max-width: 768px) {
      .vehicle-card {
        width: 100%;
      }

      .modal-content {
        width: 90%;
      }

      .form-group {
        width: 100%;
      }
    }
  </style>

<style>
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn-submit {
    background-color: #4154f1;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    margin-top: 10px;
}

.btn-submit:hover {
    background-color: #364fd4;
}

.head {
    margin-bottom: 20px;
}

.head h3 {
    color: #2c3345;
    font-size: 18px;
    font-weight: 600;
}

.vertical-separator {
    width: 1px;
    background: #e0e0e0;
    margin: 0 20px;
    box-shadow: 1px 0 2px rgba(0,0,0,0.05);
}

#createVehicleForm, #editVehicleForm {
    padding-right: 20px;
    padding-left: 20px;
}
</style>

<style>
.team-card {
    overflow: hidden; /* Ensure overlay doesn't extend beyond card */
}

.delete-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.team-card:hover .delete-overlay {
    opacity: 1;
}

.delete-btn {
    background-color: #ff4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
}

.delete-btn:hover {
    background-color: #cc0000;
}

.delete-btn i {
    font-size: 18px;
}
</style>



</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
