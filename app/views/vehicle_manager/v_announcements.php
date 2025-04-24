<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href="<?php echo URLROOT; ?>/public/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<main>
    <div class="announcements-container">
        <div class="announcements-header">
            <h2>Announcements</h2>
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" id="announcement-search" placeholder="Search announcements...">
                    <i class="bx bx-search"></i>
                </div>
                <a href="<?php echo URLROOT; ?>/manager/createAnnouncement" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Create Announcement
                </a>
            </div>
        </div>

        <!-- Debug: Print URLROOT (comment out in production) -->
        <!-- <p>URLROOT: <?php echo htmlspecialchars(URLROOT); ?></p> -->

        <?php if (empty($data['announcements'])): ?>
            <div class="no-announcements">
                <i class="bx bx-message-square-dots"></i>
                <p>No announcements found.</p>
            </div>
        <?php else: ?>
            <div class="announcements-list">
                <?php foreach ($data['announcements'] as $announcement): ?>
                    <div class="announcement-item" data-announcement-id="<?php echo $announcement->announcement_id; ?>">
                        <?php if (!empty($announcement->banner) && file_exists('public/uploads/announcements/' . $announcement->banner)): ?>
                            <div class="announcement-banner">
                                <img src="<?php echo URLROOT . '/public/public/uploads/announcements/' . htmlspecialchars($announcement->banner); ?>" alt="Banner" style="max-width: 100%; border-radius: 8px; margin-bottom: 10px;">
                            </div>
                        <?php else: ?>
                            <!-- Debug: Banner not found or invalid -->
                            <!-- <p>Banner not found for ID <?php echo $announcement->announcement_id; ?>: <?php echo htmlspecialchars($announcement->banner ?? 'NULL'); ?></p> -->
                        <?php endif; ?>
                        <div class="announcement-header">
                            <h4><?php echo htmlspecialchars($announcement->title); ?></h4>
                            <span class="announcement-date">
                                <i class="bx bx-calendar"></i>
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
                            <a href="<?php echo URLROOT; ?>/manager/editAnnouncement/<?php echo $announcement->announcement_id; ?>" class="btn btn-sm btn-warning edit-announcement-btn">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <button class="delete-announcement-btn btn btn-sm btn-danger">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Confirm Action</h3>
            <p>Are you sure you want to delete this announcement?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn btn-danger">OK</button>
                <button id="cancelDelete" class="btn btn-success">Cancel</button>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
const URLROOT = '<?php echo URLROOT; ?>';

// Handle Delete Announcement
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('delete-announcement-btn') || e.target.closest('.delete-announcement-btn')) {
        const announcementItem = e.target.closest('.announcement-item');
        const announcementId = announcementItem.dataset.announcementId;
        const confirmModal = document.getElementById('confirmModal');
        const resultModal = document.getElementById('resultModal');
        const resultTitle = document.getElementById('resultModalTitle');
        const resultMessage = document.getElementById('resultModalMessage');
        const resultClose = document.getElementById('resultModalClose');

        confirmModal.style.display = 'flex';

        document.getElementById('confirmDelete').onclick = () => {
            fetch(`${URLROOT}/manager/deleteAnnouncement`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ announcement_id: announcementId })
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                resultTitle.textContent = data.success ? 'Success' : 'Error';
                resultMessage.textContent = data.message || (data.success ? 'Announcement deleted successfully!' : 'Failed to delete announcement.');
                resultClose.className = `btn ${data.success ? 'btn-success' : 'btn-danger'}`; // Green for success, red for error
                resultModal.style.display = 'flex';
                if (data.success) setTimeout(() => window.location.reload(), 2000); // Reload after 2 seconds on success
            })
            .catch(error => {
                console.error('Error deleting announcement:', error);
                resultTitle.textContent = 'Error';
                resultMessage.textContent = 'An error occurred while deleting the announcement.';
                resultClose.className = 'btn btn-danger';
                resultModal.style.display = 'flex';
            });

            confirmModal.style.display = 'none';
        };

        document.getElementById('cancelDelete').onclick = () => {
            confirmModal.style.display = 'none';
        };

        // Close the result modal
        resultClose.onclick = () => {
            resultModal.style.display = 'none';
        };
    }
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


// Handle Delete Announcement
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('delete-announcement-btn') || e.target.closest('.delete-announcement-btn')) {
        const announcementItem = e.target.closest('.announcement-item');
        const announcementId = announcementItem.dataset.announcementId;
        const confirmModal = document.getElementById('confirmModal');

        confirmModal.style.display = 'flex';

        document.getElementById('confirmDelete').onclick = () => {
            fetch(`${URLROOT}/manager/deleteAnnouncement`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ announcement_id: announcementId })
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                // Reload the page regardless of success or failure
                window.location.reload();
            })
            .catch(error => {
                console.error('Error deleting announcement:', error);
                // Reload the page even if there's an error
                window.location.reload();
            });

            confirmModal.style.display = 'none';
        };

        document.getElementById('cancelDelete').onclick = () => {
            confirmModal.style.display = 'none';
        };
    }
});

</script>

<style>
@font-face {
    font-family: 'boxicons';
    src: url('<?php echo URLROOT; ?>/public/boxicons/fonts/boxicons.woff2') format('woff2'),
         url('<?php echo URLROOT; ?>/public/boxicons/fonts/boxicons.woff') format('woff'),
         url('<?php echo URLROOT; ?>/public/boxicons/fonts/boxicons.ttf') format('truetype');
    font-display: swap;
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
    border-color:#007664;
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
    color: #007664;
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

.edit-announcement-btn {
    background-color: #007664;
    color: white;
}
.delete-announcement-btn {
    background-color: #d9534f;
    color: white;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    width: 400px;
    text-align: center;
}

.modal-content h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.modal-content p {
    font-size: 16px;
    color: #555;
    margin-bottom: 20px;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-danger {
    background-color: #d9534f;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success {
    background-color: #007664;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
</style>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>