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

    // Check if user is logged in
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Check if user is admin (using role_id)
    public static function isAdmin() {
        return self::hasRole(self::ADMIN);
    }

    // Check if user has specific role
    public static function hasRole($roleId) {
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == $roleId;
    }

    // Check if user has any of the given roles
    public static function hasAnyRole($roles) {
        return isset($_SESSION['role_id']) && in_array($_SESSION['role_id'], $roles);
    }

    // Get current user ID
    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    // Get current user role ID
    public static function getCurrentRoleId() {
        return $_SESSION['role_id'] ?? null;
    }

    // Get current user role name
    public static function getCurrentRoleName() {
        $roleId = self::getCurrentRoleId();
        $roleNames = [
            self::ADMIN => 'Admin',
            self::SUPPLIER_MANAGER => 'Supplier Manager',
            self::EMPLOYEE => 'Employee',
            self::OPERATOR => 'Operator',
            self::SUPPLIER => 'Supplier',
            self::DRIVER => 'Driver',
            self::WEBSITE_USER => 'Website User',
            self::DRIVING_PARTNER => 'Driving Partner',
            self::EMPLOYEE_MANAGER => 'Employee Manager',
            self::VEHICLE_MANAGER => 'Vehicle Manager',
            self::INVENTORY_MANAGER => 'Inventory Manager'
        ];
        return $roleNames[$roleId] ?? 'Unknown Role';
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
            
        ];

        return $menu;
    }

    // Add getRole() method
    public static function getRole() {
        return isset($_SESSION['role_id']) ? $_SESSION['role_id'] : null;
    }

    // New method to return role based on title
    public static function getRoleByTitle($title) {
        switch ($title) {
            case 'Admin':
                return self::ADMIN; // Returns 1
            case 'Supplier Manager':
                return self::SUPPLIER_MANAGER; // Returns 2
            case 'Employee':
                return self::EMPLOYEE; // Returns 3
            case 'Operator':
                return self::OPERATOR; // Returns 4
            case 'Supplier':
                return self::SUPPLIER; // Returns 5
            case 'Driver':
                return self::DRIVER; // Returns 6
            case 'Website User':
                return self::WEBSITE_USER; // Returns 7
            case 'Driving Partner':
                return self::DRIVING_PARTNER; // Returns 8
            case 'Employee Manager':
                return self::EMPLOYEE_MANAGER; // Returns 9
            case 'Vehicle Manager':
                return self::VEHICLE_MANAGER; // Returns 10
            case 'Inventory Manager':
                return self::INVENTORY_MANAGER; // Returns 11
            default:
                return null; // Return null if no matching title is found
        }
    }

    public static function getControllerNameByRole($roleId) {
        // Map role IDs to their controller names
        $controllerMap = [
            6 => 'vehicledriver',    // Driver
            8 => 'drivingpartner',   // Driving Partner
            9 => 'employeemanager',  // Employee Manager
            11 => 'inventorymanager', // Inventory Manager
            10 => 'vehiclemanager',  // Vehicle Manager
            5 => 'supplier',         // Supplier
            2 => 'suppliermanager'   // Supplier Manager
            // ... add other role mappings as needed ...
        ];
        
        return isset($controllerMap[$roleId]) ? $controllerMap[$roleId] : '';
    }

    // Helper function to check permissions
    public static function checkPermission($requiredRoles) {
        if (!self::isLoggedIn()) {
            header('location: ' . URLROOT . '/users/login');
            exit;
        }

        if (!is_array($requiredRoles)) {
            $requiredRoles = [$requiredRoles];
        }

        if (!self::hasAnyRole($requiredRoles)) {
            header('location: ' . URLROOT . '/pages/unauthorized');
            exit;
        }

        return true;
    }
}

// Create global helper functions for commonly used methods
function isLoggedIn() {
    return RoleHelper::isLoggedIn();
}

function isAdmin() {
    return RoleHelper::isAdmin();
}

function hasRole($roleId) {
    return RoleHelper::hasRole($roleId);
}

function getCurrentUserId() {
    return RoleHelper::getCurrentUserId();
}

function getCurrentRoleId() {
    return RoleHelper::getCurrentRoleId();
}
?>