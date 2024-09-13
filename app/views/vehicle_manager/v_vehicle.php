<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
  <!-- Vehicle Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Vehicle Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <ul class="vehicle-box-info">
    <li>
        <i class='bx bxs-car'></i>
        <span class="text">
          <p>Total Vehicles</p>
          <h3>20</h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
          <p>Currently Available</p>
          <h3>18</h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
          <p>Under Maintainance</p>
          <h3>2</h3>
        </span>
    </li>
  </ul>

  <!-- Vehicle Information Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Available Vehicles</h3>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Plate Number</th>
            <th>Make</th>
            <th>Model</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>ABC-123</td>
            <td>Toyota</td>
            <td>Hiace</td>
            <td>Van</td>
            <td>10 Tons</td>
            <td><span class="status pending">Available</span></td>
          </tr>
          <tr>
            <td>2</td>
            <td>XYZ-789</td>
            <td>Ford</td>
            <td>Transit</td>
            <td>Truck</td>
            <td>15 Tons</td>
            <td><span class="status process">Unavailable</span></td>
          </tr>
          <tr>
            <td>3</td>
            <td>LMN-456</td>
            <td>Mitsubishi</td>
            <td>L200</td>
            <td>Pickup</td>
            <td>3 Tons</td>
            <td><span class="status pending">Available</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="chart-row">
    <div class="chart-container">
      <h3>Vehicle Usage Overview</h3>
      <div class="chart-wrapper">
        <canvas id="vehicleUsageChart"></canvas>
      </div>
    </div>

    <div class="chart-container">
      <h3>Available Vehicle Types</h3>
      <div class="chart-wrapper">
        <canvas id="vehicleTypesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Internal CSS -->
  <style>
    .vehicle-box-info {
      display: flex;
      justify-content: space-between;
      gap: 24px;
      margin-top: 36px;
      list-style: none;
      padding: 0;
    }

    .vehicle-box-info li {
      flex: 1;
      background: var(--light);
      border-radius: 20px;
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 40px;
      position: relative;
    }

    .vehicle-box-info li i {
      font-size: 48px;
      color: var(--main);
      background: var(--light-main);
      border-radius: 10%;
      padding: 20px;
    }

    .vehicle-box-info li .text {
      display: flex;
      flex-direction: column;
    }

    .vehicle-box-info li .text h3 {
      font-size: 32px;
      font-weight: 600;
      color: var(--dark);
    }

    .vehicle-box-info li .text p {
      font-size: 20px;
      color: var(--dark);
    }

    .vehicle-box-info li .text small {
      font-size: 16px;
      color: var(--dark-grey);
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
      height: 300px; /* Set a fixed height for the chart wrapper */
      width: 100%;
    }

    @media screen and (max-width: 768px) {
      .vehicle-box-info {
        flex-direction: column;
        gap: 16px;
      }

      .chart-row {
        flex-direction: column;
      }

      .chart-container {
        max-width: 100%;
      }
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    var ctx = document.getElementById('vehicleUsageChart').getContext('2d');
    var vehicleUsageChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Vehicle Usage (Trips)',
          data: [50, 60, 70, 55, 80, 95, 90, 85, 100, 110, 115, 120],
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    var ctx2 = document.getElementById('vehicleTypesChart').getContext('2d');
    var vehicleTypesChart = new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Van', 'Truck', 'Pickup'],
        datasets: [{
          label: 'Vehicle Types',
          data: [10, 5, 3],
          backgroundColor: [
            'rgba(75, 192, 192, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(153, 102, 255, 0.6)'
          ],
          borderColor: [
            'rgba(75, 192, 192, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(153, 102, 255, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          }
        }
      }
    });
  </script>
  <!-- New section for vehicle cards -->
  <div class="vehicle-cards-section">
    <h2>All Vehicles</h2>
    <div class="vehicle-cards-container" id="vehicleCardsContainer">
      <!-- Vehicle cards will be dynamically added here -->
    </div>
  </div>

  <!-- Vehicle details modal -->
  <div id="vehicleDetailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Vehicle Details</h2>
      <div id="vehicleDetailsContent"></div>
      <div class="modal-actions">
        <button id="editVehicleBtn" class="btn btn-primary">Edit</button>
        <button id="deleteVehicleBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>

  <!-- Add/Edit Vehicle Form Modal -->
  <div id="vehicleFormModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="formTitle">Add New Vehicle</h2>
      <form id="vehicleForm">
        <input type="hidden" id="vehicleId" name="vehicleId">
        <div class="form-group">
          <label for="image">Vehicle Image:</label>
          <input type="file" id="image" name="image" accept="image/*">
        </div>
        <div class="form-group">
          <label for="plateNo">Plate No:</label>
          <input type="text" id="plateNo" name="plateNo" required>
        </div>
        <div class="form-group">
          <label for="make">Make:</label>
          <input type="text" id="make" name="make" required>
        </div>
        <div class="form-group">
          <label for="model">Model:</label>
          <input type="text" id="model" name="model" required>
        </div>
        <div class="form-group">
          <label for="type">Type:</label>
          <input type="text" id="type" name="type" required>
        </div>
        <div class="form-group">
          <label for="capacity">Tea Leaves Capacity:</label>
          <input type="text" id="capacity" name="capacity" required>
        </div>
        <div class="form-group">
          <label for="lastMaintenance">Last Maintenance:</label>
          <input type="date" id="lastMaintenance" name="lastMaintenance" required>
        </div>
        <div class="form-group">
          <label for="nextMaintenance">Next Maintenance:</label>
          <input type="date" id="nextMaintenance" name="nextMaintenance" required>
        </div>
        <div class="form-group">
          <label for="mileage">Mileage:</label>
          <input type="number" id="mileage" name="mileage" required>
        </div>
        <div class="form-group">
          <label for="fuelInsurance">Fuel Insurance:</label>
          <input type="text" id="fuelInsurance" name="fuelInsurance" required>
        </div>
        <div class="form-group">
          <label for="regDate">Registration Date:</label>
          <input type="date" id="regDate" name="regDate" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Vehicle</button>
      </form>
    </div>
  </div>

  <!-- Add New Vehicle button -->
  <button id="addNewVehicleBtn" class="btn btn-primary add-vehicle-btn">Add New Vehicle</button>

