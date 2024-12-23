<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Supplier Manager Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="search-container">
        <div class="search-box">
            <i class='bx bx-search'></i>
            <input type="text" id="supplierSearch" placeholder="Search supplier by name...">
        </div>
        <div class="supplier-select">
            <select id="currentSupplier" onchange="updateSupplierDetails(this.value)">
                <option value="">Select a Supplier</option>
                <?php foreach($data['suppliers'] as $supplier): ?>
                    <option value="<?php echo $supplier->supplier_id; ?>">
                        <?php echo $supplier->first_name . ' ' . $supplier->last_name . ' (ID: ' . $supplier->supplier_id . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bx-leaf'></i>
            <span class="text">
                <h3></h3>
                <p>Total Collections</p>
                <small>Daily Target: 300kg</small>
            </span>
        </li>
        <li>
            <i class='bx bx-calendar-check'></i>
            <span class="text">
                <h3></h3>
                <p>Collection Days</p>
                <small>This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bx-trending-up'></i>
            <span class="text">
                <h3></h3>
                <p>Performance Rate</p>
                <small>Based on Target</small>
            </span>
        </li>
    </ul>

    
    <!-- Bar Graph and Collection Stats Row -->
    <div class="table-data">
        <!-- Weekly Collection Graph -->
        <div class="order" style="flex: 0.5;">
            <div class="head">
                <h3>Weekly Collection Overview</h3>
                <div class="head-actions">
                    <a href="<?php echo URLROOT; ?>/suppliermanager/supplierStatement/<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>" 
                       class="btn-download" 
                       id="statementBtn">
                        <i class='bx bx-file'></i> Monthly Statement
                    </a>


                </div>
            </div>
            <div class="graph-container">
                <canvas id="teaLeavesGraph"></canvas>
            </div>
        </div>



        <!-- Supplier Profile Card -->
        <div class="order" style="flex: 0.5;">
            <div class="profile-top">
                <div class="profile-image">
                    <img src="https://i.pravatar.cc/150?img=68" alt="Supplier Avatar" class="profile-avatar">
                </div>
                <div class="profile-info">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">First Name</span>
                            <span class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Name</span>
                            <span class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">NIC</span>
                            <span class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Gender</span>
                            <span class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date of Birth</span>
                            <span class="detail-value"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="table-data">
        <div class="order" style="flex: 0.6;">
            <div class="head">
                <h3>Supplier Location</h3>
            </div>
            <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
        </div>

        <div class="order" style="flex: 0.4;">
            <div class="head">
                <h3>Recent Collections</h3>
            </div>
            <table class="collection-history">
                <tbody>
                    <tr class="collection-entry">
                        <td class="collection-header">
                            04/03/2024 Type: S 1628 / 9005
                        </td>
                    </tr>
                    <tr class="collection-details">
                        <td>
                            <span class="weight">84.00</span>
                            <span class="deduction">3.50</span>
                            <span class="final-weight">80.50</span>
                            <span class="unit">kg</span>
                        </td>
                    </tr>
                    <tr class="collection-entry">
                        <td class="collection-header">
                            03/03/2024 Type: S 1627 / 9004
                        </td>
                    </tr>
                    <tr class="collection-details">
                        <td>
                            <span class="weight">92.00</span>
                            <span class="deduction">4.00</span>
                            <span class="final-weight">88.00</span>
                            <span class="unit">kg</span>
                        </td>
                    </tr>
                    <tr class="collection-entry">
                        <td class="collection-header">
                            04/03/2024 Type: B 1627 / 9004
                        </td>
                    </tr>
                    <tr class="collection-details">
                        <td>
                            <span class="weight">92.00</span>
                            <span class="deduction">4.00</span>
                            <span class="final-weight">88.00</span>
                            <span class="unit">kg</span>
                        </td>
                    </tr>
                    <!-- Add more entries as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function loadSkeletonData(skeletonId) {
        // Find the skeleton data from the existing skeletons
        <?php echo 'const skeletons = ' . json_encode($data['skeletons']) . ';'; ?>
        
        const skeleton = skeletons.find(s => s.skeleton_id === skeletonId);
        if (skeleton) {
            // Populate the edit form fields
            document.getElementById('edit_route').value = skeleton.route_id;
            document.getElementById('edit_team').value = skeleton.team_id;
            document.getElementById('edit_vehicle').value = skeleton.vehicle_id;
            document.getElementById('edit_shift').value = skeleton.shift_id;
        }
    }
    </script>

</main>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>



