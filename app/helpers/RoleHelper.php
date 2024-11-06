<?php
class RoleHelper {
    // Define role constants
    const ADMIN = 1;
    const SUPPLIER_MANAGER = 2;
    const EMPLOYEE = 3;
    const OPERATOR = 4;
    const SUPPLIER = 5;
    const DRIVER = 6;
    const WEBSITE_USER = 7;
    const DRIVING_PARTNER = 8;
    const EMPLOYEE_MANAGER = 9;
    const VEHICLE_MANAGER = 10;
    const INVENTORY_MANAGER = 11;

    // Check if user has specific role
    public static function hasRole($roleId) {
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == $roleId;
    }

    // Check if user has any of the given roles
    public static function hasAnyRole($roles) {
        return isset($_SESSION['role_id']) && in_array($_SESSION['role_id'], $roles);
    }

    // Get navigation menu based on role
    public static function getNavMenu() {
        if (!isset($_SESSION['role_id'])) {
            return [];
        }

        $menu = [
            // Common menu items for all logged-in users
            'common' => [
                'profile' => [
                    'label' => 'Profile',
                    'url' => URLROOT . '/profile',
                    'icon' => 'bx-user'
                ]
            ],
            
            // Admin menu items
            self::ADMIN => [
                'users' => [
                    'label' => 'User Management',
                    'url' => URLROOT . '/admin/users',
                    'icon' => 'bx-group'
                ],
                'roles' => [
                    'label' => 'Role Management',
                    'url' => URLROOT . '/admin/roles',
                    'icon' => 'bx-key'
                ]
            ],
            
            // Manager menu items
            self::MANAGER => [
                'inventory' => [
                    'label' => 'Inventory',
                    'url' => URLROOT . '/manager/inventory',
                    'icon' => 'bx-box'
                ],
                'reports' => [
                    'label' => 'Reports',
                    'url' => URLROOT . '/manager/reports',
                    'icon' => 'bx-file'
                ]
            ],
            
            // Add other role menus...
        ];

        return $menu;
    }
}
?>