<!-- Maintenance List Table -->
<div class="table-data">
  <div class="order">
    <div class="head">
      <h3>Maintenance List</h3>
      <button id="addMaintenanceBtn" class="btn btn-primary">Add Maintenance</button>
    </div>
    <table id="maintenanceTable">
      <thead>
        <tr>
          <th>VNO</th>
          <th>Reg Date</th>
          <th>Vehicle Name</th>
          <th>Maintenance Type</th>
          <th>Last Maintenance</th>
          <th>Next Maintenance</th>
          <th>Days Left</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="maintenanceTableBody">
        <!-- Table rows will be dynamically added here -->
      </tbody>
    </table>
  </div>
</div>

<!-- Add/Edit Maintenance Form Modal -->
<div id="maintenanceFormModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="maintenanceFormTitle">Add New Maintenance</h2>
    <form id="maintenanceForm">
      <input type="hidden" id="maintenanceId" name="maintenanceId">
      <div class="form-group">
        <label for="vno">VNO:</label>
        <input type="text" id="vno" name="vno" required>
      </div>
      <div class="form-group">
        <label for="regDate">Reg Date:</label>
        <input type="date" id="regDate" name="regDate" required>
      </div>
      <div class="form-group">
        <label for="maintenanceType">Maintenance Type:</label>
        <input type="text" id="maintenanceType" name="maintenanceType" required>
      </div>
      <div class="form-group">
        <label for="lastMaintenance">Last Maintenance:</label>
        <input type="date" id="lastMaintenance" name="lastMaintenance" required>
      </div>
      <div class="form-group">
        <label for="nextMaintenance">Next Maintenance:</label>
        <input type="date" id="nextMaintenance" name="nextMaintenance" required>
      </div>
      <button type="submit" class="btn btn-primary">Save Maintenance</button>
    </form>
  </div>
