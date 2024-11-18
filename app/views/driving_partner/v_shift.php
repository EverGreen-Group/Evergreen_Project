<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_driving_partner.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<main class="shift-management-main">
    <div class="shift-header">
        <h1>Shift Management</h1>
    </div>

    <div class="shift-content">
        <section class="upcoming-shifts">
            <h2>Upcoming Shifts</h2>
            <?php if (isset($data['error'])): ?>
                <div class="alert alert-warning"><?php echo $data['error']; ?></div>
            <?php else: ?>
                <table class="shift-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Team</th>
                            <th>Countdown</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                                    $nextDate = date('Y-m-d', strtotime("next $day"));
                                    $startDateTime = $nextDate . ' ' . $shift->start_time;
                                    $timestamp = strtotime($startDateTime);
                                    
                                    // Only include future shifts
                                    if ($timestamp > time()) {
                                        $sortedShifts[] = [
                                            'day' => ucfirst($day),
                                            'startDateTime' => $startDateTime,
                                            'timestamp' => $timestamp,
                                            'shift' => $shift
                                        ];
                                    }
                                }
                            }

                            // Sort shifts by timestamp
                            usort($sortedShifts, function($a, $b) {
                                return $a['timestamp'] - $b['timestamp'];
                            });

                            foreach ($sortedShifts as $sortedShift):
                                $shift = $sortedShift['shift'];
                        ?>
                            <tr>
                                <td><?php echo $sortedShift['day']; ?></td>
                                <td><?php echo $shift->start_time . ' - ' . $shift->end_time; ?></td>
                                <td><?php echo $shift->team_name; ?></td>
                                <td>
                                    <span class="countdown" data-start="<?php echo $sortedShift['startDateTime']; ?>">
                                        Calculating...
                                    </span>
                                </td>
                                <td><span class="status-active">Active</span></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicledriver/scheduleDetails/<?php echo $shift->schedule_id; ?>" 
                                       class="btn btn-primary btn-sm">View Details</a>
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
            <?php endif; ?>
        </section>

        <section class="shift-actions">
            <h2>Shift Management Actions</h2>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="requestLeave()">Request Leave</button>
                <button class="btn btn-secondary" onclick="requestShiftSwap()">Request Shift Swap</button>
                <button class="btn btn-secondary" onclick="requestTeamChange()">Request Team Change</button>
            </div>
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
                // Pass the formatted shifts to the calendar component
                $data['shifts'] = $calendarShifts;
                require APPROOT . '/views/inc/components/calendar.php'; 
                ?>
            </div>
        </section>
    </div>
</main>


<script>


function acceptShift(date) {
    // Implement shift acceptance logic
    alert('Accepting shift for ' + date);
}

function requestChange(date) {
    // Implement shift change request logic
    alert('Requesting change for shift on ' + date);
}

function requestLeave() {
    // Implement leave request logic
    alert('Opening leave request form');
}

function requestShiftSwap() {
    // Implement shift swap request logic
    alert('Opening shift swap request form');
}

function requestTeamChange() {
    // Implement team change request logic
    alert('Opening team change request form');
}
</script>

<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown').forEach(function(element) {
        const startTime = new Date(element.dataset.start).getTime();
        const now = new Date().getTime();
        const distance = startTime - now;

        if (distance < 0) {
            element.innerHTML = "Started";
            return;
        }

        // Time calculations
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        element.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    });
}

// Update countdowns every second
setInterval(updateCountdowns, 1000);
// Initial update
updateCountdowns();
</script>

<style>
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
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.shift-content section {
    background-color: #fff;
    border-radius: 8px;
    padding: 1.5rem;
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
.status-accepted {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-accepted {
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
    height: 600px;
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
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>