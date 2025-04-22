<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href="<?php echo URLROOT; ?>/public/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
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
</main>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
const URLROOT = '<?php echo URLROOT; ?>';

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
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                Toastify({
                    text: data.message || (data.success ? 'Announcement deleted successfully!' : 'Failed to delete announcement.'),
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: data.success ? '#28a745' : '#d9534f',
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '16px',
                        fontWeight: '500',
                        padding: '12px 20px',
                        borderRadius: '8px',
                        boxShadow: '0 4px 10px rgba(0, 0, 0, 0.2)'
                    }
                }).showToast();
                if (data.success) {
                    setTimeout(() => window.location.reload(), 1000);
                }
            })
            .catch(error => {
                console.error('Error deleting announcement:', error);
                Toastify({
                    text: 'An error occurred while deleting the announcement.',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#d9534f',
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '16px',
                        fontWeight: '500',
                        padding: '12px 20px',
                        borderRadius: '8px',
                        boxShadow: '0 4px 10px rgba(0, 0, 0, 0.2)'
                    }
                }).showToast();
            });
        }
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

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['flash']['message'])): ?>
    Toastify({
        text: '<?php echo addslashes($_SESSION['flash']['message']['message']); ?>',
        duration: 3000,
        gravity: 'topCopy to clipboard
        gravity: 'top',
        position: 'right',
        backgroundColor: '<?php echo strpos($_SESSION['flash']['message']['class'], 'success') !== false ? '#28a745' : '#d9534f'; ?>',
        style: {
            fontFamily: 'Poppins, sans-serif',
            fontSize: '16px',
            fontWeight: '500',
            padding: '12px 20px',
            borderRadius: '8px',
            boxShadow: '0 4px 10px rgba(0, 0, 0, 0.2)'
        }
    }).showToast();
    <?php unset($_SESSION['flash']['message']); ?>
<?php endif; ?>
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

.edit-announcement-btn {
    background-color: #007664;
    color: white;
}
.delete-announcement-btn {
    background-color: #d9534f;
    color: white;
}
</style>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>