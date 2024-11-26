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
                        <label for="license_plate">License Plate Number *</label>
                        <input type="text" id="license_plate" name="license_plate" required 
                               pattern="^(([A-Z]{2,3})-?([0-9]{4}))$|^([0-9]{2,3})-?([0-9]{4})$|^([A-Z]{2,3})-?([0-9]{4})-?([0-9]{1,3})$" 
                               title="Enter a valid Sri Lankan vehicle number (e.g., KA-1234, 19-2345, WP-CAB-1234)"
                               oninput="this.value = this.value.toUpperCase()">
                    </div>
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
                        <label for="condition">Condition</label>
                        <select id="condition" name="condition">
                            <option value="New">New</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
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
                               title="Year must be between 1900 and current year">
                    </div>
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" id="color" name="color" 
                               pattern="^[A-Za-z- ]+$"
                               title="Color should only contain letters, hyphens, and spaces">
                    </div>

                    <!-- Technical Details -->
                    <div class="form-group">
                        <label for="engine_number">Engine Number *</label>
                        <input type="text" id="engine_number" name="engine_number" required>
                    </div>
                    <div class="form-group">
                        <label for="chassis_number">Chassis Number *</label>
                        <input type="text" id="chassis_number" name="chassis_number" required>
                    </div>
                    <div class="form-group">
                        <label for="fuel_type">Fuel Type</label>
                        <select id="fuel_type" name="fuel_type">
                            <option value="Petrol">Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Electric">Electric</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mileage">Mileage (km)</label>
                        <input type="number" id="mileage" name="mileage" 
                               min="0" max="1500000"
                               title="Mileage must be between 0 and 1,500,000 km">
                    </div>

                    <!-- Capacity Details -->
                    <div class="form-group">
                        <label for="capacity">Cargo Capacity (Tons)</label>
                        <input type="number" id="capacity" name="capacity" 
                               step="0.01" min="0" max="100"
                               title="Capacity must be between 0 and 100 tons">
                    </div>
                    <div class="form-group">
                        <label for="seating_capacity">Seating Capacity</label>
                        <input type="number" id="seating_capacity" name="seating_capacity" 
                               min="2" max="60"
                               title="Seating capacity must be between 2 and 60 persons">
                    </div>

                    <!-- Owner Information -->
                    <div class="form-group">
                        <label for="owner_name">Owner Name</label>
                        <input type="text" id="owner_name" name="owner_name">
                    </div>
                    <div class="form-group">
                        <label for="owner_contact">Owner Contact</label>
                        <input type="text" id="owner_contact" name="owner_contact"
                               pattern="^(?:\+94|0)[1-9][0-9]{8}$"
                               title="Enter a valid Sri Lankan phone number (e.g., 0771234567 or +94771234567)">
                    </div>

                    <!-- Dates -->
                    <div class="form-group">
                        <label for="registration_date">Registration Date</label>
                        <input type="date" id="registration_date" name="registration_date">
                    </div>
                    <div class="form-group">
                        <label for="last_serviced_date">Last Serviced Date</label>
                        <input type="date" id="last_serviced_date" name="last_serviced_date">
                    </div>
                    <div class="form-group">
                        <label for="last_maintenance">Last Maintenance Date</label>
                        <input type="date" id="last_maintenance" name="last_maintenance">
                    </div>
                    <div class="form-group">
                        <label for="next_maintenance">Next Maintenance Date</label>
                        <input type="date" id="next_maintenance" name="next_maintenance">
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
                            <input type="text" id="revenue_number" name="documents[Revenue License][number]">
                        </div>
                        <div class="form-group">
                            <label for="revenue_issue">Issue Date</label>
                            <input type="date" id="revenue_issue" name="documents[Revenue License][issue_date]">
                        </div>
                        <div class="form-group">
                            <label for="revenue_expiry">Expiry Date</label>
                            <input type="date" id="revenue_expiry" name="documents[Revenue License][expiry_date]">
                        </div>
                        <div class="form-group">
                            <label for="revenue_file">Document File</label>
                            <input type="file" id="revenue_file" name="documents[Revenue License][file]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

                <!-- Insurance Certificate -->
                <div class="document-section">
                    <h4>Insurance Certificate</h4>
                    <div class="document-grid">
                        <div class="form-group">
                            <label for="insurance_number">Insurance Number</label>
                            <input type="text" id="insurance_number" name="documents[Insurance Certificate][number]">
                        </div>
                        <div class="form-group">
                            <label for="insurance_issue">Issue Date</label>
                            <input type="date" id="insurance_issue" name="documents[Insurance Certificate][issue_date]">
                        </div>
                        <div class="form-group">
                            <label for="insurance_expiry">Expiry Date</label>
                            <input type="date" id="insurance_expiry" name="documents[Insurance Certificate][expiry_date]">
                        </div>
                        <div class="form-group">
                            <label for="insurance_file">Document File</label>
                            <input type="file" id="insurance_file" name="documents[Insurance Certificate][file]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

                <!-- Emission Test -->
                <div class="document-section">
                    <h4>Emission Test Certificate</h4>
                    <div class="document-grid">
                        <div class="form-group">
                            <label for="emission_number">Certificate Number</label>
                            <input type="text" id="emission_number" name="documents[Emission Test][number]">
                        </div>
                        <div class="form-group">
                            <label for="emission_issue">Issue Date</label>
                            <input type="date" id="emission_issue" name="documents[Emission Test][issue_date]">
                        </div>
                        <div class="form-group">
                            <label for="emission_expiry">Expiry Date</label>
                            <input type="date" id="emission_expiry" name="documents[Emission Test][expiry_date]">
                        </div>
                        <div class="form-group">
                            <label for="emission_file">Document File</label>
                            <input type="file" id="emission_file" name="documents[Emission Test][file]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

                <!-- Route Permit -->
                <div class="document-section">
                    <h4>Route Permit</h4>
                    <div class="document-grid">
                        <div class="form-group">
                            <label for="permit_number">Permit Number</label>
                            <input type="text" id="permit_number" name="documents[Route Permit][number]">
                        </div>
                        <div class="form-group">
                            <label for="permit_issue">Issue Date</label>
                            <input type="date" id="permit_issue" name="documents[Route Permit][issue_date]">
                        </div>
                        <div class="form-group">
                            <label for="permit_expiry">Expiry Date</label>
                            <input type="date" id="permit_expiry" name="documents[Route Permit][expiry_date]">
                        </div>
                        <div class="form-group">
                            <label for="permit_file">Document File</label>
                            <input type="file" id="permit_file" name="documents[Route Permit][file]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
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
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });
});

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
    // Date validations
    const registrationDate = new Date(document.getElementById('registration_date').value);
    const lastServicedDate = new Date(document.getElementById('last_serviced_date').value);
    const lastMaintenanceDate = new Date(document.getElementById('last_maintenance').value);
    const nextMaintenanceDate = new Date(document.getElementById('next_maintenance').value);
    const today = new Date();

    // Registration date validation
    if (registrationDate > today) {
        alert('Registration date cannot be in the future');
        return false;
    }

    // Last serviced date validation
    if (lastServicedDate > today) {
        alert('Last serviced date cannot be in the future');
        return false;
    }

    // Last maintenance date validation
    if (lastMaintenanceDate > today) {
        alert('Last maintenance date cannot be in the future');
        return false;
    }

    // Next maintenance date validation
    if (nextMaintenanceDate < today) {
        alert('Next maintenance date must be in the future');
        return false;
    }

    // Document date validations
    const documentTypes = ['revenue', 'insurance', 'emission', 'permit'];
    for (const type of documentTypes) {
        const issueDate = new Date(document.getElementById(`${type}_issue`).value);
        const expiryDate = new Date(document.getElementById(`${type}_expiry`).value);
        
        if (issueDate && expiryDate) {
            if (issueDate > today) {
                alert(`${type.charAt(0).toUpperCase() + type.slice(1)} issue date cannot be in the future`);
                return false;
            }
            if (expiryDate <= issueDate) {
                alert(`${type.charAt(0).toUpperCase() + type.slice(1)} expiry date must be after issue date`);
                return false;
            }
        }
    }

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

    // Document file validations
    const documentFiles = ['revenue_file', 'insurance_file', 'emission_file', 'permit_file'];
    for (const fileId of documentFiles) {
        const fileInput = document.getElementById(fileId);
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const fileSize = file.size / 1024 / 1024; // Convert to MB
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
            
            if (!allowedTypes.includes(file.type)) {
                alert('Please upload only PDF, JPG, JPEG or PNG files for documents');
                return false;
            }
            
            if (fileSize > 10) {
                alert('Document size should not exceed 10MB');
                return false;
            }
        }
    }

    // Enhanced license plate validation
    const licensePlate = document.getElementById('license_plate').value;
    const licensePlatePatterns = {
        modern: /^([A-Z]{2,3})-?([0-9]{4})$/,               // CAB-1234 or WP-1234
        vintage: /^([0-9]{2,3})-?([0-9]{4})$/,              // 19-2345
        special: /^([A-Z]{2,3})-?([0-9]{4})-?([0-9]{1,3})$/ // WP-CAB-1234
    };

    if (!Object.values(licensePlatePatterns).some(pattern => pattern.test(licensePlate))) {
        alert('Please enter a valid Sri Lankan vehicle number plate format');
        return false;
    }

    // Enhanced phone number validation
    const phoneNumber = document.getElementById('owner_contact').value;
    const phonePattern = /^(?:\+94|0)[1-9][0-9]{8}$/;
    if (phoneNumber && !phonePattern.test(phoneNumber)) {
        alert('Please enter a valid Sri Lankan phone number (e.g., 0771234567 or +94771234567)');
        return false;
    }

    // Logical mileage validation based on vehicle type
    const vehicleType = document.getElementById('vehicle_type').value;
    const mileage = parseInt(document.getElementById('mileage').value);
    const manufacturingYear = parseInt(document.getElementById('manufacturing_year').value);
    const currentYear = new Date().getFullYear();
    const vehicleAge = currentYear - manufacturingYear;

    if (mileage > 0) { // Only validate if mileage is provided
        const averageAnnualMileage = mileage / (vehicleAge || 1); // Use 1 if vehicle age is 0
        if (averageAnnualMileage > 100000) { // 100,000 km per year is very high
            if (!confirm(`The average annual mileage (${Math.round(averageAnnualMileage)} km) seems unusually high. Are you sure this is correct?`)) {
                return false;
            }
        }
    }

    // Logical seating capacity validation based on vehicle type
    const seatingCapacity = parseInt(document.getElementById('seating_capacity').value);
    const maxSeatingCapacity = {
        'Car': 8,
        'Van': 15,
        'Bus': 60,
        'Truck': 3,
        'Three-Wheeler': 4,
        'Other': 60
    };

    if (seatingCapacity > maxSeatingCapacity[vehicleType]) {
        alert(`Maximum seating capacity for ${vehicleType} is ${maxSeatingCapacity[vehicleType]}`);
        return false;
    }

    // Add real-time formatting for license plate
    document.getElementById('license_plate').addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase();
        value = value.replace(/[^A-Z0-9-]/g, ''); // Remove invalid characters
        
        // Auto-format with hyphens based on the pattern
        if (value.match(/^[A-Z]{2,3}[0-9]{4}$/)) {
            value = value.replace(/([A-Z]{2,3})([0-9]{4})/, '$1-$2');
        } else if (value.match(/^[0-9]{2,3}[0-9]{4}$/)) {
            value = value.replace(/([0-9]{2,3})([0-9]{4})/, '$1-$2');
        }
        
        e.target.value = value;
    });

    // Add real-time formatting for phone numbers
    document.getElementById('owner_contact').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9+]/g, ''); // Remove invalid characters
        
        if (value.startsWith('+94') && value.length > 12) {
            value = value.slice(0, 12);
        } else if (value.startsWith('0') && value.length > 10) {
            value = value.slice(0, 10);
        }
        
        e.target.value = value;
    });

    return true;
}

// Add change event listener for vehicle type to update seating capacity limits
document.getElementById('vehicle_type').addEventListener('change', function() {
    const vehicleType = this.value;
    const seatingInput = document.getElementById('seating_capacity');
    const maxSeats = {
        'Car': 8,
        'Van': 15,
        'Bus': 60,
        'Truck': 3,
        'Three-Wheeler': 4,
        'Other': 60
    };
    
    seatingInput.max = maxSeats[vehicleType] || 60;
    seatingInput.title = `Seating capacity must be between 2 and ${maxSeats[vehicleType]} for ${vehicleType}`;
    
    if (parseInt(seatingInput.value) > maxSeats[vehicleType]) {
        seatingInput.value = maxSeats[vehicleType];
    }
});

// Add real-time validation for numeric inputs
document.addEventListener('DOMContentLoaded', function() {
    const numericInputs = document.querySelectorAll('input[type="number"]');
    numericInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            const min = parseFloat(this.min);
            const max = parseFloat(this.max);
            
            if (value < min) this.value = min;
            if (value > max) this.value = max;
        });
    });
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 