<!-- SIDEBAR -->
<section id="sidebar">
    <a href="<?php echo URLROOT; ?>" class="brand">
        <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Evergreen Logo">
        <span class="text">Evergreen</span>
    </a>
    <ul class="side-menu top">
        <li class="<?php echo ($_GET['url'] ?? '') === 'shop' ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/shop">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Shop</span>
            </a>
        </li>
        <li class="<?php echo ($_GET['url'] ?? '') === 'shop/categories' ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/shop/categories">
                <i class='bx bxs-category'></i>
                <span class="text">Categories</span>
            </a>
        </li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="<?php echo ($_GET['url'] ?? '') === 'shop/cart' ? 'active' : ''; ?>">
                <a href="<?php echo URLROOT; ?>/shop/cart">
                    <i class='bx bxs-cart'></i>
                    <span class="text">Cart</span>
                    <?php if(isset($data['cart_count']) && $data['cart_count'] > 0): ?>
                        <span class="cart-badge"><?php echo $data['cart_count']; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="<?php echo ($_GET['url'] ?? '') === 'shop/orders' ? 'active' : ''; ?>">
                <a href="<?php echo URLROOT; ?>/shop/orders">
                    <i class='bx bxs-package'></i>
                    <span class="text">My Orders</span>
                </a>
            </li>
            <li class="<?php echo ($_GET['url'] ?? '') === 'shop/wishlist' ? 'active' : ''; ?>">
                <a href="<?php echo URLROOT; ?>/shop/wishlist">
                    <i class='bx bxs-heart'></i>
                    <span class="text">Wishlist</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <ul class="side-menu">
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role_id'] == 1): ?>
                <li>
                    <a href="<?php echo URLROOT; ?>/admin">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Admin Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo URLROOT; ?>/users/profile">
                    <i class='bx bxs-user'></i>
                    <span class="text">Profile</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo URLROOT; ?>/auth/login">
                    <i class='bx bxs-log-in-circle'></i>
                    <span class="text">Login</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/users/register">
                    <i class='bx bxs-user-plus'></i>
                    <span class="text">Register</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</section>

<style>
#sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100%;
    background: var(--light);
    z-index: 2000;
    font-family: var(--lato);
    transition: .3s ease;
    overflow-x: hidden;
    scrollbar-width: none;
}

#sidebar::--webkit-scrollbar {
    display: none;
}

#sidebar.hide {
    width: 60px;
}

#sidebar .brand {
    font-size: 24px;
    font-weight: 700;
    height: 56px;
    display: flex;
    align-items: center;
    color: var(--blue);
    position: sticky;
    top: 0;
    left: 0;
    background: var(--light);
    z-index: 500;
    padding-bottom: 20px;
    box-sizing: content-box;
    text-decoration: none;
}

#sidebar .brand img {
    min-width: 40px;
    max-width: 40px;
    margin-right: 8px;
}

#sidebar .side-menu {
    width: 100%;
    margin-top: 48px;
}

#sidebar .side-menu li {
    height: 48px;
    background: transparent;
    margin-left: 6px;
    border-radius: 48px 0 0 48px;
    padding: 4px;
}

#sidebar .side-menu li.active {
    background: var(--grey);
    position: relative;
}

#sidebar .side-menu li.active::before {
    content: '';
    position: absolute;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    top: -40px;
    right: 0;
    box-shadow: 20px 20px 0 var(--grey);
    z-index: -1;
}

#sidebar .side-menu li.active::after {
    content: '';
    position: absolute;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    bottom: -40px;
    right: 0;
    box-shadow: 20px -20px 0 var(--grey);
    z-index: -1;
}

#sidebar .side-menu li a {
    width: 100%;
    height: 100%;
    background: var(--light);
    display: flex;
    align-items: center;
    border-radius: 48px;
    font-size: 16px;
    color: var(--dark);
    white-space: nowrap;
    overflow-x: hidden;
    text-decoration: none;
}

#sidebar .side-menu li.active a {
    color: var(--blue);
}

#sidebar.hide .side-menu li a {
    width: calc(48px - (4px * 2));
    transition: width .3s ease;
}

#sidebar .side-menu li a.logout {
    color: var(--red);
}

#sidebar .side-menu.top li a:hover {
    color: var(--blue);
}

#sidebar .side-menu li a .bx {
    min-width: calc(60px - ((4px + 6px) * 2));
    display: flex;
    justify-content: center;
}

.cart-badge {
    background: var(--red);
    color: var(--light);
    padding: 2px 8px;
    border-radius: 50%;
    font-size: 12px;
    margin-left: auto;
    margin-right: 16px;
}

/* SIDEBAR */
@media screen and (max-width: 768px) {
    #sidebar {
        width: 200px;
    }

    #content {
        width: calc(100% - 60px);
        left: 200px;
    }

    #sidebar.hide {
        width: 60px;
    }

    #content {
        width: calc(100% - 60px);
        left: 60px;
    }
}
</style>

<script>
// Sidebar Collapse
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});

if(window.innerWidth <= 768) {
    sidebar.classList.add('hide');
}
</script> 