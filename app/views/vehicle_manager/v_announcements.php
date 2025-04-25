<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

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

        <?php if (empty($data['announcements'])): ?>
            <div class="no-announcements">
                <i class="bx bx-message-square-dots"></i>
                <p>No announcements found.</p>
            </div>
        <?php else: ?>
            <div class="announcements-list">
                <?php foreach ($data['announcements'] as $announcement): ?>
                    <div class="announcement-item" data-id="<?php echo $announcement->announcement_id; ?>">
                        <?php 
                            $uploadPath = dirname(APPROOT) . '/public/uploads/announcements/' . $announcement->banner;
                            if (!empty($announcement->banner) && file_exists($uploadPath)): 
                        ?>
                            <div class="announcement-banner">
                                <img 
                                    src="<?php echo URLROOT . '/public/uploads/announcements/' . htmlspecialchars($announcement->banner); ?>" 
                                    alt="Banner" 
                                    style="max-width:100%;border-radius:8px;margin-bottom:10px;"
                                >
                            </div>
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
                            <p><?php echo nl2br(htmlspecialchars($announcement->content)); ?></p>
                        </div>

                        <div class="announcement-footer">
                            <a 
                                href="<?php echo URLROOT; ?>/manager/editAnnouncement/<?php echo $announcement->announcement_id; ?>" 
                                class="btn btn-primary"
                            >
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-danger delete-btn">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this announcement?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn btn-danger">Yes, Delete</button>
                <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
</main>

<script>
const BASE = '<?php echo URLROOT; ?>';

// Search
document.getElementById('announcement-search')?.addEventListener('input', e => {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.announcement-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(term) ? '' : 'none';
    });
});

// Delete flow
let targetId = null;
document.body.addEventListener('click', e => {
    if (e.target.closest('.delete-btn')) {
        targetId = e.target.closest('.announcement-item').dataset.id;
        document.getElementById('confirmModal').style.display = 'flex';
    }
});

document.getElementById('confirmDelete').onclick = () => {
    fetch(`${BASE}/manager/deleteAnnouncement`, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ announcement_id: targetId })
    })
    .then(r => r.json())
    .then(res => {
        Toastify({
            text: res.message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: res.success ? "green" : "red"
        }).showToast();
        if (res.success) setTimeout(() => location.reload(), 1500);
    })
    .catch(() => {
        Toastify({ text: "Error deleting.", duration:3000, gravity:"top", position:"right", backgroundColor:"red" }).showToast();
    })
    .finally(() => {
        document.getElementById('confirmModal').style.display = 'none';
    });
};

document.getElementById('cancelDelete').onclick = () => {
    document.getElementById('confirmModal').style.display = 'none';
};
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