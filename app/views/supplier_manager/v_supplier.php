<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/vehicle.js"></script>

<main>

        <div class="head-title">
            <div class="left">
                <h1>Supplier Management</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                </ul>
            </div>
        </div>

        <div class="action-buttons">
                <!-- <a href="<?php echo URLROOT; ?>/manager/viewInactiveSuppliers" class="btn btn-primary">
                    <i class='bx bx-show'></i>
                    View Inactive Suppliers
                </a> -->
            </div>

            <ul class="dashboard-stats">
                <li class="stat-card">
                    <div class="stat-content">
                        <i class='bx bxs-user'></i>
                        <div class="stat-info">
                            <h3><?php echo $total_suppliers; ?></h3>
                            <p>Total Suppliers</p>
                        </div>
                    </div>
                </li>

                <li class="stat-card">
                    <div class="stat-content">
                        <i class='bx bx-check'></i>
                        <div class="stat-info">
                            <h3><?php echo $active_suppliers; ?></h3>
                            <p>Currently Active</p>
                        </div>
                    </div>
                </li>

            </ul>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Search Filters</h3>
                    <i class='bx bx-search'></i>
                </div>
                <div class="filter-options">
                    <form action="<?php echo URLROOT; ?>/manager/supplier" method="GET">
                        <div class="filter-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter supplier name">
                        </div>
                        <div class="filter-group">
                            <label for="nic">NIC:</label>
                            <input type="text" id="nic" name="nic" placeholder="Enter NIC">
                        </div>
                        <div class="filter-group">
                            <label for="contact_number">Contact Number:</label>
                            <input type="text" id="contact_number" name="contact_number" placeholder="Enter contact number">
                        </div>
                        <div class="filter-group">
                            <label for="city">City:</label>
                            <input type="text" id="city" name="city" placeholder="Enter city">
                        </div>
                        <div class="filter-group">
                            <label for="status">Status:</label>
                            <select id="status" name="supplier_status">
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
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
                    <h3>Active Suppliers</h3>
                    <a href="<?php echo URLROOT; ?>/manager/viewInactiveSuppliers" class="btn btn-primary">
                        <i class='bx bx-pie-chart'></i>
                        View Unallocated Suppliers
                    </a>
                    <a href="<?php echo URLROOT; ?>/manager/viewRemovedSuppliers" class="btn btn-primary">
                        <i class='bx bx-trash' ></i>
                        View Removed Suppliers
                    </a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Collections Count</th>
                            <th>Average Collection</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($suppliers) && !empty($suppliers)): ?>
                            <?php foreach ($suppliers as $supplier): ?>
                                <tr class="supplier-row" data-supplier-id="<?php echo htmlspecialchars($supplier->supplier_id); ?>">
                                    <td><?php echo htmlspecialchars($supplier->supplier_id); ?></td>
                                    <td><?php echo htmlspecialchars($supplier->first_name . ' ' . $supplier->last_name); ?></td>
                                    <td><?php echo htmlspecialchars($supplier->email); ?></td>
                                    <td><?php echo htmlspecialchars($supplier->contact_number); ?></td>
                                    <td><?php echo htmlspecialchars($supplier->number_of_collections); ?></td>
                                    <td><?php echo htmlspecialchars($supplier->average_collection); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">

                                            <!-- Manage button with icon only -->
                                            <a 
                                                href="<?php echo URLROOT; ?>/manager/manageSupplier/<?php echo $supplier->supplier_id; ?>" 
                                                class="btn btn-tertiary" 
                                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                            >
                                                <i class='bx bx-cog' style="font-size: 24px; color:green;"></i>
                                            </a>
                                            
                                            <!-- Delete button with icon only -->
                                            <a 
                                                href="<?php echo URLROOT; ?>/manager/deleteSupplier/<?php echo $supplier->supplier_id; ?>" 
                                                class="btn btn-tertiary" 
                                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;" 
                                            >
                                                <i class='bx bx-user-x' style="font-size: 24px; color:red;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="align-items : center; justify-content : center">No suppliers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>


                <div class="table-pagination">
                    <div class="pagination">
                        <?php if ($totalPages > 1): ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a 
                                href="<?php echo URLROOT; ?>/manager/supplier?page=<?php echo $i; ?>" 
                                <?php if ($currentPage == $i) { echo 'class="active"'; } ?>>
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