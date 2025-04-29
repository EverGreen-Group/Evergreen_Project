<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Add Maintenance Record</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/vehicle">Vehicles</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?= URLROOT ?>/manager/viewVehicle/<?= $data['vehicle_id'] ?>">Vehicle Details</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Add Maintenance</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Vehicle Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Vehicle Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label">Registration No:</label>
                    <div class="value"><?= $data['vehicle_info']->license_plate ?></div>
                </div>
                <div class="info-row">
                    <label class="label">Make & Model:</label>
                    <div class="value"><?= $data['vehicle_info']->make ?> <?= $data['vehicle_info']->model ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Maintenance Form -->
    <form method="POST" action="<?php echo URLROOT; ?>/manager/addMaintenance/<?= $data['vehicle_id'] ?>">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Maintenance Details</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="maintenance_type">Maintenance Type:</label>
                        <input id="maintenance_type" name="maintenance_type" class="form-control" required>
                    </div>
                    <div class="info-row">
                        <label class="label" for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control" required rows="4"><?= $data['description'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Add Maintenance Record</button>
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

    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Notice Section */
    .notice-section {
        border-left: 4px solid #f59e0b;
    }

    .notice-text {
        padding: 12px 20px;
        font-size: 14px;
        color: #713b13;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        margin: 0 0 30px 20px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }


</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>