<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME;?></title>
    <link rel="stylesheet" href="<?php echo URLROOT;?>/public/css/style.css">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<style>
body {
    background-color: #f8f9fa;
    margin: 0;
    font-family: 'Roboto', sans-serif;
}

.public-header {
    width: 100%;
    background-color: transparent;
    padding: 20px;
    box-sizing: border-box;
    border-bottom: none;
    box-shadow: none;
}

.public-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 30px;
    background-color: white;
    border-radius: 50px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 1;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 8px;
}

.nav-brand img {
    height: 24px;
    width: auto;
}

.nav-brand a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    font-size: 18px;
}

.nav-links {
    display: flex;
    gap: 30px;
    align-items: center;
}

.nav-links a {
    text-decoration: none;
    color: #555;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
    transition: color 0.3s ease;
}

.nav-links a:hover,
.nav-links a.active {
    color: #22a45d;
}

.nav-auth {
    display: flex;
    gap: 10px;
    /* border: 1px solid red; */
}

.nav-button {
    background-color: #e8ffd4 !important;
    color: #22a45d !important;
    border: none;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    /* display: inline-block;
    border: 1px solid blue; */
}

.nav-button:hover {
    background-color: #d4ff7f !important;
}
</style>
<body>
    <header class="public-header">
        <nav class="public-nav">
            <div class="nav-brand">
                <img src="<?php echo URLROOT;?>/public/img/logo.svg" alt="EverGreen">
                <a href="<?php echo URLROOT;?>">EverGreen</a>
            </div>
            <div class="nav-links">
                <a href="<?php echo URLROOT;?>" 
                   class="<?php echo ($_GET['url'] ?? '') === '' ? 'active' : ''; ?>">
                   HOME
                </a>
                <a href="<?php echo URLROOT;?>/pages/store" 
                   class="<?php echo ($_GET['url'] ?? '') === 'pages/store' ? 'active' : ''; ?>">
                   STORE
                </a>
                <a href="<?php echo URLROOT;?>/pages/profile" 
                   class="<?php echo ($_GET['url'] ?? '') === 'pages/profile' ? 'active' : ''; ?>">
                   PROFILE
                </a>
            </div>
            <div class="nav-auth">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo URLROOT;?>/auth/logout" class="nav-button">Logout</a>
                <?php else : ?>
                    <a href="<?php echo URLROOT;?>/auth/login" class="nav-button">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    