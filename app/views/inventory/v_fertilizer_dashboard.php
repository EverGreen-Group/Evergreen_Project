<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Fertilizer Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Fertilizer</a></li>
            </ul>
        </div>
        <div class="action-buttons">
            <a href="<?php echo URLROOT; ?>/inventory/createfertilizer" class="btn btn-primary">
                <i class='bx bx-plus'></i>
                New Fertilizer
            </a>
        </div>
    </div>

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-check-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
                    <p>Accept Orders</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-x-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['a']) ? $data['a'] : '0'; ?></h3>
                    <p>Reject Orders</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-package'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['b']) ? $data['b'] : '0'; ?></h3>
                    <p>Available Orders</p>
                </div>
            </div>
        </li>
    </ul>

    <!-- Chart Section -->
        <div class="order">
            <div class="head">
                <h3>Monthly Fertilizer Usage</h3>
            </div>
            <div style="padding: 20px; margin: 10px;">
                <canvas id="fertilizerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Fertilizer Stock Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Fertilizer Stock</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Fertilizer Name</th>
                        <th>Code</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['fertilizer'])): ?>
                        <?php foreach ($data['fertilizer'] as $fertilizer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fertilizer->fertilizer_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->code); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->quantity); echo " "; echo $fertilizer->unit ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/inventory/updatefertilizer/<?php echo $fertilizer->id; ?>"
                                        class="btn btn-primary" title="Update">
                                        <i class='bx bx-edit-alt'></i> Update
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/inventory/deletefertilizer/<?php echo $fertilizer->id; ?>"
                                        class="btn btn-danger" title="Delete">
                                        <i class='bx bx-trash'></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">No fertilizer stock available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .pending {
        background-color: #FFF4DE;
        color: #FFA800;
    }

    .approved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }

    .rejected {
        background-color: #FFE2E5;
        color: #F64E60;
    }

    .constraints-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 15px;
    }

    .constraint-group {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
    }

    .constraint-group h4 {
        margin-bottom: 15px;
        color: #333;
        font-weight: 600;
    }

    .constraint-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .constraint-label {
        font-weight: 500;
        color: #2B2D42;
    }

    .constraint-input {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .constraint-input input,
    .constraint-input select {
        width: 100px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .constraint-input select {
        width: 120px;
    }

    .unit {
        color: #666;
    }

    

    .details-link {
        color: #3C91E6;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 14px;
    }

    .details-link:hover {
        text-decoration: underline;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php' ?>

<script>
    // Get the chart canvas
    const ctx = document.getElementById('fertilizerChart').getContext('2d');

    const chartData = <?= json_encode($data['chart_data']) ?>

// Map months and quantities
const monthNames = ["January", "February", "March", "April", "May", "June", 
                    "July", "August", "September", "October", "November", "December"];

const fertilizerData = {
    labels: chartData.map(entry => {
        const [year, month] = entry.month.split("-");
        return monthNames[parseInt(month, 10) - 1];
    }),
    datasets: [{
        label: 'Fertilizer Usage (kg)',
        data: chartData.map(entry => entry.total_quantity),
        backgroundColor: 'rgba(60, 145, 230, 0.5)',
        borderColor: 'rgba(60, 145, 230, 1)',
        borderWidth: 1
    }]
};

    // Create the bar chart
    new Chart(ctx, {
        type: 'bar',
        data: fertilizerData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity (kg)'
                    }
                }
            },
            plugins: {
                title: {
                    display: false
                }
            }
        }
    });
</script>