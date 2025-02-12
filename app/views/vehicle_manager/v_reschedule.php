<?php
// For testing purposes only: if $data['exceptions'] and $data['schedules'] are not set by the controller, 
// define some dummy data.
if (!isset($data['exceptions'])) {
    $data['exceptions'] = [
        (object)[
            'id'              => 1,
            'schedule_id'     => 101,
            'exception_date'  => '2025-01-10',
            'exception_type'  => 'SKIP',
            'new_time'        => null,
            'new_end_time'    => null,
            'reason'          => 'Holiday'
        ],
        (object)[
            'id'              => 2,
            'schedule_id'     => 102,
            'exception_date'  => '2025-01-15',
            'exception_type'  => 'RESCHEDULE',
            'new_time'        => '09:00:00',
            'new_end_time'    => '17:00:00',
            'reason'          => 'Maintenance'
        ]
    ];
}

if (!isset($data['schedules'])) {
    $data['schedules'] = [
        (object)[
            'schedule_id' => 101,
            'route_name'  => 'Route A'
        ],
        (object)[
            'schedule_id' => 102,
            'route_name'  => 'Route B'
        ]
    ];
}
?>

<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Sidebar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top Navigation Bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- CSS files -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">

<!-- Define global JS variables -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<!-- Inline JavaScript for modal functionality and toggle fields -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Open the Create Exception modal
    document.getElementById('openCreateExceptionModal').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('createExceptionModal').style.display = 'block';
    });
    // Open the Update Exception modal
    document.getElementById('openUpdateExceptionModal').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('updateExceptionModal').style.display = 'block';
    });
});

// Function to close a modal by its ID
function closeModal(modalID) {
    document.getElementById(modalID).style.display = 'none';
}

// Toggle the visibility of the reschedule time fields
// mode is either "create" or "edit"
function toggleRescheduleFields(exceptionType, mode) {
    var rescheduleFields = document.getElementById('rescheduleFields_' + mode);
    if (exceptionType === 'RESCHEDULE') {
        rescheduleFields.style.display = 'block';
    } else {
        rescheduleFields.style.display = 'none';
    }
}

// Dummy function to load exception data into the update form.
// Replace this with an AJAX call or your own logic as needed.
function loadExceptionData(exceptionID) {
    console.log("Load data for exception: " + exceptionID);
    // Example: Populate the update form fields using an AJAX call
    // For demonstration, you might set:
    // document.getElementById('edit_exception_date').value = '2025-01-01';
}
</script>

