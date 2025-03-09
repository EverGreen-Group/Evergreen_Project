<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>User Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">User Management</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Edit User Details</h3>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/admin/updateUser" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($data['user']->user_id); ?>">
                    
                    <div class="filter-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data['user']->email); ?>" placeholder="Enter email" required>
                    </div>
                    <div class="filter-group">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($data['user']->first_name); ?>" placeholder="Enter first name" required>
                    </div>
                    <div class="filter-group">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($data['user']->last_name); ?>" placeholder="Enter last name" required>
                    </div>
                    <div class="filter-group">
                        <label for="nic">NIC:</label>
                        <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($data['user']->nic); ?>" placeholder="Enter NIC" required>
                    </div>
                    <div class="filter-group">
                        <label for="date-of-birth">Date of Birth:</label>
                        <input type="date" id="date-of-birth" name="date_of_birth" value="<?php echo htmlspecialchars($data['user']->date_of_birth); ?>" required>
                    </div>
                    <div class="filter-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role" required>
                            <option value="<?php echo $data['user']->role_id?>"><?php echo $data['user']->role_name?></option>
                            <?php foreach ($data['allRoles'] as $role): ?>
                                <option value="<?php echo htmlspecialchars($role->role_id); ?>" <?php echo ($role->role_id == $data['user']->role_id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($role->role_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>


