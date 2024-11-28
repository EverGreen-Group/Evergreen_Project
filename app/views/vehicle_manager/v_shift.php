<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>



  <!-- Shift Management Section -->
  <div class="head-title">
    <div class="left">
      <h1>Shift Management</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li>Shift Management</li>
      </ul>
    </div>
  </div>

  <ul class="shift-box-info">
    <li>
      <i class='bx bxs-calendar'></i>
      <span class="text">
        <p>Total Shifts</p>
        <h3 id="totalShifts"><?php echo $data['totalShifts']; ?></h3>
      </span>
    </li>
    <li>
      <i class='bx bxs-group'></i>
      <span class="text">
        <p>Total Teams</p>
        <h3 id="totalTeams"><?php echo $data['totalTeamsInCollection']; ?></h3>
      </span>
    </li>
  </ul>

  <!-- Then your shift-management-row div continues below -->
  <div class="shift-management-row">
    <!-- Create Shift Form -->
    <div class="shift-form-container">
        <h2>Create New Shift</h2>
        <?php flash('shift_success'); ?>
        <?php flash('shift_error'); ?>
        <form action="<?php echo URLROOT; ?>/vehiclemanager/shift" method="POST" class="create-shift-form">
            <div class="form-group">
                <label for="shift_name">Shift Name</label>
                <input type="text" id="shift_name" name="shift_name" required 
                       placeholder="e.g., Morning Shift">
            </div>
            
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" id="start_time" name="start_time" required>
            </div>
            
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" id="end_time" name="end_time" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Shift</button>
        </form>
    </div>

    <!-- Shifts Table -->
    <div class="shifts-table-container">
        <h2>Current Shifts</h2>
        <table class="shifts-table">
            <thead>
                <tr>
                    <th>Shift Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($data['shifts']) && !empty($data['shifts'])): ?>
                    <?php foreach($data['shifts'] as $shift): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($shift->shift_name); ?></td>
                            <td><?php echo date('h:i A', strtotime($shift->start_time)); ?></td>
                            <td><?php echo date('h:i A', strtotime($shift->end_time)); ?></td>
                            <td>
                                <?php 
                                    $start = strtotime($shift->start_time);
                                    $end = strtotime($shift->end_time);
                                    $duration = round(($end - $start) / 3600, 1);
                                    echo $duration . ' hours';
                                ?>
                            </td>
                            <td>
                                <button class="btn-edit" onclick="editShift(<?php echo $shift->shift_id; ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-delete" onclick="deleteShift(<?php echo $shift->shift_id; ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">No shifts available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


  </div>
      <!-- After the shifts-table-container div -->
      <div class="weekly-schedule-container">
        <h2>Upcoming Schedule</h2>
        <div class="schedule-table-wrapper">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Shift Time</th>
                        <?php 
                        // Generate next 7 days
                        for ($i = 0; $i < 7; $i++) {
                            $date = date('D, M j', strtotime("+$i days"));
                            echo "<th>$date</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['shifts'] as $shift): ?>
                        <tr>
                            <td class="shift-time">
                                <?php 
                                echo htmlspecialchars($shift->shift_name) . '<br>';
                                echo date('h:i A', strtotime($shift->start_time)) . ' - ' . 
                                     date('h:i A', strtotime($shift->end_time));
                                ?>
                            </td>
                            <?php 
                            // For each day, show the collections scheduled for this shift
                            for ($i = 0; $i < 7; $i++) {
                                $date = date('Y-m-d', strtotime("+$i days"));
                                $dayOfWeek = strtolower(date('D', strtotime($date))); // Get the day of the week (e.g., "mon", "tue")
                                echo "<td class='schedule-cell'>";
                                
                                // Check if there are collection schedules for this shift
                                if (isset($data['schedules'][$shift->shift_id])) {
                                    foreach ($data['schedules'][$shift->shift_id] as $scheduleDate => $schedules) {
                                        foreach ($schedules as $schedule) {
                                            // Check if the current day is in the days_of_week for this schedule
                                            $daysOfWeek = explode(',', strtolower($schedule->days_of_week)); // Split the days_of_week string into an array
                                            if (in_array($dayOfWeek, $daysOfWeek)) {
                                                echo "<div class='collection-item'>";
                                                if (isset($schedule->team_name) && !empty($schedule->team_name)) {
                                                    echo "<div class='team-info'>";
                                                    echo "<strong>Team:</strong> " . htmlspecialchars($schedule->team_name) . "<br>";
                                                    if (isset($schedule->driver_name) || isset($schedule->partner_name)) {
                                                        echo "<div class='team-members'>";
                                                        if (isset($schedule->driver_name)) {
                                                            echo "<div class='member'>Driver: " . htmlspecialchars($schedule->driver_name) . "</div>";
                                                        }
                                                        if (isset($schedule->partner_name)) {
                                                            echo "<div class='member'>Partner: " . htmlspecialchars($schedule->partner_name) . "</div>";
                                                        }
                                                        echo "</div>";
                                                    }
                                                    echo "</div>";
                                                } else {
                                                    echo "<div class='no-team'>No team assigned</div>";
                                                }
                                                echo "<strong>Route:</strong> " . htmlspecialchars($schedule->route_name) . "<br>";
                                                echo "<strong>Vehicle:</strong> " . htmlspecialchars($schedule->license_plate);
                                                echo "</div>";
                                            }
                                        }
                                    }
                                } else {
                                    echo "<div class='no-schedule'>No collections scheduled</div>";
                                }
                                
                                echo "</td>";
                            }
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<!--style section -->
<style>
.swap-requests-section,
.shift-log-section {
  margin-top: 40px;
  background-color: #f5f5f5;
  padding: 20px;
  border-radius: 10px;
}

.swap-request,
.log-entry {
  background-color: white;
  padding: 15px;
  margin-bottom: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.swap-request button {
  margin-right: 10px;
}

.log-entry small {
  color: #888;
  display: block;
  margin-top: 5px;
}
</style>

<style>
  .shift-box-info {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    margin-top: 36px;
    list-style: none;
    padding: 0;
  }

  .shift-box-info li {
    flex: 1;
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 24px;
  }

  .shift-box-info li i {
    font-size: 36px;
    color: var(--main);
    background: var(--light-main);
    border-radius: 10%;
    padding: 16px;
  }

  .shift-box-info li .text h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
  }

  .shift-box-info li .text p {
    font-size: 14px;
    color: var(--dark-grey);
    margin: 0;
  }

  .shifts-section {
    margin-top: 40px;
  }

  .create-shift-btn, .create-team-btn {
    padding: 10px 20px;
    background-color: var(--main);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
  }

  .create-shift-btn:hover, .create-team-btn:hover {
    background-color: var(--main-dark);
  }

  .shifts-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
    overflow-x: auto;
  }

  .day-column {
    min-width: 200px;
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
  }

  .shift-card {
    background: white;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s ease;
  }

  .shift-card:hover {
    transform: translateY(-5px);
  }

  .team-cards-section {
    margin-top: 40px;
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
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
    width: calc(33.33% - 83.33px);
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
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 5px;
    gap: 5px;
  }

  .team-card-info {
    margin-left: 15px;
  }

  .team-card-info h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
  }

  .team-card-info p {
    font-size: 14px;
    color: var(--dark-grey);
    margin: 5px 0 0;
  }

  /* Modal Styling */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
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

  #createShiftForm, #createTeamForm {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  #createShiftForm input, #createShiftForm select, #createTeamForm input {
    padding: 8px;
    border: 1px solid var(--dark-grey);
    border-radius: 5px;
  }

  .team-images {
    display: flex;
    gap: 10px;
    margin-right: 15px;
  }

  .team-images img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 50%;
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
  }

  .btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .btn-primary {
    background-color: var(--main);
    color: white;
  }

  .btn-danger {
    background-color: #e74c3c;
    color: white;
  }

  .btn:hover {
    opacity: 0.9;
  }

  @media screen and (max-width: 768px) {
    .team-card {
      width: 100%;
    }

    .modal-content {
      width: 90%;
    }
  }