</div>



  <!-- Internal CSS -->
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

  <script>
    // Sample vehicle data (replace with actual data from your backend)
    const vehicles = [
      { id: 1, plateNo: 'ABC-123', make: 'Toyota', model: 'Hiace', type: 'Van', capacity: '10 Tons', lastMaintenance: '2023-05-15', nextMaintenance: '2023-11-15', mileage: 50000, fuelInsurance: 'Full Coverage', regDate: '2020-01-01', image: 'https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg' },
      { id: 2, plateNo: 'XYZ-789', make: 'Ford', model: 'Transit', type: 'Truck', capacity: '15 Tons', lastMaintenance: '2023-06-01', nextMaintenance: '2023-12-01', mileage: 75000, fuelInsurance: 'Basic', regDate: '2019-05-15', image: 'https://i.ikman-st.com/mazda-bongo-1997-for-sale-puttalam-2/cdd5b09e-ab3f-42c4-8642-575b1bc9072b/620/466/fitted.jpg' },
      // Add more vehicle objects as needed
    ];

    // Function to create vehicle cards
    function createVehicleCards() {
      const container = document.getElementById('vehicleCardsContainer');
      container.innerHTML = '';
      vehicles.forEach(vehicle => {
        const card = document.createElement('div');
        card.className = 'vehicle-card';
        card.innerHTML = `
          <img src="${vehicle.image}" alt="${vehicle.make} ${vehicle.model}">
          <div class="vehicle-card-info">
            <h3>${vehicle.make} ${vehicle.model}</h3>
            <p>Plate No: ${vehicle.plateNo}</p>
            <p>Type: ${vehicle.type}</p>
          </div>
        `;
        card.onclick = () => showVehicleDetails(vehicle);
        container.appendChild(card);
      });
    }

    // Function to show vehicle details
    function showVehicleDetails(vehicle) {
      const modal = document.getElementById('vehicleDetailsModal');
      const content = document.getElementById('vehicleDetailsContent');
      content.innerHTML = `
        <img src="${vehicle.image}" alt="${vehicle.make} ${vehicle.model}" style="max-width: 100%; margin-bottom: 15px;">
        <p><strong>Vehicle ID:</strong> ${vehicle.id}</p>
        <p><strong>Plate No:</strong> ${vehicle.plateNo}</p>
        <p><strong>Make:</strong> ${vehicle.make}</p>
        <p><strong>Model:</strong> ${vehicle.model}</p>
        <p><strong>Type:</strong> ${vehicle.type}</p>
        <p><strong>Capacity:</strong> ${vehicle.capacity}</p>
        <p><strong>Last Maintenance:</strong> ${vehicle.lastMaintenance}</p>
        <p><strong>Next Maintenance:</strong> ${vehicle.nextMaintenance}</p>
        <p><strong>Mileage:</strong> ${vehicle.mileage}</p>
        <p><strong>Fuel Insurance:</strong> ${vehicle.fuelInsurance}</p>
        <p><strong>Registration Date:</strong> ${vehicle.regDate}</p>
      `;
      modal.style.display = 'block';

      // Set up edit and delete buttons
      document.getElementById('editVehicleBtn').onclick = () => editVehicle(vehicle);
      document.getElementById('deleteVehicleBtn').onclick = () => deleteVehicle(vehicle.id);
    }

    // Function to show add/edit vehicle form
    function showVehicleForm(vehicle = null) {
      const modal = document.getElementById('vehicleFormModal');
      const form = document.getElementById('vehicleForm');
      const formTitle = document.getElementById('formTitle');

      if (vehicle) {
        formTitle.textContent = 'Edit Vehicle';
        // Populate form with vehicle data
        Object.keys(vehicle).forEach(key => {
          const input = form.elements[key];
          if (input && key !== 'image') input.value = vehicle[key];
        });
      } else {
        formTitle.textContent = 'Add New Vehicle';
        form.reset();
      }

      modal.style.display = 'block';
    }

    // Function to handle form submission (add or edit vehicle)
    function handleFormSubmit(event) {
      event.preventDefault();
      const formData = new FormData(event.target);
      const vehicleData = Object.fromEntries(formData.entries());

      // Handle image upload
      const imageFile = formData.get('image');
      if (imageFile.size > 0) {
        // In a real application, you would upload the image to your server here
        // For this example, we'll use a placeholder image
        vehicleData.image = '/api/placeholder/80/80';
      } else if (!vehicleData.image) {
        vehicleData.image = '/api/placeholder/80/80';
      }

      if (vehicleData.vehicleId) {
        // Edit existing vehicle
        const index = vehicles.findIndex(v => v.id === parseInt(vehicleData.vehicleId));
        if (index !== -1) {
          vehicles[index] = { ...vehicles[index], ...vehicleData };
        }
      } else {
        // Add new vehicle
        vehicleData.id = vehicles.length + 1;
        vehicles.push(vehicleData);
      }

      createVehicleCards();
      closeModal('vehicleFormModal');
    }

    // Function to edit a vehicle
    function editVehicle(vehicle) {
      closeModal('vehicleDetailsModal');
      showVehicleForm(vehicle);
    }

    // Function to delete a vehicle
    function deleteVehicle(id) {
      if (confirm('Are you sure you want to delete this vehicle?')) {
        const index = vehicles.findIndex(v => v.id === id);
        if (index !== -1) {
          vehicles.splice(index, 1);
          createVehicleCards();
          closeModal('vehicleDetailsModal');
        }
      }
    }

    // Function to close modal
    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', () => {
      createVehicleCards();

      document.getElementById('addNewVehicleBtn').onclick = () => showVehicleForm();

      document.getElementById('vehicleForm').onsubmit = handleFormSubmit;

      // Close modals when clicking on the close button or outside the modal
      document.querySelectorAll('.modal .close').forEach(closeBtn => {
        closeBtn.onclick = () => closeModal(closeBtn.closest('.modal').id);
      });

      window.onclick = (event) => {
        if (event.target.className === 'modal') {
          closeModal(event.target.id);
        }
      };
    });
  </script>

