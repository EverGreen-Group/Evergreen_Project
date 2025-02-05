<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>


<?php 
    $data = [];
    $data['total_order_products'] = 0;
    $data['total_delivered_today'] = 0;
    $data['total_available_bags'] = 0;
    $data['items'] = [
        (object)[
            'item_id' => 101,
            'supplier_name' => 'Green Tea Estates',
            'route_name' => 'Route A',
            'status' => 'Pending',
            'total_weight' => '150.00',
            'is_schedule' => 1
        ],
        (object)[
            'item_id' => 102,
            'supplier_name' => 'Hilltop Plantations',
            'route_name' => 'Route B',
            'status' => 'Approved',
            'total_weight' => '220.00',
            'is_schedule' => 1
        ],
        (object)[
            'item_id' => 103,
            'supplier_name' => 'Sunrise Farms',
            'route_name' => 'Route C',
            'status' => 'Delivered',
            'total_weight' => '180.00',
            'is_schedule' => 1
        ],
        (object)[
            'item_id' => 104,
            'supplier_name' => 'Evergreen Tea Suppliers',
            'route_name' => 'Route D',
            'status' => 'Cancelled',
            'total_weight' => '120.00',
            'is_schedule' => 0
        ]
    ];

?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Supply & Distribution</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/vehiclemanager/bag" class="btn btn-primary">
            <i class='bx bx-shopping-bag'></i>
            Manage Collection Bags
        </a>
        <a href="#" class="btn btn-primary">
            <i class='bx bx-show'></i>
            View Order History
        </a>
    </div>

    <ul class="box-info">
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <p>Total Products</p>
                <h3><?php echo $data['total_order_products']; ?></h3>
            </span>
        </li>
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <p>Delivered Today</p>
                <h3><?php echo $data['total_delivered_today']; ?></h3>
            </span>
        </li>
        <li>
            <i class='bx bxs-user-check'></i>
            <span class="text">
                <p>Available Collection Bags</p>
                <h3><?php echo $data['total_available_bags']; ?></h3>
            </span>
        </li>
    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Products To Be Delivered</h3>
                <button class="filter-btn">
                    <i class='bx bx-filter'></i>
                    Filter by availability
                </button>
            </div>
            <div class="vehicle-grid">
                <?php if (!empty($data['items'])): ?>
                    <?php foreach ($data['items'] as $item): ?>
                        <div class="vehicle-card" 
                            data-item-id="<?php echo htmlspecialchars($item->item_id); ?>"
                            onclick="updateItemDetails(this)">
                            
                            <div class="card-content">
                                <div class="card-title">
                                    <h4>Item #<?php echo htmlspecialchars($item->item_id); ?></h4>
                                    <button class="bookmark-btn">
                                        <i class='bx bx-bookmark'></i>
                                    </button>
                                </div>
                                <div class="<?php echo htmlspecialchars((($item->is_schedule == 1) ? "capacity" : "capacity not-scheduled")) ?>">
                                    <?php echo htmlspecialchars(($item->is_schedule == 1) ? "Assigned" : "Unassigned"); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No fertilizer items found matching your criteria.</p>
                <?php endif; ?>
            </div>

        </div>

        <div class="order vehicle-details">
            <div class="head">
                <h3>Item Details</h3>
            </div>
            <div class="details-container">
                <div class="details-content">
                    <div class="details-section">
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Order ID:</span>
                                <span class="value" id="detail-order-id"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Item ID:</span>
                                <span class="value" id="detail-item-id"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Product ID:</span> <!-- Changed from Fertilizer ID to Product ID -->
                                <span class="value" id="detail-fertilizer-id"></span> <!-- Keep the same ID for JavaScript -->
                            </div>
                            <div class="info-item">
                                <span class="label">Supplier:</span>
                                <span class="value" id="detail-supplier-name"></span> <!-- Changed to supplier name -->
                            </div>
                            <div class="info-item">
                                <span class="label">Route:</span>
                                <span class="value" id="detail-route-name"></span> <!-- Changed to route name -->
                            </div>
                            <div class="info-item">
                                <span class="label">Order Date:</span>
                                <span class="value" id="detail-item-date"></span> <!-- Ensure this ID matches the JS -->
                            </div>
                            <div class="info-item">
                                <span class="label">Order Time:</span>
                                <span class="value" id="detail-item-time"></span> <!-- Added ID for order time -->
                            </div>
                            <div class="info-item">
                                <span class="label">Total Quantity:</span>
                                <span class="value" id="detail-total-quantity"></span> <!-- Added total quantity -->
                            </div>
                            <div class="info-item">
                                <span class="label">Is Scheduled:</span>
                                <span class="value" id="detail-is-schedule"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</main>



<script>
function updateItemDetails(card) {
    const itemId = card.getAttribute('data-item-id');

    // Show loading state
    document.getElementById('detail-item-id').textContent = 'Loading...';

    fetch(`${URLROOT}/distribution/getProductDetails/${itemId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(item => {
            // Populate the details with the fetched data
            document.getElementById('detail-order-id').textContent = item.order_id;
            document.getElementById('detail-item-id').textContent = item.item_id;
            document.getElementById('detail-fertilizer-id').textContent = item.fertilizer_type_id; 
            document.getElementById('detail-supplier-name').textContent = item.supplier_name;
            document.getElementById('detail-route-name').textContent = item.route_id ? item.route_id : 'N/A'; 
            document.getElementById('detail-item-date').textContent = item.order_date; 
            document.getElementById('detail-item-time').textContent = item.order_time; 
            document.getElementById('detail-total-quantity').textContent = item.quantity;
            document.getElementById('detail-is-schedule').textContent = (item.is_schedule == 1) ? "Yes" : "No";

        })
        .catch(error => {
            console.error('Error fetching order details:', error);
            // document.getElementById('detail-order-id').textContent = 'Error loading order details';
        });
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

document.getElementById('openAddDriverModal').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('addDriverModal').style.display = 'block';
});


// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>