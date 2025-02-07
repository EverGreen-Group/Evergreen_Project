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

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Driver Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="action-buttons">
        <a href="#" id="openAddDriverModal" class="btn btn-primary" onclick="document.getElementById('addDriverModal').style.display='block'">
            <i class='bx bx-plus'></i>
            Register New Driver
        </a>
        <a href="#" class="btn btn-primary">
            <i class='bx bx-calendar'></i>
            View Schedule
        </a>
    </div>

    <ul class="box-info">
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <p>Total Drivers</p>
                <h3><?php echo $data['total_drivers']; ?></h3>
            </span>
        </li>
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <p>On Route</p>
                <h3><?php echo $data['on_duty_drivers']; ?></h3>
            </span>
        </li>
        <li>
            <i class='bx bxs-user-check'></i>
            <span class="text">
                <p>Available</p>
                <h3><?php echo $data['unassigned_drivers_count']; ?></h3>
            </span>
        </li>
    </ul>

    <div id="addDriverModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addDriverModal')">&times;</span>
        <h2>Add New Driver</h2>
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <form id="createDriverForm" method="POST" action="<?php echo URLROOT; ?>/vehiclemanager/addDriver">
                    <!-- User Selection -->
                    <div class="detail-group">
                        <h3>Select User</h3>
                        <div class="detail-row">
                            <span class="label">User:</span>
                            <span class="value">
                                <select id="userSelect" name="user_id" class="form-control modal-select" required>
                                    <option value="">Select a User</option>
                                    <?php foreach ($data['users'] as $user): ?>
                                        <option value="<?= $user->user_id ?>" 
                                                data-first-name="<?= htmlspecialchars($user->first_name) ?>"
                                                data-last-name="<?= htmlspecialchars($user->last_name) ?>"
                                                data-email="<?= htmlspecialchars($user->email) ?>"
                                                data-nic="<?= htmlspecialchars($user->nic) ?>"
                                                data-dob="<?= $user->date_of_birth ?>"
                                                data-gender="<?= $user->gender ?>">
                                            <?= htmlspecialchars($user->first_name . ' ' . $user->last_name . ' (' . $user->nic . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>

                    <!-- General Information -->
                    <div class="detail-group">
                        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                            <h3>General Information</h3>
                        </div>

                        <!-- User icon centered in its own detail-row -->
                        <div class="detail-row" style="justify-content: center; margin-bottom: 20px;">
                            <i class='bx bx-user' style="font-size: 2em; color: #3C91E6;"></i>
                        </div>
                        <div class="specifications-container">

                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">First Name:</span>
                                    <span class="value" id="firstName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Last Name:</span>
                                    <span class="value" id="lastName"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Email:</span>
                                    <span class="value" id="email"></span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">NIC:</span>
                                    <span class="value" id="nic"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Date of Birth:</span>
                                    <span class="value" id="dateOfBirth"></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Gender:</span>
                                    <span class="value" id="gender"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Information -->
                    <div class="detail-group">
                        <h3>Driver Information</h3>
                        <div class="specifications-container">
                            <div class="specifications-left">
                                <div class="detail-row">
                                    <span class="label">Address Line 1:</span>
                                    <span class="value">
                                        <input type="text" id="address_line1" name="address_line1" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Address Line 2:</span>
                                    <span class="value">
                                        <input type="text" id="address_line2" name="address_line2" class="form-control">
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">City:</span>
                                    <span class="value">
                                        <input type="text" id="city" name="city" class="form-control" required>
                                    </span>
                                </div>
                            </div>
                            <div class="specifications-right">
                                <div class="detail-row">
                                    <span class="label">Contact Number:</span>
                                    <span class="value">
                                        <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Emergency Contact:</span>
                                    <span class="value">
                                        <input type="text" id="emergency_contact" name="emergency_contact" class="form-control" required>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary full-width">ADD DRIVER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                <h3>Driver List</h3>
                <button class="filter-btn">
                    <i class='bx bx-filter'></i>
                    Filter by availability
                </button>
            </div>
            <div class="vehicle-grid">
                <?php if (!empty($data['drivers'])): ?>
                    <?php foreach ($data['drivers'] as $driver): ?>
                        <div class="vehicle-card" 
                            data-driver-id="<?php echo htmlspecialchars($driver->driver_id); ?>"
                            data-employee-id="<?php echo htmlspecialchars($driver->employee_id); ?>"
                            data-name="<?php echo htmlspecialchars($driver->first_name . ' ' . $driver->last_name); ?>"
                            data-nic="<?php echo htmlspecialchars($driver->nic); ?>"
                            data-contact="<?php echo htmlspecialchars($driver->contact_number); ?>"
                            data-emergency="<?php echo htmlspecialchars($driver->emergency_contact); ?>"
                            data-status="<?php echo htmlspecialchars($driver->status); ?>"
                            data-employee-status="<?php echo htmlspecialchars($driver->employee_status); ?>"
                            onclick="updateDriverDetails(this)">
                            <img src="<?php echo URLROOT; ?>/uploads/driver_photos/<?php echo htmlspecialchars($driver->driver_id); ?>.png" 
                                alt="<?php echo htmlspecialchars($driver->first_name . ' ' . $driver->last_name); ?>"
                                onerror="this.src='<?php echo URLROOT; ?>/public/img/default_profile.jpg'">
                            <div class="card-content">
                                <div class="card-title">
                                    <h4><?php echo htmlspecialchars($driver->first_name . ' ' . $driver->last_name); ?></h4>
                                    <button class="bookmark-btn">
                                        <i class='bx bx-bookmark'></i>
                                    </button>
                                </div>
                                <div class="capacity"><?php echo htmlspecialchars($driver->status); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No drivers found matching your criteria.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Driver Details Section -->
        <div class="order vehicle-details">
            <div class="head">
                <h3>Driver Details</h3>
            </div>
            <div class="details-container">
                <div class="circular-container">
                    <img id="selectedDriverImage" 
                         src="<?php echo URLROOT; ?>/uploads/driver_photos/loading-driver.png"  
                         alt="Driver Photo">
                </div>
                
                <div class="details-content">
                    <div class="details-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Driver ID:</span>
                                <span class="value" id="detail-driver-id"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Name:</span>
                                <span class="value" id="detail-name"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">NIC:</span>
                                <span class="value" id="detail-nic"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Date of Birth:</span>
                                <span class="value" id="detail-dob"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Email:</span>
                                <span class="value" id="detail-email"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Number of Collections:</span>
                                <span class="value" id="detail-collection-count"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Number of Deliveries:</span>
                                <span class="value" id="detail-delivery-count"></span>
                            </div>
                        </div>
                    </div>

                    <div class="details-section">
                        <h4 class="section-title">Contact Information</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Contact Number:</span>
                                <span class="value" id="detail-contact"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Emergency Contact:</span>
                                <span class="value" id="detail-emergency"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Address:</span>
                                <span class="value" id="detail-address"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">City:</span>
                                <span class="value" id="detail-city"></span>
                            </div>
                        </div>
                    </div>

                    <div class="details-section">
                        <h4 class="section-title">Employment Information</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Hire Date:</span>
                                <span class="value" id="detail-hire-date"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Driver availability:</span>
                                <span class="value status-badge" id="detail-status"></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status:</span>
                                <span class="value status-badge" id="detail-employee-status"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Remove Driver Button -->
                    <div class="remove-vehicle">
                        <form action="<?php echo URLROOT; ?>/vehiclemanager/removeDriver" method="POST" onsubmit="return confirm('Are you sure you want to remove this driver?');">
                            <input type="hidden" name="driver_id" id="remove-driver-id" value="">
                            <button type="submit" class="btn btn-danger" style="width:100%;">Remove Driver</button>
                        </form>
                    </div>
                    <div class="remove-vehicle">
                        <form action="<?php echo URLROOT; ?>/vehiclemanager/removeDriver" method="POST" onsubmit="return confirm('Are you sure you want to remove this driver?');">
                            <input type="hidden" name="driver_id" id="remove-driver-id" value="">
                            <button type="submit" class="btn btn-danger" style="width:100%;">Set Inactive</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</main>



<script>
function updateDriverDetails(card) {
    const driverId = card.getAttribute('data-driver-id');

    // Show loading state
    document.getElementById('detail-name').textContent = 'Loading...';

    fetch(`${URLROOT}/vehiclemanager/getDriverDetails/${driverId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(driver => {
            // Basic Information
            document.getElementById('detail-driver-id').textContent = driver.driver_id;
            document.getElementById('detail-name').textContent = driver.first_name + ' ' + driver.last_name;
            document.getElementById('detail-nic').textContent = driver.nic;
            document.getElementById('detail-dob').textContent = driver.date_of_birth;
            document.getElementById('detail-email').textContent = driver.email;
            document.getElementById('detail-collection-count').textContent = driver.collections_count;
            document.getElementById('detail-delivery-count').textContent = driver.deliveries_count;

            // Contact Information
            document.getElementById('detail-contact').textContent = driver.contact_number;
            document.getElementById('detail-emergency').textContent = driver.emergency_contact;
            document.getElementById('detail-address').textContent = 
                `${driver.address_line1}${driver.address_line2 ? ', ' + driver.address_line2 : ''}`;
            document.getElementById('detail-city').textContent = driver.city;

            // Employment Information
            document.getElementById('detail-hire-date').textContent = driver.hire_date;
            document.getElementById('detail-status').textContent = driver.availability;
            document.getElementById('detail-employee-status').textContent = driver.status;
            // Update hidden input for remove form
            document.getElementById('remove-driver-id').value = driver.driver_id;

            // Update the driver image
            const driverImage = document.getElementById('selectedDriverImage');
            driverImage.src = `${URLROOT}/uploads/driver_photos/${driver.driver_id}.png`;
            driverImage.alt = driver.first_name + ' ' + driver.last_name;
            driverImage.onerror = function() {
                this.src = `${URLROOT}/public/img/default_profile.jpg`;
            };
        })
        .catch(error => {
            console.error('Error fetching driver details:', error);
            document.getElementById('detail-name').textContent = 'Error loading driver details';
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

document.getElementById('userSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    // Update the display fields with the selected user's information
    document.getElementById('firstName').textContent = selectedOption.getAttribute('data-first-name');
    document.getElementById('lastName').textContent = selectedOption.getAttribute('data-last-name');
    document.getElementById('email').textContent = selectedOption.getAttribute('data-email');
    document.getElementById('nic').textContent = selectedOption.getAttribute('data-nic');
    document.getElementById('dateOfBirth').textContent = selectedOption.getAttribute('data-dob');
});

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<style>
    .circular-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: var(--main);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
    }

    .circular-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>