<!-- MAIN CONTENT -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Collection Rescheduling &amp; Skips</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
        <div class="datetime-display">
            <div class="date">
                <i class='bx bx-calendar'></i>
                <span><?php echo date('l, F j, Y'); ?></span>
            </div>
            <div class="time" id="live-time">
                <i class='bx bx-time-five'></i>
                <span>Loading...</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="#" id="openCreateExceptionModal" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Create Exception
        </a>
        <a href="#" id="openUpdateExceptionModal" class="btn btn-primary">
            <i class='bx bx-analyse'></i>
            Update Exception
        </a>
    </div>

    <?php 
    // Separate exceptions into skips and reschedules.
    $skips = array_filter($data['exceptions'], function($exception) {
        return $exception->exception_type === 'SKIP';
    });
    $reschedules = array_filter($data['exceptions'], function($exception) {
        return $exception->exception_type === 'RESCHEDULE';
    });
    ?>

    <!-- Skips Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Skip Exceptions</h3>
                <script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/calendar.js"></script>
            </div>
            <table id="skips-table">
                <thead>
                    <tr>
                        <th>Exception ID</th>
                        <th>Schedule</th>
                        <th>Exception Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($skips)) : ?>
                        <?php foreach ($skips as $exception) : ?>
                            <tr>
                                <td><?= $exception->id; ?></td>
                                <td>
                                    <?php 
                                    // Display schedule information. Adjust as needed.
                                    echo "Schedule " . str_pad($exception->schedule_id, 3, '0', STR_PAD_LEFT); 
                                    ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($exception->exception_date)); ?></td>
                                <td><?= htmlspecialchars($exception->reason); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">No skip exceptions found. Please create one.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reschedules Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Reschedule Exceptions</h3>
                <script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/calendar.js"></script>
            </div>
            <table id="reschedules-table">
                <thead>
                    <tr>
                        <th>Exception ID</th>
                        <th>Schedule</th>
                        <th>Exception Date</th>
                        <th>New Start Time</th>
                        <th>New End Time</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reschedules)) : ?>
                        <?php foreach ($reschedules as $exception) : ?>
                            <tr>
                                <td><?= $exception->id; ?></td>
                                <td>
                                    <?php 
                                    echo "Schedule " . str_pad($exception->schedule_id, 3, '0', STR_PAD_LEFT); 
                                    ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($exception->exception_date)); ?></td>
                                <td>
                                    <?= $exception->new_time ? date('h:i A', strtotime($exception->new_time)) : '-' ; ?>
                                </td>
                                <td>
                                    <?= $exception->new_end_time ? date('h:i A', strtotime($exception->new_end_time)) : '-' ; ?>
                                </td>
                                <td><?= htmlspecialchars($exception->reason); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">No reschedule exceptions found. Please create one.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Exception Modal -->
    <div id="createExceptionModal" class="modal" style="display:none;" onclick="if(event.target === this){ closeModal('createExceptionModal'); }">
        <div class="modal-content" style="width: 80%; max-width: 600px;" onclick="event.stopPropagation();">
            <span class="close" onclick="closeModal('createExceptionModal')">&times;</span>
            <h2 style="margin-bottom: 30px;">Create New Exception</h2>
            <form id="createExceptionForm" method="POST" action="<?php echo URLROOT; ?>/collection_exceptions/create">
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Select Schedule -->
                    <div class="form-group">
                        <label for="schedule_id">Select Schedule:</label>
                        <select id="schedule_id" name="schedule_id" required>
                            <option value="" disabled selected>Select a schedule</option>
                            <?php foreach ($data['schedules'] as $schedule) : ?>
                                <option value="<?= $schedule->schedule_id; ?>">
                                    Schedule <?= str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?> - <?= htmlspecialchars($schedule->route_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Exception Date -->
                    <div class="form-group">
                        <label for="exception_date">Exception Date:</label>
                        <input type="date" id="exception_date" name="exception_date" required>
                    </div>
                    <!-- Exception Type -->
                    <div class="form-group">
                        <label for="exception_type">Exception Type:</label>
                        <select id="exception_type" name="exception_type" required onchange="toggleRescheduleFields(this.value, 'create')">
                            <option value="" disabled selected>Select type</option>
                            <option value="SKIP">Skip</option>
                            <option value="RESCHEDULE">Reschedule</option>
                        </select>
                    </div>
                    <!-- New Time Fields (only for RESCHEDULE) -->
                    <div id="rescheduleFields_create" style="display: none;">
                        <div class="form-group">
                            <label for="new_time">New Start Time:</label>
                            <input type="time" id="new_time" name="new_time">
                        </div>
                        <div class="form-group">
                            <label for="new_end_time">New End Time:</label>
                            <input type="time" id="new_end_time" name="new_end_time">
                        </div>
                    </div>
                    <!-- Reason -->
                    <div class="form-group">
                        <label for="reason">Reason:</label>
                        <textarea id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-secondary">Create Exception</button>
            </form>
        </div>
    </div>

    <!-- Update Exception Modal -->
    <div id="updateExceptionModal" class="modal" style="display:none;" onclick="if(event.target === this){ closeModal('updateExceptionModal'); }">
        <div class="modal-content" style="width: 80%; max-width: 600px;" onclick="event.stopPropagation();">
            <span class="close" onclick="closeModal('updateExceptionModal')">&times;</span>
            <h2 style="margin-bottom: 30px;">Update Exception</h2>
            <form id="updateExceptionForm" method="POST" action="<?php echo URLROOT; ?>/collection_exceptions/update">
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Select Exception to Update -->
                    <div class="form-group">
                        <label for="exception_id">Select Exception:</label>
                        <select id="exception_id" name="exception_id" required onchange="loadExceptionData(this.value)">
                            <option value="" disabled selected>Select an exception</option>
                            <?php foreach ($data['exceptions'] as $exception) : ?>
                                <option value="<?= $exception->id; ?>">
                                    Exception <?= $exception->id; ?> - <?= date('M d, Y', strtotime($exception->exception_date)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Schedule -->
                    <div class="form-group">
                        <label for="edit_schedule_id">Schedule:</label>
                        <select id="edit_schedule_id" name="schedule_id" required>
                            <?php foreach ($data['schedules'] as $schedule) : ?>
                                <option value="<?= $schedule->schedule_id; ?>">
                                    Schedule <?= str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?> - <?= htmlspecialchars($schedule->route_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Exception Date -->
                    <div class="form-group">
                        <label for="edit_exception_date">Exception Date:</label>
                        <input type="date" id="edit_exception_date" name="exception_date" required>
                    </div>
                    <!-- Exception Type -->
                    <div class="form-group">
                        <label for="edit_exception_type">Exception Type:</label>
                        <select id="edit_exception_type" name="exception_type" required onchange="toggleRescheduleFields(this.value, 'edit')">
                            <option value="" disabled selected>Select type</option>
                            <option value="SKIP">Skip</option>
                            <option value="RESCHEDULE">Reschedule</option>
                        </select>
                    </div>
                    <!-- New Time Fields (only for RESCHEDULE) -->
                    <div id="rescheduleFields_edit" style="display: none;">
                        <div class="form-group">
                            <label for="edit_new_time">New Start Time:</label>
                            <input type="time" id="edit_new_time" name="new_time">
                        </div>
                        <div class="form-group">
                            <label for="edit_new_end_time">New End Time:</label>
                            <input type="time" id="edit_new_end_time" name="new_end_time">
                        </div>
                    </div>
                    <!-- Reason -->
                    <div class="form-group">
                        <label for="edit_reason">Reason:</label>
                        <textarea id="edit_reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-secondary">Update Exception</button>
            </form>
        </div>
    </div>

</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
