<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Driver</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/driver">Drivers</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update Driver</a></li>
            </ul>
        </div>
    </div>

    
    <form method="POST" action="<?php echo URLROOT; ?>/manager/updateDriver/<?php echo $data['driver_id']; ?>" enctype="multipart/form-data">
        
        <!-- Driver Information Section -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Driver Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="license_number">License Number:</label>
                        <input type="text" id="license_number" name="license_number" class="form-control" value="<?php echo htmlspecialchars($data['license_number']); ?>" required>
                    </div>
                    <div class="info-row">
                        <label class="label" for="hire_date">Hire Date:</label>
                        <input type="date" id="hire_date" name="hire_date" class="form-control" value="<?php echo htmlspecialchars($data['hire_date']); ?>" required>
                    </div>
                    <div class="info-row">
                        <label class="label" for="status">Status:</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Active" <?php echo $data['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo $data['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <!-- Current image display -->
                    <?php if (!empty($data['image_path'])): ?>
                    <div class="info-row">
                        <label class="label">Current Image:</label>
                        <div class="image-preview">
                            <img src="<?php echo URLROOT . '/' . $data['image_path']; ?>" alt="Driver Photo" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-row">
                        <label class="label" for="image_path">Upload New Image:</label>
                        <input type="file" id="image_path" name="image_path" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Driver</button>
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

    /* Image preview */
    .image-preview {
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 4px;
        background-color: #f9f9f9;
        display: inline-block;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 