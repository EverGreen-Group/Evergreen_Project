<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="tracking-dashboard">
    <!-- Navigation Header -->
    <div class="tracking-nav">
        <div class="nav-icons">
            <a href="#" class="nav-icon"><i class='bx bx-package'></i></a>
            <a href="#" class="nav-icon active"><i class='bx bx-map'></i></a>
            <a href="#" class="nav-icon"><i class='bx bx-calculator'></i></a>
            <a href="#" class="nav-icon"><i class='bx bx-envelope'></i></a>
        </div>
        <div class="nav-right">
            <div class="search-box">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Track Number" value="<?php echo $data['tracking_number']; ?>">
            </div>
            <a href="#" class="notification-icon"><i class='bx bx-bell'></i></a>
            <a href="#" class="profile-icon"><i class='bx bx-user'></i></a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="tracking-content">
        <!-- Delivery Status Tabs -->
        <div class="status-tabs">
            <button class="tab-btn active">Delivering</button>
            <button class="tab-btn">Received</button>
            <button class="tab-btn">All</button>
        </div>

        <!-- Delivery Cards -->
        <div class="delivery-cards">
            <?php foreach($data['orders'] as $order): ?>
            <div class="delivery-card">
                <div class="card-header">
                    <div class="delivery-icon">
                        <i class='bx bx-package'></i>
                    </div>
                    <div class="delivery-info">
                        <h3><?php echo $order->product_name; ?></h3>
                        <p class="tracking-number"><?php echo $order->tracking_number; ?></p>
                    </div>
                    <button class="more-options"><i class='bx bx-dots-vertical-rounded'></i></button>
                </div>
                <div class="card-details">
                    <div class="route-info">
                        <div class="location from">
                            <span class="label">From:</span>
                            <span class="value"><?php echo $order->shipping_from; ?></span>
                        </div>
                        <div class="location to">
                            <span class="label">To:</span>
                            <span class="value"><?php echo $order->shipping_to; ?></span>
                        </div>
                    </div>
                    <div class="price-info">
                        <span class="label">Price:</span>
                        <span class="value">LKR <?php echo number_format($order->total_amount, 2); ?></span>
                    </div>
                </div>
                <div class="delivery-status">
                    <span class="status-badge <?php echo strtolower($order->status); ?>">
                        <?php echo $order->status; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Map Section -->
        <div class="map-container">
            <div id="deliveryMap"></div>
            
            <!-- Post Office Finder -->
            <div class="post-office-finder">
                <h3>Find post office</h3>
                <div class="finder-filters">
                    <span class="filter active">Day-and-night</span>
                    <span class="filter">More than 30 kg</span>
                </div>
                
                <!-- Post Office List -->
                <div class="post-office-list">
                    <?php foreach($data['post_offices'] as $office): ?>
                    <div class="post-office-item">
                        <div class="office-icon">
                            <i class='bx bx-building'></i>
                        </div>
                        <div class="office-info">
                            <h4><?php echo $office->name; ?></h4>
                            <p><?php echo $office->address; ?></p>
                            <div class="office-meta">
                                <span class="time-left">
                                    <i class='bx bx-time'></i>
                                    <?php echo $office->hours_left; ?> hours left
                                </span>
                                <span class="distance">
                                    <i class='bx bx-walk'></i>
                                    <?php echo $office->distance; ?> min
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
    // Initialize delivery map
    function initMap() {
        const map = new google.maps.Map(document.getElementById('deliveryMap'), {
            center: { lat: <?php echo $data['map_center_lat']; ?>, lng: <?php echo $data['map_center_lng']; ?> },
            zoom: 13,
            styles: [/* Your custom map styles */]
        });

        // Add delivery markers and route
        // ... Add your map markers and routing logic here
    }
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 