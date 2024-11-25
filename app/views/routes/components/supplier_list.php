<!-- Unallocated Suppliers List -->
<div class="suppliers-section">
    <h4>Unallocated Suppliers</h4>
    <div class="suppliers-list">
        <?php foreach($data['suppliers'] as $supplier): ?>
            <div class="supplier-item">
                <div class="supplier-info">
                    <span class="supplier-name">Supplier #<?php echo $supplier->supplier_id; ?></span>
                    <div class="supplier-details">
                        <span data-collections="<?php echo $supplier->number_of_collections; ?>">
                            Collections: <?php echo $supplier->number_of_collections; ?>
                        </span>
                        <span data-avg-collection="<?php echo $supplier->avg_collection; ?>">
                            Avg: <?php echo $supplier->avg_collection; ?> kg
                        </span>
                    </div>
                </div>
                <button type="button" 
                        class="add-supplier-btn" 
                        data-id="<?php echo $supplier->supplier_id; ?>"
                        data-lat="<?php echo $supplier->latitude; ?>"
                        data-lng="<?php echo $supplier->longitude; ?>">
                    <i class='bx bx-plus'></i>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>