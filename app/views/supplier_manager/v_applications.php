<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>

    
    <div class="head-title">
        <div class="left">
            <h1>Supplier Applications</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Applications</a></li>
            </ul>
        </div>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-file'></i>
            <span class="text">
                <h3>10</h3>
                <p>Total Applications</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-check-circle'></i>
            <span class="text">
                <h3>5</h3>
                <p>Approved</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-x-circle'></i>
            <span class="text">
                <h3>3</h3>
                <p>Rejected</p>
            </span>
        </li>
    </ul>

    <!-- Applications Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Applications</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>User ID</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['applications'] as $application): ?>
                        <tr>
                            <td>APP<?= str_pad($application->application_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $application->user_id ?></td>
                            <td>
                                <span class="status-badge <?= strtolower($application->status) ?>">
                                    <?= ucfirst($application->status) ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($application->created_at)) ?></td>
                            <td>
                                <a href="<?= URLROOT ?>/suppliermanager/viewApplication/<?= $application->application_id ?>" class="btn-view">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approved Applications without Supplier Role -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Approved Applications (Pending Role Assignment)</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>User Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['approved_pending_role'] as $application): ?>
                        <tr>
                            <td>APP<?= str_pad($application->application_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $application->user_name ?></td>
                            <td>
                                <a href="<?= URLROOT ?>/suppliermanager/confirmSupplierRole/<?= $application->application_id ?>" 
                                   class="btn-confirm">
                                    <i class='bx bx-user-check'></i> Confirm Role
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 500;
    }

    .status-badge.pending {
        background-color: #ffd700;
        color: #000;
    }

    .status-badge.approved {
        background-color: #4CAF50;
        color: white;
    }

    .status-badge.rejected {
        background-color: #f44336;
        color: white;
    }

    .btn-view, .btn-approve, .btn-reject {
        padding: 5px 10px;
        border-radius: 4px;
        margin: 0 2px;
        text-decoration: none;
        font-size: 0.9em;
    }

    .btn-view {
        background-color: #007bff;
        color: white;
    }

    .btn-approve {
        background-color: #4CAF50;
        color: white;
    }

    .btn-reject {
        background-color: #f44336;
        color: white;
    }

    .btn-view:hover, .btn-approve:hover, .btn-reject:hover {
        opacity: 0.8;
    }

    .table-data .order {
        background: var(--light);
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-data .head {
        display: flex;
        align-items: center;
        margin-bottom: 24px;
    }

    .table-data .head h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
    }

    .table-data table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-data table th {
        padding: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        text-align: left;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
    }

    .table-data table td {
        padding: 12px;
        font-size: 0.9rem;
        color: var(--dark);
        border-bottom: 1px solid #eee;
    }

    .table-data table tr:hover {
        background: #f8f9fa;
    }

    .btn-confirm {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        margin: 0 2px;
        text-decoration: none;
        font-size: 0.9em;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-confirm:hover {
        opacity: 0.8;
    }

    .btn-confirm i {
        font-size: 1.1rem;
    }
</style>