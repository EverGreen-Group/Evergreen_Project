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

  <div class="action-buttons">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/addVehicle" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Add New Vehicle
      </a>

      <a href="<?php echo URLROOT; ?>/vehiclemanager/updateVehicle" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Update Vehicle
      </a>
  </div>

  <ul class="vehicle-box-info">
    <li>
        <i class='bx bxs-car'></i>
        <span class="text">
          <p>Total Vehicles</p>
          <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
          <p>Currently Available</p>
          <h3><?php echo isset($data['availableVehicles']) ? $data['availableVehicles'] : '0'; ?></h3>
        </span>
    </li>
  </ul>


  <?php flash('vehicle_message'); ?>
  <!-- New section for vehicle cards -->
  <div class="vehicle-cards-section">
      <h2>All Vehicles</h2>
      <div class="vehicle-cards-container" id="vehicleCardsContainer">
          <?php foreach ($data['vehicles'] as $vehicle): ?>
              <!-- Basic card with flexbox -->
              <div class="vehicle-card" style="display: flex; margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                  <!-- Left side - Image with fixed dimensions -->
                  <div style="margin-right: 20px;">
                      <img src="<?php echo URLROOT; ?>/public/uploads/vehicle_photos/<?php echo $vehicle->license_plate; ?>.jpg" 
                           alt="Vehicle <?php echo $vehicle->license_plate; ?>">
                  </div>
                  
                  <!-- Right side - Info -->
                  <div>
                      <h3 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($vehicle->license_plate); ?></h3>
                      <span style="display: inline-block; padding: 5px 10px; background: #e8f5e9; color: #2e7d32; border-radius: 15px; margin-bottom: 10px;">
                          <?php echo htmlspecialchars($vehicle->status); ?>
                      </span>
                      <p><strong>Type:</strong> <?php echo htmlspecialchars($vehicle->vehicle_type); ?></p>
                      <p><strong>Capacity:</strong> <?php echo htmlspecialchars($vehicle->capacity); ?> Tons</p>
                  </div>
              </div>
          <?php endforeach; ?>
      </div>
  </div>

  <!-- Vehicle Information Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Vehicle Availability</h3>
        <div class="filter-container">
                    <label for="dayFilter">Filter by Day:</label>
                    <select id="dayFilter">
                        <option value="all">All Days</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Plate Number</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if(isset($data['vehicles']) && !empty($data['vehicles'])): ?>
            <?php foreach($data['vehicles'] as $vehicle): ?>
                <tr>
                    <td><?php echo $vehicle->vehicle_id; ?></td>
                    <td><?php echo $vehicle->license_plate; ?></td>
                    <td><?php echo $vehicle->vehicle_type; ?></td>
                    <td><?php echo $vehicle->capacity; ?> Tons</td>
                    
                    <td>
                        <span class="status <?php 
                            echo $vehicle->status === 'Available' ? 'pending' : 
                                ($vehicle->status === 'In Use' ? 'process' : 'completed'); 
                        ?>">
                            <?php echo $vehicle->status; ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr>
                  <td colspan="9" class="text-center">No vehicles found</td>
              </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="chart-row">
    <div class="chart-container">
      <h3>Track Vehicle</h3>
      <div id="map-container" style="height: 200px; width: 100%;"></div>
      
      <div class="vehicle-status">
          <label for="vehicleSelect">Select Vehicle:</label>
          <select id="vehicleSelect">
              <option value="">Select vehicle</option>
              <?php if (isset($data['vehicles']) && !empty($data['vehicles'])): ?>
                  <?php foreach ($data['vehicles'] as $vehicle): ?>
                      <option value="<?php echo $vehicle->vehicle_id; ?>"> <?php echo $vehicle->license_plate; ?></option>
                  <?php endforeach; ?>
              <?php endif; ?>
          </select>
          <div id="vehicleInfo" style="margin-top: 10px;"></div>
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

<style>
        /* Existing styles */
        .filter-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .filter-container label {
            margin-right: 10px;
            font-weight: bold;
        }
        #dayFilter {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 150px;
        }
        #dayFilter:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
    </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vehicle Usage Chart (using dummy data for now)

        // Vehicle Types Chart (using real database data)
        const vehicleData = <?php echo json_encode($data['vehicleTypeStats']); ?>;
        const types = vehicleData.map(item => item.vehicle_type);
        const counts = vehicleData.map(item => parseInt(item.count));

        const ctx2 = document.getElementById('vehicleTypesChart');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: types,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        '#4CAF50',  // Green for Truck
                        '#2196F3',  // Blue for Van
                        '#FFC107'   // Yellow (if needed for Other)
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Available Vehicles by Type',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    });
  </script>



  <!-- Vehicle details modal -->
  <div id="vehicleDetailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Vehicle Details</h2>
      <div id="vehicleDetailsContent"></div>
    </div>
  </div>

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

