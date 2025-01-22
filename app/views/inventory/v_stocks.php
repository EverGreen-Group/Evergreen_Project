<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection_bags/styles.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/stocks.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/collection_bags.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/inventory_manager/stocks.js"></script>

<!-- MAIN -->
<main>
  <!-- Vehicle Management Section -->
    <div class="head-title">
        <div class="left">
            <h1>Tea Leaf Inventory</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

  <div class="action-buttons">
      <button class="btn btn-primary" onclick="addStockModal()">
          <i class='bx bx-plus'></i>
          Add Stocks
      </button>
  </div>

  <ul class="box-info">
    <li>
      <i class='bx bxs-shopping-bag'></i>
        <span class="text">
          <p>Total Stocks</p>
          <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
          <p>Total Received Last Month</p>
          <h3><?php echo isset($data['availableVehicles']) ? $data['availableVehicles'] : '0'; ?></h3>
        </span>
    </li>
    <li>
      <i class='bx bxs-shopping-bag'></i>
        <span class="text">
          <p>Total In Processing</p>
          <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
        </span>
    </li>
  </ul>


  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Tea Stock Overview</h3>
        </div>
        <table id="teaStockTable">
            <thead>
                <tr>
                    <th>Tea Type</th>
                    <th>Total Stock</th>
                    <th>Last Production</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Black Tea</td>
                    <td>5000 kg</td>
                    <td>2023-10-01</td>
                    <td>
                        <div style="display: flex; justify-content: center; margin-right: 80px; gap: 30px;">
                            <button class="btn btn-primary" onclick="viewStockModal()">View</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Green Tea</td>
                    <td>3000 kg</td>
                    <td>2023-09-15</td>
                    <td>
                        <div style="display: flex; justify-content: center; margin-right: 80px; gap: 30px;">
                            <button class="btn btn-primary" onclick="viewStockModal()">View</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Herbal Tea</td>
                    <td>2000 kg</td>
                    <td>2023-08-20</td>
                    <td>
                        <div style="display: flex; justify-content: center; margin-right: 80px; gap: 30px;">
                            <button class="btn btn-primary" onclick="viewStockModal()">View</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Oolong Tea</td>
                    <td>2000 kg</td>
                    <td>2023-08-20</td>
                    <td>
                        <div style="display: flex; justify-content: center; margin-right: 80px; gap: 30px;">
                            <button class="btn btn-primary" onclick="viewStockModal()">View</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Driver Status Chart -->
    <div class="order">
        <div class="head">
            <h3>Available Tea Leaf Stock</h3>
            <i class='bx bx-shopping-bag'></i>
        </div>
        <div class="chart-container-wrapper">
            <canvas id="reportTypesChart"></canvas>
        </div>
        <div class="legend-container">
            <div class="legend-wrapper">
                <div class="legend-item">
                    <span class="legend-dot bg-blue"></span>
                    <span class="legend-text">Black Tea</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-red"></span>
                    <span class="legend-text">Green Tea</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-yellow"></span>
                    <span class="legend-text">Herbal Tea</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-purple"></span>
                    <span class="legend-text">Oolong Tea</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="viewStockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewStockModal')">&times;</span>
        <h2>Black Tea Stock Details</h2>
        <div class="stock-modal-content">
            <div class="stock-modal-details">
                <form id="blackTeaStockForm">
                    <!-- Total Stock -->

                    <!-- Grading Breakdown Table -->
                    <div class="detail-group">
                        <h3>Grading Breakdown</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Stock</th>
                                    <th>Last Added</th>
                                    <th>Last Deducted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BOPF</td>
                                    <td>3000 kg</td>
                                    <td>2023-10-01</td>
                                    <td>2023-09-15</td>
                                </tr>
                                <tr>
                                    <td>FBOP</td>
                                    <td>1500 kg</td>
                                    <td>2023-09-20</td>
                                    <td>2023-09-10</td>
                                </tr>
                                <tr>
                                    <td>Dust</td>
                                    <td>500 kg</td>
                                    <td>2023-08-30</td>
                                    <td>2023-08-25</td>
                                </tr>
                                <tr>
                                    <td>OP (Orange Pekoe)</td>
                                    <td>800 kg</td>
                                    <td>2023-09-25</td>
                                    <td>2023-09-18</td>
                                </tr>
                                <tr>
                                    <td>FOP (Flowery Orange Pekoe)</td>
                                    <td>700 kg</td>
                                    <td>2023-09-22</td>
                                    <td>2023-09-16</td>
                                </tr>
                                <tr>
                                    <td>GFOP (Golden Flowery Orange Pekoe)</td>
                                    <td>600 kg</td>
                                    <td>2023-09-15</td>
                                    <td>2023-09-12</td>
                                </tr>
                                <tr>
                                    <td>BOP (Broken Orange Pekoe)</td>
                                    <td>1200 kg</td>
                                    <td>2023-10-05</td>
                                    <td>2023-09-30</td>
                                </tr>
                                <tr>
                                    <td>PD (Pekoe Dust)</td>
                                    <td>400 kg</td>
                                    <td>2023-09-28</td>
                                    <td>2023-09-20</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong>9700 kg</strong></td> <!-- Updated total -->
                                    <td colspan="2"></td> <!-- Empty cells for last added and deducted -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Stock Change Logs</h3>
        </div>
        <table id="stockChangeLogsTable">
            <thead>
                <tr>
                    <th>Inventory Manager</th>
                    <th>Action</th>
                    <th>Stock Type</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>Added</td>
                    <td>Black Tea</td>
                    <td>1000 kg</td>
                    <td>New shipment received</td>
                    <td>2023-10-05</td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>Deducted</td>
                    <td>Green Tea</td>
                    <td>500 kg</td>
                    <td>Quality check failure</td>
                    <td>2023-10-06</td>
                </tr>
            </tbody>
        </table>
    </div>  
