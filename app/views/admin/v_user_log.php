<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/user_logs.css">

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>User Logs</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">User Logs</a></li>
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
                <form action="<?php echo URLROOT; ?>/admin/userLogs" method="GET">
                <div class="filter-group">
                        <label for="user_id">User ID:</label>
                        <input type="text" id="user_id" name="user_id" placeholder="Enter User ID" value="<?php echo isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : ''; ?>">
                    </div>
                    <div class="filter-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" placeholder="Enter email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>All User Logs</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                        <th>Message</th>
                        <th>URL</th>
                        <th>Status Code</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($data['userLogs']) && !empty($data['userLogs'])): ?>
                    <?php foreach ($data['userLogs'] as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log->user_id); ?></td>
                            <td><?php echo htmlspecialchars($log->email); ?></td>
                            <td><?php echo htmlspecialchars($log->ip_address); ?></td>
                            <td><?php echo htmlspecialchars($log->timestamp); ?></td>
                            <td><?php echo htmlspecialchars($log->message); ?></td>
                            <td><?php echo htmlspecialchars($log->url); ?></td>
                            <td><?php echo htmlspecialchars($log->status_code); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No logs found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            
            <div class="table-pagination">
                <div class="pagination">
                    <?php if ($data['totalPages'] > 1): ?>
                        <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                            <a 
                            href="<?php echo URLROOT; ?>/admin/userLogs?page=<?php echo $i; ?>&user_id=<?php echo urlencode($data['user_id']); ?>&email=<?php echo urlencode($data['email']); ?>" 
                            <?php if ($data['currentPage'] == $i) { echo 'class="active"'; } ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>
