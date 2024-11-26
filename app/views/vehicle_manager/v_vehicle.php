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
      <a href="<?php echo URLROOT; ?>/vehicles/addVehicle" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Add New Vehicle
      </a>

      <a href="<?php echo URLROOT; ?>/vehicles/updateVehicle" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Update Vehicle
      </a>
  </div>

  <!-- Replace the separate table and chart sections with this combined layout -->
  <div class="table-chart-container">
      <!-- Vehicle Information Table -->
      <div class="table-data">
          <div class="order">
              <div class="head">
                  <h3>Vehicle Availability</h3>
                  <i class='bx bx-search'></i>
              </div>
              <table>
                  <thead>
                      <tr>
                          <th>Plate Number</th>
                          <th>Type</th>
                          <th>Capacity</th>
                          <th>Available Days</th>
                          <th>Status</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($data['vehicles']) && !empty($data['vehicles'])): ?>
                      <?php foreach($data['vehicles'] as $vehicle): ?>
                          <tr>
                              <td><?php echo $vehicle->license_plate; ?></td>
                              <td><?php echo $vehicle->vehicle_type; ?></td>
                              <td><?php echo $vehicle->capacity; ?> Tons</td>
                              <td>
                                  <div class="availability-days">
                                      <span class="day <?php echo isset($vehicle->available_days) && str_contains($vehicle->available_days, 'mon') ? 'active' : ''; ?>">M</span>
                                      <span class="day <?php echo isset($vehicle->available_days) && str_contains($vehicle->available_days, 'tue') ? 'active' : ''; ?>">T</span>
                                      <span class="day <?php echo isset($vehicle->available_days) && str_contains($vehicle->available_days, 'wed') ? 'active' : ''; ?>">W</span>
                                  </div>
                              </td>
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
                            <td colspan="5" class="text-center">No vehicles found</td>
                        </tr>
                    <?php endif; ?>
                  </tbody>
              </table>
          </div>
      </div>

      <!-- Vehicle Types Chart -->
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

    .chart-container {
        margin-top: 40px;
        padding: 20px;
        background: var(--light);
        border-radius: 20px;
        text-align: center;
        box-sizing: border-box;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .chart-wrapper {
        height: 300px;
        width: 100%;
        max-width: 600px; /* Limit width for better presentation */
        margin: 0 auto; /* Center the chart */
    }

    @media screen and (max-width: 768px) {
        .chart-container {
            padding: 15px;
        }
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vehicle Types Chart
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
                        '#007664',       // Primary color - #007664
                        '#cbeae5', // Light main color - #cbeae5
                        '#ffce26',     // Yellow - #ffce26
                        '#8d9f2d',      // Green - #8d9f2d
                        '#edf5c2' // Light green - #edf5c2
                    ],
                    borderWidth: 0,
                    borderColor: 'var(--light)' // White border for contrast
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'var(--dark)',  // Dark text for readability
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Available Vehicles by Type',
                        color: 'var(--dark)',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
    });
  </script>

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

  <!-- Vehicle details modal -->
  <div id="vehicleDetailsModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Vehicle Details</h2>
      <div id="vehicleDetailsContent"></div>
    </div>
  </div>



<script>
document.getElementById('editVehicleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const vehicleId = document.getElementById('edit_vehicle_select').value;
    if (!vehicleId) {
        alert('Please select a vehicle');
        return;
    }

    // Create data object
    const data = {
        vehicle_id: vehicleId,
        license_plate: document.getElementById('edit_license_plate').value,
        status: document.getElementById('edit_status').value,
        vehicle_type: document.getElementById('edit_vehicle_type').value,
        owner_name: document.getElementById('edit_owner_name').value,
        owner_contact: document.getElementById('edit_owner_contact').value,
        capacity: document.getElementById('edit_capacity').value,
        seating_capacity: document.getElementById('edit_seating_capacity').value,
        insurance_expiry_date: document.getElementById('edit_insurance_expiry_date').value,
        road_tax_expiry_date: document.getElementById('edit_road_tax_expiry_date').value,
        color: document.getElementById('edit_color').value,
        engine_number: document.getElementById('edit_engine_number').value,
        chassis_number: document.getElementById('edit_chassis_number').value,
        condition: document.getElementById('edit_condition').value,
        last_serviced_date: document.getElementById('edit_last_serviced_date').value,
        last_maintenance: document.getElementById('edit_last_maintenance').value,
        next_maintenance: document.getElementById('edit_next_maintenance').value,
        mileage: document.getElementById('edit_mileage').value,
        fuel_type: document.getElementById('edit_fuel_type').value,
        registration_date: document.getElementById('edit_registration_date').value
    };

    // Log the data being sent
    console.log('Sending data:', data);

    // Send AJAX request
    fetch('<?php echo URLROOT; ?>/vehiclemanager/updateVehicle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        // First check if the response is ok
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Server response: ' + text);
            });
        }
        return response.json();
    })
    .then(responseData => {
        console.log('Server response:', responseData);
        if (responseData.success) {
            alert('Vehicle updated successfully');
            location.reload();
        } else {
            alert(responseData.message || 'Failed to update vehicle');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the vehicle: ' + error.message);
    });
});

