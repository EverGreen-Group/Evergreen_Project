<?php
// Define menu items for different roles
$menuItems = [
    // Dashboard is common for all roles
    'profile' => [
      'icon' => 'bxs-user-circle',
      'text' => 'Profile',
      'link' => '/profile',
        'roles' => ['all']
    ],
    'collection' => [
        'icon' => 'bxs-collection',
        'text' => 'Collection',
        'link' => '/vehiclemanager/collection',
        'roles' => ['vehicle_manager'] // test
    ],
    // Vehicle Driver specific
    'shift' => [
        'icon' => 'bxs-time-five',
        'text' => 'Shift',
        'link' => '/vehicledriver/shift',
        'roles' => ['driver']
    ],
    // Vehicle Manager specific
    'vehicles' => [
        'icon' => 'bxs-car',
        'text' => 'Vehicles',
        'link' => '/vehiclemanager/vehicles',
        'roles' => ['vehicle_manager', 'admin']
    ],
    'drivers' => [
        'icon' => 'bxs-user-badge',
        'text' => 'Drivers',
        'link' => '/vehiclemanager/drivers',
        'roles' => ['vehicle_manager']
    ],
    // Supplier specific
    'orders' => [
        'icon' => 'bxs-cart',
        'text' => 'Orders',
        'link' => '/supplier/orders',
        'roles' => ['supplier']
    ],
    // Admin specific
    'vehicle_staff' => [
      'icon' => 'bxs-group',
      'text' => 'Staff',
      'link' => '/vehiclemanager/staff',
        'roles' => ['vehicle_manager', 'admin']
    ],
    'staff' => [
      'icon' => 'bxs-group',
      'text' => 'Staff',
      'link' => '/employeemanager/staff',
        'roles' => ['employee_manager', 'admin']
    ],
    'users' => [
        'icon' => 'bxs-group',
        'text' => 'Users',
        'link' => '/admin/users',
        'roles' => ['admin']
    ],
    // Common bottom menu items
    'settings' => [
        'icon' => 'bxs-cog',
        'text' => 'Settings',
        'link' => '/settings',
        'roles' => ['admin']
    ],
    'logout' => [
        'icon' => 'bxs-log-out-circle',
        'text' => 'Logout',
        'link' => '/auth/logout',
        'roles' => ['all'],
        'class' => 'logout'
    ]
];

// Helper function to check if menu item should be shown for current user
function shouldShowMenuItem($item, $userRole) {
    return in_array('all', $item['roles']) || in_array($userRole, $item['roles']);
}

// Get current user's role
$userRole = RoleHelper::getRole();
$currentPage = basename($_SERVER['REQUEST_URI']);
?>

<section id="sidebar">
    <a href="<?php echo URLROOT; ?>" class="brand">
        <img src="../img/logo.svg" alt="Logo" />
        <span class="text">EVERGREEN</span>
    </a>
    
    <ul class="side-menu top">
        <?php foreach ($menuItems as $key => $item): ?>
            <?php 
            // Skip bottom menu items
            if (in_array($key, ['settings', 'logout'])) continue;
            
            // Check if user has access to this menu item
            if (!shouldShowMenuItem($item, $userRole)) continue;
            ?>
            
            <li class="<?php echo ($currentPage == basename($item['link'])) ? 'active' : ''; ?>">
                <a href="<?php echo URLROOT . $item['link']; ?>" <?php echo isset($item['class']) ? 'class="' . $item['class'] . '"' : ''; ?>>
                    <i class="bx <?php echo $item['icon']; ?>"></i>
                    <span class="text"><?php echo $item['text']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <ul class="side-menu">
        <?php foreach ($menuItems as $key => $item): ?>
            <?php 
            // Only show bottom menu items
            if (!in_array($key, ['settings', 'logout'])) continue;
            
            // Check if user has access to this menu item
            if (!shouldShowMenuItem($item, $userRole)) continue;
            ?>
            
            <li class="<?php echo ($currentPage == basename($item['link'])) ? 'active' : ''; ?>">
                <a href="<?php echo URLROOT . $item['link']; ?>" <?php echo isset($item['class']) ? 'class="' . $item['class'] . '"' : ''; ?>>
                    <i class="bx <?php echo $item['icon']; ?>"></i>
                    <span class="text"><?php echo $item['text']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>