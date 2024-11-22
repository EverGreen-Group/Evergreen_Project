<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_driving_partner.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Driving Partner Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li>Overview</li>
            </ul>
        </div>
    </div>

    <!-- Quick Stats -->
    <ul class="route-box-info">
        <li>
            <i class='bx bxs-time'></i>
            <span class="text">
                <p>Current Time</p>
                <h3 id="current-time">--:--</h3>
                <span>Local Time</span>
            </span>
        </li>
        <li>
            <i class='bx bxs-timer'></i>
            <span class="text">
                <p>Next Collection</p>
                <h3>2:30 PM</h3>
                <span>Hatton Central</span>
            </span>
        </li>
        <li>
            <i class='bx bxs-calendar'></i>
            <span class="text">
                <p>Today's Progress</p>
                <h3>4/6</h3>
                <span>Collections</span>
            </span>
        </li>
    </ul>

    <!-- Shift Management Section -->
    <div class="shift-content">
        <section class="upcoming-shifts">
            <h2>Upcoming Shifts</h2>
            <?php if (isset($data['error'])): ?>
                <div class="alert alert-warning"><?php echo $data['error']; ?></div>
            <?php else: ?>
                <!-- Table view (shows above 600px) -->
                <table class="shift-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th class="hide-mobile">Team</th>
                            <th class="hide-mobile">Countdown</th>
                            <th class="hide-mobile">Status</th>
                            <th><i class='bx bx-dots-vertical-rounded'></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($data['upcomingShifts'])):
                            // Prepare sorted shifts array
                            $sortedShifts = [];
                            foreach ($data['upcomingShifts'] as $shift) {
                                $days = explode(',', $shift->days_of_week);
                                foreach ($days as $day) {
                                    $currentTime = time();
                                    
                                    // Get timestamp for this week's occurrence of the day
                                    $thisWeekDate = date('Y-m-d', strtotime($day . ' this week'));
                                    $thisWeekDateTime = $thisWeekDate . ' ' . $shift->start_time;
                                    $thisWeekEndDateTime = $thisWeekDate . ' ' . $shift->end_time;
                                    $thisWeekTimestamp = strtotime($thisWeekDateTime);
                                    $thisWeekEndTimestamp = strtotime($thisWeekEndDateTime);
                                    
                                    // Get timestamp for next week's occurrence
                                    $nextWeekDate = date('Y-m-d', strtotime($day . ' next week'));
                                    $nextWeekDateTime = $nextWeekDate . ' ' . $shift->start_time;
                                    $nextWeekEndDateTime = $nextWeekDate . ' ' . $shift->end_time;
                                    $nextWeekTimestamp = strtotime($nextWeekDateTime);
                                    $nextWeekEndTimestamp = strtotime($nextWeekEndDateTime);
                                    
                                    // Check if the shift is currently ongoing
                                    $isOngoing = ($currentTime >= $thisWeekTimestamp && $currentTime <= $thisWeekEndTimestamp);
                                    
                                    // If this week's shift hasn't ended yet, use this week's times
                                    if ($currentTime <= $thisWeekEndTimestamp) {
                                        $sortedShifts[] = [
                                            'day' => ucfirst($day),
                                            'startDateTime' => $thisWeekDateTime,
                                            'endDateTime' => $thisWeekEndDateTime,
                                            'timestamp' => $thisWeekTimestamp,
                                            'isOngoing' => $isOngoing,
                                            'shift' => $shift
                                        ];
                                    } else {
                                        // If this week's shift has ended, use next week's times
                                        $sortedShifts[] = [
                                            'day' => ucfirst($day),
                                            'startDateTime' => $nextWeekDateTime,
                                            'endDateTime' => $nextWeekEndDateTime,
                                            'timestamp' => $nextWeekTimestamp,
                                            'isOngoing' => false,
                                            'shift' => $shift
                                        ];
                                    }
                                }
                            }

                            usort($sortedShifts, function($a, $b) {
                                return $a['timestamp'] - $b['timestamp'];
                            });

                            foreach ($sortedShifts as $sortedShift):
                                $shift = $sortedShift['shift'];
                        ?>
                            <tr>
                                <td><?php echo $sortedShift['day']; ?></td>
                                <td><?php echo $shift->start_time; ?></td>
                                <td class="hide-mobile"><?php echo $shift->team_name; ?></td>
                                <td class="hide-mobile">
                                    <span class="countdown" data-start="<?php echo $sortedShift['startDateTime']; ?>">
                                        Calculating...
                                    </span>
                                </td>
                                <td class="hide-mobile"><span class="status-active">Active</span></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivingpartner/scheduleDetails/<?php echo $shift->schedule_id; ?>" 
                                       class="btn-icon">
                                        <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <tr>
                                <td colspan="6" class="text-center">No shifts available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Card view (shows below 600px) -->
                <div class="shifts-card-container">
                    <?php 
                    if (!empty($sortedShifts)):
                        foreach ($sortedShifts as $sortedShift):
                            $shift = $sortedShift['shift'];
                    ?>
                        <div class="shift-card">
                            <div class="shift-card-header">
                                <span class="shift-card-day"><?php echo $sortedShift['day']; ?></span>
                                <span class="shift-card-time"><?php echo $shift->start_time; ?></span>
                            </div>
                            <div class="shift-card-details">
                                <div class="shift-card-team">
                                    <i class='bx bxs-group'></i>
                                    <span><?php echo $shift->team_name; ?></span>
                                </div>
                                <div class="shift-card-countdown">
                                    <span class="countdown" data-start="<?php echo $sortedShift['startDateTime']; ?>">
                                        Calculating...
                                    </span>
                                </div>
                            </div>
                            <div class="shift-card-footer">
                                <span class="shift-card-status">Active</span>
                                <a href="<?php echo URLROOT; ?>/drivingpartner/scheduleDetails/<?php echo $shift->schedule_id; ?>" 
                                   class="btn-icon">
                                    <i class='bx bx-right-arrow-alt'></i>
                                </a>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <div class="shift-card">
                            <p class="text-center">No shifts available</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>


        <?php
        // Format shifts for the calendar
        $calendarShifts = [];
        if (!empty($data['upcomingShifts'])) {
            foreach ($data['upcomingShifts'] as $shift) {
                $days = explode(',', $shift->days_of_week);
                foreach ($days as $day) {
                    $nextDate = date('Y-m-d', strtotime("next $day"));
                    if (!isset($calendarShifts[$nextDate])) {
                        $calendarShifts[$nextDate] = [];
                    }
                    $calendarShifts[$nextDate][] = [
                        'start_time' => $shift->start_time,
                        'end_time' => $shift->end_time,
                        'location' => $shift->team_name,
                    ];
                }
            }
        }
        ?>

        <section class="shift-calendar">
            <h2>Shift Calendar</h2>
            <div id="shift-calendar">
                <?php 
                $data['shifts'] = $calendarShifts;
                require APPROOT . '/views/inc/components/calendar.php'; 
                ?>
            </div>
        </section>
    </div>
