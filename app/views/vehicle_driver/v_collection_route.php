<?php require APPROOT . '/views/inc/components/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/collection_route/collection_route.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/collection_route/route_suppliers.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
    const collections = <?php echo json_encode($data['collections']); ?>;
    const collectionId = <?php echo $data['collection']->collection_id; ?>;
    const vehicleLocation = <?php echo json_encode($data['vehicleLocation']); ?>;
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_driver/collection_route_maps.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_driver/collection_route_suppliers.js"></script>


<div class="map-container" id="map"></div>


<div class="bottom-nav">
    <button class="btn-arrive" onclick="markArrived()">
        <i class='bx bx-map-pin'></i> Mark Arrived
    </button>
    <button class="btn-view" onclick="viewCollection()">
        <i class='bx bx-collection'></i> View Collection
    </button>
</div>

<div id="collectionBagDetailsModal" class="modal" onclick="closeModal('collectionBagDetailsModal')">
    <div class="modal-content" onclick="event.stopPropagation();">
        <span class="close" onclick="closeModal('collectionBagDetailsModal')">&times;</span>
        <h2>Collection Route</h2>
        <div id="collectionBagDetailsContent">
            <!-- Current Supplier (First in the list) -->
            <?php if (!empty($data['collections'])): ?>
                <?php $currentSupplier = $data['collections'][0]; ?>
                <div class="current-supplier-card">
                    <div class="card-header">
                        <h3>Current Stop</h3>
                    </div>
                    <div class="supplier-main-info">
                        <div class="supplier-profile">
                            <div class="supplier-avatar">
                                <!-- <img src="<?php echo URLROOT; ?>/img/default-supplier.png" alt="Supplier"> -->
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR53xuHWi7TlyLSTriezsdVqrnnAKfCKFo4Pw&s alt="Supplier">
                            </div>
                            <div class="supplier-details">
                                <h4><?php echo $currentSupplier['supplierName']; ?></h4>
                                <p class="expected-amount"><?php echo $currentSupplier['estimatedCollection']; ?>kg expected</p>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-actions">
                        <button class="action-btn" onclick="callSupplier('<?php echo $currentSupplier['contact']; ?>')">
                            <i class='bx bx-phone'></i>
                            Call Supplier
                        </button>
                        <button class="action-btn" onclick="getDirections('<?php echo $currentSupplier['id']; ?>')">
                            <i class='bx bx-directions'></i>
                            <span>Navigate</span>
                        </button>
                        <button class="action-btn primary" onclick="addCollection('<?php echo $currentSupplier['id']; ?>')">
                            <i class='bx bx-plus-circle'></i>
                            Add Collection
                        </button>
                    </div>
                </div>

                <!-- Separator -->
                <div class="separator">
                    <span>Other Suppliers</span>
                </div>

                <!-- Remaining Suppliers -->
                <?php if (count($data['collections']) > 1): ?>
                    <div class="remaining-suppliers">
                        <?php foreach (array_slice($data['collections'], 1) as $supplier): ?>
                            <div class="supplier-item">
                                <div class="supplier-info">
                                    <span class="supplier-name"><?php echo $supplier['supplierName']; ?></span>
                                    <span class="collection-amount"><?php echo $supplier['estimatedCollection']; ?>kg expected</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Collection Modal -->
<div id="addCollectionModal" class="modal" onclick="closeModal('addCollectionModal')">
    <div class="modal-content" onclick="event.stopPropagation();">
        <span class="close" onclick="closeModal('addCollectionModal')">&times;</span>
        
        <!-- Supplier Info Header -->
        <div class="current-supplier-card">
            <div class="supplier-profile">
                <div class="supplier-avatar">
                    <img src="<?php echo $currentSupplier['image']; ?>" alt="Supplier">
                </div>
                <div class="supplier-details">
                    <h3 id="modalSupplierName"></h3>
                    <p class="expected-amount" id="modalExpectedAmount"></p>
                </div>
            </div>
        </div>

        <!-- Assigned Bags Section -->
        <div id="assignedBagsSection" class="supplier-item">
            <div class="supplier-info">
                <span class="supplier-name">Added Bags</span>
                <div id="assignedBagsList"></div>
            </div>
        </div>

        <!-- Step 1: Bag ID Input -->
        <div id="bagIdStep" class="collection-step supplier-item">
            <div class="supplier-info">
                <label for="bagId" class="supplier-name">Scan Bag QR Code</label>
                <div class="input-with-button">
                    <input type="text" id="bagId" placeholder="Scan or enter bag ID">
                    <button onclick="checkBag()" class="action-btn primary">Verify Bag</button>
                </div>
            </div>
        </div>

        <!-- Step 2: Collection Details -->
        <div id="bagDetailsStep" class="collection-step" style="display: none;">
            <div class="supplier-item">
                <div class="supplier-info">
                    <span class="supplier-name">Bag Currently Selected: #<span id="selectedBagId"></span></span>
                    <span class="collection-amount">Capacity: <span id="bagCapacity"></span>kg</span>
                </div>
            </div>

            <div class="supplier-item">
                <div class="supplier-info">
                    <label class="supplier-name">Actual Weight (kg)</label>
                    <input type="number" id="actualWeight" step="0.01" required>
                </div>
            </div>

            <div class="supplier-item">
                <div class="supplier-info">
                    <label class="supplier-name">Leaf Quality Details</label>
                    <div class="quality-grid">
                        <select id="leafType" required>
                            <option value="S">Soft</option>
                            <option value="B">Broad</option>
                            <option value="N">Normal</option>
                        </select>
                        <select id="leafAge" required>
                            <option value="Young">Young</option>
                            <option value="Medium">Medium</option>
                            <option value="Mature">Mature</option>
                        </select>
                        <select id="moistureLevel" required>
                            <option value="Wet">Wet</option>
                            <option value="Semi Wet">Semi Wet</option>
                            <option value="Dry">Dry</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="supplier-item">
                <div class="supplier-info">
                    <label class="supplier-name">Notes</label>
                    <textarea id="deductionNotes" placeholder="Add any deduction notes or remarks"></textarea>
                </div>
            </div>

            <div class="supplier-actions">
                <button type="button" onclick="addBagToCollection()" class="action-btn primary">
                    <i class='bx bx-plus-circle'></i>
                    <span>Add Bag</span>
                </button>
            </div>
        </div>

        <!-- Finalize Button -->
        <div class="supplier-actions" id="finalizeActions">
            <button type="button" id="confirmCollectionButton" onclick="finalizeSupplierCollection()" class="action-btn primary" style="display: none;">
                <i class='bx bx-check'></i>
                <span>Finalize Collection</span>
            </button>
        </div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>