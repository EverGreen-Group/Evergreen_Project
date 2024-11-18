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
                            <h3>12</h3>
                            <p>New Complaints</p>
                            <small>+3 from yesterday</small>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-loader-circle'></i>
                        <span class="text">
                            <h3>8</h3>
                            <p>In Progress</p>
                            <small>Pending Resolution</small>
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

                    <!-- Supplier Incompetence Reports -->
                    <div class="order" style="flex: 1;">
                        <div class="head">
                            <h3>Supplier Performance Issues</h3>
                        </div>
                        <div class="performance-list">
                            <div class="performance-item">
                                <div class="supplier-details">
                                    <span class="supplier-id">SUP27429</span>
                                    <span class="supplier-name">John Doe</span>
                                </div>
                                <div class="issue-count critical">5 complaints</div>
                                <div class="issue-types">Quality Issues, Late Delivery</div>
                            </div>
                            <div class="performance-item">
                                <div class="supplier-details">
                                    <span class="supplier-id">SUP13445</span>
                                    <span class="supplier-name">Sarah Smith</span>
                                </div>
                                <div class="issue-count high">3 complaints</div>
                                <div class="issue-types">Quantity Discrepancy</div>
                            </div>
                            <!-- Add more performance items as needed -->
                        </div>
                    </div>
                </div>

                <!-- Complaints Table -->
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Recent Complaints</h3>
                            <div class="head-actions">
                                <div class="search-box">
                                    <i class='bx bx-search'></i>
                                    <input type="text" placeholder="Search complaints...">
                                </div>
                                <select class="filter-select">
                                    <option value="all">All Status</option>
                                    <option value="new">New</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                </select>
                            </div>
                        </div>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Complaint ID</th>
                                    <th>Supplier</th>
                                    <th>Category</th>
                                    <th>Subject</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#CM2024-001</td>
                                    <td>
                                        <div class="supplier-info">
                                            <span class="id">SUP27429</span>
                                            <span class="name">John Doe</span>
                                        </div>
                                    </td>
                                    <td>Payment Delay</td>
                                    <td>Late payment for February delivery</td>
                                    <td>
                                        <div class="time-info">
                                            <span class="date">Mar 15, 2024</span>
                                            <span class="time">08:30 AM</span>
                                        </div>
                                    </td>
                                    <td><span class="status new">New</span></td>
                                    <td><span class="priority high">High</span></td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn-action" title="View Details">
                                                <i class='bx bx-show'></i>
                                            </button>
                                            <button class="btn-action" title="Assign">
                                                <i class='bx bx-user-plus'></i>
                                            </button>
                                            <button class="btn-action" title="Update Status">
                                                <i class='bx bx-revision'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- More complaint rows... -->
                            </tbody>
                        </table>

                        <div class="pagination">
                            <button class="btn-page"><i class='bx bx-chevron-left'></i></button>
                            <button class="btn-page active">1</button>
                            <button class="btn-page">2</button>
                            <button class="btn-page">3</button>
                            <button class="btn-page"><i class='bx bx-chevron-right'></i></button>
                        </div>
                    </div>
                </div>
            </main>
            </div>
        </section>
        <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
        <script>
        // Initialize the complaints type chart
        const ctx = document.getElementById('complaintTypesChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    'Quality Issues',
                    'Late Delivery',
                    'Quantity Discrepancy',
                    'Communication Issues',
                    'Payment Disputes'
                ],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        'var(--main)',      // Primary color for most critical
                        '#FF6B6B',          // Red for second most critical
                        '#4ECDC4',          // Teal
                        '#45B7D1',          // Blue
                        '#96A5C8'           // Grey
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
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${percentage}% (${value} complaints)`;
                            }
                        }
                    }
                }
            }
        });
        </script>
        <style>
        .chart-container {
            height: 300px;
            padding: 1rem;
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
        </style>
    </body>
</html>
    