</main>

<script>
// Update current time
function updateCurrentTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('current-time').textContent = `${hours}:${minutes}`;
}

// Improved countdown function
function updateCountdowns() {
    document.querySelectorAll('.countdown').forEach(function(element) {
        const startTime = new Date(element.dataset.start).getTime();
        const now = new Date().getTime();
        const distance = startTime - now;

        if (distance < 0) {
            element.innerHTML = "Started";
            return;
        }

        // Calculate time units
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Format the display based on the remaining time
        let displayText = '';
        if (days > 0) {
            displayText = `${days}d ${hours}h`;
        } else if (hours > 0) {
            displayText = `${hours}h ${minutes}m`;
        } else if (minutes > 0) {
            displayText = `${minutes}m ${seconds}s`;
        } else {
            displayText = `${seconds}s`;
        }

        element.innerHTML = displayText;
    });
}

// Initialize and set intervals
setInterval(updateCurrentTime, 1000);
setInterval(updateCountdowns, 1000);
updateCurrentTime();
updateCountdowns();
</script>

<style>
.route-box-info {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-top: 24px;
    margin-bottom: 24px;
    list-style: none;
    padding: 0;
    flex-wrap: wrap;
}

.route-box-info li {
    flex: 1;
    min-width: 100%;
    background: var(--light);
    border-radius: 20px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 8px;
}

