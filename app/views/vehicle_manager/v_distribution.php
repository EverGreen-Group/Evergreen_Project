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
<script src="<?php echo URLROOT; ?>/public/js/driver_manager/driver.js"></script>


<?php 
    $data = [];
    $data['total_order_products'] = 0;
    $data['total_delivered_today'] = 0;
    $data['total_available_bags'] = 0;
    $data['orders'] = [
        (object)[
            'order_id' => 101,
            'supplier_name' => 'Green Tea Estates',
            'route_name' => 'Route A',
            'status' => 'Pending',
            'total_weight' => '150.00',
            'payment_status' => 'Pending',
            'delivery_date' => '2025-02-10',
            'is_schedule' => 1
        ],
        (object)[
            'order_id' => 102,
            'supplier_name' => 'Hilltop Plantations',
            'route_name' => 'Route B',
            'status' => 'Approved',
            'total_weight' => '220.00',
            'payment_status' => 'Paid',
            'delivery_date' => '2025-02-12',
            'is_schedule' => 1
        ],
        (object)[
            'order_id' => 103,
            'supplier_name' => 'Sunrise Farms',
            'route_name' => 'Route C',
            'status' => 'Delivered',
            'total_weight' => '180.00',
            'payment_status' => 'Paid',
            'delivery_date' => '2025-02-08',
            'is_schedule' => 1
        ],
        (object)[
            'order_id' => 104,
            'supplier_name' => 'Evergreen Tea Suppliers',
            'route_name' => 'Route D',
            'status' => 'Cancelled',
            'total_weight' => '120.00',
            'payment_status' => 'Failed',
            'delivery_date' => '2025-02-15',
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
            <i class='bx bx-plus'></i>
            Manage Collection Bags
        </a>
        <a href="#" class="btn btn-primary">
            <i class='bx bx-calendar'></i>
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
                <h3>Search Filters</h3>
                <i class='bx bx-search'></i>
            </div>
            <div class="filter-options">
                <form action="<?php echo URLROOT; ?>/vehiclemanager/driver" method="GET">
                    <div class="filter-group">
                        <label for="driver-id">Driver ID:</label>
                        <input type="text" id="driver-id" name="driver_id" placeholder="Search by ID">
                    </div>
                    <div class="filter-group">
                        <label for="name">Driver Name:</label>
                        <input type="text" id="name" name="name" placeholder="Search by name">
                    </div>
                    <div class="filter-group">
                        <label for="nic">NIC:</label>
                        <input type="text" id="nic" name="nic" placeholder="Search by NIC">
                    </div>
                    <div class="filter-group">
                        <label for="contact">Contact Number:</label>
                        <input type="text" id="contact" name="contact_number" placeholder="Search by contact">
                    </div>
                    <div class="filter-group">
                        <label for="driver-status">Driver Status:</label>
                        <select id="driver-status" name="driver_status">
                            <option value="">All Statuses</option>
                            <option value="Available">Available</option>
                            <option value="On Route">On Route</option>
                            <option value="Off Duty">Off Duty</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="employee-status">Employee Status:</label>
                        <select id="employee-status" name="employee_status">
                            <option value="">All Statuses</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="On Leave">On Leave</option>
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
                <h3>Confirmed Orders</h3>
                <button class="filter-btn">
                    <i class='bx bx-filter'></i>
                    Filter by availability
                </button>
            </div>
            <div class="vehicle-grid">
                <?php if (!empty($data['orders'])): ?>
                    <?php foreach ($data['orders'] as $order): ?>
                        <div class="vehicle-card" 
                            data-order-id="<?php echo htmlspecialchars($order->order_id); ?>"
                            onclick="updateOrderDetails(this)">
                            
                            <div class="card-content">
                                <div class="card-title">
                                    <h4>Order #<?php echo htmlspecialchars($order->order_id); ?></h4>
                                    <button class="bookmark-btn">
                                        <i class='bx bx-bookmark'></i>
                                    </button>
                                </div>
                                <div class="vehicle-specs">
                                    <div class="spec">
                                        <span class="spec-label">Supplier:</span>
                                        <span class="spec-value"><?php echo htmlspecialchars($order->supplier_name); ?></span>
                                    </div>
                                    <div class="spec">
                                        <span class="spec-label">Route:</span>
                                        <span class="spec-value"><?php echo htmlspecialchars($order->route_name); ?></span>
                                    </div>
                                </div>
                                <div class="<?php echo htmlspecialchars((($order->is_schedule == 1) ? "capacity" : "capacity not-scheduled")) ?>"><?php echo htmlspecialchars(($order->is_schedule == 1) ? "Assigned" : "Unassigned"); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No fertilizer orders found matching your criteria.</p>
                <?php endif; ?>
            </div>

        </div>

        <!-- Driver Details Section -->
        <div class="order vehicle-details">
            <div class="head">
                <h3>Order Details</h3>
            </div>
            <div class="details-container">
                <div class="details-content">
                    <div class="details-section">
                        <h4 class="section-title">Order Information</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Order ID:</span>
                                <span class="value" id="detail-order-id"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Supplier ID:</span>
                                <span class="value" id="detail-supplier-id"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Route ID:</span>
                                <span class="value" id="detail-route-id"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Order Date:</span>
                                <span class="value" id="detail-order-date"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Order Time:</span>
                                <span class="value" id="detail-order-time"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status:</span>
                                <span class="value" id="detail-status"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Total Weight:</span>
                                <span class="value" id="detail-total-weight"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Is Scheduled:</span>
                                <span class="value" id="detail-is-schedule"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Order Button -->
                    <div class="remove-vehicle">
                        <form action="<?php echo URLROOT; ?>/vehiclemanager/removeOrder" method="POST" onsubmit="return confirm('Are you sure you want to remove this order?');">
                            <input type="hidden" name="order_id" id="remove-order-id" value="">
                            <button type="submit" class="btn btn-danger" style="width:100%;">View Details</button>
                        </form>
                    </div>

                    <div class="remove-vehicle">
                        <form action="<?php echo URLROOT; ?>/vehiclemanager/removeOrder" method="POST" onsubmit="return confirm('Are you sure you want to remove this order?');">
                            <input type="hidden" name="order_id" id="remove-order-id" value="">
                            <button type="submit" class="btn btn-tertiary" style="width:100%;">Remove from Route</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</main>



<script>
function updateOrderDetails(card) {
    const orderId = card.getAttribute('data-order-id');

    // Show loading state
    document.getElementById('detail-order-id').textContent = 'Loading...';

    fetch(`${URLROOT}/distribution/getOrderDetails/${orderId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(order => {
            // Order Information
            document.getElementById('detail-order-id').textContent = order.order_id;
            document.getElementById('detail-supplier-id').textContent = order.supplier_id;
            document.getElementById('detail-route-id').textContent = order.route_id;
            document.getElementById('detail-order-date').textContent = order.order_date;
            document.getElementById('detail-order-time').textContent = order.order_time;
            document.getElementById('detail-status').textContent = order.status;
            document.getElementById('detail-total-weight').textContent = order.total_amount + ' kg';
            document.getElementById('detail-is-schedule').textContent = (order.is_schedule == 1) ? "Yes" : "No";

            // Update hidden input for remove form
            document.getElementById('remove-order-id').value = order.order_id;
        })
        .catch(error => {
            console.error('Error fetching order details:', error);
            document.getElementById('detail-order-id').textContent = 'Error loading order details';
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