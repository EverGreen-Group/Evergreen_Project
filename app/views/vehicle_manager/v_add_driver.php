<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Add Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Enroll a New Driver into the System</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/suppliermanager/applications">Driver Role</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Add Driver</a></li>
            </ul>
        </div>
    </div>
    <form id="createDriverForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/addDriver" enctype="multipart/form-data">
    <!-- User Selection Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Select User</h3>
            </div>
            <div class="section-content">
                <div class="user-selection-container">
                    <select id="userSelect" name="user_id" class="form-control" required>
                        <option value="">Select a User</option>
                        <?php foreach ($data['users'] as $user): ?>
                            <option value="<?= $user->user_id ?>" 
                                    data-first-name="<?= htmlspecialchars($user->first_name) ?>"
                                    data-last-name="<?= htmlspecialchars($user->last_name) ?>"
                                    data-email="<?= htmlspecialchars($user->email) ?>"
                                    data-nic="<?= htmlspecialchars($user->nic) ?>"
                                    data-dob="<?= $user->date_of_birth ?>"
                                    data-gender="<?= $user->gender ?>"
                                    data-role-id="<?= $user->role_id ?>"
                            >
                                <?= htmlspecialchars($user->first_name . ' ' . $user->last_name . ' (' . $user->nic . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>General Information</h3>
            </div>
            <div class="section-content">
                <div class="info-row">
                    <span class="label">First Name:</span>
                    <span class="value" id="firstName"><?= $data['application']['first_name'] ?? '' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Last Name:</span>
                    <span class="value" id="lastName"><?= $data['application']['last_name'] ?? '' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value" id="email"><?= $data['application']['email'] ?? '' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">NIC:</span>
                    <span class="value" id="nic"><?= $data['application']['nic'] ?? '' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Date of Birth:</span>
                    <span class="value" id="dateOfBirth"><?= $data['application']['date_of_birth'] ?? '' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Gender:</span>
                    <span class="value" id="gender"><?= $data['application']['gender'] ?? '' ?></span>
                </div>
            </div>
        </div>
    </div>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Driver Information</h3>
            </div>
            <div class="section-content">
                <div class="address-container">
                    <div class="address-details">
                        <div class="info-row">
                            <label class="label" for="address_line1">Address Line 1:</label>
                            <input type="text" id="address_line1" name="address_line1" class="form-control" placeholder="Enter Address Line 1" required>
                        </div>
                        <div class="info-row">
                            <label class="label" for="address_line2">Address Line 2:</label>
                            <input type="text" id="address_line2" name="address_line2" class="form-control" placeholder="Enter Address Line 2">
                        </div>
                        <div class="info-row">
                            <label class="label" for="city">City:</label>
                            <input type="text" id="city" name="city" class="form-control" placeholder="Enter City" required>
                        </div>
                        <div class="info-row">
                            <label class="label" for="contact_number">Contact Number:</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Enter Contact Number" required>
                        </div>
                        <div class="info-row">
                            <label class="label" for="emergency_contact">Emergency Contact:</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" class="form-control" placeholder="Enter Emergency Contact Number" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Document Upload</h3>
            </div>
            <div class="section-content">
                <div class="document-upload-container">
                    <div class="info-row">
                        <label class="label" for="driver_photo">Driver Photo:</label>
                        <input type="file" id="driver_photo" name="driver_photo" class="form-control" accept="image/*" required>
                    </div>
                    <div class="info-row">
                        <label class="label" for="license_card">License Card:</label>
                        <input type="file" id="license_card" name="license_card" class="form-control" accept="image/*,application/pdf" required>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Add Driver</button>
    </form>
</main>

<!-- Add JavaScript to handle user selection -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('userSelect');
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const email = document.getElementById('email');
    const nic = document.getElementById('nic');
    const dateOfBirth = document.getElementById('dateOfBirth');
    const gender = document.getElementById('gender');

    userSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            firstName.textContent = selectedOption.getAttribute('data-first-name');
            lastName.textContent = selectedOption.getAttribute('data-last-name');
            email.textContent = selectedOption.getAttribute('data-email');
            nic.textContent = selectedOption.getAttribute('data-nic');
            dateOfBirth.textContent = selectedOption.getAttribute('data-dob');
            gender.textContent = selectedOption.getAttribute('data-gender');
        } else {
            // Reset fields if no user is selected
            firstName.textContent = '';
            lastName.textContent = '';
            email.textContent = '';
            nic.textContent = '';
            dateOfBirth.textContent = '';
            gender.textContent = '';
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        padding: 16px 20px;
        border-top: 1px solid #f0f0f0;
    }

    .btn-approve,
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-approve {
        background-color: #10b981;
        color: white;
    }

    .btn-approve:hover {
        background-color: #059669;
    }

    .btn-reject {
        background-color: #ef4444;
        color: white;
    }

    .btn-reject:hover {
        background-color: #dc2626;
    }

    /* Document Grid Styles */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .document-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .document-content {
        position: relative;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        background: #f8f9fa;
    }

    .document-preview {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .document-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 48px;
        color: #dc3545;
    }

    .document-info {
        padding: 12px;
        background: #fff;
    }

    .document-type {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background-color: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 13px;
        transition: background-color 0.2s;
    }

    .btn-view:hover {
        background-color: #2563eb;
    }

    .no-documents {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 14px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 500;
    }

    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.rejected {
        background-color: #fee2e2;
        color: #991b1b;
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

    /* Add to your existing style block */
    .address-container {
        display: flex;
        gap: 20px;
        padding: 20px;
        min-height: 300px;
    }

    .address-details {
        flex: 1;
    }

    #map {
        flex: 1;
        min-height: 300px;
        height: 300px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        z-index: 1;
    }

    /* Make it responsive */
    @media (max-width: 768px) {
        .address-container {
            flex-direction: column;
        }
        
        #map {
            height: 250px;
        }
    }

    /* Update these styles in your CSS section */

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background-color: white;
    }

    thead tr {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    th {
        color: #2c3e50;
        font-weight: 600;
        padding: 12px 15px;
        text-align: left;
    }

    td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
        color: #000000;
    }

    tbody tr {
        background-color: white;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Update button style to match approve button */
    .btn-confirm {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-confirm:hover {
        background-color: #059669;
    }

    .btn-confirm i {
        font-size: 16px;
    }

    /* Add styles for user selection dropdown */
    .user-selection-container {
        padding: 20px;
    }

    #userSelect {
        width: 100%;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #007bff; /* Change border color on focus */
        outline: none; /* Remove default outline */
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
        border-color: #007bff; /* Change border color on focus */
        outline: none; /* Remove default outline */
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 