<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Employee Manager Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Home</a></li>
            </ul>
        </div>
    </div>

    <!-- Stats Cards -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-group'></i>
            <span class="text">
                <h3><?php echo $data['stats']['employees']->total_employees; ?></h3>
                <p>Total Employees</p>
                <small><?php echo $data['stats']['employees']->male_count; ?> Men, <?php echo $data['stats']['employees']->female_count; ?> Women</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-calendar-check'></i>
            <span class="text">
                <h3><?php echo $data['stats']['attendance']->total_present; ?></h3>
                <p>Present Today</p>
                <small><?php echo $data['stats']['attendance']->late_count; ?> Late Arrivals</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-calendar-x'></i>
            <span class="text">
                <h3><?php echo $data['stats']['leaves']->pending_requests; ?></h3>
                <p>Pending Leaves</p>
                <small>Out of <?php echo $data['stats']['leaves']->total_requests; ?> This Month</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-badge-check'></i>
            <span class="text">
                <h3><?php echo $data['stats']['evaluations']->pending_evaluations; ?></h3>
                <p>Pending Evaluations</p>
            </span>
        </li>
    </ul>

    <!-- Recent Activities Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Recent Leave Requests</h3>
                <a href="<?php echo URLROOT; ?>/employeemanager/leaves" class="btn-view-all">View All</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['recent_leaves'] as $leave): ?>
                    <tr>
                        <td>
                            <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $leave->user_id; ?>.jpg" onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                            <p><?php echo $leave->first_name . ' ' . $leave->last_name; ?></p>
                        </td>
                        <td><?php echo $leave->leave_type; ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave->start_date)); ?></td>
                        <td><?php echo date('M d, Y', strtotime($leave->end_date)); ?></td>
                        <td><span class="status <?php echo strtolower($leave->status); ?>"><?php echo $leave->status; ?></span></td>
                        <td>
                            <?php if($leave->status == 'Pending'): ?>
                            <div class="action-buttons">
                                <button onclick="updateLeaveStatus(<?php echo $leave->request_id; ?>, 'Approved')" class="btn-approve">
                                    <i class='bx bx-check'></i>
                                </button>
                                <button onclick="updateLeaveStatus(<?php echo $leave->request_id; ?>, 'Rejected')" class="btn-reject">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Today's Attendance -->
        <div class="attendance">
            <div class="head">
                <h3>Today's Attendance</h3>
                <a href="<?php echo URLROOT; ?>/employeemanager/attendance" class="btn-view-all">View All</a>
            </div>
            <div class="attendance-list">
                <?php foreach($data['today_attendance'] as $attendance): ?>
                <div class="attendance-item">
                    <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $attendance->user_id; ?>.jpg" onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                    <div class="info">
                        <h4><?php echo $attendance->first_name . ' ' . $attendance->last_name; ?></h4>
                        <p><?php echo date('h:i A', strtotime($attendance->check_in_time)); ?></p>
                    </div>
                    <span class="status <?php echo strtolower($attendance->status); ?>">
                        <?php echo $attendance->status; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Pending Evaluations and Tasks -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Pending Evaluations</h3>
                <a href="<?php echo URLROOT; ?>/employeemanager/evaluations" class="btn-view-all">View All</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['pending_evaluations'] as $evaluation): ?>
                    <tr>
                        <td>
                            <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $evaluation->user_id; ?>.jpg" onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                            <p><?php echo $evaluation->first_name . ' ' . $evaluation->last_name; ?></p>
                        </td>
                        <td><?php echo $evaluation->department; ?></td>
                        <td><?php echo date('M d, Y', strtotime($evaluation->due_date)); ?></td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/employeemanager/evaluation/<?php echo $evaluation->evaluation_id; ?>" class="btn-evaluate">
                                Evaluate
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
/* Dashboard Specific Styles */
.box-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.box-info li {
    padding: 24px;
    background: var(--light);
    border-radius: 20px;
    display: flex;
    align-items: center;
    grid-gap: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.box-info li .bx {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    font-size: 36px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: var(--light-main);
    color: var(--main);
}

.box-info li .text h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
}

.box-info li .text p {
    color: var(--dark);
}

.box-info li .text small {
    color: var(--dark-grey);
    font-size: 0.875rem;
}

/* Table Styles */
.table-data {
    display: flex;
    flex-wrap: wrap;
    grid-gap: 24px;
    margin-top: 24px;
    width: 100%;
    color: var(--dark);
}

.table-data > div {
    border-radius: 20px;
    background: var(--light);
    padding: 24px;
    overflow-x: auto;
}

.table-data .head {
    display: flex;
    align-items: center;
    grid-gap: 16px;
    margin-bottom: 24px;
}

.table-data .head h3 {
    margin-right: auto;
    font-weight: 600;
}

.table-data table {
    width: 100%;
    border-collapse: collapse;
}

.table-data table th {
    padding: 12px;
    font-weight: 600;
    text-align: left;
    border-bottom: 1px solid var(--grey);
}

.table-data table td {
    padding: 12px;
    border-bottom: 1px solid var(--grey-light);
}

/* Status Styles */
.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.875rem;
}

.status.approved { background: #e6f4ea; color: #1e8e3e; }
.status.pending { background: #fef7e0; color: #b06000; }
.status.rejected { background: #fce8e6; color: #d93025; }
.status.late { background: #fce8e6; color: #d93025; }
.status.present { background: #e6f4ea; color: #1e8e3e; }

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-approve, .btn-reject {
    padding: 6px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-approve {
    background: #e6f4ea;
    color: #1e8e3e;
}

.btn-reject {
    background: #fce8e6;
    color: #d93025;
}

.btn-evaluate {
    padding: 6px 12px;
    background: var(--main);
    color: var(--light);
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-evaluate:hover {
    opacity: 0.9;
}

/* Attendance List Styles */
.attendance-list {
    max-height: 400px;
    overflow-y: auto;
}

.attendance-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid var(--grey-light);
}

.attendance-item img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    margin-right: 12px;
}

.attendance-item .info {
    flex: 1;
}

.attendance-item .info h4 {
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.attendance-item .info p {
    font-size: 0.75rem;
    color: var(--grey);
}
</style>

<script>
// Function to update leave status
async function updateLeaveStatus(requestId, status) {
    try {
        const response = await fetch(`${URLROOT}/employeemanager/updateLeaveStatus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                requestId: requestId,
                status: status
            })
        });

        const data = await response.json();
        if (data.success) {
            // Refresh the page or update UI
            location.reload();
        } else {
            alert('Failed to update leave status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while updating leave status');
    }
}

// Initialize any charts or data visualization
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 