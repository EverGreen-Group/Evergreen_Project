<?php
// Dummy data with updated fields
$dummy_data = [
    'drivers' => [
        (object)['id' => 1, 'name' => 'John Doe', 'license_no' => 'DL12345', 'registered_date' => '2023-01-15', 'shift_id' => 'S001', 'team_id' => 'T001'],
        (object)['id' => 2, 'name' => 'Jane Smith', 'license_no' => 'DL67890', 'registered_date' => '2023-02-20', 'shift_id' => null, 'team_id' => null],
    ],
    'partners' => [
        (object)['id' => 3, 'name' => 'Bob Johnson', 'registered_date' => '2023-03-10', 'shift_id' => 'S002', 'team_id' => 'T002'],
        (object)['id' => 4, 'name' => 'Alice Brown', 'registered_date' => '2023-04-05', 'shift_id' => null, 'team_id' => null],
    ],
    'managers' => [
        (object)['id' => 5, 'name' => 'Charlie Wilson', 'teams_managed' => 3],
        (object)['id' => 6, 'name' => 'Diana Clark', 'teams_managed' => 2],
    ],
    'unassigned' => [
        (object)['id' => 7, 'name' => 'Eva Green', 'license_no' => 'DL54321'],
        (object)['id' => 8, 'name' => 'Frank White', 'license_no' => null],
    ],
    'reports' => [
        (object)['id' => 1, 'staff_id' => 1, 'staff_name' => 'John Doe', 'report_date' => '2023-09-10', 'issue' => 'Vehicle Maintenance', 'description' => 'Brake pads need replacement', 'image' => 'https://www.team-bhp.com/sites/default/files/styles/check_extra_large_for_review/public/rr310-brake-pad-replacement-5.jpg'],
        (object)['id' => 2, 'staff_id' => 3, 'staff_name' => 'Bob Johnson', 'report_date' => '2023-09-12', 'issue' => 'Route Obstruction', 'description' => 'Road closure on Main St', 'image' => 'https://www.newsnow.lk/wp-content/uploads/2024/05/Screenshot_2024-05-22-08-15-48-64_99c04817c0de5652397fc8b56c3b3817.jpg'],
    ],
];

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>
<!-- MAIN -->
<main>
  <!-- Vehicle Management Staff Section -->
  <div class="head-title">
    <div class="left">
      <h1>Staff Management</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li>Staff Management</li>
      </ul>
    </div>
  </div>
  
  <!-- Vehicle Drivers Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Vehicle Drivers</h3>
        <i class='bx bx-search'></i>
      </div>
      <table id="driversTable">
        <thead>
          <tr>
            <th>Driver ID</th>
            <th>Name</th>
            <th>License No</th>
            <th>Registered Date</th>
            <th>Shift ID</th>
            <th>Team ID</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dummy_data['drivers'] as $driver): ?>
            <tr>
              <td><?php echo $driver->id; ?></td>
              <td><?php echo $driver->name; ?></td>
              <td><?php echo $driver->license_no; ?></td>
              <td><?php echo $driver->registered_date; ?></td>
              <td><?php echo $driver->shift_id ?? 'N/A'; ?></td>
              <td><?php echo $driver->team_id ?? 'N/A'; ?></td>
              <td>
                <button class="btn-delete" onclick="removeStaff(<?php echo $driver->id; ?>, 'driver')">Remove</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Driving Partners Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Driving Partners</h3>
        <i class='bx bx-search'></i>
      </div>
      <table id="partnersTable">
        <thead>
          <tr>
            <th>Partner ID</th>
            <th>Name</th>
            <th>Registered Date</th>
            <th>Shift ID</th>
            <th>Team ID</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dummy_data['partners'] as $partner): ?>
            <tr>
              <td><?php echo $partner->id; ?></td>
              <td><?php echo $partner->name; ?></td>
              <td><?php echo $partner->registered_date; ?></td>
              <td><?php echo $partner->shift_id ?? 'N/A'; ?></td>
              <td><?php echo $partner->team_id ?? 'N/A'; ?></td>
              <td>
                <button class="btn-delete" onclick="removeStaff(<?php echo $partner->id; ?>, 'partner')">Remove</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Vehicle Managers Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Vehicle Managers</h3>
        <i class='bx bx-search'></i>
      </div>
      <table id="managersTable">
        <thead>
          <tr>
            <th>Manager ID</th>
            <th>Name</th>
            <th>No. of Teams Managed</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dummy_data['managers'] as $manager): ?>
            <tr>
              <td><?php echo $manager->id; ?></td>
              <td><?php echo $manager->name; ?></td>
              <td><?php echo $manager->teams_managed; ?></td>
              <td>
                <button class="btn-delete" onclick="removeStaff(<?php echo $manager->id; ?>, 'manager')">Remove</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Unassigned Staff Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Unassigned Staff</h3>
        <i class='bx bx-search'></i>
      </div>
      <table id="unassignedTable">
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>License No</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dummy_data['unassigned'] as $staff): ?>
            <tr>
              <td><?php echo $staff->id; ?></td>
              <td><?php echo $staff->name; ?></td>
              <td><?php echo $staff->license_no ?? 'N/A'; ?></td>
              <td>
                <button class="btn-assign" onclick="openAssignModal(<?php echo $staff->id; ?>)">Assign Role</button>
                <button class="btn-delete" onclick="removeStaff(<?php echo $staff->id; ?>, 'unassigned')">Remove</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Staff Reports Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Staff Reports</h3>
        <i class='bx bx-search'></i>
      </div>
      <table id="reportsTable">
        <thead>
          <tr>
            <th>Report ID</th>
            <th>Staff Name</th>
            <th>Report Date</th>
            <th>Issue</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dummy_data['reports'] as $report): ?>
            <tr>
              <td><?php echo $report->id; ?></td>
              <td><?php echo $report->staff_name; ?></td>
              <td><?php echo $report->report_date; ?></td>
              <td><?php echo $report->issue; ?></td>
              <td>
                <button class="btn-view" onclick="openReportModal(<?php echo htmlspecialchars(json_encode($report)); ?>)">View Details</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal for assigning roles -->
  <div id="assignModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('assignModal')">&times;</span>
      <h2>Assign Role</h2>
      <form id="assignForm">
        <input type="hidden" id="staffId" name="staffId">
        <div class="form-group">
          <label for="roleSelect">Role:</label>
          <select id="roleSelect" name="role" required>
            <option value="driver">Vehicle Driver</option>
            <option value="partner">Driving Partner</option>
            <option value="manager">Vehicle Manager</option>
          </select>
        </div>
        <button type="submit">Assign Role</button>
      </form>
    </div>
  </div>

  <!-- Modal for viewing report details -->
  <div id="reportModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('reportModal')">&times;</span>
      <h2>Report Details</h2>
      <div id="reportDetails"></div>
    </div>
  </div>
