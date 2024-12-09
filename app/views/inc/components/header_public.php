<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f8f9fa;
        margin: 0;
        font-family: "Poppins", sans-serif;
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
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 50px;
        box-shadow: 0 2px 35px rgba(34, 164, 93, 0.2);
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

/* Add these new styles and update existing ones */

/* Add hamburger menu icon */
.mobile-menu-btn {
    display: none;
    font-size: 24px;
    cursor: pointer;
    color: #333;
}

/* Add media query for mobile */
@media screen and (max-width: 891px) {
    .public-nav {
        flex-wrap: wrap;
        border-radius: 25px;
        padding: 15px 20px;
    }

    .mobile-menu-btn {
        display: block;
    }

    .nav-brand {
        flex: 1;
    }

    .nav-links {
        display: none; /* Hidden by default */
        width: 100%;
        flex-direction: column;
        gap: 15px;
        padding: 15px 0;
        margin-top: 15px;
        border-top: 1px solid #eee;
        order: 3;
    }

    .nav-links.active {
        display: flex;
    }

    .nav-auth {
        order: 2;
    }

    .nav-button {
        padding: 6px 15px;
        font-size: 13px;
    }
}
</style>

<body>
    <header class="public-header">
        <nav class="public-nav">
            <div class="nav-brand">
                <img src="<?php echo URLROOT; ?>/public/img/logo.svg" alt="EverGreen">
                <a href="<?php echo URLROOT; ?>">EverGreen</a>
            </div>
            <div class="nav-links">
                <a href="<?php echo URLROOT; ?>" class="<?php echo ($_GET['url'] ?? '') === '' ? 'active' : ''; ?>">
                    HOME
                </a>
                <a href="<?php echo URLROOT; ?>/pages/store"
                    class="<?php echo ($_GET['url'] ?? '') === 'pages/store' ? 'active' : ''; ?>">
                    STORE
                </a>
                <a href="<?php echo URLROOT; ?>/pages/about"
                    class="<?php echo ($_GET['url'] ?? '') === 'pages/store' ? 'active' : ''; ?>">
                    ABOUT
                </a>
                
                <?php if (RoleHelper::hasRole(RoleHelper::DRIVER)): ?>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/index" class="<?php echo ($_GET['url'] ?? '') === 'vehicledriver/index' ? 'active' : ''; ?>">
                        DASHBOARD
                    </a>
                <?php endif; ?>

                <?php if (RoleHelper::hasRole(RoleHelper::SUPPLIER_MANAGER)): ?>
                    <a href="<?php echo URLROOT; ?>/suppliermanager/index" class="<?php echo ($_GET['url'] ?? '') === 'suppliermanager/index' ? 'active' : ''; ?>">
                        SUPPLIER MANAGER
                    </a>
                <?php endif; ?>

                <?php if (RoleHelper::hasRole(RoleHelper::EMPLOYEE)): ?>
                    <a href="<?php echo URLROOT; ?>/employeemanager/index" class="<?php echo ($_GET['url'] ?? '') === 'employeemanager/index' ? 'active' : ''; ?>">
                        EMPLOYEE DASHBOARD
                    </a>
                <?php endif; ?>

                <?php if (RoleHelper::isAdmin()): ?>
                    <a href="<?php echo URLROOT; ?>/admin/index" class="<?php echo ($_GET['url'] ?? '') === 'admin/index' ? 'active' : ''; ?>">
                        ADMIN DASHBOARD
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo URLROOT; ?>/profile" class="<?php echo ($_GET['url'] ?? '') === 'profile' ? 'active' : ''; ?>">
                        Profile
                    </a>
                <?php endif; ?>
            </div>
            <div class="nav-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo URLROOT; ?>/auth/logout" class="nav-button">Logout</a>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="nav-button">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>