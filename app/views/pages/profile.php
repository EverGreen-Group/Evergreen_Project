<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

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
                <h2 class="profile-name"><?php echo $driver['name']; ?></h2>
                <p class="profile-id">ID: <?php echo $driver['license_number']; ?></p>
                <div class="profile-contact">
                    <p><i class="fas fa-envelope"></i> <?php echo $driver['email']; ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo $driver['phone']; ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo $driver['address']; ?></p>
                </div>
            </div>
        </div>
        <div class="profile-details">
            <h3>Driver Information</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">License Number</span>
                    <span class="detail-value"><?php echo $driver['license_number']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Registration Date</span>
                    <span class="detail-value"><?php echo $driver['registration_date']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Current Shift ID</span>
                    <span class="detail-value"><?php echo $driver['current_shift_id']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Current Team ID</span>
                    <span class="detail-value"><?php echo $driver['current_team_id']; ?></span>
                </div>
            </div>
            <h3>Performance Metrics</h3>
            <div class="metrics-grid">
                <div class="metric-item">
                    <span class="metric-value"><?php echo $driver['hours_worked']; ?></span>
                    <span class="metric-label">Hours Worked</span>
                </div>
                <div class="metric-item">
                    <span class="metric-value"><?php echo $driver['collections_driven']; ?></span>
                    <span class="metric-label">Collections Driven</span>
                </div>
            </div>
            <h3>Team Information</h3>
            <div class="card-grid">
                <div class="info-card horizontal">
                    <div class="card-image">
                        <img src="https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg" alt="Vehicle Image" class="team-image">
                    </div>
                    <div class="card-content">
                        <h4>Team: <?php echo $team['name']; ?></h4>
                        <p><strong>Vehicle ID:</strong> <?php echo $vehicle['id']; ?></p>
                        <p><strong>Vehicle:</strong> <?php echo $vehicle['name']; ?></p>
                        <p><strong>Type:</strong> <?php echo $vehicle['type']; ?></p>
                        <p><strong>Current Capacity:</strong> <?php echo $vehicle['capacity']; ?></p>
                        <div class="card-actions">
                            <a href="#" class="btn btn-secondary" onclick="reportIssue('vehicle')"><i class="fas fa-flag"></i> Report Issue</a>
                        </div>
                    </div>
                </div>
                <div class="info-card horizontal">
                    <div class="card-image">
                        <img src="https://i.pravatar.cc/150?img=56" alt="Partner Image" class="team-image">
                    </div>
                    <div class="card-content">
                        <h4>Partner Information</h4>
                        <p><strong>Partner ID:</strong> <?php echo $team['partner_id']; ?></p>
                        <p><strong>Name:</strong> <?php echo $team['partner_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $team['partner_email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $team['partner_phone']; ?></p>
                        <div class="card-actions">
                            <a href="#" class="btn btn-secondary" onclick="reportUser()"><i class="fas fa-flag"></i> Report User</a>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Financial Information (Last Shift)</h3>
            <div class="card-grid financial-grid">
                <div class="info-card">
                    <div class="card-content">
                        <h4>Total Earnings</h4>
                        <p class="large-text">LKR <?php echo number_format($total_earnings, 2); ?></p>
                        <p>Last completed shift</p>
                    </div>
                </div>
                <div class="info-card">
                    <div class="card-content">
                        <h4>Earnings Breakdown</h4>
                        <table class="financial-table">
                            <?php foreach ($financial as $key => $value): ?>
                                <tr>
                                    <td><?php echo ucwords(str_replace('_', ' ', $key)); ?></td>
                                    <td class="amount <?php echo $value >= 0 ? 'positive' : 'negative'; ?>">
                                        LKR <?php echo number_format(abs($value), 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
            <h3>Earnings Trend (Last 30 Days)</h3>
            <div class="chart-container">
                <canvas id="earningsChart"></canvas>
            </div>
            <h3>Work History</h3>
            <div class="work-history">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Shift ID</th>
                            <th>Hours</th>
                            <th>Collections</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023-05-15</td>
                            <td>SHIFT089</td>
                            <td>8.5</td>
                            <td>12</td>
                            <td><span class="performance high">Excellent</span></td>
                        </tr>
                        <tr>
                            <td>2023-05-14</td>
                            <td>SHIFT088</td>
                            <td>7.5</td>
                            <td>10</td>
                            <td><span class="performance medium">Good</span></td>
                        </tr>
                        <tr>
                            <td>2023-05-13</td>
                            <td>SHIFT087</td>
                            <td>8.0</td>
                            <td>11</td>
                            <td><span class="performance high">Excellent</span></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
                <a href="#" class="btn btn-primary">View Full History</a>
            </div>
        </div>
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

// Sample data for the last 30 days
const last30Days = <?php echo json_encode(array_map(function($i) {
    return date('M d', strtotime("-$i days"));
}, range(29, 0, -1))); ?>;

const earnings = <?php echo json_encode(array_map(function() {
    return rand(1800, 2800); // Random earnings between 1800 and 2800 LKR
}, range(30, 1))); ?>;

// Create the chart
const ctx = document.getElementById('earningsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: last30Days,
        datasets: [{
            label: 'Daily Earnings (LKR)',
            data: earnings,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Earnings (LKR)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            }
        }
    }
});
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
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.profile-id {
    color: #7f8c8d;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.profile-contact {
    font-size: 0.95rem;
}

.profile-contact p {
    margin-bottom: 0.5rem;
}

.profile-contact i {
    width: 20px;
    color: #007bff;
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
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>