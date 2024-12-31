<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_driver_collections.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/collection_route/collection_status.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Ongoing Collection</h1>
            <ul class="breadcrumb">
                <li><a href="#">Collections</a></li>
                <li>Current</li>
            </ul>
        </div>
    </div>



    <!-- Supplier Lists Section -->
    <div class="table-data">

        <!-- Current Supplier -->
        <section class="current-supplier">
            <h2>Current Supplier</h2>
            <?php if (!empty($data['collectionSupplierRecords'])): ?>
                <?php $currentSupplier = $data['collectionSupplierRecords'][0]; ?>
                <div class="supplier-card">
                    <div class="supplier-info">
                        <div class="supplier-profile">
                            <img src="<?php echo URLROOT; ?>/public/uploads/supplier_photos/default-supplier.png" alt="Supplier">
                            <h4><?php echo $currentSupplier->supplier_name; ?></h4>
                            <span class="supplier-id">#<?php echo $currentSupplier->supplier_id; ?></span>
                        </div>
                        <div class="supplier-actions">
                            <button class="btn-location" onclick="openLocation(<?php echo $currentSupplier->latitude; ?>, <?php echo $currentSupplier->longitude; ?>)">
                                <i class='bx bx-map'></i>
                                Location
                            </button>
                            <button class="btn-call" onclick="callSupplier('<?php echo $currentSupplier->contact_number; ?>')" style="background-color: var(--main)">
                                <i class='bx bx-phone'></i>
                                Contact
                            </button>
                        </div>
                    </div>
                    <div class="supplier-details">
                        <p><strong>Status:</strong> <span class="status-badge <?php echo strtolower($currentSupplier->status); ?>"><?php echo $currentSupplier->status; ?></span></p>
                        <?php if ($currentSupplier->arrival_time): ?>
                            <p><strong>Arrived:</strong> <?php echo date('H:i', strtotime($currentSupplier->arrival_time)); ?></p>
                        <?php endif; ?>
                        <p><strong>Contact:</strong> <?php echo $currentSupplier->contact_number; ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p class="no-supplier">No current supplier assigned</p>
            <?php endif; ?>
        </section>

        <!-- Suppliers List -->
        <section class="suppliers-list">
            <h2>Collection List</h2>
            <div class="suppliers-cards">
                <?php if (!empty($data['collectionSupplierRecords'])): ?>
                    <?php foreach ($data['collectionSupplierRecords'] as $record): ?>
                        <?php 
                        // Determine card status
                        $cardStatus = '';
                        if ($record->collection_time) {
                            $cardStatus = 'collected';
                        } elseif ($record->arrival_time) {
                            $cardStatus = 'arrived';
                        }
                        ?>
                        <div class="supplier-card <?php echo $cardStatus; ?>">
                            <div class="supplier-detail">
                                <strong>ID:</strong> #<?php echo $record->supplier_id; ?>
                            </div>
                            <div class="supplier-detail">
                                <strong>Supplier:</strong> <?php echo $record->supplier_name; ?>
                            </div>
                            <div class="supplier-detail">
                                <strong>Status:</strong> 
                                <span class="status" style="color: white; background-color: var(--main)" <?php echo $cardStatus ?: strtolower($record->status); ?>">
                                    <?php 
                                    if ($record->collection_time) {
                                        echo 'Collected';
                                    } elseif ($record->arrival_time) {
                                        echo 'Arrived';
                                    } else {
                                        echo $record->status;
                                    }
                                    ?>
                                </span>
                            </div>
                            <?php if ($record->collection_time): ?>
                                <div class="supplier-detail">
                                    <strong>Collected at:</strong> <?php echo date('H:i', strtotime($record->collection_time)); ?>
                                </div>
                            <?php elseif ($record->arrival_time): ?>
                                <div class="supplier-detail">
                                    <strong>Arrived at:</strong> <?php echo date('H:i', strtotime($record->arrival_time)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-suppliers">No suppliers in the collection list.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>


<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<script>
function openLocation(lat, lng) {
    const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving`;
    window.open(mapsUrl, '_blank');
}

function callSupplier(phoneNumber) {
    window.location.href = `tel:${phoneNumber}`;
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>