.route-box-info li i {
    font-size: 24px;
    color: var(--main);
    background: var(--light-main);
    border-radius: 10%;
    padding: 12px;
}

.route-box-info li .text h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.route-box-info li .text p {
    font-size: 14px;
    color: var(--dark-grey);
    margin: 0;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

th {
    font-weight: 600;
    color: var(--dark-grey);
}

.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
}

.status.completed {
    background: var(--light-success);
    color: var(--success);
}

.status.pending {
    background: var(--light-warning);
    color: var(--warning);
}

/* Add shift management styles from v_shift.php */
.shift-management-main {
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
}

.shift-header {
    margin-bottom: 2rem;
}

.shift-header h1 {
    font-size: 2.25rem;
    font-weight: 600;
    color: #2c3e50;
}

.shift-content {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-top: 24px;
}

.shift-content section {
    background-color: #fff;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.shift-content h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.shift-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.shift-table th,
.shift-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.shift-table th {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #2c3e50;
}

.status-pending,
.status-accepted,
.status-active {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-accepted,
.status-active {
    background-color: #d4edda;
    color: #155724;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
    transition: background-color 0.3s, transform 0.1s;
    border: none;
    cursor: pointer;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.btn-primary {
    background-color: #007664;
    color: #fff;
}

.btn-primary:hover {
    background-color: #005a4d;
}

.btn-secondary {
    background-color: #F06E6E;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #e85c5c;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

#shift-calendar {
    height: 400px;
    max-width: 100%;
    overflow-x: auto;
}

.countdown {
    font-family: monospace;
    font-weight: bold;
    color: #2c3e50;
}

/* Additional Calendar Styles */
.fc-event {
    border: none !important;
    padding: 3px !important;
    margin: 2px !important;
}

.fc-content {
    padding: 4px;
}

.fc-title {
    font-weight: bold;
    font-size: 0.9em;
    margin-bottom: 2px;
}

.fc-location {
    font-size: 0.8em;
    color: #666;
    margin-bottom: 2px;
}

.fc-time {
    font-size: 0.8em;
    color: #444;
}

.fc-day-today {
    background: #f8f9fa !important;
}

.fc-button-primary {
    background-color: #007664 !important;
    border-color: #005a4d !important;
}

.fc-button-primary:hover {
    background-color: #005a4d !important;
    border-color: #004a3f !important;
}

.fc-button-primary:disabled {
    background-color: #339989 !important;
    border-color: #2d8579 !important;
}

/* Responsive typography */
@media screen and (max-width: 768px) {
    .head-title h1 {
        font-size: 1.25rem;
    }

    .shift-content h2 {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }

    .route-box-info li i {
        font-size: 24px;
        padding: 12px;
    }

    .route-box-info li .text h3 {
        font-size: 18px;
    }

    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }

    .shift-table th,
    .shift-table td,
    .table-data th,
    .table-data td {
        padding: 8px;
        font-size: 0.9rem;
    }

    .status, .status-active {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
}

/* Table responsiveness */
@media screen and (max-width: 640px) {
    .shift-table, .table-data table {
        display: block;
    }

    .shift-table th, 
    .shift-table td,
    .table-data th,
    .table-data td {
        min-width: 120px;
    }

    /* Hide status and countdown columns */
    .shift-table th:nth-child(4),
    .shift-table td:nth-child(4),
    .shift-table th:nth-child(5),
    .shift-table td:nth-child(5),
    .table-data th:nth-child(5),
    .table-data td:nth-child(5) {
        display: none;
    }
}

@media screen and (max-width: 480px) {
    /* Hide team column as well */
    .shift-table th:nth-child(3),
    .shift-table td:nth-child(3),
    .table-data th:nth-child(4),
    .table-data td:nth-child(4) {
        display: none;
    }
}

/* For very small screens, ensure only essential columns are visible */
@media screen and (max-width: 412px) {
    main {
        padding: 12px;
    }

    .shift-content section {
        padding: 12px;
        width: 100%;
        overflow-x: hidden;
    }

    .shift-table {
        width: 100%;
        overflow-x: visible;
    }

    .shift-table th, 
    .shift-table td {
        padding: 8px 4px;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .shift-table th:first-child,
    .shift-table td:first-child {
        width: 30%;
    }

    .shift-table th:nth-child(2),
    .shift-table td:nth-child(2) {
        width: 50%;
    }

    .shift-table th:last-child,
    .shift-table td:last-child {
        width: 20%;
        position: relative;
        padding-right: 0;
        text-align: center;
    }

    .btn-icon {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
        margin: 0;
        font-size: 1.2rem;
        background: transparent;
        border-radius: 4px;
    }

    .btn-icon i {
        display: inline-block !important;
        font-size: 1.2rem;
    }
}

/* Ensure buttons are touch-friendly */
@media (hover: none) {
    .btn {
        min-height: 44px;
    }
}

/* Add specific styles for Samsung S8 and similar sizes */
@media screen and (max-width: 360px) {
    .route-box-info li {
        padding: 12px;
        gap: 12px;
    }

    .route-box-info li i {
        font-size: 20px;
        padding: 10px;
    }

    .route-box-info li .text h3 {
        font-size: 16px;
    }

    .head-title h1 {
        font-size: 1.1rem;
    }

    .breadcrumb {
        font-size: 0.8rem;
    }

    .shift-content section {
        padding: 0.75rem;
    }

    .shift-table th:first-child,
    .shift-table td:first-child {
        width: 30%;
    }

    .shift-table th:nth-child(2),
    .shift-table td:nth-child(2) {
        width: 50%;
    }

    .shift-table th:last-child,
    .shift-table td:last-child {
        width: 20%;
        display: table-cell !important;
        text-align: center;
    }

    .btn-icon {
        width: 28px;
        height: 28px;
        min-width: unset;
        min-height: unset;
    }
}

.btn-icon {
    color: #007664;
    font-size: 1.2rem;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background-color 0.3s, transform 0.1s;
}

.btn-icon:hover {
    background-color: #005a4d;
    transform: scale(1.05);
}

/* Add these calendar-specific styles for smaller screens */
@media screen and (max-width: 412px) {
    /* Calendar container */
    #shift-calendar {
        height: 300px; /* Reduce overall height */
    }

    /* Calendar header */
    .fc-header-toolbar {
        padding: 8px 0 !important;
        margin-bottom: 0.5em !important;
    }

    .fc-toolbar-title {
        font-size: 1rem !important; /* Smaller title */
    }

    .fc-button {
        padding: 4px 8px !important;
        font-size: 0.8rem !important;
    }

    /* Calendar body */
    .fc-daygrid-day {
        min-height: 40px !important; /* Smaller day cells */
    }

    .fc-daygrid-day-number {
        font-size: 0.8rem !important;
        padding: 4px !important;
    }

    /* Event styles */
    .fc-event {
        margin: 1px !important;
        padding: 2px !important;
    }

    .fc-event-title {
        font-size: 0.7rem !important;
    }

    .fc-event-time {
        font-size: 0.7rem !important;
    }

    /* Week header */
    .fc-col-header-cell {
        padding: 4px !important;
    }

    .fc-col-header-cell-cushion {
        font-size: 0.8rem !important;
    }

    /* Make events more compact */
    .fc-daygrid-event-harness {
        margin-top: 1px !important;
        margin-bottom: 1px !important;
    }

    /* Adjust spacing */
    .fc-daygrid-day-frame {
        padding: 2px !important;
    }
}

/* Even smaller screens */
@media screen and (max-width: 360px) {
    #shift-calendar {
        height: 280px;
    }

    .fc-toolbar-title {
        font-size: 0.9rem !important;
    }

    .fc-daygrid-day {
        min-height: 35px !important;
    }
}

/* Add this media query to hide calendar on smaller screens */
@media screen and (max-width: 1035px) {
    .shift-calendar {
        display: none !important;
    }
}

/* Hide team field for screens less than 620px */
@media screen and (max-width: 620px) {
    .shift-table th:nth-child(3),
    .shift-table td:nth-child(3),
    .shift-table th:nth-child(4),
    .shift-table td:nth-child(4),
    .shift-table th:nth-child(5),
    .shift-table td:nth-child(5) {
        display: none;
    }

    /* Adjust remaining columns */
    .shift-table th:first-child,
    .shift-table td:first-child {
        width: 35%;
    }

    .shift-table th:nth-child(2),
    .shift-table td:nth-child(2) {
        width: 45%;
    }

    .shift-table th:last-child,
    .shift-table td:last-child {
        width: 20%;
    }
}

/* Hide time field for screens less than 400px */
@media screen and (max-width: 400px) {
    .shift-table th:nth-child(2),
    .shift-table td:nth-child(2) {
        display: none;
    }

    /* Adjust remaining columns */
    .shift-table th:first-child,
    .shift-table td:first-child {
        width: 70%;
    }

    .shift-table th:last-child,
    .shift-table td:last-child {
        width: 30%;
        display: table-cell !important;
        text-align: center;
    }

    /* Ensure icon remains visible */
    .btn-icon {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
}

/* First, hide the card container by default */
.shifts-card-container {
    display: none;
}

/* Show cards only below 590px */
@media screen and (max-width: 590px) {
    .shift-table {
        display: none; /* Hide the table */
    }

    .shifts-card-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 8px 0;
    }

    /* Rest of the card styles remain the same */
    .shift-card {
        background: #fff;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .shift-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .shift-card-day {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
    }

    .shift-card-time {
        color: #007664;
        font-weight: 500;
    }

    .shift-card-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 0.9rem;
        color: #666;
    }

    .shift-card-team {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .shift-card-team i {
        font-size: 1rem;
        color: #007664;
    }

    .shift-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #eee;
    }

    .shift-card-status {
        font-size: 0.85rem;
        padding: 4px 8px;
        border-radius: 4px;
        background: #e6f3f0;
        color: #007664;
    }

    .shift-card .btn-icon {
        color: #007664;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .shift-card .btn-icon:hover {
        background: #007664;
        color: #fff;
    }
}

/* Ensure table is visible above 590px */
@media screen and (min-width: 591px) {
    .shift-table {
        display: table;
    }

    .shifts-card-container {
        display: none !important;
    }
}

/* Filter dropdown styles */
.filter-dropdown {
    position: relative;
    margin-left: auto;
}

.filter-dropdown select {
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    font-size: 0.9rem;
    color: #333;
    cursor: pointer;
}

/* Enhanced card styles */
.swap-card {
    background: #fff;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 16px;
}

.swap-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.swap-card-requester {
    font-weight: 600;
    font-size: 1.1rem;
}

.swap-details {
    display: grid;
    gap: 16px;
    margin-bottom: 16px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
}

.requester-shift,
.your-shift {
    padding: 12px;
    background: #fff;
    border-radius: 4px;
}

.requester-shift h4,
.your-shift h4 {
    margin-bottom: 8px;
    color: #007664;
    font-size: 0.9rem;
}

.shift-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    font-size: 0.9rem;
}

.shift-info i {
    color: #007664;
    font-size: 1rem;
}

.swap-card-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.btn-approve,
.btn-reject {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-approve {
    background: #007664;
    color: #fff;
}

.btn-reject {
    background: #dc3545;
    color: #fff;
}
</style>

<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>