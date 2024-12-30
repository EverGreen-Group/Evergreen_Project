<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1><?php echo isset($data['vehicle']) ? 'Edit Vehicle' : 'Add New Vehicle'; ?></h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/vehiclemanager">Vehicles</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#"><?php echo isset($data['vehicle']) ? 'Edit' : 'Add'; ?></a></li>
            </ul>
        </div>
    </div>

    <form id="vehicleForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <!-- Vehicle Information Section -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Vehicle Information</h3>
                </div>
                <div class="form-grid">
                    <!-- Basic Details -->
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type *</label>
                        <select id="vehicle_type" name="vehicle_type" required>
                            <option value="">Select Type</option>
                            <option value="Truck">Truck</option>
                            <option value="Van">Van</option>
                            <option value="Car">Car</option>
                            <option value="Bus">Bus</option>
                            <option value="Three-Wheeler">Three-Wheeler</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Available">Available</option>
                            <option value="In Use">In Use</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="license_plate">License Plate Number *</label>
                        <input type="text" id="license_plate" name="license_plate" required 
                               title="Enter a valid Sri Lankan vehicle number (e.g., KA-1234, 19-2345, WP-CAB-1234)"
                               oninput="this.value = this.value.toUpperCase()">
                    </div>



                    <!-- Vehicle Specifications -->
                    <div class="form-group">
                        <label for="make">Make</label>
                        <input type="text" id="make" name="make">
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model">
                    </div>
                    <div class="form-group">
                        <label for="manufacturing_year">Manufacturing Year</label>
                        <input type="number" id="manufacturing_year" name="manufacturing_year" 
                               min="1900" max="<?php echo date('Y'); ?>"
                               title="Year must be between 1900 and current year"
                               placeholder="Enter manufacturing year" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" id="color" name="color" 
                               pattern="^[A-Za-z- ]+$"
                               title="Color should only contain letters, hyphens, and spaces">
                    </div>

                    <!-- Capacity Details -->
                    <div class="form-group">
                        <label for="capacity">Vehicle Capacity (kg)</label>
                        <input type="number" id="capacity" name="capacity" 
                               step="0.01" min="0" max="5000"
                               title="Capacity must be between 0 and 5000 kilos">
                    </div>

                    <!-- Vehicle Image -->
                    <div class="form-group">
                        <label for="vehicle_image">Vehicle Image</label>
                        <input type="file" 
                               id="vehicle_image" 
                               name="vehicle_image" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <div id="imagePreview" class="image-preview"></div>
                        <div id="uploadError" class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="table-data">
            <div class="order">
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Vehicle</button>
                </div>
            </div>
        </div>
    </form>
</main>

<style>
/* Using your existing table-data and order styles */

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    padding: 20px;
}

.document-section {
    border-bottom: 1px solid var(--grey);
    padding: 20px;
    margin-bottom: 20px;
}

.document-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.document-section h4 {
    color: var(--dark);
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.document-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
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

.form-group input:focus,
.form-group select:focus {
    border-color: var(--main);
    outline: none;
    box-shadow: 0 0 0 2px var(--light-main);
}

.form-group input[type="file"] {
    padding: 6px;
    background: var(--light);
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
    .form-grid,
    .document-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}

.image-preview {
    margin-top: 10px;
    max-width: 300px;
}

.image-preview img {
    width: 100%;
    height: auto;
    border-radius: 5px;
}

.text-muted {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 5px;
}
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

function validateForm() {

    // Image validation
    const imageInput = document.getElementById('vehicle_image');
    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (!allowedTypes.includes(file.type)) {
            alert('Please upload only JPG, JPEG or PNG images');
            return false;
        }
        
        if (fileSize > 5) {
            alert('Image size should not exceed 5MB');
            return false;
        }
    }

    return true;
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 