<script src="<?php echo URLROOT; ?>/public/js/notification.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/confirm-dialog.js"></script>
<link href="<?php echo URLROOT; ?>/public/css/notification.css" rel="stylesheet" />
<link href="<?php echo URLROOT; ?>/public/css/confirm-dialog.css" rel="stylesheet" />


<section id="content">
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
        <a href="#" class="notification" id="notificationIcon">
            <i class='bx bxs-bell'></i>
            <span class="num" style="cursor: pointer;">0</span>
        </a>
        <div class="profile-container">
            <a href="#" class="profile">
                <?php
                    $profileImageSrc = URLROOT . '/uploads/supplier_photos/default-supplier.png'; 
                    if (isset($_SESSION['profile_image_path']) && !empty($_SESSION['profile_image_path'])) {
                        $profileImageSrc = URLROOT . '/' . $_SESSION['profile_image_path'];
                    }
                ?>
                <img src="<?php echo $profileImageSrc; ?>" alt="Profile Photo">
            </a>
            <div class="user-name">
                <?php if (isset($_SESSION['full_name'])): ?>
                    <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div id="notificationDropdown" class="notification-dropdown" style="display: none;">
        <ul id="notificationList">
            <li>Loading notifications...</li>
        </ul>
    </div>

    <div id="profileDropdown" class="profile-dropdown" style="display: none;">
        <ul>

            <?php if (RoleHelper::isAdmin()): ?>
                <li><a href="<?php echo URLROOT; ?>/auth/profile">Profile</a></li>
                <li><a href="<?php echo URLROOT; ?>/admin/">Admin Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/">Manager Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/inventory/">Inventory Dashboard</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::MANAGER)): ?>
                <li><a href="<?php echo URLROOT; ?>/auth/profile">Profile</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/index">Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/collection">Collections</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::SUPPLIER)): ?>
                <li><a href="<?php echo URLROOT; ?>/auth/profile">Profile</a></li>
                <li><a href="<?php echo URLROOT; ?>/supplier/index">Dashboard</a></li>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::DRIVER)): ?>
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::WEBSITE_USER)): ?>
                <li><a href="<?php echo URLROOT; ?>/auth/profile">Profile</a></li>  
            <?php endif; ?>

            <?php if (RoleHelper::hasRole(RoleHelper::INVENTORY_MANAGER)): ?>
                <li><a href="<?php echo URLROOT; ?>/auth/profile">Profile</a></li>
                <li><a href="<?php echo URLROOT; ?>/manager/dashboard">Dashboard</a></li>
            <?php endif; ?>

            <li><a href="<?php echo URLROOT; ?>/auth/logout">Logout</a></li>
        </ul>
    </div>
    <!-- NAVBAR -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown functionality
    const profileLink = document.querySelector('.profile');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileLink && profileDropdown) {
        profileLink.addEventListener('click', function(event) {
            event.preventDefault();
            profileDropdown.style.display = profileDropdown.style.display === 'none' || profileDropdown.style.display === '' ? 'block' : 'none';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!profileLink.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.style.display = 'none';
            }
        });
    }

    // Notification dropdown functionality
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationIcon && notificationDropdown) {
        notificationIcon.addEventListener('click', function(event) {
            event.preventDefault();
            notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.style.display = 'none';
            }
        });
    }

    // Load notification count
    loadNotificationCount();
    
    // Load notifications for dropdown
    loadNotifications();
});

function loadNotificationCount() {
    fetch('/Evergreen_Project/notifications/getUnreadNotificationCount')
        .then(response => response.json())
        .then(data => {
            const countElement = document.querySelector('.num');
            if (countElement) {
                countElement.textContent = data.count;
            }
        })
        .catch(error => {
            console.error('Error fetching notification count:', error);
        });
}

function loadNotifications() {
    const userId = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
    if (!userId) return;
    
    fetch(`/Evergreen_Project/notifications/getUnreadUserNotifications/${userId}`)
        .then(response => response.json())
        .then(notifications => {
            const notificationList = document.getElementById('notificationList');
            if (!notificationList) return;
            
            notificationList.innerHTML = '';

            if (notifications.length > 0) {
                notifications.forEach(notification => {
                    const listItem = document.createElement('li');
                    
                    // Safely check if data exists and can be parsed
                    let link = '#';
                    try {
                        // Try to use the link property directly first
                        if (notification.link) {
                            link = notification.link;
                        } 
                        // Fall back to parsing data if needed
                        else if (notification.data) {
                            const data = JSON.parse(notification.data);
                            if (data.link) {
                                link = data.link;
                            }
                        }
                    } catch (e) {
                        console.warn('Could not parse notification data', e);
                    }

                    listItem.innerHTML = `
                        <div class="notification-item" data-id="${notification.id}">
                            <div class="notification-content">
                                ${notification.type ? 
                                  `<h4 class="notification-title" style="color: green; font-weight: bold;">${notification.type}</h4>` : ''}
                                <p class="notification-description">
                                    ${notification.message}
                                </p>
                            </div>
                            <div class="notification-meta">
                                <a href="${link}" class="notification-action" onclick="handleNotificationClick(event, ${notification.id}, '${link}')">View</a>
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
            const notificationList = document.getElementById('notificationList');
            if (notificationList) {
                notificationList.innerHTML = '<li>Error loading notifications.</li>';
            }
        });
}

function markAsRead(event, notificationId) {
    event.preventDefault();
    
    fetch(`/Evergreen_Project/notifications/markAsRead/${notificationId}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            console.log('Notification marked as read');
            
            // Remove from UI
            const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationItem && notificationItem.parentElement) {
                notificationItem.parentElement.remove();
            }
            
            // Update count
            loadNotificationCount();
            
            // Check if there are no more notifications
            const notificationList = document.getElementById('notificationList');
            if (notificationList && notificationList.children.length === 0) {
                notificationList.innerHTML = '<li>No notifications available.</li>';
            }
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function handleNotificationClick(event, notificationId, link) {
    event.preventDefault();

    if (!link || link === '#') return;

    const baseUrl = '<?php echo URLROOT; ?>';
    const finalLink = `${baseUrl}/${link}`;

    fetch(`${baseUrl}/notifications/markAsRead/${notificationId}`, {
        method: 'POST'
    })
    .then(() => {
        window.location.href = finalLink;
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        window.location.href = finalLink; 
    });
}


</script>

<!-- fLash part -->
<?php if(isset($_SESSION['flash_message'])): ?>
    <div id="php-flash-message" 
         data-message="<?php echo htmlspecialchars($_SESSION['flash_message']); ?>"
         data-type="<?php echo htmlspecialchars($_SESSION['flash_type'] ?? 'success'); ?>"
         style="display: none;"></div>
    <?php 
    // Clear after rendering
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
    ?>
<?php endif; ?>

<!-- Notification container -->
<div id="notification-container"></div>