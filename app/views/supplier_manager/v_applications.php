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
                <li><a href="#">Dashboard</a></li>
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
                                <a href="<?= URLROOT ?>/suppliermanager/confirmSupplierRole/<?= $application->application_id ?>" 
                                   class="btn-confirm">
                                    <i class='bx bx-user-check'></i> Confirm Role
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

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
                            <td><?php echo htmlspecialchars($inspection->status ?? 'N/A'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-approve" onclick="">
                                        <i class='bx bx-calendar'></i> Schedule
                                    </button>
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
</style>

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
</script>
