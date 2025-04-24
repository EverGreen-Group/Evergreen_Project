<?php require APPROOT . '/views/inc/components/header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
</head>

<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Fertilizer Available</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Fertilizer</a></li>
            </ul>
        </div>
        <a href="<?php echo URLROOT; ?>/inventory/fertilizerdashboard">
            <button class="btn btn-primary">Fertilizer Dashboard</button>
        </a>
    </div>

    <!-- Stats section similar to collection bags -->
    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-leaf'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['totalorder']) ? $data['totalorder'] : 0; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-check-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['approvedCount']) ? $data['approvedCount'] : 0; ?></h3>
                    <p>Approved</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-time'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['pendingCount']) ? $data['pendingCount'] : 0; ?></h3>
                    <p>Pending</p>
                </div>
            </div>
        </li>
    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Available Orders</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>quantity</th>
                        <th>Inventory Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($fertilizers)): ?>
                        <?php foreach ($fertilizers as $fertilizer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fertilizer->first_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->address); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->quantity); ?>kg</td>
                                <td><?php echo htmlspecialchars($fertilizer->order_date); ?></td>
                                <td>
                                    <span class="status-badge <?php 
                                        echo $fertilizer->status == 'Pending' ? 'pending' : 
                                            ($fertilizer->status == 'Approved' ? 'approved' : 'rejected'); 
                                    ?>">
                                        <?php echo htmlspecialchars($fertilizer->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($fertilizer->status == 'Pending'): ?>
                                        <form method="POST" action="<?php echo URLROOT; ?>/Inventory/fertilizer?id=<?php echo $fertilizer->order_id; ?>" style="display: inline-block;">
                                            <input hidden value="<?php echo $fertilizer->fertilizer_id; ?>" name="fertilizer_id">
                                            <input hidden type="number" value="<?php echo $fertilizer->quantity;?>" name="quantity">
                                            <button class="btn btn-primary" type="submit" name="status_approve" title="Approve">
                                                <i class='bx bx-check'></i> Approve
                                            </button>
                                        </form>
                                    
                                    <form method="POST" action="<?php echo URLROOT; ?>/Inventory/fertilizer?id=<?php echo $fertilizer->order_id; ?>" style="display: inline-block; margin-left: 5px;">
                                        <button class="btn btn-danger" type="submit" name="status_reject" title="Reject">
                                            <i class='bx bx-x'></i> Reject
                                        </button>
                                    </form>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No fertilizers available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .pending {
        background-color: #FFF4DE;
        color: #FFA800;
    }

    .approved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }

    .rejected {
        background-color: #FFE2E5;
        color: #F64E60;
    }

    .btn-primary {
        /* background-color: #3C91E6;
        color: white; */
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }


    .btn-danger {
        background-color: #F64E60;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-danger:hover {
        background-color: #E03E4C;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php' ?>