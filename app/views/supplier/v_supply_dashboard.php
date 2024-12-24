<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<?php require APPROOT . '/views/supplier/css/dashboard_style.php'; ?>

<main>
    <!-- Dashboard Header -->
    <div class="head-title">
        <div class="left">
            <h1>Supplier Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="stats-wrapper">
        <div class="stats-container">
            <!-- Your existing stats content -->
            <ul class="box-info">
                <li>
                    <i class='bx bx-box'></i>
                    <span class="text">
                        <h3><?php echo isset($data['total_collections']) ? $data['total_collections'] : '3'; ?></h3>
                        <p>Collections</p>
                        <small>this month</small>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-leaf'></i>
                    <span class="text">
                        <h3><?php echo isset($data['total_quantity']) ? $data['total_quantity'] : '120'; ?></h3>
                        <p>Tea Leaves</p>
                        <small>kg this month</small>
                    </span>
                </li>
            </ul>

            <div class="calendar-wrapper">
                <div class="calendar-container">
                    <h4>Upcoming Land Inspection</h4>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="table-data">
        <!-- Scheduled Collections Section -->
        <div class="schedule-section">
            <div class="section-header">
                <h3>Scheduled Collections</h3>
            </div>
            <?php if (isset($data['schedule']) ): ?>
                <div class="schedule-card">
                    <button class="nav-btn prev-btn">
                        <i class='bx bx-chevron-left'></i>
                    </button>

                    <div class="card-content">
                        <div class="card-header">
                            <div class="status-badge <?php echo (date('Y-m-d') === $data['schedule']['next_collection_date']) ? 'today' : 'upcoming'; ?>">
                                <?php echo (date('Y-m-d') === $data['schedule']['next_collection_date']) ? 'Today' : 'Next Collection'; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="schedule-info">
                                <div class="info-item">
                                    <i class='bx bx-calendar'></i>
                                    <span><?php echo date('F j, Y', strtotime($data['schedule']['next_collection_date'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-time-five'></i>
                                    <span><?php echo $data['schedule']['time_slot']; ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-map'></i>
                                    <span>Route: <?php echo htmlspecialchars($data['schedule']['route_name']); ?></span>
                                </div>
                            </div>
                            <div class="schedule-action">
                                <a href="<?php echo URLROOT; ?>/Supplier/scheduleDetails" class="view-details-btn">
                                    <i class='bx bx-info-circle'></i>
                                    <span>View Details</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <button class="nav-btn next-btn">
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>

                <div class="schedule-actions">
                    <select class="schedule-select">
                        <option value="" disabled selected>Select New Day</option>
                        <?php foreach ($data['schedule']['all_collection_days'] as $day): ?>
                            <option value="<?php echo strtolower($day); ?>"><?php echo ucfirst($day); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="change-schedule-btn">
                        <i class='bx bx-calendar-edit'></i>
                        <span>Request Schedule Change</span>
                    </button>
                </div>
            <?php else: ?>
                <div class="no-schedule-message">
                    <p>No collection schedule found. Please contact your route manager.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="stats-chart-container">
            <canvas id="teaCollectionChart"></canvas>
        </div> 

        <!-- Land Inspection Request Section -->
        <div class="order">
            <div class="head">
                <h4>Submit Land Inspection Request</h4>
            </div>

            <form action="<?php echo URLROOT; ?>/supplier/requestInspection" method="POST" class="request-form">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="land_area">Land Area (Acres):</label>
                            <input type="number" id="land_area" name="land_area" min="1" step="0.1" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location" required>
                        </div>

                        <div class="form-group">
                            <label for="preferred_date">Preferred Date:</label>
                            <input type="date" id="preferred_date" name="preferred_date" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="comments">Comments (Optional):</label>
                            <input type="text" id="comments" name="comments">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Submit Request</button>
                        <button type="button" class="btn-cancel" onclick="this.form.reset()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Previous Land Inspection Requests Section -->
        <div class="order">
            <div class="head">
                <h4>Previous Land Inspection Requests</h4>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Preferred Date</th>
                        <th>Scheduled Date</th>
                        <th>Scheduled Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['previous_inspections'])): ?>
                        <?php foreach ($data['previous_inspections'] as $inspection): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inspection->request_id ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($inspection->preferred_date ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($inspection->scheduled_date ?? 'Not Scheduled'); ?></td>
                                <td><?php echo htmlspecialchars($inspection->scheduled_time ?? 'Not Scheduled'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo match(strtolower($inspection->status ?? '')) {
                                        'pending' => 'pending',
                                        'completed' => 'approved',
                                        'cancelled' => 'rejected',
                                        default => 'pending'
                                    }; ?>">
                                        <?php echo htmlspecialchars($inspection->status ?? 'Pending'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No previous inspection requests found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add this after your main content but before closing </main> -->
    <div class="modal" id="scheduleDetailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Collection Details</h3>
                <button class="close-modal">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-group">
                    <h4>Collection Information</h4>
                    <div class="detail-item">
                        <span class="label">Date:</span>
                        <span class="value">Today</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Time:</span>
                        <span class="value">08:00 AM</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Order ID:</span>
                        <span class="value">#11</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Quantity:</span>
                        <span class="value">20 kg</span>
                    </div>
                </div>
                <div class="detail-group">
                    <h4>Status Updates</h4>
                    <div class="status-timeline">
                        <div class="timeline-item active">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="time">08:00 AM</p>
                                <p class="status">Collection Scheduled</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="time">Pending</p>
                                <p class="status">Collector Arrival</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="time">Pending</p>
                                <p class="status">Collection Complete</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    {
                        title: 'Event 1',
                        start: '2024-12-25',
                    },
                    {
                        title: 'Event 2',
                        start: '2024-12-30',
                    }
                ]
            });
            calendar.render();
        });
    </script>

