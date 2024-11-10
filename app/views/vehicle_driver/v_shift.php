<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main class="shift-management-main">
    <div class="shift-header">
        <h1>Shift Management</h1>
    </div>

    <div class="shift-content">
        <section class="upcoming-shifts">
            <h2>Upcoming Shifts</h2>
            <table class="shift-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Team</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Dummy data for demonstration
                    $upcomingShifts = [
                        ['date' => '2024-09-20', 'time' => '08:00 - 16:00', 'team' => 'Team A', 'status' => 'Pending'],
                        ['date' => '2024-09-21', 'time' => '09:00 - 17:00', 'team' => 'Team A', 'status' => 'Accepted'],
                        ['date' => '2024-09-22', 'time' => '10:00 - 18:00', 'team' => 'Team A', 'status' => 'Pending'],
                    ];

                    foreach ($upcomingShifts as $shift):
                    ?>
                    <tr>
                        <td><?php echo $shift['date']; ?></td>
                        <td><?php echo $shift['time']; ?></td>
                        <td><?php echo $shift['team']; ?></td>
                        <td><span class="status-<?php echo strtolower($shift['status']); ?>"><?php echo $shift['status']; ?></span></td>
                        <td>
                            <?php if ($shift['status'] === 'Pending'): ?>
                                <button class="btn btn-primary btn-sm" onclick="acceptShift('<?php echo $shift['date']; ?>')">Accept</button>
                            <?php elseif ($shift['status'] === 'Accepted'): ?>
                                <button class="btn btn-secondary btn-sm" onclick="requestChange('<?php echo $shift['date']; ?>')">Request Change</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="shift-actions">
            <h2>Shift Management Actions</h2>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="requestLeave()">Request Leave</button>
                <button class="btn btn-secondary" onclick="requestShiftSwap()">Request Shift Swap</button>
                <button class="btn btn-secondary" onclick="requestTeamChange()">Request Team Change</button>
            </div>
        </section>

        <section class="shift-calendar">
            <h2>Shift Calendar</h2>
            <div id="shift-calendar"></div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('shift-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            // Add your shift events here
            { title: 'Shift', start: '2024-09-20T08:00:00', end: '2024-09-20T16:00:00' },
            { title: 'Shift', start: '2024-09-21T09:00:00', end: '2024-09-21T17:00:00' },
            { title: 'Shift', start: '2024-09-22T10:00:00', end: '2024-09-22T18:00:00' }
        ]
    });
    calendar.render();
});

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
    background-color: #86E211;
    color: #fff;
}

.btn-primary:hover {
    background-color: #78cc0f;
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
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>