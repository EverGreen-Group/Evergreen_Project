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

    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }


    .card {
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
      width: 95%;
      height: 400px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }


    /* Add more styles as needed */
  </style>
  </head>

  <body>
    <!-- Add the rest of the content here -->

    <div class="container2">
      <div class="card">
        <h3>Last month Export</h3>
        <p>2000kg</p>
      </div>
      <div class="card">
        <h3>Processing Stock</h3>
        <p>4000kg</p>
      </div>
      <div class="card">
        <h3>Total Exports</h3>
        <p>1900Ton</p>
      </div>
      <div class="card">
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
        <h2>Pending Exports</h2>
        <ul class="event-list">
          <!-- Headers -->
          <li class="event-header">
            <span class="event-type">Date</span>
            <span class="event-method">Quantity</span>
            
          </li>
          <!-- Data Rows -->
          <li>
            <span class="event-type">22-11-2024</span>
            <span class="event-method">2000</span>
            
          </li>
          <li>
            <span class="event-type">23-11-2024</span>
            <span class="event-method">2500</span>
           
          </li>
          <li>
              <span class="event-type">24-11-2024</span>
            <span class="event-method">2100</span>
            
          </li>
          <li>
            <span class="event-type">25-11-2024</span>
            <span class="event-method">2150</span>
           
          </li>
        </ul>
      </section>
    </div>
      <div class="container2">
        <div class="chart-container">
          <canvas id="monthlyExportChart"></canvas>
        </div>
      </div>
      <!-- Add this after your existing container2 div -->
      <div class="container2">
        <div class="chart-container">
          
        </div>
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