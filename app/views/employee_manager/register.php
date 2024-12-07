<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Register New Employee</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?php echo URLROOT; ?>/employeemanager/staff">Staff</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Register</a></li>
            </ul>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="registration-form">
        <form id="registrationForm" onsubmit="return handleRegistration(event)" enctype="multipart/form-data">
            <!-- Profile Photo Upload -->
            <div class="form-section photo-upload">
                <div class="profile-photo">
                    <img id="photoPreview" src="<?php echo URLROOT; ?>/img/profile/default.jpg" alt="Profile Photo">
                    <input type="file" id="profilePhoto" accept="image/*" onchange="previewPhoto(event)">
                    <label for="profilePhoto" class="upload-btn">
                        <i class='bx bx-upload'></i>
                        Upload Photo
                    </label>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="form-section">
                <h3><i class='bx bxs-user-detail'></i> Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nic">NIC Number *</label>
                        <input type="text" id="nic" name="nic" required>
                        <small class="error-message" id="nicError"></small>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="maritalStatus">Marital Status</label>
                        <select id="maritalStatus" name="maritalStatus">
                            <option value="">Select Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3><i class='bx bxs-contact'></i> Contact Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                        <small class="error-message" id="emailError"></small>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                        <small class="error-message" id="phoneError"></small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="province">Province *</label>
                        <select id="province" name="province" required>
                            <option value="">Select Province</option>
                            <?php foreach($data['provinces'] as $province): ?>
                                <option value="<?php echo $province; ?>"><?php echo $province; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="form-section">
                <h3><i class='bx bxs-briefcase'></i> Employment Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <?php foreach($data['departments'] as $dept): ?>
                                <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">Position *</label>
                        <input type="text" id="position" name="position" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="employmentType">Employment Type *</label>
                        <select id="employmentType" name="employmentType" required>
                            <option value="">Select Type</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Temporary">Temporary</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="joinDate">Join Date *</label>
                        <input type="date" id="joinDate" name="joinDate" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="basicSalary">Basic Salary (Rs.) *</label>
                        <input type="number" id="basicSalary" name="basicSalary" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="bankAccount">Bank Account Number *</label>
                        <input type="text" id="bankAccount" name="bankAccount" required>
                        <small class="error-message" id="bankAccountError"></small>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="form-section">
                <h3><i class='bx bxs-phone-call'></i> Emergency Contact</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="emergencyName">Contact Name *</label>
                        <input type="text" id="emergencyName" name="emergencyName" required>
                    </div>
                    <div class="form-group">
                        <label for="emergencyPhone">Contact Phone *</label>
                        <input type="tel" id="emergencyPhone" name="emergencyPhone" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="emergencyRelation">Relationship *</label>
                    <input type="text" id="emergencyRelation" name="emergencyRelation" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="reset" class="btn-reset">
                    <i class='bx bx-reset'></i>
                    Reset Form
                </button>
                <button type="submit" class="btn-submit">
                    <i class='bx bx-user-plus'></i>
                    Register Employee
                </button>
            </div>
        </form>
    </div>
</main>

<script>
// Preview profile photo
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// Handle form submission
async function handleRegistration(event) {
    event.preventDefault();
    
    // Clear previous error messages
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    // Create FormData object
    const formData = new FormData(event.target);
    
    try {
        const response = await AjaxUtils.post(`${URLROOT}/employeemanager/registerEmployee`, formData, true);
        
        if (response.success) {
            AjaxUtils.showSuccess('Employee registered successfully');
            setTimeout(() => {
                window.location.href = `${URLROOT}/employeemanager/staff`;
            }, 1500);
        }
    } catch (error) {
        if (error.response?.data?.errors) {
            // Display validation errors
            const errors = error.response.data.errors;
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(`${field}Error`);
                if (errorElement) {
                    errorElement.textContent = errors[field];
                }
            });
        }
        console.error('Error:', error);
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Add validation for NIC
    document.getElementById('nic').addEventListener('blur', validateNIC);
    
    // Add validation for email
    document.getElementById('email').addEventListener('blur', validateEmail);
    
    // Add validation for phone numbers
    document.getElementById('phone').addEventListener('blur', validatePhone);
    document.getElementById('emergencyPhone').addEventListener('blur', validatePhone);
    
    // Add validation for bank account
    document.getElementById('bankAccount').addEventListener('blur', validateBankAccount);
});

// ... (Add validation functions)
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 