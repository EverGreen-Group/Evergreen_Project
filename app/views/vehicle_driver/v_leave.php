<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Leave Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li>Leave Management</li>
            </ul>
        </div>
    </div>

    

    <!-- Leave Statistics Cards -->
    <ul class="route-box-info">
        <li>
            <i class='bx bxs-calendar-check'></i>
            <span class="text">
                <p>Annual Leave Balance</p>
                <h3><?php echo $data['leaveBalance']->annual ?? 0; ?> Days</h3>
            </span>
        </li>
        <li>
            <i class='bx bxs-first-aid'></i>
            <span class="text">
                <p>Sick Leave Balance</p>
                <h3><?php echo $data['leaveBalance']->sick ?? 0; ?> Days</h3>
            </span>
        </li>
        <li>
            <i class='bx bxs-hourglass'></i>
            <span class="text">
                <p>Pending Requests</p>
                <h3><?php echo $data['pendingLeaveCount'] ?? 0; ?></h3>
            </span>
        </li>
    </ul>


    <!-- Leave History Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Leave History</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['leaveHistory'] as $leave): ?>
                        <tr>
                            <td><?php echo $leave->leave_type_name; ?></td>
                            <td><?php echo date('d M Y', strtotime($leave->start_date)); ?></td>
                            <td><?php echo date('d M Y', strtotime($leave->end_date)); ?></td>
                            <td><?php echo floor((strtotime($leave->end_date) - strtotime($leave->start_date)) / (60 * 60 * 24)) + 1; ?></td>
                            <td><span class="status <?php echo strtolower($leave->status); ?>"><?php echo $leave->status; ?></span></td>
                            <td>
                                <?php if ($leave->status == 'pending'): ?>
                                    <button class="btn-cancel" onclick="cancelLeave(<?php echo $leave->id; ?>)">Cancel</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Leave Request Form -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Request Leave</h3>
            </div>
            <form id="leaveRequestForm">
                <div class="form-group">
                    <label for="leaveType">Leave Type</label>
                    <select id="leaveType" name="leaveType" required>
                        <?php foreach($data['leaveTypes'] as $type): ?>
                            <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>
                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="endDate" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="needsSwap" name="needsSwap"> Request Shift Swap
                    </label>
                </div>
                <div class="form-group swap-options" style="display: none;">
                    <label for="swapWith">Swap With</label>
                    <select id="swapWith" name="swapWith">
                        <option value="">Select Colleague</option>
                        <?php if(isset($data['availableSwapUsers']) && is_array($data['availableSwapUsers'])): ?>
                            <?php foreach($data['availableSwapUsers'] as $user): ?>
                                <option value="<?php echo $user->id; ?>"><?php echo htmlspecialchars($user->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Submit Leave Request</button>
            </form>
        </div>
    </div>

    <!-- Swap Requests Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Swap Requests</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Requested By</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['swapRequests'] as $request): ?>
                        <tr>
                            <td><?php echo $request->requester_name; ?></td>
                            <td><?php echo date('d M Y', strtotime($request->start_date)); ?></td>
                            <td><?php echo date('d M Y', strtotime($request->end_date)); ?></td>
                            <td><span class="status <?php echo strtolower($request->status); ?>"><?php echo $request->status; ?></span></td>
                            <td>
                                <?php if ($request->status == 'pending'): ?>
                                    <button class="btn-approve" onclick="handleSwapRequest(<?php echo $request->id; ?>, 'approve')">Accept</button>
                                    <button class="btn-reject" onclick="handleSwapRequest(<?php echo $request->id; ?>, 'reject')">Reject</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<style>
    .route-box-info {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-top: 36px;
        list-style: none;
        padding: 0;
    }

    .route-box-info li {
        flex: 1;
        background: var(--light);
        border-radius: 20px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .route-box-info li i {
        font-size: 36px;
        color: var(--main);
        background: var(--light-main);
        border-radius: 10%;
        padding: 16px;
    }

    .route-box-info li .text h3 {
        font-size: 24px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .route-box-info li .text p {
        font-size: 14px;
        color: var(--dark-grey);
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input[type="date"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group textarea {
        height: 100px;
        resize: vertical;
    }

    .btn-submit {
        background: var(--blue);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }
</style>

<script>
// Show/hide swap options based on checkbox
document.getElementById('needsSwap').addEventListener('change', function() {
    const swapOptions = document.querySelector('.swap-options');
    swapOptions.style.display = this.checked ? 'block' : 'none';
    document.getElementById('swapWith').required = this.checked;
});

// Handle leave request submission
document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    fetch('<?php echo URLROOT; ?>/vehicledriver/request_leave', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData)),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Leave request submitted successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error submitting leave request', 'error');
    });
});

// Handle swap request responses
function handleSwapRequest(requestId, action) {
    if (confirm(`Are you sure you want to ${action} this swap request?`)) {
        fetch(`<?php echo URLROOT; ?>/vehicledriver/handle_swap_request`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                requestId: requestId,
                action: action 
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`Swap request ${action}ed successfully`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(`Error ${action}ing swap request`, 'error');
        });
    }
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>