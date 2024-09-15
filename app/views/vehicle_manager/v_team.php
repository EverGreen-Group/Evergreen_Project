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
          <h3>20</h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-check'></i>
        <span class="text">
          <p>On Duty</p>
          <h3>18</h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user-x'></i>
        <span class="text">
          <p>Unassigned Teams</p>
          <h3>2</h3>
        </span>
    </li>
  </ul>

  <!-- Team Information Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Team details</h3>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>Team ID</th>
            <th>Team Name</th>
            <th>Driver</th>
            <th>Partner</th>
            <th>Vehicle ID</th>
            <th>Shift ID</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Alpha Team</td>
            <td>John Doe</td>
            <td>Jane Smith</td>
            <td>VEH-001</td>
            <td>S123</td>
            <td><span class="status pending">On Duty</span></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Beta Team</td>
            <td>Mike Johnson</td>
            <td>Sarah Brown</td>
            <td>VEH-002</td>
            <td>S124</td>
            <td><span class="status completed">Available</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="team-cards-section">
    <h2>All Teams</h2>
    <button id="createTeamButton" class="create-team-btn">Create Team</button>
    <div class="team-cards-container" id="teamCardsContainer">
      <!-- Team cards will be dynamically added here -->
    </div>
  </div>



 <!-- Modal Form for Creating a New Team -->
 <div id="createTeamModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Create New Team</h2>
      
      <!-- Form to create a new team -->
      <form id="createTeamForm">
        <label for="teamName">Team Name:</label>
        <input type="text" id="teamName" name="teamName" required>

        <label for="driver">Driver:</label>
        <select id="driver" name="driver" required>
          <option value="">Select Driver</option>
          <option value="John Smith">John Smith</option>
          <option value="Emily Johnson">Emily Johnson</option>
          <!-- Add more drivers here -->
        </select>

        <label for="partner">Driving Partner:</label>
        <select id="partner" name="partner" required>
          <option value="">Select Partner</option>
          <option value="Michael Brown">Michael Brown</option>
          <option value="Sarah Davis">Sarah Davis</option>
          <!-- Add more driving partners here -->
        </select>

        <label for="shift">Shift:</label>
        <select id="shift" name="shift" required>
          <option value="">Select Shift</option>
          <option value="Morning">Morning</option>
          <option value="Afternoon">Afternoon</option>
          <option value="Night">Night</option>
          <!-- Add more shifts if needed -->
        </select>

        <label for="route">Route:</label>
        <select id="route" name="route" required>
          <option value="">Select Route</option>
          <option value="Route A">Route A</option>
          <option value="Route B">Route B</option>
          <option value="Route C">Route C</option>
          <!-- Add more routes as needed -->
        </select>

        <button type="submit" class="submit-btn">Create Team</button>
      </form>
    </div>
  </div>


  <style>
    /* Button Styles */
    .create-team-btn {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
    }

    .create-team-btn:hover {
      background-color: #0056b3;
    }

    /* Modal Styles */
    .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1000; /* Sit on top */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto; /* Center it */
      padding: 20px;
      border-radius: 10px;
      width: 50%; /* Could be more or less, depending on screen size */
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


<script>
  document.addEventListener('DOMContentLoaded', () => {
    const createTeamButton = document.getElementById('createTeamButton');
    const modal = document.getElementById('createTeamModal');
    const closeBtn = document.querySelector('.close');

    // Show the modal when the "Create Team" button is clicked
    createTeamButton.onclick = function() {
      modal.style.display = 'block';
    };

    // Close the modal when the user clicks the 'x'
    closeBtn.onclick = function() {
      modal.style.display = 'none';
    };

    // Close the modal if the user clicks outside the modal content
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    };

    // Submit Form Logic
    const createTeamForm = document.getElementById('createTeamForm');
    createTeamForm.onsubmit = function(event) {
      event.preventDefault();

      const teamName = document.getElementById('teamName').value;
      const teamLeader = document.getElementById('teamLeader').value;
      const teamMember1 = document.getElementById('teamMember1').value;
      const teamMember2 = document.getElementById('teamMember2').value;
      const teamMember3 = document.getElementById('teamMember3').value;

      // Log the data (you can add this to a data structure or make an API call here)
      console.log('Team Created:', {
        teamName,
        teamLeader,
        members: [teamMember1, teamMember2, teamMember3]
      });

      // Clear the form and hide the modal after submission
      createTeamForm.reset();
      modal.style.display = 'none';
    };
  });

