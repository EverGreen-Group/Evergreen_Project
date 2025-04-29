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
            <h1>Record</h1>
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
                        <th>Total Quantity</th>
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