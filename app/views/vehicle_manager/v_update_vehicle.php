<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Vehicle Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/vehiclemanager">Vehicles</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update</a></li>
            </ul>
        </div>
    </div>

    <form id="vehicleForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="vehicle_id" value="<?php echo $data['vehicle']->vehicle_id; ?>">
        
        <!-- Vehicle Information Section -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Vehicle Information</h3>
                </div>
                <div class="form-grid">
                    <!-- Basic Details -->
                    <div class="form-group">
                        <label for="license_plate">License Plate Number *</label>
                        <input type="text" id="license_plate" name="license_plate" 
                               value="<?php echo $data['vehicle']->license_plate; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type *</label>
                        <select id="vehicle_type" name="vehicle_type" required>
                            <?php
                            $types = ['Truck', 'Van', 'Car', 'Bus', 'Three-Wheeler', 'Other'];
                            foreach ($types as $type) {
                                $selected = ($data['vehicle']->vehicle_type === $type) ? 'selected' : '';
                                echo "<option value=\"$type\" $selected>$type</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Technical Details -->
                    <div class="form-group">
                        <label for="engine_number">Engine Number *</label>
                        <input type="text" id="engine_number" name="engine_number" 
                               value="<?php echo $data['vehicle']->engine_number; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="chassis_number">Chassis Number *</label>
                        <input type="text" id="chassis_number" name="chassis_number" 
                               value="<?php echo $data['vehicle']->chassis_number; ?>" required>
                    </div>
                    <!-- Continue with all other fields, adding value attributes -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <?php
                            $statuses = ['Available', 'In Use', 'Maintenance'];
                            foreach ($statuses as $status) {
                                $selected = ($data['vehicle']->status === $status) ? 'selected' : '';
                                echo "<option value=\"$status\" $selected>$status</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Continue with other fields... -->
                </div>
            </div>
        </div>

        <!-- Legal Documents Section -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Legal Documents</h3>
                    <p class="text-muted">Documents can be added later if not immediately available</p>
                </div>
                
                <!-- Revenue License -->
                <div class="document-section">
                    <h4>Revenue License</h4>
                    <div class="document-grid">
                        <div class="form-group">
                            <label for="revenue_number">License Number</label>
                            <input type="text" id="revenue_number" 
                                   name="documents[Revenue License][number]" 
                                   value="<?php echo $data['documents']['revenue']->number ?? ''; ?>">
                        </div>
                        <!-- Continue with other document fields... -->
                    </div>
                </div>
                <!-- Continue with other document sections... -->
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

<!-- Use the same CSS from v_add_vehicle.php -->
<style>
/* Copy all styles from v_add_vehicle.php */
</style>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Vehicle Preview">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Show existing vehicle image if available
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('imagePreview');
    const vehiclePlate = '<?php echo $data['vehicle']->license_plate; ?>';
    const imagePath = `${URLROOT}/public/uploads/vehicle_photos/${vehiclePlate}.jpg`;
    
    preview.innerHTML = `<img src="${imagePath}" alt="Vehicle" onerror="this.src='${URLROOT}/public/images/default-vehicle.jpg'">`;
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 