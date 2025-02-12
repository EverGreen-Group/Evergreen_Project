<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<!-- MAIN -->
<main>

    
    <div class="head-title">
        <div class="left">
            <h1>Supplier Applications</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard > </a></li>
                <li><a href="#">Applications</a></li>
            </ul>
        </div>
    </div>

    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-file'></i>
            <span class="text">
                <h3>10</h3>
                <p>Total Applications</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-check-circle'></i>
            <span class="text">
                <h3>5</h3>
                <p>Approved</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-x-circle'></i>
            <span class="text">
                <h3>3</h3>
                <p>Rejected</p>
            </span>
        </li>
    </ul>

    <!-- Applications Table -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Applications</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>User ID</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['applications'] as $application): ?>
                        <tr>
                            <td>APP<?= str_pad($application->application_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $application->user_id ?></td>
                            <td>
                                <span class="status-badge <?= strtolower($application->status) ?>">
                                    <?= ucfirst($application->status) ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($application->created_at)) ?></td>
                            <td>
                                <a href="<?= URLROOT ?>/suppliermanager/viewApplication/<?= $application->application_id ?>" class="btn-view">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approved Applications without Supplier Role -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Approved Applications (Pending Role Assignment)</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>User Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['approved_pending_role'] as $application): ?>
                        <tr>
                            <td>APP<?= str_pad($application->application_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $application->user_name ?></td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="btn-confirm"
                                   onclick="confirmSupplierRole(<?php echo $application->application_id ?>)"
                                   >
                                    <i class='bx bx-user-check'></i> Confirm Role
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function confirmSupplierRole(applicationId) {
        const data = { application_id: applicationId };
        fetch('<?= URLROOT ?>/suppliermanager/confirmSupplierRole', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json()) // Corrected from respose to response
        .then(data => {
            if (data.success) {
                location.reload(); // Corrected from location.refresh() to location.reload()
            } else {
                alert('Error confirming role: ' + (data.message || 'Unknown error')); // Added fallback for message
            }
        })
        .catch(error => {
            alert('Error confirming role: ' + error.message);
        });
    }
</script>

    <!-- Land Inspection Requests -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Land Inspection Requests</h3>
            <div class="head-actions">
                <select class="filter-select">
                    <option value="all">All Requests</option>
                    <option value="pending">Pending</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Inspection ID</th>
                    <th>Supplier ID</th>
                    <th>Land Area (Acres)</th>
                    <th>Location</th>
                    <th>Preferred Date</th>
                    <th>Scheduled Date</th>
                    <th>Scheduled Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data['previous_inspections']): ?>
                    <?php foreach ($data['previous_inspections'] as $inspection): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($inspection->inspection_id ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($inspection->supplier_id ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($inspection->land_area ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($inspection->location ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($inspection->preferred_date ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($inspection->scheduled_date ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($inspection->scheduled_time ?? 'N/A'); ?></td>
                            <td>
                                <select class="status-select" onchange="updateStatus('<?php echo $inspection->request_id; ?>', this.value)" 
                                        <?php echo ($inspection->scheduled_date == null ? 'disabled' : ''); ?>>
                                    <option value="pending" <?php echo ($inspection->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="cancelled" <?php echo ($inspection->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                    <option value="completed" <?php echo ($inspection->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                </select>
                            </td>
                            <td>
                                <div class="action-buttons" id="action-<?php echo $inspection->request_id; ?>">
                                    <?php if ($inspection->status !== 'scheduled'): ?>
                                        <button class="btn-approve schedule-btn" onclick="showScheduleInputs('<?php echo $inspection->request_id; ?>')">
                                            <i class='bx bx-calendar'></i> Schedule
                                        </button>
                                    <?php else: ?>
                                        <span class="status-badge scheduled">Scheduled</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No previous inspection requests found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .inline-date-input, .inline-time-input {
        width: 100%;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.8rem;
    }
    .text-center {
        text-align: center;
    }
</style>

<script>
function scheduleInspection(requestId) {
    const dateInput = document.getElementById(`scheduleDate-${requestId}`);
    const timeInput = document.getElementById(`scheduleTime-${requestId}`);
    
    if (dateInput.value && timeInput.value) {
        console.log('Scheduling inspection:', {
            requestId: requestId,
            date: dateInput.value,
            time: timeInput.value
        });
        // Here you would typically make an AJAX call to save the scheduling
    } else {
        alert('Please select both date and time');
    }
}
</script>
</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>


<script>
    function scheduleInspection(requestId) {
        // Set the request ID in the modal
        document.getElementById('requestId').value = requestId;
        
        // Show the modal
        document.getElementById('scheduleInspectionModal').style.display = 'block';
    }

    function closeModal() {
        // Hide the modal
        document.getElementById('scheduleInspectionModal').style.display = 'none';
    }

    document.getElementById('inspectionForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const requestId = document.getElementById('requestId').value;
        const inspectionDate = document.getElementById('inspectionDate').value;
        const inspectionTime = document.getElementById('inspectionTime').value;

        // Add your scheduling logic here
        console.log('Scheduling inspection for:', requestId, 'on', inspectionDate, 'at', inspectionTime);

        // Close the modal after scheduling
        closeModal();
    });

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('scheduleInspectionModal');
        if (event.target == modal) {
            closeModal();
        }
    };

    // Existing functions
    function markComplete(requestId) {
        console.log('Marking inspection as complete:', requestId);
    }

    function viewDetails(requestId) {
        console.log('Viewing details for:', requestId);
    }

    document.querySelector('.filter-select').addEventListener('change', function() {
        const status = this.value;
        console.log('Filtering by status:', status);
    });

    function showScheduleInputs(requestId) {
        const actionDiv = document.getElementById(`action-${requestId}`);
        
        // Create date and time input fields
        const html = `
            <input type="date" class="inline-date-input" id="date-${requestId}" 
                min="${new Date().toISOString().split('T')[0]}" required>
            <input type="time" class="inline-time-input" id="time-${requestId}" required>
            <button class="btn-approve" onclick="submitSchedule('${requestId}')">
                <i class='bx bx-check'></i> Confirm
            </button>
            <button class="btn-reject" onclick="cancelSchedule('${requestId}')">
                <i class='bx bx-x'></i> Cancel
            </button>
        `;
        
        actionDiv.innerHTML = html;
    }

    function cancelSchedule(requestId) {
        const actionDiv = document.getElementById(`action-${requestId}`);
        actionDiv.innerHTML = `
            <button class="btn-approve schedule-btn" onclick="showScheduleInputs('${requestId}')">
                <i class='bx bx-calendar'></i> Schedule
            </button>
        `;
    }

    function submitSchedule(requestId) {
        const date = document.getElementById(`date-${requestId}`).value;
        const time = document.getElementById(`time-${requestId}`).value;
        
        if (!date || !time) {
            alert('Please select both date and time');
            return;
        }

        const formData = new FormData();
        formData.append('request_id', requestId);
        formData.append('date', date);
        formData.append('time', time);

        fetch(`${URLROOT}/suppliermanager/scheduleInspection`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // Reload to show updated data
            } else {
                alert('Failed to schedule inspection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while scheduling the inspection');
        });
    }

    function updateStatus(requestId, status) {
        const formData = new FormData();
        formData.append('request_id', requestId);
        formData.append('status', status);

        fetch(`${URLROOT}/suppliermanager/updateInspectionStatus`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update the row's appearance based on the new status
                const row = document.querySelector(`select[onchange*="${requestId}"]`).closest('tr');
                updateRowAppearance(row, status);
            } else {
                alert('Failed to update status');
                // Revert the select to its previous value
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
            location.reload();
        });
    }

    function updateRowAppearance(row, status) {
        // Remove any existing status-related classes
        row.classList.remove('status-pending', 'status-cancelled', 'status-completed');
        
        // Add the new status class
        row.classList.add(`status-${status}`);
        
        // You can add additional visual updates here if needed
    }

    // Modify the existing submitSchedule function
    function submitSchedule(requestId) {
        const date = document.getElementById(`date-${requestId}`).value;
        const time = document.getElementById(`time-${requestId}`).value;
        
        if (!date || !time) {
            alert('Please select both date and time');
            return;
        }

        const formData = new FormData();
        formData.append('request_id', requestId);
        formData.append('date', date);
        formData.append('time', time);

        fetch(`${URLROOT}/suppliermanager/scheduleInspection`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // Reload to show updated data
            } else {
                alert('Failed to schedule inspection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while scheduling the inspection');
        });
    }
</script>

<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 500;
    }

    .status-badge.pending {
        background-color: #ff9800;
        color: white;
    }

    .status-badge.approved {
        background-color: #4CAF50;
        color: white;
    }

    .status-badge.rejected {
        background-color: #f44336;
        color: white;
    }

    .btn-view, .btn-approve, .btn-reject {
        padding: 5px 10px;
        border-radius: 4px;
        margin: 0 2px;
        text-decoration: none;
        font-size: 0.9em;
    }

    .btn-view {
        background-color: #007bff;
        color: white;
    }

    .btn-approve {
        background-color: #4CAF50;
        color: white;
    }

    .btn-reject {
        background-color: #f44336;
        color: white;
    }

    .btn-view:hover, .btn-approve:hover, .btn-reject:hover {
        opacity: 0.8;
    }

    .table-data .order {
        background: var(--light);
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-data .head {
        display: flex;
        align-items: center;
        margin-bottom: 24px;
    }

    .table-data .head h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
    }

    .table-data table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-data table th {
        padding: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        text-align: left;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
    }

    .table-data table td {
        padding: 12px;
        font-size: 0.9rem;
        color: var(--dark);
        border-bottom: 1px solid #eee;
    }

    .table-data table tr:hover {
        background: #f8f9fa;
    }

    .btn-confirm {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        margin: 0 2px;
        text-decoration: none;
        font-size: 0.9em;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-confirm:hover {
        opacity: 0.8;
    }

    .btn-confirm i {
        font-size: 1.1rem;
    }

    .head-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .filter-select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
        color: var(--dark);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-buttons button {
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 0.9em;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }

    .action-buttons .btn-view {
        background-color: #007bff;
        color: white;
    }

    .action-buttons .btn-approve {
        background-color: #4CAF50;
        color: white;
    }

    .action-buttons .btn-reject {
        background-color: #f44336;
        color: white;
    }

    .action-buttons button:hover {
        opacity: 0.8;
    }

    .status-badge.scheduled {
        background-color: #4CAF50;
        color: white;
    }

    .status-badge.completed {
        background-color: #4CAF50;
        color: white;
    }

    .status-select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
        background-color: white;
        font-size: 0.9em;
    }

    .status-select:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    .status-select option {
        padding: 5px;
    }
</style>