</div>


<div id="addStockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addStockModal')">&times;</span>
        <h2>Add Stock</h2>
        <div class="stock-modal-content">
            <div class="vehicle-modal-content">
                <div class="vehicle-modal-details">
                    <div class="detail-group">
                        <h3>Stock Information</h3>
                        <div class="detail-row">
                            <span class="label">Stock ID:</span>
                            <span class="value">23</span> <!-- Hardcoded for now -->
                        </div>
                        <div class="detail-row">
                            <span class="label">Tea Type:</span>
                            <span class="value">
                                <select id="teaType" name="tea_type" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                                    <option value="">Select a Tea Type</option>
                                    <option value="Black Tea">Black Tea</option>
                                    <option value="Green Tea">Green Tea</option>
                                    <option value="Herbal Tea">Herbal Tea</option>
                                    <option value="Oolong Tea">Oolong Tea</option>
                                </select>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Grade:</span>
                            <span class="value">
                                <select id="grading" name="grading" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                                    <option value="">Select a Grading</option>
                                    <!-- Options will be populated based on tea type selection -->
                                </select>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Quantity (kg):</span>
                            <span class="value">
                                <input type="number" id="quantity" name="quantity" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Notes:</span>
                            <span class="value">
                                <textarea id="notes" name="notes" rows="3" style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
                            </span>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary full-width" onclick="addStock(event)">ADD STOCK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateGradingOptions() {
    const teaType = document.getElementById('teaType').value;
    const gradingSelect = document.getElementById('grading');

    // Clear existing options
    gradingSelect.innerHTML = '<option value="">Select a Grading</option>';

    // Define grading options based on tea type
    let gradingOptions = [];
    if (teaType === 'Black Tea') {
        gradingOptions = ['BOPF', 'FBOP', 'Dust', 'OP (Orange Pekoe)', 'FOP (Flowery Orange Pekoe)'];
    } else if (teaType === 'Green Tea') {
        gradingOptions = ['Sencha', 'Matcha', 'Gyokuro'];
    } else if (teaType === 'Herbal Tea') {
        gradingOptions = ['Chamomile', 'Peppermint', 'Rooibos'];
    } else if (teaType === 'Oolong Tea') {
        gradingOptions = ['Tieguanyin', 'Da Hong Pao'];
    }

    // Populate grading select options
    gradingOptions.forEach(function(grading) {
        const option = document.createElement('option');
        option.value = grading;
        option.textContent = grading;
        gradingSelect.appendChild(option);
    });
}
</script>

</main>



<style>


</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>