</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<script>
    // Add this to your existing JavaScript file
    function initializeModal() {
        const modal = document.getElementById('scheduleDetailsModal');
        const viewDetailsBtn = document.querySelector('.view-details-btn');
        const closeModalBtn = document.querySelector('.close-modal');

        viewDetailsBtn.addEventListener('click', () => {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Add this to your DOMContentLoaded event
    document.addEventListener('DOMContentLoaded', function() {
        initializeScheduleCards();
        initializeModal();
    });

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('preferred_date').setAttribute('min', today);

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('teaCollectionChart').getContext('2d');
        let teaChart;

        // Function to format months
        function formatMonth(monthStr) {
            return monthStr.substring(0, 3);
        }

        // Function to show error message
        function showChartError(message) {
            const container = document.querySelector('.stats-chart-container');
            container.innerHTML = `
                <div style="text-align: center; color: #666; padding: 20px;">
                    ${message}
                </div>`;
        }

        // Fetch the data
        fetch(`${URLROOT}/Supplier/getTeaLeavesCollectionData`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data || data.length === 0) {
                    showChartError('No collection data available');
                    return;
                }

                // Create the chart
                teaChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => formatMonth(item.month)),
                        datasets: [{
                            label: 'Tea Collection',
                            data: data.map(item => parseFloat(item.quantity) || 0), // Ensure numeric values
                            borderColor: '#008000',
                            backgroundColor: 'rgba(0, 128, 0, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#008000',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#333',
                                bodyColor: '#666',
                                borderColor: '#ddd',
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return `${context.parsed.y}kg collected`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + 'kg';
                                    },
                                    color: '#666',
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    color: '#f0f0f0'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                showChartError('Failed to load chart data');
            });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (teaChart) {
                teaChart.resize();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                {
                    title: 'Land Inspection',
                    start: '<?php echo isset($data["next_inspection"]) ? $data["next_inspection"]->scheduled_date : ""; ?>',
                    className: 'inspection-date'
                }
            ],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            }
        });
        calendar.render();
    });

