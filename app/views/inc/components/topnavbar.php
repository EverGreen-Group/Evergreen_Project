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
        <ul id="notificationList">
            <li>Loading notifications...</li>
        </ul>
    </div>

    <div id="profileDropdown" class="profile-dropdown" style="display: none;">
        <ul>
            <li><a href="<?php echo URLROOT; ?>/profile">Profile</a></li>

            <?php if (RoleHelper::isAdmin()): ?>
                <li><a href="<?php echo URLROOT; ?>/admin/">Admin Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/suppliermanager/">Supplier Manager Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/">Vehicle Manager Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/inventory/">Inventory Manager Dashboard</a></li>
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

            <?php if (RoleHelper::hasRole(RoleHelper::VEHICLE_MANAGER)): ?>
                <li><a href="<?php echo URLROOT; ?>/manager/dashboard">Vehicle Manager Dashboard</a></li>
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

    document.addEventListener('DOMContentLoaded', function() {
        const userId = <?php echo json_encode($_SESSION['user_id']); ?>;

        // Fetch unread notifications count
        fetch(`/Evergreen_Project/notifications/getUnreadNotificationCount`)
            .then(response => response.json())
            .then(data => {
                const notificationCount = data.count;
                document.querySelector('.num').textContent = notificationCount; 
            })
            .catch(error => {
                console.error('Error fetching unread notification count:', error);
            });

        // forr dropdown
        fetch(`/Evergreen_Project/notifications/getUnreadUserNotifications/${userId}`)
            .then(response => response.json())
            .then(notifications => {
                const notificationList = document.getElementById('notificationList');
                notificationList.innerHTML = ''; 

                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        const listItem = document.createElement('li');
                        const data = JSON.parse(notification.data); // Parse the JSON data
                        const scheduleId = data.schedule_id; // Extract the schedule ID
                        const link = data.link; // Extract the link

                        listItem.innerHTML = `
                            <div class="notification-item" data-id="${notification.id}">
                                <div class="notification-content">
                                    <h4 class="notification-title" style="color: green; font-weight: bold;">${notification.type}</h4>
                                    <p class="notification-description">
                                        ${notification.message} 
                                    </p>
                                </div>
                                <div class="notification-meta">
                                    <a href="${link ? link : '#'}" class="notification-action" onclick="handleNotificationClick(event, '${notification.id}', '${link}')">View</a>
                                    <a href="#" class="notification-action" onclick="markAsRead(event, ${notification.id})">Mark as read</a>
                                </div>
                            </div>
                        `;
                        notificationList.appendChild(listItem);
                    });
                } else {
                    notificationList.innerHTML = '<li>No notifications available.</li>';
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                document.getElementById('notificationList').innerHTML = '<li>Error loading notifications.</li>';
            });
    });

    function markAsRead(event, notificationId) {
        event.preventDefault(); // Prevent the default link behavior
        fetch(`/Evergreen_Project/notifications/markAsRead/${notificationId}`, {
            method: 'POST'
        })
        .then(response => {
            if (response.ok) {
                console.log('Notification marked as read');
                // Optionally, remove the notification from the UI or update its status
                const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.remove(); 
                }
                updateUnreadCount();
            }
        });
    }

    function updateUnreadCount() {
        const userId = <?php echo json_encode($_SESSION['user_id']); ?>; // Get user ID from PHP
        fetch(`/Evergreen_Project/notifications/getUnreadNotificationCount`)
            .then(response => response.json())
            .then(data => {
                const notificationCount = data.count;
                document.querySelector('.num').textContent = notificationCount; // Update the notification count
            })
            .catch(error => {
                console.error('Error fetching unread notification count:', error);
            });
    }

    function handleNotificationClick(event, notificationId, link) {
        // Prevent the default action if there's no link
        if (!link) {
            event.preventDefault(); // Prevent the default link behavior
            return; // Exit the function
        }
        // Optionally, you can mark the notification as read here if you want
        markAsRead(notificationId);
        // Redirect to the link
        window.location.href = link;
    }
</script>

<style>

    .notification-dropdown {
        position: absolute;
        right: 10px;
        background-color: white;
        /* border: 1px solid #ccc; */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 450px;
        padding: 10px 0;
        border-radius: 12px;
        font-family: 'Lato', sans-serif;
    }

    .notification-dropdown ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .notification-dropdown li {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 15px;
        color: #333;
        transition: all 0.2s ease;
    }

    .notification-dropdown li:hover {
        background-color: rgba(0, 123, 0, 0.05);
        color: #007b00;
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
        font-weight: 1000;
        font-size: 15px;
        color: var(--main);
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
        color: #007b00;
        font-weight: 500;
        font-size: 13px;
    }

    .profile-dropdown {
        position: absolute;
        right: 10px;
        background-color: white;
        /* border: 1px solid #ccc; */
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
        background-color: rgba(0, 123, 0, 0.05);
    }

    .profile-dropdown li:hover a {
        color: #007b00;
    }
</style>