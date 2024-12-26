<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo SITENAME; ?></title>
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>

  <!-- Top nav bar -->
  <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
  <!-- Side bar -->
  <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Reset some default styles */
    /* * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    } */

    


    .card1 {
      background-color: #00a99d;
      color: #fff;
      padding: 20px;
      margin: 20px;
      border-radius: 5px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      width: 400px;

    }

    .card h3 {
      font-size: 18px;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
    }

    .Last-export {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      margin-top: 20px;
      width: 45%;

    }

    .Last-export h2 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .Last-export ul {
      list-style-type: none;
      padding-left: 0;
    }

    .Last-export li {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      padding: 15px 20px;
      border-bottom: 1px solid #ddd;
      align-items: center;
    }

    .Last-export li span {
      text-align: center;
    }

    .Last-export .event-type {
      font-weight: bold;
      text-align: left;
    }

    .Last-export .event-location {
      color: #666;
    }

    .Last-export .event-method {
      text-transform: uppercase;
      color: #00a99d;
      font-weight: bold;
      text-align: right;
    }

    /* Add your internal CSS styles here */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }


    .container2 {
      display: flex;
      justify-content: space-around;
    }

    .card {
      background-color: #00a99d;
      color: #fff;
      padding: 20px;
      border-radius: 5px;
    }

    .card h3 {
      margin-top: 0;
    }

    .card p {
      margin-bottom: 0;
    }

    .pending-works {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      margin-top: 20px;
      width: 45%;
    }

    .pending-works h2 {
      font-size: 20px;
      margin-bottom: 20px;
    }

    .work-item {
      display: flex;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid #ddd;
    }

    .work-item:last-child {
      border-bottom: none;
    }

    .work-status {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 250px;
    }

    .work-status .progress-bar {
      height: 20px;
      width: 150px;
      border-radius: 10px;
      background-color: #e0e0e0;
      position: relative;
      overflow: hidden;
    }

    .work-status .progress-bar::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      border-radius: 10px;
    }

    .work-status.progress .progress-bar::after {
      background-color: #ffb300;
      width: 60%;
    }

    .work-status.success .progress-bar::after {
      background-color: #4caf50;
      width: 100%;
    }

    .work-status.failed .progress-bar::after {
      background-color: #f44336;
      width: 40%;
    }

    .work-item h3 {
      font-size: 16px;
      margin-bottom: 5px;
    }

    .work-item p {
      color: #666;
      font-size: 14px;
    }

    .chart-container {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      margin: 20px;
      width: 100%;
      height: 400px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }


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

    /* form styles */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
    }

    .form-control {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .form-control:focus {
      outline: none;
      border-color: #4834d4;
    }

    select.form-control {
      appearance: none;
      background: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23333' viewBox='0 0 12 12'%3E%3Cpath d='M3 5l3 3 3-3'/%3E%3C/svg%3E") no-repeat right 12px center;
    }

    .error-text {
      color: #ff4444;
      font-size: 12px;
      margin-top: 5px;
    }

    .btn {
      background: #2ec720;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      float: right;
    }

    .btn:hover {
      background: #3a2bb7;
    }

    .cardrelese{
      width: 100%;
    }

    /* Add more styles as needed */
  </style>
  </head>

  <body>
    <!-- Add the rest of the content here -->

    <div class="container2">
      <div class="card1">
        <h3>Last month Export</h3>
        <p>2000kg</p>
      </div>
      <div class="card1">
        <h3>Processing Stock</h3>
        <p>4000kg</p>
      </div>
      <div class="card1">
        <h3>Total Exports</h3>
        <p>1900Ton</p>
      </div>
      <div class="card1">
        <h3>Ready Stock</h3>
        <p>400kg</p>
      </div>
    </div>


    <div class="container2">
      <div class="Last-export">
        <h2>Last Exports</h2>
        <ul class="event-list">
          <!-- Headers -->
          <li class="event-header">
            <span class="event-type">Month</span>
            <span class="event-location">Quantity</span>
            <span class="event-method">Price</span>
          </li>
          <!-- Data Rows -->
          <li>
            <span class="event-type">September</span>
            <span class="event-location">2000</span>
            <span class="event-method">200</span>
          </li>
          <li>
            <span class="event-type">October</span>
            <span class="event-location">2500</span>
            <span class="event-method">190</span>
          </li>
          <li>
            <span class="event-type">November</span>
            <span class="event-location">2100</span>
            <span class="event-method">197</span>
          </li>
          <li>
            <span class="event-type">August</span>
            <span class="event-location">2150</span>
            <span class="event-method">218</span>
          </li>
        </ul>

      </div>

      <section class="Last-export">
        <h2>Relese Exports</h2>
        <form action="<?php echo URLROOT; ?>/Export/release" method="POST" >
          <div class="form-grid">
            <div class="form-group">
              <label>Export Stock Name*</label>
              <input type="text" name="stock-name" class="form-control" placeholder="Enter Stock Name" required>
              <span class="error-text">Please enter stock name</span>
            </div>
            <div class="form-group">
              <label>Company for Export*</label>
              <input type="text" name="company-name" class="form-control" placeholder="Enter Company Name">
              <span class="error-text">Please enter company name</span>
            </div>


            <div class="form-group">
              <label>Export Confirmation Date*</label>
              <input type="date" name="confirm-date" class="form-control" required>
              <span class="error-text">Please enter date</span>
            </div>
            <div class="form-group">
              <label>Export Manager name*</label>
              <input type="text" name="manager-name" class="form-control" placeholder="Enter First Name" def required>
              <span class="error-text">Please enter manager name</span>
            </div>
            <div class="form-group">
              <label>Export Price(per kg)</label>
              <input type="number" name="price" class="form-control" placeholder="Enter Price">
              <span class="error-text">Please enter Price</span>
            </div>
            <div class="form-group">
              <label>Export Quantity*</label>
              <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity" required>
              <span class="error-text">Please enter quantity</span>
            </div>
            <div class="form-group">
              <label>Export Reg No</label>
              <input type="text" name="reg-no" class="form-control" placeholder="Enter your reg no" required>
            </div>

          </div>
          <button type="submit" class="btn">Confirm</button>
        </form>
      </section>
    </div>
    <div class="container2">
      <div class="chart-container">
        <canvas id="monthlyExportChart"></canvas>
      </div>

    </div>
    <!-- Add this after your existing container2 div -->
    <div class='cardrelese'>
      <?php require APPROOT .'/views/inventory/components/export_card.php';?>
    </div>



    <!-- chart javascript of that -->

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var monthlyCtx = document.getElementById('monthlyExportChart').getContext('2d');
        var monthlyExportChart = new Chart(monthlyCtx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
              label: 'Export Quantity (kg)',
              data: [2000, 2200, 1800, 2400, 2100, 1900, 2300, 2150, 2000, 2500, 2100, 1950], // Replace with your actual data
              backgroundColor: '#00a99d',
              borderColor: '#008f84',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Export Quantity (kg)'
                }
              },
              x: {
                title: {
                  display: true,
                  text: 'Months'
                }
              }
            },
            plugins: {
              title: {
                display: true,
                text: 'Monthly Export Overview',
                font: {
                  size: 16
                }
              }
            }
          }
        });
      });
    </script>
  </body>

</html>