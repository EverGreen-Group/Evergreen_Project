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
      <h1>Weekly Shift Schedule Management</h1>
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
        <h3 id="totalShifts">35</h3>
      </span>
    </li>
    <li>
      <i class='bx bxs-group'></i>
      <span class="text">
        <p>Total Teams</p>
        <h3 id="totalTeams">8</h3>
      </span>
    </li>
  </ul>

  <div class="shifts-section">
    <h2>Weekly Schedule</h2>
    <button class="create-shift-btn" onclick="openCreateShiftModal()">Create New Shift</button>

    <div class="shifts-container" id="shiftsContainer">
      <!-- Shifts will be dynamically added here -->
    </div>
  </div>

  <div class="team-cards-section">
    <h2>All Teams</h2>
    <button id="createTeamButton" class="create-team-btn">Create Team</button>
    <div class="team-cards-container" id="teamCardsContainer">
      <!-- Team cards will be dynamically added here -->
    </div>
  </div>

  <div class="swap-requests-section">
    <h2>Shift Swap Requests</h2>
    <div id="swapRequestsContainer"></div>
  </div>

  <div class="shift-log-section">
    <h2>Shift Change Log</h2>
    <div id="shiftLogContainer"></div>
  </div>
  
</main>

<!-- Modal for creating a new shift -->
<div id="createShiftModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('createShiftModal')">&times;</span>
    <h2>Create New Shift</h2>
    <form id="createShiftForm">
      <input type="text" name="name" placeholder="Shift Name" required>
      <select name="day" required>
        <option value="">Select Day</option>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Saturday">Saturday</option>
        <option value="Sunday">Sunday</option>
      </select>
      <input type="time" name="start_time" required>
      <input type="time" name="end_time" required>
      <button type="submit" class="btn btn-primary">Create Shift</button>
    </form>
  </div>
</div>

<!-- Modal for shift details and assignment -->
<div id="shiftDetailsModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('shiftDetailsModal')">&times;</span>
    <h2>Shift Details</h2>
    <div id="shiftDetails"></div>
    <h3>Assign Team</h3>
    <select id="teamSelect">
      <option value="">Select a team</option>
      <!-- Team options will be dynamically added here -->
    </select>
    <button onclick="assignTeamToShift()" class="btn btn-primary">Assign Team</button>
    <button onclick="removeTeamFromShift()" class="btn btn-danger">Remove Team</button>
    <button onclick="editShiftTime()" class="btn btn-primary">Edit Shift Time</button>
    <button onclick="deleteShift()" class="btn btn-danger">Delete Shift</button>
  </div>
</div>

<!-- Modal for team details -->
<div id="teamDetailsModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('teamDetailsModal')">&times;</span>
    <h2>Team Details</h2>
    <div id="teamDetails"></div>
  </div>
</div>

<!-- Modal for creating a team -->
<div id="createTeamModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('createTeamModal')">&times;</span>
    <h2>Create New Team</h2>
    <form id="createTeamForm">
      <div class="form-group">
        <label for="teamName">Team Name</label>
        <input type="text" id="teamName" name="teamName" required>
      </div>
      <div class="form-group">
        <label for="teamDriver">Driver Name</label>
        <input type="text" id="teamDriver" name="teamDriver" required>
      </div>
      <div class="form-group">
        <label for="teamPartner">Driving Partner Name</label>
        <input type="text" id="teamPartner" name="teamPartner" required>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn btn-primary">Save Team</button>
        <button type="button" class="btn btn-danger" onclick="closeModal('createTeamModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>