<script>
  // Sample maintenance data
  const maintenances = [
    { id: 1, vno: '1', regDate: '2020-01-01', vehicleName: 'Toyota Hiace', maintenanceType: 'Routine Check', lastMaintenance: '2023-05-15', nextMaintenance: '2023-11-15', daysLeft: 60 },
    // Add more maintenance objects as needed
  ];

  // Function to calculate days left
// Function to calculate days left
function calculateDaysLeft(nextMaintenanceDate) {
  const today = new Date();
  const nextMaintenance = new Date(nextMaintenanceDate);
  const timeDiff = nextMaintenance - today;
  return Math.ceil(timeDiff / (1000 * 3600 * 24));
}

// Helper function to parse MM-DD-YYYY format to Date object
function parseDate(dateString) {
  const [month, day, year] = dateString.split('-').map(Number);
  return new Date(year, month - 1, day); // Month is zero-based in JavaScript Date object
}

// Function to calculate days between two dates
function calculateDaysBetween(date1, date2) {
  const timeDiff = date2 - date1;
  return Math.max(Math.ceil(timeDiff / (1000 * 60 * 60 * 24)), 0); // Convert time difference from milliseconds to days
}

// Function to create maintenance rows in the table
function createMaintenanceRows() {
  const tableBody = document.getElementById('maintenanceTableBody');
  tableBody.innerHTML = ''; // Clear existing rows

  maintenances.forEach(maintenance => {
    const row = document.createElement('tr');
    
    // Calculate days left
    const today = new Date();
    const nextMaintenanceDate = parseDate(maintenance.nextMaintenance);
    
    // Ensure the date is valid
    let daysLeft;
    if (isNaN(nextMaintenanceDate.getTime())) {
      daysLeft = 'Invalid Date';
    } else {
      daysLeft = calculateDaysBetween(today, nextMaintenanceDate);
    }
    
    row.innerHTML = `
      <td>${maintenance.vno}</td>
      <td>${maintenance.regDate}</td>
      <td>${maintenance.vehicleName}</td>
      <td>${maintenance.maintenanceType}</td>
      <td>${maintenance.lastMaintenance}</td>
      <td>${maintenance.nextMaintenance}</td>
      <td>${daysLeft} days</td>
      <td><button class="btn btn-primary edit-btn" data-id="${maintenance.id}">Edit</button></td>
      <td><button class="btn btn-danger delete-btn" data-id="${maintenance.id}">Delete</button></td>
    `;
    
    tableBody.appendChild(row);
  });

  // Attach event listeners for edit and delete buttons
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.onclick = editMaintenance;
  });

  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.onclick = deleteMaintenance;
  });
}


