<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/f_available.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

    <style>
         /* item card css */

    .card4 {
    
    margin: 20px;
    margin-right: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    font-family: Arial, sans-serif;
  }

  .card-header {
    padding: 24px 24px 8px 24px;
  }

  .header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .title-group {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .id-number {
    color: #6B7280;
  }

  .title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
  }

  .badge {
    background-color: #EFF6FF;
    color: #1D4ED8;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 500;
  }

  .card-content {
    padding: 16px 24px 24px 24px;
  }

  .metadata-container {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    font-size: 14px;
    color: #4B5563;
  }

  .metadata-item {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .icon {
    width: 16px;
    height: 16px;
    fill: currentColor;
  }

  .progress-container {
    margin-top: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #2563EB;
  }

  .update-text {
    color: #6B7280;
  }
        
    </style>
</head>

<body>

    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

    <div class="card4">
    <div class="card-header">
      <div class="header-content">
        <div class="title-group">
          <span class="id-number">#1 -</span>
          <h2 class="title">Black Tea Stock</h2>
        </div>
        <span class="badge">In progress</span>
      </div>
    </div>
    
    <div class="card-content">
      <div class="metadata-container">
        <div class="metadata-item">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9M3 9V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4M3 9h18M9 16v-4m6 4v-4"></path>
          </svg>
          <span>Dilmah Company</span>
        </div>
        
        <div class="metadata-item">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span>colombo</span>
        </div>
        
        <div class="metadata-item">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span>Simaak nasrif</span>
        </div>
        
        <div class="metadata-item">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
            <line x1="7" y1="7" x2="7.01" y2="7"></line>
          </svg>
          <span>Jackets & coats</span>
        </div>
      </div>
      
      <div class="progress-container">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M12 6v6l4 2"></path>
        </svg>
        <span>Pre-Production (2/11)</span>
        <span class="update-text">updated 4d ago</span>
      </div>
    </div>
  </div>

    
</body>
</html>