</script>

  <!-- Team details modal -->
  <div id="teamDetailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Team Details</h2>
      <div id="teamDetailsContent"></div>
      <div class="modal-actions">
        <button id="editTeamBtn" class="btn btn-primary">Edit</button>
        <button id="deleteTeamBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>


  <div class="unassigned-section">
    <h2>Unassigned Drivers</h2>
    <div class="unassigned-cards-container" id="unassignedDriversContainer"></div>
  </div>

  <div class="unassigned-section">
    <h2>Unassigned Driving Partners</h2>
    <div class="unassigned-cards-container" id="unassignedPartnersContainer"></div>
  </div>

  <div class="unassigned-section">
    <h2>Unassigned Vehicles</h2>
    <div class="unassigned-cards-container" id="unassignedVehiclesContainer"></div>
  </div>

  <!-- Details Modal -->
  <div id="detailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div id="detailsContent"></div>
    </div>
  </div>


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



  <script>
    // Sample team data (replace with actual data from your backend)
    const teams = [
      { id: 1, name: 'Alpha Team', driver: 'John Doe', partner: 'AB', vehicleId: 'VEH-001', shiftId: 'S123', totalHours: 120, reports: 2, image: 'https://i.ikman-st.com/labroder-retriever-for-crossing-for-sale-puttalam/fc6747fc-9b63-4f2b-9a42-3a6270e1db13/620/466/fitted.jpg' },
      { id: 2, name: 'Beta Team', driver: 'Jane Doe', partner: 'CD', vehicleId: 'VEH-002', shiftId: 'S124', totalHours: 110, reports: 1, image: 'https://i.ikman-st.com/rottweiler-puppy-for-sale-colombo-1232/55321a0d-b2c1-4a9a-92f5-10f5b66f6b2d/620/466/fitted.jpg' },
      // Add more team objects as needed
    ];

  function createTeamCards() {
    const container = document.getElementById('teamCardsContainer');
    container.innerHTML = '';
    teams.forEach(team => {
      const card = document.createElement('div');
      card.className = 'team-card';
      card.innerHTML = `
        <div class="team-icons">
          <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Driver Icon" title="${team.driver}">
          <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Partner Icon" title="${team.partner}">
        </div>
        <div class="team-card-info">
          <h3>${team.name}</h3>
          <p>Driver: ${team.driver}</p>
          <p>Partner: ${team.partner}</p>
        </div>
      `;
      card.onclick = () => showTeamDetails(team);
      container.appendChild(card);
    });
  }

  function showTeamDetails(team) {
    const modal = document.getElementById('teamDetailsModal');
    const content = document.getElementById('teamDetailsContent');
    content.innerHTML = `
      <div class="team-member-images">
        <div>
          <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="${team.driver}" title="Driver">
          <p>Driver: ${team.driver}</p>
        </div>
        <div>
          <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="${team.partner}" title="Partner">
          <p>Partner: ${team.partner}</p>
        </div>
      </div>
      <p><strong>Team ID:</strong> ${team.id}</p>
      <p><strong>Team Name:</strong> ${team.name}</p>
      <p><strong>Vehicle ID:</strong> ${team.vehicleId}</p>
      <p><strong>Shift ID:</strong> ${team.shiftId}</p>
      <p><strong>Total Hours Worked:</strong> ${team.totalHours}</p>
      <p><strong>Reports:</strong> ${team.reports}</p>
    `;
    modal.style.display = 'block';

    // Set up delete button
    document.getElementById('deleteTeamBtn').onclick = () => deleteTeam(team.id);
  }
  
  
  // Function to show add/edit team form
    function showTeamForm(team = null) {
      const modal = document.getElementById('teamFormModal');
      const form = document.getElementById('teamForm');
      const formTitle = document.getElementById('formTitle');

      if (team) {
        formTitle.textContent = 'Edit Team';
        // Populate form with team data
        Object.keys(team).forEach(key => {
          const input = form.elements[key];
          if (input && key !== 'image') input.value = team[key];
        });
      } else {
        formTitle.textContent = 'Add New Team';
        form.reset();
      }

      modal.style.display = 'block';
    }

    // Function to handle form submission (add or edit team)
    function handleFormSubmit(event) {
      event.preventDefault();
      const formData = new FormData(event.target);
      const teamData = Object.fromEntries(formData.entries());

      // Handle image upload (placeholder for now)
      teamData.image = 'https://i.ikman-st.com/mazda-bongo-1997-for-sale-puttalam-2/cdd5b09e-ab3f-42c4-8642-575b1bc9072b/620/466/fitted.jpg';

      if (teamData.teamId) {
        // Edit existing team
        const index = teams.findIndex(t => t.id === parseInt(teamData.teamId));
        if (index !== -1) {
          teams[index] = { ...teams[index], ...teamData };
        }
      } else {
        // Add new team
        teamData.id = teams.length + 1;
        teams.push(teamData);
      }

      createTeamCards();
      closeModal('teamFormModal');
    }

    // Function to edit a team
    function editTeam(team) {
      closeModal('teamDetailsModal');
      showTeamForm(team);
    }

    // Function to delete a team
    function deleteTeam(id) {
      if (confirm('Are you sure you want to delete this team?')) {
        const index = teams.findIndex(team => team.id === id);
        if (index !== -1) {
          teams.splice(index, 1);
          createTeamCards();
          closeModal('teamDetailsModal');
        }
      }
    }

    // Function to close modal
    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    // Function to initialize charts (using Chart.js)
    function initializeCharts() {
      // Team Performance Chart
      const ctxPerformance = document.getElementById('teamPerformanceChart').getContext('2d');
      new Chart(ctxPerformance, {
        type: 'bar',
        data: {
          labels: teams.map(team => team.name),
          datasets: [{
            label: 'Total Hours Worked',
            data: teams.map(team => team.totalHours),
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Team Shift Distribution Chart
      const ctxShift = document.getElementById('teamShiftChart').getContext('2d');
      new Chart(ctxShift, {
        type: 'doughnut',
        data: {
          labels: ['On Duty', 'Available', 'Unassigned'],
          datasets: [{
            label: 'Shift Distribution',
            data: [
              teams.filter(team => team.status === 'On Duty').length,
              teams.filter(team => team.status === 'Available').length,
              teams.filter(team => team.status === 'Unassigned').length
            ],
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 206, 86, 0.2)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 206, 86, 1)'],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true
        }
      });
    }

    // Initialize everything on page load
    document.addEventListener('DOMContentLoaded', () => {
      createTeamCards();
    });
  </script>


<style>
  .unassigned-section {
    margin-top: 40px;
    background-color: #f5f5f5;
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
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
    width: calc(12%);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    cursor: pointer;
  }

  @media screen and (max-width: 1024px) {
    .unassigned-card {
      width: calc(10%); /* Adjust width for medium screens */
    }
  }

  @media screen and (max-width: 768px) {
    .unassigned-card {
      width: calc(50% - 10px); /* Adjust width for smaller screens */
    }
  }

  .unassigned-card:hover {
    transform: translateY(-5px);
  }

  .unassigned-card img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 10%;
    margin-bottom: 10px;
  }

  .unassigned-card h3 {
    margin-top: 0;
    color: var(--dark);
  }

  .unassigned-card p {
    margin: 5px 0;
    color: var(--dark-grey);
  }

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

<script>
  // Sample data for unassigned drivers, partners, and vehicles
  const unassignedDrivers = [
    { id: 'D1', name: 'John Smith', experience: '5 years', license: 'Class A', image: 'https://randomuser.me/api/portraits/men/1.jpg' },
    { id: 'D2', name: 'Emily Johnson', experience: '3 years', license: 'Class B', image: 'https://randomuser.me/api/portraits/women/1.jpg' },
    // Add more unassigned drivers as needed
  ];

  const unassignedPartners = [
    { id: 'P1', name: 'Michael Brown', experience: '2 years', image: 'https://randomuser.me/api/portraits/men/2.jpg' },
    { id: 'P2', name: 'Sarah Davis', experience: '4 years', image: 'https://randomuser.me/api/portraits/women/2.jpg' },
    // Add more unassigned partners as needed
  ];

  const unassignedVehicles = [
    { id: 'V1', type: 'Truck', model: 'Freightliner Cascadia', capacity: '36000', image: 'https://i.ikman-st.com/mazda-bongo-1997-for-sale-puttalam-2/cdd5b09e-ab3f-42c4-8642-575b1bc9072b/620/466/fitted.jpg' },
    { id: 'V2', type: 'Van', model: 'Mercedes-Benz Sprinter', capacity: '1500', image: 'https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg' },
    // Add more unassigned vehicles as needed
  ];

  function createUnassignedCards() {
    createUnassignedDriverCards();
    createUnassignedPartnerCards();
    createUnassignedVehicleCards();
  }

  function createUnassignedDriverCards() {
    const container = document.getElementById('unassignedDriversContainer');
    container.innerHTML = '';
    unassignedDrivers.forEach(driver => {
      const card = document.createElement('div');
      card.className = 'unassigned-card';
      card.innerHTML = `
        <img src="${driver.image}" alt="${driver.name}">
        <h3>${driver.name}</h3>
        <p><strong>ID:</strong> ${driver.id}</p>
      `;
      card.onclick = () => showDetails('driver', driver);
      container.appendChild(card);
    });
  }

  function createUnassignedPartnerCards() {
    const container = document.getElementById('unassignedPartnersContainer');
    container.innerHTML = '';
    unassignedPartners.forEach(partner => {
      const card = document.createElement('div');
      card.className = 'unassigned-card';
      card.innerHTML = `
        <img src="${partner.image}" alt="${partner.name}">
        <h3>${partner.name}</h3>
        <p><strong>ID:</strong> ${partner.id}</p>
      `;
      card.onclick = () => showDetails('partner', partner);
      container.appendChild(card);
    });
  }

  function createUnassignedVehicleCards() {
    const container = document.getElementById('unassignedVehiclesContainer');
    container.innerHTML = '';
    unassignedVehicles.forEach(vehicle => {
      const card = document.createElement('div');
      card.className = 'unassigned-card';
      card.innerHTML = `
        <img src="${vehicle.image}" alt="${vehicle.model}">
        <h3>${vehicle.model}</h3>
        <p><strong>ID:</strong> ${vehicle.id}</p>
        <p><strong>Type:</strong> ${vehicle.type}</p>
        <p><strong>Capacity:</strong> ${vehicle.capacity} kg</p>
      `;
      card.onclick = () => showDetails('vehicle', vehicle);
      container.appendChild(card);
    });
  }

  function showDetails(type, item) {
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('detailsContent');
    let detailsHTML = '';

    switch(type) {
      case 'driver':
        detailsHTML = `
          <h2>Driver Details</h2>
          <img src="${item.image}" alt="${item.name}" style="width: 120px; height: 120px; border-radius: 50%;">
          <p><strong>Name:</strong> ${item.name}</p>
          <p><strong>ID:</strong> ${item.id}</p>
          <p><strong>Experience:</strong> ${item.experience}</p>
          <p><strong>License:</strong> ${item.license}</p>
        `;
        break;
      case 'partner':
        detailsHTML = `
          <h2>Driving Partner Details</h2>
          <img src="${item.image}" alt="${item.name}" style="width: 120px; height: 120px; border-radius: 50%;">
          <p><strong>Name:</strong> ${item.name}</p>
          <p><strong>ID:</strong> ${item.id}</p>
          <p><strong>Experience:</strong> ${item.experience}</p>
        `;
        break;
      case 'vehicle':
        detailsHTML = `
          <h2>Vehicle Details</h2>
          <img src="${item.image}" alt="${item.model}" style="width: 200px; height: auto;">
          <p><strong>ID:</strong> ${item.id}</p>
          <p><strong>Type:</strong> ${item.type}</p>
          <p><strong>Model:</strong> ${item.model}</p>
          <p><strong>Capacity:</strong> ${item.capacity} kg</p>
        `;
        break;
    }

    content.innerHTML = detailsHTML;
    modal.style.display = 'block';
  }

  // Close modal when clicking on the close button or outside the modal
  window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target == modal || event.target.className == 'close') {
      modal.style.display = 'none';
    }
  }

  // Update the existing DOMContentLoaded event listener
  document.addEventListener('DOMContentLoaded', () => {
    createTeamCards();
    createUnassignedCards();
  });
</script>

</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
