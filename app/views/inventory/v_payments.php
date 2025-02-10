<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
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

    <!-- Monthly Supplier Payrolls -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Monthly Payment Overview - 2024</h3>
                <div class="head-info">
                    <select class="year-select">
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
            </div>
            <table class="payment-overview-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Suppliers</th>
                        <th>Total Tea Weight (kg)</th>
                        <th>Gross Amount (Rs.)</th>
                        <th>Fertilizer Deductions (Rs.)</th>
                        <th>Net Amount (Rs.)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="clickable-row">
                        <td>March</td>
                        <td>15</td>
                        <td>2,450.5</td>
                        <td>285,740.00</td>
                        <td>65,000.00</td>
                        <td>220,740.00</td>
                        <td><span class="status pending">Pending</span></td>
                        <td><a href="<?php echo URLROOT; ?>/supplier/monthlyPayments/2024-03" class="view-btn">View
                                Details</a></td>
                    </tr>
                    <tr class="clickable-row">
                        <td>February</td>
                        <td>14</td>
                        <td>2,280.0</td>
                        <td>265,300.00</td>
                        <td>48,000.00</td>
                        <td>217,300.00</td>
                        <td><span class="status processed">Completed</span></td>
                        <td><a href="<?php echo URLROOT; ?>/supplier/monthlyPayments/2024-02" class="view-btn">View
                                Details</a></td>
                    </tr>
                    <tr class="clickable-row">
                        <td>January</td>
                        <td>13</td>
                        <td>2,150.0</td>
                        <td>248,500.00</td>
                        <td>52,000.00</td>
                        <td>196,500.00</td>
                        <td><span class="status processed">Completed</span></td>
                        <td><a href="<?php echo URLROOT; ?>/supplier/monthlyPayments/2024-01" class="view-btn">View
                                Details</a></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>Year Total</strong></td>
                        <td><strong>14 (avg)</strong></td>
                        <td><strong>6,880.5</strong></td>
                        <td><strong>799,540.00</strong></td>
                        <td><strong>165,000.00</strong></td>
                        <td><strong>634,540.00</strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="table-data">
        <!-- Monthly Fertilizer Summary -->
        <div class="order">
            <div class="head">
                <h3>Monthly Fertilizer Purchases Summary</h3>
                <div class="head-info">
                    <span class="total-amount">Total Value: Rs. 65,000.00</span>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Fertilizer Type</th>
                        <th>Total Quantity (kg)</th>
                        <th>Unit Price (Rs.)</th>
                        <th>Total Value (Rs.)</th>
                        <th>No. of Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>NPK 15-15-15</td>
                        <td>250</td>
                        <td>180.00</td>
                        <td>45,000.00</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>Urea</td>
                        <td>125</td>
                        <td>160.00</td>
                        <td>20,000.00</td>
                        <td>8</td>
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
                <!-- Base Rates Section -->
                <div class="config-section">
                    <h4><i class='bx bx-money'></i> Base Rates</h4>
                    <div class="config-item">
                        <label>Normal Leaf Rate</label>
                        <div class="input-control">
                            <span class="prefix">Rs.</span>
                            <input type="number" id="normal_leaf_rate" value="95" step="0.50" min="0">
                            <span class="suffix">/kg</span>
                        </div>
                    </div>
                    <div class="config-item">
                        <label>Super Leaf Rate</label>
                        <div class="input-control">
                            <span class="prefix">Rs.</span>
                            <input type="number" id="super_leaf_rate" value="120" step="0.50" min="0">
                            <span class="suffix">/kg</span>
                        </div>
                    </div>
                </div>

                <!-- Moisture Content Deductions -->
                <div class="config-section">
                    <h4><i class='bx bx-droplet'></i> Moisture Deductions</h4>
                    <div class="config-item">
                        <label>Low Moisture (Below 68%)</label>
                        <div class="input-control">
                            <input type="number" value="5" min="0" max="100">
                            <span class="suffix">% deduction</span>
                        </div>
                    </div>
                    <div class="config-item">
                        <label>Optimal Range</label>
                        <div class="range-input">
                            <input type="number" value="68" min="0" max="100">
                            <span>% to</span>
                            <input type="number" value="72" min="0" max="100">
                            <span>%</span>
                        </div>
                    </div>
                    <div class="config-item">
                        <label>High Moisture (Above 72%)</label>
                        <div class="input-control">
                            <input type="number" value="8" min="0" max="100">
                            <span class="suffix">% deduction</span>
                        </div>
                    </div>
                </div>

                <!-- Leaf Age Deductions -->
                <div class="config-section">
                    <h4><i class='bx bx-time'></i> Leaf Age Deductions</h4>
                    <div class="config-item">
                        <label>4-6 Hours Old</label>
                        <div class="input-control">
                            <input type="number" value="3" min="0" max="100">
                            <span class="suffix">% deduction</span>
                        </div>
                    </div>
                    <div class="config-item">
                        <label>6-8 Hours Old</label>
                        <div class="input-control">
                            <input type="number" value="5" min="0" max="100">
                            <span class="suffix">% deduction</span>
                        </div>
                    </div>
                    <div class="config-item">
                        <label>Over 8 Hours Old</label>
                        <div class="input-control">
                            <input type="number" value="10" min="0" max="100">
                            <span class="suffix">% deduction</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
