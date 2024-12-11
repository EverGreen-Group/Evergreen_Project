<!-- NAVBAR -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link" style="visibility: hidden;">Categories</a>
        <form action="#" style="visibility: hidden;">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification" id="notificationIcon">
            <i class='bx bxs-bell'></i>
            <span class="num" style="cursor: pointer;">8</span>
        </a>
        <a href="#" class="profile">
            <img src="<?php echo URLROOT; ?>/uploads/supplier_photos/default-supplier.png">
        </a>
    </nav>

    <div id="notificationDropdown" class="notification-dropdown" style="display: none;">
        <ul>
            <li>
                <div class="notification-item">
                    <div class="notification-content">
                        <h4 class="notification-title">TKT2207531</h4>
                        <p class="notification-description">Lorem ipsum dolor Lorem ipsum dolor Lorem ipsum dolor</p>
                    </div>
                    <div class="notification-meta">
                        <span class="notification-time">5 minutes ago</span>
                        <a href="#" class="notification-action">Mark as read</a>
                    </div>
                </div>
            </li>
            <li>
                <div class="notification-item">
                    <div class="notification-content">
                        <h4 class="notification-title">TKT2207532</h4>
                        <p class="notification-description">Lorem ipsum dolor Lorem ipsum dolor Lorem ipsum dolor</p>
                    </div>
                    <div class="notification-meta">
                        <span class="notification-time">20 minutes ago</span>
                        <a href="#" class="notification-action">Mark as read</a>
                    </div>
                </div>
            </li>
            <li>
                <div class="notification-item">
                    <div class="notification-content">
                        <h4 class="notification-title">TKT2207533</h4>
                        <p class="notification-description">Lorem ipsum dolor Lorem ipsum dolor Lorem ipsum dolor</p>
                    </div>
                    <div class="notification-meta">
                        <span class="notification-time">1 hour ago</span>
                        <a href="#" class="notification-action">Mark as read</a>
                    </div>
                </div>
            </li>
            <!-- Add more notification items here -->
        </ul>
    </div>

    <div id="profileDropdown" class="profile-dropdown" style="display: none;">
        <ul>
            <li><a href="<?php echo URLROOT; ?>/profile">Profile</a></li>

            <?php if (RoleHelper::isAdmin()): ?>
                <li><a href="<?php echo URLROOT; ?>/admin/dashboard">Admin Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/admin/users">Supplier Manager Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/admin/roles">Vehicle Manager Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/admin/vehicles">Inventory Manager Dashboard</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::SUPPLIER_MANAGER)): ?>
                <li><a href="<?php echo URLROOT; ?>/supplier/dashboard">Supplier Manager Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/supplier/orders">Chat</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::SUPPLIER)): ?>
                <li><a href="<?php echo URLROOT; ?>/supplier/profile">Supplier Profile</a></li>
                <li><a href="<?php echo URLROOT; ?>/supplier/profile">Supplier Dashboard</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::DRIVER)): ?>
                <li><a href="<?php echo URLROOT; ?>/driver/trips">Driver Profile</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::WEBSITE_USER)): ?>
                <li><a href="<?php echo URLROOT; ?>/user/dashboard">Orders</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::DRIVING_PARTNER)): ?>
                <li><a href="<?php echo URLROOT; ?>/drivingpartner/dashboard">Partner Profile</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::VEHICLE_MANAGER)): ?>
                <li><a href="<?php echo URLROOT; ?>/vehiclemanager/dashboard">Vehicle Manager Dashboard</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::INVENTORY_MANAGER)): ?>
                <li><a href="<?php echo URLROOT; ?>/inventorymanager/dashboard">Inventory Manager Dashboard</a></li>
            <?php endif; ?>

            <li><a href="<?php echo URLROOT; ?>/auth/logout">Logout</a></li>
        </ul>
    </div>
    <!-- NAVBAR -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileLink = document.querySelector('.profile');
        const dropdown = document.getElementById('profileDropdown');

        profileLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
        });

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(event) {
            const isClickInsideProfile = profileLink.contains(event.target);
            if (!isClickInsideProfile) {
                dropdown.style.display = 'none';
            }
        });
    });

    // Similar modification for notification dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const notificationIcon = document.getElementById('notificationIcon');
        const notificationDropdown = document.getElementById('notificationDropdown');

        notificationIcon.addEventListener('click', function(event) {
            event.preventDefault();
            notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(event) {
            const isClickInsideNotification = notificationIcon.contains(event.target);
            if (!isClickInsideNotification) {
                notificationDropdown.style.display = 'none';
            }
        });
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Lato:wght@300;400;700&display=swap');

    .notification-dropdown {
        position: absolute;
        right: 10px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 450px;
        padding: 10px 0;
        border-radius: 12px;
        font-family: 'Lato', sans-serif; /* Changed from Poppins to Lato */
    }

    .notification-dropdown ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .notification-dropdown li {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 15px; /* Adjusted to match profile dropdown */
        color: #333;
        transition: all 0.2s ease;
    }

    .notification-dropdown li:hover {
        background-color: rgba(0, 123, 0, 0.05); /* Changed hover background to green */
        color: #007b00; /* Changed hover text color to green */
    }

    .notification-item {
        display: flex;
        flex-direction: column;
    }

    .notification-content {
        margin-bottom: 5px;
    }

    .notification-title {
        margin: 0;
        font-weight: 500;
        font-size: 15px; /* Matching profile dropdown style */
        color: #333;
    }

    .notification-description {
        margin: 0;
        font-size: 14px;
        color: #666;
    }

    .notification-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #888;
    }

    .notification-time {
        font-size: 12px;
    }

    .notification-action {
        text-decoration: none;
        color: #007b00; /* Changed action link color to green */
        font-weight: 500;
        font-size: 13px;
    }

    .profile-dropdown {
        position: absolute;
        right: 10px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 200px;
        border-radius: 12px;
        font-family: 'Lato', sans-serif;
        overflow: hidden;
    }

    .profile-dropdown ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .profile-dropdown li {
        padding: 10px 15px;
        transition: all 0.2s ease;
    }

    .profile-dropdown li a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        font-size: 15px;
        display: block;
        transition: color 0.2s ease;
    }

    .profile-dropdown li:hover {
        background-color: rgba(0, 123, 0, 0.05); /* Changed hover background to green */
    }

    .profile-dropdown li:hover a {
        color: #007b00; /* Changed hover text color to green */
    }
</style>