</script>


<style>
    .box-info {
        margin-left: 100px;
    }

    .schedule-action {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .schedule-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #008000;
        border-radius: 25px;
        background: white;
        color: #2b2b2b;
        font-size: 0.95rem;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
    }

    .schedule-select:hover {
        border-color: #006400;
        box-shadow: 0 2px 5px rgba(0, 128, 0, 0.1);
    }

    .schedule-select:focus {
        border-color: #006400;
        box-shadow: 0 2px 5px rgba(0, 128, 0, 0.1);
    }

    .stats-container {
        display: flex;
        justify-content: space-between;
        align-items: center; /* Align items vertically */
        background-color: #f8f9fa; 
        border: 1px solid #ddd; 
        padding: 20px; 
        margin: 10px; 
        border-radius: 20px; 
        width: 45%; 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    }

    .stats-container:nth-child(odd) {
        margin-right: 5%; /* Space between odd and even elements */
    }

    .stat-item {
        text-align: center; 
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 10px; /* Space between icon and text */
        font-size: 1.2em; /* Slightly larger font */
        color: #333; /* Darker font color */
    }

    .stat-value {
        margin-top: 10px;
        font-size: 1.5em; /* Increase size of the value */
        color: #555; /* Softer font color */
    }

    .stat-value small {
        display: block;
        font-size: 0.5em;
        color: #777;
    }

    .stat-divider {
        display: none; /* Hides the divider */
    }

    .stats-wrapper {
        display: flex;
        flex-wrap: wrap; /* Ensure wrapping on smaller screens */
        gap: 10px; /* Gap between rows or boxes */
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .stats-container {
            flex-direction: column; /* Stack items vertically on small screens */
        }

        .stat-item {
            flex: 1 1 100%; /* Make each item take full width */
        }
    }

    .stats-wrapper {
        display: flex;
        gap: 20px;
        margin: 20px 0;
        width: 100%;
    }

    .stats-container {
        flex: 1;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        min-width: 250px;
    }

    .stats-chart-container {
        flex: 2;
        background: white;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        height: 300px;
        min-width: 300px;
    }

    .stat-item {
        margin-bottom: 20px;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2em;
        color: #333;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 1.5em;
        color: #555;
    }

    small{
        color: #777;
    }

    .stat-value small {
        display: block;
        font-size: 0.5em;
        color: #777;
    }

    @media (max-width: 1024px) {
        .stats-wrapper {
            flex-direction: column;
        }
        
        .stats-container,
        .stats-chart-container {
            width: 100%;
        }
        
        .stats-chart-container {
            height: 250px;
        }
    }

    @media (max-width: 768px) {
        .stats-wrapper {
            margin: 10px 0;
        }
        
        .stats-container,
        .stats-chart-container {
            padding: 15px;
        }
    }
    .calendar-wrapper {
        margin-top: 20px;
        margin-right: 100px;

    }

    .calendar-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .inspection-date {
        background-color: pink !important;
        color: #333 !important;
        font-weight: bold !important;
    }

    .inspection-details {
        font-size: small;
    }
    .request-form {
        background: white;
        border-radius: 10px;
        padding: 15px;
    }

    .form-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-size: 0.9rem;
        color: #666;
    }

    .form-group select,
    .form-group input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.9rem;
        width: 100%;
    }

    .form-group input[readonly] {
        background-color: #f5f5f5;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-submit,
    .btn-cancel {
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        flex: 1;
        transition: all 0.3s ease;
    }

    .btn-submit {
        background: #008000;
        color: white;
    }

    .btn-submit:hover {
        background: #006400;
    }

    .btn-cancel {
        background: #f5f5f5;
        color: #666;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    @media screen and (max-width: 360px) {
        .request-form {
            padding: 10px;
        }

        .form-row {
            flex-direction: column;
            gap: 10px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
        }
    }
</style>
