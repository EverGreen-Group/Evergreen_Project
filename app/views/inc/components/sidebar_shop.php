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
            <li>
                <a href="<?php echo URLROOT; ?>/shop/tracking">
                    <i class='bx bx-map-alt'></i>
                    <span>Track Order</span>
                </a>
            </li>
            <!-- Delivery Tracking Section -->
            <li class="delivery-section <?php echo (strpos($_GET['url'] ?? '', 'shopdelivery') !== false) ? 'active' : ''; ?>">
                <a href="#" class="delivery-menu">
                    <i class='bx bx-map-alt'></i>
                    <span class="text">Delivery Tracking</span>
                    <i class='bx bx-chevron-right arrow'></i>
                </a>
                <ul class="delivery-submenu">
                    <li class="<?php echo ($_GET['url'] ?? '') === 'shopdelivery' ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/shopdelivery">
                            <i class='bx bx-package'></i>
                            <span class="text">Active Deliveries</span>
                        </a>
                    </li>
                    
                    <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                        <li class="<?php echo ($_GET['url'] ?? '') === 'shopdelivery/update' ? 'active' : ''; ?>">
                            <a href="<?php echo URLROOT; ?>/shopdelivery/update">
                                <i class='bx bx-edit'></i>
                                <span class="text">Update Tracking</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
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
</script> 