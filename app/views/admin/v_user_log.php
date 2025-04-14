<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_admin.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

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
                <h3>All User Logs</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <!-- <th>ID</th> -->
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
                            <!-- <td><?php echo htmlspecialchars($log->id); ?></td> -->
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
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>