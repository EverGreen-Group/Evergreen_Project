<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Staff Management</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Staff</a></li>
            </ul>
        </div>
        <button class="btn-add" onclick="showAddEmployeeModal()">
            <i class='bx bx-user-plus'></i>
            <span class="text">Add Employee</span>
        </button>
    </div>

    <!-- Staff Overview -->
    <div class="staff-stats">
        <div class="stat-card total">
            <i class='bx bxs-group'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['total_employees']; ?></div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        <div class="stat-card active">
            <i class='bx bxs-user-check'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['active_employees']; ?></div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card pending">
            <i class='bx bxs-user-detail'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['pending_approvals']; ?></div>
                <div class="stat-label">Pending Approvals</div>
            </div>
        </div>
        <div class="stat-card departments">
            <i class='bx bxs-buildings'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['total_departments']; ?></div>
                <div class="stat-label">Departments</div>
            </div>
        </div>
    </div>

    <!-- Staff List -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Employee List</h3>
                <div class="filter-group">
                    <select id="departmentFilter" onchange="filterEmployees()">
                        <option value="">All Departments</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="statusFilter" onchange="filterEmployees()">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>

            <table id="employeeTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Contact</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['employees'] as $employee): ?>
                    <tr data-id="<?php echo $employee->user_id; ?>">
                        <td>
                            <div class="employee-info">
                                <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $employee->user_id; ?>.jpg" 
                                     onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                                <div>
                                    <p class="name"><?php echo $employee->first_name . ' ' . $employee->last_name; ?></p>
                                    <p class="emp-id">ID: <?php echo $employee->employee_id; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $employee->department; ?></td>
                        <td><?php echo $employee->position; ?></td>
                        <td>
                            <div class="contact-info">
                                <p><?php echo $employee->email; ?></p>
                                <p><?php echo $employee->phone; ?></p>
                            </div>
                        </td>
                        <td data-sort="<?php echo $employee->join_date; ?>">
                            <?php echo date('M d, Y', strtotime($employee->join_date)); ?>
                        </td>
                        <td>
                            <span class="status <?php echo strtolower($employee->status); ?>">
                                <?php echo $employee->status; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewEmployee(<?php echo $employee->user_id; ?>)" 
                                        class="btn-view" title="View Details">
                                    <i class='bx bx-detail'></i>
                                </button>
                                <button onclick="editEmployee(<?php echo $employee->user_id; ?>)" 
                                        class="btn-edit" title="Edit Employee">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <?php if($employee->status == 'Pending'): ?>
                                <button onclick="approveEmployee(<?php echo $employee->user_id; ?>)" 
                                        class="btn-approve" title="Approve">
                                    <i class='bx bx-check'></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Employee Modal -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Employee</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="employeeForm" onsubmit="return handleEmployeeSubmit(event)">
                    <input type="hidden" id="userId">
                    
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3>Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone *</label>
                                <input type="tel" id="phone" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nic">NIC *</label>
                                <input type="text" id="nic" required>
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth *</label>
                                <input type="date" id="dob" required>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Details -->
                    <div class="form-section">
                        <h3>Employment Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="department">Department *</label>
                                <select id="department" required>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="position">Position *</label>
                                <input type="text" id="position" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="joinDate">Join Date *</label>
                                <input type="date" id="joinDate" required>
                            </div>
                            <div class="form-group">
                                <label for="employeeType">Employee Type *</label>
                                <select id="employeeType" required>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-save">Save Employee</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
let employeeTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    employeeTable = DataTableUtils.init('#employeeTable', {
        order: [[0, 'asc']], // Sort by employee name
        columns: [
            { orderable: false }, // Employee
            null,                 // Department
            null,                 // Position
            null,                 // Contact
            { type: 'date' },     // Join Date
            null,                 // Status
            { orderable: false }  // Actions
        ]
    });
});

// ... (Add other necessary JavaScript functions)
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 