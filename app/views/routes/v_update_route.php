<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Route</h1>
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
                        <!-- Route Selection -->
                        <div class="form-group">
                            <label for="routeSelect">Select Route</label>
                            <select id="routeSelect" name="routeSelect" required>
                                <option value="" disabled selected>Choose a route</option>
                                <?php foreach($data['routes'] as $route): ?>
                                    <option value="<?php echo $route->route_id; ?>">
                                        <?php echo $route->route_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php require APPROOT . '/views/routes/components/vehicle_selection.php'; ?>
                        
                        <!-- Selected Suppliers Section -->
                        <div class="selected-suppliers-section">
                            <h4>Selected Suppliers</h4>
                            <div id="selectedSuppliersList" class="selected-suppliers-list">
                                <div class="empty-message">No suppliers selected</div>
                            </div>
                        </div>
                        
                        <?php require APPROOT . '/views/routes/components/supplier_list.php'; ?>
                        
                        <div class="form-group submit-section">
                            <button type="button" id="updateRoute" class="btn btn-primary" disabled>
                                Update Route
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
<?php require APPROOT . '/views/routes/components/update_scripts.php'; ?>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>