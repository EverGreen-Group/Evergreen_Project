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

    <!-- Application Constraints -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Application Constraints</h3>
            </div>
            <form id="constraintsForm">
                <table>
                    <thead>
                        <tr>
                            <th>Constraint Type</th>
                            <th>Minimum Value</th>
                            <th>Maximum Value</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Land Area</td>
                            <td><input type="number" name="total_land_area_min" value="20" min="0"></td>
                            <td><input type="number" name="total_land_area_max" value="160" min="0"></td>
                            <td>Perches</td>
                        </tr>
                        <tr>
                            <td>Cultivation Area</td>
                            <td><input type="number" name="cultivation_area_min" value="20" min="0"></td>
                            <td><input type="number" name="cultivation_area_max" value="160" min="0"></td>
                            <td>Perches</td>
                        </tr>
                        <tr>
                            <td>Plant Age</td>
                            <td><input type="number" name="plant_age_min" value="3" min="0"></td>
                            <td><input type="number" name="plant_age_max" value="15" min="0"></td>
                            <td>Years</td>
                        </tr>
                        <tr>
                            <td>Monthly Production</td>
                            <td><input type="number" name="monthly_production_min" value="100" min="0"></td>
                            <td><input type="number" name="monthly_production_max" value="1000" min="0"></td>
                            <td>kg</td>
                        </tr>
                        <tr>
                            <td>Distance from Factory</td>
                            <td><input type="number" name="factory_distance_min" value="0" min="0"></td>
                            <td><input type="number" name="factory_distance_max" value="50" min="0"></td>
                            <td>km</td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class='bx bx-save'></i> Save Changes
                    </button>
                </div>
            </form>
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
                        <th>Request ID</th>
                        <th>Supplier ID</th>
                        <th>Land Area (Acres)</th>
                        <th>Location</th>
                        <th>Preferred Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>INS001</td>
                        <td>SUP001</td>
                        <td>2.5</td>
                        <td>Galle, Sri Lanka</td>
                        <td>2024-03-15</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-approve" onclick="scheduleInspection('INS001')">
                                    <i class='bx bx-calendar'></i> Schedule
                                </button>
                                <button class="btn-view" onclick="viewDetails('INS001')">
                                    <i class='bx bx-detail'></i> Details
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>INS002</td>
                        <td>SUP003</td>
                        <td>1.8</td>
                        <td>Matara, Sri Lanka</td>
                        <td>2024-03-18</td>
                        <td><span class="status-badge scheduled">Scheduled</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-approve" onclick="scheduleInspection('INS002')">
                                    <i class='bx bx-calendar'></i> Schedule
                                </button>
                                <button class="btn-view" onclick="viewDetails('INS002')">
                                    <i class='bx bx-detail'></i> Details
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 500px;
        max-width: 90%;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .close {
        font-size: 24px;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .range-inputs {
        display: flex;
        gap: 10px;
    }

    .range-inputs input {
        width: 50%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .table-data input[type="number"] {
        width: 100px;
        padding: 6px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .table-data input[type="number"]:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .form-actions {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        padding: 0 20px;
    }

    .btn-save {
        background-color: #4CAF50;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9em;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        opacity: 0.9;
    }

    .btn-save i {
        font-size: 1.1em;
    }

    /* Add validation styles */
    .input-error {
        border-color: #f44336 !important;
    }

    .error-message {
        color: #f44336;
        font-size: 0.8em;
        margin-top: 4px;
    }
</style>

<script>
function scheduleInspection(requestId) {
    // Add your scheduling logic here
    console.log('Scheduling inspection for:', requestId);
}

function markComplete(requestId) {
    // Add your completion logic here
    console.log('Marking inspection as complete:', requestId);
}

function viewDetails(requestId) {
    // Add your view details logic here
    console.log('Viewing details for:', requestId);
}

document.querySelector('.filter-select').addEventListener('change', function() {
    // Add your filtering logic here
    const status = this.value;
    console.log('Filtering by status:', status);
});

function editConstraints() {
    // Create modal for editing constraints
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Application Constraints</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="constraintsForm">
                    <div class="form-group">
                        <label>Total Land Area (Acres)</label>
                        <div class="range-inputs">
                            <input type="number" name="land_area_min" placeholder="Minimum" step="0.1" required>
                            <input type="number" name="land_area_max" placeholder="Maximum" step="0.1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cultivation Area (Acres)</label>
                        <div class="range-inputs">
                            <input type="number" name="cultivation_area_min" placeholder="Minimum" step="0.1" required>
                            <input type="number" name="cultivation_area_max" placeholder="Maximum" step="0.1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Plant Age (Years)</label>
                        <div class="range-inputs">
                            <input type="number" name="plant_age_min" placeholder="Minimum" step="0.5" required>
                            <input type="number" name="plant_age_max" placeholder="Maximum" step="0.5" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Monthly Production (kg)</label>
                        <div class="range-inputs">
                            <input type="number" name="production_min" placeholder="Minimum" required>
                            <input type="number" name="production_max" placeholder="Maximum" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Maximum Distance from Factory (km)</label>
                        <input type="number" name="max_distance" placeholder="Maximum distance" required>
                    </div>
                    <button type="submit" class="btn-approve">Save Changes</button>
                </form>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Close modal functionality
    const closeBtn = modal.querySelector('.close');
    closeBtn.onclick = function() {
        modal.remove();
    }

    // Form submission
    const form = modal.querySelector('#constraintsForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        
        fetch(`${URLROOT}/suppliermanager/updateConstraints`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating constraints');
            }
        });
    }
}

document.getElementById('constraintsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    let isValid = true;
    const inputs = this.querySelectorAll('input[type="number"]');
    
    inputs.forEach(input => {
        // Remove any existing error styles
        input.classList.remove('input-error');
        const errorMessage = input.parentElement.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }

        // Validate min/max pairs
        const isMin = input.name.includes('_min');
        const pairName = isMin ? 
            input.name.replace('_min', '_max') : 
            input.name.replace('_max', '_min');
        const pairInput = this.querySelector(`input[name="${pairName}"]`);

        if (isMin && parseFloat(input.value) > parseFloat(pairInput.value)) {
            isValid = false;
            input.classList.add('input-error');
            pairInput.classList.add('input-error');
            
            const error = document.createElement('div');
            error.className = 'error-message';
            error.textContent = 'Minimum value cannot be greater than maximum';
            input.parentElement.appendChild(error);
        }

        // Validate not empty and positive
        if (input.value === '' || parseFloat(input.value) < 0) {
            isValid = false;
            input.classList.add('input-error');
            
            const error = document.createElement('div');
            error.className = 'error-message';
            error.textContent = 'Please enter a valid positive number';
            input.parentElement.appendChild(error);
        }
    });

    if (isValid) {
        // Show success message
        const successMessage = document.createElement('div');
        successMessage.className = 'alert alert-success';
        successMessage.innerHTML = `
            <i class='bx bx-check-circle'></i>
            Constraints updated successfully
        `;
        this.insertBefore(successMessage, this.firstChild);

        // Remove success message after 3 seconds
        setTimeout(() => {
            successMessage.remove();
        }, 3000);

        // Here you would typically save to backend
        console.log('Form submitted successfully');
    }
});
</script>