<script>
  // Hardcoded team data for demonstration
  let teams = [
    { id: 1, name: 'Team A', driver: 'John Doe', partner: 'Alice Smith' },
    { id: 2, name: 'Team B', driver: 'Jane Smith', partner: 'Bob Johnson' },
    { id: 3, name: 'Team C', driver: 'Mike Johnson', partner: 'Eve Brown' },
  ];

  // Hardcoded shift data for demonstration
  let shifts = [
    { id: 1, name: 'Morning Shift', day: 'Monday', start_time: '08:00', end_time: '16:00', team_name: 'Team A' },
    { id: 2, name: 'Evening Shift', day: 'Monday', start_time: '16:00', end_time: '00:00', team_name: null },
    { id: 3, name: 'Night Shift', day: 'Tuesday', start_time: '00:00', end_time: '08:00', team_name: 'Team B' },
  ];

  // Function to render team cards
  function renderTeamCards() {
    const container = document.getElementById('teamCardsContainer');
    container.innerHTML = '';
    teams.forEach(team => {
      const card = document.createElement('div');
      card.className = 'team-card';
      card.innerHTML = `
        <div class="team-images">
          <img src="/api/placeholder/120/120" alt="${team.driver}">
          <img src="/api/placeholder/120/120" alt="${team.partner}">
        </div>
        <div class="team-card-info">
          <h3>${team.name}</h3>
          <p>Driver: ${team.driver}</p>
          <p>Partner: ${team.partner}</p>
        </div>
      `;
      card.onclick = () => openTeamDetailsModal(team.id);
      container.appendChild(card);
    });
    document.getElementById('totalTeams').textContent = teams.length;
  }

  // Function to render shifts
  function renderShifts() {
    const container = document.getElementById('shiftsContainer');
    container.innerHTML = '';
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    days.forEach(day => {
      const dayColumn = document.createElement('div');
      dayColumn.className = 'day-column';
      dayColumn.innerHTML = `<h3>${day}</h3>`;
      const dayShifts = shifts.filter(shift => shift.day === day);
      dayShifts.forEach(shift => {
        const shiftCard = document.createElement('div');
        shiftCard.className = 'shift-card';
        shiftCard.innerHTML = `
          <h4>${shift.name}</h4>
          <p>Time: ${shift.start_time} - ${shift.end_time}</p>
          <p>Team: ${shift.team_name || 'Unassigned'}</p>
        `;
        shiftCard.onclick = () => openShiftDetailsModal(shift.id);
        dayColumn.appendChild(shiftCard);
      });
      container.appendChild(dayColumn);
    });
    document.getElementById('totalShifts').textContent = shifts.length;
  }

  // Call these functions when the page loads
  renderTeamCards();
  renderShifts();

  function openCreateShiftModal() {
    document.getElementById('createShiftModal').style.display = 'block';
  }

  function openShiftDetailsModal(shiftId) {
    const shift = shifts.find(s => s.id === shiftId);
    if (shift) {
      document.getElementById('shiftDetails').innerHTML = `
        <p>Name: ${shift.name}</p>
        <p>Day: ${shift.day}</p>
        <p>Time: ${shift.start_time} - ${shift.end_time}</p>
        <p>Team: ${shift.team_name || 'Unassigned'}</p>
      `;
      document.getElementById('shiftDetailsModal').style.display = 'block';
    }
  }

  function assignTeamToShift() {
    const teamSelect = document.getElementById('teamSelect');
    const selectedTeam = teamSelect.options[teamSelect.selectedIndex].text;
    alert(`Assigned ${selectedTeam} to the shift!`);
    closeModal('shiftDetailsModal');
    renderShifts();
  }

  function removeTeamFromShift() {
    alert('Team removed from the shift!');
    closeModal('shiftDetailsModal');
    renderShifts();
  }

  function editShiftTime() {
    const newStartTime = prompt('Enter new start time (HH:MM):', '09:00');
    const newEndTime = prompt('Enter new end time (HH:MM):', '17:00');
    if (newStartTime && newEndTime) {
      alert(`Shift time updated to ${newStartTime} - ${newEndTime}`);
      closeModal('shiftDetailsModal');
      renderShifts();
    }
  }

  function deleteShift() {
    if (confirm('Are you sure you want to delete this shift?')) {
      alert('Shift deleted successfully!');
      closeModal('shiftDetailsModal');
      renderShifts();
    }
  }

  function openTeamDetailsModal(teamId) {
    const team = teams.find(t => t.id === teamId);
    if (team) {
      document.getElementById('teamDetails').innerHTML = `
        <h3>${team.name}</h3>
        <p>Driver: ${team.driver}</p>
        <p>Driving Partner: ${team.partner}</p>
      `;
      document.getElementById('teamDetailsModal').style.display = 'block';
    }
  }

  document.getElementById('createTeamButton').addEventListener('click', function() {
    document.getElementById('createTeamModal').style.display = 'block';
  });

  document.getElementById('createTeamForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const teamName = document.getElementById('teamName').value;
    const teamDriver = document.getElementById('teamDriver').value;
    const teamPartner = document.getElementById('teamPartner').value;
    
    // In a real application, you would save this data to your backend
    const newTeam = {
      id: teams.length + 1,
      name: teamName,
      driver: teamDriver,
      partner: teamPartner
    };
    teams.push(newTeam);
    alert(`Team "${teamName}" created successfully!`);
    closeModal('createTeamModal');
    renderTeamCards();
  });

  document.getElementById('createShiftForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const shiftName = this.elements['name'].value;
    const shiftDay = this.elements['day'].value;
    const startTime = this.elements['start_time'].value;
    const endTime = this.elements['end_time'].value;
    
    const newShift = {
      id: shifts.length + 1,
      name: shiftName,
      day: shiftDay,
      start_time: startTime,
      end_time: endTime,
      team_name: null
    };
    shifts.push(newShift);
    alert('New shift created successfully!');
    closeModal('createShiftModal');
    renderShifts();
  });

  function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
  }

  // Close modals when clicking outside
  window.onclick = function(event) {
    if (event.target.className === 'modal') {
      event.target.style.display = 'none';
    }
  }
