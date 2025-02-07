<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<main>
    <!-- Dashboard Header -->
    <div class="head-title">
        <div class="left">
            <h1>Supplier Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Collection Status Cards -->
    <div class="info-cards">
        <!-- Preferred Collection Card -->
        <div class="card">
            <div class="card-header">
                <i class='bx bx-calendar'></i>
                <h3>Next Collection</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="label">Preferred Date:</span>
                    <span class="value"><?php echo isset($data['nextCollection']->preferred_date) ? date('l, M j', strtotime($data['nextCollection']->preferred_date)) : 'Not scheduled'; ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Time:</span>
                    <span class="value"><?php echo isset($data['nextCollection']->preferred_time) ? date('g:i A', strtotime($data['nextCollection']->preferred_time)) : '--:--'; ?></span>
                </div>
            </div>
        </div>

        <!-- Driver Arrival Card -->
        <div class="card">
            <div class="card-header">
                <i class='bx bx-time-five'></i>
                <h3>Driver Status</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="label">Arrival Status:</span>
                    <span class="value status-badge <?php echo isset($data['driverStatus']->arrived) ? 'arrived' : 'pending'; ?>">
                        <?php echo isset($data['driverStatus']->arrived) ? 'Arrived' : 'En Route'; ?>
                    </span>
                </div>
                <?php if (isset($data['driverStatus']->arrival_time)): ?>
                <div class="info-item">
                    <span class="label">Arrived At:</span>
                    <span class="value"><?php echo date('g:i A', strtotime($data['driverStatus']->arrival_time)); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Vehicle Location Card -->
        <div class="card">
            <div class="card-header">
                <i class='bx bx-map'></i>
                <h3>Vehicle Location</h3>
            </div>
            <div class="card-content">
                <div id="vehicle-location-map" style="height: 150px; margin-bottom: 10px;"></div>
                <div class="info-item">
                    <span class="label">Distance:</span>
                    <span class="value" id="distance-away">Calculating...</span>
                </div>
            </div>
        </div>

        <!-- Collection Amount Card -->
        <div class="card">
            <div class="card-header">
                <i class='bx bx-leaf'></i>
                <h3>Collection Amount</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="label">Today's Collection:</span>
                    <span class="value"><?php echo isset($data['collection']->amount) ? number_format($data['collection']->amount, 1) . ' kg' : '0.0 kg'; ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Status:</span>
                    <span class="value status-badge <?php echo isset($data['collection']->status) ? strtolower($data['collection']->status) : 'pending'; ?>">
                        <?php echo isset($data['collection']->status) ? $data['collection']->status : 'Pending'; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-container">

        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bxs-calendar-check'></i>
                <span>Collections</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['total_collections']) ? $data['total_collections'] : '3'; ?>
                <small>this month</small>
            </div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bx-leaf'></i>
                <span>Tea Leaves</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['total_quantity']) ? $data['total_quantity'] : '120'; ?>
                <small>kg this month</small>
            </div>
        </div>
    </div>


    <!-- Current Set Date Card -->
    <div class="schedule-section">
        
        <div class="schedule-card current-schedule">
            <div class="card-content">
                <div class="schedule-info">
                    <div class="info-item">
                        <i class='bx bx-calendar'></i>
                        <span>Every Monday</span>
                    </div>
                    <div class="info-item">
                        <i class='bx bx-time-five'></i>
                        <span>08:00 AM</span>
                    </div>
                </div>
                <div class="schedule-action">
                    <button class="change-schedule-btn">
                        <i class='bx bx-calendar-edit'></i>
                        <span>Request Schedule Change</span>
                    </button>
                </div>
            </div>
        </div>
        
    </div>

    <div class="section-divider"></div>

    <!-- Schedule Section -->
    <div class="schedule-section">
        <div class="section-header">
            <h3>Upcoming Schedule</h3>
        </div>

        <?php if (!empty($data['todaySchedules'])): ?>
            <?php $schedule = $data['todaySchedules'][0]; // Access the first schedule ?>
            <div class="schedule-card">
                <div class="card-content">
                    <div class="card-header">
                        <div class="status-badge today">Collection Schedule #<?php echo $schedule->schedule_id; ?></div>
                    </div>
                    <div class="card-body">
                        <div class="schedule-info">
                            <div class="info-item">
                                <i class='bx bx-calendar'></i>
                                <span><?php echo date('m/d/Y', strtotime($schedule->start_time)); ?></span> <!-- Format the date -->
                            </div>
                            <div class="info-item">
                                <i class='bx bx-time-five'></i>
                                <span><?php echo date('h:i A', strtotime($schedule->start_time)); ?></span> <!-- Format the time -->
                            </div>
                            <div class="info-item">
                                <i class='bx bx-user'></i>
                                <span>Driver: <?php echo $schedule->driver_id; // You may want to fetch the driver's name ?></span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-car'></i>
                                <span>Vehicle: <?php echo $schedule->license_plate; ?></span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-check-circle'></i>
                                <span>Current Status: <?php echo $schedule->schedule_status; ?></span>
                            </div>
                        </div>
                        <div class="schedule-action">
                            <?php if (!empty($data['collectionId'])): ?>
                                <a href="<?php echo URLROOT; ?>/Supplier/collection/<?php echo $data['collectionId']; ?>" class="view-details-btn">
                                    <i class='bx bx-info-circle'></i>
                                    <span>View Details</span>
                                </a>
                            <?php else: ?>
                                <span class="no-details">Collection hasnt started yet!</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-schedule">
                <p>No upcoming schedules for today.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="section-divider"></div>


