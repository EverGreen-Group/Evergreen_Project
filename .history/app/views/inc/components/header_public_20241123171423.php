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
<body>
    <nav class="main-nav">
        <div class="nav-wrapper">
            <div class="logo">
                <a href="<?php echo URLROOT; ?>">
                    <img src="<?php echo URLROOT; ?>/public/img/logo.svg" alt="EverGreen">
                    <span>EverGreen</span>
                </a>
            </div>

            <button class="mobile-menu-btn" aria-label="Toggle menu" aria-expanded="false">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <div class="nav-menu">
                <a href="<?php echo URLROOT; ?>" class="nav-link <?php echo ($_GET['url'] ?? '') === '' ? 'active' : ''; ?>">
                    <i class='bx bxs-home'></i>
                    Home
                </a>
                
                <!-- About Dropdown -->
                <div class="nav-dropdown">
                    <button class="dropdown-trigger">
                        <i class='bx bxs-info-circle'></i>
                        About
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="<?php echo URLROOT; ?>/pages/about">About Us</a>
                        <a href="<?php echo URLROOT; ?>/pages/team">Our Team</a>
                        <a href="<?php echo URLROOT; ?>/pages/mission">Mission & Vision</a>
                    </div>
                </div>
                
                <!-- Management Dropdown -->
                <div class="nav-dropdown">
                    <button class="dropdown-trigger">
                        <i class='bx bxs-truck'></i>
                        Management
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="<?php echo URLROOT; ?>/vehicles">Vehicle Management</a>
                        <a href="<?php echo URLROOT; ?>/supply">Supply Management</a>
                        <a href="<?php echo URLROOT; ?>/inventory">Inventory</a>
                    </div>
                </div>

                <a href="<?php echo URLROOT; ?>/marketplace" class="nav-link <?php echo ($_GET['url'] ?? '') === 'marketplace' ? 'active' : ''; ?>">
                    <i class='bx bxs-store'></i>
                    Marketplace
                </a>

                <!-- Blog Dropdown -->
                <div class="nav-dropdown">
                    <button class="dropdown-trigger">
                        <i class='bx bxs-book-content'></i>
                        Blog
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="<?php echo URLROOT; ?>/blog">All Posts</a>
                        <a href="<?php echo URLROOT; ?>/blog/categories">Categories</a>
                        <?php if(isset($_SESSION['user_id'])) : ?>
                            <a href="<?php echo URLROOT; ?>/blog/create">Create Post</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo URLROOT; ?>/contact" class="nav-link <?php echo ($_GET['url'] ?? '') === 'contact' ? 'active' : ''; ?>">
                    <i class='bx bxs-contact'></i>
                    Contact
                </a>
            </div>

            <div class="nav-buttons">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <div class="nav-dropdown">
                        <button class="dropdown-trigger user-menu">
                            <i class='bx bxs-user-circle'></i>
                            <span>Account</span>
                            <i class='bx bx-chevron-down'></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo URLROOT; ?>/profile">
                                <i class='bx bxs-user-detail'></i>
                                Profile
                            </a>
                            <a href="<?php echo URLROOT; ?>/dashboard">
                                <i class='bx bxs-dashboard'></i>
                                Dashboard
                            </a>
                            <a href="<?php echo URLROOT; ?>/auth/logout" class="logout-link">
                                <i class='bx bxs-log-out'></i>
                                Logout
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <a href="<?php echo URLROOT; ?>/auth/login" class="login-btn">
                        <i class='bx bxs-user'></i>
                        Login
                    </a>
                    <a href="<?php echo URLROOT; ?>/auth/register" class="register-btn">
                        <i class='bx bxs-user-plus'></i>
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</body>
</html>
    