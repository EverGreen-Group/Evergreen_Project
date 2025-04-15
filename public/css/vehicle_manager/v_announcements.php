<!-- app/views/vehicle_manager/v_announcements.php -->
<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container">
    <h1><?php echo $data['page_title']; ?></h1>
    
    <!-- Create Announcement Form -->
    <div class="card mb-3">
        <div class="card-body">
            <h3>Create Announcement</h3>
            <form id="announcement-form">
                <div class="mb-3">
                    <input type="text" class="form-control" id="announcement-title" placeholder="Title">
                </div>
                <div class="mb-3">
                    <textarea class="form-control" id="announcement-content" placeholder="Content"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

    <!-- Announcements List -->
    <div id="announcements-list">
        <?php foreach ($data['announcements'] as $announcement): ?>
            <div class="card mb-2" data-announcement-id="<?php echo $announcement->announcement_id; ?>">
                <div class="card-body">
                    <h5><?php echo $announcement->title; ?></h5>
                    <p><?php echo $announcement->content; ?></p>
                    <small>Created: <?php echo $announcement->created_at; ?></small>
                    <button class="btn btn-sm btn-warning edit-announcement">Edit</button>
                    <button class="btn btn-sm btn-danger delete-announcement">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Create announcement
    document.getElementById('announcement-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const title = document.getElementById('announcement-title').value;
        const content = document.getElementById('announcement-content').value;
        
        fetch('<?php echo URLROOT; ?>/manager/createAnnouncement', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title: title, content: content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh to show new announcement
            }
        });
    });

    // Edit announcement
    document.querySelectorAll('.edit-announcement').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.parentElement.parentElement.dataset.announcementId;
            fetch('<?php echo URLROOT; ?>/manager/getAnnouncement/' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newTitle = prompt('Enter new title:', data.announcement.title);
                    const newContent = prompt('Enter new content:', data.announcement.content);
                    if (newTitle && newContent) {
                        fetch('<?php echo URLROOT; ?>/manager/updateAnnouncement', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ 
                                announcement_id: id, 
                                title: newTitle, 
                                content: newContent 
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) location.reload();
                        });
                    }
                }
            });
        });
    });

    // Delete announcement
    document.querySelectorAll('.delete-announcement').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.parentElement.parentElement.dataset.announcementId;
            if (confirm('Are you sure you want to delete this announcement?')) {
                fetch('<?php echo URLROOT; ?>/manager/deleteAnnouncement', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ announcement_id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                });
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>