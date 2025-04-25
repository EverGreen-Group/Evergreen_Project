<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<main>
  <div class="announcements-container">
    <div class="announcements-header">
      <h2>Announcements</h2>
      <div class="search-box">
        <input type="text" id="announcement-search" placeholder="Search announcements...">
        <i class="bx bx-search"></i>
      </div>
    </div>

    <?php if (!empty($data['error'])): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($data['error']); ?>
      </div>
    <?php elseif (empty($data['announcements'])): ?>
      <div class="no-announcements">
        <i class="bx bx-message-square-dots"></i>
        <p>No announcements found.</p>
      </div>
    <?php else: ?>
      <div class="announcements-list">
        <?php foreach ($data['announcements'] as $announcement): ?>
          <div class="announcement-item">
            <?php 
              $fsPath = dirname(APPROOT) . '/public/uploads/announcements/' . $announcement->banner;
              if (!empty($announcement->banner) && file_exists($fsPath)): 
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
              </span>
            </div>

            <div class="announcement-body">
              <p><?php echo nl2br(htmlspecialchars($announcement->content)); ?></p>
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
  // Search filter
  document.getElementById('announcement-search')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.announcement-item').forEach(item => {
      const txt = item.textContent.toLowerCase();
      item.style.display = txt.includes(term) ? '' : 'none';
    });
  });

  // Flash message via Toastify
  <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
  <?php if (!empty($_SESSION['flash']['message'])): 
    $msg = addslashes($_SESSION['flash']['message']['message']);
    $isSuccess = strpos($_SESSION['flash']['message']['class'],'success')!==false;
    unset($_SESSION['flash']['message']);
  ?>
  Toastify({
    text: "<?php echo $msg; ?>",
    duration: 3000,
    gravity: "top",
    position: "right",
    backgroundColor: "<?php echo $isSuccess ? '#28a745' : '#d9534f'; ?>",
    style: {
      fontFamily: "Poppins, sans-serif",
      fontSize: "16px",
      fontWeight: "500",
      padding: "12px 20px",
      borderRadius: "8px",
      boxShadow: "0 4px 10px rgba(0,0,0,0.2)"
    }
  }).showToast();
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
    transition: transform 0.2s;
  }
  .announcement-item:hover {
    transform: translateY(-2px);
  }
  .announcement-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
  }
  .announcement-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
  }
  .announcement-date {
    font-size: 14px;
    color: #777;
  }
  .announcement-body p {
    margin: 0;
    line-height: 1.6;
    color: #555;
  }
  .announcement-footer {
    text-align: right;
    color: #999;
    font-size: 12px;
  }
</style>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>
