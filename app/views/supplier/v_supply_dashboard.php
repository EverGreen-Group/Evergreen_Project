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
                                <th id="table-head">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tomorrow</td>
                                <td>08:00am</td>
                                <td>11</td>
                                <td>20</td>
                                <td>Pending</td>
                            </tr>
                            <tr>
                                <td>2024/11/05</td>
                                <td>09:00am</td>
                                <td>10</td>
                                <td>20</td>
                                <td>Delivered</td>
                            </tr>
                            <tr>
                                <td>2024/10/12</td>
                                <td>08:00am</td>
                                <td>9</td>
                                <td>20</td>
                                <td>Delivered</td>
                            </tr>
                            <tr>
                                <td>2024/10/07</td>
                                <td>09:00am</td>
                                <td>12</td>
                                <td>26</td>
                                <td>Delivered</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <a href="<?php echo URLROOT; ?>/Supplier/cancelpickup/" >
                <button class="button">Request Pickup</button>
            </a>

        </div>

    <div class="table-data">
        
        <!-- <div class="order">
            <div class="head">
                <h3>Collection over past months</h3>
                <i class='bx bx-search'></i>
                <i class='bx bx-filter'></i>

                <div class="table-data">
                <div class="chart-container">
                    <h5>Tea Leaves Collections Chart(Last 6 Months)</h5>
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
            <a href="<?php echo URLROOT; ?>/Supplier/notifications/" >
                <button class="button">Collection Notifications</button>
            </a>
        </div> -->
        <div class="order">
            <div class="head">
                <h5>Tea Leaves(This year)</h5>
                <i class='bx bx-plus'></i>
                <i class='bx bx-filter'></i>
            </div>
            <div class="chart-wrapper">
                <canvas id="teaLeavesCollectionChart"></canvas>
            </div>
        </div>
        
    </div>

</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>

        <!-- TEA ORDER CHART -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('teaLeavesChart').getContext('2d');
            const tealeavesChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        data: [210, 180, 120, 90, 200, 320, 0],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#36923B',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#9'
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
            })
        });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('teaLeavesCollectionChart').getContext('2d');
                var teaLeavesCollectionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [{
                            label: 'Tea Leaves Collections',
                            data: [280, 250, 180, 120, 100, 210, 180, 120, 90, 200, 320, 0],
                            fill: true,
                            backgroundColor: 'rgba(0, 128, 0, 0.1)',
                            borderColor: '#008000',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: '#008000',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 14
                                    },
                                    color: '#008000'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Tea Leaves Collections (Monthly)',
                                color: '#008000',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month',
                                    color: '#008000'
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#008000'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Amount (kg)',
                                    color: '#008000'
                                },
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 128, 0, 0.1)'
                                },
                                ticks: {
                                    color: '#008000'
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        }
                    }
                });
            });
        </script>
    </body>
</html>
    
