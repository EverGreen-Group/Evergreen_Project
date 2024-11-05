<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="landing-container">
    <h1>Evergreen Project - Temporary Navigation</h1>
    
    <div class="role-section">
        <h2>Vehicle Manager</h2>
        <ul>
            <li><a href="<?php echo URLROOT; ?>/vehiclemanager/index">Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/vehiclemanager/vehicles">Vehicle Management</a></li>
            <li><a href="<?php echo URLROOT; ?>/vehiclemanager/routes">Route Management</a></li>
        </ul>
    </div>

    <div class="role-section">
        <h2>Vehicle Driver</h2>
        <ul>
            <li><a href="<?php echo URLROOT; ?>/vehicledriver/index">Driver Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/vehicledriver/collections">Collections</a></li>
        </ul>
    </div>

    <div class="role-section">
        <h2>Test Users</h2>
        <table>
            <tr>
                <th>Role</th>
                <th>Username</th>
                <th>Password</th>
            </tr>
            <tr>
                <td>Vehicle Manager</td>
                <td>manager@test.com</td>
                <td>password123</td>
            </tr>
            <tr>
                <td>Vehicle Driver</td>
                <td>driver@test.com</td>
                <td>password123</td>
            </tr>
        </table>
    </div>
</div>

<style>
.landing-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
}

.role-section {
    background: #f5f5f5;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
}

.role-section ul {
    list-style: none;
    padding: 0;
}

.role-section ul li {
    margin: 10px 0;
}

.role-section a {
    color: #333;
    text-decoration: none;
    padding: 5px 10px;
    background: #fff;
    border-radius: 4px;
    display: inline-block;
}

.role-section a:hover {
    background: #e0e0e0;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #f0f0f0;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 