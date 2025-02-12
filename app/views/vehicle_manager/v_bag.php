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
          <h1>Collection Bags</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/addBag" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Add New Bag
      </a>

      <a href="<?php echo URLROOT; ?>/vehiclemanager/updateBag" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Update Bag Details
      </a>
  </div>

  <ul class="box-info">
    <li>
      <i class='bx bxs-shopping-bag'></i>
        <span class="text">
          <p>Total Bags</p>
          <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '20'; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
          <p>Currently In Use</p>
          <h3><?php echo isset($data['availableVehicles']) ? $data['availableVehicles'] : '5'; ?></h3>
        </span>
    </li>
  </ul>


  <?php flash('vehicle_message'); ?>
  <!-- New section for vehicle cards -->
  <!-- <div class="vehicle-cards-section">
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


  <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Collection Bags Usage</h3>
                <i class='bx bx-leaf'></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Route</th>
                        <th>Driver</th>
                        <th>No of Bags</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>COL001</td>
                        <td>Route A</td>
                        <td>Driver 1</td>
                        <td>3</td>
                        <td>
                            <button class="btn btn-view" onclick="showCollectionBagDetails()">
                                <i class='bx bx-show'></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>COL002</td>
                        <td>Route B</td>
                        <td>Driver 2</td>
                        <td>2</td>
                        <td>
                            <button class="btn btn-view" onclick="showCollectionBagDetails({
                                collection_id: 'COL002',
                                route: 'Route B',
                                driver: 'Driver 2',
                                no_of_bags: 2
                            })">
                                <i class='bx bx-show'></i>
                            </button>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="order">
          <div class="head">
              <h3>Bag Usage</h3>
          </div>
          <div class="chart-container-wrapper">
              <canvas id="bagUsageChart" width="300" height="300" style="max-width: 100%; height: auto;"></canvas>
          </div>
          <div class="color-legend">
              <div><span style="background-color: rgba(54, 162, 235, 0.6);"></span> Bags Used</div>
              <div><span style="background-color: rgba(255, 99, 132, 0.6);"></span> Bags Not Used</div>
              <div><span style="background-color: rgba(255, 206, 86, 0.6);"></span> Bags In Processing</div>
          </div>
      </div>
    </div>

  <!-- Vehicle Information Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Bags Availability</h3>
        <div class="filter-container">
                    <label for="dayFilter">Filter Bags:</label>
                    <select id="dayFilter">
                        <option value="all">All Bags</option>
                        <option value="Monday">Avalable</option>
                        <option value="Tuesday">In Use</option>
                        <option value="Wednesday">Processing</option>
                    </select>
                </div>
        <i class='bx bx-search'></i>
      </div>
      <table>
        <thead>
          <tr>
            <th>Bag ID</th>
            <th>Bag Type</th>
            <th>Capacity</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>
            <tr>
                <td>Bag001</td>
                <td>Type A</td>
                <td>50 kg</td>
                <td>
                    <button class="btn btn-primary" onclick="showBagDetails('Bag001', 'Type A', 50, 45, 'Normal Leaf', '2023-10-01', 'COL001', 'Driver A', 12, 5, 45, 50, 30)">View</button>
                </td>
            </tr>
            <tr>
                <td>Bag002</td>
                <td>Type B</td>
                <td>70 kg</td>
                <td>
                    <button class="btn btn-primary" onclick="showBagDetails('Bag002', 'Type B', 70)">View</button>
                </td>
            </tr>
            <tr>
                <td>Bag003</td>
                <td>Type C</td>
                <td>60 kg</td>
                <td>
                    <button class="btn btn-primary" onclick="showBagDetails('Bag003', 'Type C', 60)">View</button>
                </td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>





  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



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


<!-- This part is needed for the modal style ok -->
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
    flex-grow: 0;
}

.status-badge.available {
    background: #e8f5e9;
    color: #2e7d32;
    max-width: 200px;
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






<script>
// Update the container styling first
const style = document.createElement('style');
style.textContent = `
.order .head {
    margin-bottom: 15px;
}

/* Create a container with fixed dimensions for the chart */
.chart-container-wrapper {
    width: 200px;  /* Fixed width */
    height: 200px; /* Fixed height */
    margin: 0 auto; /* Center the chart */
    position: relative;
}

#bagUsageChart {
    max-width: 100% !important;
    max-height: 100% !important;
    width: 200px !important;
    height: 200px !important;
}
`;
document.head.appendChild(style);

// Update the chart initialization code
document.addEventListener('DOMContentLoaded', function() {
    const bagsUsed = 4;
    const bagsNotUsed = 13;
    const bagsInProcessing = 5;

    // Wrap the canvas in a div with controlled dimensions
    const canvas = document.getElementById('bagUsageChart');
    const wrapper = document.createElement('div');
    wrapper.className = 'chart-container-wrapper';
    canvas.parentNode.insertBefore(wrapper, canvas);
    wrapper.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    const bagUsageChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Bags Used', 'Bags Not Used', 'Bags In Processing'],
            datasets: [{
                label: 'Bag Usage',
                data: [bagsUsed, bagsNotUsed, bagsInProcessing],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false // Remove legend to save space
                },
                title: {
                    display: false // Remove title to save space
                }
            },
            layout: {
                padding: 0 // Remove padding
            }
        }
    });
});
</script>

