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
                <h3>Search Filters</h3>
                <i class='bx bx-search'></i>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/admin/users" method="GET">
                    <div class="filter-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" placeholder="Enter email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>
                    <div class="filter-group">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first_name" placeholder="Enter first name" value="<?php echo isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : ''; ?>">
                    </div>
                    <div class="filter-group">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last_name" placeholder="Enter last name" value="<?php echo isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : ''; ?>">
                    </div>
                    <div class="filter-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role">
                            <option value="">Select Role</option>
                            <?php foreach ($data['allRoles'] as $role): ?>
                                <option value="<?php echo htmlspecialchars($role->role_id); ?>" <?php echo (isset($_GET['role']) && $_GET['role'] == $role->role_id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($role->role_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>All Users</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data['allUsers'] as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user->user_id); ?></td>
                        <td><?php echo htmlspecialchars($user->email); ?></td>
                        <td><?php echo htmlspecialchars($user->first_name); ?></td>
                        <td><?php echo htmlspecialchars($user->last_name); ?></td>
                        <td><?php echo htmlspecialchars($user->role_id); ?></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <!-- Manage button with icon only -->
                                <a 
                                    href="<?php echo URLROOT; ?>/admin/manageUser/<?php echo $user->user_id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-cog' style="font-size: 24px; color:green;"></i> <!-- Boxicon for settings -->
                                </a>
                                
                                <!-- Delete button with icon only -->
                                <form action="<?php echo URLROOT; ?>/user/deleteUser/" method="POST" style="margin: 0;"> 
                                    <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
                                    <button type="submit" class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                        onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class='bx bx-trash' style="font-size: 24px; color:red;"></i> <!-- Boxicon for trash -->
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>


