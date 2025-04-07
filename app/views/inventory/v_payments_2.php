<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory/payments_form.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Payment Report</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/inventory/dashboard">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Select Month</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if(!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>
    
    <form id="paymentReportForm" method="POST" action="<?php echo URLROOT; ?>/inventory/paymentsReport">
        <!-- Report Selection -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Select Report Period</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="month">Month and Year:</label>
                        <input type="month" id="month" name="month" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Report Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Report Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Report Contents:</span>
                        <span class="value">Payment details for all suppliers including their personal and bank information.</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Data Included:</span>
                        <span class="value">Supplier names, bank details, total bags collected, total weight, and payment amounts.</span>
                    </div>
                    <div class="info-row">
                        <span class="label">PDF Export:</span>
                        <span class="value">The report will be displayed on screen with an option to download as PDF.</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
</main>

<style>
    /* Table Data Container */
    .table-data {
        margin-bottom: 24px;
    }

    .order {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Section Headers */
    .head {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .head h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Content Sections */
    .section-content {
        padding: 8px 0;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        transition: background-color 0.2s;
    }

    .info-row:hover {
        background-color: #f8f9fa;
    }

    .info-row .label {
        flex: 0 0 200px;
        font-size: 14px;
        color: #6c757d;
    }

    .info-row .value {
        flex: 1;
        font-size: 14px;
        color: #2c3e50;
    }

    /* Alert styling */
    .alert {
        padding: 12px 20px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-size: 14px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Breadcrumb Refinements */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .breadcrumb a {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #3b82f6;
    }

    .breadcrumb a.active {
        color: #2c3e50;
        pointer-events: none;
    }

    .breadcrumb i {
        color: #9ca3af;
        font-size: 14px;
    }

    /* Form controls */
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
    }
    
    /* Radio button styling */
    .radio-label {
        margin-right: 20px;
        cursor: pointer;
    }

    /* Submit button styling */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        margin: 0 0 20px 20px;
    }

    .btn-primary:hover {
        background-color: #059669;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>