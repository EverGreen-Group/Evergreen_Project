<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <!-- Route Management Section -->
    <div class="head-title">
        <div class="left">
            <h1>Manage Route</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="create-route-container">
            <!-- Map Component -->
            <?php require APPROOT . '/views/routes/components/map.php'; ?>
            
            <!-- Options Component -->
            <div class="options-section">
                <div class="head">
                    <h3>Route Options</h3>
                </div>
                <div class="body">
                    <form id="routeForm" method="POST" onsubmit="return false;">
                        <?php require APPROOT . '/views/routes/components/vehicle_selection.php'; ?>
                        
                        <!-- Selected Suppliers Section -->
                        <div class="selected-suppliers-section">
                            <h4>Selected Suppliers</h4>
                            <div id="selectedSuppliersList" class="selected-suppliers-list">
                                <!-- Selected suppliers will be added here dynamically -->
                                <div class="empty-message">No suppliers selected</div>
                            </div>
                        </div>
                        
                        <?php require APPROOT . '/views/routes/components/supplier_list.php'; ?>
                        
                        <!-- Add submit button at the bottom -->
                        <div class="form-group submit-section">
                            <button type="button" id="submitRoute" class="btn btn-primary" disabled>
                                Create Route
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Styles -->
<?php require APPROOT . '/views/routes/components/styles.php'; ?>

<!-- Scripts -->
<?php require APPROOT . '/views/routes/components/scripts.php'; ?>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>