</main>
<script>

</script>

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>





<style>
:root {
  /* Color Variables */
  --primary-color: var(--mainn);
  --secondary-color: #2ecc71;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --background-light: #f8f9fa;
  --border-color: #e0e0e0;
  --success-color: #27ae60;
  --warning-color: #f39c12;
  
  /* Spacing */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;
  
  /* Border Radius */
  --border-radius-sm: 4px;
  --border-radius-md: 8px;
  --border-radius-lg: 12px;
}

/* Layout & Common Styles */
main {
  padding: var(--spacing-lg);
  max-width: 1200px;
  margin: 0 auto;
}

.section-divider {
  height: 1px;
  background-color: var(--border-color);
  margin: var(--spacing-xl) 0;
}

/* Dashboard Header */
.head-title {
  margin-bottom: var(--spacing-xl);
}

.head-title h1 {
  color: var(--text-primary);
  font-size: 1.75rem;
  margin-bottom: var(--spacing-sm);
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  list-style: none;
  padding: 0;
}

.breadcrumb a {
  color: var(--text-secondary);
  text-decoration: none;
}

.breadcrumb i {
  color: var(--primary-color);
}

/* Stats Container */
.stats-container {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: var(--spacing-lg);
  background-color: white;
  padding: var(--spacing-lg);
  border-radius: var(--border-radius-lg);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: var(--spacing-xl);
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.stat-header {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  color: var(--text-secondary);
}

.stat-header i {
  font-size: 1.5rem;
  color: var(--primary-color);
}

    .stat-value {
        font-size: 1.5em; /* Adjust font size for better visibility */
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .stats-container {
            flex-direction: column; /* Stack items vertically on small screens */
        }

        .stat-item {
            flex: 1 1 100%; /* Make each item take full width */
        }
    }

    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin: 1rem 0;
    }

    .card {
        background: #fff;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .card-header i {
        font-size: 1.5rem;
        color: #007664;
    }

    .card-header h3 {
        font-size: 1.1rem;
        color: #333;
        margin: 0;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .label {
        color: #666;
        font-size: 0.9rem;
    }

    .value {
        font-weight: 500;
        color: #333;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .status-badge.arrived {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.completed {
        background: #cce5ff;
        color: #004085;
    }

    #vehicle-location-map {
        border-radius: 4px;
        overflow: hidden;
    }
</style>

<!-- Add necessary scripts -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
    // Initialize map
    function initMap() {
        const map = new google.maps.Map(document.getElementById('vehicle-location-map'), {
            zoom: 13,
            center: { lat: 0, lng: 0 }
        });

        // Update vehicle location and distance
        function updateVehicleLocation() {
            // Replace with actual API call to get vehicle location
            const vehicleLocation = <?php echo json_encode($data['vehicleLocation'] ?? null); ?>;
            
            if (vehicleLocation) {
                const position = new google.maps.LatLng(vehicleLocation.lat, vehicleLocation.lng);
                
                // Update marker position
                marker.setPosition(position);
                map.setCenter(position);
                
                // Calculate and update distance
                calculateDistance(position);
            }
        }

        // Create vehicle marker
        const marker = new google.maps.Marker({
            map: map,
            icon: {
                url: '<?php echo URLROOT; ?>/public/images/truck-icon.png',
                scaledSize: new google.maps.Size(32, 32)
            }
        });

        // Update location every 30 seconds
        setInterval(updateVehicleLocation, 30000);
        updateVehicleLocation();
    }

    // Initialize map when page loads
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
