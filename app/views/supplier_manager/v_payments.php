<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
        <div class="head-title">
                <div class="left">
                    <h1>Payment Management</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="#">Payments</a>
                        </li>
                    </ul>
                </div>
                <div class="head-actions">
                    <button class="btn-download" onclick="downloadPaymentReport()">
                        <i class='bx bx-download'></i> Export Report
                    </button>
                </div>
            </div>

            <!-- Today's Pending Payments -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Today's Pending Payments</h3>
                        <div class="head-info">
                            <span class="total-amount">Total: Rs. 125,450</span>
                            <span class="total-suppliers">15 Suppliers</span>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Name</th>
                                <th>Supply Time</th>
                                <th>Quantity (kg)</th>
                                <th>Est. Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SUP001</td>
                                <td>John Doe</td>
                                <td>08:30 AM</td>
                                <td>25</td>
                                <td>Rs. 2,875</td>
                                <td><span class="status processing">Processing</span></td>
                            </tr>
                            <tr>
                                <td>SUP014</td>
                                <td>Sarah Smith</td>
                                <td>09:15 AM</td>
                                <td>32</td>
                                <td>Rs. 3,680</td>
                                <td><span class="status processed">Processed</span></td>
                            </tr>
                            <tr>
                                <td>SUP023</td>
                                <td>Mike Johnson</td>
                                <td>10:00 AM</td>
                                <td>18</td>
                                <td>Rs. 2,070</td>
                                <td><span class="status processing">Processing</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rate Configuration Section -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Rate Configuration</h3>
                        <button class="btn-save" onclick="saveRateConfig()">
                            <i class='bx bx-save'></i> Save Changes
                        </button>
                    </div>
                    
                    <div class="config-grid">
                        <!-- Base Rate Section -->
                        <div class="config-section">
                            <h4><i class='bx bx-money'></i> Base Rates</h4>
                            <div class="config-item">
                                <label>Standard Base Rate</label>
                                <div class="input-control">
                                    <span class="prefix">Rs.</span>
                                    <input type="number" value="100" step="0.50" min="0">
                                    <span class="suffix">/kg</span>
                                </div>
                            </div>
                            <div class="config-item">
                                <label>Peak Season Bonus</label>
                                <div class="input-control">
                                    <input type="number" value="10" min="0" max="100">
                                    <span class="suffix">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quality Grades Section -->
                        <div class="config-section">
                            <h4><i class='bx bx-medal'></i> Quality Grades</h4>
                            <div class="config-item">
                                <label>Grade A (Premium)</label>
                                <div class="input-control">
                                    <input type="number" value="20" min="0" max="100">
                                    <span class="suffix">% bonus</span>
                                </div>
                            </div>
                            <div class="config-item">
                                <label>Grade B (Standard)</label>
                                <div class="base-rate-text">Base Rate</div>
                            </div>
                            <div class="config-item">
                                <label>Grade C (Low)</label>
                                <div class="input-control">
                                    <input type="number" value="10" min="0" max="100">
                                    <span class="suffix">% deduction</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quality Parameters Section -->
                        <div class="config-section">
                            <h4><i class='bx bx-leaf'></i> Quality Parameters</h4>
                            <div class="config-item">
                                <label>Moisture Content Range</label>
                                <div class="range-input">
                                    <input type="number" value="70" min="0" max="100">
                                    <span>% to</span>
                                    <input type="number" value="75" min="0" max="100">
                                    <span>%</span>
                                </div>
                            </div>
                            <div class="config-item">
                                <label>Maximum Collection Time</label>
                                <div class="time-input">
                                    <input type="number" value="4" min="1" max="24">
                                    <span>hours</span>
                                </div>
                            </div>
                            <div class="config-item">
                                <label>Contamination Tolerance</label>
                                <div class="rate-input">
                                    <input type="number" value="2" min="0" max="100">
                                    <span>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    <style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    /* Configuration Grid */
    .config-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        padding: 1.5rem;
    }

    .config-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #eee;
    }

    .config-section h4 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        color: var(--main);
        font-size: 1.1rem;
    }

    .config-item {
        margin-bottom: 1.5rem;
    }

    .config-item:last-child {
        margin-bottom: 0;
    }

    .config-item label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
        font-size: 0.9rem;
    }

    .input-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-control input,
    .range-input input,
    .time-input input {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100px;
    }

    .prefix, .suffix {
        color: #666;
        font-size: 0.9rem;
    }

    .btn-save {
        background: var(--main);
        color: var(--light);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
    }

    .btn-save:hover {
        background: var(--main-dark);
    }

    .btn-save i {
        font-size: 1.1rem;
    }

    /* Add these styles */
    .head-info {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .total-amount, .total-suppliers {
        font-size: 0.9rem;
        color: #666;
    }

    .total-amount {
        font-weight: 600;
        color: var(--main);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, 
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    table th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.9rem;
    }

    table tbody tr:hover {
        background: #f8f9fa;
    }

    /* Status badge styles using system colors */
    .status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Processing Status */
    .status.processing {
        background-color: var(--red);  /* Light yellow background */
    }

    /* Processed Status */
    .status.processed {
        background-color: var(--main);  /* Light background */
    }

    .status::before {
        content: '•';
    }

    /* Modify the pulse animation */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }

    .status.processing {
        animation: pulse 1.5s ease-in-out infinite;
    }
    </style>
    </body>
</html>
    