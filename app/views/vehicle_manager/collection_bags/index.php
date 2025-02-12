<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection_bags/styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/collection_bags.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>


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
      <table id="bagsTable">
        <thead>
          <tr>
            <th>Bag ID</th>
            <th>Capacity</th>
            <th>Bag Weight</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            <tr>
                <td>Bag001</td>
                <td>50 kg</td>
                <td>2 kg</td>
                <td>
                    <div style="display: flex; justify-content: flex-end; margin-right: 80px; gap: 30px;">
                        <button class="btn btn-primary" onclick="showBagDetails(50)">View</button>
                        <button class="btn btn-secondary" onclick="openUpdateBagModal('Bag001')">Update</button>
                        <button class="btn btn-tertiary" onclick="removeBag('Bag001')">Remove</button>
                    </div>
                </td>

            </tr>
        </tbody>
      </table>
    </div>

    <!-- Driver Status Chart -->
    <div class="order">
        <div class="head">
            <h3>Bag Status Distribution</h3>
            <i class='bx bx-shopping-bag'></i>
        </div>
        <div class="chart-container-wrapper">
            <canvas id="reportTypesChart"></canvas>
        </div>
        <div class="legend-container">
            <div class="legend-wrapper">
                <div class="legend-item">
                    <span class="legend-dot bg-blue"></span>
                    <span class="legend-text">Bags Used</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-red"></span>
                    <span class="legend-text">Bags Not Used</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-yellow"></span>
                    <span class="legend-text">Bags In Processing</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-purple"></span>
                    <span class="legend-text">Bags In Transit</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Bags In Use</h3>
            <i class='bx bx-shopping-bag'></i>
        </div>
        <div class="bags-grid">
            <!-- Bag Card 1 -->
            <div class="bag-card" onclick="showBagCardDetails('BAG001', '50kg', '2kg', 'Processing', 'Route A')">
                <div class="bag-icon">
                    <i class='bx bx-shopping-bag'></i>
                </div>
                <div class="bag-info">
                    <h4>BAG001</h4>
                    <p>Capacity: 50kg</p>
                    <span class="status processing">Processing</span>
                </div>
            </div>

            <!-- Bag Card 2 -->
            <div class="bag-card" onclick="showBagCardDetails('BAG002', '45kg', '2kg', 'In Transit', 'Route B')">
                <div class="bag-icon">
                    <i class='bx bx-shopping-bag'></i>
                </div>
                <div class="bag-info">
                    <h4>BAG002</h4>
                    <p>Capacity: 45kg</p>
                    <span class="status transit">In Transit</span>
                </div>
            </div>

            <!-- Bag Card 3 -->
            <div class="bag-card" onclick="showBagCardDetails('BAG003', '55kg', '2kg', 'Used', 'Route C')">
                <div class="bag-icon">
                    <i class='bx bx-shopping-bag'></i>
                </div>
                <div class="bag-info">
                    <h4>BAG003</h4>
                    <p>Capacity: 55kg</p>
                    <span class="status used">Used</span>
                </div>
            </div>

            <!-- Add more bag cards as needed -->
        </div>
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

<div id="bagCardDetailsModal" class="modal" onclick="closeModal('bagCardDetailsModal')">
    <div class="modal-content" onclick="event.stopPropagation();">
        <span class="close" onclick="closeModal('bagCardDetailsModal')">&times;</span>
        <h2>Bag Details</h2>
        <div id="bagCardDetailsContent">
            <!-- Bag card details will be populated here -->
        </div>
    </div>
</div>
    




</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Report Types Chart
    const reportCtx = document.getElementById('reportTypesChart');
    if (reportCtx) {
        new Chart(reportCtx, {
            type: 'doughnut',
            data: {
                labels: ['Bags Used', 'Bags Not Used', 'Bags In Processing', 'Bags In Transit'],
                datasets: [{
                    data: [5, 3, 4, 2],
                    backgroundColor: [
                        '#FF9F40',
                        '#4BC0C0',
                        '#36A2EB',
                        '#9966FF'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Keep the existing driver status chart code as is
    // ...
});
</script>


<style>
.chart-container-wrapper {
    height: 250px;
}

.legend-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    margin-top: 10px;
}

.legend-wrapper {
    display: flex;
    gap: 15px;
}

.legend-item {
    display: flex;
    align-items: center;
}

.legend-dot {
    width: 12px;
    height: 12px;
    margin-right: 5px;
    border-radius: 2px;
}

.legend-text {
    font-size: 0.9em;
}

.bg-blue {
    background-color: rgba(54, 162, 235, 0.6);
}

.bg-red {
    background-color: rgba(255, 99, 132, 0.6);
}

.bg-yellow {
    background-color: rgba(255, 206, 86, 0.6);
}

.bg-purple {
    background-color: rgba(153, 102, 255, 0.6);
}

.bags-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    padding: 20px;
}

.bag-card {
    background: #fff;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.bag-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.bag-icon {
    text-align: center;
    margin-bottom: 10px;
}

.bag-icon i {
    font-size: 2.5em;
    color: var(--main);
}

.bag-info {
    text-align: center;
}

.bag-info h4 {
    margin: 0;
    color: #342E37;
    font-size: 1.1em;
}

.bag-info p {
    margin: 5px 0;
    color: #666;
    font-size: 0.9em;
}

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 500;
}

.status.processing {
    background-color: #FF9020;
    color: white;
}

.status.transit {
    background-color: var(--green);
    color: white;
}

.status.used {
    background-color: var(--mainn);
    color: white;
}
</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>