// Function to show add/edit maintenance form
  function showMaintenanceForm(maintenance = null) {
    const modal = document.getElementById('maintenanceFormModal');
    const form = document.getElementById('maintenanceForm');
    const formTitle = document.getElementById('maintenanceFormTitle');

    if (maintenance) {
      formTitle.textContent = 'Edit Maintenance';
      // Populate form with maintenance data
      Object.keys(maintenance).forEach(key => {
        const input = form.elements[key];
        if (input && key !== 'image') input.value = maintenance[key];
      });
    } else {
      formTitle.textContent = 'Add New Maintenance';
      form.reset();
    }

    modal.style.display = 'block';
  }

  // Function to handle form submission (add or edit maintenance)
// Function to handle form submission (add or edit maintenance)
function handleMaintenanceFormSubmit(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  const maintenanceData = Object.fromEntries(formData.entries());

  // Calculate days left
  const daysLeft = calculateDaysLeft(maintenanceData.nextMaintenance);
  maintenanceData.daysLeft = daysLeft; // Include daysLeft in the data

  if (maintenanceData.maintenanceId) {
    // Edit existing maintenance
    const index = maintenances.findIndex(m => m.id === parseInt(maintenanceData.maintenanceId));
    if (index !== -1) {
      maintenances[index] = { ...maintenances[index], ...maintenanceData };
    }
  } else {
    // Add new maintenance
    maintenanceData.id = maintenances.length + 1;
    maintenances.push(maintenanceData);
  }

  createMaintenanceRows();
  closeModal('maintenanceFormModal');
}

// Function to handle edit button click
function editMaintenance(event) {
  const maintenanceId = parseInt(event.target.getAttribute('data-id'));
  
  // Debugging output
  console.log('Editing maintenance with ID:', maintenanceId);
  
  const maintenance = maintenances.find(m => m.id === maintenanceId);

  if (maintenance) {
    console.log('Maintenance record found:', maintenance); // Debugging output
    showMaintenanceForm(maintenance);
  } else {
    console.error('Maintenance record not found'); // Debugging output
  }
}

  // Function to delete a maintenance
  function deleteMaintenance(id) {
    if (confirm('Are you sure you want to delete this maintenance record?')) {
      const index = maintenances.findIndex(m => m.id === id);
      if (index !== -1) {
        maintenances.splice(index, 1);
        createMaintenanceRows();
      }
    }
  }

  // Function to close modal
  function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
  }

  // Event listeners
  document.addEventListener('DOMContentLoaded', () => {
    createMaintenanceRows();

    document.getElementById('addMaintenanceBtn').onclick = () => showMaintenanceForm();

    document.getElementById('maintenanceForm').onsubmit = handleMaintenanceFormSubmit;

    // Close modals when clicking on the close button or outside the modal
    document.querySelectorAll('.modal .close').forEach(closeBtn => {
      closeBtn.onclick = () => closeModal(closeBtn.closest('.modal').id);
    });

    window.onclick = (event) => {
      if (event.target.className === 'modal') {
        closeModal(event.target.id);
      }
    };
  });
</script>


</main>
<!-- MAIN -->
<!-- MAIN -->
</section>
<!-- CONTENT -->

<?php require APPROOT . '/views/inc/components/footer.php'; ?>