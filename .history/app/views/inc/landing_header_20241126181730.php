<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4CAF50">
    <link rel="icon" type="image/x-icon" href="<?php echo URLROOT; ?>/img/favicon.ico">
    <title><?php echo SITENAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/landing.css">
</head>
<body>
    <nav class="landing-nav">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Evergreen">
            <span style="color: var(--main)">Evergreen</span>
        </div>
        <div class="mobile-menu-btn">
            <i class='bx bx-menu'></i>
        </div>
        <div class="nav-links">
            <a href="<?php echo URLROOT; ?>">Home</a>
            <a href="<?php echo URLROOT; ?>/shop">Shop</a>
            <a href="<?php echo URLROOT; ?>/about">About</a>
            <a href="<?php echo URLROOT; ?>/contact">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo URLROOT; ?>/users/profile">Profile</a>
                <a href="<?php echo URLROOT; ?>/users/logout">Logout</a>
            <?php else: ?>
                <a href="<?php echo URLROOT; ?>/users/login" class="btn-login">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html> 