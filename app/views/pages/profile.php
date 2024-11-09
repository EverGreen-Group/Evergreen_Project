<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<!-- var_dump($_SESSION); -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="profile-main">
    <div class="profile-header">
        <h1>User Profile</h1>
        <div class="profile-actions">
            <a href="#" class="btn btn-primary" onclick="editProfile()"><i class="fas fa-edit"></i> Edit Profile</a>
        </div>
    </div>

    <?php
    // Hardcoded driver data for demonstration

    $userModel = new M_User();
    $userInfo = $userModel->getUserById($_SESSION['user_id']);

    $driver = [
        'name' => 'Chaminda Perera',
        'license_number' => 'DL123456',
        'registration_date' => '2023-01-15',
        'current_shift_id' => 'SHIFT001',
        'current_team_id' => 'TEAM005',
        'hours_worked' => 8,
        'collections_driven' => 12,
        'email' => 'chaminda.perera@example.com',
        'phone' => '+94 77 123 4567',
        'address' => '123 Rampart Street, Fort, Galle, Sri Lanka'
    ];

    // Hardcoded team data for demonstration
    $team = [
        'name' => 'Green Squad',
        'vehicle_id' => 'VEH789',
        'partner_id' => 'DRV456',
        'partner_name' => 'Jane Smith',
        'partner_email' => 'jane.smith@example.com',
        'partner_phone' => '+1 (555) 987-6543'
    ];

    // Updated vehicle data
    $vehicle = [
        'id' => 'VEH789',
        'name' => 'Isuzu Elf',
        'type' => 'Freezer Truck',
        'capacity' => '80%'
    ];

    // Updated financial data for last shift
    $financial = [
        'base_pay' => 1500.00,
        'distance_bonus' => 300.00,
        'time_spent_bonus' => 150.00,
        'peak_hours_bonus' => 200.00,
        'customer_satisfaction_bonus' => 100.00,
        'on_time_bonus' => 150.00,
        'failed_delivery_penalty' => -50.00,
        'supplier_delay_wait' => 75.00,
        'cancelled_deliveries' => -25.00
    ];

    $total_earnings = array_sum($financial);
    ?>

    <div class="profile-content">
        <div class="profile-top">
            <div class="profile-image">
                <img src="https://i.pravatar.cc/150?img=68" alt="Driver Avatar" class="profile-avatar">
            </div>
            <div class="profile-info">
                <div class="detail-grid">

                    <div class="detail-item">
                        <span class="detail-label">First Name</span>
                        <span class="detail-value"><?php echo htmlspecialchars($userInfo['first_name']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Last Name</span>
                        <span class="detail-value"><?php echo htmlspecialchars($userInfo['last_name']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value"><?php echo htmlspecialchars($userInfo['email']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NIC</span>
                        <span class="detail-value"><?php echo htmlspecialchars($userInfo['nic']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Gender</span>
                        <span class="detail-value"><?php echo htmlspecialchars($userInfo['gender']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date of Birth</span>
                        <span class="detail-value"><?php echo date('F j, Y', strtotime($userInfo['date_of_birth'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once APPROOT . '/models/M_Driver.php';
        if (RoleHelper::hasRole(RoleHelper::DRIVER)): 
            // Get driver details
            $driverModel = new M_Driver();
            $driverDetails = $driverModel->getDriverDetails($_SESSION['user_id']);
        ?>
        <div class="profile-details">
            <h3>Driver Information</h3>
            <div class="detail-grid single-row">
                <div class="detail-item">
                    <span class="detail-label">License Number</span>
                    <span class="detail-value"><?php echo $driverDetails ? htmlspecialchars($driverDetails->license_no) : 'Not Available'; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Registration Date</span>
                    <span class="detail-value">
                        <?php echo $driverDetails && $driverDetails->registration_date ? 
                            date('F j, Y', strtotime($driverDetails->registration_date)) : 'Not Available'; ?>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Current Shift</span>
                    <span class="detail-value"><?php echo $driverDetails && $driverDetails->current_shift ? 
                        htmlspecialchars($driverDetails->current_shift) : 'Not Assigned'; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Current Team</span>
                    <span class="detail-value"><?php echo $driverDetails && $driverDetails->current_team ? 
                        htmlspecialchars($driverDetails->current_team) : 'Not Assigned'; ?></span>
                </div>
            </div>

            <?php 
            // Get team information if driver has one
            if ($driverDetails->team_id): 
            ?>
                <h3>Team Information</h3>
                <div class="card-grid">
                    <?php if ($driverDetails->vehicle_id): ?>
                    <div class="info-card horizontal">
                        <div class="card-image">
                            <img src="<?php echo $driverDetails->vehicle_image; ?>" alt="Vehicle Image" class="team-image">
                        </div>
                        <div class="card-content">
                            <h4>Team: <?php echo htmlspecialchars($driverDetails->current_team); ?></h4>
                            <p><strong>Vehicle ID:</strong> <?php echo htmlspecialchars($driverDetails->vehicle_id); ?></p>
                            <p><strong>Vehicle:</strong> <?php echo htmlspecialchars($driverDetails->vehicle_type); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($driverDetails->vehicle_status); ?></p>
                            <div class="card-actions">
                                <a href="#" class="btn btn-secondary" onclick="reportIssue('vehicle')">
                                    <i class="fas fa-flag"></i> Report Issue
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($driverDetails->partner_id): ?>
                    <div class="info-card horizontal">
                        <div class="card-image">
                            <?php
                            // theres some underlying issue with the image path, ill check this later
                            // var_dump($driverDetails->partner_photo);
                            // var_dump($driverDetails->partner_image_type);
                            // var_dump(UPLOADROOT);

                            $photoFileName = $driverDetails && $driverDetails->partner_photo 
                                ? $driverDetails->partner_photo . '.' . $driverDetails->partner_image_type
                                : '';
                            
                            // Use UPLOADURL for the web-accessible path
                            $photoPath = $photoFileName && file_exists(UPLOADPATH . '/user_photos/' . $photoFileName)
                                ? UPLOADURL . '/user_photos/' . $photoFileName
                                : URLROOT . '/img/default-avatar.jpg';
                            
                            // Debug
                            // echo "<!-- File exists check: " . UPLOADPATH . '/user_photos/' . $photoFileName . " -->";
                            // echo "<!-- Final URL: " . $photoPath . " -->";
                            ?>
                            <img src="<?php echo htmlspecialchars($photoPath); ?>" 
                                 alt="Partner Image" 
                                 class="team-image"
                                 onerror="this.src='<?php echo URLROOT; ?>/img/default-avatar.jpg'">
                        </div>
                        <div class="card-content">
                            <h4>Partner Information</h4>
                            <p><strong>Partner ID:</strong> <?php echo htmlspecialchars($driverDetails->partner_id); ?></p>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($driverDetails->partner_name); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($driverDetails->partner_phone); ?></p>
                            <div class="card-actions">
                                <a href="#" class="btn btn-secondary" onclick="reportUser()">
                                    <i class="fas fa-flag"></i> Report User
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
function editProfile() {
    alert('Edit profile functionality to be implemented.');
}

function reportUser() {
    alert('Report user functionality to be implemented.');
}

function reportIssue(type) {
    alert('Report ' + type + ' issue functionality to be implemented.');
}

</script>

<style>
.profile-main {
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
}

.profile-header {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.profile-header h1 {
    font-size: 2.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.profile-actions {
    margin-top: 0;
}

.profile-container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0);
    overflow: hidden;
}

.profile-content {
    display: flex;
    flex-direction: column;
}

.profile-top {
    display: flex;
    align-items: center;
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
    border: 3px solid #86E211;
}

.profile-image {
    margin-right: 2rem;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0);
}

.profile-info {
    flex: 1;
    padding: 1rem;
}

.profile-name {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.profile-id {
    color: #7f8c8d;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.profile-contact {
    margin-top: 1rem;
}

.info-row-group {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.info-row {
    flex: 1;
    display: flex;
    background: #f5f5f5;
    padding: 0.75rem;
    border-radius: 4px;
}

.info-row label {
    width: 120px;
    color: #666;
    font-weight: 500;
}

.info-row p {
    margin: 0;
    color: #333;
}

.profile-contact p {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #444;
}

.profile-contact i {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 50%;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Icon specific colors */
.profile-contact i.fa-user { color: #2196F3; }
.profile-contact i.fa-envelope { color: #4CAF50; }
.profile-contact i.fa-id-card { color: #FF9800; }
.profile-contact i.fa-venus-mars { color: #9C27B0; }
.profile-contact i.fa-calendar-alt { color: #F44336; }

/* Responsive design */
@media (max-width: 768px) {
    .profile-contact {
        grid-template-columns: 1fr; /* Single column on mobile */
    }
}

.profile-details {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0);
}

.profile-details h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2c3e50;
    border-bottom: 2px solid #ecf0f1;
    padding-bottom: 0.5rem;
}

.detail-grid, .metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.detail-item, .metric-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
}

.detail-label, .metric-label {
    font-size: 0.85rem;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 0.25rem;
}

.detail-value, .metric-value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 500;
}

.metric-item {
    text-align: center;
}

.metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #27ae60;
    display: block;
    margin-bottom: 0.5rem;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.info-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
    display: flex;
    flex-direction: column;
}

.info-card.horizontal {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
}

.info-card.horizontal .card-image {
    flex: 0 0 200px;
    height: auto;
}

.info-card.horizontal .card-content {
    flex: 1;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.team-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-content {
    flex: 1;
    padding: 1rem;
}

.card-content h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.card-content p {
    font-size: 0.95rem;
    color: #34495e;
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.card-content p strong {
    font-weight: 600;
    color: #333;
}

.large-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2980b9;
    margin-bottom: 0.25rem;
}

.work-history {
    margin-bottom: 2rem;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.history-table th,
.history-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.history-table th {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #2c3e50;
    background-color: #f8f9fa;
}

.history-table td {
    font-size: 0.95rem;
}

.performance {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.performance.high {
    background-color: #d4edda;
    color: #155724;
}

.performance.medium {
    background-color: #fff3cd;
    color: #856404;
}

.performance.low {
    background-color: #f8d7da;
    color: #721c24;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.95rem;
    transition: background-color 0.3s, transform 0.1s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background-color: #86E211;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #F06E6E;
}

.btn-secondary:hover {
    background-color: #545b62;
}

.financial-grid {
    grid-template-columns: 1fr 2fr;
}

.financial-table {
    width: 100%;
    border-collapse: collapse;
}

.financial-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.financial-table td {
    padding: 0.5rem;
    font-size: 0.95rem;
}

.financial-table .amount {
    text-align: right;
    font-weight: 600;
}

.financial-table .amount.positive {
    color: #28a745;
}

.financial-table .amount.negative {
    color: #dc3545;
}

.large-text {
    font-size: 1.75rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 0.25rem;
}

.chart-container {
    width: 100%;
    max-width: 800px;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0);
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.detail-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.detail-label {
    display: block;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.detail-value {
    color: #333;
    font-weight: 500;
}

.detail-grid.single-row {
    grid-template-columns: repeat(4, 1fr);  /* Creates 4 equal columns */
    gap: 1rem;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>