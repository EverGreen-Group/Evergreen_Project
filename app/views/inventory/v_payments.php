<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



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
        <!-- <div class="head-actions">
            <button class="btn-download" id="downloadBtn">
                <i class='bx bx-download'></i> Export Report
            </button>
        </div> -->
    </div>

    <!-- Monthly Supplier Payrolls -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Income Overview of Last 3 Month</h3>
            </div>
            <table class="payment-overview-table">
    <thead>
        <tr>
            <th>Month</th>
            <th>Total Tea Weight (kg)</th>
            <th>Export Amount (kg)</th>
            <th>Net Income (Rs.)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($overviewData as $row): ?>
            <tr class="clickable-row">
                <td><?= date('F Y', strtotime($row['month'].'-01')) ?></td>
                <td><?= number_format($row['total_tea_weight'], 2) ?></td>
                <td><?= number_format($row['export_amount'], 2) ?></td>
                <td><?= number_format($row['net_income'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
    </div>

    <div class="table-data">
        <!-- Monthly Fertilizer Summary -->
        <div class="order">
            <div class="head">
                <h3>Monthly Fertilizer Purchases Summary</h3>

            </div>
            <table>
                <thead>
                    <tr>
                        <th>Fertilizer Type</th>
                        <th>Total Quantity (kg)</th>
                        <th>Unit Price (Rs.)</th>
                        <th>Total Value (Rs.)</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    if (!empty($data['fertilizer'])) {
                        foreach ($data['fertilizer'] as $item) {
                            $totalValue = $item->quantity * $item->price;
                            $grandTotal += $totalValue;
                            $numOrders = 1; // Replace this with your actual logic
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item->fertilizer_name); ?></td>
                                <td><?php echo htmlspecialchars($item->quantity); ?></td>
                                <td><?php echo number_format($item->price, 2); ?></td>
                                <td><?php echo number_format($totalValue, 2); ?></td>
                               
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="head">
                <div class="head-info">
                    <span class="total-amount">Total Value: Rs.<?php echo number_format($grandTotal, 2); ?></span>
                </div>

            </div>
        </div>
    </div>

    <!-- Rate Configuration Section
    <form method="post" Action="<?php echo URLROOT ?>/Inventory/payments">
    <input type="hidden" name="rate_config" value="1">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Rate Configuration</h3>
                    <button class="btn-save" type="submit">
                        <i class='bx bx-save'></i> Save Changes
                    </button>
                </div>

                <div class="config-grid">
                    
                    <div class="config-section">
                        <h4><i class='bx bx-money'></i> Base Rates</h4>
                        <div class="config-item">
                            <label>Normal Leaf Rate</label>
                            <div class="input-control">
                                <span class="prefix">Rs.</span>
                                <input type="number" name="normal_leaf_rate" value="95" step="0.50" min="0">
                                <span class="suffix">/kg</span>
                            </div>
                        </div>
                        <div class="config-item">
                            <label>Super Leaf Rate</label>
                            <div class="input-control">
                                <span class="prefix">Rs.</span>
                                <input type="number" name="super_leaf_rate" value="120" step="0.50" min="0">
                                <span class="suffix">/kg</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="config-section">
                        <h4><i class='bx bx-droplet'></i> Fertilizer Stock Limitation</h4>
                        <div class="config-item">
                            <label>Lower Stock Limit</label>
                            <div class="input-control">
                                <input type="number" name="fertilizer_stock_lower" value="1200" min="1000" max="10000">
                                <span class="suffix">kg</span>
                            </div>
                        </div>
                        <div class="config-item">
                            <label>Should Import Limit</label>
                            <div class="range-input">  
                                <input type="number" name="fertilizer_stock_mid_low" value="10000" min="1000"
                                    max="20000">
                                <span>kg to</span>
                                <input type="number" name="fertilizer_stock_mid_high" value="72000" min="10000"
                                    max="100000">
                                <span>kg</span>
                            </div>
                        </div>

                    </div>

                
                    <div class="config-section">
                        <h4><i class='bx bx-time'></i> Leaf Age Deductions</h4>
                        <div class="config-item">
                            <label>6-10 Hours Old</label>
                            <div class="input-control">
                                <input type="number" name="Leaf_age_1" value="2" min="0" max="100">
                                <span class="suffix">% deduction</span>
                            </div>
                        </div>
                        <div class="config-item">
                            <label>1 Day Old</label>
                            <div class="input-control">
                                <input type="number" name="Leaf_age_2" value="5">
                                <span class="suffix">% deduction</span>
                            </div>
                        </div>
                        <div class="config-item">
                            <label>Over 1 Day Old</label>
                            <div class="input-control">
                                <input type="number" name="Leaf_age_3" value="8">
                                <span class="suffix">% deduction</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> -->

    <!-- <div id="statementContent" class="pdf-only">
        
        <div class="table-data">
            <div class="order statement-container">
                
                <div class="statement-header">
                    
                    <div class="company-info">
                        <div class="logo-container">
                            <img src="<?php echo URLROOT; ?>/public/img/logo.svg" alt="Tea Factory Logo" class="logo">
                            <div class="company-details">
                                <span class="company-name">Evergreen Tea Factory</span>
                                <p>123 Tea Estate Road, Galle</p>
                                <p>Tel: +94 123 456 789</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="info-row">
                        <div class="supplier-details">
                            <h3>John Doe</h3>
                            <p>456 Tea Gardens,</p>
                            <p>Galle, Sri Lanka</p>
                        </div>
                        <div class="statement-details">
                            <table>
                                <tr>
                                    <td class="label">Statement Period</td>
                                    <td class="label">Supplier Number</td>
                                </tr>
                                <tr>
                                    <td class="value">01 Mar 2024 - 31 Mar 2024</td>
                                    <td class="value">SUP001</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="collection-records">
                    <h3 class="section-title">Collection Records</h3>
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Receipt No.</th>
                                <th>Normal Leaf (kg)</th>
                                <th>Super Leaf (kg)</th>
                                <th>Transport</th>
                                <th>Bags</th>
                                <th>Moisture</th>
                                <th>Other Deductions</th>
                                <th>True Weight</th>
                                <th>Rate (Rs.)</th>
                                <th>Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>03-01</td>
                                <td>R001</td>
                                <td>50</td>
                                <td>30</td>
                                <td>200</td>
                                <td>5</td>
                                <td>2%</td>
                                <td>1.5</td>
                                <td>76.5</td>
                                <td>95/120</td>
                                <td>8,550</td>
                            </tr>
                            <tr>
                                <td>03-03</td>
                                <td>R002</td>
                                <td>45</td>
                                <td>35</td>
                                <td>180</td>
                                <td>4</td>
                                <td>1.5%</td>
                                <td>1.2</td>
                                <td>77.3</td>
                                <td>95/120</td>
                                <td>8,740</td>
                            </tr>
                            <tr>
                                <td>03-05</td>
                                <td>R003</td>
                                <td>55</td>
                                <td>25</td>
                                <td>220</td>
                                <td>6</td>
                                <td>2.5%</td>
                                <td>1.8</td>
                                <td>75.7</td>
                                <td>95/120</td>
                                <td>8,320</td>
                            </tr>
                            <tr>
                                <td>03-07</td>
                                <td>R004</td>
                                <td>48</td>
                                <td>32</td>
                                <td>190</td>
                                <td>5</td>
                                <td>1.8%</td>
                                <td>1.4</td>
                                <td>76.8</td>
                                <td>95/120</td>
                                <td>8,680</td>
                            </tr>
                            <tr>
                                <td>03-09</td>
                                <td>R005</td>
                                <td>52</td>
                                <td>28</td>
                                <td>210</td>
                                <td>5</td>
                                <td>2.2%</td>
                                <td>1.6</td>
                                <td>76.2</td>
                                <td>95/120</td>
                                <td>8,450</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                <div class="fertilizer-records">
                    <h3 class="section-title">Fertilizer Orders</h3>
                    <table class="records-table fertilizer-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order No.</th>
                                <th>Fertilizer Type</th>
                                <th>Quantity (kg)</th>
                                <th>Unit Price (Rs.)</th>
                                <th>Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-03-02</td>
                                <td>F001</td>
                                <td>NPK 15-15-15</td>
                                <td>50</td>
                                <td>180.00</td>
                                <td>9,000</td>
                            </tr>
                            <tr>
                                <td>2024-03-08</td>
                                <td>F002</td>
                                <td>Urea</td>
                                <td>25</td>
                                <td>160.00</td>
                                <td>4,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                <div class="statement-summary">
                    <table class="summary-table">
                        <tr>
                            <td class="summary-label">Total Tea Leaf Income</td>
                            <td class="summary-value">Rs. 42,740.00</td>
                        </tr>
                        <tr>
                            <td class="summary-label">Total Fertilizer Deductions</td>
                            <td class="summary-value">Rs. 13,000.00</td>
                        </tr>
                        <tr class="total-row">
                            <td class="summary-label">Net Amount</td>
                            <td class="summary-value">Rs. 29,740.00</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
</main>


<style>
    @media print {
        .pdf-only {
            display: block;
        }
    }

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

    .statement-container {
        background: white;
        padding: 2rem;
        margin: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .statement-header {
        display: flex;
        flex-direction: column;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        /* border-bottom: 1px solid #eee; */
    }

    .company-info {
        margin-bottom: 2rem;
        border-bottom: none;
    }

    .logo-container {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .company-info .logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    .company-name {
        font-size: 1.2rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .company-details p {
        color: #666;
        margin: 0.2rem 0;
        font-size: 0.9rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-left: 50px;
        min-height: 80px;
        gap: 2rem;
    }

    .supplier-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 100%;
        padding: 0.15rem 0;
    }

    .supplier-details h3 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .supplier-details p {
        color: #666;
        margin: 0.1rem 0;
        font-size: 0.9rem;
    }

    .statement-details {
        flex: 0.3;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .statement-details table {
        border-collapse: collapse;
        width: auto;
        font-size: 0.8rem;
    }

    .statement-details td {
        padding: 0.15rem 0.35rem;
        border: 1px solid #ddd;
    }

    .statement-details td.label {
        background-color: #f8f9fa;
        font-weight: normal;
        color: #333;
        text-align: center;
    }

    .statement-details td.value {
        min-width: 150px;
        text-align: center;
    }

    .records-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .records-table th,
    .records-table td {
        padding: 0.75rem;
        border: 1px solid #ddd;
        text-align: center;
    }

    .records-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .records-table tbody tr:hover {
        background: #f8f9fa;
    }

    /* Update these styles */
    .left-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .supplier-details {
        text-align: left;
        /* Changed from right to left */
        /* margin-top: 1rem;
        padding-top: 1rem; */
        border-top: 1px solid #eee;
    }

    .right-header {
        display: flex;
        align-items: flex-start;
        /* Align to top */
    }

    /* Remove the gap from right-header since we only have one element now */
    .right-header {
        gap: 0;
    }

    /* Add these new styles */
    .fertilizer-records {
        margin-top: 3rem;
    }

    .section-title {
        font-size: 1rem;
        color: #333;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .fertilizer-table {
        margin-bottom: 2rem;
    }

    .statement-summary {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 2px solid #eee;
    }

    .summary-table {
        width: auto;
        border-collapse: collapse;
        min-width: 300px;
    }

    .summary-table tr {
        border-bottom: 1px solid #eee;
    }

    .summary-table tr:last-child {
        border-bottom: none;
    }

    .summary-label {
        padding: 0.5rem 1rem;
        text-align: left;
        font-weight: 500;
        color: #333;
    }

    .summary-value {
        padding: 0.5rem 1rem;
        text-align: right;
        color: #333;
    }

    .total-row {
        font-weight: 600;
        font-size: 1.1rem;
        border-top: 2px solid #eee;
    }

    .total-row .summary-label,
    .total-row .summary-value {
        padding-top: 1rem;
        color: var(--main);
    }

    /* Add these new styles */
    .head-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .statement-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .statement-select {
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
        min-width: 200px;
    }

    .statement-select:focus {
        outline: none;
        border-color: var(--main);
    }

    .statement-select option {
        padding: 0.5rem;
    }
</style>
<script>

    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';

    function saveRateConfig() {
        const normalLeafRate = document.getElementById('normal_leaf_rate').value;
        const superLeafRate = document.getElementById('super_leaf_rate').value;
        const fertilizerStockLower = document.getElementById('fertilizer_stock_lower').value;
        const fertilizerStockMidLow = document.getElementById('fertilizer_stock_mid_low').value;
        const fertilizerStockMidHigh = document.getElementById('fertilizer_stock_mid_high').value;
        const leafAge1 = document.getElementById('Leaf_age_1').value;
        const leafAge2 = document.getElementById('Leaf_age_2').value;
        const leafAge3 = document.getElementById('Leaf_age_3').value;

        const postData = {
            normalLeafRate,
            superLeafRate,
            fertilizerStockLower,
            fertilizerStockMidLow,
            fertilizerStockMidHigh,
            leafAge1,
            leafAge2,
            leafAge3
        };


        const url = `${URLROOT}/inventory/payments`;
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(postData)
        })




        // For demonstration purposes
        console.log(postData);
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
    document.getElementById("downloadBtn").addEventListener("click", () => {
        const element = document.getElementById("statementContent");

        const opt = {
            margin: 0.3,
            filename: 'supplier-statement.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().from(element).set(opt).save();
    });

</script>
</body>

</html>