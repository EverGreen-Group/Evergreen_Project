<!-- SIDEBAR -->
<section id="sidebar">
    <div class = "brand">
    <a href="<?php echo URLROOT; ?>" class="brand">
        <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Evergreen Logo">
        <span class="text">Evergreen</span>

        
    </a>
    <button id="menu-toggle" class="menu-toggle">
            <i class='bx bx-menu'></i>
    </button>
    </div>
    
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
            
            <!-- Order Tracking Section -->
            <li class="tracking-section <?php echo (strpos($_GET['url'] ?? '', 'shop/trackOrder') !== false || strpos($_GET['url'] ?? '', 'shop/activeDeliveries') !== false) ? 'active' : ''; ?>">
                <a href="#" class="tracking-menu">
                    <i class='bx bx-map-alt'></i>
                    <span class="text">Order Tracking</span>
                    <i class='bx bx-chevron-right arrow'></i>
                </a>
                <ul class="tracking-submenu">
                    <li class="<?php echo ($_GET['url'] ?? '') === 'shop/activeDeliveries' ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/shop/activeDeliveries">
                            <i class='bx bx-package'></i>
                            <span class="text">Active Deliveries</span>
                        </a>
                    </li>
                    <li class="<?php echo ($_GET['url'] ?? '') === 'shop/trackOrder' ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/shop/trackOrder">
                            <i class='bx bx-map'></i>
                            <span class="text">Track Order</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

    </ul>
    <ul class="side-menu">
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role_id'] == 1): ?>
                <!-- <li>
                    <a href="<?php echo URLROOT; ?>/admin">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Admin Dashboard</span>
                    </a>
                </li> -->
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
.tracking-section .tracking-menu {
    position: relative;
    display: flex;
    align-items: center;
    padding: 10px 20px;
    color: var(--dark);
    transition: all 0.3s ease;
}

.tracking-section .tracking-menu .arrow {
    margin-left: auto;
    transition: transform 0.3s ease;
}

.tracking-section.active .tracking-menu .arrow {
    transform: rotate(90deg);
}

.tracking-submenu {
    display: none;
    padding-left: 30px;
}

.tracking-section.active .tracking-submenu {
    display: block;
}

.tracking-submenu li a {
    padding: 8px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--dark);
    transition: all 0.3s ease;
}

.tracking-submenu li.active a,
.tracking-submenu li a:hover {
    color: var(--main);
    padding-left: 25px;
}

.cart-badge {
    background: var(--red);
    color: white;
    padding: 2px 6px;
    border-radius: 50%;
    font-size: 0.8em;
    margin-left: 5px;
}
</style>

<script>
// Sidebar Collapse
const menuBar = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});

if(window.innerWidth <= 768) {
    sidebar.classList.add('hide');
}

// Tracking submenu toggle
document.querySelector('.tracking-menu').addEventListener('click', function(e) {
    e.preventDefault();
    this.closest('.tracking-section').classList.toggle('active');
});
</script> 