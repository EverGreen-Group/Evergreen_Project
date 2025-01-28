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
    <div class="head-title">
        <div class="left">
            <h1>Tea Leaf Inventory</h1>
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
                <h3>Tea Stock Overview</h3>
            </div>
            <table id="teaStockTable">
                <thead>
                    <tr>
                        <th>Tea Type</th>
                        <th>Types of Grading</th>
                        <th>Total Quantity</th>
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
                    <!-- Legend items will be populated here dynamically -->
                </div>
            </div>
        </div>
    </div>


    <div id="viewStockModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('viewStockModal')">&times;</span>
            <h2>Stock Details</h2>
            <div class="stock-modal-content">
                <div class="stock-modal-details">
                    <form id="stockDetailsForm">
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
                                    <!-- Dynamic rows will be populated here -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>0 kg</strong></td> <!-- Placeholder for total -->
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


    <div style="text-align: right; margin-bottom: 10px;">
        <button class="btn btn-primary" onclick="addBatch()">Add Batch</button>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Batch Process</h3>
            </div>
            <table id="batchLogsTable">
                <thead>
                    <tr>
                        <th>Batch ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Output</th>
                        <th>Total Wastage</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['batches'])): ?>
                        <?php foreach ($data['batches'] as $batch): ?>
                            <tr>
                                <td><?php echo $batch->batch_id; ?></td>
                                <td><?php echo $batch->start_time; ?></td>
                                <td><?php echo $batch->end_time ? $batch->end_time : 'N/A'; ?></td>
                                <td><?php echo $batch->total_output_kg . ' kg'; ?></td>
                                <td><?php echo $batch->total_wastage_kg . ' kg'; ?></td>
                                <td><?php echo $batch->created_at; ?></td>
                                <td><button class="btn btn-primary" onclick="openBatchDetailModal(<?php echo $batch->batch_id; ?>)">Manage</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No batches found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

    <div id="openBatchDetailModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('openBatchDetailModal')">&times;</span>
            <h2>Batch Details</h2>
            <div class="stock-modal-content">
                <div class="stock-modal-details">
                    <h3>Batch Information</h3>
                    <p><strong>Batch ID:</strong> <span id="batchIdDetail">1</span></p>
                    <p><strong>Start Time:</strong> <span id="startTimeDetail">2023-10-05 15:00</span></p>
                    <p><strong>Total Output:</strong> <span id="totalOutputDetail">1000 kg</span></p>
                    <p><strong>Total Wastage:</strong> <span id="totalWastageDetail">47 kg</span></p>
                    
                    <h3 style="margin-top:10px;">Ingredients Used</h3>
                    <button class="btn btn-primary" onclick="addIngredient()" style="margin-top:10px;margin-bottom:20px;">Add Ingredient</button>
                    <table>
                        <thead>
                            <tr>
                                <th>Ingredient ID</th>
                                <th>Leaf Type ID</th>
                                <th>Quantity Used (kg)</th>
                                <th>Added At</th>
                            </tr>
                        </thead>
                        <tbody id="ingredientDetails">

                            <!-- More rows can be added here -->
                        </tbody>
                    </table>

                    <h3 style="margin-top:10px;">Outputs</h3>
                    <button class="btn btn-primary" onclick="addOutput()" style="margin-top:10px;margin-bottom:20px;">Add Output</button>
                    <table>
                        <thead>
                            <tr>
                                <th>Processed ID</th>
                                <th>Leaf Type ID</th>
                                <th>Grading ID</th>
                                <th>Output (kg)</th>
                                <th>Processed At</th>
                            </tr>
                        </thead>
                        <tbody id="outputDetails">
                            <tr>
                                <td>1</td>
                                <td>101</td>
                                <td>201</td>
                                <td>800.00</td>
                                <td>2023-10-05 18:00</td>
                            </tr>
                            <!-- More rows can be added here -->
                        </tbody>
                    </table>

                    <h3 style="margin-top:10px;">Machine Usage</h3>
                    <button class="btn btn-primary" onclick="addMachineUsage()" style="margin-top:10px;margin-bottom:20px;">Add Machine Usage</button>
                    <table>
                        <thead>
                            <tr>
                                <th>Usage ID</th>
                                <th>Machine ID</th>
                                <th>Operator ID</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="machineUsageDetails">
                            <tr>
                                <td>1</td>
                                <td>301</td>
                                <td>401</td>
                                <td>2023-10-05 15:00</td>
                                <td>2023-10-05 17:00</td>
                                <td>Initial processing</td>
                            </tr>
                            <!-- More rows can be added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</main>

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


<style>


</style>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>