</script>

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

<script>
// Simulated data for swap requests
let swapRequests = [
  { id: 1, requesterName: 'John Doe', requesterTeam: 'Team A', requestedTeam: 'Team B', shiftDate: '2024-09-20', status: 'pending' },
  { id: 2, requesterName: 'Jane Smith', requesterTeam: 'Team C', requestedTeam: 'Team A', shiftDate: '2024-09-22', status: 'pending' }
];

// Simulated data for shift log
let shiftLog = [
  { id: 1, action: 'Shift Created', details: 'Morning Shift created for Monday', timestamp: '2024-09-15 09:00:00' },
  { id: 2, action: 'Team Assigned', details: 'Team A assigned to Morning Shift on Monday', timestamp: '2024-09-15 10:30:00' }
];

function renderSwapRequests() {
  const container = document.getElementById('swapRequestsContainer');
  container.innerHTML = '';
  swapRequests.forEach(request => {
    const requestElement = document.createElement('div');
    requestElement.className = 'swap-request';
    requestElement.innerHTML = `
      <p><strong>${request.requesterName}</strong> from ${request.requesterTeam} requests to swap with ${request.requestedTeam} for shift on ${request.shiftDate}</p>
      <button onclick="handleSwapRequest(${request.id}, 'accept')" class="btn btn-primary">Accept</button>
      <button onclick="handleSwapRequest(${request.id}, 'deny')" class="btn btn-danger">Deny</button>
    `;
    container.appendChild(requestElement);
  });
}

function handleSwapRequest(requestId, action) {
  const request = swapRequests.find(r => r.id === requestId);
  if (request) {
    request.status = action === 'accept' ? 'accepted' : 'denied';
    alert(`Swap request ${action}`);
    renderSwapRequests();
    addToShiftLog(`Swap Request ${action.charAt(0).toUpperCase() + action.slice(1)}ed`, 
                  `${request.requesterName}'s request to swap with ${request.requestedTeam} for ${request.shiftDate} was ${action}ed`);
  }
}

function renderShiftLog() {
  const container = document.getElementById('shiftLogContainer');
  container.innerHTML = '';
  shiftLog.forEach(log => {
    const logElement = document.createElement('div');
    logElement.className = 'log-entry';
    logElement.innerHTML = `
      <p><strong>${log.action}</strong>: ${log.details}</p>
      <small>${log.timestamp}</small>
    `;
    container.appendChild(logElement);
  });
}

function addToShiftLog(action, details) {
  const newLog = {
    id: shiftLog.length + 1,
    action: action,
    details: details,
    timestamp: new Date().toISOString().replace('T', ' ').substr(0, 19)
  };
  shiftLog.unshift(newLog);
  renderShiftLog();
}

// Call these functions to render swap requests and shift log
renderSwapRequests();
renderShiftLog();

// Modify existing functions to add logs
function assignTeamToShift() {
  const teamSelect = document.getElementById('teamSelect');
  const selectedTeam = teamSelect.options[teamSelect.selectedIndex].text;
  alert(`Assigned ${selectedTeam} to the shift!`);
  closeModal('shiftDetailsModal');
  renderShifts();
  addToShiftLog('Team Assigned', `${selectedTeam} assigned to shift`);
}

function removeTeamFromShift() {
  alert('Team removed from the shift!');
  closeModal('shiftDetailsModal');
  renderShifts();
  addToShiftLog('Team Removed', 'Team removed from shift');
}

function editShiftTime() {
  const newStartTime = prompt('Enter new start time (HH:MM):', '09:00');
  const newEndTime = prompt('Enter new end time (HH:MM):', '17:00');
  if (newStartTime && newEndTime) {
    alert(`Shift time updated to ${newStartTime} - ${newEndTime}`);
    closeModal('shiftDetailsModal');
    renderShifts();
    addToShiftLog('Shift Time Updated', `Shift time changed to ${newStartTime} - ${newEndTime}`);
  }
}

function deleteShift() {
  if (confirm('Are you sure you want to delete this shift?')) {
    alert('Shift deleted successfully!');
    closeModal('shiftDetailsModal');
    renderShifts();
    addToShiftLog('Shift Deleted', 'A shift was deleted from the schedule');
  }
}
</script>

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

<?php require APPROOT . '/views/inc/components/footer.php'; ?>