<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit User</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/Admin/users">Users</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Edit User</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if(!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>
    
    <form id="editUserForm" method="POST" action="<?php echo URLROOT; ?>/Admin/updateUser">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($data['user']->user_id); ?>">
        
        <!-- Basic Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>User Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($data['user']->email); ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required value="<?php echo htmlspecialchars($data['user']->first_name); ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required value="<?php echo htmlspecialchars($data['user']->last_name); ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Personal Details -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Personal Details</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="nic">NIC:</label>
                        <input type="text" id="nic" name="nic" class="form-control" required value="<?php echo htmlspecialchars($data['user']->nic); ?>">
                    </div>
                    <div class="info-row">
                        <label class="label" for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required value="<?php echo htmlspecialchars($data['user']->date_of_birth); ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Role Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Assigned Role</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="role">User Role:</label>
                        <select id="role" name="role" class="form-control" required>
                            <?php foreach ($data['allRoles'] as $role): ?>
                                <option value="<?php echo htmlspecialchars($role->role_id); ?>" <?php echo ($role->role_id == $data['user']->role_id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($role->role_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Save Changes</button>
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
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>