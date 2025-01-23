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
<script src="<?php echo URLROOT; ?>/public/js/inventory_manager/process.js"></script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Raw Tea Leaves</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn btn-primary" onclick="openAddStockModal()">
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
                <h3>Raw Tea Leaves Supply</h3>
                <i class='bx bx-shopping-bag'></i>
            </div>
            <div class="chart-container-wrapper" style="position:relative; width:100%; height:300px; padding:20px;">
                <canvas id="reportTypesChart"></canvas>
            </div>
        </div>


        <div class="order">
            <div class="head">
                <h3>Raw Tea Leaves</h3>
                <i class='bx bx-shopping-bag'></i>
            </div>
            <div class="chart-container-wrapper">
                <canvas id="reportTypesChart2"></canvas>
            </div>
            <div class="legend-container">
                <div class="legend-wrapper">
                    <!-- Legend items will be populated here dynamically -->
                </div>
            </div>
        </div>
    </div>



    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Batch Log</h3>
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
                                <span class="label">Tea Type:</span>
                                <span class="value">
                                    <select id="teaType" name="tea_type" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                                        <option value="">Select a Tea Type</option>
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

</main>



<style>


</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>

