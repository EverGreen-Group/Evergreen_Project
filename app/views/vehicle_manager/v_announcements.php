<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<main>
    <div class="announcements-container">
        <div class="announcements-header">
            <h2>Announcements</h2>
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" id="announcement-search" placeholder="Search announcements...">
                    <i class='bx bx-search'></i>
                </div>
                <button id="create-announcement-btn" class="btn btn-primary">
                    <i class='bx bx-plus'></i> Create Announcement
                </button>
            </div>
        </div>

        <?php if (empty($data['announcements'])): ?>
            <div class="no-announcements">
                <i class='bx bx-message-square-dots'></i>
                <p>No announcements found.</p>
            </div>
        <?php else: ?>
            <div class="announcements-list">
                <?php foreach ($data['announcements'] as $announcement): ?>
                    <div class="announcement-item" data-announcement-id="<?php echo $announcement->announcement_id; ?>">
                        <div class="announcement-header">
                            <h4><?php echo htmlspecialchars($announcement->title); ?></h4>
                            <span class="announcement-date">
                                <i class='bx bx-calendar'></i>
                                <?php echo date('F j, Y, g:i a', strtotime($announcement->created_at)); ?>
                                <?php if ($announcement->updated_at): ?>
                                    <small>(Updated: <?php echo date('F j, Y, g:i a', strtotime($announcement->updated_at)); ?>)</small>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="announcement-body">
                            <p><?php echo htmlspecialchars($announcement->content); ?></p>
                        </div>
                        <div class="announcement-footer">
                            <button class="edit-announcement-btn btn btn-sm btn-warning">
                                <i class='bx bx-edit'></i> Edit
                            </button>
                            <button class="delete-announcement-btn btn btn-sm btn-danger">
                                <i class='bx bx-trash'></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Create Announcement Modal -->
    <div class="create-announcement-modal" style="display: none;">
        <div class="modal-content">
            <h3>Create Announcement <span class="close-modal" style="float: right; cursor: pointer;">×</span></h3>
            <form id="create-announcement-form">
                <div class="form-group">
                    <label for="create-title">Title</label>
                    <input type="text" id="create-title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="create-content">Content</label>
                    <textarea id="create-content" class="form-control" rows="5" required></textarea>
                </div>
                <div class="button-row">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
                
            </form>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div class="edit-announcement-modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Announcement <span class="close-modal" style="float: right; cursor: pointer;">×</span></h3>
            <form id="edit-announcement-form">
                <input type="hidden" id="edit-announcement-id">
                <div class="form-group">
                    <label for="edit-title">Title</label>
                    <input type="text" id="edit-title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit-content">Content</label>
                    <textarea id="edit-content" class="form-control" rows="5" required></textarea>
                </div>
                <div class="button-row">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
                
            </form>
        </div>
    </div>
</main>

<script>
const URLROOT = '<?php echo URLROOT; ?>';

// Show Create Announcement Modal
document.getElementById('create-announcement-btn')?.addEventListener('click', function() {
    document.querySelector('.create-announcement-modal').style.display = 'block';
});

// Handle Create Announcement Form Submission
document.getElementById('create-announcement-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = document.getElementById('create-title').value.trim();
    const content = document.getElementById('create-content').value.trim();

    if (!title || !content) {
        alert('Please fill in all fields.');
        return;
    }

    fetch(`${URLROOT}/manager/createAnnouncement`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Announcement created successfully!');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to create announcement.');
        }
    })
    .catch(error => {
        console.error('Error creating announcement:', error);
        alert('An error occurred while creating the announcement.');
    });
});

