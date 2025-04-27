<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<?php print_r($data); ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>All Users Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>NIC</th>
                        <th>Date of Birth</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="user-row" data-user-id="<?php echo htmlspecialchars($user->user_id); ?>">
                                <td><?php echo htmlspecialchars($user->user_id); ?></td>
                                <td><?php echo htmlspecialchars($user->email); ?></td>
                                <td><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                                <td><?php echo htmlspecialchars($user->nic); ?></td>
                                <td><?php echo htmlspecialchars($user->date_of_birth); ?></td>
                                <td><?php echo htmlspecialchars($user->role_id); ?></td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <!-- View button -->
                                        <a 
                                            href="<?php echo URLROOT; ?>/admin/viewUser/<?php echo $user->user_id; ?>" 
                                            class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                        >
                                            <i class='bx bx-show' style="font-size: 24px; color:blue;"></i>
                                        </a>
                                        
                                        <!-- Remove role button -->
                                        <a 
                                            href="<?php echo URLROOT; ?>/admin/removeRole/<?php echo $user->user_id; ?>" 
                                            class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            onclick="return confirm('Are you sure you want to remove this user\'s role?');"
                                        >
                                            <i class='bx bx-user-x' style="font-size: 24px; color:red;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 