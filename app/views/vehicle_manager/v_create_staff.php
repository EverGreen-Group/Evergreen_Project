<?php require APPROOT.'/views/inc/components/header.php'?>
<?php require APPROOT.'/views/inc/components/topnavbar.php'?>
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/staff_registration.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Register Staff</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Register Staff</a></li>
            </ul>
        </div>
    </div>

    <div class="registration-form-container">
        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger"><?php echo $data['error']; ?></div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/vehiclemanager/createStaff" method="POST" enctype="multipart/form-data">
            <!-- Role Selection -->
            <div class="form-section">
                <h3>Role Selection</h3>
                <div class="form-group">
                    <label for="role">Staff Role*</label>
                    <select id="role" name="role" required onchange="toggleRoleSpecificFields()">
                        <option value="">Select Role</option>
                        <option value="Driver">Driver</option>
                        <option value="Driving Partner">Driving Partner</option>
                        <option value="Vehicle Manager">Vehicle Manager</option>
                    </select>
                </div>
            </div>

            <!-- Base User Information -->
            <div class="form-section">
                <h3>Basic Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name*</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name*</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth*</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Default Password*</label>
                        <div class="password-input-group">
                            <input type="password" id="password" name="password" required minlength="6">
                            <i class='bx bx-hide toggle-password'></i>
                        </div>
                        <small>Minimum 6 characters. Staff can change this after first login.</small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password*</label>
                        <div class="password-input-group">
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                            <i class='bx bx-hide toggle-password'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3>Contact Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="primary_phone">Primary Phone*</label>
                        <input type="tel" id="primary_phone" name="primary_phone" required pattern="[0-9]{10}">
                    </div>
                    <div class="form-group">
                        <label for="secondary_phone">Secondary Phone</label>
                        <input type="tel" id="secondary_phone" name="secondary_phone" pattern="[0-9]{10}">
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="form-section">
                <h3>Address Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address_line1">Address Line 1*</label>
                        <input type="text" id="address_line1" name="address_line1" required>
                    </div>
                    <div class="form-group">
                        <label for="address_line2">Address Line 2</label>
                        <input type="text" id="address_line2" name="address_line2">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City*</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Postal Code*</label>
                        <input type="text" id="postal_code" name="postal_code" required pattern="[0-9]{5}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="province">Province*</label>
                        <select id="province" name="province" required>
                            <option value="">Select Province</option>
                            <option value="Western">Western</option>
                            <option value="Central">Central</option>
                            <option value="Southern">Southern</option>
                            <option value="Northern">Northern</option>
                            <option value="Eastern">Eastern</option>
                            <option value="North Western">North Western</option>
                            <option value="North Central">North Central</option>
                            <option value="Uva">Uva</option>
                            <option value="Sabaragamuwa">Sabaragamuwa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="district">District*</label>
                        <select id="district" name="district" required>
                            <option value="">Select District</option>
                            <!-- Districts will be populated based on selected province via JavaScript -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- Employee Information -->
            <div class="form-section">
                <h3>Employee Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nic">NIC Number*</label>
                        <input type="text" id="nic" name="nic" required>
                        <small>Old format: 123456789V or New format: 123456789012</small>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender*</label>
                        <select id="gender" name="gender" required>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="hire_date">Hire Date*</label>
                        <input type="date" id="hire_date" name="hire_date" required>
                    </div>
                    <div class="form-group">
                        <label for="profile_photo">Profile Photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- Role Specific Fields -->
            <div id="driver-fields" class="form-section role-specific" style="display: none;">
                <h3>Driver Information</h3>
                <div class="form-group">
                    <label for="license_no">License Number*</label>
                    <input type="text" id="license_no" name="license_no">
                </div>
            </div>

            <div id="partner-fields" class="form-section role-specific" style="display: none;">
                <h3>Partner Information</h3>
                <!-- Any partner-specific fields would go here -->
            </div>

            <div id="manager-fields" class="form-section role-specific" style="display: none;">
                <h3>Manager Information</h3>
                <div class="form-group">
                    <label for="manager_type">Manager Type*</label>
                    <select id="manager_type" name="manager_type" disabled>
                        <option value="Vehicle Manager">Vehicle Manager</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-submit">Register Staff</button>
        </form>
    </div>
</main>

<script>
// Province-District mapping
const districtsByProvince = {
    'Western': ['Colombo', 'Gampaha', 'Kalutara'],
    'Central': ['Kandy', 'Matale', 'Nuwara Eliya'],
    'Southern': ['Galle', 'Matara', 'Hambantota'],
    'Northern': ['Jaffna', 'Kilinochchi', 'Mannar', 'Mullaitivu', 'Vavuniya'],
    'Eastern': ['Batticaloa', 'Ampara', 'Trincomalee'],
    'North Western': ['Kurunegala', 'Puttalam'],
    'North Central': ['Anuradhapura', 'Polonnaruwa'],
    'Uva': ['Badulla', 'Monaragala'],
    'Sabaragamuwa': ['Ratnapura', 'Kegalle']
};

// Update districts when province changes
document.getElementById('province').addEventListener('change', function() {
    const districts = districtsByProvince[this.value] || [];
    const districtSelect = document.getElementById('district');
    districtSelect.innerHTML = '<option value="">Select District</option>' +
        districts.map(district => 
            `<option value="${district}">${district}</option>`
        ).join('');
});

// Toggle role-specific fields
function toggleRoleSpecificFields() {
    const role = document.getElementById('role').value;
    document.querySelectorAll('.role-specific').forEach(div => div.style.display = 'none');
    
    if (role) {
        document.getElementById(`${role}-fields`).style.display = 'block';
    }

    // Update required fields based on role
    updateRequiredFields(role);
}

// Validate NIC format
document.getElementById('nic').addEventListener('input', function() {
    const nic = this.value.toUpperCase();
    const oldFormat = /^[0-9]{9}[VX]$/;
    const newFormat = /^[0-9]{12}$/;
    
    if (!(oldFormat.test(nic) || newFormat.test(nic))) {
        this.setCustomValidity('Please enter a valid NIC number');
    } else {
        this.setCustomValidity('');
    }
});

// Password toggle functionality
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function() {
        const input = this.previousElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('bx-hide');
            this.classList.add('bx-show');
        } else {
            input.type = 'password';
            this.classList.remove('bx-show');
            this.classList.add('bx-hide');
        }
    });
});

// Password validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    if (this.value !== password) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php require APPROOT.'/views/inc/components/footer.php'?>