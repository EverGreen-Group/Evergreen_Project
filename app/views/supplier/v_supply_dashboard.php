<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tea Leaves Supplier Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
        <a href="#" class="btn-download">
            <i class='bx bxs-cloud-download'></i>
            <span class="text">Download Report</span>
        </a>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-calendar-check'></i>
            <span class="text">
                <h3><?php echo isset($data['total_collections']) ? $data['total_collections'] : '0'; ?></h3>
                <p>Total Collections</p>
                <small>This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-leaf'></i>
            <span class="text">
                <h3><?php echo isset($data['total_quantity']) ? $data['total_quantity'] . 'kg' : '0kg'; ?></h3>
                <p>Total Tea Leaves</p>
                <small>This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-dollar-circle'></i>
            <span class="text">
                <h3>Rs. <?php echo isset($data['total_earnings']) ? number_format($data['total_earnings'], 2) : '0.00'; ?></h3>
                <p>Total Earnings</p>
                <small>This Month</small>
            </span>
        </li>
    </ul>

    <div class="table-data">
        
        <div class="order">
            <div class="head">
                <h3>Collection over past months</h3>
                <i class='bx bx-search'></i>
                <i class='bx bx-filter'></i>

                <div class="table-data">
                <div class="chart-container">
                    <h4>Tea Leaves Collections Chart(Monthly)</h4>
                    <canvas id="teaLeavesChart"></canvas>
                </div>
                </div>
            </div>
            <?php if(isset($data['pending_requests']) && !empty($data['pending_requests'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Date</th>
                            <th>Quantity (kg)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['pending_requests'] as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo $request['date']; ?></td>
                                <td><?php echo $request['quantity']; ?></td>
                                <td><?php echo $request['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div>No pending requests</div>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/Supplier/v_notifications/" >
                <button class="button">Collection Notifications</button>
            </a>
        </div>
        <div class="order">
            <div class="head">
                <h5>Tea Leaves</h5>
                <i class='bx bx-plus'></i>
                <i class='bx bx-filter'></i>
            </div>
            <canvas id="teaLeavesCollectionChart" width="300" height="200"></canvas>
        </div>
        <div class="todo">
            <div class="head">
                <h3>Scheduled Collection Dates</h3>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h4>Tea Leaves Collections</h4>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th id="table-head">Date</th>
                                <th id="table-head">Time</th>
                                <th id="table-head">Order ID</th>
                                <th id="table-head">Amount in kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tomorrow</td>
                                <td>08:00am</td>
                                <td>11</td>
                                <td>20</td>
                            </tr>
                            <tr>
                                <td>2024/11/05</td>
                                <td>09:00am</td>
                                <td>10</td>
                                <td>15</td>
                            </tr>
                            <tr>
                                <td>2024/10/12</td>
                                <td>08:00am</td>
                                <td>9</td>
                                <td>30</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <a href="<?php echo URLROOT; ?>/Supplier/cancel_pickup/" >
                <button class="button">Cancel Pickup</button>
            </a>

        </div>
    </div>

</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- TEA ORDER CHART -->
        <script>
        const ctx = document.getElementById('teaLeavesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    data: [280, 250, 180, 120, 100, 210],
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('teaLeavesCollectionChart').getContext('2d');
                var teaLeavesCollectionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June'], 
                        datasets: [{
                            label: 'Tea Leaves Collections',
                            data: [280, 250, 180, 120, 100, 210], // Example data 
                            fill: false,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Tea Leaves Collections (Monthly)'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Amount'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
        <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    
