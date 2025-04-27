<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Factory Configurations</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/Admin/dashboard">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Factory Configurations</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Factory Location -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Factory Location</h3>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/Admin/updateFactoryLocation">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['factory_location']->id); ?>">
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="latitude">Latitude:</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" required 
                               value="<?php echo htmlspecialchars($data['factory_location']->latitude); ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="longitude">Longitude:</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" required 
                               value="<?php echo htmlspecialchars($data['factory_location']->longitude); ?>">
                    </div>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Location</button>
            </form>
        </div>
    </div>
    
    <!-- Moisture Level Deductions -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Moisture Level Deductions</h3>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/Admin/updateMoistureDeductions">
                <div class="section-content">
                    <?php foreach($data['moisture_deductions'] as $deduction): ?>
                    <div class="info-row">
                        <label class="label" for="moisture_<?php echo htmlspecialchars($deduction->id); ?>">
                            <?php echo htmlspecialchars($deduction->value); ?>:
                        </label>
                        <div class="input-with-suffix">
                            <input type="hidden" name="id[]" value="<?php echo htmlspecialchars($deduction->id); ?>">
                            <input type="number" id="moisture_<?php echo htmlspecialchars($deduction->id); ?>" 
                                   name="deduction[]" class="form-control" step="0.01" min="0" max="100" required 
                                   value="<?php echo htmlspecialchars($deduction->deduction_percent); ?>">
                            <span class="suffix">%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Moisture Deductions</button>
            </form>
        </div>
    </div>
    
    <!-- Leaf Age Deductions -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Leaf Age Deductions</h3>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/Admin/updateLeafAgeDeductions">
                <div class="section-content">
                    <?php foreach($data['leaf_age_deductions'] as $deduction): ?>
                    <div class="info-row">
                        <label class="label" for="leaf_age_<?php echo htmlspecialchars($deduction->id); ?>">
                            <?php echo htmlspecialchars($deduction->value); ?>:
                        </label>
                        <div class="input-with-suffix">
                            <input type="hidden" name="id[]" value="<?php echo htmlspecialchars($deduction->id); ?>">
                            <input type="number" id="leaf_age_<?php echo htmlspecialchars($deduction->id); ?>" 
                                   name="deduction[]" class="form-control" step="0.01" min="0" max="100" required 
                                   value="<?php echo htmlspecialchars($deduction->deduction_percent); ?>">
                            <span class="suffix">%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Leaf Age Deductions</button>
            </form>
        </div>
    </div>
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

    .input-with-suffix {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .input-with-suffix .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-with-suffix .suffix {
        padding: 10px 16px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-left: none;
        border-radius: 0 4px 4px 0;
        color: #6c757d;
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