</section>

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

    .prefix,
    .suffix {
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

    .total-amount,
    .total-suppliers {
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
        background-color: var(--red);
        /* Light yellow background */
    }

    /* Processed Status */
    .status.processed {
        background-color: var(--main);
        /* Light background */
    }

    .status::before {
        content: '•';
    }

    /* Modify the pulse animation */
    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }

        100% {
            opacity: 1;
        }
    }

    .status.processing {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Add these new styles */
    .month-select {
        padding: 0.5rem 2rem 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        font-size: 0.9rem;
        color: #333;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 0.75rem) center;
    }

    .view-btn {
        padding: 0.4rem 0.8rem;
        background-color: var(--main);
        color: var(--light);
        border-radius: 4px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .view-btn:hover {
        background-color: var(--main-dark);
    }

    table tfoot {
        background-color: #f8f9fa;
    }

    table tfoot td {
        padding: 12px;
        border-top: 2px solid #ddd;
    }

    .status.paid {
        background-color: var(--main);
        color: var(--light);
    }

    .status.pending {
        background-color: var(--yellow);
        color: var(--dark);
    }

    .year-select {
        padding: 0.5rem 2rem 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        font-size: 0.9rem;
        color: #333;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 0.75rem) center;
    }

    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .clickable-row:hover {
        background-color: #f0f4f8 !important;
    }

    .payment-overview-table td {
        vertical-align: middle;
    }

    .status.pending {
        background-color: var(--yellow);
        color: var(--dark);
    }

    .status.processed {
        background-color: var(--main);
        color: var(--light);
    }
</style>
<script>

    function saveRateConfig() {
        const normalLeafRate = document.getElementById('normal_leaf_rate').value;
        const superLeafRate = document.getElementById('super_leaf_rate').value;

        // Send the data to the server using fetch or XMLHttpRequest
        // fetch('saveRateConfig.php', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify({ normalLeafRate, superLeafRate })
        // })
        // .then(response => response.json())
        // .then(data => {
        //     console.log(data);
        // });

        // For demonstration purposes
        console.log('Normal Leaf Rate:', normalLeafRate);
        console.log('Super Leaf Rate:', superLeafRate);
    }

    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function (e) {
            // Ignore click if it's on the View Details button
            if (e.target.closest('.view-btn')) return;

            // Get the month from the first cell
            const month = this.cells[0].textContent.toLowerCase();
            const year = document.querySelector('.year-select').value;
            const url = `<?php echo URLROOT; ?>/supplier/monthlyPayments/${year}-${month}`;
            window.location.href = url;
        });
    });

    document.querySelector('.year-select').addEventListener('change', function () {
        const selectedYear = this.value;
        // Reload the page with the selected year
        window.location.href = `<?php echo URLROOT; ?>/supplier/payments/${selectedYear}`;
    });



</script>
</body>

</html>