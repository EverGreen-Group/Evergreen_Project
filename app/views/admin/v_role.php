<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<!-- Add Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

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

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-user'></i>
                <div class="stat-info">
                    <h3><?php echo $data['totalUsers']; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-user'></i>
                <div class="stat-info">
                    <h3><?php echo $data['normalUsers']; ?></h3>
                    <p>Total Web Users</p>
                </div>
            </div>
        </li>

    </ul>

    <!-- Charts Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>User Analytics</h3>
            </div>
            <div class="chart-container" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1; min-width: 300px; height: 300px;">
                    <h4 style="text-align: center; margin-bottom: 10px;">Monthly User Registration</h4>
                    <canvas id="monthlyRegistrationChart"></canvas>
                </div>
                <div style="flex: 1; min-width: 300px; height: 300px;">
                    <h4 style="text-align: center; margin-bottom: 10px;">User Role Distribution</h4>
                    <canvas id="roleDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Search Filters</h3>
                <i class='bx bx-search'></i>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/admin/index" method="GET">
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
                        <label for="nic">NIC:</label>
                        <input type="text" id="nic" name="nic" placeholder="Enter NIC" value="<?php echo isset($_GET['nic']) ? htmlspecialchars($_GET['nic']) : ''; ?>">
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
                        <th>Name</th>
                        <th>NIC</th>
                        <th>Date of Birth</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($data['allUsers']) && !empty($data['allUsers'])): ?>
                    <?php foreach ($data['allUsers'] as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user->user_id); ?></td>
                            <td><?php echo htmlspecialchars($user->email); ?></td>
                            <td><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                            <td><?php echo htmlspecialchars($user->nic); ?></td>
                            <td><?php echo htmlspecialchars($user->date_of_birth); ?></td>
                            <td><?php echo htmlspecialchars($user->role_name); ?></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a 
                                        href="<?php echo URLROOT; ?>/auth/profile/<?php echo $user->user_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bx-show' style="font-size: 24px; color:blue;"></i> 
                                    </a>

                                    <form action="<?php echo URLROOT; ?>/user/deleteUser/" method="POST" style="margin: 0;"> 
                                        <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
                                        <button type="submit" class="btn btn-tertiary" 
                                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class='bx bx-user-x' style="font-size: 24px; color:red;"></i> 
                                        </button>
                                    </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyRegistrationData = <?php echo json_encode($data['monthlyRegistration']); ?>;
    const monthlyLabels = monthlyRegistrationData.map(item => item.month_year);
    const monthlyValues = monthlyRegistrationData.map(item => item.count);
    
    const monthlyChart = new Chart(
        document.getElementById('monthlyRegistrationChart'),
        {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'User Registrations',
                    data: monthlyValues,
                    borderColor: '#1775F1',
                    backgroundColor: 'rgba(23, 117, 241, 0.2)',
                    fill: true,
                    tension: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        }
    );

    // Role Distribution Chart
    const roleData = <?php echo json_encode($data['roleDistribution']); ?>;
    const roleLabels = roleData.map(item => item.role_name);
    const roleValues = roleData.map(item => item.count);
    
    const roleChart = new Chart(
        document.getElementById('roleDistributionChart'),
        {
            type: 'doughnut',
            data: {
                labels: roleLabels,
                datasets: [{
                    data: roleValues,
                    backgroundColor: [
                        '#1775F1',
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        }
    );
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>