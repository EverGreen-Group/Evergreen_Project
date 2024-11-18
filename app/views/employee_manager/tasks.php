<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_employee_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Task Management</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/employeemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Tasks</a></li>
            </ul>
        </div>
        <button class="btn-add" onclick="showAddTaskModal()">
            <i class='bx bx-plus'></i>
            <span class="text">Add New Task</span>
        </button>
    </div>

    <!-- Task Overview -->
    <div class="task-stats">
        <div class="stat-card pending">
            <i class='bx bx-time-five'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['pending']; ?></div>
                <div class="stat-label">Pending Tasks</div>
            </div>
        </div>
        <div class="stat-card progress">
            <i class='bx bx-loader-circle'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['in_progress']; ?></div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        <div class="stat-card completed">
            <i class='bx bx-check-circle'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['completed']; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        <div class="stat-card overdue">
            <i class='bx bx-error-circle'></i>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['stats']['overdue']; ?></div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>
    </div>

    <!-- Task List -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Task List</h3>
                <div class="filter-group">
                    <select id="statusFilter" onchange="filterTasks()">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                    <select id="priorityFilter" onchange="filterTasks()">
                        <option value="">All Priority</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
            </div>

            <table id="taskTable">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['tasks'] as $task): ?>
                    <tr data-id="<?php echo $task->task_id; ?>">
                        <td>
                            <div class="task-info">
                                <h4><?php echo $task->title; ?></h4>
                                <p><?php echo strlen($task->description) > 50 ? substr($task->description, 0, 50) . '...' : $task->description; ?></p>
                            </div>
                        </td>
                        <td>
                            <div class="employee-info">
                                <img src="<?php echo URLROOT; ?>/img/profile/<?php echo $task->user_id; ?>.jpg" 
                                     onerror="this.src='<?php echo URLROOT; ?>/img/profile/default.jpg'">
                                <p><?php echo $task->first_name . ' ' . $task->last_name; ?></p>
                            </div>
                        </td>
                        <td>
                            <span class="priority <?php echo strtolower($task->priority); ?>">
                                <?php echo $task->priority; ?>
                            </span>
                        </td>
                        <td data-sort="<?php echo $task->due_date; ?>">
                            <?php echo date('M d, Y', strtotime($task->due_date)); ?>
                        </td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo $task->progress; ?>%"></div>
                                <span><?php echo $task->progress; ?>%</span>
                            </div>
                        </td>
                        <td>
                            <span class="status <?php echo strtolower($task->status); ?>">
                                <?php echo $task->status; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewTaskDetails(<?php echo $task->task_id; ?>)" 
                                        class="btn-view" title="View Details">
                                    <i class='bx bx-detail'></i>
                                </button>
                                <button onclick="editTask(<?php echo $task->task_id; ?>)" 
                                        class="btn-edit" title="Edit Task">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button onclick="deleteTask(<?php echo $task->task_id; ?>)" 
                                        class="btn-delete" title="Delete Task">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Task Modal -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Task</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="taskForm" onsubmit="return handleTaskSubmit(event)">
                    <input type="hidden" id="taskId">
                    <div class="form-group">
                        <label for="title">Task Title *</label>
                        <input type="text" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="assignedTo">Assign To *</label>
                            <select id="assignedTo" required>
                                <option value="">Select Employee</option>
                                <?php foreach($data['employees'] as $employee): ?>
                                    <option value="<?php echo $employee->user_id; ?>">
                                        <?php echo $employee->first_name . ' ' . $employee->last_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority">Priority *</label>
                            <select id="priority" required>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dueDate">Due Date *</label>
                            <input type="date" id="dueDate" required>
                        </div>
                        <div class="form-group">
                            <label for="progress">Progress</label>
                            <input type="number" id="progress" min="0" max="100" value="0">
                        </div>
                    </div>
                    <button type="submit" class="btn-save">Save Task</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    <div id="taskDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Task Details</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</main>

<style>
/* Add your CSS styles here */
</style>

<script>
let taskTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    taskTable = DataTableUtils.init('#taskTable', {
        order: [[3, 'asc']], // Sort by due date
        columns: [
            null,                 // Task
            { orderable: false }, // Assigned To
            null,                 // Priority
            { type: 'date' },     // Due Date
            null,                 // Progress
            null,                 // Status
            { orderable: false }  // Actions
        ]
    });
});

async function handleTaskSubmit(event) {
    event.preventDefault();
    
    const taskData = {
        id: document.getElementById('taskId').value,
        title: document.getElementById('title').value,
        description: document.getElementById('description').value,
        assigned_to: document.getElementById('assignedTo').value,
        priority: document.getElementById('priority').value,
        due_date: document.getElementById('dueDate').value,
        progress: document.getElementById('progress').value
    };

    try {
        const response = await AjaxUtils.post(`${URLROOT}/employeemanager/saveTask`, taskData);
        
        if (response.success) {
            AjaxUtils.showSuccess('Task saved successfully');
            document.getElementById('taskModal').style.display = 'none';
            loadTasks();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadTasks() {
    try {
        const status = document.getElementById('statusFilter').value;
        const priority = document.getElementById('priorityFilter').value;
        
        const response = await AjaxUtils.get(`${URLROOT}/employeemanager/getTasks`, {
            status: status,
            priority: priority
        });

        if (response.success) {
            // Update table data
            taskTable.clear().rows.add(response.data).draw();
            // Update statistics
            updateStats(response.stats);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// ... (Add other necessary JavaScript functions)
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?> 