</style>

<style>
.shift-management-row {
    display: flex;
    gap: 24px;
    margin-top: 36px;
}

.shift-form-container,
.shifts-table-container {
    flex: 1;
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
}

.create-shift-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group label {
    font-weight: 600;
    color: var(--dark);
}

.form-group input,
.form-group select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.shifts-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.shifts-table th,
.shifts-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.shifts-table th {
    font-weight: 600;
    color: var(--dark);
    background-color: var(--light-main);
}

.shifts-table tr:hover {
    background-color: var(--light-main);
}

.btn-edit,
.btn-delete {
    padding: 6px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 5px;
}

.btn-edit {
    background-color: var(--main);
    color: white;
}

.btn-delete {
    background-color: #e74c3c;
    color: white;
}

.btn-edit:hover,
.btn-delete:hover {
    opacity: 0.8;
}

.no-data {
    text-align: center;
    color: var(--dark-grey);
    padding: 20px;
}

h2 {
    color: var(--dark);
    margin-bottom: 20px;
}

@media screen and (max-width: 768px) {
    .shift-management-row {
        flex-direction: column;
    }
}
</style>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
</style>

<style>
.weekly-schedule-container {
    margin-top: 36px;
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
    overflow-x: auto;
}

.schedule-table-wrapper {
    overflow-x: auto;
}

