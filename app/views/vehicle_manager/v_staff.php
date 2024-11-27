<?php

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


  <ul class="route-box-info">
    <li>
        <i class='bx bxs-map'></i>
        <span class="text">
            <p>Total Drivers</p>
            <h3><?php echo isset($data['totalDrivers']->total_drivers) ? $data['totalDrivers']->total_drivers : 0; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-check-circle'></i>
        <span class="text">
            <p>Total Partners</p>
            <h3><?php echo isset($data['totalPartners']->total_partners) ? $data['totalPartners']->total_partners : 0; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-x-circle'></i>
        <span class="text">
            <p>Total Unavailable</p>
            <h3><?php 
                $total = 0;
                if (isset($data['totalUnavailableDriver']->total_drivers_unavailable) && 
                    isset($data['totalUnavailablePartner']->total_partners_unavailable)) {
                    $total = (int)$data['totalUnavailableDriver']->total_drivers_unavailable + 
                            (int)$data['totalUnavailablePartner']->total_partners_unavailable;
                }
                echo $total;
            ?></h3>
        </span>
    </li>
  </ul>


    <!-- Leave Statistics Row -->
    <div class="table-data">
    <div class="chart">
        <div class="head">
            <h3>Monthly Leave Statistics</h3>
        </div>
        <div class="chart-wrapper">
            <canvas id="monthlyLeaveChart"></canvas>
        </div>
    </div>
    <div class="order">
        <div class="head">
            <h3>Upcoming Leaves</h3>
        </div>
        <table id="currentLeavesTable">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['currentLeaves'] as $leave): ?>
                    <tr>
                        <td><?php echo $leave->employee_id; ?></td>
                        <td><?php echo $leave->staff_name; ?></td>
                        <td><?php echo $leave->leave_type; ?></td>
                        <td><?php echo date('d M', strtotime($leave->start_date)); ?></td>
                        <td><?php echo date('d M', strtotime($leave->end_date)); ?></td>
                        <td><span class="status <?php echo $leave->status; ?>"><?php echo $leave->status; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </div>

  <!-- Upcoming Leaves and Leave Types Row -->
  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Pending Leave Requests</h3>
        </div>
        <table id="pendingLeavesTable">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Days</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['pendingLeaves'] as $leave): ?>
                    <tr>
                        <td><?php echo $leave->employee_id; ?></td>
                        <td><?php echo $leave->staff_name; ?></td>
                        <td><?php echo $leave->leave_type; ?></td>
                        <td><?php echo $leave->total_days; ?></td>
                        <td><?php echo date('d M', strtotime($leave->start_date)); ?></td>
                        <td><?php echo date('d M', strtotime($leave->end_date)); ?></td>
                        <td>
                            <button class="btn-approve" onclick="updateLeaveStatus(<?php echo $leave->id; ?>, 'approved', <?php echo $data['manager_id']; ?>)">
                                <i class='bx bx-check'></i>
                            </button>
                            <button class="btn-reject" onclick="updateLeaveStatus(<?php echo $leave->id; ?>, 'rejected', <?php echo $data['user_id']; ?>)">
                                <i class='bx bx-x'></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="chart">
        <div class="head">
            <h3>Leave Types Distribution</h3>
        </div>
        <div class="chart-wrapper">
            <canvas id="leaveTypesChart"></canvas>
        </div>
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
            <th>Employee ID</th>
            <th>License No</th>
            <th>Status</th>
            <th>Experience Years</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['drivers'] as $driver): ?>
            <tr>
              <td><?php echo $driver->driver_id; ?></td>
              <td><?php echo $driver->employee_id; ?></td>
              <td><?php echo $driver->license_no; ?></td>
              <td><?php echo $driver->status; ?></td>
              <td><?php echo $driver->experience_years; ?></td>
              <td>
                <button class="btn-delete" onclick="removeStaff(<?php echo $driver->driver_id; ?>, 'driver')">
                  <i class='bx bx-trash'></i>
                </button>
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
            <th>Employee ID</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['partners'] as $partner): ?>
            <tr>
              <td><?php echo $partner->partner_id; ?></td>
              <td><?php echo $partner->employee_id; ?></td>
              <td><?php echo $partner->status; ?></td>
              <td>
                <button class="btn-delete" onclick="removeStaff(<?php echo $partner->partner_id; ?>, 'partner')">
                  <i class='bx bx-trash'></i>
                </button>
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
            <th>Employee ID</th>
            <th>Manager Type</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['managers'] as $manager): ?>
            <tr>
              <td><?php echo $manager->manager_id; ?></td>
              <td><?php echo $manager->employee_id; ?></td>
              <td><?php echo $manager->manager_type; ?></td>
              <td><?php echo $manager->status; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>




