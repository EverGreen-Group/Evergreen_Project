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
            <h1>Edit Route</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/route">Routes</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Edit Route</a></li>
            </ul>
        </div>
    </div>

    
    <form id="editRouteForm" method="POST" action="<?php echo URLROOT; ?>/route/editRoute/<?php echo $data['route']->route_id; ?>">
    
    <!-- Route Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Route Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label" for="route_name">Route Name:</label>
                    <input type="text" id="route_name" name="route_name" class="form-control" required 
                           placeholder="Enter route name" value="<?php echo htmlspecialchars($data['route']->route_name); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Selection Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Select Vehicle</h3>
            </div>
            <div class="section-content">
                <div class="user-selection-container">
                    <select id="vehicleSelect" name="vehicle_id" class="form-control" required>
                        <option value="">Select a Vehicle</option>
                        <?php foreach ($data['availableVehicles'] as $vehicle): ?>
                            <option value="<?= $vehicle->vehicle_id ?>" 
                                    data-vehicle-number="<?= htmlspecialchars($vehicle->vehicle_number) ?>"
                                    data-model="<?= htmlspecialchars($vehicle->model) ?>"
                                    <?php if ($vehicle->vehicle_id == $data['route']->vehicle_id) echo 'selected'; ?>>
                                <?= htmlspecialchars($vehicle->license_plate) ?> - 
                                <?= htmlspecialchars($vehicle->vehicle_type) ?> - 
                                <?= htmlspecialchars($vehicle->make) ?> - 
                                <?= htmlspecialchars($vehicle->model) ?> - 
                                Capacity: <?= htmlspecialchars($vehicle->capacity) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Route Statistics (Read-only) -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Route Details</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label">Number of Suppliers:</label>
                    <span class="value"><?php echo htmlspecialchars($data['route']->number_of_suppliers); ?></span>
                </div>
                <div class="info-row">
                    <label class="label">Remaining Capacity:</label>
                    <span class="value"><?php echo htmlspecialchars($data['route']->remaining_capacity); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="button-group">
        <button type="submit" class="btn btn-primary">Update Route</button>
        <a href="<?php echo URLROOT; ?>/route" class="btn btn-secondary">Cancel</a>
    </div>
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

    /* Add styles for selection dropdowns */
    .user-selection-container {
        padding: 20px;
    }

    #vehicleSelect {
        width: 100%;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
    }

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

    /* Button group styling */
    .button-group {
        display: flex;
        gap: 12px;
        margin: 0 0 20px 20px;
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
    }

    .btn-primary:hover {
        background-color: #059669;
    }

    /* Cancel button styling */
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>