<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo SITENAME; ?></title>
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .content {
      margin-top: 30px;
      margin-left: 20px;
      padding: 20px;
    }

    .item-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .item-card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.2s;
    }

    .item-card:hover {
      transform: translateY(-5px);
    }

    .item-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .item-details {
      padding: 15px;
    }

    .item-title {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }

    .item-price {
      font-size: 14px;
      color: #00a99d;
      margin-bottom: 10px;
    }

    .buy-now-btn {
      display: inline-block;
      padding: 8px 12px;
      background: #00a99d;
      color: white;
      text-align: center;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
    }

    .buy-now-btn:hover {
      background: #007d71;
    }
  </style>
</head>

<body>

  <!-- Top nav bar -->
  <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
  <!-- Side bar -->
  <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>



  <div class="content">
    <div class="item-grid">

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item1" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 1</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item2" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 2</div>
          <div class="item-price">RS.145.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item3" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 3</div>
          <div class="item-price">RS.450.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item4" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 4</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item5" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 5</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item6" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 6</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item7" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 7</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item8" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 8</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item9" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 9</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item10" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 10</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

      <div class="item-card">
        <img src="https://via.placeholder.com/200x200" alt="Item11" class="item-image">
        <div class="item-details">
          <div class="item-title">Item Title 11</div>
          <div class="item-price">RS.128.50</div>
          <a href="#" class="buy-now-btn">Buy Now</a>
        </div>
      </div>

    </div>
  </div>
</body>

</html>