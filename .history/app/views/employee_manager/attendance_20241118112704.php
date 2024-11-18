<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Attendance Management</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Attendance</a></li>
            </ul>
        </div>
        <button class="btn-download" onclick="exportAttendance()">
            <i class='bx bxs-cloud-download'></i>
            <span class="text">Export Report</span>
        </button>
    </div>

    <!-- Attendance Overview -->
    <div class="attendance-stats">
        <div class="stat-card present">
            <i class='bx bxs-user-check'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['present']; ?></div>
                <div class="stat-label">Present Today</div>
            </div>
        </div>
        <div class="stat-card late">
            <i class='bx bxs-time-five'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['late']; ?></div>
                <div class="stat-label">Late Arrivals</div>
            </div>
        </div>
        <div class="stat-card absent">
            <i class='bx bxs-user-x'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['absent']; ?></div>
                <div class="stat-label">Absent</div>
            </div>
        </div>
        <div class="stat-card leave">
            <i class='bx bxs-calendar'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['on_leave']; ?></div>
                <div class="stat-label">On Leave</div>
            </div>
        </div>
    </div>

    <!-- Mark Attendance Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Mark Attendance</h3>
                <div class="filter-group">
                    <input type="date" id="attendanceDate" value="<?php echo date('Y-m-d'); ?>" 
                           onchange="loadAttendance(this.value)">
                    <select id="departmentFilter" onchange="filterAttendance()">
                        <option value="">All Departments</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <table id="attendanceTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Working Hours</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['attendance'] as $record): ?>
                    <tr data-id="<?php echo $record->user_id; ?>">
                        <td>
                            <div class="employee-info">
                                <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $record->user_id; ?>.jpg" 
                                     onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                                <div>
                                    <p class="name"><?php echo $record->first_name . ' ' . $record->last_name; ?></p>
                                    <p class="emp-id">ID: <?php echo $record->employee_id; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $record->department; ?></td>
                        <td class="check-in">
                            <?php if($record->check_in_time): ?>
                                <?php echo date('h:i A', strtotime($record->check_in_time)); ?>
                            <?php else: ?>
                                <button onclick="markAttendance(<?php echo $record->user_id; ?>, 'check_in')" 
                                        class="btn-mark-attendance">
                                    Mark Check In
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="check-out">
                            <?php if($record->check_out_time): ?>
                                <?php echo date('h:i A', strtotime($record->check_out_time)); ?>
                            <?php elseif($record->check_in_time): ?>
                                <button onclick="markAttendance(<?php echo $record->user_id; ?>, 'check_out')" 
                                        class="btn-mark-attendance">
                                    Mark Check Out
                                </button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status <?php echo strtolower($record->status); ?>">
                                <?php echo $record->status; ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            if($record->check_in_time && $record->check_out_time) {
                                $hours = round((strtotime($record->check_out_time) - strtotime($record->check_in_time)) / 3600, 1);
                                echo $hours . ' hrs';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <button onclick="viewAttendanceHistory(<?php echo $record->user_id; ?>)" 
                                    class="btn-view" title="View History">
                                <i class='bx bx-history'></i>
                            </button>
                            <?php if($record->check_in_time): ?>
                            <button onclick="editAttendance(<?php echo $record->user_id; ?>)" 
                                    class="btn-edit" title="Edit Record">
                                <i class='bx bx-edit'></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance History Modal -->
    <div id="attendanceHistoryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Attendance History</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div id="editAttendanceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Attendance Record</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editAttendanceForm">
                    <input type="hidden" id="editUserId">
                    <div class="form-group">
                        <label>Check In Time</label>
                        <input type="time" id="editCheckIn" required>
                    </div>
                    <div class="form-group">
                        <label>Check Out Time</label>
                        <input type="time" id="editCheckOut">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="editStatus" required>
                            <option value="Present">Present</option>
                            <option value="Late">Late</option>
                            <option value="Half Day">Half Day</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea id="editRemarks"></textarea>
                    </div>
                    <button type="submit" class="btn-save">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
/* Add your CSS styles here */
</style>

<script>
let attendanceTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    attendanceTable = DataTableUtils.init('#attendanceTable', {
        order: [[2, 'asc']], // Sort by check-in time
        columns: [
            { orderable: false }, // Employee
            null,                 // Department
            null,                 // Check In
            null,                 // Check Out
            null,                 // Status
            null,                 // Working Hours
            { orderable: false }  // Actions
        ]
    });
});

async function markAttendance(userId, type) {
    try {
        const response = await AjaxUtils.post(`${URLROOT}/employeemanager/markAttendance`, {
            userId: userId,
            type: type,
            date: document.getElementById('attendanceDate').value
        });

        if (response.success) {
            AjaxUtils.showSuccess(`${type === 'check_in' ? 'Check In' : 'Check Out'} marked successfully`);
            loadAttendance(document.getElementById('attendanceDate').value);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadAttendance(date) {
    try {
        const response = await AjaxUtils.get(`${URLROOT}/employeemanager/getAttendance`, {
            date: date
        });

        if (response.success) {
            // Update table data
            attendanceTable.clear().rows.add(response.data).draw();
            // Update statistics
            updateStats(response.stats);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function updateStats(stats) {
    document.querySelector('.stat-card.present .stat-value').textContent = stats.present;
    document.querySelector('.stat-card.late .stat-value').textContent = stats.late;
    document.querySelector('.stat-card.absent .stat-value').textContent = stats.absent;
    document.querySelector('.stat-card.leave .stat-value').textContent = stats.on_leave;
}

async function viewAttendanceHistory(userId) {
    try {
        const response = await AjaxUtils.get(`${URLROOT}/employeemanager/getAttendanceHistory/${userId}`);
        
        if (response.success) {
            // Populate modal with attendance history
            const modalBody = document.querySelector('#attendanceHistoryModal .modal-body');
            modalBody.innerHTML = generateHistoryHTML(response.data);
            
            // Show modal
            document.getElementById('attendanceHistoryModal').style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function generateHistoryHTML(history) {
    // Generate HTML for attendance history
    let html = '<div class="history-list">';
    history.forEach(record => {
        html += `
            <div class="history-item">
                <div class="date">${formatDate(record.date)}</div>
                <div class="times">
                    <span>In: ${record.check_in_time ? formatTime(record.check_in_time) : '-'}</span>
                    <span>Out: ${record.check_out_time ? formatTime(record.check_out_time) : '-'}</span>
                </div>
                <span class="status ${record.status.toLowerCase()}">${record.status}</span>
            </div>
        `;
    });
    html += '</div>';
    return html;
}

async function exportAttendance() {
    const date = document.getElementById('attendanceDate').value;
    const department = document.getElementById('departmentFilter').value;
    
    window.location.href = `${URLROOT}/employeemanager/exportAttendance?date=${date}&department=${department}`;
}

// Modal handling code
// ... (similar to previous modal code)
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 