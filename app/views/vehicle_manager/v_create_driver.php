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
            <h1>Create Driver</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/manager/driver">Drivers</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Create Driver</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if(!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>
    
    <form id="createDriverForm" method="POST" action="<?php echo URLROOT; ?>/manager/createDriver" enctype="multipart/form-data">
    
    <!-- Account Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Account Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label" for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Personal Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Personal Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label" for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="nic">NIC:</label>
                    <input type="text" id="nic" name="nic" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="contact_number">Contact Number:</label>
                    <input type="tel" id="contact_number" name="contact_number" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="emergency_contact">Emergency Contact:</label>
                    <input type="tel" id="emergency_contact" name="emergency_contact" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Driver Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <label class="label" for="license_number">License Number:</label>
                    <input type="text" id="license_number" name="license_number" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="license_expiry_date">License Expiry Date:</label>
                    <input type="date" id="license_expiry_date" name="license_expiry_date" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="hire_date">Hire Date:</label>
                    <input type="date" id="hire_date" name="hire_date" class="form-control" required>
                </div>
                <div class="info-row">
                    <label class="label" for="status">Status:</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                </div>
                <div class="info-row">
                    <label class="label" for="driver_image">Driver Photo:</label>
                    <input type="file" id="driver_image" name="driver_image" class="form-control" accept="image/*" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Summary -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Driver Summary</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value" id="summaryName">Not specified</span>
                </div>
                <div class="info-row">
                    <span class="label">NIC:</span>
                    <span class="value" id="summaryNIC">Not specified</span>
                </div>
                <div class="info-row">
                    <span class="label">Contact:</span>
                    <span class="value" id="summaryContact">Not specified</span>
                </div>
                <div class="info-row">
                    <span class="label">License:</span>
                    <span class="value" id="summaryLicense">Not specified</span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value" id="summaryStatus">Not specified</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Create Driver</button>
    </form>
</main>

<!-- Add JavaScript to handle selections and validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const nicInput = document.getElementById('nic');
    const contactInput = document.getElementById('contact_number');
    const licenseInput = document.getElementById('license_number');
    const licenseExpiryInput = document.getElementById('license_expiry_date');
    const statusSelect = document.getElementById('status');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Summary elements
    const summaryName = document.getElementById('summaryName');
    const summaryNIC = document.getElementById('summaryNIC');
    const summaryContact = document.getElementById('summaryContact');
    const summaryLicense = document.getElementById('summaryLicense');
    const summaryStatus = document.getElementById('summaryStatus');
    
    // Update summary when names are entered
    firstNameInput.addEventListener('input', updateNameDisplay);
    lastNameInput.addEventListener('input', updateNameDisplay);
    
    function updateNameDisplay() {
        if (firstNameInput.value && lastNameInput.value) {
            summaryName.textContent = `${firstNameInput.value} ${lastNameInput.value}`;
        } else {
            summaryName.textContent = 'Not specified';
        }
    }
    
    // Update summary when NIC is entered
    nicInput.addEventListener('input', function() {
        summaryNIC.textContent = this.value || 'Not specified';
    });
    
    // Update summary when contact is entered
    contactInput.addEventListener('input', function() {
        summaryContact.textContent = this.value || 'Not specified';
    });
    
    // Update summary when license info is entered
    licenseInput.addEventListener('input', updateLicenseDisplay);
    licenseExpiryInput.addEventListener('change', updateLicenseDisplay);
    
    function updateLicenseDisplay() {
        if (licenseInput.value) {
            let displayText = licenseInput.value;
            if (licenseExpiryInput.value) {
                const expiryDate = new Date(licenseExpiryInput.value);
                const formattedDate = expiryDate.toLocaleDateString();
                displayText += ` (Expires: ${formattedDate})`;
            }
            summaryLicense.textContent = displayText;
        } else {
            summaryLicense.textContent = 'Not specified';
        }
    }
    
    // Update summary when status is selected
    statusSelect.addEventListener('change', function() {
        summaryStatus.textContent = this.value || 'Not specified';
    });
    
    // Form validation
    document.getElementById('createDriverForm').addEventListener('submit', function(e) {
        // Password validation
        if (passwordInput.value !== confirmPasswordInput.value) {
            e.preventDefault();
            alert('Passwords do not match');
            return;
        }
        
        // NIC format validation (Sri Lankan format)
        const nicPattern = /^([0-9]{9}[vVxX]|[0-9]{12})$/;
        if (!nicPattern.test(nicInput.value)) {
            e.preventDefault();
            alert('Please enter a valid NIC number (9 digits + V/X or 12 digits)');
            return;
        }
        
        // Phone number validation
        const phonePattern = /^(?:\+94|0)[0-9]{9}$/;
        if (!phonePattern.test(contactInput.value)) {
            e.preventDefault();
            alert('Please enter a valid phone number (format: 0XXXXXXXXX or +94XXXXXXXXX)');
            return;
        }
        
        // License expiry date validation
        const today = new Date();
        const expiryDate = new Date(licenseExpiryInput.value);
        if (expiryDate <= today) {
            e.preventDefault();
            alert('License expiry date must be in the future');
            return;
        }
    });
});
</script>

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
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 