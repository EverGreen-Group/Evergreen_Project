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


    <div class="stats-container">

        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bxs-calendar-check'></i>
                <span>Collections</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['total_collections']) ? $data['total_collections'] : '3'; ?>
                <small>this month</small>
            </div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="stat-header">
                <i class='bx bx-leaf'></i>
                <span>Tea Leaves</span>
            </div>
            <div class="stat-value">
                <?php echo isset($data['total_quantity']) ? $data['total_quantity'] : '120'; ?>
                <small>kg this month</small>
            </div>
        </div>
    </div>

    <div class="table-data order head">

        <!-- Current Set Date Card -->
        <div class="schedule-section">
            
            <div class="schedule-card current-schedule">
                <div class="card-content">
                    <div class="schedule-info">
                        <div class="stats-container">
                            <div class="info-item">
                                <i class='bx bx-calendar'></i>
                                <span>Every Monday</span>
                            </div>
                            <div class="info-item">
                                <i class='bx bx-time-five'></i>
                                <span>08:00 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="schedule-action">
                        <select class="schedule-select">
                            <option value="" disabled selected>Select New Day</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                        </select>
                        <button class="change-schedule-btn">
                            <i class='bx bx-calendar-edit'></i>
                            <span>Request Schedule Change</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- Schedule Section -->
            <div class="schedule-section">
                <div class="section-header">
                    <h3>Scheduled Collections</h3>
                
                <div class="schedule-card">
                    <button class="nav-btn prev-btn">
                        <i class='bx bx-chevron-left'></i>
                    </button>

                    <div class="card-content">
                        <div class="card-header">
                            <div class="status-badge today">Today</div>
                        </div>
                        <div class="card-body">
                            <div class="schedule-info">
                                <div class="info-item">
                                    <i class='bx bx-calendar'></i>
                                    <span>Today</span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-time-five'></i>
                                    <span>08:00 AM</span>
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

                <div class="card-navigation">
                    <span class="current-card">1</span>
                    <span>/</span>
                    <span class="total-cards">4</span>
                </div>
            </div>

            <div class="section-divider"></div>
        </div>
    </div>


</main>
<script>

</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

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
</script>

<style>
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

    /* Add this CSS to your stylesheet */
    .stats-container {
        display: flex; /* Use flexbox for layout */
        flex-wrap: wrap; /* Allow items to wrap to the next line */
        justify-content: space-between; /* Space items evenly */
        margin: 0 auto; /* Center the container */
        padding: 10px; /* Add some padding */
    }

    .stat-item {
        flex: 1 1 45%; /* Allow items to grow and shrink, with a base width of 45% */
        box-sizing: border-box; /* Include padding and border in the element's total width and height */
        margin: 10px; /* Add margin for spacing */
        min-width: 200px; /* Set a minimum width for smaller screens */
    }

    .stat-header {
        display: flex; /* Use flexbox for header layout */
        align-items: center; /* Center items vertically */
    }

    .stat-value {
        font-size: 1.5em; /* Adjust font size for better visibility */
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
</style>
