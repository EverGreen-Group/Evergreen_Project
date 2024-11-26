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
                    <h5>Tea Leaves Collections Chart(Last 6 Months)</h5>
                    <canvas id="teaLeavesChart"></canvas>
                </div>
            </div>
        </div>


            <?php //if(isset($data['pending_requests']) && !empty($data['pending_requests'])): ?>
            <!--    <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Date</th>
                            <th>Quantity (kg)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php //foreach($data['pending_requests'] as $request): ?>
                            <tr>
                                <td><?php //echo $request['id']; ?></td>
                                <td><?php //echo $request['date']; ?></td>
                                <td><?php //echo $request['quantity']; ?></td>
                                <td><?php //echo $request['status']; ?></td>
                            </tr> 
                        <?php //endforeach; ?>
                    </tbody>
                </table> -->
            <?php //else: ?>
            <!--    <div>No pending requests</div> -->
            <?php //endif; ?>

        </div>
        <div class="order">
            <div class="head">
                <h5>Tea Leaves(This year)</h5>
                <i class='bx bx-plus'></i>
                <i class='bx bx-filter'></i>
            </div>
            <canvas id="teaLeavesCollectionChart" width="300" height="200"></canvas>
        </div>
        <div class="todo">
            <div class="head">
                <h3>Tea Leaves Collection Details</h3>
            </div>
            <div class="todo">
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h4>Schedule</h4>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th id="table-head">Collection ID</th>
                                    <th id="table-head">Date</th>
                                    <th id="table-head">Time</th>
                                        <th id="table-head">Team No</th>
                                    <th id="table-head">Amount in kg</th>
                                    <th id="table-head">Status</th>
                                    <th id="table-head">Confirmation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>41</td>
                                    <td>Tomorrow</td>
                                    <td>08:00am</td>
                                    <td>45</td>
                                    <td>Pending</td>
                                    <td>Pending</td>
                                    <td><button class="pending-btn">Pending</button></td>
                                </tr>
                                <tr>
                                    <td>40</td>
                                    <td>2024/11/05</td>
                                    <td>09:00am</td>
                                    <td>21</td>
                                    <td>20</td>
                                    <td>Delivered</td>
                                    <td><button class="accept-btn">Sent</button></td>
                                </tr>
                                <tr>
                                    <td>39</td>
                                    <td>2024/10/12</td>
                                    <td>08:00am</td>
                                    <td>35</td>
                                    <td>20</td>
                                    <td>Delivered</td>
                                    <td><button class="accept-btn">Sent</button></td>
                                </tr>
                                <tr>
                                    <td>38</td>
                                    <td>2024/10/07</td>
                                    <td>09:00am</td>
                                    <td>21</td>
                                    <td>26</td>
                                    <td>Delivered</td>
                                    <td><button class="accept-btn">Sent</button></td>
                                </tr>
                            </tbody>
                        </table>
                    <a href="<?php echo URLROOT; ?>/Supplier/cancelpickup/" >
                        <button class="button">Cancel Pickup</button>
                    </a>
                    </div>
                </div>
                    

            <div class="todo">
            <div class="head">
                <div class="todo">
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h4>Cancelled Pickups</h4>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th id="table-head">Collection ID</th>
                                    <th id="table-head">Date</th>
                                    <th id="table-head">Time</th>
                                    <th id="table-head">Team No</th>
                                    <th id="table-head">Confirmation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>2024/11/05</td>
                                    <td>08:00am</td>
                                    <td>36</td>
                                    <td><button class="btn-edit btn-primary">accepted</button></td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>2024/09/15</td>
                                    <td>09:00am</td>
                                    <td>31</td>
                                    <td><button class="btn-edit btn-primary">accepted</button></td>
                                </tr>
                                <tr>
                                    <td>05</td>
                                    <td>2024/08/12</td>
                                    <td>09:00am</td>
                                    <td>12</td>
                                    <td><button class="btn-edit btn-primary">accepted</button></td>
                                </tr>
                                <tr>
                                    <td>06</td>
                                    <td>2024/05/27</td>
                                    <td>10:00am</td>
                                    <td>11</td>
                                    <td><button class="btn-edit btn-primary">accepted</button></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>2024/05/01</td>
                                    <td>09:00am</td>
                                    <td>12</td>
                                    <td><button class="btn-delete btn-primary">rejected</button></td>
                                </tr>
                                <tr>
                                    <td>19</td>
                                    <td>2024/03/23</td>
                                    <td>10:00am</td>
                                    <td>11</td>
                                    <td><button class="btn-edit btn-primary">accepted</button></td>
                                </tr>
                            </tbody>
                        </table>
                
                    <a href="<?php echo URLROOT; ?>/Supplier/notifications/" >
                        <button class="button">Collection Notifications</button>
                    </a>
            </div>
            <div class="order">
                <div class="head">
                    <h3>Land Inspection Request Form</h3>
                    <p>Please fill in the form below to request a land inspection.</p>
                </div>
                <form action="submit_complaint.php" method="post" class="complaint-form">
                    <div class="form-group">
                        <label>Land Type:</label>
                        <select id="land-type" name="land_type" required>
                            <option value="tea">Tea</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea id="description" name="description" rows="5" placeholder="Enter Description" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="phone" name="phone" placeholder="Enter Phone Number">
                    </div>
                    <button type="submit" class="button" onclick="submitmessage()">Submit</button>
                    <button type="button" class="button" onclick="refreshPage()">Cancel</button>
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
                    data: [280, 250, 180, 120, 100, 210, 180, 120, 90, 200, 320, 0], // Example data 
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
</body>
</html>
    
