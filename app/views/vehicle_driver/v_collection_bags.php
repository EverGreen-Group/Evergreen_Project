<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collection_1.css">

<main>
    <!-- Page Header -->
    <div class="head-title">
        <div class="left">
            <h1>Collection Bags</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/dashboard">Dashboard</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/collectionRoute/<?php echo $data['collection']->collection_id; ?>">Collection Route</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                    <a href="#">Bags</a>
                </li>
            </ul>
        </div>
    </div>


    
    <!-- Supplier Info -->
    <div class="panel">
        <div class="panel-header">
            <h3>Supplier</h3>
            <div class="panel-badge">Collection ID: <?php echo $data['collection']->collection_id; ?></div>
        </div>
        <div class="panel-body">
            <div class="supplier-profile">
                <div class="supplier-avatar">
                    <img src="<?php echo htmlspecialchars(URLROOT . '/' . $data['supplier']['image']); ?>" alt="Supplier">
                </div>
                <div class="supplier-details">
                    <h4><?php echo $data['supplier']['supplierName']; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($data['flash'])): ?>
        <div class="alert alert-warning">
            <strong>Error!</strong> <?php echo htmlspecialchars($data['flash']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($data['flash_success'])): ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?php echo htmlspecialchars($data['flash_success']); ?>
        </div>
    <?php endif; ?>

    <!-- Bags List Section -->
    <div class="panel">
        <div class="panel-header">
            <h3>Added Bags</h3>
            <div class="panel-badge">
                <span id="totalBags">0</span> bags | 
                <span id="totalWeight">0</span> kg total
            </div>
        </div>
        <div class="panel-body">
            <div id="assignedBagsList" class="bags-list">
                <?php if (empty($data['bags'])): ?>
                    <p id="noBagsMessage">No bags added yet</p>
                <?php else: ?>
                    <?php foreach ($data['bags'] as $bag): ?>
                        <div class="bag-item" data-bag-id="<?php echo $bag->bag_id; ?>">
                            <div class="bag-info">
                                <span class="bag-id">Bag #<?php echo $bag->bag_id; ?></span>
                                <span class="bag-weight"><?php echo $bag->actual_weight_kg; ?> kg</span>
                            </div>
                            <div class="bag-actions">
                                <a href="<?php echo URLROOT; ?>/vehicledriver/updateBag/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>/<?php echo $bag->bag_id; ?>" class="mini-btn edit">
                                    <i class='bx bx-edit'></i> Edit
                                </a>
                                <a data-confirm="Are you sure you want to remove the bag?" href="<?php echo URLROOT; ?>/vehicledriver/removeBag/<?php echo $bag->bag_id; ?>/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>" class="mini-btn delete">
                                    <i class='bx bx-trash'></i> Remove
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="actions-container" style="margin-top: 20px;">
                <a href="<?php echo URLROOT; ?>/vehicledriver/addBag/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>" class="action-btn primary">
                    <i class='bx bx-plus-circle'></i>
                    Add New Bag
                </a>
                
                <a href="<?php echo URLROOT; ?>/vehicledriver/collection/<?php echo $data['collection']->collection_id; ?>" class="action-btn">
                    <i class='bx bx-arrow-back'></i>
                    Back to Route
                </a>
            </div>
            
            <div class="actions-container" style="margin-top: 20px;">
                <button id="finalizeButton" class="action-btn primary" <?php echo empty($data['bags']) ? 'disabled' : ''; ?> href="<?php echo URLROOT; ?>/vehicledriver/finalizeCollection/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>" data-confirm="Are you sure you want to finalize the collection for this supplier?">
                    <i class='bx bx-check-circle'></i>
                    Finalize Collection
                </button>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateTotals();
    });
    
    function updateTotals() {
        const bagElements = document.querySelectorAll('.bag-item');
        let totalBags = bagElements.length;
        let totalWeight = 0;
        
        bagElements.forEach(item => {
            const weightText = item.querySelector('.bag-weight').textContent;
            const weight = parseFloat(weightText.replace(' kg', ''));
            totalWeight += weight;
        });
        
        document.getElementById('totalBags').textContent = totalBags;
        document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
        
        // Enable or disable finalize button
        const finalizeButton = document.getElementById('finalizeButton');
        if (totalBags > 0) {
            finalizeButton.removeAttribute('disabled');
        } else {
            finalizeButton.setAttribute('disabled', 'disabled');
        }
    }
    
</script> 


<style>
.alert {
    padding: 15px;
    border-radius: 5px;
    background-color: #f8d7da;
    color: #721c24; 
    border: 1px solid #f5c6cb; 
    margin-bottom: 20px; 
}

.alert-success {
    padding: 15px;
    border-radius: 5px;
    background-color: #d4edda; 
    color: #155724; 
    border: 1px solid #c3e6cb; 
    margin-bottom: 20px; 
}

.alert strong {
    font-weight: bold; 
}
</style>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>