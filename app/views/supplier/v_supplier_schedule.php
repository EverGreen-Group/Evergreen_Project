<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
  <div class="head-title">
    <div class="left">
      <h1>My Schedule</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Schedule</span>
        </li>
      </ul>
    </div>
  </div>

  <div class="schedule-container">
    <div class="schedule-box">
      <h3>Schedule Information</h3>
      <table class="schedule-table">
        <tr>
          <td><strong>Schedule ID:</strong></td>
          <td><?php echo $data['schedule_id']; ?></td>
        </tr>
        <tr>
          <td><strong>Route ID:</strong></td>
          <td><?php echo $data['route_id']; ?></td>
        </tr>
        <tr>
          <td><strong>Route Name:</strong></td>
          <td><?php echo $data['route_name']; ?></td>
        </tr>
        <tr>
          <td><strong>Collection Day:</strong></td>
          <td><?php echo $data['day']; ?></td>
        </tr>
        <tr>
          <td><strong>Start Time:</strong></td>
          <td><?php echo date('h:i A', strtotime($data['start_time'])); ?></td>
        </tr>
      </table>
    </div>

    <div class="schedule-box">
      <h3>Driver Information</h3>
      <div class="driver-info">
        <?php if(!empty($data['driver_image'])): ?>
          <img src="<?php echo URLROOT; ?>/<?php echo $data['driver_image']; ?>" alt="Driver" class="driver-image">
        <?php else: ?>
          <div class="no-image">No Image Available</div>
        <?php endif; ?>
        
        <table class="schedule-table">
          <tr>
            <td><strong>Driver ID:</strong></td>
            <td><?php echo $data['driver_id']; ?></td>
          </tr>
          <tr>
            <td><strong>Name:</strong></td>
            <td><?php echo $data['driver_name']; ?></td>
          </tr>
          <tr>
            <td><strong>Contact:</strong></td>
            <td><?php echo $data['contact_number']; ?></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="schedule-box">
      <h3>Vehicle Information</h3>
      <div class="vehicle-info">
        <?php if(!empty($data['vehicle_image'])): ?>
          <img src="<?php echo URLROOT; ?>/<?php echo $data['vehicle_image']; ?>" alt="Vehicle" class="vehicle-image">
        <?php else: ?>
          <div class="no-image">No Image Available</div>
        <?php endif; ?>
        
        <table class="schedule-table">
          <tr>
            <td><strong>Vehicle ID:</strong></td>
            <td><?php echo $data['vehicle_id']; ?></td>
          </tr>
          <tr>
            <td><strong>License Plate:</strong></td>
            <td><?php echo $data['license_plate']; ?></td>
          </tr>
          <tr>
            <td><strong>Type:</strong></td>
            <td><?php echo $data['vehicle_type']; ?></td>
          </tr>
          <tr>
            <td><strong>Make:</strong></td>
            <td><?php echo $data['make']; ?></td>
          </tr>
          <tr>
            <td><strong>Model:</strong></td>
            <td><?php echo $data['model']; ?></td>
          </tr>
          <tr>
            <td><strong>Color:</strong></td>
            <td><?php echo $data['color']; ?></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="reminder">
      <p><strong>Remember:</strong> Collection occurs every <?php echo $data['day']; ?> starting at <?php echo date('h:i A', strtotime($data['start_time'])); ?>.</p>
    </div>
  </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
  main {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
  }

  .head-title {
    margin-bottom: 20px;
  }

  .head-title h1 {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .breadcrumb li {
    display: inline;
    margin-right: 10px;
  }

  .breadcrumb a {
    color: #666;
    text-decoration: none;
  }

  .schedule-container {
    margin-top: 20px;
  }

  .schedule-box {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 20px;
    padding: 15px;
  }

  .schedule-box h3 {
    margin-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
    color: var(--mainn);
  }

  .schedule-table {
    width: 100%;
    border-collapse: collapse;
  }

  .schedule-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
  }

  .schedule-table tr:last-child td {
    border-bottom: none;
  }

  .driver-info, .vehicle-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .driver-image, .vehicle-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 1px solid #ddd;
  }

  .no-image {
    width: 150px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    color: #999;
    font-size: 14px;
    text-align: center;
  }

  .reminder {
    background-color: #f8f9fa;
    border-left: 4px solid var(--mainn);
    padding: 10px 15px;
    margin-top: 20px;
    border-radius: 3px;
  }

  .reminder p {
    margin: 0;
    color: #555;
  }

  /* Responsive styles */
  @media (max-width: 768px) {
    .driver-info, .vehicle-info {
      flex-direction: column;
    }
    
    .driver-image, .vehicle-image, .no-image {
      margin: 0 auto 15px auto;
    }
  }

  @media (max-width: 480px) {
    main {
      padding: 10px;
    }
    
    .schedule-table td {
      display: block;
    }
    
    .schedule-table td:first-child {
      font-weight: bold;
      padding-bottom: 0;
      border-bottom: none;
    }
    
    .schedule-table tr {
      border-bottom: 1px solid #eee;
      display: block;
      padding: 5px 0;
    }
    
    .schedule-table tr:last-child {
      border-bottom: none;
    }
  }
</style>