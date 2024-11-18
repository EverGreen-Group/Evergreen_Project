<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main class="fertilizer-requests-container">
        <div class="head-title">
                <div class="left">
                    <h1>Fertilizer Management</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplementDashboard.html">Home</a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="#">Fertilizer Orders</a>
                        </li>
                    </ul>
                </div>
            </div>

                <!-- Box Info -->
            <ul class="box-info">
                <li>
                    <i class='bx bx-box'></i>
                    <span class="text">
                        <h3>4,450</h3>
                        <p>Total Stock</p>
                        <small>Available Fertilizer (kg)</small>
                    </span>
                </li>
                <li>
                    <i class='bx bx-cart'></i>
                    <span class="text">
                        <h3>18</h3>
                        <p>Pending Orders</p>
                        <small>Awaiting Approval</small>
                    </span>
                </li>
                <li>
                    <i class='bx bx-error-circle'></i>
                    <span class="text">
                        <h3>2</h3>
                        <p>Low Stock Alert</p>
                        <small>Below Minimum Level</small>
                    </span>
                </li>
            </ul>

            <!-- Fertilizer Inventory Section -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Fertilizer Inventory</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Fertilizer Type</th>
                                <th>Available Stock (kg)</th>
                                <th>Minimum Level</th>
                                <th>Status</th>
                                <th>Last Restocked</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>NPK Fertilizer</td>
                                <td>1,200</td>
                                <td>500</td>
                                <td><span class="status-badge success">In Stock</span></td>
                                <td>2024-02-15</td>
                                <td>
                                    <button class="btn-action" title="Update Stock">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Urea</td>
                                <td>450</td>
                                <td>500</td>
                                <td><span class="status-badge warning">Low Stock</span></td>
                                <td>2024-02-10</td>
                                <td>
                                    <button class="btn-action" title="Update Stock">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Organic Fertilizer</td>
                                <td>2,000</td>
                                <td>500</td>
                                <td><span class="status-badge success">In Stock</span></td>
                                <td>2024-02-20</td>
                                <td>
                                    <button class="btn-action" title="Update Stock">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Phosphate</td>
                                <td>300</td>
                                <td>400</td>
                                <td><span class="status-badge danger">Critical</span></td>
                                <td>2024-02-05</td>
                                <td>
                                    <button class="btn-action" title="Update Stock">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Fertilizer Constraints Section -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Fertilizer Request Constraints</h3>
                        <button class="btn-save" onclick="saveConstraints()">
                            <i class='bx bx-save'></i>
                            Save Changes
                        </button>
                    </div>
                    <div class="constraints-container">
                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Maximum Order Per Supplier</span>
                                <div class="constraint-input">
                                    <input type="number" id="maxOrder" value="20" min="0">
                                    <span class="unit">kg</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Maximum amount a supplier can request per order</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Minimum Stock Level</span>
                                <div class="constraint-input">
                                    <input type="number" id="minStock" value="500" min="0">
                                    <span class="unit">kg</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Alert threshold for low stock warning</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Order Frequency Limit</span>
                                <div class="constraint-input">
                                    <select id="orderFrequency">
                                        <option value="7">Weekly</option>
                                        <option value="14">Bi-weekly</option>
                                        <option value="30" selected>Monthly</option>
                                        <option value="90">Quarterly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>How often a supplier can place new orders</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Quantity per Acre</span>
                                <div class="constraint-input">
                                    <input type="number" id="qtyPerAcre" value="2.5" step="0.5" min="0">
                                    <span class="unit">kg</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Recommended fertilizer amount per acre of land</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Previous Supply Window</span>
                                <div class="constraint-input">
                                    <input type="number" id="supplyWindow" value="30" min="0">
                                    <span class="unit">days</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Check previous supply within this time period</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fertilizer Orders Table -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Fertilizer Orders</h3>
                    </div>
                    <div class="table-container">
                        <table class="table-body">
                            <thead>
                                <tr>
                                    <th>Supplier ID</th>
                                    <th>Phone No</th>
                                    <th>Fertilizer Type</th>
                                    <th>Quantity</th>
                                    <th>Accept/Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Supplier 000231<br><small>3 Minutes ago</small></td>
                                    <td>718360733</td>
                                    <td>NPK Fertilizer</td>
                                    <td>20Kg</td>
                                    <td>
                                        <button class="accept-btn">Accept</button>
                                        <button class="reject-btn">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Supplier 020331<br><small>13 days ago, 12:20 PM</small></td>
                                    <td>718090733</td>
                                    <td>Urea</td>
                                    <td>20Kg</td>
                                    <td>
                                        <button class="accept-btn">Accept</button>
                                        <button class="reject-btn">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Supplier 028001<br><small>2 weeks ago, 09:15 PM</small></td>
                                    <td>77607533</td>
                                    <td>Organic Fertilizer</td>
                                    <td>20Kg</td>
                                    <td>
                                        <button class="accept-btn">Accept</button>
                                        <button class="reject-btn">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Supplier 004473<br><small>2 weeks ago, 09:15 PM</small></td>
                                    <td>718360762</td>
                                    <td>NPK Fertilizer</td>
                                    <td>20Kg</td>
                                    <td>
                                        <button class="accept-btn">Accept</button>
                                        <button class="reject-btn">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Supplier 000939<br><small>3 weeks ago, 09:13 PM</small></td>
                                    <td>718319523</td>
                                    <td>Phosphate</td>
                                    <td>20Kg</td>
                                    <td>
                                        <button class="accept-btn">Accept</button>
                                        <button class="reject-btn">Reject</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    <style>
    .constraints-container {
        padding: 1.5rem;
        background: #fff;
    }

    .constraint-group {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .constraint-group:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .constraint-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .constraint-label {
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .constraint-input {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .constraint-input input,
    .constraint-input select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 120px;
        font-size: 0.9rem;
    }

    .constraint-input .unit {
        color: #666;
        font-size: 0.9rem;
    }

    .constraint-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.85rem;
        margin-left: 1rem;
    }

    .constraint-info i {
        color: var(--main);
        font-size: 1rem;
    }

    .btn-save {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: var(--main);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-save:hover {
        background-color: var(--main-dark);
    }

    .accept-btn, .reject-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .accept-btn {
        background-color: #e8f5e9;
        color: #2e7d32;
        margin-right: 8px;
    }

    .accept-btn:hover {
        background-color: #2e7d32;
        color: white;
    }

    .reject-btn {
        background-color: #ffebee;
        color: #c62828;
    }

    .reject-btn:hover {
        background-color: #c62828;
        color: white;
    }

    /* Optional: Add disabled state styles */
    .accept-btn:disabled, .reject-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Optional: Add active state styles */
    .accept-btn:active, .reject-btn:active {
        transform: scale(0.98);
    }
    </style>
    <script>
    function saveConstraints() {
        // Collect all constraint values
        const constraints = {
            maxOrder: document.getElementById('maxOrder').value,
            minStock: document.getElementById('minStock').value,
            orderFrequency: document.getElementById('orderFrequency').value,
            qtyPerAcre: document.getElementById('qtyPerAcre').value,
            supplyWindow: document.getElementById('supplyWindow').value
        };

        // TODO: Add your AJAX call here to save to backend
        console.log('Saving constraints:', constraints);
        
        // Show success message
        alert('Constraints saved successfully!');
    }
    </script>
    </body>
</html>