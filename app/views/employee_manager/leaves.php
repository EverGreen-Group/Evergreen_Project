<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Leave Management</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Leave Management</a></li>
            </ul>
        </div>
    </div>

    <!-- Leave Statistics -->
    <div class="leave-stats">
        <div class="stat-card">
            <div class="stat-value"><?php echo $data['stats']['pending']; ?></div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $data['stats']['approved']; ?></div>
            <div class="stat-label">Approved This Month</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $data['stats']['rejected']; ?></div>
            <div class="stat-label">Rejected This Month</div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Leave Requests</h3>
                <div class="filter-group">
                    <select id="statusFilter" onchange="filterLeaves()">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <select id="typeFilter" onchange="filterLeaves()">
                        <option value="">All Types</option>
                        <option value="Annual">Annual Leave</option>
                        <option value="Sick">Sick Leave</option>
                        <option value="Casual">Casual Leave</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            
            <table id="leaveRequestsTable" class="display">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['requests'] as $request): ?>
                    <tr data-id="<?php echo $request->request_id; ?>">
                        <td>
                            <div class="employee-info">
                                <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $request->user_id; ?>.jpg" 
                                     onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'"
                                     alt="<?php echo $request->first_name; ?>">
                                <div>
                                    <p class="name"><?php echo $request->first_name . ' ' . $request->last_name; ?></p>
                                    <p class="department"><?php echo $request->department; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $request->leave_type; ?></td>
                        <td data-sort="<?php echo $request->start_date; ?>">
                            <?php echo date('M d, Y', strtotime($request->start_date)); ?>
                        </td>
                        <td data-sort="<?php echo $request->end_date; ?>">
                            <?php echo date('M d, Y', strtotime($request->end_date)); ?>
                        </td>
                        <td><?php echo $request->days; ?></td>
                        <td>
                            <div class="reason-tooltip" title="<?php echo htmlspecialchars($request->reason); ?>">
                                <?php echo strlen($request->reason) > 30 ? substr($request->reason, 0, 30) . '...' : $request->reason; ?>
                            </div>
                        </td>
                        <td>
                            <span class="status <?php echo strtolower($request->status); ?>">
                                <?php echo $request->status; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($request->status == 'Pending'): ?>
                            <div class="action-buttons">
                                <button onclick="handleLeaveAction(<?php echo $request->request_id; ?>, 'Approved')" 
                                        class="btn-approve" title="Approve">
                                    <i class='bx bx-check'></i>
                                </button>
                                <button onclick="handleLeaveAction(<?php echo $request->request_id; ?>, 'Rejected')" 
                                        class="btn-reject" title="Reject">
                                    <i class='bx bx-x'></i>
                                </button>
                                <button onclick="viewLeaveDetails(<?php echo $request->request_id; ?>)" 
                                        class="btn-view" title="View Details">
                                    <i class='bx bx-detail'></i>
                                </button>
                            </div>
                            <?php else: ?>
                            <button onclick="viewLeaveDetails(<?php echo $request->request_id; ?>)" 
                                    class="btn-view" title="View Details">
                                <i class='bx bx-detail'></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Leave Details Modal -->
    <div id="leaveDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Leave Request Details</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</main>

<style>
/* Leave Management Specific Styles */
.leave-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 2em;
    font-weight: bold;
    color: var(--main);
    margin-bottom: 8px;
}

.stat-label {
    color: var(--dark);
    font-size: 0.9em;
}

.filter-group {
    display: flex;
    gap: 10px;
}

.filter-group select {
    padding: 6px 12px;
    border: 1px solid var(--grey-light);
    border-radius: 4px;
    background: var(--light);
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.employee-info img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.employee-info .name {
    font-weight: 500;
    margin-bottom: 2px;
}

.employee-info .department {
    font-size: 0.8em;
    color: var(--grey);
}

.reason-tooltip {
    cursor: help;
    position: relative;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: var(--light);
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
    position: relative;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.close {
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: var(--main);
}
</style>

<script>
let leaveTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    leaveTable = DataTableUtils.init('#leaveRequestsTable', {
        order: [[2, 'desc']], // Sort by start date
        columns: [
            { orderable: false }, // Employee
            null,                 // Leave Type
            { type: 'date' },     // From
            { type: 'date' },     // To
            { type: 'num' },      // Days
            { orderable: false }, // Reason
            null,                 // Status
            { orderable: false }  // Actions
        ]
    });

    // Initialize tooltips
    $('.reason-tooltip').tooltip();
});

async function handleLeaveAction(requestId, action) {
    try {
        const confirmed = await AjaxUtils.confirm(
            `Are you sure you want to ${action.toLowerCase()} this leave request?`
        );
        
        if (!confirmed) return;

        const response = await AjaxUtils.post(`${URLROOT}/employeemanager/updateLeaveStatus`, {
            requestId: requestId,
            status: action
        });

        if (response.success) {
            AjaxUtils.showSuccess(`Leave request ${action.toLowerCase()} successfully`);
            setTimeout(() => location.reload(), 1500);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function viewLeaveDetails(requestId) {
    try {
        const response = await AjaxUtils.get(`${URLROOT}/employeemanager/getLeaveDetails/${requestId}`);
        
        if (response.success) {
            // Populate modal with leave details
            document.querySelector('.modal-body').innerHTML = `
                <div class="leave-details">
                    <div class="detail-row">
                        <label>Employee:</label>
                        <span>${response.data.employee_name}</span>
                    </div>
                    <div class="detail-row">
                        <label>Leave Type:</label>
                        <span>${response.data.leave_type}</span>
                    </div>
                    <div class="detail-row">
                        <label>Duration:</label>
                        <span>${formatDate(response.data.start_date)} - ${formatDate(response.data.end_date)}</span>
                    </div>
                    <div class="detail-row">
                        <label>Reason:</label>
                        <span>${response.data.reason}</span>
                    </div>
                    <div class="detail-row">
                        <label>Status:</label>
                        <span class="status ${response.data.status.toLowerCase()}">${response.data.status}</span>
                    </div>
                    ${response.data.status !== 'Pending' ? `
                        <div class="detail-row">
                            <label>Reviewed By:</label>
                            <span>${response.data.reviewed_by}</span>
                        </div>
                        <div class="detail-row">
                            <label>Review Date:</label>
                            <span>${formatDate(response.data.reviewed_at)}</span>
                        </div>
                    ` : ''}
                </div>
            `;
            
            // Show modal
            document.getElementById('leaveDetailsModal').style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Close modal when clicking outside or on close button
document.querySelector('.close').onclick = function() {
    document.getElementById('leaveDetailsModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('leaveDetailsModal')) {
        document.getElementById('leaveDetailsModal').style.display = 'none';
    }
}

function filterLeaves() {
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    leaveTable.columns(6).search(statusFilter); // Status column
    leaveTable.columns(1).search(typeFilter);   // Type column
    leaveTable.draw();
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 