<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Salary Management</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Salary</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn-download" onclick="generatePayroll()">
                <i class='bx bxs-file-pdf'></i>
                <span class="text">Generate Payroll</span>
            </button>
            <button class="btn-add" onclick="showSalaryModal()">
                <i class='bx bx-plus'></i>
                <span class="text">Update Salary</span>
            </button>
        </div>
    </div>

    <!-- Salary Overview -->
    <div class="salary-stats">
        <div class="stat-card total">
            <i class='bx bx-money'></i>
            <div class="stat-info">
                <div class="stat-value">Rs. <?php echo number_format($data['stats']['total_payroll'], 2); ?></div>
                <div class="stat-label">Monthly Payroll</div>
            </div>
        </div>
        <div class="stat-card average">
            <i class='bx bx-line-chart'></i>
            <div class="stat-info">
                <div class="stat-value">Rs. <?php echo number_format($data['stats']['avg_salary'], 2); ?></div>
                <div class="stat-label">Average Salary</div>
            </div>
        </div>
        <div class="stat-card pending">
            <i class='bx bx-time'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['pending_payments']; ?></div>
                <div class="stat-label">Pending Payments</div>
            </div>
        </div>
    </div>

    <!-- Salary List -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Employee Salaries</h3>
                <div class="filter-group">
                    <select id="monthFilter" onchange="filterSalaries()">
                        <?php foreach(range(1, 12) as $month): ?>
                            <option value="<?php echo $month; ?>" 
                                    <?php echo date('n') == $month ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $month, 1)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="yearFilter" onchange="filterSalaries()">
                        <?php foreach(range(date('Y'), date('Y')-2) as $year): ?>
                            <option value="<?php echo $year; ?>">
                                <?php echo $year; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="departmentFilter" onchange="filterSalaries()">
                        <option value="">All Departments</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <table id="salaryTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['salaries'] as $salary): ?>
                    <tr data-id="<?php echo $salary->salary_id; ?>">
                        <td>
                            <div class="employee-info">
                                <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $salary->user_id; ?>.jpg" 
                                     onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                                <div>
                                    <p class="name"><?php echo $salary->first_name . ' ' . $salary->last_name; ?></p>
                                    <p class="emp-id">ID: <?php echo $salary->employee_id; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $salary->department; ?></td>
                        <td>Rs. <?php echo number_format($salary->basic_salary, 2); ?></td>
                        <td>Rs. <?php echo number_format($salary->allowances, 2); ?></td>
                        <td>Rs. <?php echo number_format($salary->deductions, 2); ?></td>
                        <td>Rs. <?php echo number_format($salary->net_salary, 2); ?></td>
                        <td>
                            <span class="status <?php echo strtolower($salary->payment_status); ?>">
                                <?php echo $salary->payment_status; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewSalaryDetails(<?php echo $salary->salary_id; ?>)" 
                                        class="btn-view" title="View Details">
                                    <i class='bx bx-detail'></i>
                                </button>
                                <button onclick="generatePayslip(<?php echo $salary->salary_id; ?>)" 
                                        class="btn-download" title="Generate Payslip">
                                    <i class='bx bx-download'></i>
                                </button>
                                <?php if($salary->payment_status == 'Pending'): ?>
                                <button onclick="markAsPaid(<?php echo $salary->salary_id; ?>)" 
                                        class="btn-approve" title="Mark as Paid">
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

    <!-- Salary Update Modal -->
    <div id="salaryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Salary</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="salaryForm" onsubmit="return handleSalarySubmit(event)">
                    <input type="hidden" id="salaryId">
                    <div class="form-group">
                        <label for="employeeId">Employee *</label>
                        <select id="employeeId" required>
                            <option value="">Select Employee</option>
                            <?php foreach($data['employees'] as $employee): ?>
                                <option value="<?php echo $employee->user_id; ?>">
                                    <?php echo $employee->first_name . ' ' . $employee->last_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="basicSalary">Basic Salary *</label>
                            <input type="number" id="basicSalary" required min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="allowances">Allowances</label>
                            <input type="number" id="allowances" min="0" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deductions">Deductions</label>
                        <input type="number" id="deductions" min="0" step="0.01" value="0">
                    </div>
                    <div class="form-group">
                        <label for="effectiveDate">Effective Date *</label>
                        <input type="date" id="effectiveDate" required>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks"></textarea>
                    </div>
                    <button type="submit" class="btn-save">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
let salaryTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    salaryTable = DataTableUtils.init('#salaryTable', {
        order: [[0, 'asc']], // Sort by employee name
        columns: [
            { orderable: false }, // Employee
            null,                 // Department
            null,                 // Basic Salary
            null,                 // Allowances
            null,                 // Deductions
            null,                 // Net Salary
            null,                 // Status
            { orderable: false }  // Actions
        ]
    });
});

async function handleSalarySubmit(event) {
    event.preventDefault();
    
    const salaryData = {
        id: document.getElementById('salaryId').value,
        employee_id: document.getElementById('employeeId').value,
        basic_salary: document.getElementById('basicSalary').value,
        allowances: document.getElementById('allowances').value,
        deductions: document.getElementById('deductions').value,
        effective_date: document.getElementById('effectiveDate').value,
        remarks: document.getElementById('remarks').value
    };

    try {
        const response = await AjaxUtils.post(`${URLROOT}/employeemanager/updateSalary`, salaryData);
        
        if (response.success) {
            AjaxUtils.showSuccess('Salary updated successfully');
            document.getElementById('salaryModal').style.display = 'none';
            loadSalaries();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ... (Add other necessary JavaScript functions)
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 