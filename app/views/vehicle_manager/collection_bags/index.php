<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection_bags/styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/collection_bags.js"></script>


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
      <button class="btn btn-primary" onclick="openAddBagModal()">
          <i class='bx bx-plus'></i>
          Add New Bag
      </button>

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
          <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
          <p>Currently In Use</p>
          <h3><?php echo isset($data['availableVehicles']) ? $data['availableVehicles'] : '0'; ?></h3>
        </span>
    </li>
  </ul>


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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>COL001</td>
                        <td>Route A</td>
                        <td>Driver 1</td>
                        <td>3</td>
                        <td>
                            <button class="btn btn-view" onclick="showCollectionBagDetails('COL001', 'Route A', 'Driver 1', 3)">
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
                            <button class="btn btn-view" onclick="showCollectionBagDetails('COL002', 'Route B', 'Driver 2', 2)">
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


    <div id="collectionBagDetailsModal" class="modal" onclick="closeModal('collectionBagDetailsModal')">
        <div class="modal-content" onclick="event.stopPropagation();">
            <span class="close" onclick="closeModal('collectionBagDetailsModal')">&times;</span>
            <h2>Bag Details</h2>
            <div id="collectionBagDetailsContent">
                <!-- Bag details will be populated here -->
            </div>
        </div>
    </div>




</main>


</section>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>