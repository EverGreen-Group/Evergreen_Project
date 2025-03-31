<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collection_1.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
    const collections = <?php echo json_encode($data['collections']); ?>;
    const collectionId = <?php echo $data['collection']->collection_id; ?>;
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_driver/collection_route_suppliers.js"></script>

<main>
    <!-- Page Header -->
    <div class="head-title">
        <div class="left">
            <h1>Collection Route</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/dashboard">Dashboard</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                    <a href="#">Collection Route</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Collection View Section -->
    <div id="collection-view-section">
        <!-- Current Supplier Section -->
        <div class="panel">
            <div class="panel-header">
                <h3>Current Supplier</h3>
                <?php if ($data['collection']->status == 'Completed'): ?>
                    <div class="panel-badge success">Collection Completed</div>
                <?php elseif (empty($data['currentSupplier'])): ?>
                    <div class="panel-badge warning">All Suppliers Collected</div>
                <?php else: ?>
                    <div class="panel-badge">Next in Route</div>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (empty($data['currentSupplier'])): ?>
                    <div class="empty-state">
                        <i class='bx bx-check-circle'></i>
                        <p>All suppliers have been collected. You can now complete this collection route.</p>
                        <form action="<?php echo URLROOT; ?>/vehicledriver/completeCollection/<?php echo $data['collection']->collection_id; ?>" method="POST">
                            <button type="submit" class="action-btn primary">
                                <i class='bx bx-check-double'></i>
                                Complete Collection
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="supplier-profile">
                        <div class="supplier-avatar">
                            <img src="<?php echo htmlspecialchars(URLROOT . '/' . $data['currentSupplier']['image']); ?>" alt="Supplier">
                        </div>
                        <div class="supplier-details">
                            <h4><?php echo $data['currentSupplier']['supplierName']; ?></h4>
                            <p class="expected-amount">
                                <i class='bx bx-leaf'></i>
                                <?php echo $data['currentSupplier']['estimatedCollection']; ?>kg expected
                            </p>
                            <p class="supplier-contact">
                                <i class='bx bx-phone'></i>
                                <?php echo $data['currentSupplier']['contact']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="actions-container">
                        <a href="<?php echo URLROOT; ?>/vehicledriver/collectionBags/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['currentSupplier']['id']; ?>" class="action-btn primary">
                            <i class='bx bx-package'></i>
                            Manage Bags
                        </a>
                        <a href="<?php echo URLROOT; ?>/vehicledriver/skipSupplier/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['currentSupplier']['id']; ?>" class="action-btn">
                            <i class='bx bx-skip-next'></i>
                            Skip Supplier
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Remaining Suppliers Section -->
        <div class="panel">
            <div class="panel-header">
                <h3>Remaining Suppliers</h3>
                <div class="panel-badge">
                    <?php 
                    // Use collections array if remainingSuppliers is not set
                    $remainingCount = 0;
                    if (isset($data['collections'])) {
                        // Count suppliers that aren't the current one
                        foreach ($data['collections'] as $supplier) {
                            if (!isset($data['currentSupplier']) || $supplier['id'] != $data['currentSupplier']['id']) {
                                $remainingCount++;
                            }
                        }
                    }
                    echo $remainingCount; 
                    ?> suppliers left
                </div>
            </div>
            <div class="panel-body">
                <?php if (!isset($data['collections']) || empty($data['collections']) || $remainingCount == 0): ?>
                    <div class="empty-state">
                        <i class='bx bx-check-circle'></i>
                        <p>No more suppliers remaining in this collection route.</p>
                    </div>
                <?php else: ?>
                    <div class="suppliers-list">
                        <?php foreach ($data['collections'] as $supplier): ?>
                            <?php if (!isset($data['currentSupplier']) || $supplier['id'] != $data['currentSupplier']['id']): ?>
                                <div class="supplier-item">
                                    <div class="supplier-info">
                                        <span class="supplier-name"><?php echo $supplier['supplierName']; ?></span>
                                        <span class="supplier-estimate"><?php echo $supplier['estimatedCollection']; ?>kg</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    // Call supplier
    function callSupplier(phoneNumber) {
        window.location.href = `tel:${phoneNumber}`;
    }
    
    // Get directions to supplier
    function getDirections(supplierId) {
        // Implement navigation functionality
        alert(`Navigating to supplier #${supplierId}`);
    }
    
    // Cancel collection
    function cancelCollection() {
        if (confirm('Are you sure you want to cancel this collection?')) {
            // Add your cancellation logic here
            alert('Collection canceled!');
            // Redirect back to a relevant page
            window.location.href = `${URLROOT}/vehicledriver/dashboard`;
        }
    }
</script>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>