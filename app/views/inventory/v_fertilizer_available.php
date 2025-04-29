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
            <h1>Factory Fertilizer Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Fertilizer</a></li>
            </ul>
        </div>
       
    </div>

    <!-- Stats section similar to collection bags -->
    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-leaf'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['totalorder']) ? $data['totalorder'] : 0; ?></h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-check-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['approvedCount']) ? $data['approvedCount'] : 0; ?></h3>
                    <p>Approved this month</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-time'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['pendingCount']) ? $data['pendingCount'] : 0; ?></h3>
                    <p>Currently Pending</p>
                </div>
            </div>
        </li>
    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Incoming Requests</h3>
                <a href="<?php echo URLROOT; ?>/inventory/viewFertilizerRequests" class="btn btn-primary">
                    <i class='bx bx-show'></i>
                    View All Requests
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>

                        <th>Fertilizer Name</th>
                        <th>Company Name</th>
                        <th>Quantity</th>
                        <th>Inventory Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($fertilizerRequest)): ?>
                        <?php foreach ($data['fertilizerRequest'] as $fertilizer): ?>
                            <?php if($fertilizer->status == 'Pending'): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fertilizer->order_id); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/manageSupplier/<?php echo htmlspecialchars($fertilizer->supplier_id); ?>" class="manager-link">
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($fertilizer->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                                        <?php echo htmlspecialchars($fertilizer->full_name); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($fertilizer->fertilizer_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->company_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->order_quantity); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->order_date); ?></td>
                                <td>
                                    <span class="status-badge <?php 
                                        echo $fertilizer->status == 'Pending' ? 'oranged' : 
                                            ($fertilizer->status == 'Approved' ? 'added' : 'removed'); 
                                    ?>">
                                        <?php echo htmlspecialchars($fertilizer->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($fertilizer->status == 'Pending'): ?>
                                        <div style="display: flex; gap: 5px;">
                                            <!-- Approve button -->
                                            <form method="POST" action="<?php echo URLROOT; ?>/Inventory/fertilizer?id=<?php echo $fertilizer->order_id; ?>" style="display: inline-block;">
                                                <input hidden value="<?php echo $fertilizer->fertilizer_id; ?>" name="fertilizer_id">
                                                <input hidden type="number" value="<?php echo $fertilizer->order_quantity;?>" name="order_quantity">
                                                <input hidden type="number" value="<?php echo $fertilizer->supplier_id;?>" name="supplier_id">
                                                <input hidden type="text" value="<?php echo $fertilizer->full_name;?>" name="full_name">
                                                <input hidden type="text" value="<?php echo $fertilizer->email;?>" name="supplier_email">
                                                <button type="submit" name="status_approve" 
                                                    class="btn btn-tertiary" 
                                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                                    title="Approve">
                                                    <i class='bx bx-check-circle' style="font-size: 24px; color:green;"></i>
                                                </button>
                                            </form>

                                            <!-- Reject button -->
                                            <form method="POST" action="<?php echo URLROOT; ?>/Inventory/fertilizer?id=<?php echo $fertilizer->order_id; ?>" style="display: inline-block;">
                                                <input hidden value="<?php echo $fertilizer->fertilizer_id; ?>" name="fertilizer_id">
                                                <input hidden type="number" value="<?php echo $fertilizer->order_quantity;?>" name="order_quantity">
                                                <input hidden type="number" value="<?php echo $fertilizer->supplier_id;?>" name="supplier_id">
                                                <input hidden type="text" value="<?php echo $fertilizer->full_name;?>" name="full_name">
                                                <input hidden type="text" value="<?php echo $fertilizer->email;?>" name="supplier_email">
                                                <button type="submit" name="status_reject"
                                                    class="btn btn-tertiary" 
                                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                                    title="Reject">
                                                    <i class='bx bx-x-circle' style="font-size: 24px; color:red;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
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


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Fertilizers</h3>
                <a href="<?php echo URLROOT; ?>/inventory/createFertilizer" class="btn btn-primary">
                    <i class='bx bx-plus'></i>
                    Add Fertilizer
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Fertilizer ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <!-- <th>Unit</th> -->

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['fertilizer'] as $item): ?>
                        <tr class="fertilizer-row" data-fertilizer-id="<?php echo htmlspecialchars($item->id); ?>">
                            <td><?php echo htmlspecialchars($item->id); ?></td>
                            <td>
                                <img src="<?php echo URLROOT . '/uploads/fertilizers/' . htmlspecialchars($item->image_path); ?>" alt="Fertilizer Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                            </td>
                            <td><?php echo htmlspecialchars($item->fertilizer_name); ?></td>
                            <td><?php echo htmlspecialchars($item->company_name); ?></td>
                            <td> රු.  <?php echo htmlspecialchars($item->price); ?></td>
                            <td><?php echo htmlspecialchars($item->quantity); ?> kg</td>
                            <!-- <td><?php echo htmlspecialchars($item->unit); ?></td> -->

                            <td>
                                <div style="display: flex; gap: 5px;">

                                    <!-- Manage button -->
                                    <a 
                                        href="<?php echo URLROOT; ?>/inventory/updateFertilizer/<?php echo $item->id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bx-cog' style="font-size: 24px; color:green;"></i>
                                    </a>

                                    <!-- Delete button -->
                                    <a href="<?php echo URLROOT; ?>/inventory/deleteFertilizer/<?php echo $item->id; ?>" 
                                       class="btn btn-tertiary" 
                                       style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                       data-confirm="Do you want to delete this fertilizer: <?php echo $item->fertilizer_name; ?>" 
                                    >
                                        <i class='bx bx-trash' style="font-size: 24px; color:red;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</main>

<style>

</style>

<?php require APPROOT . '/views/inc/components/footer.php' ?>