.schedule-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px; /* Ensure table doesn't get too narrow */
}

.schedule-table th,
.schedule-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

.schedule-table th {
    background-color: var(--light-main);
    color: var(--dark);
    font-weight: 600;
}

.shift-time {
    background-color: var(--light-main);
    font-weight: 600;
    min-width: 150px;
}

.schedule-cell {
    vertical-align: top;
    min-height: 100px;
}

.collection-item {
    background-color: var(--light-main);
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 8px;
    font-size: 0.9em;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.team-info {
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding-bottom: 8px;
    margin-bottom: 8px;
}

.team-members {
    margin-top: 4px;
    padding-left: 12px;
    font-size: 0.9em;
    color: var(--dark-grey);
}

.member {
    margin: 2px 0;
    padding: 2px 0;
}

.no-team {
    color: #999;
    font-style: italic;
    margin-bottom: 8px;
    padding: 4px 0;
}
</style>

<style>
.shift-form-container {
    background: var(--light);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn-primary {
    background: var(--main);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary:hover {
    background: var(--main-dark);
}

/* Flash message styles */
.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<script>
function editShift(shiftId) {
    const url = `<?php echo URLROOT; ?>/vehiclemanager/getShift/${shiftId}`;
    console.log('Fetching shift details from:', url); // Log the URL
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text(); // Get the response as text first
        })
        .then(text => {
            console.log('Response text:', text); // Log the raw response text
            return JSON.parse(text); // Manually parse the JSON
        })
        .then(shift => {
            if (shift.error) {
                alert(shift.error);
                return;
            }
            
            document.getElementById('shift_name').value = shift.shift_name;
            document.getElementById('start_time').value = shift.start_time.slice(0, 5);
            document.getElementById('end_time').value = shift.end_time.slice(0, 5);
            
            const form = document.querySelector('.create-shift-form');
            form.action = `<?php echo URLROOT; ?>/vehiclemanager/updateShift/${shiftId}`;
            
            document.querySelector('.create-shift-form button').textContent = 'Update Shift';
            
            if (!document.querySelector('.btn-cancel')) {
                const cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.className = 'btn btn-danger btn-cancel';
                cancelBtn.textContent = 'Cancel';
                cancelBtn.onclick = resetForm;
                form.appendChild(cancelBtn);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to fetch shift details: ' + error.message);
        });
}

function deleteShift(shiftId) {
    if (confirm('Are you sure you want to delete this shift?')) {
        fetch(`<?php echo URLROOT; ?>/vehiclemanager/deleteShift/${shiftId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'Failed to delete shift');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete shift');
        });
    }
}

function resetForm() {
    const form = document.querySelector('.create-shift-form');
    form.reset();
    form.action = `<?php echo URLROOT; ?>/vehiclemanager/shift`;
    document.querySelector('.create-shift-form button').textContent = 'Create Shift';
    const cancelBtn = document.querySelector('.btn-cancel');
    if (cancelBtn) cancelBtn.remove();
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>