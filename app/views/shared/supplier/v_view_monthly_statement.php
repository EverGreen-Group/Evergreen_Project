<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php 
// Assuming you have a way to get the user role, e.g., from session or user object // Example of getting user role from session

// Conditional inclusion of the sidebar based on user role
if (RoleHelper::hasRole(RoleHelper::SUPPLIER_MANAGER)) {
    require APPROOT . '/views/inc/components/sidebar_suppliermanager.php';
} else {
    require APPROOT . '/views/inc/components/sidebar_supplier.php';
}
?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
    <main>
        <div class="head-title">
                <div class="left">
                    <h1>Supplier Payment History</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="#">Monthly Statement</a>
                        </li>
                    </ul>
                </div>
                <div class="right">
                    <div class="statement-selector">
                        <select id="monthlyStatementSelect" class="statement-select">
                            <option value="">Select Previous Statement</option>
                            <option value="2024-02">February 2024</option>
                            <option value="2024-01">January 2024</option>
                            <option value="2023-12">December 2023</option>
                            <option value="2023-11">November 2023</option>
                            <option value="2023-10">October 2023</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Today's Pending Payments -->
            <div class="table-data">
                <div class="order statement-container">
                    <!-- Header Section -->
                    <div class="statement-header">
                        <!-- Company Info Section -->
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

                        <!-- Info Row -->
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
                                        <td class="value">01 Dec 2024 - 31 Dec 2024</td>
                                        <td class="value">SUP002</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Records -->
                    <div class="collection-records">
                        <h3 class="section-title">Collection Records</h3>
                        <table class="records-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Leaf Type</th>
                                    <th>Leaf Age</th>
                                    <th>Quantity (kg)</th>
                                    <th>Moisture</th>
                                    <th>Deductions (kg)</th>
                                    <th>True Weight (kg)</th>
                                    <th>Rate (Rs.)</th>
                                    <th>Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_tea_income = 0;
                                if (!empty($data['collections'])): 
                                    foreach ($data['collections'] as $collection): 
                                        $total_tea_income += $collection['amount'];
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($collection['date']); ?></td>
                                        <td><?php echo htmlspecialchars($collection['leaf_type']); ?></td>
                                        <td><?php echo htmlspecialchars($collection['leaf_age']); ?></td>
                                        <td><?php echo number_format($collection['quantity'], 1); ?></td>
                                        <td><?php echo htmlspecialchars($collection['moisture']); ?></td>
                                        <td><?php echo number_format($collection['deductions'], 1); ?></td>
                                        <td><?php echo number_format($collection['true_weight'], 1); ?></td>
                                        <td><?php echo number_format($collection['rate'], 2); ?></td>
                                        <td><?php echo number_format($collection['amount'], 2); ?></td>
                                    </tr>
                                <?php 
                                    endforeach; 
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No collection records found for this period.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add after the collection-records div -->
                    <div class="fertilizer-records">
                        <h3 class="section-title">Fertilizer Orders</h3>
                        <?php 
                        $total_fertilizer = 0;
                        if (!empty($data['orders'])): 
                        ?>
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
                                    <?php foreach ($data['orders'] as $order): 
                                        $total_fertilizer += $order->total_price;
                                    ?>
                                        <tr>
                                            <td><?php echo date('Y-m-d', strtotime($order->order_date)); ?></td>
                                            <td><?php echo htmlspecialchars($order->order_id); ?></td>
                                            <td><?php echo htmlspecialchars($order->fertilizer_name); ?></td>
                                            <td><?php echo htmlspecialchars($order->total_amount); ?></td>
                                            <td><?php echo number_format($order->price_per_unit, 2); ?></td>
                                            <td><?php echo number_format($order->total_price, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No fertilizer orders found for this period.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Add Summary Section -->
                    <div class="statement-summary">
                        <table class="summary-table">
                            <tr>
                                <td class="summary-label">Total Tea Leaf Income</td>
                                <td class="summary-value">Rs. <?php echo number_format($total_tea_income, 2); ?></td>
                            </tr>
                            <tr>
                                <td class="summary-label">Total Fertilizer Deductions</td>
                                <td class="summary-value">Rs. <?php echo number_format($total_fertilizer, 2); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td class="summary-label">Net Amount</td>
                                <td class="summary-value">Rs. <?php echo number_format($total_tea_income - $total_fertilizer, 2); ?></td>
                            </tr>
                        </table>
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
        text-align: left;  /* Changed from right to left */
        /* margin-top: 1rem;
        padding-top: 1rem; */
        border-top: 1px solid #eee;
    }

    .right-header {
        display: flex;
        align-items: flex-start;  /* Align to top */
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

    .text-center {
        column-span: 9;
        text-align: center;

    }
</style>
<script>
    document.getElementById('monthlyStatementSelect').addEventListener('change', function() {
        const selectedPeriod = this.value;
        if (selectedPeriod) {
            // Add your logic here to load the selected statement
            // For example:
            window.location.href = `<?php echo URLROOT; ?>/supplier/viewMonthlyStatement/${selectedPeriod}`;
        }
    });
</script>
</body>
</html>
    