</main>

<style>
  .table-data .order table {
    width: 100%;
    border-collapse: collapse;
  }

  .table-data .order table th,
  .table-data .order table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  .table-data .order table th {
    background-color: #f2f2f2;
    font-weight: bold;
  }

  .btn-delete,
  .btn-assign,
  .btn-view {
    padding: 6px 12px;
    margin: 0 4px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
  }

  .btn-delete {
    background-color: #f44336;
    color: white;
  }

  .btn-assign {
    background-color: #2196F3;
    color: white;
  }

  .btn-view {
    background-color: #4CAF50;
    color: white;
  }

  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
  }

  .modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
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
    text-decoration: none;
    cursor: pointer;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
  }

  .form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }

  .notification.show {
    opacity: 1;
  }

  .notification.success {
    background-color: #4CAF50;
  }

  .notification.error {
    background-color: #f44336;
  }

  .table-search {
    margin-bottom: 10px;
    padding: 5px;
    width: 100%;
    box-sizing: border-box;
  }

  #reportDetails img {
    max-width: 100%;
    height: auto;
    margin-top: 10px;
  }
</style>

<script>
  // Function to filter tables
  function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
      let showRow = false;
      const cells = rows[i].getElementsByTagName("td");
      for (let j = 0; j < cells.length; j++) {
        const cell = cells[j];
        if (cell) {
          const textValue = cell.textContent || cell.innerText;
          if (textValue.toUpperCase().indexOf(filter) > -1) {
            showRow = true;
            break;
          }
        }
      }
      rows[i].style.display = showRow ? "" : "none";
    }
  }

  // Function to initialize search inputs for each table
  function initializeTableSearch() {
    const tables = [
      { id: "driversTable", placeholder: "Search drivers..." },
      { id: "partnersTable", placeholder: "Search partners..." },
      { id: "managersTable", placeholder: "Search managers..." },
      { id: "unassignedTable", placeholder: "Search unassigned staff..." },
      { id: "reportsTable", placeholder: "Search reports..." }
    ];

    tables.forEach(table => {
      const tableElement = document.getElementById(table.id);
      const searchInput = document.createElement("input");
      searchInput.type = "text";
      searchInput.id = `${table.id}Search`;
      searchInput.placeholder = table.placeholder;
      searchInput.className = "table-search";
      searchInput.addEventListener("keyup", () => filterTable(`${table.id}Search`, table.id));
      tableElement.parentNode.insertBefore(searchInput, tableElement);
    });
  }

  // Initialize table search when the DOM is fully loaded
  document.addEventListener("DOMContentLoaded", initializeTableSearch);

  // Function to open the assign role modal
  function openAssignModal(staffId) {
    document.getElementById("staffId").value = staffId;
    document.getElementById("assignModal").style.display = "block";
  }

  // Function to open the report details modal
  function openReportModal(report) {
    const reportDetails = document.getElementById("reportDetails");
    reportDetails.innerHTML = `
      <p><strong>Staff Name:</strong> ${report.staff_name}</p>
      <p><strong>Report Date:</strong> ${report.report_date}</p>
      <p><strong>Issue:</strong> ${report.issue}</p>
      <p><strong>Description:</strong> ${report.description}</p>
      <img src="${report.image}" alt="Report Image">
    `;
    document.getElementById("reportModal").style.display = "block";
  }

  // Function to close modals
  function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
  }

  // Function to remove staff
  function removeStaff(staffId, role) {
    if (confirm("Are you sure you want to remove this staff member?")) {
      // AJAX call to remove staff
      fetch('<?php echo URLROOT; ?>/vehicle_managers/remove_staff', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ staffId, role }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('Staff member removed successfully', 'success');
          location.reload(); // Refresh the page to show updated data
        } else {
          showNotification('Error removing staff: ' + data.message, 'error');
        }
      })
      .catch((error) => {
        console.error('Error:', error);
        showNotification('An error occurred while removing the staff member.', 'error');
      });
    }
  }

  // Function to show notifications
  function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.classList.add('show');
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }, 100);
  }

  // Add event listener for the assign role form
  document.getElementById("assignForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const staffData = Object.fromEntries(formData.entries());

    // AJAX call to assign role
    fetch('<?php echo URLROOT; ?>/vehicle_managers/assign_role', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(staffData),
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification('Role assigned successfully', 'success');
        closeModal('assignModal');
        location.reload(); // Refresh the page to show updated data
      } else {
        showNotification('Error assigning role: ' + data.message, 'error');
      }
    })
    .catch((error) => {
      console.error('Error:', error);
      showNotification('An error occurred while assigning the role.', 'error');
    });
  });

  // Close modals when clicking outside
  window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
      event.target.style.display = "none";
    }
  }
</script>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>