<script>
    // Example of handling form submission for assigning collection
    document.getElementById('assignCollectionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const route = document.getElementById('route').value;
        const team = document.getElementById('team').value;
        const shift = document.getElementById('shift').value;

        // Log or handle the assigned collection details
        console.log(`Collection Assigned: Route - ${route}, Team - ${team}, Shift - ${shift}`);
        alert('Collection assigned successfully!');
    });

    // Example logic for adding late suppliers to ongoing collections
    document.querySelectorAll('.assign-btn').forEach(button => {
        button.addEventListener('click', function() {
            alert('Supplier added to collection!');
        });
    });
</script>

<style>
    /* Box Layout */
    .table-data {
        display: flex;
        flex-wrap: wrap;
        grid-gap: 24px;
        margin-top: 24px;
        width: 100%;
        color: var(--dark);
    }

    .table-data > div {
        border-radius: 20px;
        background: var(--light);
        padding: 24px;
        overflow-x: auto;
    }

    .table-data .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        grid-gap: 16px;
        margin-bottom: 24px;
    }

    /* Graph Styles */
    .graph-container {
        width: 100%;
        height: 400px;
        padding: 20px;
    }

    #teaLeavesGraph {
        width: 100% !important;
        height: 100% !important;
    }

    /* Collection Stats */
    .stats-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        margin: 1rem 0;
        border: 2px solid var(--main);
        border-radius: 8px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
    }

    /* Progress Indicators */
    .progress-bar {
        width: 100%;
        height: 15px;
        background: var(--grey);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress {
        height: 100%;
        background: var(--main);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--light);
        font-size: 11px;
    }

    /* Map Section */
    #map-container {
        height: 300px;
        width: 100%;
        margin-bottom: 1rem;
        border-radius: 5px;
        overflow: hidden;
    }

    #collection-details {
        background: var(--grey);
        padding: 1rem;
        border-radius: 5px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    /* Buttons and Actions */
    .btn-download {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--main);
        color: var(--light);
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: transparent;
    }

    /* Constraints Section */
    .constraints-container {
        margin-bottom: 2rem;
    }

    .constraint-card {
        background: var(--light);
        border: 1px solid var(--grey);
        border-radius: 8px;
        overflow: hidden;
    }

    .constraint-header {
        background: var(--grey);
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid var(--grey);
    }

    /* Status Indicators */
    .variance {
        font-weight: bold;
    }

    .variance.positive { color: var(--success); }
    .variance.negative { color: var(--danger); }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .status-badge.completed {
        background: var(--main);
        color: var(--light);
    }

    /* Table Styles */
    .table-data table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-data table th {
        padding: 12px 10px;
        font-size: 14px;
        text-transform: uppercase;
        background: var(--grey);
        text-align: left;
        font-weight: 600;
    }

    .table-data table td {
        padding: 12px 10px;
        font-size: 14px;
        border-bottom: 1px solid var(--grey);
        vertical-align: middle;
    }

    /* Form Elements */
    select, input[type="number"] {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        border: 1px solid var(--grey);
    }

    /* Progress Chart */
    .progress-chart-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 2rem auto;
    }

    .progress-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .progress-percentage {
        display: block;
        font-size: 1.8rem;
        font-weight: 600;
        color: #007664;
    }

    .progress-label {
        display: block;
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.2rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>


<style>
    .collection-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }

    .stat-card {
        background-color: #f8f9fa;
        padding: 1.2rem;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: px solid var(--main);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        display: block;
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }

    .stat-subtext {
        display: block;
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.2rem;
    }

    .stat-icon {
        font-size: 2rem;
        color: #007664;
        margin-left: 1rem;
    }

    .progress-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .progress-info .progress-bar {
        height: 8px;
        margin-top: 0.2rem;
    }

    .progress-info .progress {
        font-size: 0;
        background-color: #007664;
    }

    .head-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-download {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #007664;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-download:hover {
        background-color: #005a4d;
    }

    .btn-download i {
        font-size: 1.1rem;
    }
    </style>


    >

    <style>
    .supplier-card {
        background: var(--light);
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .supplier-image-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
    }

    .supplier-profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid var(--main);
    }

    .status-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .status-indicator.active {
        background-color: var(--success);
    }

    .supplier-info {
        text-align: center;
    }

    .supplier-info h4 {
        margin: 0;
        font-size: 1.4rem;
        color: var(--dark);
    }

    .supplier-id {
        color: var(--grey);
        font-size: 0.9rem;
        margin: 0.3rem 0;
    }

    .supplier-stats {
        font-size: 1rem;
        color: var(--dark);
        margin: 0.5rem 0;
    }

    .supplier-rating {
        color: #ffc107;
        font-size: 1.2rem;
        margin: 0.5rem 0;
    }

    .supplier-rating small {
        color: var(--grey);
        margin-left: 0.5rem;
    }

    .supplier-actions {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 5px;
        background: var(--main);
        color: var(--light);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--main-dark);
    }

    .action-btn i {
        font-size: 1.1rem;
    }
    </style>

    <style>
    .tea-distribution {
        display: flex;
        flex-direction: column;
        height: calc(100% - 60px); /* Subtract header height */
        padding: 1rem;
    }

    .chart-container {
        flex: 1;
        min-height: 200px; /* Adjusted height */
        position: relative;
        margin: 0.5rem 0;
    }

    .distribution-legend {
        padding: 1rem;
        background: rgba(0, 0, 0, 0.03);
        border-radius: 8px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 1rem;
    }

    .dot.normal { background-color: #4CAF50; }
    .dot.super { background-color: #2196F3; }

    .legend-info {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .type {
        color: var(--dark);
        font-weight: 500;
    }

    .amount {
        color: var(--grey);
        font-size: 0.9rem;
        font-weight: 500;
    }
    </style>



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
        font-size: 0.8rem;
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

    <style>
    .status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .status.completed {
        background: #86E211;
        color: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    table th {
        font-weight: 600;
        color: #666;
    }
    </style>

    <style>
    .collection-history {
        width: 100%;
        border-collapse: collapse;
    }

    .collection-entry {
        background: #f8f9fa;
    }

    .collection-header {
        padding: 12px;
        font-weight: 500;
        color: #333;
        border-bottom: 1px solid #eee;
    }

    .collection-details td {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        color: #666;
    }

    .collection-details span {
        display: inline-block;
        margin-right: 15px;
    }

    .weight::before {
        content: "Weight: ";
        color: #888;
    }

    .deduction::before {
        content: "Deduction: ";
        color: #888;
    }

    .final-weight::before {
        content: "Final: ";
        color: #888;
    }

    .unit {
        color: #888;
    }
    </style>

    <style>
    .search-container {
        display: flex;
        gap: 1rem;
        margin: 24px 0;
        align-items: center;
    }

    .search-box {
        flex: 1;
        position: relative;
        max-width: 500px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 2px solid #eee;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #86E211;
        outline: none;
    }

    .supplier-select select {
        padding: 12px 20px;
        border: 2px solid #eee;
        border-radius: 10px;
        font-size: 1rem;
        min-width: 250px;
        background: white;
        cursor: pointer;
    }

    .supplier-select select:focus {
        border-color: #86E211;
        outline: none;
    }

    .no-results {
        text-align: center;
        color: #666;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 20px 0;
    }

    .error-message {
        color: #dc3545;
        text-align: center;
        padding: 10px;
        background: #f8d7da;
        border-radius: 8px;
        margin: 10px 0;
    }
</style>

<script>
// Add this function to handle supplier search
document.getElementById('supplierSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const select = document.getElementById('currentSupplier');
    const options = select.options;

    for (let i = 0; i < options.length; i++) {
        const text = options[i].text.toLowerCase();
        if (text.includes(searchTerm) || options[i].value.includes(searchTerm)) {
            options[i].style.display = '';
        } else {
            options[i].style.display = 'none';
        }
    }
});

// Add this function to update the dashboard when a supplier is selected
function updateDashboard(supplierId) {
    if (!supplierId) return;

    // Fetch supplier data from backend
    fetch(`/api/supplier/${supplierId}`)
        .then(response => response.json())
        .then(data => {
            // Update profile info
            document.querySelector('.profile-info .detail-value').textContent = data.firstName;
            document.querySelector('.profile-info .detail-value:nth-child(2)').textContent = data.lastName;
            // ... update other profile fields

            // Update collection stats
            document.querySelector('.box-info li:nth-child(1) h3').textContent = data.todayCollection;
            document.querySelector('.box-info li:nth-child(2) h3').textContent = data.collectionDays;
            document.querySelector('.box-info li:nth-child(3) h3').textContent = data.performanceRate;

            // Update map
            updateMap(data.location);

            // Update collection history
            updateCollectionHistory(data.recentCollections);

            // Update graph
            updateCollectionGraph(data.collectionData);
        })
        .catch(error => console.error('Error:', error));
}
</script>

<script>
    // Sample data for the line graph
    const collectionDates = ['2023-10-01', '2023-10-02', '2023-10-03', '2023-10-04', '2023-10-05'];
    const teaLeavesCollected = [45, 43, 41, 39, 44]; // Sample values for tea leaves collected

    const ctx = document.getElementById('teaLeavesGraph').getContext('2d');
    const teaLeavesGraph = new Chart(ctx, {
        type: 'line', // Specify the type of chart
        data: {
            labels: collectionDates, // X-axis labels
            datasets: [{
                label: 'Tea Leaves Collected (kg)', // Label for the dataset
                data: teaLeavesCollected, // Data for the Y-axis
                borderColor: 'rgba(75, 192, 192, 1)', // Line color
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Area color
                borderWidth: 2,
                fill: false // Fill the area under the line
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Collection Dates' // X-axis title
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount (kg)' // Y-axis title
                    },
                    beginAtZero: true, // Start Y-axis at zero
                    max: 50, // Set a custom maximum value for the Y-axis
                    min: 30
                }
            }
        }
    });

    // Initialize the map with a default center
    let map;
    let marker;

    function initMap() {
        // Default center (can be your factory location)
        const defaultCenter = { lat: 6.2173037, lng: 80.2564385 };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: defaultCenter,
            mapId: "4504f8b37365c3d0",
            disableDefaultUI: true,
            zoomControl: true
        });

        // Create a marker but don't set its position yet
        marker = new google.maps.Marker({
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('supplierSearch');
        const supplierSelect = document.getElementById('currentSupplier');
        const boxInfo = document.querySelector('.box-info');
        
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const options = supplierSelect.options;
            let found = false;

            // Reset the no results message
            if (document.querySelector('.no-results')) {
                document.querySelector('.no-results').remove();
            }

            for (let i = 1; i < options.length; i++) {
                const text = options[i].text.toLowerCase();
                const value = options[i].value;
                if (text.includes(searchTerm) || value.includes(searchTerm)) {
                    options[i].style.display = '';
                    found = true;
                    if (searchTerm.length > 2) {
                        supplierSelect.value = options[i].value;
                        updateSupplierDetails(options[i].value);
                    }
                } else {
                    options[i].style.display = 'none';
                }
            }

            // Show "No supplier found" message only if no matches and search term exists
            if (!found && searchTerm.length > 0) {
                if (!document.querySelector('.no-results')) {
                    boxInfo.insertAdjacentHTML('beforeend', '<p class="no-results">No supplier found</p>');
                }
            } else {
                // Remove the no results message if there are matches
                const noResults = document.querySelector('.no-results');
                if (noResults) {
                    noResults.remove();
                }
            }
        });
    });

    function updateSupplierDetails(supplierId) {
        if (!supplierId) return;

        fetch('<?php echo URLROOT; ?>/suppliermanager/getSupplierDetails', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'supplier_id=' + supplierId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const supplier = data.data.supplier;
                const stats = data.data.stats;
                
                // Update box-info statistics
                document.querySelector('.box-info li:nth-child(1) h3').textContent = 
                    stats.totalQuantity + ' kg';
                document.querySelector('.box-info li:nth-child(2) h3').textContent = 
                    stats.collectionDays + ' days';
                document.querySelector('.box-info li:nth-child(3) h3').textContent = 
                    stats.performanceRate + '%';

                // Update profile info
                document.querySelector('.detail-grid').innerHTML = `
                    <div class="detail-item">
                        <span class="detail-label">First Name</span>
                        <span class="detail-value">${supplier.first_name}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Last Name</span>
                        <span class="detail-value">${supplier.last_name}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">${supplier.email}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NIC</span>
                        <span class="detail-value">${supplier.nic}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Gender</span>
                        <span class="detail-value">${supplier.gender}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date of Birth</span>
                        <span class="detail-value">${supplier.date_of_birth}</span>
                    </div>
                `;

                // Update map marker position
                if (supplier.latitude && supplier.longitude) {
                    const supplierLocation = {
                        lat: parseFloat(supplier.latitude),
                        lng: parseFloat(supplier.longitude)
                    };
                    
                    marker.setPosition(supplierLocation);
                    map.setCenter(supplierLocation);
                    
                    // Update marker info window
                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div style="padding: 10px;">
                                <h3 style="margin: 0 0 5px 0;">${supplier.first_name} ${supplier.last_name}</h3>
                                <p style="margin: 0;">Collection Point</p>
                            </div>`
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(map, marker);
                    });
                }

                // Remove any existing error messages
                const noResults = document.querySelector('.no-results');
                if (noResults) {
                    noResults.remove();
                }
            } else {
                // Show error message
                document.querySelector('.box-info').innerHTML = 
                    `<p class="error-message">${data.message}</p>`;
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>
