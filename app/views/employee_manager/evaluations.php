<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Employee Evaluations</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Evaluations</a></li>
            </ul>
        </div>
        <button class="btn-add" onclick="showNewEvaluationModal()">
            <i class='bx bx-plus'></i>
            <span class="text">New Evaluation</span>
        </button>
    </div>

    <!-- Evaluation Statistics -->
    <div class="evaluation-stats">
        <div class="stat-card pending">
            <i class='bx bx-time'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['pending']; ?></div>
                <div class="stat-label">Pending Reviews</div>
            </div>
        </div>
        <div class="stat-card completed">
            <i class='bx bx-check-circle'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['completed']; ?></div>
                <div class="stat-label">Completed This Month</div>
            </div>
        </div>
        <div class="stat-card average">
            <i class='bx bx-star'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo number_format($data['stats']['avg_rating'], 1); ?></div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
    </div>

    <!-- Evaluation List -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Evaluation Records</h3>
                <div class="filter-group">
                    <select id="departmentFilter" onchange="filterEvaluations()">
                        <option value="">All Departments</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="periodFilter" onchange="filterEvaluations()">
                        <option value="">All Periods</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annual">Annual</option>
                    </select>
                </div>
            </div>

            <table id="evaluationTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Period</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Review Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['evaluations'] as $eval): ?>
                    <tr data-id="<?php echo $eval->evaluation_id; ?>">
                        <td>
                            <div class="employee-info">
                                <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $eval->user_id; ?>.jpg" 
                                     onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                                <div>
                                    <p class="name"><?php echo $eval->first_name . ' ' . $eval->last_name; ?></p>
                                    <p class="position"><?php echo $eval->position; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $eval->department; ?></td>
                        <td><?php echo ucfirst($eval->evaluation_period); ?></td>
                        <td>
                            <div class="rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class='bx <?php echo $i <= $eval->rating ? 'bxs-star' : 'bx-star'; ?>'></i>
                                <?php endfor; ?>
                                <span>(<?php echo $eval->rating; ?>)</span>
                            </div>
                        </td>
                        <td>
                            <span class="status <?php echo strtolower($eval->status); ?>">
                                <?php echo $eval->status; ?>
                            </span>
                        </td>
                        <td data-sort="<?php echo $eval->review_date; ?>">
                            <?php echo date('M d, Y', strtotime($eval->review_date)); ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewEvaluation(<?php echo $eval->evaluation_id; ?>)" 
                                        class="btn-view" title="View Details">
                                    <i class='bx bx-detail'></i>
                                </button>
                                <?php if($eval->status == 'Pending'): ?>
                                <button onclick="editEvaluation(<?php echo $eval->evaluation_id; ?>)" 
                                        class="btn-edit" title="Edit Evaluation">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <?php endif; ?>
                                <button onclick="downloadEvaluation(<?php echo $eval->evaluation_id; ?>)" 
                                        class="btn-download" title="Download Report">
                                    <i class='bx bx-download'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Evaluation Form Modal -->
    <div id="evaluationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">New Evaluation</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="evaluationForm" onsubmit="return handleEvaluationSubmit(event)">
                    <input type="hidden" id="evaluationId">
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
                    
                    <!-- Performance Criteria -->
                    <div class="criteria-section">
                        <h3>Performance Criteria</h3>
                        <div class="criteria-grid">
                            <div class="criteria-item">
                                <label>Work Quality</label>
                                <div class="rating-input">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                    <input type="radio" name="quality_rating" value="<?php echo $i; ?>" required>
                                    <label><i class='bx bx-star'></i></label>
                                    <?php endfor; ?>
                                </div>
                                <textarea placeholder="Comments..." name="quality_comments"></textarea>
                            </div>
                            
                            <!-- Add more criteria items -->
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="evaluationPeriod">Evaluation Period *</label>
                            <select id="evaluationPeriod" required>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="annual">Annual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reviewDate">Review Date *</label>
                            <input type="date" id="reviewDate" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="overallComments">Overall Comments</label>
                        <textarea id="overallComments" rows="4"></textarea>
                    </div>

                    <button type="submit" class="btn-save">Save Evaluation</button>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
/* Add your CSS styles here */
</style>

<script>
let evaluationTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    evaluationTable = DataTableUtils.init('#evaluationTable', {
        order: [[5, 'desc']], // Sort by review date
        columns: [
            { orderable: false }, // Employee
            null,                 // Department
            null,                 // Period
            null,                 // Rating
            null,                 // Status
            { type: 'date' },     // Review Date
            { orderable: false }  // Actions
        ]
    });

    // Initialize rating system
    initializeRatingSystem();
});

function initializeRatingSystem() {
    document.querySelectorAll('.rating-input').forEach(container => {
        const stars = container.querySelectorAll('i.bx');
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                stars.forEach((s, i) => {
                    s.classList.toggle('bxs-star', i <= index);
                    s.classList.toggle('bx-star', i > index);
                });
                container.querySelector('input[type="radio"]').value = index + 1;
            });
        });
    });
}

// ... (Add other necessary JavaScript functions)
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 