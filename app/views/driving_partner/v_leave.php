<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_driving_partner.php';
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
                <div class="filter-dropdown">
                    <select id="leaveStatusFilter" onchange="filterLeaveHistory()">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Table view for leave history (shows above 586px) -->
            <table class="leave-table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-status="pending">
                        <td>Annual Leave</td>
                        <td>15 Mar 2024</td>
                        <td>20 Mar 2024</td>
                        <td>6</td>
                        <td><span class="status pending">Pending</span></td>
                        <td><button class="btn-cancel" onclick="cancelLeave(1)">Cancel</button></td>
                    </tr>
                    <tr data-status="approved">
                        <td>Sick Leave</td>
                        <td>01 Mar 2024</td>
                        <td>02 Mar 2024</td>
                        <td>2</td>
                        <td><span class="status approved">Approved</span></td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>

            <!-- Card view for leave history (shows below 586px) -->
            <div class="leave-cards-container">
                <div class="leave-card" data-status="pending">
                    <div class="leave-card-header">
                        <span class="leave-card-type">Annual Leave</span>
                        <span class="status pending">Pending</span>
                    </div>
                    <div class="leave-card-dates">
                        <div>From: 15 Mar 2024</div>
                        <div>To: 20 Mar 2024</div>
                        <div>Days: 6</div>
                    </div>
                    <button class="btn-cancel" onclick="cancelLeave(1)">Cancel Request</button>
                </div>

                <div class="leave-card" data-status="approved">
                    <div class="leave-card-header">
                        <span class="leave-card-type">Sick Leave</span>
                        <span class="status approved">Approved</span>
                    </div>
                    <div class="leave-card-dates">
                        <div>From: 01 Mar 2024</div>
                        <div>To: 02 Mar 2024</div>
                        <div>Days: 2</div>
                    </div>
                </div>
            </div>
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
                <div class="filter-dropdown">
                    <select id="swapStatusFilter" onchange="filterSwapRequests()">
                        <option value="all">All Requests</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Card view for swap requests -->
            <div class="swap-cards-container">
                <div class="swap-card" data-status="pending">
                    <div class="swap-card-header">
                        <span class="swap-card-requester">John Smith</span>
                        <span class="status pending">Pending</span>
                    </div>
                    <div class="swap-details">
                        <div class="shift-info">
                            <i class='bx bxs-calendar'></i>
                            <span>18 Mar 2024</span>
                        </div>
                        <div class="shift-info">
                            <i class='bx bxs-time'></i>
                            <span>Morning Shift (6:00 AM - 2:00 PM)</span>
                        </div>
                        <div class="shift-info">
                            <i class='bx bxs-group'></i>
                            <span>Team Alpha</span>
                        </div>
                    </div>
                    <div class="swap-card-actions">
                        <button class="btn-approve" onclick="handleSwapRequest(1, 'approve')">Accept</button>
                        <button class="btn-reject" onclick="handleSwapRequest(1, 'reject')">Reject</button>
                    </div>
                </div>
            </div>
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
        max-width: 100%;
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
        width: auto;
        max-width: 200px;
        display: inline-block;
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

    /* Mobile responsive styles */
    @media screen and (max-width: 768px) {
        /* Stats cards adjustment */
        .route-box-info {
            flex-direction: column;
            gap: 12px;
        }

        .route-box-info li {
            padding: 16px;
        }

        /* Hide tables on mobile */
        .table-data table {
            display: none;
        }

        /* Leave History Cards */
        .leave-cards-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 8px 0;
        }

        .leave-card {
            background: var(--light);
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .leave-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .leave-card-type {
            font-weight: 600;
            color: var(--dark);
        }

        .leave-card-dates {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin: 8px 0;
            font-size: 0.9rem;
        }

        .leave-card-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin: 8px 0;
        }

        /* Swap Request Cards */
        .swap-cards-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 8px 0;
        }

        .swap-card {
            background: var(--light);
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .swap-card-header {
            margin-bottom: 8px;
        }

        .swap-card-dates {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin: 8px 0;
            font-size: 0.9rem;
        }

        .swap-card-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        /* Form adjustments */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 10px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
        }
    }

    /* Update status badge styles */
    .status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .status.pending {
        background-color: #FF9800; /* Orange */
        color: white;
    }

    .status.approved {
        background-color: #4CAF50; /* Keep green for approved */
        color: white;
    }

    .status.cancelled {
        background-color: #F44336; /* Red */
        color: white;
    }

    /* Update card status badges to match */
    .leave-card .status.pending {
        background-color: #FF9800;
        color: white;
    }

    .leave-card .status.approved {
        background-color: #4CAF50;
        color: white;
    }

    .leave-card .status.cancelled {
        background-color: #F44336;
        color: white;
    }

    /* Swap Request Card styles */
    .swap-card {
        background: #fff;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 12px;
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
        color: #333;
    }

    .swap-details {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 16px;
    }

    .shift-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #555;
    }

    .shift-info:last-child {
        margin-bottom: 0;
    }

    .shift-info i {
        color: #007664;
        font-size: 1.1rem;
    }

    .swap-card-actions {
        display: flex;
        gap: 8px;
    }

    /* Button styles */
    .btn-submit {
        background-color: #007664;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        width: 100%;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #005a4d;
    }

    .btn-approve,
    .btn-reject {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        flex: 1;
        width: auto;
        min-width: 100px;
        max-width: 150px;
    }

    .btn-approve {
        background-color: #007664;
        color: white;
    }

    .btn-approve:hover {
        background-color: #005a4d;
    }

    .btn-reject {
        background-color: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background-color: #c82333;
    }

    .btn-cancel {
        background-color: #dc3545;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s;
    }

    .btn-cancel:hover {
        background-color: #c82333;
    }

    /* Add these styles */
    .leave-table {
        width: 100%;
        display: table;
        margin-top: 1rem;
    }

    .leave-cards-container {
        display: none;
    }

    @media screen and (max-width: 586px) {
        .leave-table {
            display: none;
        }

        .leave-cards-container {
            display: block;
        }
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

// Add the filter functions
function filterLeaveHistory() {
    const status = document.getElementById('leaveStatusFilter').value;
    const cards = document.querySelectorAll('.leave-card');
    const rows = document.querySelectorAll('.leave-table tbody tr');
    
    // Filter cards
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // Filter table rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterSwapRequests() {
    const status = document.getElementById('swapStatusFilter').value;
    const cards = document.querySelectorAll('.swap-card');
    
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>