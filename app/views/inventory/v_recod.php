<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/machineallo.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
  <style>

/* Reset some default styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 20px;
    }

    .card {
      background-color: #00a99d;
      color: #fff;
      padding: 20px;
      border-radius: 5px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card h3 {
      font-size: 18px;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
    }

    .upcoming-events {
      grid-column: 1 / 5;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
    }

    .upcoming-events h2 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .upcoming-events ul {
      list-style-type: none;
      padding-left: 0;
    }

    .upcoming-events li {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      padding: 15px 20px;
      border-bottom: 1px solid #ddd;
      align-items: center;
    }

    .upcoming-events li span {
      text-align: center;
    }

    .upcoming-events .event-type {
      font-weight: bold;
      text-align: left;
    }

    .upcoming-events .event-location {
      color: #666;
    }

    .upcoming-events .event-method {
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

    .container {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 20px;
      padding: 20px;
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

    /* Add more styles as needed */
  </style>
</head>
<body>
  <!-- Add the rest of the content here -->

  <div class="container">
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

  <div class="upcoming-events">
    <h2>Last Exports</h2>
    <ul>
      <li>
        <span class="event-type">September</span>
        <span class="event-location">2000</span>
        <span class="event-method">200</span>
      </li>
      <li>
        <span class="event-type">Octomber</span>
        <span class="event-location">2500</span>
        <span class="event-method">190</span>
      </li>
      <li>
        <span class="event-type">november</span>
        <span class="event-location">2100</span>
        <span class="event-method">197</span>
      </li>
      <li>
        <span class="event-type">Auguest</span>
        <span class="event-location">2150</span>
        <span class="event-method">218</span>
      </li>
    </ul>
  </div>

  <section class="pending-works">
    <h2>Pending Works</h2>
    <div class="work-item">
      <div class="work-status progress">
        <div class="progress-bar"></div>
        <span>In Process</span>
      </div>
      <h3>Green Tea</h3>
      <p>Nov 16, 2024</p>
    </div>
    <div class="work-item">
      <div class="work-status success">
        <div class="progress-bar"></div>
        <span>Packing completed</span>
      </div>
      <h3>natural Tea</h3>
      <p>Jul 17, 2024</p>
    </div>
    <div class="work-item">
      <div class="work-status failed">
        <div class="progress-bar"></div>
        <span>In Process</span>
      </div>
      <h3>Green Tea</h3>
      <p>Nov 18, 2024</p>
    </div>
    <div class="work-item">
      <div class="work-status progress">
        <div class="progress-bar"></div>
        <span>In Process</span>
      </div>
      <h3>Black Tea</h3>
      <p>Nov 19, 2024</p>
    </div>
    <div class="work-item">
      <div class="work-status success">
        <div class="progress-bar"></div>
        <span>Packing Completed</span>
      </div>
      <h3>Black Tea</h3>
      <p>Nov 20, 2024</p>
    </div>
  </section>
</body>
</html>