<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/landing.css">
</head>
<body>
    <nav class="landing-nav">
        <div class="logo">
            <img src="<?php echo URLROOT; ?>/img/logo.png" alt="Evergreen">
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
                <a href="<?php echo URLROOT; ?>/users/login">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html> 