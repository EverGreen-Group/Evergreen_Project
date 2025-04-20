<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<main>
    <div class="announcements-container">
        <div class="announcements-header">
            <h2>Announcements</h2>
            <div class="search-box">
                <input type="text" id="announcement-search" placeholder="Search announcements...">
                <i class='bx bx-search'></i>
            </div>
        </div>

        <?php if (isset($data['error']) && !empty($data['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $data['error']; ?>
            </div>
        <?php elseif (empty($data['announcements'])): ?>
            <div class="no-announcements">
                <i class='bx bx-message-square-dots'></i>
                <p>No announcements found.</p>
            </div>
        <?php else: ?>
            <div class="announcements-list">
                <?php foreach ($data['announcements'] as $announcement): ?>
                    <div class="announcement-item">
                        <?php if (!empty($announcement->banner)): ?>
                            <div class="announcement-banner">
                                <img src="<?php echo URLROOT . '/uploads/announcements/' . htmlspecialchars($announcement->banner); ?>" alt="Banner" style="max-width: 100%; border-radius: 8px; margin-bottom: 10px;">
                            </div>
                        <?php endif; ?>
                        <div class="announcement-header">
                            <h4><?php echo htmlspecialchars($announcement->title); ?></h4>
                            <span class="announcement-date">
                                <i class='bx bx-calendar'></i>
                                <?php echo date('F j, Y, g:i a', strtotime($announcement->created_at)); ?>
                            </span>
                        </div>
                        <div class="announcement-body">
                            <p><?php echo htmlspecialchars($announcement->content); ?></p>
                        </div>
                        <div class="announcement-footer">
                            <small>Posted by: <?php echo htmlspecialchars($announcement->sender_name); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.getElementById('announcement-search')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.announcement-item').forEach(item => {
        const title = item.querySelector('h4')?.textContent.toLowerCase() || '';
        const content = item.querySelector('.announcement-body p')?.textContent.toLowerCase() || '';
        const sender = item.querySelector('.announcement-footer small')?.textContent.toLowerCase() || '';
        item.style.display = 
            title.includes(searchTerm) || content.includes(searchTerm) || sender.includes(searchTerm) 
            ? 'block' : 'none';
    });
});
</script>

<style>
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
    text-align: right;
}

.announcement-footer small {
    color: #999;
    font-size: 12px;
}
</style>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>