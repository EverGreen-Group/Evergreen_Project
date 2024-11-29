<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Vehicle</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/vehiclemanager">Vehicles</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update</a></li>
            </ul>
        </div>
    </div>

    <form id="updateVehicleForm" method="POST">
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Vehicle Information</h3>
                </div>
                <div class="form-grid">
                    <!-- Basic Vehicle Details -->
                    <div class="form-group">
                        <label for="license_plate">License Plate Number</label>
                        <input type="text" id="license_plate" name="license_plate" value="WP-CAB-1234">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type</label>
                        <select id="vehicle_type" name="vehicle_type">
                            <option value="Car" selected>Car</option>
                            <option value="Van">Van</option>
                            <option value="Truck">Truck</option>
                            <option value="Bus">Bus</option>
                            <option value="Three-Wheeler">Three-Wheeler</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Available" selected>Available</option>
                            <option value="In Use">In Use</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="make">Make</label>
                        <input type="text" id="make" name="make" value="Toyota">
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model" value="Corolla">
                    </div>

                    <!-- Technical Details -->
                    <div class="form-group">
                        <label for="engine_number">Engine Number</label>
                        <input type="text" id="engine_number" name="engine_number" value="2ZR-FE1234567">
                    </div>
                    <div class="form-group">
                        <label for="chassis_number">Chassis Number</label>
                        <input type="text" id="chassis_number" name="chassis_number" value="JTDKB20UX93456789">
                    </div>
                    <div class="form-group">
                        <label for="fuel_type">Fuel Type</label>
                        <select id="fuel_type" name="fuel_type">
                            <option value="Petrol" selected>Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Electric">Electric</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="table-data">
            <div class="order">
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Vehicle</button>
                </div>
            </div>
        </div>
    </form>
</main>

<style>
/* Copying the original styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--dark);
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--grey);
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px;
}

.btn {
    padding: 10px 24px;
    border: none;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--main);
    color: var(--light);
}

.btn-secondary {
    background: var(--grey);
    color: var(--dark);
}

.btn:hover {
    opacity: 0.9;
}

@media screen and (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>