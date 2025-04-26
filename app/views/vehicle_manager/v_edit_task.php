<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href="<?php echo URLROOT; ?>/public/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<main>
    <div class="edit-task-container">
        <h2><?php echo empty($data['task']->task_id) ? 'Create Task' : 'Edit Task'; ?></h2>
        <div class="form-card">
            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
            <?php endif; ?>
            <form action="<?php echo URLROOT; ?>/manager/<?php echo empty($data['task']->task_id) ? 'createTask' : 'updateTask'; ?>" method="POST">
                <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($data['task']->task_id); ?>">
                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($data['task']->title); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="6"><?php echo htmlspecialchars($data['task']->description); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="priority">Priority <span class="required">*</span></label>
                    <select id="priority" name="priority" class="form-control" required>
                        <option value="Low" <?php echo $data['task']->priority == 'Low' ? 'selected' : ''; ?>>Low</option>
                        <option value="Medium" <?php echo $data['task']->priority == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="High" <?php echo $data['task']->priority == 'High' ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control" value="<?php echo htmlspecialchars($data['task']->due_date); ?>">
                </div>
                <div class="button-row">
                    <button type="submit" class="btn btn-primary">
                        <?php echo empty($data['task']->task_id) ? 'Create' : 'Update'; ?>
                    </button>
                    <a href="<?php echo URLROOT; ?>/manager/tasks" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
.edit-task-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 0 20px;
}

.edit-task-container h2 {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.form-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.form-group .required {
    color: #e53e3e;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group select,
.form-group input[type="date"] {
    background: #fff;
}

.button-row {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.btn-primary {
    background-color: #007664;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
}

@media (max-width: 600px) {
    .edit-task-container {
        margin: 20px auto;
        padding: 0 15px;
    }
    .form-card {
        padding: 15px;
    }
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>