</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Leave Chart
    const monthlyCtx = document.getElementById('monthlyLeaveChart');
    if (monthlyCtx) {
        console.log('Monthly chart canvas found');
        const monthlyData = <?php echo json_encode($data['monthlyLeaveStats'] ?? []); ?>;
        console.log('Monthly data:', monthlyData);
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const chartData = Array(12).fill(0);
        
        if (monthlyData) {
            monthlyData.forEach(item => {
                chartData[item.month - 1] = parseInt(item.count);
            });
        }
        
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Number of Leaves',
                    data: chartData,
                    backgroundColor: '#36A2EB',
                    borderColor: '#2196F3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Monthly Leave Distribution'
                    }
                }
            }
        });
    } else {
        console.error('Monthly chart canvas not found');
    }

    // Leave Types Chart
    const leaveTypesCtx = document.getElementById('leaveTypesChart');
    if (leaveTypesCtx) {
        console.log('Leave types chart canvas found');
        const labels = <?php echo json_encode(array_column($data['leaveTypeStats'] ?? [], 'name')); ?>;
        const chartData = <?php echo json_encode(array_column($data['leaveTypeStats'] ?? [], 'count')); ?>;
        
        new Chart(leaveTypesCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: chartData,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Leave Types Distribution'
                    }
                }
            }
        });
    } else {
        console.error('Leave types chart canvas not found');
    }
});
</script>

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

  .btn-approve, .btn-reject {
    padding: 6px 12px;
    margin: 0 4px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .btn-approve {
    background-color: #4CAF50;
    color: white;
  }

  .btn-reject {
    background-color: #f44336;
    color: white;
  }

  .status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
  }

  .status.pending {
    background-color: #ffd700;
    color: #000;
  }

  .status.approved {
    background-color: #4CAF50;
    color: white;
  }

  .status.rejected {
    background-color: #f44336;
    color: white;
  }

  .chart {
    flex: 1;
    padding: 20px;
    background: var(--light);
    border-radius: 20px;
    min-height: 380px;
  }

  #monthlyLeaveChart,
  #leaveTypesChart {
    width: 100% !important;
    height: 300px !important;
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
      // AJAX call to soft delete staff
      fetch('<?php echo URLROOT; ?>/vehiclemanager/remove_staff', {
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

  function updateLeaveStatus(requestId, status, managerId) {
    const action = status === 'approved' ? 'approve' : 'reject';
    if (confirm(`Are you sure you want to ${action} this leave request?`)) {
        console.log('Sending request:', {
            requestId: parseInt(requestId),
            status,
            vehicle_manager_id: parseInt(managerId)
        });

        fetch('<?php echo URLROOT; ?>/vehiclemanager/update_leave_status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                requestId: parseInt(requestId),
                status,
                vehicle_manager_id: parseInt(managerId)
            }),
        })
        .then(async response => {
            const text = await response.text();
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error(`Invalid JSON response: ${text}`);
            }
        })
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                showNotification(`Leave request ${action}d successfully`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error: ' + error.message, 'error');
        });
    }
}
</script>

<style>
    .route-box-info {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    margin-top: 36px;
    list-style: none;
    padding: 0;
  }

  .route-box-info li {
    flex: 1;
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 24px;
  }

  .route-box-info li i {
    font-size: 36px;
    color: var(--main);
    background: var(--light-main);
    border-radius: 10%;
    padding: 16px;
  }

  .route-box-info li .text h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
  }

  .route-box-info li .text p {
    font-size: 14px;
    color: var(--dark-grey);
    margin: 0;
  }
</style>

<style>
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

@media screen and (max-width: 768px) {
    .chart-row {
        flex-direction: column;
    }

    .chart-container {
        max-width: 100%;
    }
}
</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>