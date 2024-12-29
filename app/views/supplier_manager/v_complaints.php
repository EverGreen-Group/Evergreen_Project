<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Complaints Management</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Complaints</a></li>
                    </ul>
                </div>
                <div class="head-actions">
                    <button class="btn-download" onclick="exportComplaints()">
                        <i class='bx bx-download'></i> Export Report
                    </button>
                </div>
            </div>

            <!-- Box Info -->
            <ul class="box-info">
                <li>
                    <i class='bx bx-message-error'></i>
                    <span class="text">
                        <h3><?php echo $data['unviewed_count']; ?></h3>
                        <p>New Complaints</p>
                        <small>+<?php echo $data['new_last_week']; ?> from last week</small>
                    </span>
                </li>
                <li>
                    <i class='bx bx-loader-circle'></i>
                    <span class="text">
                        <h3><?php echo $data['viewed_count']; ?></h3>
                        <p>In Progress</p>
                        <small>Viewed Complaints</small>
                    </span>
                </li>
                <li>
                    <i class='bx bx-check-circle'></i>
                    <span class="text">
                        <h3>85%</h3>
                        <p>Resolution Rate</p>
                        <small>4h Avg. Response Time</small>
                    </span>
                </li>
            </ul>

            <!-- Complaints Analytics -->
            <div class="table-data">
                <!-- Complaint Types Chart -->
                <div class="order" style="flex: 1;">
                    <div class="head">
                        <h3>Types of Complaints</h3>
                        <select class="period-select">
                            <option value="this-month">This Month</option>
                            <option value="last-month">Last Month</option>
                            <option value="last-3-months">Last 3 Months</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="complaintTypesChart"></canvas>
                    </div>
                </div>

                <!-- Missed Collection -->
                <div class="order" style="flex: 1;">
                    <div class="head">
                        <h3>Missed Collections</h3>
                    </div>
                    <div class="table-data">
                        <table>
                            <thead>
                                <tr>
                                    <th>Supplier ID</th>
                                    <th>Supplier Name</th>
                                    <th>Missed Date</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>SUP27429</td>
                                    <td>John Doe</td>
                                    <td>2024-03-01</td>
                                    <td>Late Arrival</td>
                                </tr>
                                <tr>
                                    <td>SUP13445</td>
                                    <td>Sarah Smith</td>
                                    <td>2024-03-02</td>
                                    <td>Vehicle Breakdown</td>
                                </tr>
                                <tr>
                                    <td>SUP98765</td>
                                    <td>Michael Brown</td>
                                    <td>2024-03-03</td>
                                    <td>Weather Conditions</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Complaints Table -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Recent Complaints</h3>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Complaint ID</th>
                                <th>Supplier ID</th>
                                <th>Category</th>
                                <th>Subject</th>
                                <th>Submitted Date</th>
                                <th>Submitted Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($data['complaints'])): 
                                foreach ($data['complaints'] as $complaint): 
                                    // Debug output (remove in production)
                                    error_log("Processing complaint: " . print_r($complaint, true));
                            ?>
                                <tr class="complaint-row" id="row-<?php echo htmlspecialchars($complaint->complaint_id); ?>">
                                    <td><?php echo htmlspecialchars($complaint->complaint_id); ?></td>
                                    <td><?php echo htmlspecialchars($complaint->supplier_id); ?></td>
                                    <td><?php echo htmlspecialchars($complaint->complaint_type); ?></td>
                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                    <td><?php echo htmlspecialchars($complaint->submitted_date); ?></td>
                                    <td><?php echo htmlspecialchars($complaint->submitted_time); ?></td>
                                    <td>
                                        <button class="btn-action view-complaint" 
                                                data-complaint-id="<?php echo $complaint->complaint_id; ?>"
                                                data-viewed="<?php echo $complaint->viewed; ?>">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                endforeach; 
                            else: 
                                // Debug output (remove in production)
                                error_log("No complaints found in view data");
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center">No complaints found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </section>
        <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize complaint types chart
                const ctx = document.getElementById('complaintTypesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode(array_column($data['complaint_types'], 'complaint_type')); ?>,
                        datasets: [{
                            data: <?php echo json_encode(array_column($data['complaint_types'], 'count')); ?>,
                            backgroundColor: [
                                '#86E211',
                                '#FF6B6B',
                                '#4ECDC4',
                                '#45B7D1',
                                '#96A5C8'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    font: { size: 12 }
                                }
                            }
                        }
                    }
                });

            // Handle view button clicks
            document.querySelectorAll('.view-complaint').forEach(button => {
                button.addEventListener('click', function() {
                    const complaintId = this.dataset.complaintId;
                    const descriptionRow = document.getElementById(`description-${complaintId}`);
                    
                    if (descriptionRow.style.display === 'none') {
                        descriptionRow.style.display = 'table-row';
                        
                        // If complaint hasn't been viewed yet
                        if (this.dataset.viewed === '0') {
                            fetch('<?php echo URLROOT; ?>/suppliermanager/markComplaintViewed', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `complaint_id=${complaintId}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update unviewed count (decrease)
                                    const unviewedCount = document.querySelector('.box-info li:first-child h3');
                                    unviewedCount.textContent = parseInt(unviewedCount.textContent) - 1;
                                    
                                    // Update viewed count (increase)
                                    const viewedCount = document.querySelector('.box-info li:nth-child(2) h3');
                                    viewedCount.textContent = parseInt(viewedCount.textContent) + 1;
                                    
                                    this.dataset.viewed = '1';
                                }
                            });
                        }
                    } else {
                        descriptionRow.style.display = 'none';
                    }
                });
            });
        });
        </script>
        <style>
            .chart-container {
                height: 300px;
                padding: 1rem;
                position: relative;
            }

            .performance-list {
                padding: 1rem;
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .performance-item {
                background: #f8f9fa;
                padding: 1rem;
                border-radius: 8px;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .supplier-details {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .supplier-id {
                color: #666;
                font-size: 0.9rem;
            }

            .supplier-name {
                font-weight: 500;
            }

            .issue-count {
                font-weight: 600;
                padding: 0.2rem 0.5rem;
                border-radius: 4px;
                display: inline-block;
                width: fit-content;
            }

            .issue-count.critical {
                background-color: #ffe5e5;
                color: #d32f2f;
            }

            .issue-count.high {
                background-color: #fff3e0;
                color: #ef6c00;
            }

            .issue-types {
                color: #666;
                font-size: 0.9rem;
            }

            .period-select {
                padding: 0.5rem;
                border: 1px solid var(--grey);
                border-radius: 4px;
                outline: none;
            }

            .status {
                padding: 0.2rem 0.5rem;
                border-radius: 4px;
                font-weight: bold;
            }

            .status.action-required {
                background-color: #ffe5e5; /* Light red */
                color: #d32f2f; /* Dark red */
            }

            .status.resolved {
                background-color: #d4edda; /* Light green */
                color: #155724; /* Dark green */
            }

            .table-data {
                margin-top: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 12px 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f4f4f4;
                color: #333;
            }

            tbody tr:hover {
                background-color: #f1f1f1; /* Light grey on hover */
            }

            .status {
                padding: 0.2rem 0.5rem;
                border-radius: 4px;
                font-weight: bold;
            }

            .status.new {
                background-color: #e7f3fe; /* Light blue */
                color: #31708f; /* Dark blue */
            }

            .status.in-progress {
                background-color: #fff3cd; /* Light yellow */
                color: #856404; /* Dark yellow */
            }

            .status.resolved {
                background-color: #d4edda; /* Light green */
                color: #155724; /* Dark green */
            }

            .priority {
                padding: 0.2rem 0.5rem;
                border-radius: 4px;
                font-weight: bold;
            }

            .priority.high {
                background-color: #f8d7da; /* Light red */
                color: #721c24; /* Dark red */
            }

            .priority.medium {
                background-color: #fff3cd; /* Light yellow */
                color: #856404; /* Dark yellow */
            }

            .priority.low {
                background-color: #d4edda; /* Light green */
                color: #155724; /* Dark green */
            }

            .search-box {
                display: flex;
                align-items: center;
                border: 1px solid var(--grey);
                border-radius: 4px;
                padding: 5px;
            }

            .search-box input {
                border: none;
                outline: none;
                padding: 5px;
            }

            .filter-select {
                margin-left: 10px;
                padding: 5px;
                border: 1px solid var(--grey);
                border-radius: 4px;
            }

            .btn-action {
                background-color: #007bff; /* Bootstrap primary color */
                color: white;
                border: none;
                border-radius: 4px;
                padding: 5px 10px;
                cursor: pointer;
            }

            .btn-action:hover {
                background-color: #0056b3; /* Darker shade on hover */
            }
        </style>
    </body>
</html>
    