// Handle Edit Announcement
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-announcement-btn') || e.target.closest('.edit-announcement-btn')) {
        const announcementItem = e.target.closest('.announcement-item');
        const announcementId = announcementItem.dataset.announcementId;

        fetch(`${URLROOT}/manager/getAnnouncement/${announcementId}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit-announcement-id').value = announcementId;
                document.getElementById('edit-title').value = data.announcement.title;
                document.getElementById('edit-content').value = data.announcement.content;
                document.querySelector('.edit-announcement-modal').style.display = 'block';
            } else {
                alert(data.message || 'Failed to fetch announcement.');
            }
        })
        .catch(error => {
            console.error('Error fetching announcement:', error);
            alert('An error occurred while fetching the announcement.');
        });
    }
});

// Handle Edit Announcement Form Submission
document.getElementById('edit-announcement-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const announcementId = document.getElementById('edit-announcement-id').value;
    const title = document.getElementById('edit-title').value.trim();
    const content = document.getElementById('edit-content').value.trim();

    if (!title || !content) {
        alert('Please fill in all fields.');
        return;
    }

    fetch(`${URLROOT}/manager/updateAnnouncement`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ announcement_id: announcementId, title, content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Announcement updated successfully!');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to update announcement.');
        }
    })
    .catch(error => {
        console.error('Error updating announcement:', error);
        alert('An error occurred while updating the announcement.');
    });
});

// Handle Delete Announcement
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-announcement-btn') || e.target.closest('.delete-announcement-btn')) {
        if (confirm('Are you sure you want to delete this announcement?')) {
            const announcementItem = e.target.closest('.announcement-item');
            const announcementId = announcementItem.dataset.announcementId;

            fetch(`${URLROOT}/manager/deleteAnnouncement`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ announcement_id: announcementId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Announcement deleted successfully!');
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete announcement.');
                }
            })
            .catch(error => {
                console.error('Error deleting announcement:', error);
                alert('An error occurred while deleting the announcement.');
            });
        }
    }
});

// Close Modals
document.querySelectorAll('.close-modal').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelector('.create-announcement-modal').style.display = 'none';
        document.querySelector('.edit-announcement-modal').style.display = 'none';
    });
});

// Search Announcements
document.getElementById('announcement-search')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.announcement-item').forEach(item => {
        const title = item.querySelector('h4')?.textContent.toLowerCase() || '';
        const content = item.querySelector('.announcement-body p')?.textContent.toLowerCase() || '';
        item.style.display = 
            title.includes(searchTerm) || content.includes(searchTerm) 
            ? 'block' : 'none';
    });
});
</script>

<style>
.button-row{
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.announcements-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.announcements-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.announcements-header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.search-box {
    position: relative;
    width: 300px;
}

.search-box input {
    width: 100%;
    padding: 10px 40px 10px 15px;
    border: 1px solid #ddd;
    border-radius: 25px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.3s;
}

.search-box input:focus {
    border-color: #28a745;
}

.search-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #777;
}

.no-announcements {
    text-align: center;
    padding: 50px;
    color: #777;
}

.no-announcements i {
    font-size: 48px;
    margin-bottom: 10px;
    color: #28a745;
}

.announcements-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.announcement-item {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s;
}

.announcement-item:hover {
    transform: translateY(-2px);
}

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.announcement-header h4 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.announcement-date {
    font-size: 14px;
    color: #777;
}

.announcement-date i {
    margin-right: 5px;
}

.announcement-body {
    margin-bottom: 10px;
}

.announcement-body p {
    margin: 0;
    color: #555;
    line-height: 1.6;
}

.announcement-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.create-announcement-modal,
.edit-announcement-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
}

.create-announcement-modal .modal-content,
.edit-announcement-modal .modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    width: 500px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
    margin-top: 10%;
}

.create-announcement-modal .form-group,
.edit-announcement-modal .form-group {
    margin-bottom: 15px;
}

.create-announcement-modal label,
.edit-announcement-modal label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.create-announcement-modal input,
.create-announcement-modal textarea,
.edit-announcement-modal input,
.edit-announcement-modal textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.create-announcement-modal textarea,
.edit-announcement-modal textarea {
    resize: vertical;
}

.create-announcement-modal button,
.edit-announcement-modal button {
    margin-right: 10px;
}

.edit-announcement-btn{
    background-color: #007664;
    color: white;
}
.delete-announcement-btn{
    background-color: #d9534f;
    color: white;
}
</style>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>