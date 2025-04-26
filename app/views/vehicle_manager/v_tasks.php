<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href="<?php echo URLROOT; ?>/public/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<main>
    <div class="filter-box">
        <select id="date-filter" name="date_filter">
            <option value="all">All Announcements</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
        </select>
    </div>
    <div class="tasks-container">
        <div class="tasks-header">
            <h2>Tasks</h2>
            <a href="<?php echo URLROOT; ?>/manager/editTask" class="btn btn-primary">
                <i class="bx bx-plus"></i> Create Task
            </a>
        </div>

        <?php if (isset($_SESSION['flash']['message'])): ?>
            <div class="<?php echo $_SESSION['flash']['message']['class']; ?>">
                <?php echo htmlspecialchars($_SESSION['flash']['message']['message']); ?>
            </div>
            <?php unset($_SESSION['flash']['message']); ?>
        <?php endif; ?>

        <?php if (empty($data['tasks'])): ?>
            <div class="no-tasks">
                <i class="bx bx-task"></i>
                <p>No tasks found.</p>
            </div>
        <?php else: ?>
            <div class="tasks-list">
                <?php foreach ($data['tasks'] as $task): ?>
                    <div class="task-item">
                        <div class="task-header">
                            <h4><?php echo htmlspecialchars($task->title); ?></h4>
                            <span class="task-priority priority-<?php echo strtolower($task->priority); ?>">
                                <?php echo htmlspecialchars($task->priority); ?>
                            </span>
                        </div>
                        <div class="task-body">
                            <p><?php echo htmlspecialchars($task->description ?: 'No description'); ?></p>
                            <?php if ($task->due_date): ?>
                                <p>Due: <?php echo date('F j, Y', strtotime($task->due_date)); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="task-footer">
                            <a href="<?php echo URLROOT; ?>/manager/editTask/<?php echo $task->task_id; ?>" class="btn btn-sm btn-warning">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <a href="<?php echo URLROOT; ?>/manager/deleteTask/<?php echo $task->task_id; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this task?');">
                                <i class="bx bx-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.tasks-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.tasks-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.tasks-header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
}

.no-tasks {
    text-align: center;
    padding: 50px;
    color: #777;
}

.no-tasks i {
    font-size: 48px;
    margin-bottom: 10px;
    color: #007664;
}

.tasks-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.task-item {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.task-header h4 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.task-priority {
    font-size: 14px;
    padding: 2px 8px;
    border-radius: 4px;
}

.priority-low { color: #28a745; }
.priority-medium { color: #ffc107; }
.priority-high { color: #d9534f; }

.task-body p {
    margin: 0 0 10px;
    color: #555;
    line-height: 1.6;
}

.task-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-primary {
    background-color: #007664;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
}

.btn-warning {
    background-color: #ffc107;
    color: #333;
}

.btn-danger {
    background-color: #d9534f;
    color: white;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 14px;
}
</style>

<script>
document.getElementById('date-filter').addEventListener('change', function(e) {
    const filter = e.target.value;
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const weekAgo = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);

    document.querySelectorAll('.announcement-item').forEach(item => {
        const dateText = item.querySelector('.announcement-date').textContent;
        const announcementDate = new Date(dateText.split('(')[0].trim()); // Extract date
        let show = false;

        if (filter === 'all') {
            show = true;
        } else if (filter === 'today' && announcementDate >= today) {
            show = true;
        } else if (filter === 'week' && announcementDate >= weekAgo) {
            show = true;
        }

        item.style.display = show ? 'block' : 'none';
    });
});
</script>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>