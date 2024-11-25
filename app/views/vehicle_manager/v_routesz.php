<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Vehicle Manager Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <ul class="box-info">
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <h3>20</h3>
                <p>Vehicles</p>
                <small>10 Available</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-user'></i>
            <span class="text">
                <h3>15</h3>
                <p>Drivers</p>
                <small>4 Available</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <h3>15</h3>
                <p>Driving Partners</p>
                <small>4 Available</small>
            </span>
        </li>
    </ul>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Routes</h3>
            </div>
        </div>
        
    </div>

    <!-- Optimized Routes Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Optimized Collection Routes</h3>
            </div>

            <?php if (isset($data['optimizedRoutes']) && !empty($data['optimizedRoutes'])): ?>
                <!-- Route Statistics -->
                <div class="route-stats">
                    <div class="stat-item">
                        <i class='bx bxs-truck'></i>
                        <span>Vehicles Used: <?php echo $data['stats']['total_vehicles_used']; ?></span>
                    </div>
                    <div class="stat-item">
                        <i class='bx bxs-map'></i>
                        <span>Total Distance: <?php echo $data['stats']['total_distance']; ?> km</span>
                    </div>
                    <div class="stat-item">
                        <i class='bx bxs-time'></i>
                        <span>Total Duration: <?php echo $data['stats']['total_duration']; ?> min</span>
                    </div>
                </div>

                <!-- Routes List -->
                <div class="routes-list">
                    <?php foreach ($data['optimizedRoutes'] as $route): ?>
                        <div class="route-card">
                            <div class="route-header">
                                <i class='bx bxs-truck'></i>
                                <h4>Vehicle <?php echo $route['vehicle_id']; ?></h4>
                            </div>
                            <div class="route-path">
                                <span class="location-dot start">●</span>
                                <span class="location-name">Factory</span>
                                <?php foreach ($route['stops'] as $index => $stop): ?>
                                    <span class="arrow">→</span>
                                    <span class="location-dot">●</span>
                                    <span class="location-name">Supplier <?php echo $stop['supplier_id']; ?></span>
                                <?php endforeach; ?>
                                <span class="arrow">→</span>
                                <span class="location-dot end">●</span>
                                <span class="location-name">Factory</span>
                            </div>
                            <div class="route-details">
                                <span class="detail">
                                    <i class='bx bxs-map'></i>
                                    Distance: <?php echo $route['total_distance']; ?> km
                                </span>
                                <span class="detail">
                                    <i class='bx bxs-time'></i>
                                    Duration: <?php echo $route['total_duration']; ?> min
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-routes">
                    <i class='bx bx-map-alt'></i>
                    <p>No optimized routes available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>
</style>