<script>
// ONE single declaration of vehicles at the top
const vehicles = <?php echo json_encode($data['vehicles']); ?>;

// Then all our functions below can use this same 'vehicles' variable
function showVehicleDetails(vehicle) { ... }
function loadVehicleData(vehicleId) { ... }
</script>


<script>
document.getElementById('vehicleSelect').addEventListener('change', function() {
    const vehicleId = this.value;
    const vehicleInfoDiv = document.getElementById('vehicleInfo');

    if (vehicleId) {
        // Send AJAX request to fetch vehicle details
        fetch(`<?php echo URLROOT; ?>/vehiclemanager/getVehicleById/${vehicleId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                  const latitude =parseFloat(data.vehicle.latitude);
                  const longitude = parseFloat(data.vehicle.longitude);
                  updateMap(latitude, longitude);
                } else {
                    vehicleInfoDiv.innerHTML = '<p>Coordinates are not set for the vehicle.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching vehicle details:', error);
                vehicleInfoDiv.innerHTML = '<p>Error fetching vehicle details.</p>';
            });
    } else {
        vehicleInfoDiv.innerHTML = ''; // Clear vehicle info if no vehicle is selected
    }
});
</script>




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
      z-index: 3000;
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

    const vehicles = <?php echo json_encode($data['vehicles']); ?>;
    console.log('Vehicle Data:', vehicles);

    // Function to create vehicle cards
    function createVehicleCards() {
      const container = document.getElementById('vehicleCardsContainer');
      container.innerHTML = '';
      vehicles.forEach(vehicle => {
        const defaultImage = '<?php echo URLROOT; ?>/public/uploads/vehicle_photos/default-vehicle.jpg';
        const vehicleImage = vehicle.license_plate ? 
            '<?php echo URLROOT; ?>/public/uploads/vehicle_photos/' + vehicle.license_plate + '.jpg' : 
            defaultImage;

        const card = document.createElement('div');
        card.className = 'vehicle-card';
        card.innerHTML = `
          <div class="delete-button" onclick="event.stopPropagation(); confirmDelete(${vehicle.vehicle_id});">
              <i class='bx bx-trash'></i>
          </div>
          <img src="${vehicleImage}" 
               alt="Vehicle ${vehicle.license_plate}"
               onerror="this.src='${defaultImage}'">
          <div class="vehicle-card-info">
              <h3>${vehicle.license_plate || 'N/A'}</h3>
              <div class="vehicle-status">
                  <span class="status ${vehicle.status?.toLowerCase() || 'unknown'}">
                      ${vehicle.status || 'N/A'}
                  </span>
              </div>
              <p><strong>Type:</strong> ${vehicle.vehicle_type || 'N/A'}</p>
              <p><strong>Capacity:</strong> ${vehicle.capacity || 'N/A'} Kg</p>
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
      
      const formatDate = (dateString) => {
          return dateString ? new Date(dateString).toLocaleDateString() : 'N/A';
      };

      content.innerHTML = `
          <div class="vehicle-modal-content">
              <div class="vehicle-modal-image">
                <img src="<?php echo URLROOT; ?>/public/uploads/vehicle_photos/<?php echo $vehicle->license_plate; ?>.jpg" />
              </div>
              <div class="vehicle-modal-details">
                  <div class="detail-group">
                      <h3>Basic Information</h3>
                      <div class="detail-row">
                          <span class="label">License Plate:</span>
                          <span class="value">${vehicle.license_plate}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Vehicle Type:</span>
                          <span class="value">${vehicle.vehicle_type}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Status:</span>
                          <span class="value status-badge ${vehicle.status.toLowerCase()}">${vehicle.status}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Specifications</h3>
                      <div class="detail-row">
                          <span class="label">Capacity:</span>
                          <span class="value">${vehicle.capacity} kg</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Make:</span>
                          <span class="value">${vehicle.make}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Model:</span>
                          <span class="value">${vehicle.model}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Manufacturing Year:</span>
                          <span class="value">${vehicle.manufacturing_year}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Color:</span>
                          <span class="value">${vehicle.color}</span>
                      </div>

                  </div>
              </div>
          </div>
      `;
      modal.style.display = 'block';

      // Set up delete button handler
      const deleteButton = document.getElementById('deleteVehicleBtn');
      if (deleteButton) {
          deleteButton.onclick = () => {
              if (confirm('Are you sure you want to delete this vehicle?')) {
                  fetch(`<?php echo URLROOT; ?>/vehiclemanager/deleteVehicle/${vehicle.vehicle_id}`, {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-Requested-With': 'XMLHttpRequest'
                      }
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          modal.style.display = 'none';
                          window.location.reload();
                      } else {
                          alert(data.message || 'Failed to delete vehicle');
                      }
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      alert('An error occurred while deleting the vehicle');
                  });
              }
          };
      }
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
          if (input) {
            if (key.includes('date')) {
              // Format date inputs
              input.value = vehicle[key] ? vehicle[key].split('T')[0] : '';
            } else {
              input.value = vehicle[key] || '';
            }
          }
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

      // document.getElementById('addNewVehicleBtn').onclick = () => showVehicleForm();

      // document.getElementById('vehicleForm').onsubmit = handleFormSubmit;

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

<style>
.vehicle-modal-content {
    padding: 20px;
}

.vehicle-modal-image {
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f5f5f5;
}

.vehicle-modal-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
}

.vehicle-modal-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-group {
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.detail-group h3 {
    color: var(--main);
    margin-bottom: 10px;
    font-size: 1.2em;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.detail-row .label {
    color: #666;
    font-weight: 500;
    flex: 1;
}

.detail-row .value {
    flex: 2;
    color: #333;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.9em;
    display: inline-block;
}

.status-badge.available {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-badge.in-use {
    background: #e3f2fd;
    color: #1565c0;
}

.status-badge.maintenance {
    background: #fff3e0;
    color: #ef6c00;
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .vehicle-modal-details {
        gap: 15px;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .detail-row .label,
    .detail-row .value {
        flex: none;
    }
}
</style>

<style>
.action-buttons {
    margin: 20px 0;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.btn-primary {
    display: flex;
    align-items: center;
    gap: 8px;
    background-color: var(--main);
    color: var(--light);
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: var(--main-dark);
}

.btn-primary i {
    font-size: 1.2em;
}
</style>

<style>
    /* Add this to your existing styles */
    .alert {
        position: fixed;
        top: 80px; /* Adjust based on your header height */
        right: 20px;
        z-index: 1000;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        max-width: 300px;
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

<style>
    .delete-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .vehicle-card {
        position: relative;
    }

    .vehicle-card:hover .delete-button {
        opacity: 1;
    }

    .delete-button:hover {
        background-color: #dc3545;
    }

    .vehicle-status select {
    width: 100%; /* Full width for the dropdown */
    padding: 10px; /* Padding inside the dropdown */
    border: 1px solid #ccc; /* Border for the dropdown */
    border-radius: 4px; /* Rounded corners */
    font-size: 16px; /* Font size for the dropdown */
    background-color: #fff; /* White background for the dropdown */
    cursor: pointer; /* Pointer cursor on hover */
}
</style>

<script>
async function confirmDelete(vehicleId) {
    if (confirm('Are you sure you want to delete this vehicle?')) {
        try {
            const formData = new FormData();
            formData.append('vehicle_id', vehicleId);

            const response = await fetch(`<?php echo URLROOT; ?>/vehiclemanager/deleteVehicle/${vehicleId}`, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                window.location.reload(); // Reload the page after successful deletion
            } else {
                alert('Failed to delete vehicle. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the vehicle.');
        }
    }
}
</script>

<script>
function updateMap(lat, lng) {
    const vehicleLocation = { lat: lat, lng: lng };
    
    const map = new google.maps.Map(document.getElementById('map-container'), {
        center: vehicleLocation,
        zoom: 13,
    });

    // Add a marker for the vehicle location
    new google.maps.Marker({
        position: vehicleLocation,
        map: map,
        title: 'Vehicle Location'
    });
}
</script>

<!-- Include Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>
<script>
    let map;

    function initMap() {
        // Hardcoded vehicle location (example coordinates)
        const vehicleLocation = { lat: 6.2202197, lng: 80.2448223 }; 

        // Initialize the map
        map = new google.maps.Map(document.getElementById('map-container'), {
            center: vehicleLocation,
            zoom: 13,
        });

        // Add a marker for the vehicle location
        new google.maps.Marker({
            position: vehicleLocation,
            map: map,
            title: 'Vehicle Location'
        });
    }

</script>

</main>


</section>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>