<style>
    .color-legend {
        display: flex;
        flex-direction: column;
        margin-top: 10px;
    }

    .color-legend div {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .color-legend span {
        width: 20px;
        height: 20px;
        border-radius: 3px;
        margin-right: 10px;
    }
</style>

<style>
.btn {
    padding: 5px 10px; /* Adjust padding for better fit */
    border: none; /* Remove border */
    border-radius: 4px; /* Slightly round the corners */
    color: white; /* Text color */
    cursor: pointer; /* Change cursor to pointer */
    display: inline-flex; /* Use flexbox for alignment */
    align-items: center; /* Center items vertically */
    justify-content: center; /* Center items horizontally */
    margin-right: 5px; /* Space between buttons */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.btn-view {
    background-color: var(--main); /* Use your theme color for view */
}

.btn-view:hover {
    background-color: darken(var(--main), 10%); /* Darker shade on hover */
}

.btn-accept {
    background-color: #4CAF50; /* Green for accept */
}

.btn-accept:hover {
    background-color: #45a049; /* Darker green on hover */
}

.btn-decline {
    background-color: #f44336; /* Red for decline */
}

.btn-decline:hover {
    background-color: #da190b; /* Darker red on hover */
}

.btn i {
    font-size: 18px; /* Adjust icon size */
}
</style>

<script>
function showCollectionBagDetails() {
    const content = document.getElementById('collectionBagDetailsContent');

    // Hardcoded values for demonstration
    const collectionBag = {
        collection_id: 'COL001',
        route: 'Route A',
        driver: 'Driver 1',
        suppliers: [
            {
                name: 'Supplier A',
                bags: [
                    { name: 'Bag 1', capacity: 50, filledAmount: 30, detailsUrl: 'bag_details.php?id=1' },
                    { name: 'Bag 2', capacity: 70, filledAmount: 50, detailsUrl: 'bag_details.php?id=2' }
                ]
            },
            {
                name: 'Supplier B',
                bags: [
                    { name: 'Bag 3', capacity: 60, filledAmount: 20, detailsUrl: 'bag_details.php?id=3' }
                ]
            }
        ],
        unassignedSuppliers: ['Supplier C', 'Supplier D'],
        unassignedBags: [
            { name: 'Bag 4', capacity: 40, detailsUrl: 'bag_details.php?id=4' },
            { name: 'Bag 5', capacity: 30, detailsUrl: 'bag_details.php?id=5' }
        ]
    };

    // Create tags for unassigned bags
    const unassignedBagTags = collectionBag.unassignedBags.map(bag => `
        <button class="tag-button" onclick="window.location.href='${bag.detailsUrl}'">
            ${bag.name} (Capacity: ${bag.capacity} kg)
        </button>
    `).join(' ');

    // Create table rows for assigned suppliers and their bags
    const supplierRows = collectionBag.suppliers.map(supplier => `
        <tr>
            <td>${supplier.name}</td>
            <td>${supplier.bags.map(bag => `
                <button class="tag-button" onclick="window.location.href='${bag.detailsUrl}'">
                    ${bag.name} (Capacity: ${bag.capacity} kg, Filled: ${bag.filledAmount} kg)
                </button>
            `).join(' ')}</td>
        </tr>
    `).join('');

    content.innerHTML = `
          <div class="vehicle-modal-content">
              <div class="vehicle-modal-details">
                  <div class="detail-group">
                      <h3>Collection Information</h3>
                      <div class="detail-row">
                          <span class="label">Collection ID:</span>
                          <span class="value">${collectionBag.collection_id}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Route:</span>
                          <span class="value">${collectionBag.route}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Driver:</span>
                          <span class="value">${collectionBag.driver}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Number of Suppliers:</span>
                          <span class="value">${collectionBag.suppliers.length}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Unassigned Suppliers</h3>
                      <div class="detail-row">
                          <span class="label">Suppliers:</span>
                          <span class="value">${collectionBag.unassignedSuppliers.join(', ')}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Unassigned Bags</h3>
                      <div class="detail-row">
                          <span class="label">Bags:</span>
                          <span class="value">${unassignedBagTags}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Assigned Suppliers and Their Bags</h3>
                      <table>
                          <thead>
                              <tr>
                                  <th>Supplier</th>
                                  <th>Assigned Bags</th>
                              </tr>
                          </thead>
                          <tbody>
                              ${supplierRows}
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
    `;
    document.getElementById('collectionBagDetailsModal').style.display = 'block';
}
</script>

<div id="collectionBagDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('collectionBagDetailsModal')">&times;</span>
        <h2>Bag Details</h2>
        <div id="collectionBagDetailsContent">
            <!-- Bag details will be populated here -->
        </div>
    </div>
</div>

<style>
.collection-bag-modal-content {
    padding: 20px;
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

/* Responsive adjustments */
@media screen and (max-width: 768px) {
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
.tag-button {
    background-color: #e0e0e0; /* Light gray background */
    border: none;
    border-radius: 4px;
    padding: 5px 10px;
    margin: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.tag-button:hover {
    background-color: #bdbdbd; /* Darker gray on hover */
}
</style>

<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 6000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto; /* Reduced top margin to 5% */
    padding: 20px;
    border: 1px solid #888;
    width: 85%; /* Increased width to 85% of the viewport */
    max-width: 1000px; /* Set a maximum width for larger screens */
    border-radius: 8px; /* Optional: round the corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: add shadow for depth */
}

/* Specific styles for the table inside the modal */
.modal-content table {
    width: 100%; /* Make the table take the full width of the modal */
    border-collapse: collapse; /* Collapse borders for a cleaner look */
}

.modal-content th, 
.modal-content td {
    padding: 10px; /* Add padding to table cells */
    text-align: left; /* Align text to the left */
    border: 1px solid #ddd; /* Add border to cells */
}

.modal-content th {
    background-color: #f2f2f2; /* Light gray background for header */
    font-weight: bold; /* Make header text bold */
}

.tag-button {
    background-color: #e0e0e0; /* Light gray background */
    border: none;
    border-radius: 4px;
    padding: 5px 10px; /* Adjust padding for buttons */
    margin: 2px; /* Add margin between buttons */
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 0.9em; /* Adjust font size for better fit */
}

.tag-button:hover {
    background-color: #bdbdbd; /* Darker gray on hover */
}
</style>

<script>
function showBagDetails(bagId, bagType, capacity, actualCapacity, leafType, lastModified, collectionId, driver, moisture, bagWeight, actualWeight, grossWeight, leafAge, assignedSupplier) {
    const content = document.getElementById('collectionBagDetailsContent');

    content.innerHTML = `
          <div class="vehicle-modal-content">
              <div class="vehicle-modal-image">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" />
              </div>
              <div class="vehicle-modal-details">
                  <div class="detail-group">
                      <h3>Basic Information</h3>
                      <div class="detail-row">
                          <span class="label">Bag ID:</span>
                          <span class="value">${bagId}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Bag Type:</span>
                          <span class="value">${bagType}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Status:</span>
                          <span class="value">Available</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Assigned Supplier:</span>
                          <span class="value">${assignedSupplier || 'N/A'}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Specifications</h3>
                      <div class="specifications-container">
                          <div class="specifications-left">
                              <div class="detail-row">
                                  <span class="label">Capacity:</span>
                                  <span class="value">${capacity} kg</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Actual Capacity:</span>
                                  <span class="value">${actualCapacity} kg</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Leaf Type:</span>
                                  <span class="value">${leafType}</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Last Modified:</span>
                                  <span class="value">${lastModified}</span>
                              </div>
                          </div>
                          <div class="specifications-right">
                              <div class="detail-row">
                                  <span class="label">Collection ID:</span>
                                  <span class="value">${collectionId}</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Added By Driver:</span>
                                  <span class="value">${driver}</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Moisture:</span>
                                  <span class="value">${moisture}%</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Bag Weight:</span>
                                  <span class="value">${bagWeight} kg</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    `;
    document.getElementById('collectionBagDetailsModal').style.display = 'block';
}
</script>

<style>
.specifications-container {
    display: flex; /* Use flexbox for layout */
    justify-content: space-between; /* Space between the two columns */
}

.specifications-left,
.specifications-right {
    flex: 1; /* Each column takes equal space */
    margin-right: 10px; /* Space between the columns */
}

.specifications-right {
    margin-right: 0; /* Remove margin from the right column */
}
</style>

<style>
/* Apply Poppins font to all h2 elements in modals */
.modal-content h2 {
    font-family: 'Poppins', sans-serif; /* Use Poppins font */
    font-weight: 600; /* Adjust weight as needed */
    margin: 0; /* Optional: remove default margin */
}
</style>

</main>


</section>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>