// Add this code to handle the edit form population
document.getElementById('edit_vehicle_select').addEventListener('change', function() {
    const vehicleId = this.value;
    const form = document.getElementById('editVehicleForm');
    
    // Get all form inputs except the vehicle select dropdown
    const formInputs = form.querySelectorAll('input, select:not(#edit_vehicle_select)');
    
    if (!vehicleId) {
        // If no vehicle selected, disable and clear all fields
        formInputs.forEach(input => {
            input.disabled = true;
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });
        return;
    }

    // Enable all fields if a vehicle is selected
    formInputs.forEach(input => {
        input.disabled = false;
    });

    // Find the selected vehicle data
    const vehicle = vehicles.find(v => v.vehicle_id == vehicleId);
    if (vehicle) {
        // Populate form fields
        document.getElementById('edit_license_plate').value = vehicle.license_plate || '';
        document.getElementById('edit_status').value = vehicle.status || '';
        document.getElementById('edit_vehicle_type').value = vehicle.vehicle_type || '';
        document.getElementById('edit_owner_name').value = vehicle.owner_name || '';
        document.getElementById('edit_owner_contact').value = vehicle.owner_contact || '';
        document.getElementById('edit_capacity').value = vehicle.capacity || '';
        document.getElementById('edit_seating_capacity').value = vehicle.seating_capacity || '';
        document.getElementById('edit_insurance_expiry_date').value = vehicle.insurance_expiry_date ? vehicle.insurance_expiry_date.split(' ')[0] : '';
        document.getElementById('edit_road_tax_expiry_date').value = vehicle.road_tax_expiry_date ? vehicle.road_tax_expiry_date.split(' ')[0] : '';
        document.getElementById('edit_color').value = vehicle.color || '';
        document.getElementById('edit_engine_number').value = vehicle.engine_number || '';
        document.getElementById('edit_chassis_number').value = vehicle.chassis_number || '';
        document.getElementById('edit_condition').value = vehicle.condition || '';
        document.getElementById('edit_last_serviced_date').value = vehicle.last_serviced_date ? vehicle.last_serviced_date.split(' ')[0] : '';
        document.getElementById('edit_last_maintenance').value = vehicle.last_maintenance ? vehicle.last_maintenance.split(' ')[0] : '';
        document.getElementById('edit_next_maintenance').value = vehicle.next_maintenance ? vehicle.next_maintenance.split(' ')[0] : '';
        document.getElementById('edit_mileage').value = vehicle.mileage || '';
        document.getElementById('edit_fuel_type').value = vehicle.fuel_type || '';
        document.getElementById('edit_registration_date').value = vehicle.registration_date ? vehicle.registration_date.split(' ')[0] : '';
    }
});

// Initially disable all form fields except the vehicle select dropdown
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editVehicleForm');
    const formInputs = form.querySelectorAll('input, select:not(#edit_vehicle_select)');
    
    formInputs.forEach(input => {
        input.disabled = true;
    });
});
</script>

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

    // Pass PHP data to JavaScript
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
              <p><strong>Capacity:</strong> ${vehicle.capacity || 'N/A'} Tons</p>
              <p><strong>Owner:</strong> ${vehicle.owner_name || 'N/A'}</p>
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
                  <img src="https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg" 
                       alt="Vehicle Image">
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
                          <span class="value">${vehicle.capacity} Tons</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Fuel Type:</span>
                          <span class="value">${vehicle.fuel_type}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Maintenance</h3>
                      <div class="detail-row">
                          <span class="label">Last Maintenance:</span>
                          <span class="value">${vehicle.last_maintenance || 'N/A'}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Next Maintenance:</span>
                          <span class="value">${vehicle.next_maintenance || 'N/A'}</span>
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

<style>
    .table-chart-container {
        display: grid;
        grid-template-columns: 2fr 1fr; /* Adjust ratio as needed */
        gap: 24px;
        margin-top: 36px;
    }

    .table-data {
        background: var(--light);
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .chart-container {
        background: var(--light);
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .chart-wrapper {
        height: 300px;
        width: 100%;
        margin: 0 auto;
    }

    @media screen and (max-width: 1200px) {
        .table-chart-container {
            grid-template-columns: 1fr;
        }
        
        .chart-container {
            margin-top: 0;
        }
    }
</style>

<style>
    .availability-days {
        display: flex;
        gap: 4px;
        justify-content: center;
    }

    .availability-days .day {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--grey);
        color: var(--dark-grey);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }

    .availability-days .day.active {
        background: var(--main);
        color: var(--light);
    }
</style>

<style>
    /* Update the table styles */
    .table-data table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-data table th,
    .table-data table td {
        padding: 12px;
        text-align: left;
        vertical-align: middle; /* This ensures vertical centering */
    }

    /* Center specific columns */
    .table-data table th:nth-child(3),
    .table-data table td:nth-child(3),
    .table-data table th:nth-child(4),
    .table-data table td:nth-child(4),
    .table-data table th:nth-child(5),
    .table-data table td:nth-child(5) {
        text-align: center;
    }

    /* Update availability days styles */
    .availability-days {
        display: inline-flex; /* Changed to inline-flex */
        gap: 8px;
        justify-content: center;
        align-items: center;
        padding: 4px 0;
    }

    .availability-days .day {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--grey);
        color: var(--dark-grey);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .availability-days .day.active {
        background: var(--main);
        color: var(--light);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Status badge alignment */
    .status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        text-align: center;
        min-width: 90px;
    }
</style>

</main>
<!-- MAIN -->
<!-- MAIN -->
</section>
<!-- CONTENT -->

<?php require APPROOT . '/views/inc/components/footer.php'; ?>